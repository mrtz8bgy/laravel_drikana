<?php

namespace Tests\Feature;

use App\Http\Controllers\Jewelry\DashboardController;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\RepairOrder;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class JewelryDashboardTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function super_admin_dashboard_uses_system_wide_counts_and_stored_repair_statuses()
    {
        $admin = $this->makeUser('admin', true);
        $ownerUser = $this->makeUser('owner');
        $initialOwners = JewelryOwner::count();
        $initialCertificates = JewelryCertificate::count();
        $initialRepairs = RepairOrder::count();
        $initialActiveRepairs = RepairOrder::whereIn('status', ['pending', 'in_progress'])->count();

        $owner = $this->makeOwner($ownerUser);
        $this->makeCertificate($owner, 'DASH-' . strtoupper(uniqid()));
        $this->makeRepair($ownerUser, 'pending');
        $this->makeRepair($ownerUser, 'in_progress');

        $this->actingAs($admin);
        $view = app(DashboardController::class)->index();
        $stats = $view->getData()['stats'];

        $this->assertSame($initialOwners + 1, $stats['owners_count']);
        $this->assertSame($initialCertificates + 1, $stats['certificates_count']);
        $this->assertSame($initialRepairs + 2, $stats['repair_orders_count']);
        $this->assertSame($initialActiveRepairs + 2, $stats['active_repairs']);
        $this->assertSame(1, $stats['pending_repairs']);
        $this->assertSame(1, $stats['in_progress_repairs']);
        $this->assertTrue($view->getData()['isSuperAdmin']);

        $html = $view->render();
        $this->assertStringContainsString('داشبورد سامانه جواهرات', $html);
        $this->assertStringContainsString('آخرین سفارش‌های تعمیر', $html);
    }

    /** @test */
    public function non_admin_dashboard_only_counts_the_current_users_records()
    {
        $user = $this->makeUser('customer');
        $otherUser = $this->makeUser('other');
        $owner = $this->makeOwner($user);
        $otherOwner = $this->makeOwner($otherUser);
        $certificate = $this->makeCertificate($owner, 'MINE-' . strtoupper(uniqid()));
        $this->makeCertificate($otherOwner, 'OTHER-' . strtoupper(uniqid()));
        $this->makeRepair($user, 'pending');
        $this->makeRepair($otherUser, 'in_progress');

        $this->actingAs($user);
        $view = app(DashboardController::class)->index();
        $stats = $view->getData()['stats'];

        $this->assertSame(1, $stats['owners_count']);
        $this->assertSame(1, $stats['certificates_count']);
        $this->assertSame(1, $stats['pending_verification']);
        $this->assertSame(1, $stats['repair_orders_count']);
        $this->assertSame(1, $stats['pending_repairs']);
        $this->assertSame(0, $stats['in_progress_repairs']);
        $this->assertFalse($view->getData()['isSuperAdmin']);
        $this->assertTrue($view->getData()['recentCertificates']->contains('id', $certificate->id));
    }

    private function makeUser($suffix, $admin = false)
    {
        $user = new User();
        $user->name = 'Dashboard ' . $suffix;
        $user->email = 'dashboard-' . $suffix . '-' . uniqid() . '@example.com';
        $user->password = bcrypt('test-password-12345');
        $user->user_type = $admin ? 'admin' : 'customer';
        $user->role = $admin ? 'admin' : 'customer';
        $user->save();

        $user->flushPermissionCache();

        return $user->fresh();
    }

    private function makeOwner(User $user)
    {
        return JewelryOwner::create([
            'user_id' => $user->id,
            'full_name' => 'مالک داشبورد ' . uniqid(),
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '09123456789',
        ]);
    }

    private function makeCertificate(JewelryOwner $owner, $serialNumber)
    {
        return JewelryCertificate::create([
            'owner_id' => $owner->id,
            'product_name' => 'قطعه آزمایشی داشبورد',
            'metal_type' => 'طلا',
            'purity' => 18,
            'weight' => 2.5,
            'serial_number' => $serialNumber,
            'is_verified' => false,
        ]);
    }

    private function makeRepair(User $user, $status)
    {
        return RepairOrder::create([
            'user_id' => $user->id,
            'order_number' => 'DASH-' . strtoupper(uniqid()),
            'status' => $status,
        ]);
    }
}
