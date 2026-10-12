<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * مدل انتقال/درخواست انتقال مالکیت (Jewelry Transfer).
 *
 * ── تصمیم معماری ────────────────────────────────────────────────────
 * این جدول از قبل در پروژه وجود داشت و فقط انتقال‌های نهایی‌شده را
 * ذخیره می‌کرد. طبق دستور «از ایجاد ساختار تکراری جلوگیری کن»، به‌جای
 * ساخت جدول جدید `ownership_transfer_requests`، همین جدول را با ستون‌های
 * گردش‌کار توسعه دادیم:
 *
 *   status, requested_by, approved_by, approval_note, approved_at,
 *   price, contract_file
 *
 * بنابراین این مدل اکنون «درخواست انتقال» را نمایندگی می‌کند و پس از
 * تأیید، یک رکورد تغییرناپذیر در `ownership_history` ثبت می‌شود.
 *
 * @property int    $id
 * @property int    $certificate_id
 * @property int    $from_owner_id
 * @property int    $to_owner_id
 * @property string $status
 */
class JewelryTransfer extends Model
{
    protected $table = 'jewelry_transfers';

    // وضعیت‌های درخواست انتقال
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * فهرست وضعیت‌های مجاز — برای اعتبارسنجی و جلوگیری از مقدار جعلی.
     *
     * @return array
     */
    public static function statuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * برچسب فارسی وضعیت‌ها.
     *
     * @return array
     */
    public static function statusLabels()
    {
        return [
            self::STATUS_PENDING   => 'در انتظار تأیید',
            self::STATUS_APPROVED  => 'تأییدشده',
            self::STATUS_REJECTED  => 'ردشده',
            self::STATUS_CANCELLED => 'لغوشده',
        ];
    }

    /**
     * انواع انتقال با برچسب فارسی.
     *
     * @return array
     */
    public static function transferTypes()
    {
        return [
            'فروش' => 'فروش',
            'هدیه' => 'هدیه',
            'ارث'  => 'ارث',
            'سایر' => 'سایر',
        ];
    }

    /**
     * فیلدهای مجاز برای تخصیص انبوه.
     *
     * ── رفع باگ ────────────────────────────────────────────────────
     * پیش از این `price` و `contract_file` در `$fillable` نبودند، در حالی
     * که کنترلر آن‌ها را در `create()` می‌فرستاد. نتیجه: مقادیر مالی و
     * مدرک قرارداد «بی‌سروصدا» دور ریخته می‌شدند.
     *
     * ستون‌های `status`, `requested_by`, `approved_by` عمداً در فهرست
     * نیستند تا از طریق تخصیص انبوه قابل دستکاری نباشند؛ فقط سرویس
     * اختصاصی مالکیت آن‌ها را تنظیم می‌کند.
     */
    protected $fillable = [
        'certificate_id',
        'from_owner_id',
        'to_owner_id',
        'transfer_date',
        'transfer_type',
        'notes',
        'price',
        'contract_file',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'approved_at'   => 'datetime',
        'price'         => 'float',
    ];

    // =========================================================
    // روابط
    // =========================================================

    public function certificate()
    {
        return $this->belongsTo(JewelryCertificate::class, 'certificate_id');
    }

    public function fromOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'from_owner_id');
    }

    public function toOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'to_owner_id');
    }

    public function requester()
    {
        return $this->belongsTo(\App\User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }

    /**
     * رکورد دفتر کل مالکیت متناظر با این انتقال.
     */
    public function ownershipHistory()
    {
        return $this->hasOne(OwnershipHistory::class, 'transfer_id');
    }

    // =========================================================
    // متدهای دامنه
    // =========================================================

    /**
     * آیا درخواست هنوز در انتظار تصمیم‌گیری است؟
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * آیا درخواست نهایی شده است؟
     *
     * @return bool
     */
    public function isFinalized()
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ], true);
    }

    /**
     * برچسب فارسی وضعیت.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = self::statusLabels();

        return $labels[$this->status] ?? (string) $this->status;
    }

    /**
     * رنگ Bootstrap متناظر با وضعیت.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_PENDING   => 'warning',
            self::STATUS_APPROVED  => 'success',
            self::STATUS_REJECTED  => 'danger',
            self::STATUS_CANCELLED => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeForCertificate($query, $certificateId)
    {
        return $query->where('certificate_id', $certificateId);
    }
}
