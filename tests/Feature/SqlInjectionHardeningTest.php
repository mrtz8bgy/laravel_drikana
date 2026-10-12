<?php

namespace Tests\Feature;

use App\Models\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * آزمون بستن تزریق SQL در مرتب‌سازی و محدودسازی اندازه صفحه.
 *
 * ── مسئله (بازبینی L-03) ─────────────────────────────────────────────
 *
 * در `JewelryCertificateController@index` دو مقدار مستقیماً از پارامتر
 * درخواست به `orderBy()` می‌رفتند:
 *
 *     $sortBy    = $request->get('sort_by', 'created_at');
 *     $sortOrder = $request->get('sort_order', 'desc');
 *     $query->orderBy($sortBy, $sortOrder);
 *
 * `orderBy()` برخلاف `where()`، نام ستون و جهت را به‌صورت **خام**
 * داخل رشته SQL می‌گذارد (مقدار binding نمی‌شود). پس این یک تزریق SQL
 * واقعی بود، نه یک الگوی مشکوک.
 *
 * به‌علاوه `paginate($request->get('per_page', 15))` بی‌بازبینی بود و
 * با `?per_page=10000000` می‌شد سرور را زیر بار برد (انکار سرویس).
 *
 * این آزمون، رفتار در برابر محموله‌های حمله را می‌سنجد. معیار موفقیت:
 * پاسخ ۵۰۰ نباشد (تزریق، خطای SQL می‌سازد) و صفحه سالم رندر شود.
 */
class SqlInjectionHardeningTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * کاربر با نقش «مدیر مالکیت» — دسترسی مشاهده شناسنامه‌ها.
     *
     * @return User
     */
    protected function makeViewer()
    {
        $user = new User();
        $user->name      = 'بیننده شناسنامه';
        $user->email     = 'sqli' . uniqid() . '@example.test';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = 'customer';
        $user->role      = 'customer';
        $user->save();

        $roleIds = Role::query()
            ->whereIn('slug', ['ownership_manager'])
            ->pluck('id')
            ->all();

        $payload = [];
        foreach ($roleIds as $roleId) {
            $payload[$roleId] = [
                'assigned_at' => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        $user->roles()->sync($payload);
        $user->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * محموله‌های حمله در پارامتر مرتب‌سازی.
     *
     * @return array
     */
    protected function hostileSortPayloads()
    {
        return [
            'زیرپرسش تودرتو'    => 'id,(select 1)',
            'تزریق با پرانتز'   => 'id) desc, (select 1',
            'ستون غیرمجاز'      => 'password',
            'نقل‌قول و کاما'    => "id' , (select 1)--",
            'نام ستون با فاصله' => 'id asc, owner_id',
            'عبارت تابع‌دار'    => 'sleep(5)',
            'ستون جدول دیگر'    => 'users.password',
            'ستون ناموجود'       => 'status',
        ];
    }

    /**
     * محموله‌های حمله در جهت مرتب‌سازی.
     *
     * @return array
     */
    protected function hostileOrderPayloads()
    {
        return [
            'زیرپرسش'       => 'desc, (select 1)',
            'کاما و شرط'    => 'asc; drop table x',
            'کلمه کلیدی'    => 'desc nulls first',
            'خالی'          => '',
        ];
    }

    // ==================================================================
    //  مرتب‌سازی
    // ==================================================================

    public function test_hostile_sort_by_does_not_break_the_query()
    {
        $user = $this->makeViewer();

        foreach ($this->hostileSortPayloads() as $label => $payload) {
            $response = $this->actingAs($user)->get(
                '/admin/jewelry/certificates?sort_by=' . urlencode($payload)
            );

            $this->assertNotEquals(
                500,
                $response->getStatusCode(),
                'محموله «' . $label . '» خطای سرور ساخت (کد ' . $response->getStatusCode()
                . ') — احتمالاً به SQL رسیده است. مقدار: ' . $payload
            );

            $this->assertContains(
                $response->getStatusCode(),
                [200, 302, 403],
                'پاسخ غیرمنتظره برای محموله «' . $label . '»: ' . $response->getStatusCode()
            );
        }
    }

    public function test_hostile_sort_order_does_not_break_the_query()
    {
        $user = $this->makeViewer();

        foreach ($this->hostileOrderPayloads() as $label => $payload) {
            $response = $this->actingAs($user)->get(
                '/admin/jewelry/certificates?sort_order=' . urlencode($payload)
            );

            $this->assertNotEquals(
                500,
                $response->getStatusCode(),
                'محموله جهت مرتب‌سازی «' . $label . '» خطای سرور ساخت. مقدار: ' . $payload
            );
        }
    }

    public function test_valid_sort_columns_still_work()
    {
        $user = $this->makeViewer();

        foreach (['created_at', 'updated_at', 'serial_number', 'product_name', 'owner_id', 'metal_type', 'weight', 'is_verified'] as $column) {
            $response = $this->actingAs($user)->get(
                '/admin/jewelry/certificates?sort_by=' . $column . '&sort_order=asc'
            );

            $this->assertNotEquals(
                500,
                $response->getStatusCode(),
                'مرتب‌سازی مجاز «' . $column . '» باید کار کند.'
            );
        }
    }

    // ==================================================================
    //  اندازه صفحه (انکار سرویس)
    // ==================================================================

    public function test_oversized_per_page_is_clamped()
    {
        $user = $this->makeViewer();

        foreach (['10000000', '-5', 'abc', '0', '999999999999999999999'] as $value) {
            $response = $this->actingAs($user)->get(
                '/admin/jewelry/certificates?per_page=' . urlencode($value)
            );

            $this->assertNotEquals(
                500,
                $response->getStatusCode(),
                'مقدار per_page = ' . $value . ' خطای سرور ساخت.'
            );
        }
    }

    public function test_reasonable_per_page_is_accepted()
    {
        $user = $this->makeViewer();

        foreach (['10', '50', '100'] as $value) {
            $response = $this->actingAs($user)->get(
                '/admin/jewelry/certificates?per_page=' . $value
            );

            $this->assertNotEquals(500, $response->getStatusCode());
        }
    }
}
