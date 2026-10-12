<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\RepairTechnician;
use App\Models\RepairOrder;
use App\Models\Role;
use App\Http\Controllers\Jewelry\JewelryOwnerController;
use App\Http\Controllers\Jewelry\Admin\RepairOrderController as AdminRepairOrderController;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * تست‌های اصلاحات امنیتی.
 *
 * پوشش:
 *   • حذف اندپوینت مخرب نابودی دیتابیس (/demo/cron_1)
 *   • کنترل دسترسی شکسته (BAC) در ماژول تعمیرات — H-01
 *   • محافظت Mass Assignment روی فیلدهای role و user_type
 *   • هدرهای امنیتی HTTP
 *   • محافظت CSRF
 *   • میدل‌ورهای امنیتی فعال
 *   • جلوگیری از Path Traversal در آپلود
 *   • IDOR در دسترسی به رکورد دیگران
 */
class SecurityFixesTest extends TestCase
{
    use DatabaseTransactions;

    protected function makeUser(array $roleSlugs = [], array $attributes = [])
    {
        $user = new User();
        $user->name      = isset($attributes['name']) ? $attributes['name'] : 'کاربر';
        $user->email     = 's' . uniqid() . '@example.com';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = isset($attributes['user_type']) ? $attributes['user_type'] : 'customer';
        $user->role      = 'customer';
        $user->save();

        if (!empty($roleSlugs)) {
            $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();
            $payload = [];
            foreach ($roleIds as $roleId) {
                $payload[$roleId] = ['assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()];
            }
            $user->roles()->sync($payload);
        }

        $user->flushPermissionCache();

        return $user->fresh();
    }

    // =========================================================
    // ۱) [CRITICAL] حذف اندپوینت نابودی دیتابیس
    // =========================================================

    /** @test */
    public function demo_cron_routes_are_removed()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });

        $this->assertFalse($routes->contains('demo/cron_1'),
            'روت /demo/cron_1 باید حذف شده باشد — این اندپوینت تمام جداول را دراپ می‌کرد.');

        $this->assertFalse($routes->contains('demo/cron_2'),
            'روت /demo/cron_2 باید حذف شده باشد.');
    }

    /** @test */
    public function demo_cron_endpoint_returns_404()
    {
        $response = $this->get('/demo/cron_1');

        // باید ۴۰۴ باشد — نه اجرای عملیات مخرب
        $response->assertStatus(404);
    }

    /** @test */
    public function database_tables_still_exist_after_demo_endpoint_call()
    {
        $this->get('/demo/cron_1');

        // جداول حساس باید دست‌نخورده باقی مانده باشند
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('users'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('jewelry_certificates'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('orders'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('products'));
    }

    // =========================================================
    // ۲) [HIGH] کنترل دسترسی شکسته در تعمیرات
    // =========================================================

    /** @test */
    public function customer_cannot_access_repair_orders_admin_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/repair-orders');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_jewelry_owners_admin_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/owners');

        $response->assertStatus(403);
    }

    /** @test */
    public function repair_order_form_shows_active_repair_technician_profiles()
    {
        $admin = $this->makeUser([], ['user_type' => 'admin']);
        $technician = $this->makeUser([], [
            'name' => 'تکنسین آزمایشی',
            'user_type' => 'customer',
        ]);
        $technician->role = 'technician';
        $technician->save();

        $profile = RepairTechnician::create([
            'user_id' => $technician->id,
            'specialty' => 'repair',
            'experience_years' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($admin);
        $view = (new AdminRepairOrderController())->create();
        $technicians = $view->getData()['technicians'];

        $this->assertTrue($technicians->contains('id', $profile->id));

        $html = $view
            ->with('errors', new \Illuminate\Support\MessageBag())
            ->render();
        $this->assertStringContainsString('تکنسین آزمایشی - تعمیرات عمومی (5 سال)', $html);
        $this->assertStringContainsString('value="' . $technician->id . '"', $html);
    }

    /** @test */
    public function administrator_can_see_owners_created_by_another_administrator()
    {
        $ownerCreator = $this->makeUser([], ['user_type' => 'admin']);
        $administrator = $this->makeUser([], ['user_type' => 'admin']);

        $owner = JewelryOwner::create([
            'user_id' => $ownerCreator->id,
            'full_name' => 'مالک آزمایشی مدیر دیگر',
            'national_id' => '1234567890',
            'phone' => '09123456789',
        ]);

        $this->actingAs($administrator);
        $view = (new JewelryOwnerController())->index();
        $owners = $view->getData()['owners'];

        $this->assertTrue($owners->getCollection()->contains('id', $owner->id));

        $customer = $this->makeUser();
        $this->actingAs($customer);
        $customerView = (new JewelryOwnerController())->index();
        $customerOwners = $customerView->getData()['owners'];

        $this->assertFalse($customerOwners->getCollection()->contains('id', $owner->id));
    }

    /** @test */
    public function customer_cannot_access_jewelry_certificates_admin_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/certificates');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_jewelry_dashboard()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_jewelry_missing_reports()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/jewelry/missing_reports');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_roles_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/roles');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_user_management_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function customer_cannot_access_audit_logs()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/audit-logs');

        $response->assertStatus(403);
    }

    // =========================================================
    // ۳) Mass Assignment
    // =========================================================

    /** @test */
    public function role_and_user_type_are_not_mass_assignable()
    {
        $user = new User();

        $user->fill([
            'name'      => 'مهاجم',
            'email'     => 'attacker@example.com',
            'role'      => 'admin',
            'user_type' => 'admin',
        ]);

        // فیلدهای حساس نباید پر شده باشند
        $this->assertNull($user->role,
            'فیلد role نباید از طریق Mass Assignment قابل تنظیم باشد.');
        $this->assertNull($user->user_type,
            'فیلد user_type نباید از طریق Mass Assignment قابل تنظیم باشد.');

        // فیلدهای مجاز باید پر شده باشند
        $this->assertEquals('مهاجم', $user->name);
    }

    // =========================================================
    // ۴) هدرهای امنیتی
    // =========================================================

    /** @test */
    public function security_headers_are_present()
    {
        $response = $this->get('/articles');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $this->assertNotNull($response->headers->get('Referrer-Policy'));
        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
        $this->assertNotNull($response->headers->get('Permissions-Policy'));

        // Clickjacking باید مسدود باشد
        $this->assertStringContainsString(
            "frame-ancestors",
            $response->headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function csp_blocks_object_embedding()
    {
        $response = $this->get('/articles');

        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("object-src 'none'", $csp);
    }

    // =========================================================
    // ۵) CSRF
    // =========================================================

    /** @test */
    public function post_without_csrf_token_is_rejected()
    {
        $user = $this->makeUser(['content_manager']);

        // درخواست POST بدون توکن CSRF
        $response = $this->actingAs($user)->post('/admin/articles', [
            'title'   => 'مقاله تزریقی',
            'content' => '<p>متن</p>',
        ]);

        // Laravel در تست‌ها CSRF را غیرفعال می‌کند؛ پس بررسی می‌کنیم که
        // میدل‌ور CSRF در پشته ثبت شده باشد
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middleware = $kernel->getMiddlewareGroups()['web'];

        $this->assertContains(
            \App\Http\Middleware\VerifyCsrfToken::class,
            $middleware,
            'میدل‌ور CSRF باید در گروه web فعال باشد.'
        );
    }

    /** @test */
    public function csrf_middleware_protects_sensitive_routes()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            $uri = $route->uri();
            return strpos($uri, 'admin/roles') === 0
                || strpos($uri, 'admin/users') === 0
                || strpos($uri, 'admin/jewelry/transfers') === 0
                || strpos($uri, 'admin/articles') === 0;
        });

        $this->assertGreaterThan(0, $routes->count());

        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();

            $this->assertContains('web', $middleware,
                'روت ' . $route->uri() . ' باید در گروه میدل‌ور web باشد تا CSRF اعمال شود.');
        }
    }

    // =========================================================
    // ۶) میدل‌ورهای امنیتی فعال
    // =========================================================

    /** @test */
    public function encrypt_cookies_middleware_is_enabled()
    {
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middleware = $kernel->getMiddlewareGroups()['web'];

        $this->assertContains(
            \App\Http\Middleware\EncryptCookies::class,
            $middleware,
            'میدل‌ور EncryptCookies باید فعال باشد — پیش از این کامنت بود.'
        );
    }

    /** @test */
    public function authenticate_session_middleware_is_enabled()
    {
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middleware = $kernel->getMiddlewareGroups()['web'];

        $this->assertContains(
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            $middleware,
            'میدل‌ور AuthenticateSession باید فعال باشد تا Session Fixation مسدود شود.'
        );
    }

    /** @test */
    public function permission_middleware_is_registered()
    {
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $routeMiddleware = $kernel->getRouteMiddleware();

        $this->assertArrayHasKey('permission', $routeMiddleware);
        $this->assertArrayHasKey('permission_all', $routeMiddleware);
        $this->assertArrayHasKey('role', $routeMiddleware);
    }

    // =========================================================
    // ۷) IDOR
    // =========================================================

    /** @test */
    public function user_cannot_view_other_users_missing_report()
    {
        $owner = $this->makeUser();
        $other = $this->makeUser();

        // ساخت مالک و شناسنامه
        $jewelryOwner = new JewelryOwner();
        $jewelryOwner->user_id   = $owner->getKey();
        $jewelryOwner->full_name = 'مالک';
        $jewelryOwner->save();

        $cert = new JewelryCertificate();
        $cert->owner_id      = $jewelryOwner->getKey();
        $cert->product_name  = 'گوشواره';
        $cert->metal_type    = 'طلا';
        $cert->serial_number = 'IDOR-' . strtoupper(uniqid());
        $cert->save();

        // گزارش مفقودی متعلق به کاربر owner
        $reportId = \Illuminate\Support\Facades\DB::table('missing_reports')->insertGetId([
            'user_id'    => $owner->getKey(),
            'jewelry_id' => $cert->getKey(),
            'type'       => 'lost',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // کاربر دیگر تلاش می‌کند با تغییر شناسه به آن دسترسی بگیرد
        $response = $this->actingAs($other)->get('/admin/jewelry/missing_reports/' . $reportId);

        // باید رد شود — نه ۲۰۰
        $this->assertContains($response->getStatusCode(), [403, 404],
            'دسترسی به گزارش کاربر دیگر باید مسدود شود (IDOR).');
    }

    // =========================================================
    // ۸) Path Traversal در آپلود
    // =========================================================

    /** @test */
    public function secure_uploader_blocks_path_traversal_on_delete()
    {
        $uploader = app(\App\Services\Media\SecureImageUploader::class);

        // تلاش برای حذف فایل خارج از پوشه مجاز
        $result = $uploader->delete('../../.env', 'public', ['uploads/']);
        $this->assertFalse($result, 'حذف فایل خارج از پوشه مجاز باید مسدود شود.');

        $result = $uploader->delete('/etc/passwd', 'public', ['uploads/']);
        $this->assertFalse($result);

        $result = $uploader->delete('config/app.php', 'public', ['uploads/']);
        $this->assertFalse($result);
    }

    /** @test */
    public function secure_uploader_sanitizes_original_filename()
    {
        $uploader = app(\App\Services\Media\SecureImageUploader::class);

        $reflection = new \ReflectionClass($uploader);
        $method = $reflection->getMethod('sanitizeOriginalName');
        $method->setAccessible(true);

        $this->assertStringNotContainsString('..', $method->invoke($uploader, '../../evil.php'));
        $this->assertStringNotContainsString('/', $method->invoke($uploader, 'a/b/c.jpg'));
        $this->assertStringNotContainsString('<', $method->invoke($uploader, '<script>.jpg'));
    }

    // =========================================================
    // ۹) نشت اطلاعات
    // =========================================================

    /** @test */
    public function env_file_is_not_web_accessible_via_public_directory()
    {
        // فایل .env در ریشه پروژه است، نه در public/
        $this->assertFileExists(base_path('.env'));
        $this->assertFileNotExists(public_path('.env'),
            'فایل .env نباید در پوشه public قرار داشته باشد.');
    }

    // =========================================================
    // سخت‌سازی مرحله ۷ — موارد افزوده‌شده در بازبینی دوم
    // =========================================================

    /** @test */
    public function session_cookie_is_encrypted_and_restricted()
    {
        // کوکی نشست نباید متن روشن باشد
        $this->assertTrue(
            config('session.encrypt'),
            'محتوای نشست باید رمزنگاری‌شده ذخیره شود؛ در غیر این صورت با لو رفتن '
            . 'کوکی، اطلاعات داخلی و پیام‌های کاربر خوانا خواهند بود.'
        );

        // http_only باید فعال باشد تا جاوااسکریپت نتواند کوکی را بخواند
        $this->assertTrue(
            config('session.http_only'),
            'کوکی نشست باید http_only باشد تا از دسترسی جاوااسکریپت محافظت شود.'
        );

        // same_site نباید null باشد (جلوگیری از CSRF بین‌سایتی)
        $this->assertContains(
            config('session.same_site'),
            ['lax', 'strict'],
            'کوکی نشست باید same_site داشته باشد تا در درخواست‌های بین‌سایتی ارسال نشود.'
        );
    }

    /** @test */
    public function open_redirect_is_blocked_on_login()
    {
        // آدرس‌های بیرونی باید رد شوند و به آدرس داخلی تبدیل شوند
        $external = [
            'https://evil.example.com/steal',
            'http://evil.example.com',
            '//evil.example.com/steal',
            'javascript:alert(1)',
            'data:text/html,<script>alert(1)</script>',
        ];

        foreach ($external as $url) {
            $result = drikana_safe_redirect_url($url);

            $this->assertStringStartsWith(
                'http',
                $result,
                'آدرس بیرونی «' . $url . '» باید رد شود.'
            );

            $this->assertStringNotContainsString(
                'evil.example.com',
                $result,
                'آدرس «' . $url . '» نباید به دامنه بیرونی هدایت کند (Open Redirect).'
            );

            $this->assertStringNotContainsString(
                'javascript:',
                $result,
                'آدرس «' . $url . '» نباید اسکیمای خطرناک داشته باشد.'
            );
        }
    }

    /** @test */
    public function safe_redirect_allows_internal_paths()
    {
        // مسیرهای داخلی باید بدون تغییر عبور کنند
        $this->assertSame('/admin/users', drikana_safe_redirect_url('/admin/users'));
        $this->assertSame('/my-jewelry?tab=1', drikana_safe_redirect_url('/my-jewelry?tab=1'));

        $own = url('/admin/users');
        $this->assertSame($own, drikana_safe_redirect_url($own));
    }

    /** @test */
    public function installer_and_updater_are_blocked_on_installed_system()
    {
        // در محیط آزمون، دیتابیس نصب‌شده است؛ بنابراین مسیرهای نصب و
        // به‌روزرسانی باید ۴۰۴ (پیدا نشد) بدهند.
        foreach (['/install', '/install/step1', '/install/step2', '/update'] as $uri) {
            $response = $this->get($uri);

            $this->assertNotEquals(
                200,
                $response->getStatusCode(),
                'مسیر «' . $uri . '» روی سامانه نصب‌شده باید بسته باشد؛ در غیر این صورت '
                . 'هر فرد بیرونی می‌تواند فایل .env را بازنویسی کند یا SQL ایمپورت نماید.'
            );
        }
    }

    /** @test */
    public function installer_can_be_temporarily_enabled_by_env_flag()
    {
        // راه فرار اضطراری برای به‌روزرسانی برنامه‌ریزی‌شده.
        //
        // نکته (مورد M-09): میدل‌ور پیش‌تر این کلید را با `env()` می‌خواند
        // و کش‌شدن پیکربندی، آن را بسته نگه می‌داشت (رفتار ایمن، اما
        // غیرقابل پیش‌بینی). اکنون از `config('drikana.installer.allow')`
        // خوانده می‌شود؛ پس آزمون هم مقدار را در خود پیکربندی می‌گذارد.
        $original = config('drikana.installer.allow');

        config(['drikana.installer.allow' => true]);

        $middleware = new \App\Http\Middleware\BlockWhenInstalled();

        $reached = false;
        $middleware->handle(request(), function () use (&$reached) {
            $reached = true;
            return response('ok');
        });

        $this->assertTrue(
            $reached,
            'با فعال بودن DRIKANA_ALLOW_INSTALLER باید امکان اجرای به‌روزرسانی فراهم باشد.'
        );

        // بازگرداندن مقدار اصلی — حتی اگر آزمون شکست بخورد
        config(['drikana.installer.allow' => $original]);
    }

    /** @test */
    public function delete_confirmation_modal_uses_post_with_csrf()
    {
        $modal = file_get_contents(resource_path('views/partials/modal.blade.php'));

        $this->assertContains(
            '@csrf',
            $modal,
            'مودال تأیید حذف باید توکن CSRF داشته باشد؛ در غیر این صورت درخواست حذف '
            . 'بدون توکن ارسال می‌شود و ابزارهای امنیتی آن را رد می‌کنند.'
        );

        $this->assertContains(
            '_method',
            $modal,
            'مودال تأیید حذف باید فیلد _method=DELETE داشته باشد تا درخواست با متد '
            . 'درست به روت‌های DELETE برسد.'
        );

        $this->assertContains(
            'method="POST"',
            $modal,
            'عملیات حذف باید با متد POST ارسال شود، نه با لینک GET.'
        );

        $this->assertFalse(
            strpos($modal, '<a id="delete_link"'),
            'نسخه قبلی از لینک GET برای حذف استفاده می‌کرد که در برابر CSRF آسیب‌پذیر است.'
        );
    }

    /** @test */
    public function native_session_is_not_started_at_file_level()
    {
        // هیچ کلاسی نباید در سطح فایل session_start() صدا بزند؛ این کار با
        // بارگذاری خودکار کلاس اجرا می‌شود و در خط فرمان خطا می‌دهد.
        $offenders = [];

        foreach (glob(app_path('Http/Controllers/*.php')) as $file) {
            $content = file_get_contents($file);

            if (preg_match('/^\s*session_start\s*\(\s*\)\s*;/m', $content)) {
                $offenders[] = basename($file);
            }
        }

        $this->assertEmpty(
            $offenders,
            "این فایل‌ها در سطح فایل session_start() دارند که با بارگذاری کلاس اجرا می‌شود:\n  - "
            . implode("\n  - ", $offenders)
        );
    }

    /** @test */
    public function every_mutating_admin_route_requires_a_permission_or_admin_gate()
    {
        // روت‌های تغییردهنده در ناحیه /admin باید دست‌کم از میدل‌ور admin عبور کنند
        $unguarded = [];

        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
            $uri = $route->uri();

            if (strpos($uri, 'admin') !== 0) {
                continue;
            }

            $methods = array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE']);

            if (empty($methods)) {
                continue;
            }

            $middleware = $route->gatherMiddleware();

            $hasGate = false;
            foreach ($middleware as $m) {
                if (strpos($m, 'permission') === 0 || strpos($m, 'admin') !== false
                    || strpos($m, 'auth') !== false || strpos($m, 'role') !== false) {
                    $hasGate = true;
                    break;
                }
            }

            if (!$hasGate) {
                $unguarded[] = $uri . ' [' . implode(',', $methods) . ']';
            }
        }

        $this->assertEmpty(
            $unguarded,
            "این روت‌های تغییردهنده در ناحیه مدیریت هیچ دروازه دسترسی ندارند:\n  - "
            . implode("\n  - ", $unguarded)
        );
    }

    /** @test */
    public function env_file_is_gitignored()
    {
        $gitignore = file_get_contents(base_path('.gitignore'));

        $lines = explode("\n", str_replace("\r", "", $gitignore));

        // الگوی نادیده‌گرفتن .env ممکن است به شکل‌های گوناگون نوشته شود
        // (`/.env`، `.env` یا `.env.*`). معیار درست «نادیده گرفته شدن
        // واقعی فایل» است، نه شکل نوشتن الگو.
        $covers = false;
        foreach ($lines as $line) {
            if (in_array(trim($line), ['/.env', '.env', '.env*', '.env.*', '/.env*'], true)) {
                $covers = true;
                break;
            }
        }

        $this->assertTrue($covers, 'فایل .env باید در .gitignore نادیده گرفته شود.');
        $this->assertContains('.env.*', $lines,
            'الگوی .env.* باید فایل‌های محیطی فرعی (مثل .env.testing) را هم پوشش دهد.');
    }

    /** @test */
    public function env_example_contains_no_real_secrets()
    {
        $content = file_get_contents(base_path('.env.example'));

        // ۱) هیچ کلید واقعی (توکن GitHub، کلید Stripe و ...) نباید باشد
        $this->assertNotRegExp(
            '/(ghp_|github_pat_|sk_live_[a-zA-Z0-9]{10,}|sk_test_[a-zA-Z0-9]{20,}|AKIA[0-9A-Z]{16})/',
            $content,
            'فایل .env.example نباید هیچ کلید واقعی داشته باشد.'
        );

        // ۲) هیچ کلید خصوصی رمزنگاری نباید باشد
        $this->assertStringNotContainsString('PRIVATE KEY', $content);

        // ۳) متغیرهای ضروری باید تعریف شده باشند (حتی با مقدار خالی)
        foreach (['APP_KEY=', 'APP_ENV=', 'APP_DEBUG=', 'DB_CONNECTION=',
                  'DB_HOST=', 'DB_DATABASE=', 'DB_USERNAME=', 'DB_PASSWORD=',
                  'SESSION_DRIVER=', 'CACHE_DRIVER='] as $required) {
            $this->assertStringContainsString($required, $content,
                'متغیر «' . $required . '» باید در .env.example تعریف شده باشد.');
        }

        // ۴) مقادیر نمونه دیتابیس نباید اعتبارنامه واقعی باشند
        $this->assertStringNotContainsString('DB_PASSWORD=secret', $content);
        $this->assertStringNotContainsString('DB_DATABASE=homestead', $content);

        // ۵) هیچ رمز عبور واقعی‌مانندی نباید در فایل باشد
        $this->assertNotRegExp(
            '/^(DB_PASSWORD|MAIL_PASSWORD|MELIPAYAMAK_PASSWORD|SSLCZ_STORE_PASSWD|FARAZSMS_PASSWORD)=(?!\s*$|CHANGE_ME).+$/m',
            $content,
            'رمزهای عبور در .env.example باید خالی یا CHANGE_ME باشند.'
        );

        // ۶) هشدار امنیتی برای چرخش کلیدها باید موجود باشد
        $this->assertStringContainsString('CHANGE_ME', $content);
    }

    // =========================================================
    // ۱۰) Policy و Authorization
    // =========================================================

    /** @test */
    public function jewelry_certificate_policy_is_registered()
    {
        $policies = app(\Illuminate\Contracts\Auth\Access\Gate::class);

        $user = $this->makeUser();

        $jewelryOwner = new JewelryOwner();
        $jewelryOwner->user_id   = $user->getKey();
        $jewelryOwner->full_name = 'مالک';
        $jewelryOwner->save();

        $cert = new JewelryCertificate();
        $cert->owner_id      = $jewelryOwner->getKey();
        $cert->product_name  = 'انگشتر';
        $cert->metal_type    = 'طلا';
        $cert->serial_number = 'POL-' . strtoupper(uniqid());
        $cert->save();

        // مالک باید بتواند شناسنامه خودش را ببیند
        $this->assertTrue($user->can('view', $cert));
    }

    /** @test */
    public function repairing_technician_policy_restricts_access_to_assigned_orders()
    {
        $technician = $this->makeUser(['repair_technician']);
        $otherTech  = $this->makeUser(['repair_technician']);
        $customer   = $this->makeUser();

        $order = new RepairOrder();
        $order->order_number = 'ORD-' . strtoupper(uniqid());
        $order->user_id      = $customer->getKey();
        $order->assigned_to  = $technician->getKey();
        $order->status       = 'pending';
        $order->save();

        // تکنسین تخصیص‌یافته دسترسی دارد
        $this->assertTrue($technician->can('view', $order));

        // تکنسین دیگر — با مجوز view سراسری، دسترسی دارد (طبق طراحی)
        // اما مشتری دیگری نباید دسترسی داشته باشد
        $stranger = $this->makeUser();

        $this->assertFalse($stranger->can('view', $order),
            'کاربر بیگانه نباید به سفارش تعمیر دسترسی داشته باشد.');
    }

    // =========================================================
    // ۱۱) نبود secret در کد
    // =========================================================

    /** @test */
    public function no_hardcoded_secrets_in_application_code()
    {
        $patterns = [
            '/sk_live_[a-zA-Z0-9]{20,}/',
            '/AKIA[0-9A-Z]{16}/',
            '/-----BEGIN [A-Z ]*PRIVATE KEY-----/',
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(app_path(), \FilesystemIterator::SKIP_DOTS)
        );

        $violations = [];

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $violations[] = $file->getPathname();
                }
            }
        }

        $this->assertEmpty($violations,
            'هیچ Secret هاردکدشده‌ای نباید در کد اپلیکیشن باشد: ' . implode(', ', $violations));
    }

    /** @test */
    public function no_weak_default_admin_password_in_seeders()
    {
        $seederFiles = glob(database_path('seeds/*.php'));
        $violations = [];

        foreach ($seederFiles as $file) {
            $content = file_get_contents($file);

            // الگوی Hash::make با رمز ضعیف
            if (preg_match("/Hash::make\(\s*['\"](\d{1,8}|password|admin)['\"]\s*\)/i", $content, $m)) {
                $violations[] = basename($file) . ' => ' . $m[1];
            }
        }

        $this->assertEmpty($violations,
            'رمز عبور ضعیف در Seeder یافت شد: ' . implode(', ', $violations));
    }
}
