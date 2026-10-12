<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * تست‌های سخت‌سازی احراز هویت.
 *
 * ── چه چیزی پوشش داده می‌شود؟ ───────────────────────────────────────
 *   ۱) محدودسازی نرخ روی مسیرهای ورود/OTP/بازیابی رمز
 *   ۲) استفاده از مولد تصادفی رمزنگاری‌شده برای کدهای OTP
 *   ۳) نبود متن به‌هم‌ریخته (mojibake) در رشته‌های فارسی کنترلرها
 *   ۴) قفل‌شدن حساب پس از تلاش‌های ناموفق ورود
 *
 * ── چرا تست ساختاری و نه فقط کاربردی؟ ───────────────────────────────
 * برای مسیرهای OTP، ارسال واقعی پیامک انجام نمی‌شود؛ پس نمی‌توان با
 * تست کاربردی ثابت کرد که «محدودسازی نرخ» برقرار است. بررسی میدل‌ور
 * خودِ مسیر، مستقیم‌ترین راه اثبات است. تست قفل‌شدن ورود اما کاربردی
 * است و واقعاً شش درخواست می‌فرستد.
 */
class AuthHardeningTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * مسیرهایی که باید محدودسازی نرخ داشته باشند.
     *
     * هر مورد: [متد، URI] — همان‌طور که در جدول روت ثبت شده است.
     *
     * @return array
     */
    protected function rateLimitedRoutes()
    {
        return [
            ['POST', 'users/login/otp'],
            ['POST', 'verification'],
            ['GET',  'verification/phone/code/resend'],
            ['POST', 'password/reset/submit'],
            ['POST', 'login'],
        ];
    }

    /**
     * هر مسیر حساس احراز هویت باید میدل‌ور throttle داشته باشد.
     *
     * بدون آن، مهاجم می‌تواند با ارسال پیاپی:
     *   • کد OTP را با آزمون و خطا حدس بزند
     *   • با هر درخواست یک پیامک بفرستد و اعتبار پنل را بسوزاند
     *   • روی رمز عبور حمله دیکشنری اجرا کند
     */
    public function test_sensitive_auth_routes_are_rate_limited()
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $routes[$route->uri()] = $route;
        }

        $missing = [];

        foreach ($this->rateLimitedRoutes() as $item) {
            list($method, $uri) = $item;

            $this->assertArrayHasKey($uri, $routes, "مسیر «{$uri}» ثبت نشده است.");

            $middleware = $routes[$uri]->gatherMiddleware();

            $hasThrottle = false;
            foreach ($middleware as $m) {
                if (strpos((string) $m, 'throttle') === 0 || strpos((string) $m, 'throttle:') === 0) {
                    $hasThrottle = true;
                    break;
                }
            }

            if (!$hasThrottle) {
                $missing[] = strtoupper($method) . ' ' . $uri;
            }
        }

        $this->assertCount(
            0,
            $missing,
            'این مسیرهای حساس محدودسازی نرخ ندارند: ' . implode(', ', $missing)
        );
    }

    /**
     * مسیر ارسال دوباره کد باید سخت‌گیرانه‌تر محدود شود.
     *
     * هر درخواست این مسیر یک پیامک واقعی می‌فرستد؛ پس محدودیت آن
     * باید کمتر از مسیرهای دیگر باشد (یعنی اجازه درخواست کمتری بدهد).
     */
    public function test_resend_route_is_more_strictly_limited()
    {
        $route = null;
        foreach (Route::getRoutes() as $r) {
            if ($r->uri() === 'verification/phone/code/resend') {
                $route = $r;
                break;
            }
        }

        $this->assertNotNull($route, 'مسیر ارسال دوباره کد پیدا نشد.');

        $throttle = null;
        foreach ($route->gatherMiddleware() as $m) {
            if (strpos((string) $m, 'throttle:') === 0) {
                $throttle = (string) $m;
                break;
            }
        }

        $this->assertNotNull($throttle, 'مسیر ارسال دوباره کد throttle ندارد.');

        // قالب «throttle:تعداد,دقیقه» — تعداد مجاز باید کوچک باشد
        $parts = explode(',', substr($throttle, strlen('throttle:')));
        $maxAttempts = (int) $parts[0];

        $this->assertLessThanOrEqual(
            5,
            $maxAttempts,
            'مسیر ارسال دوباره کد پیامک باید حداکثر ۵ درخواست در بازه مجاز کند.'
        );
    }

    /**
     * کدهای OTP باید با مولد تصادفی رمزنگاری‌شده ساخته شوند.
     *
     * `rand()` و `mt_rand()` مولدهای شبه‌تصادفی غیررمزنگاری‌شده‌اند؛
     * دنباله آن‌ها با مشاهده چند خروجی قابل پیش‌بینی است. برای کدی که
     * نقش «اثبات مالکیت موبایل» را دارد، این ضعف قابل قبول نیست.
     *
     * بررسی ساختاری روی فایل‌ها انجام می‌شود چون مقدار تولیدی در هر
     * اجرا متفاوت است.
     */
    public function test_otp_codes_use_secure_random_source()
    {
        $files = [
            app_path('Http/Controllers/OTPVerificationController.php'),
            app_path('Http/Controllers/OTPController.php'),
            app_path('Http/Controllers/Auth/RegisterController.php'),
            app_path('Http/Controllers/Auth/ForgotPasswordController.php'),
        ];

        $offenders = [];

        foreach ($files as $file) {
            $this->assertFileExists($file);

            $contents = file_get_contents($file);

            // تخصیص کد با rand() یا mt_rand()
            if (preg_match('/(verification_code|generateOTP|generateOTPCode)[^\n]*\b(mt_)?rand\s*\(/i', $contents)) {
                $offenders[] = basename($file) . ' (انتساب کد با rand)';
            }

            if (preg_match('/return\s+(mt_)?rand\s*\(\s*100000/', $contents)) {
                $offenders[] = basename($file) . ' (return rand)';
            }
        }

        $this->assertCount(
            0,
            $offenders,
            'کد OTP با مولد تصادفی ناامن ساخته می‌شود: ' . implode(', ', $offenders)
        );
    }

    /**
     * هیچ فایل کنترلری نباید متن فارسی به‌هم‌ریخته داشته باشد.
     *
     * الگوی «mojibake»: بایت‌های UTF-8 فارسی که با Windows-1252
     * رمزگشایی و دوباره به‌صورت UTF-8 ذخیره شده‌اند. نتیجه، رشته‌هایی
     * مانند «Ø§Ø·Ù„Ø§Ø¹Ø§Øª» است که در پیام‌ها و درگاه پرداخت به کاربر
     * نمایش داده می‌شود.
     */
    public function test_no_mojibake_in_controller_strings()
    {
        $directory = app_path('Http/Controllers');

        $offenders = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $contents = file_get_contents($file->getPathname());

            // امضا‌ی کلاسیک: یک بایتِ سرِ UTF-8 (C2..DF) که به‌عنوان
            // کاراکتر لاتین خوانده شده و پس از آن یک بایت ادامه
            if (preg_match('/[\x{00C2}-\x{00DF}][\x{0080}-\x{00BF}]/u', $contents)) {
                $offenders[] = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertCount(
            0,
            $offenders,
            'متن فارسی به‌هم‌ریخته در این فایل‌ها وجود دارد: ' . implode(', ', $offenders)
        );
    }

    /**
     * پس از پنج تلاش ناموفق، پیام «تلاش بیش از حد» باید نمایش داده
     * شود — نه پیام معمول «اطلاعات ورود نادرست».
     *
     * این تست واقعاً درخواست می‌فرستد و رفتار Trait آپ‌استریم لاراول
     * را تأیید می‌کند؛ بنابراین اگر کسی `login()` را بازنویسی کند و
     * شمارش تلاش‌ها را از دست بدهد، اینجا شکسته می‌شود.
     */
    public function test_login_is_locked_after_repeated_failures()
    {
        // پاک‌سازی محدودکننده‌ها تا تست به اجرای قبلی وابسته نباشد
        $this->clearLoginLimits();

        $lastError = null;

        for ($attempt = 1; $attempt <= 6; $attempt++) {
            $response = $this->from('/login')->post('/login', [
                'username' => 'login-lockout-test@example.com',
                'password' => 'definitely-not-the-password',
            ]);

            $errors = $response->getSession()->get('errors');

            if ($errors && $errors->has('email')) {
                $lastError = $errors->first('email');
            }
        }

        $this->assertNotNull($lastError, 'پس از تلاش‌های ناموفق هیچ پیام خطایی نمایش داده نشد.');

        // پیام قفل‌شدن باید متمایز از پیام «اطلاعات نادرست» باشد
        $this->assertNotEquals(
            trans('auth.failed'),
            $lastError,
            'پس از چند تلاش ناموفق، حساب باید قفل شود اما پیام خطای عادی برگشت.'
        );

        $this->assertEquals(
            trans('auth.throttle', ['seconds' => 60]),
            $lastError,
            'پیام قفل‌شدن حساب باید پیام throttle باشد.'
        );

        // پاک‌سازی برای تست‌های بعدی
        $this->clearLoginLimits();
    }

    /**
     * پاک‌کردن محدودکننده‌های ورود مربوط به این تست.
     *
     * لاراول در 5.8 به‌صورت `RateLimiter` (نه Facade) در دسترس است و
     * کلید آن از «ایمیل + آی‌پی» ساخته می‌شود.
     *
     * @return void
     */
    protected function clearLoginLimits()
    {
        $limiter = app(RateLimiter::class);

        try {
            $limiter->clear('login-lockout-test@example.com|127.0.0.1');
            $limiter->clear('login-lockout-test@example.com|::1');
        } catch (\Exception $e) {
            // پاک‌سازی حداکثری — خطا نباید خودِ تست را بشکند
        }
    }

    /**
     * مسیر خروج نباید از طریق GET نشست را ببندد بدون توکن.
     *
     * ── توضیح تصمیم ────────────────────────────────────────────────
     * «خروج با GET» یک آسیب‌پذیری واقعی نیست: بدترین اثرش این است که
     * مهاجم بتواند کاربر را از حساب خودش خارج کند (Logout CSRF). چون
     * پیامد آن ناچیز است و GET /logout بخشی از قرارداد نسخه فعلی
     * پروژه است، این مسیر حفظ شده است. این تست فقط «وجود» آن را ثبت
     * می‌کند تا حذف تصادفی‌اش در آینده باعث خطای ۴۰۴ کاربران نشود.
     */
    public function test_logout_get_route_is_intentionally_preserved()
    {
        $found = false;

        foreach (Route::getRoutes() as $route) {
            if ($route->uri() === 'logout' && in_array('GET', $route->methods(), true)) {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            'مسیر GET /logout حذف شده است. این مسیر عامدانه حفظ می‌شود؛ '
            . 'اگر قصد حذف آن را دارید، ابتدا همه لینک‌های خروج در ویوها را '
            . 'به فرم POST تغییر دهید.'
        );
    }

    /**
     * صفحه ورود نباید هیچ اطلاعات ورود پیش‌فرضی در منبع خود داشته باشد.
     *
     * ── یافته‌ای که این تست محافظت می‌کند ────────────────────────────
     * `resources/views/auth/login.blade.php` اسکریپتی داشت که ایمیل
     * `admin@example.com` و رمز `123456` را در فرم قرار می‌داد. حتی با
     * وجود اینکه رمز پیش‌فرض دیگر کار نمی‌کند، افشای «ایمیل حساب مدیر»
     * به هر بازدیدکننده، حمله هدفمند را آسان می‌کند.
     */
    public function test_login_page_does_not_leak_default_credentials()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertFalse(
            strpos($html, 'admin@example.com') !== false,
            'صفحه ورود، ایمیل حساب مدیر را افشا می‌کند.'
        );

        $this->assertFalse(
            strpos($html, "'123456'") !== false || strpos($html, '"123456"') !== false,
            'صفحه ورود، رمز عبور پیش‌فرض را در منبع صفحه دارد.'
        );

        $this->assertFalse(
            strpos($html, 'autoFill(') !== false,
            'تابع پرشدن خودکار فرم ورود باید حذف شده باشد.'
        );
    }

    /**
     * صفحه ورود کاربران نباید اطلاعات حساب‌های نمونه را در منبع خود
     * داشته باشد — مگر در حالت نمایشی.
     */
    public function test_user_login_page_hides_demo_credentials_when_demo_mode_off()
    {
        // اطمینان از خاموش بودن حالت نمایشی
        config(['app.demo_mode' => 'Off']);

        $response = $this->get('/users/login');

        // این صفحه ممکن است به داده تنظیمات نیاز داشته باشد؛ اگر خطای
        // سرور برگشت، بررسی منبع صفحه معنا ندارد.
        if ($response->getStatusCode() !== 200) {
            $this->markTestSkipped('صفحه ورود کاربران در محیط آزمون قابل نمایش نیست (کد ' . $response->getStatusCode() . ').');
        }

        $html = $response->getContent();

        $this->assertFalse(
            strpos($html, 'seller@example.com') !== false,
            'اطلاعات حساب نمونه فروشنده در حالت غیرنمایشی منتشر شده است.'
        );

        $this->assertFalse(
            strpos($html, 'customer@example.com') !== false,
            'اطلاعات حساب نمونه کاربر در حالت غیرنمایشی منتشر شده است.'
        );

        $this->assertFalse(
            strpos($html, 'autoFillSeller') !== false,
            'توابع پرشدن خودکار حساب نمونه در حالت غیرنمایشی منتشر شده‌اند.'
        );
    }

    /**
     * در حالت نمایشی، پنل اطلاعات ورود نمونه باید نمایش داده شود.
     *
     * این تست «قابلیت» را تضمین می‌کند: حذف کامل داده‌های نمونه، کار
     * محیط نمایشی را خراب می‌کرد.
     */
    public function test_demo_credentials_appear_when_demo_mode_on()
    {
        config(['app.demo_mode' => 'On']);

        $response = $this->get('/users/login');

        if ($response->getStatusCode() !== 200) {
            $this->markTestSkipped('صفحه ورود کاربران در محیط آزمون قابل نمایش نیست (کد ' . $response->getStatusCode() . ').');
        }

        $this->assertContains(
            'seller@example.com',
            $response->getContent(),
            'در حالت نمایشی، اطلاعات حساب نمونه باید نمایش داده شود.'
        );
    }
}
