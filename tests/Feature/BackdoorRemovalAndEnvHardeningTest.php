<?php

namespace Tests\Feature;

use App\Http\Controllers\BusinessSettingsController;
use App\Models\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * آزمون‌های حذف «در پشتی» و سخت‌سازی نویسنده فایل محیطی.
 *
 * ── پیشینه ───────────────────────────────────────────────────────────
 *
 * ۱) C-04: مسیر عمومی `POST /config_content` بدون احراز هویت و بدون
 *    توکن CSRF، پارامترهای خود را به تابعی می‌رساند که در نهایت
 *    `file_put_contents` را روی مسیری که کاربر تعیین می‌کرد اجرا
 *    می‌کرد. نتیجه: امکان نوشتن وب‌شل با یک درخواست HTTP.
 *
 * ۲) C-05: تابع `cartSetup()` با کد مبهم‌سازی‌شده، نشانی صفحه جاری را
 *    با `mail()` به یک نشانی جیمیل بیرونی می‌فرستاد (ارتباط پنهان).
 *
 * ۳) M-10: مسیر `POST /admin/env_key_update` هر کلید و مقداری را که در
 *    بدنه درخواست می‌آمد، بی‌بازبینی در فایل `.env` می‌نوشت.
 *
 * این فایل هر سه را می‌سنجد. نکته مهم: نام توابع و رشته‌های حساس
 * عامدانه با به‌هم‌چسباندن قطعات ساخته شده‌اند تا این فایل خودش
 * «حاوی» آن الگوها نباشد و آزمون دچار مثبت کاذب نشود.
 */
class BackdoorRemovalAndEnvHardeningTest extends TestCase
{
    use DatabaseTransactions;

    // ==================================================================
    // C-04 — مسیر نوشتن فایل روی مسیر دلخواه
    // ==================================================================

    /**
     * مسیر `/config_content` نباید در فهرست روت‌ها باشد.
     */
    public function test_config_content_route_no_longer_exists()
    {
        $paths = [];

        foreach (Route::getRoutes() as $route) {
            $paths[] = $route->uri();
        }

        $forbidden = 'config' . '_content';

        $this->assertNotContains(
            $forbidden,
            $paths,
            'مسیر ' . $forbidden . ' باید حذف شده باشد (نوشتن فایل بدون احراز هویت).'
        );
    }

    /**
     * تابع پشت آن مسیر نباید تعریف شده باشد.
     */
    public function test_arbitrary_file_write_helper_is_gone()
    {
        $fn = 'productDesc' . 'Cache';

        $this->assertFalse(
            function_exists($fn),
            'تابع ' . $fn . ' باید حذف شده باشد.'
        );
    }

    /**
     * توابع مبهم‌سازی نباید باقی مانده باشند — هیچ کد سالمی به آن‌ها
     * نیازی ندارد و وجودشان نشانه کد پنهان‌کار است.
     */
    public function test_obfuscation_helpers_are_gone()
    {
        $this->assertFalse(
            function_exists('loaded_class' . '_select'),
            'تابع مبهم‌سازی باید حذف شده باشد.'
        );

        $this->assertFalse(
            function_exists('config_key' . '_provider'),
            'تابع مبهم‌سازی باید حذف شده باشد.'
        );
    }

    /**
     * استثنای CSRF آن مسیر هم باید برداشته شده باشد.
     */
    public function test_config_content_is_not_csrf_exempt()
    {
        $middleware = app(\App\Http\Middleware\VerifyCsrfToken::class);

        $ref = new \ReflectionProperty($middleware, 'except');
        $ref->setAccessible(true);
        $except = $ref->getValue($middleware);

        $forbidden = '/config' . '_content';

        $this->assertNotContains(
            $forbidden,
            $except,
            'استثنای CSRF روی ' . $forbidden . ' یعنی راه بدون توکن به عملیات نوشتن فایل.'
        );
    }

    // ==================================================================
    // C-05 — ارتباط پنهان (ارسال نشانی صفحه به بیرون)
    // ==================================================================

    /**
     * تابع ارسال پنهان نباید وجود داشته باشد.
     */
    public function test_covert_mailer_helper_is_gone()
    {
        $this->assertFalse(
            function_exists('cart' . 'Setup'),
            'تابع ارتباط پنهان باید حذف شده باشد.'
        );

        $this->assertFalse(
            function_exists('updateCart' . 'Setup'),
            'تابع ارتباط پنهان باید حذف شده باشد.'
        );
    }

    /**
     * نشانی گیرنده نباید در هیچ فایل PHP پروژه باقی مانده باشد.
     */
    public function test_no_reference_to_third_party_recipient()
    {
        $needle = 'gzhandler@' . 'gmail.com';
        $hits   = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path())
        );

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = $file->getPathname();

            // پوشه‌های وابستگی و آزمون‌ها از جست‌وجو کنار گذاشته می‌شوند
            if (strpos($path, '/vendor/') !== false
                || strpos($path, '/tests/') !== false
                || strpos($path, '/.git/') !== false) {
                continue;
            }

            if (strpos((string) file_get_contents($path), $needle) !== false) {
                $hits[] = str_replace(base_path() . '/', '', $path);
            }
        }

        $this->assertSame(
            [],
            $hits,
            'نشانی گیرنده ارتباط پنهان در این فایل‌ها باقی مانده است: ' . implode(', ', $hits)
        );
    }

    // ==================================================================
    // M-10 — سخت‌سازی نویسنده فایل .env
    // ==================================================================

    /**
     * سازنده کنترلر برای دسترسی به متدهای محافظت‌شده.
     *
     * @return BusinessSettingsController
     */
    protected function controller()
    {
        return new BusinessSettingsController();
    }

    public function test_unsafe_env_values_are_rejected()
    {
        $method = new \ReflectionMethod($this->controller(), 'isSafeEnvValue');
        $method->setAccessible(true);

        $unsafe = [
            "smtp.example.com\nAPP_DEBUG=true",   // تزریق کلید تازه
            "value\r\nMAIL_DRIVER=log",
            'quoted"value',
            "single'quote",
            'back\\slash',
            "nul\x00byte",
            "tab\there",
        ];

        foreach ($unsafe as $value) {
            $this->assertFalse(
                $method->invoke($this->controller(), $value),
                'این مقدار باید رد شود: ' . str_replace(["\n", "\r", "\0", "\t"], ['\\n', '\\r', '\\0', '\\t'], $value)
            );
        }
    }

    public function test_safe_env_values_are_accepted()
    {
        $method = new \ReflectionMethod($this->controller(), 'isSafeEnvValue');
        $method->setAccessible(true);

        $safe = [
            '',
            'smtp.gmail.com',
            '587',
            'someone@example.com',
            'a-very_normal.value-123',
            'مقدار فارسی',
        ];

        foreach ($safe as $value) {
            $this->assertTrue(
                $method->invoke($this->controller(), $value),
                'این مقدار باید پذیرفته شود: ' . $value
            );
        }
    }

    /**
     * کلیدهای خطرناک نباید در فهرست قابل‌نوشتن باشند.
     */
    public function test_dangerous_env_keys_are_not_writable()
    {
        $method = new \ReflectionMethod($this->controller(), 'writableEnvKeys');
        $method->setAccessible(true);

        $allowed = $method->invoke($this->controller());

        $this->assertIsArray($allowed);
        $this->assertNotEmpty($allowed, 'فهرست کلیدهای قابل‌نوشتن نباید خالی باشد.');

        foreach ([
            'APP_KEY',
            'APP_ENV',
            'APP_DEBUG',
            'DB_PASSWORD',
            'DB_HOST',
            'SESSION_DRIVER',
            'SESSION_ENCRYPT',
            'CACHE_DRIVER',
            'QUEUE_CONNECTION',
            'DEMO_MODE',
        ] as $key) {
            $this->assertNotContains(
                $key,
                $allowed,
                'کلید ' . $key . ' نباید از پنل مدیریت نوشته شود.'
            );
        }
    }

    /**
     * کلیدهای مجاز واقعی باید در فهرست باشند تا پنل از کار نیفتد.
     */
    public function test_legitimate_env_keys_remain_writable()
    {
        $method = new \ReflectionMethod($this->controller(), 'writableEnvKeys');
        $method->setAccessible(true);

        $allowed = $method->invoke($this->controller());

        foreach (['MAIL_HOST', 'MAIL_PORT', 'GOOGLE_CLIENT_ID', 'TRACKING_ID'] as $key) {
            $this->assertContains(
                $key,
                $allowed,
                'کلید ' . $key . ' باید قابل‌نوشتن بماند.'
            );
        }
    }

    /**
     * مسیر به‌روزرسانی فایل محیطی باید مجوز `settings.update` بخواهد.
     */
    public function test_env_update_route_requires_settings_permission()
    {
        $route = Route::getRoutes()->getByName('env_key_update.update');

        $this->assertNotNull($route, 'مسیر env_key_update.update باید وجود داشته باشد.');

        $this->assertContains(
            'permission:settings.update',
            $route->gatherMiddleware(),
            'مسیر نوشتن در .env باید مجوز settings.update بخواهد.'
        );
    }

    // ==================================================================
    // آزمون سرتاسری: رد شدن تزریق از مسیر HTTP
    // ==================================================================

    /**
     * کاربر عادی نباید به مسیر نوشتن `.env` دسترسی داشته باشد.
     *
     * نکته: پاسخ مورد انتظار ۴۰۴ است، نه ۴۰۳. میدل‌ور `admin` عامدانه
     * برای کاربران بدون نقش پشتیبان ۴۰۴ برمی‌گرداند تا «وجود پنل
     * مدیریت» افشا نشود. این رفتار در `IsAdmin` مستند شده است.
     */
    public function test_env_update_route_is_blocked_for_plain_customer()
    {
        $user = factory(User::class)->create([
            'user_type' => 'customer',
            'email'     => 'env-blocked-' . uniqid() . '@example.test',
        ]);

        $response = $this->actingAs($user)->post(route('env_key_update.update'), [
            'types'     => ['MAIL_HOST'],
            'MAIL_HOST' => 'attacker.example.com',
        ]);

        $response->assertStatus(404);

        $this->assertFalse(
            strpos((string) file_get_contents(base_path('.env')), 'attacker.example.com') !== false,
            'فایل .env نباید با درخواست غیرمجاز تغییر کند.'
        );
    }

    /**
     * کاربر پشتیبان بدون مجوز `settings.update` نباید بتواند بنویسد.
     *
     * این آزمون «دفاع لایه‌ای» را می‌سنجد: میدل‌ور `admin` فقط دروازه
     * ورود به ناحیه مدیریت است؛ اجازه یک عملیات خاص از میدل‌ور
     * `permission:` می‌آید. اگر نقش پشتیبانی پیدا نشود که این مجوز را
     * نداشته باشد، آزمون رد می‌شود (skip) تا شکست کاذب نسازد.
     */
    public function test_env_update_route_requires_settings_permission_for_staff()
    {
        $candidates = Role::query()
            ->whereIn('slug', [
                'store_manager',
                'repair_technician',
                'ownership_manager',
                'content_manager',
            ])
            ->get();

        $this->assertNotEmpty($candidates, 'نقش‌های پشتیبان باید در دیتابیس موجود باشند.');

        $role = null;

        foreach ($candidates as $candidate) {
            $slugs = $candidate->permissionSlugs();
            if (!in_array('settings.update', $slugs, true)) {
                $role = $candidate;
                break;
            }
        }

        if ($role === null) {
            $this->markTestSkipped('هیچ نقش پشتیبانی بدون مجوز settings.update یافت نشد.');
        }

        $user = $this->makeStaffUser($role->slug);

        $response = $this->actingAs($user)->post(route('env_key_update.update'), [
            'types'     => ['MAIL_HOST'],
            'MAIL_HOST' => 'attacker.example.com',
        ]);

        $response->assertStatus(403);

        $this->assertFalse(
            strpos((string) file_get_contents(base_path('.env')), 'attacker.example.com') !== false,
            'فایل .env نباید با درخواست بدون مجوز تغییر کند.'
        );
    }

    /**
     * ساخت کاربر با نقش مشخص (بدون فعال‌سازی استثنای مدیر کل).
     *
     * @param  string $roleSlug
     * @return User
     */
    protected function makeStaffUser($roleSlug)
    {
        $user = new User();
        $user->name      = 'کاربر پشتیبان آزمون';
        $user->email     = 'staff' . uniqid() . '@example.test';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = 'customer';   // عامدانه غیر admin
        $user->role      = 'customer';
        $user->save();

        $roleIds = Role::query()->where('slug', $roleSlug)->pluck('id')->all();

        $payload = [];
        foreach ($roleIds as $roleId) {
            $payload[$roleId] = [
                'assigned_by' => null,
                'assigned_at' => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        $user->roles()->sync($payload);
        $user->flushPermissionCache();

        return $user->fresh();
    }
}
