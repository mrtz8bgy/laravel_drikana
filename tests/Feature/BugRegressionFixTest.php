<?php

namespace Tests\Feature;

use App\Models\SellerPayment;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * تست‌های رگرسیون برای اشکالاتی که در بازبینی «سلامت همه صفحات» پیدا و
 * رفع شدند.
 *
 * ── چرا این فایل لازم است؟ ────────────────────────────────────────────
 * خزش خودکار صفحات پنل مدیریت (tools/crawl_admin.sh) ۱۶ صفحهٔ ۵۰۰ پیدا
 * کرد. پس از رفع، این تست‌ها تضمین می‌کنند که همان اشکالات برنگردند:
 *
 *   ۱) صفحهٔ «شرایط و مقررات» — نام ردیف جدول `policies` اشتباه بود
 *      (`terms` به‌جای `terms_conditions`) و در نبود ردیف، ۵۰۰ می‌داد.
 *   ۲) صفحهٔ «ساخت بنر» — روت resource متد `create($position)` را بدون
 *      پارامتر صدا می‌زد و صفحه ۵۰۰ می‌شد.
 *   ۳) گزارش زندهٔ جواهرات — ستون `completed_at` در جدول `repair_orders`
 *      وجود ندارد (نام درست: `completion_date`).
 *   ۴) خروجی PDF کاربران — متد `setOptions()` روی الیاس سراسری `PDF` به
 *      mPDF می‌رسید (pkg alias) و متد وجود نداشت؛ علاوه بر آن با
 *      `isRemoteEnabled=true` واکشی تصویر لوگو از راه HTTP باعث توقف
 *      ۶۰ ثانیه‌ای می‌شد.
 *   ۵) دست‌کاری شناسه‌های رمزنگاری‌شده — `decrypt()` نامعتبر خطای ۵۰۰
 *      می‌داد (اکنون ۴۰۴).
 *   ۶) مسیرهای افزونه‌های نصب‌نشده — به‌جای ۵۰۰، روت ثبت نمی‌شود (۴۰۴).
 *   ۷) `/seller/payments` — به جدول غلط (`payments`) و ستونی که وجود
 *      نداشت (`seller_id`) وابسته بود؛ اکنون جدول `seller_payments`.
 *
 * نکته: این تست‌ها وضعیت را «غیر از ۵۰۰» می‌سنجند؛ ۴۰۳/۴۰۴ می‌توانند
 * رفتار درست باشند و در صورت نیاز جداگانه بررسی شده‌اند.
 */
class BugRegressionFixTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ساخت کاربر آزمایشی با نقش‌های دلخواه.
     *
     * @param  array $roleSlugs
     * @param  array $attributes
     * @return \App\User
     */
    protected function makeUser(array $roleSlugs = [], array $attributes = [])
    {
        $user = new User();
        $user->name              = isset($attributes['name']) ? $attributes['name'] : 'کاربر آزمایشی';
        $user->email             = 'reg' . uniqid() . '@example.com';
        $user->password          = bcrypt('test-password-12345');
        $user->user_type         = isset($attributes['user_type']) ? $attributes['user_type'] : 'customer';
        $user->role              = $user->user_type;
        $user->email_verified_at = now();
        $user->phone_verified_at = now();
        $user->save();

        if (!empty($roleSlugs)) {
            $roleIds = \App\Models\Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();
            $payload = [];
            foreach ($roleIds as $roleId) {
                $payload[$roleId] = [
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            $user->roles()->sync($payload);
        }

        $user->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * ساخت فروشندهٔ آزمایشی همراه با پروفایل فروشنده.
     *
     * @return \App\User
     */
    protected function makeSeller()
    {
        $seller = $this->makeUser([], ['user_type' => 'seller']);

        DB::table('sellers')->insert([
            'user_id'             => $seller->id,
            'verification_status' => 1,
            'admin_to_pay'        => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return $seller;
    }

    /**
     * میان‌بُر: صفحه نباید ۵۰۰ بدهد.
     *
     * @param  \App\User $user
     * @param  string    $uri
     * @param  string    $label
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertNoServerError(User $user, $uri, $label)
    {
        $response = $this->actingAs($user)->get($uri);

        $this->assertNotEquals(500, $response->getStatusCode(), $label . ' (' . $uri . ') خطای ۵۰۰ داد.');
        $this->assertNotEquals(404, $response->getStatusCode(), $label . ' (' . $uri . ') یافت نشد (۴۰۴).');

        return $response;
    }

    // =========================================================
    // ۱) صفحهٔ شرایط و مقررات
    // =========================================================

    /** @test */
    public function terms_page_renders_stored_terms_conditions() : void
    {
        // متن مشخصی را در ردیف درست (terms_conditions) می‌نویسیم و انتظار
        // داریم همان در صفحه دیده شود. پیش‌تر ویو به اشتباه نام `terms` را
        // می‌خواند و صفحه ۵۰۰ می‌داد؛ این تست همان رگرسیون را می‌گیرد.
        $marker = 'متن آزمایشی شرایط و مقررات — ' . uniqid();

        $existing = DB::table('policies')->where('name', 'terms_conditions')->first();
        if ($existing === null) {
            DB::table('policies')->insert([
                'name'       => 'terms_conditions',
                'content'    => $marker,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('policies')->where('name', 'terms_conditions')->update(['content' => $marker]);
        }

        $response = $this->get('/terms');

        $response->assertStatus(200);
        $this->assertStringContainsString($marker, $response->getContent(), 'صفحهٔ شرایط باید متن ردیف terms_conditions را نشان دهد.');
    }

    /** @test */
    public function terms_page_does_not_fail_when_policy_row_is_missing() : void
    {
        // ردیف را موقتاً حذف می‌کنیم؛ صفحه باید سالم بماند (نه ۵۰۰)
        $backup = DB::table('policies')->where('name', 'terms_conditions')->get();
        DB::table('policies')->where('name', 'terms_conditions')->delete();

        try {
            $response = $this->get('/terms');
            $this->assertNotEquals(500, $response->getStatusCode(), 'در نبود ردیف سیاست، صفحه نباید ۵۰۰ بدهد.');
        } finally {
            foreach ($backup as $row) {
                DB::table('policies')->insert((array) $row);
            }
        }
    }

    // =========================================================
    // ۲) صفحهٔ ساخت بنر (frontend_settings/home_banners/create)
    // =========================================================

    /** @test */
    public function banner_create_pages_render_for_admin() : void
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $this->assertNoServerError($admin, '/admin/frontend_settings/home_banners/create', 'ساخت بنر (بدون موقعیت)');
        $this->assertNoServerError($admin, '/admin/frontend_settings/home_banners/create/1', 'ساخت بنر (موقعیت ۱)');
        $this->assertNoServerError($admin, '/admin/frontend_settings/home_banners/create/home_banner_one', 'ساخت بنر (نامک)');
    }

    // =========================================================
    // ۳) گزارش زندهٔ جواهرات
    // =========================================================

    /** @test */
    public function jewelry_live_stats_endpoint_returns_json() : void
    {
        $admin    = $this->makeUser(['super_admin'], ['user_type' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/jewelry/reports/live-stats');

        $response->assertStatus(200);
        $payload = json_decode($response->getContent(), true);

        $this->assertIsArray($payload, 'خروجی باید JSON باشد.');
        $this->assertArrayHasKey('today', $payload);
        $this->assertArrayHasKey('completed_repairs', $payload['today']);
    }

    // =========================================================
    // ۴) خروجی PDF کاربران
    // =========================================================

    /** @test */
    public function user_pdf_export_returns_pdf_document() : void
    {
        $admin    = $this->makeUser(['super_admin'], ['user_type' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/user');

        $response->assertStatus(200);

        $contentType = $response->headers->get('content-type');
        $this->assertStringContainsString('pdf', strtolower((string) $contentType), 'نوع خروجی باید PDF باشد.');
        $this->assertStringStartsWith('%PDF', $response->getContent(), 'خروجی باید یک سند PDF معتبر باشد.');
    }

    // =========================================================
    // ۵) دست‌کاری شناسه‌های رمزنگاری‌شده → ۴۰۴ (نه ۵۰۰)
    // =========================================================

    /** @test */
    public function tampered_encrypted_ids_return_404_not_500() : void
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $uris = [
            '/admin/brands/not-a-valid-payload/edit',
            '/admin/categories/00000000000/edit',
            '/admin/staff/xyz/edit',
        ];

        foreach ($uris as $uri) {
            $response = $this->actingAs($admin)->get($uri);
            $this->assertEquals(404, $response->getStatusCode(), $uri . ' باید ۴۰۴ بدهد، نه خطای سرور.');
        }
    }

    // =========================================================
    // ۶) افزونه‌های نصب‌نشده: روت ثبت نمی‌شود (۴۰۴ نه ۵۰۰)
    // =========================================================

    /** @test */
    public function uninstalled_addon_pages_return_404_not_500() : void
    {
        // جدول addons در دیتابیس آزمایشی رکوردی ندارد (افزونه‌ای نصب نشده)
        $this->assertEquals(0, DB::table('addons')->count(), 'پیش‌نیاز تست: هیچ افزونه‌ای نصب نشده باشد.');

        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $uris = [
            '/admin/affiliate'           => 'افزونهٔ همکاری در فروش',
            '/admin/club-points/index'   => 'افزونهٔ امتیاز باشگاه مشتریان',
            '/admin/seller_packages'     => 'افزونهٔ اشتراک فروشنده',
        ];

        foreach ($uris as $uri => $label) {
            $response = $this->actingAs($admin)->get($uri);

            $this->assertEquals(
                404,
                $response->getStatusCode(),
                $label . ' نصب نشده است؛ انتظار ۴۰۴ بود اما ' . $response->getStatusCode() . ' دریافت شد.'
            );
        }

        // روت‌های افزونه‌های نصب‌نشده نباید اصلاً ثبت شده باشند
        $this->assertFalse(
            app('router')->has('admin.affiliate.index'),
            'روت افزونهٔ نصب‌نشده نباید ثبت شود.'
        );
    }

    // =========================================================
    // ۷) تاریخچهٔ پرداخت فروشنده
    // =========================================================

    /** @test */
    public function seller_payment_history_page_renders_and_uses_seller_payments_table() : void
    {
        $seller = $this->makeSeller();

        SellerPayment::create([
            'seller_id'      => $seller->id,
            'amount'         => 150000,
            'payment_method' => 'bank',
            'txn_code'       => 'TEST-TXN-1',
        ]);

        $response = $this->actingAs($seller)->get('/seller/payments');
        $this->assertNotEquals(500, $response->getStatusCode(), 'صفحهٔ تاریخچهٔ پرداخت فروشنده ۵۰۰ داد.');

        $admin        = $this->makeUser(['super_admin'], ['user_type' => 'admin']);
        $adminRequest = $this->actingAs($admin)->get('/admin/seller/payments');
        $this->assertNotEquals(500, $adminRequest->getStatusCode(), 'صفحهٔ پرداخت فروشندگان در پنل مدیریت ۵۰۰ داد.');
    }

    /** @test */
    public function seller_payment_model_keeps_history_when_user_is_deleted() : void
    {
        $seller = $this->makeSeller();

        $payment = SellerPayment::create([
            'seller_id' => $seller->id,
            'amount'    => 1000,
        ]);

        $seller->delete();

        // تاریخچهٔ مالی نباید از بین برود؛ فقط ارجاع خالی می‌شود
        $this->assertDatabaseHas('seller_payments', ['id' => $payment->id]);
        $this->assertNull($payment->fresh()->seller_id, 'با حذف کاربر، seller_id باید NULL شود (نه حذف رکورد).');
    }

    // =========================================================
    // ۸) حذف محصول: روت موجود + کنترل مالکیت
    // =========================================================

    /** @test */
    public function product_delete_route_exists_and_blocks_cross_seller_deletion() : void
    {
        $this->assertNotNull(
            app('router')->getRoutes()->getByName('products.destroy'),
            'روت products.destroy باید ثبت شده باشد (دکمهٔ حذف محصول به آن وابسته است).'
        );

        $sellerA = $this->makeSeller();
        $sellerB = $this->makeSeller();

        $product = Product::query()->where('added_by', 'seller')->first();

        if ($product === null) {
            $product = Product::query()->first();
        }

        $this->assertNotNull($product, 'پیش‌نیاز تست: وجود حداقل یک محصول.');

        // محصول را به فروشندهٔ B نسبت می‌دهیم
        $product->user_id = $sellerB->id;
        $product->save();

        // فروشندهٔ A نباید بتواند محصول فروشندهٔ B را حذف کند
        $response = $this->actingAs($sellerA)->delete('/products/' . $product->id);
        $this->assertEquals(403, $response->getStatusCode(), 'فروشنده نباید محصول فروشندهٔ دیگر را حذف کند.');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    /** @test */
    public function product_delete_requires_permission_for_admin() : void
    {
        // نقش content_manager مجوز products.delete ندارد
        $contentManager = $this->makeUser(['content_manager'], ['user_type' => 'staff']);

        $product = Product::query()->first();
        $this->assertNotNull($product, 'پیش‌نیاز تست: وجود حداقل یک محصول.');

        $response = $this->actingAs($contentManager)->delete('/products/' . $product->id);

        $this->assertEquals(403, $response->getStatusCode(), 'کاربر بدون مجوز products.delete نباید محصول حذف کند.');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
