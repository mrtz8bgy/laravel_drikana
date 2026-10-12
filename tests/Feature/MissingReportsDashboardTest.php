<?php

namespace Tests\Feature;

use App\Http\Controllers\MissingReportController;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\MissingReport;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class MissingReportsDashboardTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function super_admin_sees_system_wide_report_totals_independent_of_active_filters()
    {
        $admin = $this->makeUser('admin', 'admin');
        $baseline = [
            'total' => MissingReport::count(),
            'pending' => MissingReport::where('status', 'pending')->count(),
            'found' => MissingReport::where('status', 'found')->count(),
            'theft' => MissingReport::where('type', 'theft')->count(),
            'lost' => MissingReport::where('type', 'lost')->count(),
            'filtered_theft' => MissingReport::where('type', 'theft')->count(),
        ];
        $firstOwner = $this->makeOwner($this->makeUser('owner-one'));
        $secondOwner = $this->makeOwner($this->makeUser('owner-two'));
        $firstCertificate = $this->makeCertificate($firstOwner, 'MISSING-' . strtoupper(uniqid()));
        $secondCertificate = $this->makeCertificate($secondOwner, 'LOST-' . strtoupper(uniqid()));
        $this->makeReport($firstOwner->user_id, $firstCertificate, 'theft', 'pending');
        $this->makeReport($secondOwner->user_id, $secondCertificate, 'lost', 'found');

        $this->actingAs($admin);
        $view = app(MissingReportController::class)->index(Request::create(
            '/admin/jewelry/missing_reports',
            'GET',
            ['type' => 'theft']
        ));
        $data = $view->getData();

        $this->assertSame($baseline['total'] + 2, $data['stats']['total']);
        $this->assertSame($baseline['pending'] + 1, $data['stats']['pending']);
        $this->assertSame($baseline['found'] + 1, $data['stats']['found']);
        $this->assertSame($baseline['theft'] + 1, $data['stats']['theft']);
        $this->assertSame($baseline['lost'] + 1, $data['stats']['lost']);
        $this->assertCount($baseline['filtered_theft'] + 1, $data['reports']);

        $html = $view->with('errors', new MessageBag())->render();
        $this->assertStringContainsString('>' . number_format($baseline['total'] + 2) . '</h4>', $html);
        $this->assertStringContainsString('ثبت گزارش جدید', $html);
        $this->assertStringContainsString('missing-report-button--create', $html);
        $this->assertStringContainsString('missing-report-button--details', $html);
        $this->assertStringContainsString('missing-report-button--print', $html);
        $this->assertStringContainsString('<span>جزئیات</span>', $html);
        $this->assertStringContainsString('<span>چاپ</span>', $html);
    }

    /** @test */
    public function regular_user_report_totals_remain_scoped_to_their_own_reports()
    {
        $user = $this->makeUser('customer');
        $other = $this->makeUser('another-customer');
        $owner = $this->makeOwner($user);
        $otherOwner = $this->makeOwner($other);
        $certificate = $this->makeCertificate($owner, 'OWN-' . strtoupper(uniqid()));
        $otherCertificate = $this->makeCertificate($otherOwner, 'OTHER-' . strtoupper(uniqid()));
        $this->makeReport($user->id, $certificate, 'theft', 'pending');
        $this->makeReport($other->id, $otherCertificate, 'lost', 'found');

        $this->actingAs($user);
        $view = app(MissingReportController::class)->index(Request::create('/admin/jewelry/missing_reports'));
        $data = $view->getData();

        $this->assertSame(1, $data['stats']['total']);
        $this->assertSame(1, $data['stats']['theft']);
        $this->assertSame(0, $data['stats']['lost']);
        $this->assertCount(1, $data['reports']);
        $this->assertSame($certificate->serial_number, $data['reports']->first()->jewelry->serial_number);
    }

    private function makeUser($name, $userType = 'customer')
    {
        $user = new User();
        $user->name = 'Missing report ' . $name;
        $user->email = 'missing-' . $name . '-' . uniqid() . '@example.com';
        $user->password = bcrypt('test-password-12345');
        $user->user_type = $userType;
        $user->role = $userType;
        $user->save();

        return $user;
    }

    private function makeOwner(User $user)
    {
        return JewelryOwner::create([
            'user_id' => $user->id,
            'full_name' => 'مالک گزارش ' . uniqid(),
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '09123456789',
        ]);
    }

    private function makeCertificate(JewelryOwner $owner, $serial)
    {
        return JewelryCertificate::create([
            'owner_id' => $owner->id,
            'product_name' => 'جواهر آزمایشی',
            'metal_type' => 'طلا',
            'serial_number' => $serial,
        ]);
    }

    private function makeReport($userId, JewelryCertificate $certificate, $type, $status)
    {
        return MissingReport::create([
            'user_id' => $userId,
            'jewelry_id' => $certificate->id,
            'type' => $type,
            'status' => $status,
        ]);
    }
}
