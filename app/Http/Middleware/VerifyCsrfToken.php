<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // ── استثناهای CSRF — هر مورد باید دلیل مستند داشته باشد ───────
        //
        // [C-04] '/config_content' از این فهرست حذف شد. آن مسیر دیگر
        // وجود ندارد؛ استثنای CSRF روی آن یعنی راهی بدون توکن به یک
        // عملیات «نوشتن فایل روی مسیر دلخواه».
        //
        // بازگشت درگاه پرداخت: درگاه مستقیماً به این آدرس‌ها POST
        // می‌کند و توکن CSRF ندارد. اعتبارسنجی این مسیرها با بررسی
        // تراکنش (verify) نزد درگاه انجام می‌شود، نه با CSRF.
        '/sslcommerz/success', '/sslcommerz/cancel', '/sslcommerz/fail', '/sslcommerz/ipn',
        '/paytm*',
    ];
}
