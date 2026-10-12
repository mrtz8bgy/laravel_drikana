<?php

namespace App\Services\Security;

/**
 * سیاست رمز عبور (Password Policy).
 *
 * ── چرا یک سرویس مشترک؟ ─────────────────────────────────────────────
 * پیش از این، قواعد رمز عبور در سه جای مختلف تکرار شده بود:
 *   • Artisan Command `drikana:make-super-admin`
 *   • Seeder `DrikanaCoreInstallSeeder`
 *   • فرم‌های ثبت‌نام کاربر
 *
 * تکرار یعنی واگرایی: اگر قاعده‌ای در یکی سخت‌تر شود و در دیگری نه،
 * «ضعیف‌ترین مسیر» تبدیل به دروازه ورود می‌شود. این کلاس مرجع یگانه
 * قواعد رمز عبور مدیران است.
 *
 * ── قواعد ───────────────────────────────────────────────────────────
 *   ۱) حداقل ۱۲ کاراکتر (برای حساب‌های مدیریتی)
 *   ۲) نبودن در فهرست رمزهای رایج/لو‌رفته
 *   ۳) تنوع کاراکتر: حداقل سه نوع از چهار نوع
 *      (حرف کوچک، حرف بزرگ، رقم، نماد)
 *
 * ── آنچه این کلاس انجام نمی‌دهد ─────────────────────────────────────
 * رمز عبور را در هیچ‌جا ذخیره یا لاگ نمی‌کند. خروجی `generate()` تنها
 * یک بار به فراخوان بازگردانده می‌شود و مسئولیت نمایش/پاک‌کردن آن با
 * فراخوان است.
 */
class PasswordPolicy
{
    /**
     * حداقل طول رمز عبور برای حساب‌های مدیریتی.
     *
     * @var int
     */
    const MIN_LENGTH = 12;

    /**
     * رمزهای بسیار رایج که هنگام بررسی رد می‌شوند.
     *
     * این فهرست عمداً کوتاه و «کم‌هزینه» است: هدف، جلوگیری از
     * استفاده راحت از بدیهی‌ترین رمزهاست، نه اجرای یک سامانه کامل
     * سنجش قدرت رمز عبور.
     *
     * @var array<int,string>
     */
    protected static $weakPasswords = [
        '123456', '12345678', '123456789', '1234567890',
        'password', 'password123', 'passw0rd',
        'admin', 'admin123', 'admin1234', 'administrator',
        'qwerty', 'qwerty123', '111111', '123123', '000000',
        'letmein', 'welcome', 'iloveyou', 'abc123',
        'drikana', 'drikana123', 'root', 'test', 'test1234',
    ];

    /**
     * بررسی قدرت رمز عبور.
     *
     * @param  string|null $password
     * @return string|null پیام خطای فارسی، یا null اگر رمز قابل قبول باشد
     */
    public static function validate($password)
    {
        $password = (string) $password;

        if (mb_strlen($password) < self::MIN_LENGTH) {
            return 'رمز عبور باید حداقل ' . self::MIN_LENGTH . ' کاراکتر باشد.';
        }

        if (in_array(strtolower($password), self::$weakPasswords, true)) {
            return 'این رمز عبور بسیار رایج و ضعیف است. رمز دیگری انتخاب کنید.';
        }

        if (!static::meetsComplexity($password)) {
            return 'رمز عبور باید حداقل سه نوع کاراکتر از این چهار مورد داشته باشد: '
                 . 'حروف کوچک، حروف بزرگ، رقم، نماد.';
        }

        return null;
    }

    /**
     * آیا رمز عبور تنوع کاراکتری کافی دارد؟
     *
     * @param  string $password
     * @return bool
     */
    public static function meetsComplexity($password)
    {
        $variety = 0;
        $variety += preg_match('/[a-z]/', $password) ? 1 : 0;
        $variety += preg_match('/[A-Z]/', $password) ? 1 : 0;
        $variety += preg_match('/[0-9]/', $password) ? 1 : 0;
        $variety += preg_match('/[^a-zA-Z0-9]/', $password) ? 1 : 0;

        return $variety >= 3;
    }

    /**
     * ساخت رمز عبور تصادفی و قوی.
     *
     * ── چرا random_int؟ ─────────────────────────────────────────────
     * `rand()` و `mt_rand()` قابل پیش‌بینی‌اند. رمز عبوری که با آن‌ها
     * ساخته شود، حتی اگر طولانی باشد، امنیت واقعی نمی‌دهد.
     * `random_int()` از منبع تصادفی سیستم‌عامل تغذیه می‌شود.
     *
     * ── چرا هر چهار گروه اجباری است؟ ────────────────────────────────
     * اگر فقط از یک مجموعه بزرگ تصادفی انتخاب کنیم، احتمال اینکه رمز
     * تولیدی قاعده تنوع را رد کند کم است اما صفر نیست (مثلاً فقط ۰.۵٪
     * برای طول ۱۶). با اجباری‌کردن حداقل یک کاراکتر از هر گروه، خروجی
     * همیشه از فیلتر خودمان عبور می‌کند و نیازی به تلاش دوباره نیست.
     *
     * @param  int $length
     * @return string
     *
     * @throws \Exception اگر منبع تصادفی سیستم در دسترس نباشد
     */
    public static function generate($length = 16)
    {
        $length = max(self::MIN_LENGTH, (int) $length);

        $lower  = 'abcdefghijkmnopqrstuvwxyz';   // بدون l برای خوانایی
        $upper  = 'ABCDEFGHJKLMNPQRSTUVWXYZ';    // بدون I و O
        $digits = '23456789';                    // بدون 0 و 1
        $symbols = '!@#$%^&*-_=+?';
        $all = $lower . $upper . $digits . $symbols;

        // یک کاراکتر از هر گروه — تضمین تنوع
        $password = $lower[random_int(0, strlen($lower) - 1)]
                  . $upper[random_int(0, strlen($upper) - 1)]
                  . $digits[random_int(0, strlen($digits) - 1)]
                  . $symbols[random_int(0, strlen($symbols) - 1)];

        // باقی کاراکترها از مجموعه کامل
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        // جای کاراکترهای اجباری را جابه‌جا کن تا الگو قابل حدس نباشد
        return str_shuffle($password);
    }

    /**
     * عدد یکتا برای درج امن در متن‌های نمونه (بدون کاربرد امنیتی).
     *
     * @return string
     */
    public static function randomToken($length = 24)
    {
        $alphabet = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $token;
    }

    /**
     * فهرست رمزهای ضعیف — برای تست‌ها و مستندسازی.
     *
     * @return array<int,string>
     */
    public static function weakPasswords()
    {
        return self::$weakPasswords;
    }
}
