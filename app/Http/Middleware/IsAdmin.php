<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * میدل‌ور «ورود به ناحیه مدیریت» (Back-office gate).
 *
 * ════════════════════════════════════════════════════════════════════
 *  جایگاه این میدل‌ور در معماری دسترسی
 * ════════════════════════════════════════════════════════════════════
 *
 * این میدل‌ور فقط یک «دروازه ورودی» است؛ تصمیم نهایی درباره اینکه کاربر
 * چه کاری می‌تواند انجام دهد توسط میدل‌ور `permission:` و Policyها گرفته
 * می‌شود. بنابراین:
 *
 *     Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], ...)
 *     Route::get('products', 'ProductController@index')->middleware('permission:products.view');
 *
 * یعنی «ورود به ناحیه مدیریت» و «اجازه انجام یک عملیات خاص» دو لایه
 * جداگانه از کنترل دسترسی هستند (Defense in Depth).
 *
 * ── اصلاح مهم (Stage 2) ─────────────────────────────────────────────
 * نسخه قبلی این میدل‌ور تنها دو مقدار را می‌پذیرفت:
 *
 *     Auth::user()->user_type == 'admin'
 *     Auth::user()->user_type == 'staff'
 *
 * این محدودیت با سیستم RBAC جدید ناسازگار بود: یک کاربر با نقش
 * «مدیر محتوا» یا «تکنسین تعمیرات» که `user_type` او `customer` است،
 * هرگز نمی‌توانست به پنل مدیریت برسد — حتی اگر تمام مجوزهای لازم را
 * داشته باشد. یعنی RBAC عملاً بی‌اثر می‌شد.
 *
 * اکنون این میدل‌ور علاوه بر دو مقدار قدیمی، کاربرانی را که «حداقل یک
 * نقش فعال غیر از کاربر عادی» دارند نیز می‌پذیرد. سیاست پیش‌فرض
 * همچنان «Deny by Default» است:
 *
 *   • کاربر مهمان (لاگین‌نکرده)          → هدایت به صفحه ورود
 *   • کاربر بدون نقش فعال مدیریتی         → ۴۰۴ (پنهان‌سازی وجود پنل)
 *   • هر خطای غیرمنتظره در بررسی نقش      → ۴۰۴ (نه عبور)
 *
 * ── چرا ۴۰۴ و نه ۴۰۳؟ ───────────────────────────────────────────────
 * پاسخ ۴۰۳ وجود یک بخش مدیریتی را تأیید می‌کند و به مهاجم می‌گوید مسیر
 * صحیح است. پاسخ ۴۰۴ این اطلاعات را افشا نمی‌کند. پاسخ ۴۰۳ برای
 * کاربرانی که وارد شده‌اند اما مجوز یک عملیات خاص را ندارند، توسط
 * میدل‌ور `permission:` صادر می‌شود.
 *
 * ── ثبت رخداد ──────────────────────────────────────────────────────
 * هر تلاش ناموفق برای ورود به ناحیه مدیریت با شدت WARNING در
 * `audit_logs` ثبت می‌شود تا بتوان الگوهای تلاش نفوذ را شناسایی کرد.
 */
class IsAdmin
{
    /**
     * نقش‌هایی که به‌تنهایی مجوز ورود به ناحیه مدیریت را می‌دهند.
     * (نقش «کاربر عادی»/`customer` عامدانه در این فهرست نیست.)
     *
     * @var array<int,string>
     */
    protected static $backOfficeRoles = [
        'super_admin',
        'store_manager',
        'repair_technician',
        'ownership_manager',
        'content_manager',
    ];

    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    /**
     * @param \App\Services\AuditLogger $audit
     */
    public function __construct(AuditLogger $audit)
    {
        $this->audit = $audit;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // ── ۱) کاربر مهمان ────────────────────────────────────────────
        if ($user === null) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'احراز هویت لازم است.'], 401);
            }

            session(['link' => url()->current()]);

            return redirect()->route('user.login');
        }

        // ── ۲) بررسی دسترسی ───────────────────────────────────────────
        $allowed = false;

        try {
            $allowed = $this->isBackOfficeUser($user);
        } catch (\Exception $e) {
            // خطا در بررسی نقش → رد (هرگز عبور نده)
            report($e);
            $allowed = false;
        }

        if ($allowed) {
            // نکته: `$next` بیرون از بلوک try فراخوانی می‌شود تا
            // استثناهای کنترلر/ویوی پایین‌دستی به ۴۰۴ گمراه‌کننده تبدیل نشوند.
            return $next($request);
        }

        // ── ۳) ثبت تلاش ناموفق و پاسخ ۴۰۴ ─────────────────────────────
        try {
            $this->audit->log(
                AuditLog::EVENT_ACCESS_DENIED,
                'تلاش ناموفق برای ورود به ناحیه مدیریت: ' . $request->path(),
                $user,
                [],
                [],
                AuditLog::SEVERITY_WARNING
            );
        } catch (\Exception $e) {
            // ثبت لاگ نباید مانع پاسخ امنیتی شود
            report($e);
        }

        abort(404);
    }

    /**
     * آیا کاربر مجاز به ورود به ناحیه مدیریت است؟
     *
     * @param  \App\User $user
     * @return bool
     */
    protected function isBackOfficeUser($user)
    {
        // مسیر قدیمی پروژه — همچنان معتبر است
        if (in_array($user->user_type, ['admin', 'staff'], true)) {
            return true;
        }

        // مسیر جدید RBAC — کاربر باید حداقل یک نقش فعال مدیریتی داشته باشد
        if (method_exists($user, 'roleSlugs')) {
            $slugs = $user->roleSlugs();

            if (count(array_intersect($slugs, static::$backOfficeRoles)) > 0) {
                return true;
            }
        }

        return false;
    }
}
