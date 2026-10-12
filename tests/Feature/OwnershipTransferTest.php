<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\OwnershipHistory;
use App\Models\Role;
use App\Services\Ownership\OwnershipTransferService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * تست‌های ماژول انتقال مالکیت.
 *
 * پوشش:
 *   • گردش‌کار Pending → Approved/Rejected/Cancelled
 *   • انتقال مالکیت فقط پس از تأیید نهایی رخ می‌دهد
 *   • تفکیک وظایف: تأییدکننده ≠ درخواست‌دهنده
 *   • جلوگیری از درخواست تکراری برای یک قطعه
 *   • جلوگیری از ثبت مالک مقصد یکسان با مالک فعلی
 *   • ثبت رکورد تغییرناپذیر در دفتر کل مالکیت
 *   • بررسی مجدد مالکیت هنگام تأیید (شرایط رقابتی)
 *   • ثبت در Audit Log
 */
class OwnershipTransferTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \App\Services\Ownership\OwnershipTransferService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OwnershipTransferService::class);
    }

    /**
     * ساخت کاربر آزمایشی.
     */
    protected function makeUser($name = 'کاربر تست')
    {
        $user = new User();
        $user->name      = $name;
        $user->email     = 'u' . uniqid() . '@example.com';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = 'customer';
        $user->role      = 'customer';
        $user->save();

        return $user;
    }

    /**
     * ساخت مالک متصل به کاربر.
     */
    protected function makeOwner(User $user, $fullName = 'مالک تست')
    {
        $owner = new JewelryOwner();
        $owner->user_id    = $user->getKey();
        $owner->full_name  = $fullName;
        $owner->national_id = null;
        $owner->phone      = '09120000000';
        $owner->save();

        return $owner;
    }

    /**
     * ساخت شناسنامه برای یک مالک.
     */
    protected function makeCertificate(JewelryOwner $owner, $serial = null)
    {
        $certificate = new JewelryCertificate();
        $certificate->owner_id      = $owner->getKey();
        $certificate->product_name  = 'دستبند طلا';
        $certificate->metal_type    = 'طلا';
        $certificate->purity        = 750;
        $certificate->weight        = 12.5;
        $certificate->serial_number = $serial ?: 'TST-' . strtoupper(uniqid());
        $certificate->issued_at     = now();
        $certificate->is_verified   = 1;
        $certificate->save();

        return $certificate;
    }

    // =========================================================
    // ۱) ثبت درخواست
    // =========================================================

    /** @test */
    public function requesting_transfer_does_not_change_ownership_immediately()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);
        $actor  = $this->makeUser('ثبت‌کننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);

        // درخواست در وضعیت pending است
        $this->assertEquals(JewelryTransfer::STATUS_PENDING, $transfer->status);

        // مالکیت هنوز تغییر نکرده — این مهم‌ترین تضمین است
        $this->assertEquals($ownerA->getKey(), $cert->fresh()->owner_id,
            'مالکیت نباید تا پیش از تأیید نهایی تغییر کند.');

        // دفتر کل باید فقط رکورد «مالکیت اولیه» را داشته باشد؛
        // هیچ رکورد مرتبط با انتقالی درج نشده است.
        $this->assertEquals(0, OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->whereNotNull('transfer_id')
            ->count(),
            'درخواست انتقال نباید هیچ رکورد انتقالی در دفتر کل ثبت کند.');

        $this->assertEquals(1, OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->count(),
            'تنها رکورد مالکیت اولیه باید در دفتر کل وجود داشته باشد.');
    }

    /** @test */
    public function cannot_create_duplicate_pending_request_for_same_certificate()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $ownerC = $this->makeOwner($this->makeUser('مالک ج'));
        $cert   = $this->makeCertificate($ownerA);
        $actor  = $this->makeUser('ثبت‌کننده');

        $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('در انتظار تأیید وجود دارد');

        $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerC->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);
    }

    /** @test */
    public function cannot_transfer_certificate_to_its_current_owner()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $cert   = $this->makeCertificate($ownerA);
        $actor  = $this->makeUser('ثبت‌کننده');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('مالک مقصد با مالک فعلی یکسان است');

        $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerA->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);
    }

    // =========================================================
    // ۲) تأیید و نهایی‌سازی
    // =========================================================

    /** @test */
    public function approving_transfer_changes_ownership_and_writes_ledger()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
            'price'          => 50000000,
        ], $requester);

        $approved = $this->service->approve($transfer->getKey(), $approver, 'تأیید شد');

        // وضعیت تأییدشده
        $this->assertEquals(JewelryTransfer::STATUS_APPROVED, $approved->status);
        $this->assertEquals($approver->getKey(), $approved->approved_by);
        $this->assertNotNull($approved->approved_at);

        // مالکیت تغییر کرده
        $this->assertEquals($ownerB->getKey(), $cert->fresh()->owner_id);

        // رکورد دفتر کل ثبت شده
        $ledger = OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->orderBy('sequence_no')
            ->get();

        // ۲ رکورد: یکی ثبت اولیه، یکی انتقال
        $this->assertGreaterThanOrEqual(2, $ledger->count());

        $last = $ledger->last();
        $this->assertEquals($ownerA->getKey(), $last->from_owner_id);
        $this->assertEquals($ownerB->getKey(), $last->to_owner_id);
        $this->assertEquals($transfer->getKey(), $last->transfer_id);
        $this->assertEquals(OwnershipHistory::EVENT_SALE, $last->event_type);
        $this->assertEquals($approver->getKey(), $last->recorded_by);
        $this->assertNotNull($last->effective_at);
    }

    /** @test */
    public function requester_cannot_approve_own_request()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);
        $actor  = $this->makeUser('درخواست‌دهنده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('نمی‌تواند درخواست خود را تأیید کند');

        $this->service->approve($transfer->getKey(), $actor);
    }

    /** @test */
    public function approving_stale_request_fails_when_ownership_changed()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $ownerC = $this->makeOwner($this->makeUser('مالک ج'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        // مالکیت به‌صورت جانبی تغییر می‌کند (شبیه‌سازی تغییر مستقیم یا انتقال دیگر)
        $cert->owner_id = $ownerC->getKey();
        $cert->save();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('مالکیت این قطعه از زمان ثبت درخواست تغییر کرده است');

        $this->service->approve($transfer->getKey(), $approver);
    }

    /** @test */
    public function cannot_approve_already_finalized_transfer()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        $this->service->approve($transfer->getKey(), $approver);

        $this->expectException(\RuntimeException::class);
        $this->service->approve($transfer->getKey(), $approver);
    }

    // =========================================================
    // ۳) رد و لغو
    // =========================================================

    /** @test */
    public function rejecting_transfer_keeps_ownership_unchanged()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        $rejected = $this->service->reject($transfer->getKey(), $approver, 'مدارک ناقص است');

        $this->assertEquals(JewelryTransfer::STATUS_REJECTED, $rejected->status);
        $this->assertEquals('مدارک ناقص است', $rejected->approval_note);

        // مالکیت تغییر نکرده
        $this->assertEquals($ownerA->getKey(), $cert->fresh()->owner_id);

        // هیچ رکوردی در دفتر کل درج نشده
        $this->assertEquals(0, OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->whereNotNull('transfer_id')
            ->count());
    }

    /** @test */
    public function rejection_requires_a_reason()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('ثبت دلیل برای رد درخواست الزامی است');

        $this->service->reject($transfer->getKey(), $approver, '   ');
    }

    /** @test */
    public function requester_can_cancel_own_pending_request()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);
        $actor  = $this->makeUser('درخواست‌دهنده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $actor);

        $cancelled = $this->service->cancel($transfer->getKey(), $actor, 'منصرف شدم');

        $this->assertEquals(JewelryTransfer::STATUS_CANCELLED, $cancelled->status);
        $this->assertEquals($ownerA->getKey(), $cert->fresh()->owner_id);
    }

    /** @test */
    public function cannot_cancel_finalized_transfer()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        $this->service->approve($transfer->getKey(), $approver);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('تنها درخواست‌های در انتظار تأیید قابل لغو هستند');

        $this->service->cancel($transfer->getKey(), $requester);
    }

    // =========================================================
    // ۴) تغییرناپذیری سابقه
    // =========================================================

    /** @test */
    public function ownership_history_cannot_be_updated()
    {
        $owner = $this->makeOwner($this->makeUser('مالک'));
        $cert  = $this->makeCertificate($owner);

        // رکورد مالکیت اولیه توسط Observer هنگام ساخت شناسنامه درج شده است
        $record = OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->orderBy('sequence_no')
            ->first();

        $this->assertNotNull($record, 'شناسنامه باید رکورد مالکیت اولیه داشته باشد.');
        $this->assertEquals(1, $record->sequence_no);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('قابل ویرایش نیست');

        $record->notes = 'تلاش برای بازنویسی';
        $record->save();
    }

    /** @test */
    public function ownership_history_cannot_be_deleted()
    {
        $owner = $this->makeOwner($this->makeUser('مالک'));
        $cert  = $this->makeCertificate($owner);

        $record = OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->orderBy('sequence_no')
            ->first();

        $this->assertNotNull($record);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('حذف سابقه مالکیت مجاز نیست');

        $record->delete();
    }

    /** @test */
    public function new_certificate_gets_initial_ownership_record()
    {
        $owner = $this->makeOwner($this->makeUser('مالک اول'));
        $cert  = $this->makeCertificate($owner);

        $ledger = OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->get();

        $this->assertCount(1, $ledger,
            'هنگام صدور شناسنامه باید یک رکورد مالکیت اولیه ثبت شود.');

        $initial = $ledger->first();
        $this->assertEquals(1, $initial->sequence_no);
        $this->assertEquals(OwnershipHistory::EVENT_INITIAL, $initial->event_type);
        $this->assertEquals($owner->getKey(), $initial->to_owner_id);
        $this->assertNull($initial->from_owner_id);
    }

    // =========================================================
    // ۵) دفتر کل و صحت داده
    // =========================================================

    /** @test */
    public function ledger_sequence_numbers_are_unique_per_certificate()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $ownerC = $this->makeOwner($this->makeUser('مالک ج'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        // انتقال اول
        $t1 = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);
        $this->service->approve($t1->getKey(), $approver);

        // انتقال دوم
        $t2 = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerC->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'هدیه',
        ], $requester);
        $this->service->approve($t2->getKey(), $approver);

        $sequences = OwnershipHistory::query()
            ->where('certificate_id', $cert->getKey())
            ->orderBy('sequence_no')
            ->pluck('sequence_no')
            ->all();

        // هیچ شماره تکراری نباید وجود داشته باشد
        $this->assertEquals(count($sequences), count(array_unique($sequences)),
            'شماره ترتیب مالکیت باید یکتا باشد.');

        // مالک نهایی
        $this->assertEquals($ownerC->getKey(), $cert->fresh()->owner_id);
    }

    /** @test */
    public function consistency_check_detects_ledger_mismatch()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $ownerC = $this->makeOwner($this->makeUser('مالک ج'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $t = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);
        $this->service->approve($t->getKey(), $approver);

        // هم‌خوانی برقرار است
        $result = $this->service->checkConsistency($cert->fresh());
        $this->assertTrue($result['consistent']);

        // اعمال تغییر مستقیم (شبیه‌سازی دستکاری)
        $cert->owner_id = $ownerC->getKey();
        $cert->save();

        $result = $this->service->checkConsistency($cert->fresh());
        $this->assertFalse($result['consistent'],
            'بررسی یکپارچگی باید مغایرت بین شناسنامه و دفتر کل را تشخیص دهد.');
    }

    // =========================================================
    // ۶) Audit Log
    // =========================================================

    /** @test */
    public function ownership_change_is_recorded_in_audit_log()
    {
        $ownerA = $this->makeOwner($this->makeUser('مالک الف'));
        $ownerB = $this->makeOwner($this->makeUser('مالک ب'));
        $cert   = $this->makeCertificate($ownerA);

        $requester = $this->makeUser('درخواست‌دهنده');
        $approver  = $this->makeUser('تأییدکننده');

        $transfer = $this->service->requestTransfer([
            'certificate_id' => $cert->getKey(),
            'to_owner_id'    => $ownerB->getKey(),
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
        ], $requester);

        $this->assertDatabaseHas('audit_logs', ['event' => AuditLog::EVENT_TRANSFER_REQUESTED]);

        $this->service->approve($transfer->getKey(), $approver);

        $this->assertDatabaseHas('audit_logs', ['event' => AuditLog::EVENT_TRANSFER_APPROVED]);
        $this->assertDatabaseHas('audit_logs', [
            'event'    => AuditLog::EVENT_OWNERSHIP_CHANGED,
            'severity' => AuditLog::SEVERITY_CRITICAL,
        ]);
    }

    // =========================================================
    // ۷) دسترسی غیرمجاز
    // =========================================================

    /** @test */
    public function customer_cannot_access_ownership_transfer_admin_panel()
    {
        $user = $this->makeUser('کاربر عادی');
        $user->roles()->sync([
            Role::query()->where('slug', 'customer')->first()->getKey() => [
                'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
        $user->flushPermissionCache();

        $response = $this->actingAs($user->fresh())->get('/admin/jewelry/transfers');

        $response->assertStatus(403);
    }

    /** @test */
    public function ownership_manager_can_access_ownership_transfer_admin_panel()
    {
        $user = $this->makeUser('مدیر مالکیت');
        $roleId = Role::query()->where('slug', 'ownership_manager')->first()->getKey();
        $user->roles()->sync([
            $roleId => ['assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);
        $user->flushPermissionCache();

        $response = $this->actingAs($user->fresh())->get('/admin/jewelry/transfers');

        $this->assertNotEquals(403, $response->getStatusCode());
    }
}
