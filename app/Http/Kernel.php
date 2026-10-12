<?php

namespace App\Http;

use App\Http\Middleware\CheckAllPermissions;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSeller;
use App\Http\Middleware\IsUser;
use App\Http\Middleware\CheckoutMiddleware;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        // [امنیت - L-06] افزودن هدرهای امنیتی HTTP به تمام پاسخ‌ها
        SecurityHeaders::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * ── تغییرات امنیتی این بخش ───────────────────────────────────────
     *
     * ۱) EncryptCookies فعال شد (M-01).
     *    پیش از این کامنت بود؛ یعنی کوکی نشست بصورت متن ساده به مرورگر
     *    می‌رفت و قابل خواندن/دستکاری بود.
     *
     * ۲) AuthenticateSession فعال شد (M-01).
     *    بدون آن، تغییر رمز عبور نشست‌های فعال در دستگاه‌های دیگر را باطل
     *    نمی‌کرد و مهاجم پس از سرقت نشست، دسترسی‌اش را از دست نمی‌داد.
     *
     * ۳) HttpsProtocol فعال نشد چون در محیط فعلی ممکن است نصب روی HTTP
     *    باشد و پروژه را از دسترس خارج کند. فعال‌سازی آن بر عهده مدیر
     *    استقرار است — دستورالعمل در docs/deployment.md آمده است.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Language::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],

        // ─────────────────────────────────────────────────────────────
        // [رفع باگ بحرانی] اینجا پیش‌تر یک گروه به نام «admin» تعریف شده
        // بود که خودش به میدل‌ور «admin» ارجاع می‌داد:
        //
        //     'admin' => ['auth', 'admin'],   // ← بازگشت بی‌نهایت!
        //
        // چون نام گروه و نام alias میدل‌ور یکسان بود،
        // MiddlewareNameResolver هنگام resolve کردن «admin» ابتدا آن را
        // به‌عنوان گروه می‌شناخت و دوباره به خودش می‌رسید. نتیجه: مصرف
        // بی‌نهایت حافظه و Fatal error (Allowed memory size exhausted).
        //
        // این گروه حذف شد. رفتار موردنظر قبلاً با نوشتن صریح
        // `->middleware(['auth', 'admin'])` در routes/admin.php به‌درستی
        // محقق شده است و نیازی به گروه جداگانه نیست.
        // ─────────────────────────────────────────────────────────────
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ---- مدیرت دسترسی (RBAC) ----
        'permission'     => CheckPermission::class,
        'permission_all' => CheckAllPermissions::class,
        'role'           => CheckRole::class,

        // ---- نقش‌های موجود پروژه ----
        'admin' => IsAdmin::class,
        // مسدودسازی صفحه‌های نصب/به‌روزرسانی روی سامانه نصب‌شده
        'not_installed' => \App\Http\Middleware\BlockWhenInstalled::class,
        'seller' => IsSeller::class,
        'user' => IsUser::class,
        'checkout' => CheckoutMiddleware::class,

        // ---- میدل‌ورهای فریم‌ورک ----
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'verification' => \App\Http\Middleware\MyVerify::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
