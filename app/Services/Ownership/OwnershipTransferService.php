<?php

namespace App\Services\Ownership;

use App\Models\AuditLog;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\OwnershipHistory;
use App\Models\RepairOrder;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;

/**
 * سرویس انتقال مالکیت طلا و جواهر.
 *
 * ── تضمین‌های صحت و امنیت ────────────────────────────────────────────
 *
 * ۱) تراکنش دیتابیس — تمام تغییرات (ثبت درخواست، تغییر مالک، ثبت دفتر کل)
 *    در یک `DB::transaction` انجام می‌شود. اگر هر مرحله شکست بخورد، همه
 *    چیز به حالت قبل برمی‌گردد.
 *
 * ۲) قفل‌گذاری ردیف (`lockForUpdate`) — هنگام تأیید نهایی، رکورد شناسنامه
 *    با قفل انحصاری خوانده می‌شود تا دو درخواست هم‌زمان نتوانند مالکیت را
 *    هم‌زمان تغییر دهند (رفع Condition Race).
 *
 * ۳) بررسی مجدد مالکیت — مالک فعلی *داخل تراکنش* دوباره خوانده و با مالک
 *    ثبت‌شده در درخواست مقایسه می‌شود. اگر در فاصله ثبت تا تأیید تغییر
 *    کرده باشد، تأیید رد می‌شود.
 *
 * ۴) جلوگیری از درخواست تکراری — نمی‌توان برای یک قطعه بیش از یک درخواست
 *    «در انتظار تأیید» ثبت کرد. این با قفل ردیف + بررسی داخل تراکنش
 *    محقق می‌شود (نه فقط با بررسی در لایه کنترلر).
 *
 * ۵) جلوگیری از ثبت دو مالک فعلی — با `UNIQUE(certificate_id, sequence_no)`
 *    روی دفتر کل و به‌روزرسانی اتمیک `current_owner_id` (که در
 *    `ownership_history` نگه داشته می‌شود) تضمین می‌گردد.
 *
 * ۶) تفکیک وظایف — تأییدکننده نباید خودِ درخواست‌دهنده باشد.
 *
 * ۷) Audit Log — هر مرحله ثبت می‌شود.
 */
class OwnershipTransferService
{
    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    public function __construct(AuditLogger $audit)
    {
        $this->audit = $audit;
    }

    // =========================================================
    // ثبت درخواست انتقال
    // =========================================================

    /**
     * ثبت درخواست انتقال مالکیت.
     *
     * درخواست در وضعیت `pending` ثبت می‌شود و مالکیت **تغییر نمی‌کند** تا
     * زمانی که تأیید شود. این مهم است: پیش از این اصلاح، انتقال بی‌درنگ
     * نهایی می‌شد بدون هیچ تأییدی.
     *
     * @param  array    $data  شامل: certificate_id, to_owner_id, transfer_date,
     *                         transfer_type, notes, price, contract_file
     * @param  \App\User $actor کاربر ثبت‌کننده درخواست
     * @return \App\Models\JewelryTransfer
     *
     * @throws \RuntimeException در صورت بروز تخلف قاعده کسب‌وکار
     */
    public function requestTransfer(array $data, $actor)
    {
        return DB::transaction(function () use ($data, $actor) {

            // ---------------------------------------------------
            // ۱) قفل‌گذاری شناسنامه برای جلوگیری از ثبت هم‌زمان
            // ---------------------------------------------------
            $certificate = JewelryCertificate::query()
                ->whereKey($data['certificate_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // ---------------------------------------------------
            // ۲) بررسی وجود درخواست در انتظار برای همین قطعه
            //    (داخل تراکنش و پس از قفل — شرایط رقابتی پوشش داده می‌شود)
            // ---------------------------------------------------
            $existingPending = JewelryTransfer::query()
                ->forCertificate($certificate->getKey())
                ->pending()
                ->first();

            if ($existingPending !== null) {
                throw new \RuntimeException(
                    'برای این قطعه یک درخواست انتقال در انتظار تأیید وجود دارد. '
                    . 'پیش از تصمیم‌گیری درباره آن، امکان ثبت درخواست جدید نیست.'
                );
            }

            // ---------------------------------------------------
            // ۳) اعتبارسنجی مالک مقصد
            // ---------------------------------------------------
            $toOwner = JewelryOwner::query()->findOrFail($data['to_owner_id']);
            $fromOwnerId = $certificate->owner_id;

            if ((int) $toOwner->getKey() === (int) $fromOwnerId) {
                throw new \RuntimeException('مالک مقصد با مالک فعلی یکسان است.');
            }

            // ---------------------------------------------------
            // ۴) ثبت درخواست در وضعیت pending
            // ---------------------------------------------------
            $transfer = JewelryTransfer::create([
                'certificate_id' => $certificate->getKey(),
                'from_owner_id'  => $fromOwnerId,
                'to_owner_id'    => $toOwner->getKey(),
                'transfer_date'  => $data['transfer_date'],
                'transfer_type'  => $data['transfer_type'],
                'notes'          => isset($data['notes']) ? $data['notes'] : null,
                'price'          => isset($data['price']) ? $data['price'] : null,
                'contract_file'  => isset($data['contract_file']) ? $data['contract_file'] : null,
            ]);

            // ستون‌هایی که عمداً در $fillable نیستند — فقط از این مسیر تنظیم می‌شوند
            $transfer->forceFill([
                'status'       => JewelryTransfer::STATUS_PENDING,
                'requested_by' => $actor->getKey(),
            ])->save();

            // ---------------------------------------------------
            // ۵) ثبت رخداد
            // ---------------------------------------------------
            $this->audit->logModelChange(
                AuditLog::EVENT_TRANSFER_REQUESTED,
                $transfer,
                [],
                [
                    'status'         => JewelryTransfer::STATUS_PENDING,
                    'certificate_id' => $certificate->getKey(),
                    'from_owner_id'  => $fromOwnerId,
                    'to_owner_id'    => $toOwner->getKey(),
                ],
                'ثبت درخواست انتقال مالکیت قطعه ' . $certificate->serial_number
            );

            return $transfer->fresh();
        });
    }

    // =========================================================
    // تأیید و نهایی‌سازی انتقال
    // =========================================================

    /**
     * تأیید درخواست انتقال و نهایی‌سازی مالکیت.
     *
     * @param  int       $transferId
     * @param  \App\User $actor
     * @param  string|null $note
     * @return \App\Models\JewelryTransfer
     *
     * @throws \RuntimeException
     */
    public function approve($transferId, $actor, $note = null)
    {
        return DB::transaction(function () use ($transferId, $actor, $note) {

            // ---------------------------------------------------
            // ۱) قفل درخواست + قفل شناسنامه (ترتیب ثابت قفل‌گیری
            //    برای پرهیز از Deadlock در درخواست‌های هم‌زمان)
            // ---------------------------------------------------
            $transfer = JewelryTransfer::query()
                ->whereKey($transferId)
                ->lockForUpdate()
                ->firstOrFail();

            $certificate = JewelryCertificate::query()
                ->whereKey($transfer->certificate_id)
                ->lockForUpdate()
                ->firstOrFail();

            // ---------------------------------------------------
            // ۲) بررسی وضعیت درخواست
            // ---------------------------------------------------
            if (!$transfer->isPending()) {
                throw new \RuntimeException(
                    'این درخواست قبلاً ' . $transfer->status_label . ' شده است.'
                );
            }

            // ---------------------------------------------------
            // ۳) تفکیک وظایف: تأییدکننده ≠ درخواست‌دهنده
            // ---------------------------------------------------
            if ((int) $transfer->requested_by === (int) $actor->getKey()) {
                throw new \RuntimeException(
                    'درخواست‌دهنده نمی‌تواند درخواست خود را تأیید کند. '
                    . 'تأیید باید توسط کاربر دیگری انجام شود.'
                );
            }

            // ---------------------------------------------------
            // ۴) بررسی مجدد مالک فعلی (داخل تراکنش)
            //    اگر مالکیت در فاصله ثبت تا تأیید تغییر کرده باشد → رد
            // ---------------------------------------------------
            $currentOwnerId = $certificate->owner_id;

            if ((int) $currentOwnerId !== (int) $transfer->from_owner_id) {
                throw new \RuntimeException(
                    'مالکیت این قطعه از زمان ثبت درخواست تغییر کرده است. '
                    . 'درخواست باید لغو و از نو ثبت شود.'
                );
            }

            // ---------------------------------------------------
            // ۵) بررسی اینکه مالک مقصد هنوز معتبر است
            // ---------------------------------------------------
            $toOwner = JewelryOwner::query()->find($transfer->to_owner_id);

            if ($toOwner === null) {
                throw new \RuntimeException('مالک مقصد دیگر در سیستم وجود ندارد.');
            }

            // مالک فعلی — پیش از هر تغییری نگه داشته می‌شود
            $oldOwnerId = $certificate->owner_id;

            // ---------------------------------------------------
            // ۶) تعیین شماره ترتیب مالکیت
            //    UNIQUE(certificate_id, sequence_no) بعنوان محافظ نهایی
            // ---------------------------------------------------
            $lastSequence = OwnershipHistory::query()
                ->forCertificate($certificate->getKey())
                ->max('sequence_no');

            // ---------------------------------------------------
            // ۶-الف) تکمیل سابقه اولیه در صورت نبود رکورد
            //
            // اگر قطعه‌ای پیش از فعال شدن ماژول مالکیت ثبت شده باشد،
            // دفتر کل آن خالی است. در این حالت پیش از ثبت انتقال، ابتدا
            // رکورد «مالکیت اولیه» را می‌سازیم تا زنجیره مالکان از ابتدا
            // کامل و قابل ردیابی باشد (نیازمندی: تمام مالکان پیشین).
            //
            // این عملیات هیچ داده‌ای را حذف یا بازنویسی نمی‌کند و تنها
            // یک رکورد افزودنی است.
            // ---------------------------------------------------
            if ($lastSequence === null && $oldOwnerId !== null) {
                $this->recordInitialOwnership($certificate, $actor);

                $lastSequence = 1;
            }

            $nextSequence = (int) $lastSequence + 1;

            // ---------------------------------------------------
            // ۷) تغییر مالک رسمی — تنها نقطه‌ای که مالکیت عوض می‌شود
            // ---------------------------------------------------
            $certificate->owner_id = $transfer->to_owner_id;
            $certificate->save();

            // ---------------------------------------------------
            // ۸) ثبت رکورد تغییرناپذیر در دفتر کل
            // ---------------------------------------------------
            $history = OwnershipHistory::create([
                'certificate_id' => $certificate->getKey(),
                'from_owner_id'  => $oldOwnerId,
                'to_owner_id'    => $transfer->to_owner_id,
                'transfer_id'    => $transfer->getKey(),
                'sequence_no'    => $nextSequence,
                'event_type'     => $this->mapTransferTypeToEvent($transfer->transfer_type),
                'notes'          => $transfer->notes,
                'declared_value' => $transfer->price,
                'recorded_by'    => $actor->getKey(),
                'effective_at'   => now(),
            ]);

            // ---------------------------------------------------
            // ۹) به‌روزرسانی وضعیت درخواست
            // ---------------------------------------------------
            $transfer->forceFill([
                'status'        => JewelryTransfer::STATUS_APPROVED,
                'approved_by'   => $actor->getKey(),
                'approved_at'   => now(),
                'approval_note' => $note,
            ])->save();

            // ---------------------------------------------------
            // ۱۰) ثبت رخدادها
            // ---------------------------------------------------
            $this->audit->logModelChange(
                AuditLog::EVENT_TRANSFER_APPROVED,
                $transfer,
                ['status' => JewelryTransfer::STATUS_PENDING],
                ['status' => JewelryTransfer::STATUS_APPROVED],
                'تأیید انتقال مالکیت قطعه ' . $certificate->serial_number
            );

            $this->audit->log(
                AuditLog::EVENT_OWNERSHIP_CHANGED,
                'تغییر مالکیت قطعی قطعه ' . $certificate->serial_number
                    . ' — از مالک #' . $oldOwnerId . ' به مالک #' . $transfer->to_owner_id,
                $certificate,
                ['owner_id' => $oldOwnerId],
                ['owner_id' => $transfer->to_owner_id, 'sequence_no' => $nextSequence],
                AuditLog::SEVERITY_CRITICAL
            );

            return $transfer->fresh();
        });
    }

    // =========================================================
    // رد درخواست
    // =========================================================

    /**
     * رد درخواست انتقال.
     *
     * @param  int         $transferId
     * @param  \App\User   $actor
     * @param  string      $reason  دلیل رد — الزامی
     * @return \App\Models\JewelryTransfer
     *
     * @throws \RuntimeException
     */
    public function reject($transferId, $actor, $reason)
    {
        if (trim((string) $reason) === '') {
            throw new \RuntimeException('ثبت دلیل برای رد درخواست الزامی است.');
        }

        return DB::transaction(function () use ($transferId, $actor, $reason) {

            $transfer = JewelryTransfer::query()
                ->whereKey($transferId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$transfer->isPending()) {
                throw new \RuntimeException(
                    'این درخواست قبلاً ' . $transfer->status_label . ' شده است.'
                );
            }

            if ((int) $transfer->requested_by === (int) $actor->getKey()) {
                throw new \RuntimeException('درخواست‌دهنده نمی‌تواند درخواست خود را رد کند.');
            }

            $transfer->forceFill([
                'status'        => JewelryTransfer::STATUS_REJECTED,
                'approved_by'   => $actor->getKey(),
                'approved_at'   => now(),
                'approval_note' => $reason,
            ])->save();

            $this->audit->logModelChange(
                AuditLog::EVENT_TRANSFER_REJECTED,
                $transfer,
                ['status' => JewelryTransfer::STATUS_PENDING],
                ['status' => JewelryTransfer::STATUS_REJECTED, 'reason' => $reason],
                'رد درخواست انتقال مالکیت — دلیل: ' . $reason
            );

            return $transfer->fresh();
        });
    }

    // =========================================================
    // لغو درخواست
    // =========================================================

    /**
     * لغو درخواست انتقال.
     *
     * @param  int       $transferId
     * @param  \App\User $actor
     * @param  string|null $reason
     * @return \App\Models\JewelryTransfer
     *
     * @throws \RuntimeException
     */
    public function cancel($transferId, $actor, $reason = null)
    {
        return DB::transaction(function () use ($transferId, $actor, $reason) {

            $transfer = JewelryTransfer::query()
                ->whereKey($transferId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$transfer->isPending()) {
                throw new \RuntimeException(
                    'تنها درخواست‌های در انتظار تأیید قابل لغو هستند. '
                    . 'وضعیت فعلی: ' . $transfer->status_label
                );
            }

            // درخواست‌دهنده همیشه می‌تواند درخواست خودش را لغو کند؛
            // دیگران نیازمند مجوز cancel هستند (بررسی در Policy انجام شده است).
            if ((int) $transfer->requested_by !== (int) $actor->getKey()
                && !$actor->hasPermissionTo('jewelry.transfers.cancel')) {
                throw new \RuntimeException('شما مجوز لغو این درخواست را ندارید.');
            }

            $transfer->forceFill([
                'status'        => JewelryTransfer::STATUS_CANCELLED,
                'approved_by'   => $actor->getKey(),
                'approved_at'   => now(),
                'approval_note' => $reason,
            ])->save();

            $this->audit->logModelChange(
                AuditLog::EVENT_TRANSFER_CANCELLED,
                $transfer,
                ['status' => JewelryTransfer::STATUS_PENDING],
                ['status' => JewelryTransfer::STATUS_CANCELLED],
                'لغو درخواست انتقال مالکیت'
            );

            return $transfer->fresh();
        });
    }

    // =========================================================
    // ثبت مالکیت اولیه
    // =========================================================

    /**
     * ثبت رکورد «مالکیت اولیه» برای یک شناسنامه تازه‌ساخته‌شده.
     *
     * بدون این رکورد، تاریخچه مالکیت یک قطعه ناقص می‌ماند و مالک اول
     * در دفتر کل ثبت نمی‌شود.
     *
     * @param  \App\Models\JewelryCertificate $certificate
     * @param  \App\User                      $actor
     * @return \App\Models\OwnershipHistory|null
     */
    public function recordInitialOwnership(JewelryCertificate $certificate, $actor)
    {
        if ($certificate->owner_id === null) {
            return null;
        }

        return DB::transaction(function () use ($certificate, $actor) {

            // اگر رکورد اولیه از قبل ثبت شده، دوباره نساز
            $exists = OwnershipHistory::query()
                ->forCertificate($certificate->getKey())
                ->where('sequence_no', 1)
                ->exists();

            if ($exists) {
                return null;
            }

            return OwnershipHistory::create([
                'certificate_id' => $certificate->getKey(),
                'from_owner_id'  => null,
                'to_owner_id'    => $certificate->owner_id,
                'transfer_id'    => null,
                'sequence_no'    => 1,
                'event_type'     => OwnershipHistory::EVENT_INITIAL,
                'notes'          => 'ثبت اولیه مالکیت هنگام صدور شناسنامه',
                'recorded_by'    => $actor ? $actor->getKey() : null,
                'effective_at'   => $certificate->issued_at ?: now(),
            ]);
        });
    }

    // =========================================================
    // گزارش‌گیری
    // =========================================================

    /**
     * تاریخچه کامل مالکیت یک قطعه.
     *
     * @param  int $certificateId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function historyFor($certificateId)
    {
        return OwnershipHistory::query()
            ->forCertificate($certificateId)
            ->with(['fromOwner', 'toOwner', 'recorder', 'transfer'])
            ->orderBy('sequence_no')
            ->get();
    }

    /**
     * مالک فعلی بر اساس دفتر کل.
     *
     * ⚠ نکته: منبع حقیقت مالکیت، ستون `jewelry_certificates.owner_id` است
     * که تنها از طریق متد `approve()` تغییر می‌کند. این متد برای
     * «بازبینی صحت» استفاده می‌شود.
     *
     * @param  int $certificateId
     * @return \App\Models\JewelryOwner|null
     */
    public function currentOwnerFromLedger($certificateId)
    {
        $latest = OwnershipHistory::query()
            ->forCertificate($certificateId)
            ->orderByDesc('sequence_no')
            ->first();

        return $latest ? $latest->toOwner : null;
    }

    /**
     * بررسی یکپارچگی: آیا مالک ثبت‌شده در شناسنامه با دفتر کل هم‌خوان است؟
     *
     * @param  \App\Models\JewelryCertificate $certificate
     * @return array  ['consistent' => bool, 'certificate_owner_id' => int|null, 'ledger_owner_id' => int|null]
     */
    public function checkConsistency(JewelryCertificate $certificate)
    {
        $ledgerOwner = $this->currentOwnerFromLedger($certificate->getKey());

        $ledgerOwnerId = $ledgerOwner ? $ledgerOwner->getKey() : null;
        $certificateOwnerId = $certificate->owner_id;

        return [
            'consistent'           => (int) $ledgerOwnerId === (int) $certificateOwnerId,
            'certificate_owner_id' => $certificateOwnerId,
            'ledger_owner_id'      => $ledgerOwnerId,
        ];
    }

    // =========================================================
    // کمکی
    // =========================================================

    /**
     * نگاشت نوع انتقال به نوع رخداد دفتر کل.
     *
     * @param  string|null $transferType
     * @return string
     */
    protected function mapTransferTypeToEvent($transferType)
    {
        switch ($transferType) {
            case 'فروش':
                return OwnershipHistory::EVENT_SALE;
            case 'هدیه':
                return OwnershipHistory::EVENT_GIFT;
            case 'ارث':
                return OwnershipHistory::EVENT_INHERITANCE;
            case 'سایر':
            default:
                return OwnershipHistory::EVENT_OTHER;
        }
    }
}
