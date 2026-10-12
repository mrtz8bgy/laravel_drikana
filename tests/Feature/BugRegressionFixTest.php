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

    // =========================================================
    // دور سوم — رگرسیون باگ‌های کشف‌شده با خزش همهٔ صفحات
    // =========================================================

    /**
     * PHP نام متدها را حساس به بزرگی/کوچکی حرف نمی‌داند؛ پس تعریف
     * aliases مثل subcategories() در کنار subCategories() خطای مرگبار
     * «Cannot redeclare» می‌دهد و کل مدل بارگذاری نمی‌شود.
     *
     * @test
     */
    public function category_models_load_without_redeclare_fatal() : void
    {
        $category = \App\Models\Category::query()->with(['subCategories.subSubCategories'])->first();
        $this->assertNotNull($category, 'پیش‌نیاز تست: وجود حداقل یک دسته‌بندی.');
        $this->assertNotNull($category->subcategories, 'دسترسی با نام کوچک هم باید کار کند.');

        $subCategory = \App\Models\SubCategory::query()->with(['subSubCategories'])->first();

        if ($subCategory !== null) {
            $this->assertNotNull($subCategory->subsubcategories, 'زیردسته با نام کوچک باید به رابطه برسد.');
        }
    }

    /**
     * اندپوینت‌های API دسته‌بندی که پیش‌تر ۵۰۰ می‌دادند.
     *
     * @test
     */
    public function category_api_endpoints_return_200() : void
    {
        foreach (['/api/v1/categories', '/api/v1/categories/featured', '/api/v1/categories/home', '/api/v1/home-categories'] as $uri) {
            $response = $this->get($uri);
            $this->assertEquals(200, $response->getStatusCode(), $uri . ' باید ۲۰۰ بدهد.');
        }
    }

    /**
     * صفحه‌های وابسته به سبد خرید نباید با سبد خالی ۵۰۰ بدهند.
     *
     * @test
     */
    public function cart_empty_pages_do_not_throw_500() : void
    {
        $uris = [
            '/checkout/delivery_info' => 'مرحلهٔ تحویل',
            '/checkout/payment_select' => 'انتخاب پرداخت',
            '/customer-products'      => 'محصولات مشتری‌ها',
            '/api/v1/products/flash-deal' => 'API پیشنهاد ویژه',
        ];

        foreach ($uris as $uri => $label) {
            $response = $this->get($uri);
            $this->assertNotEquals(500, $response->getStatusCode(), $label . ' (' . $uri . ') با سبد خالی ۵۰۰ داد.');
        }
    }

    /**
     * بازگشت از درگاه بدون پارامتر Authority نباید ۵۰۰ بدهد.
     *
     * @test
     */
    public function zarinpal_back_url_without_authority_redirects() : void
    {
        $response = $this->get('/zarinpal/back_url');

        $this->assertNotEquals(500, $response->getStatusCode(), 'بازگشت از درگاه بدون پارامتر نباید خطای سرور بدهد.');
        $this->assertContains($response->getStatusCode(), [200, 302], 'انتظار پاسخ سالم یا هدایت.');
    }

    /**
     * partialهای مشترک (سبد/مقایسه) باید با نشست خالی سالم رندر شوند؛
     * این‌ها در ده‌ها صفحه استفاده می‌شوند و خطایشان کل سایت را ۵۰۰ می‌کند.
     *
     * @test
     */
    public function shared_cart_partials_render_with_empty_session() : void
    {
        foreach (['frontend.partials.cart_summary', 'frontend.partials.cart', 'frontend.partials.compare', 'frontend.partials.cart_details'] as $view) {
            try {
                $this->assertIsString(view($view)->render(), $view . ' باید رندر شود.');
            } catch (\Throwable $e) {
                $this->fail($view . ' با خطا رندر نشد: ' . $e->getMessage());
            }
        }
    }

    /**
     * کمک‌تابع فیلتر محصولات مشتری‌ها پیش‌تر از متغیر تعریف‌نشدهٔ $products
     * استفاده می‌کرد و /customer-products را ۵۰۰ می‌کرد.
     *
     * @test
     */
    public function customer_products_filter_helper_uses_its_parameter() : void
    {
        $collection = collect([(object) ['published' => 1, 'added_by' => 'admin']]);

        $this->assertNotNull(filter_customer_products($collection), 'کمک‌تابع فیلتر باید مقدار برگرداند.');
    }

    /**
     * ستون cart در نشست یک Collection است، نه آرایه؛ cast کورکورانه با
     * (array) ساختار را می‌شکند و «Undefined index: id/price» می‌دهد.
     * این تست مسیر واقعی خرید را با سبد پُر می‌سنجد.
     *
     * @test
     */
    public function cart_pages_render_with_a_filled_cart_collection() : void
    {
        $product = \App\Product::where('published', 1)->first();

        if ($product === null) {
            $this->markTestSkipped('برای این تست حداقل یک محصول منتشرشده لازم است.');
        }

        $cart = collect([[
            'id'       => $product->id,
            'quantity' => 1,
            'price'    => (float) $product->unit_price,
            'tax'      => 0,
            'shipping' => 0,
            'variant'  => '',
            'digital'  => 0,
            'product_referral_code' => null,
        ]]);

        foreach (['/cart', '/checkout/delivery_info', '/checkout/payment_select'] as $uri) {
            $response = $this->withSession(['cart' => $cart])->get($uri);

            $this->assertEquals(200, $response->getStatusCode(), $uri . ' با سبد پُر باید ۲۰۰ بدهد.');
            $this->assertStringNotContainsString('Undefined index', $response->getContent(), $uri . ' نباید «Undefined index» بدهد.');
        }
    }

    /**
     * نشست نباید با cast آرایه‌ای، ساختار Collection را از دست بدهد.
     *
     * @test
     */
    public function cart_session_collection_survives_iteration() : void
    {
        $cart = collect([['id' => 7, 'price' => 10, 'quantity' => 2, 'variant' => '', 'digital' => 0, 'tax' => 0, 'shipping' => 0]]);

        $this->withSession(['cart' => $cart])->get('/cart');

        $fromSession = session('cart');

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $fromSession, 'سبد خرید باید Collection باقی بماند.');
        $this->assertEquals(7, $fromSession->first()['id'], 'آیتم سبد باید بدون تغییر باقی بماند.');
    }
}
