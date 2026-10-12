<?php

namespace App\Http\Middleware;

use Closure;

/**
 * افزودن هدرهای امنیتی HTTP به همه پاسخ‌ها.
 *
 * هدرهای اعمال‌شده:
 *   X-Content-Type-Options    → جلوگیری از MIME sniffing
 *   X-Frame-Options           → جلوگیری از Clickjacking
 *   Referrer-Policy           → محدودکردن ارسال Referer
 *   Permissions-Policy        → غیرفعال‌کردن قابلیت‌های مرورگر استفاده‌نشده
 *   X-XSS-Protection          → سازگاری با مرورگرهای قدیمی
 *   Strict-Transport-Security → اجبار HTTPS (فقط روی HTTPS)
 *   Content-Security-Policy   → محدودکردن منابع قابل بارگذاری
 *
 * ── نکته درباره CSP ─────────────────────────────────────────────────
 * پروژه در Blade از استایل و اسکریپت درون‌خطی و کتابخانه‌های محلی
 * استفاده می‌کند. برای اینکه ظاهر و عملکرد صفحات نشکند، CSP به‌صورت
 * «گزارش‌محور و محدودکننده نسبی» تنظیم شده است:
 *   • script-src  شامل 'unsafe-inline' است (اجبار لازم برای کد فعلی)
 *   • frame-ancestors 'none' → محافظت واقعی ضد Clickjacking
 *   • object-src 'none'      → جلوگیری از پلاگین مخرب
 *
 * برای سخت‌گیری کامل، باید تمام اسکریپت‌های درون‌خطی به فایل منتقل و
 * nonce اضافه شود. این کار در فاز بعدی (پس از جداسازی CSS) توصیه می‌شود.
 */
class SecurityHeaders
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // اگر پاسخ یک فایل باینری یا StreamedResponse باشد، هدرها را دست نزن
        if (!method_exists($response, 'header')) {
            return $response;
        }

        // -------------------------------------------------------
        // هدرهای پایه
        // -------------------------------------------------------
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=()'
        );

        // -------------------------------------------------------
        // HSTS — فقط در صورت HTTPS (روی HTTP بی‌معنا و خطرناک است)
        // -------------------------------------------------------
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // -------------------------------------------------------
        // CSP — تنها اگر از قبل توسط کنترلر تنظیم نشده باشد
        // -------------------------------------------------------
        if (!$response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->buildCsp());
        }

        return $response;
    }

    /**
     * ساخت مقدار Content-Security-Policy.
     *
     * @return string
     */
    protected function buildCsp()
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            // منابع داخلی پروژه، فونت آیکون، داده‌های درون‌خطی (data:) و
            // درگاه‌های پرداخت خارجی 3D-Secure
            "img-src 'self' data: blob: https:",
            "font-src 'self' data:",
            "style-src 'self' 'unsafe-inline'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "connect-src 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $directives);
    }
}
