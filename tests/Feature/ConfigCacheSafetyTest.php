<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * آزمون نگهبان «ایمنی کش پیکربندی» — مورد M-09 گزارش امنیتی.
 *
 * ── مسئله ────────────────────────────────────────────────────────────
 *
 * در Laravel 5.8، بوت‌استر `LoadEnvironmentVariables` این‌گونه آغاز
 * می‌شود:
 *
 *     public function bootstrap(Application $app)
 *     {
 *         if ($app->configurationIsCached()) { return; }
 *         ... Dotenv ...->safeLoad();
 *     }
 *
 * یعنی وقتی `php artisan config:cache` اجرا شده باشد، فایل `.env`
 * **خوانده نمی‌شود**. هر فراخوانی `env()` در کد برنامه `null`
 * برمی‌گرداند — بی‌صدا، بی‌خطا، بی‌هیچ لاگی. نتیجه: پیامک تأیید ارسال
 * نمی‌شود، درگاه پرداخت کلید خود را پیدا نمی‌کند.
 *
 * راه‌حل استاندارد: `env()` تنها در پوشه `config/` صدا زده شود و کد
 * برنامه از `config()` استفاده کند.
 *
 * این آزمون آن قاعده را به‌صورت خودکار نگه می‌دارد تا کسی دوباره
 * `env()` را به کد برنامه اضافه نکند.
 */
class ConfigCacheSafetyTest extends TestCase
{
    /**
     * تنها استثناهای عامدانه.
     *
     * هر استثنا باید در همان فایل دلیل مستند داشته باشد. قاعده کلی:
     * مقداری که **نباید داخل فایل کش پیکربندی نوشته شود** یا کلیدش
     * **پویا** است، می‌تواند `env()` بماند.
     *
     * @var array<string,string>
     */
    protected $allowedExceptions = [
        // رمز مدیر کل: نوشتن آن در bootstrap/cache/config.php یعنی
        // رمز به‌صورت متن روشن روی دیسک ذخیره شود. پس عمداً در کد
        // فرمان (CLI و یک‌باره) خوانده می‌شود.
        'app/Console/Commands/MakeSuperAdmin.php' => 'رمز بوت‌استرپ نباید در کش پیکربندی نوشته شود',

        // نصب‌کننده: نام کلید از فرم نصب می‌آید و پویا است؛ این کد فقط
        // پیش از نصب و در نبود کش پیکربندی اجرا می‌شود.
        'app/Http/Controllers/InstallController.php' => 'نام کلید پویا است و تنها پیش از نصب اجرا می‌شود',
    ];

    /**
     * هیچ `env()` در کد برنامه باقی نمانده باشد.
     */
    public function test_application_code_does_not_call_env()
    {
        $violations = [];

        foreach ($this->phpFilesIn([app_path(), resource_path('views')]) as $path) {
            // استثناهای مستند
            $relative = str_replace(base_path() . '/', '', $path);
            if (array_key_exists($relative, $this->allowedExceptions)) {
                continue;
            }

            // پیش‌نویس‌های متنی که ویو کامپایل‌شده نیستند
            if (substr($path, -4) !== '.php') {
                continue;
            }

            $contents = file_get_contents($path);

            if (preg_match('/(?<![\w>$])env\s*\(/', $contents, $m)) {
                $violations[] = $relative;
            }
        }

        $this->assertSame(
            [],
            $violations,
            "این فایل‌ها در کد برنامه `env()` صدا می‌زنند. با اجرای "
            . "`php artisan config:cache` این فراخوانی‌ها `null` برمی‌گردانند "
            . "و قابلیت مربوطه بی‌صدا از کار می‌افتد.\n"
            . "راه درست: کلید را در `config/` تعریف و از `config()` استفاده کنید.\n"
            . "فایل‌های خاطی:\n  - " . implode("\n  - ", $violations)
        );
    }

    /**
     * استثناهای مستند باید واقعاً در فایل مربوطه `env()` داشته باشند؛
     * در غیر این صورت استثنا کهنه است و باید از فهرست برداشته شود.
     */
    public function test_documented_exceptions_are_still_accurate()
    {
        $stale = [];

        foreach ($this->allowedExceptions as $relative => $reason) {
            $path = base_path() . '/' . $relative;

            if (!is_file($path)) {
                $stale[] = $relative . ' (فایل وجود ندارد)';
                continue;
            }

            if (!preg_match('/(?<![\w>$])env\s*\(/', (string) file_get_contents($path))) {
                $stale[] = $relative . ' (دیگر `env()` ندارد — استثنا را بردارید)';
            }
        }

        $this->assertSame(
            [],
            $stale,
            "استثناهای کهنه در ConfigCacheSafetyTest:\n  - " . implode("\n  - ", $stale)
        );
    }

    /**
     * کلیدهایی که کد برنامه انتظار دارد، باید در `config/drikana.php`
     * وجود داشته باشند.
     */
    public function test_expected_config_keys_are_defined()
    {
        $required = [
            'drikana.sms.melipayamak.username',
            'drikana.sms.melipayamak.password',
            'drikana.sms.melipayamak.pattern_id',
            'drikana.sms.farazsms.username',
            'drikana.sms.farazsms.password',
            'drikana.sms.farazsms.from',
            'drikana.sms.farazsms.pattern_id',
            'drikana.sms.kavenegar.api_key',
            'drikana.sms.twilio.sid',
            'drikana.sms.twilio.auth_token',
            'drikana.sms.twilio.from',
            'drikana.sms.ssl_sms.user',
            'drikana.sms.local.sender_id',
            'drikana.payments.paypal.client_id',
            'drikana.payments.paypal.client_secret',
            'drikana.payments.braintree.merchant_id',
            'drikana.payments.paystack.secret_key',
            'drikana.payments.instamojo.api_key',
            'drikana.payments.zarinpal.merchant_code',
            'drikana.payments.stripe.secret',
            'drikana.payments.sslcommerz.store_id',
            'drikana.payments.sslcommerz.store_passwd',
            'drikana.payments.razorpay.key',
            'drikana.payments.razorpay.secret',
            'drikana.payments.voguepay.merchant_id',
            'drikana.tracking.google_analytics_id',
            'drikana.tracking.facebook_pixel_id',
            'drikana.tracking.facebook_page_id',
            'drikana.tracking.captcha_key',
            'drikana.debug.otp_code',
            'drikana.debug.show_otp',
            'drikana.default_language',
            'drikana.installer.allow',
            'drikana.seed_demo_data',
        ];

        foreach ($required as $key) {
            // وجود کلید با array_key_exists بررسی می‌شود، نه با مقدار —
            // چون مقدار می‌تواند عامدانه خالی (null) باشد و آن هم درست است.
            $this->assertTrue(
                $this->configKeyExists($key),
                'کلید پیکربندی «' . $key . '» در config/ تعریف نشده است.'
            );
        }
    }

    /**
     * آیا مسیر کلید در پیکربندی وجود دارد؟ (مستقل از خالی‌بودن مقدار)
     *
     * @param  string $key
     * @return bool
     */
    protected function configKeyExists($key)
    {
        $segments = explode('.', $key);

        // توجه: `config()` بدون آرگومان، خودِ شیء Repository را برمی‌گرداند
        // (نه آرایه). پس از ریشه شروع می‌کنیم.
        $cursor = config(array_shift($segments));

        foreach ($segments as $segment) {
            if (!is_array($cursor) || !array_key_exists($segment, $cursor)) {
                return false;
            }
            $cursor = $cursor[$segment];
        }

        return true;
    }

    /**
     * کلیدهایی که پنل مدیریت در `.env` می‌نویسد باید در `.env.example`
     * هم باشند تا استقرار تازه آن‌ها را ببیند.
     */
    public function test_env_example_covers_integration_keys()
    {
        $example = (string) file_get_contents(base_path('.env.example'));

        $keys = [
            'MELIPAYAMAK_USERNAME', 'MELIPAYAMAK_PASSWORD', 'MELIPAYAMAK_PATTERN_ID',
            'FARAZSMS_USERNAME', 'FARAZSMS_PASSWORD', 'FARAZSMS_FROM', 'FARAZSMS_PATTERN_ID',
            'KAVENEGAR_API_KEY',
            'TWILIO_SID', 'TWILIO_AUTH_TOKEN', 'VALID_TWILLO_NUMBER',
            'SSL_SMS_USER', 'SSL_SMS_PASSWORD', 'SSL_SMS_SID', 'SSL_SMS_URL',
            'PAYPAL_CLIENT_ID', 'PAYPAL_CLIENT_SECRET',
            'BRAINTREE_ENVIRONMENT', 'BRAINTREE_MERCHANT_ID',
            'BRAINTREE_PUBLIC_KEY', 'BRAINTREE_PRIVATE_KEY',
            'STRIPE_KEY', 'STRIPE_SECRET', 'STRIPE_SECRET_KEY',
            'RAZOR_KEY', 'RAZOR_SECRET',
            'DEBUG_OTP_CODE', 'SHOW_DEBUG_OTP', 'DEFAULT_LANGUAGE',
            'CAPTCHA_KEY', 'TRACKING_ID',
            'FACEBOOK_PAGE_ID', 'FACEBOOK_PIXEL_ID',
        ];

        $missing = [];

        foreach ($keys as $key) {
            if (!preg_match('/^' . preg_quote($key, '/') . '=/m', $example)) {
                $missing[] = $key;
            }
        }

        $this->assertSame(
            [],
            $missing,
            "این کلیدها در `.env.example` نیستند؛ مستقرکننده نمی‌داند باید مقدار بگیرند:\n  - "
            . implode("\n  - ", $missing)
        );
    }

    /**
     * `.env.example` نباید هیچ مقدار حساس یا رمز واقعی داشته باشد.
     */
    public function test_env_example_has_no_real_secrets()
    {
        $lines = file(base_path('.env.example'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // مقادیر جانگهدار (placeholder) که «رمز واقعی» نیستند.
        $placeholders = ['CHANGE_ME', 'change_me', 'your_password', 'the_sample_value', 'null', '""'];

        $suspicious = [
            '/^APP_KEY=base64:[A-Za-z0-9+\/]{30,}/',
            '/^DB_PASSWORD=(?!$|"")[^\s]{3,}/',
            '/^MAIL_PASSWORD=(?!$|"")[^\s]{3,}/',
            '/ghp_[A-Za-z0-9]{20,}/',
            '/github_pat_[A-Za-z0-9_]{20,}/',
            '/sk_live_[A-Za-z0-9]{10,}/',
            '/AKIA[0-9A-Z]{16}/',
        ];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (strpos($trimmed, '#') === 0) {
                continue;
            }

            // خطی که مقدارش یکجانگهدار شناخته‌شده است، مشکوک نیست
            $value = trim(substr($trimmed, (int) strpos($trimmed, '=') + 1));
            if (in_array($value, $placeholders, true)) {
                continue;
            }

            foreach ($suspicious as $pattern) {
                $this->assertNotRegExp(
                    $pattern,
                    $line,
                    '`.env.example` نباید مقدار حساس واقعی داشته باشد. خط مشکوک: ' . $line
                );
            }
        }
    }

    /**
     * فهرست همه فایل‌های PHP زیر مسیرهای داده‌شده.
     *
     * @param  array $roots
     * @return array
     */
    protected function phpFilesIn(array $roots)
    {
        $files = [];

        foreach ($roots as $root) {
            if (!is_dir($root)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $files[] = $file->getPathname();
                }
            }
        }

        return $files;
    }
}
