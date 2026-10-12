<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * میدل‌ور بررسی مجوز (Permission Middleware).
 *
 * استفاده در روت‌ها:
 *
 *     Route::get('/x')->middleware('permission:products.view');
 *     Route::get('/y')->middleware('permission:products.update,products.create');
 *
 * سیاست پیش‌فرض «Deny by Default» است:
 *   • کاربر مهمان (مهمان لاگین‌نکرده) → هدایت به صفحه ورود
 *   • کاربر لاگین‌شده بدون مجوز        → 403
 *   • هر خطای غیرمنتظره                 → 403 (نه عبور)
 *
 * چند مجوز با کاما یعنی «حداقل یکی کافی است» (OR). برای شرط AND از
 * `permission_all:` استفاده کنید.
 *
 * نکته امنیتی: این میدل‌ور فقط لایه اول است. کنترلرها نیز باید برای
 * عملیات حساس روی رکورد مشخص، Policy را فراخوانی کنند (دفاع لایه‌ای).
 */
class CheckPermission
{
    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    public function __construct(AuditLogger $audit)
    {
        $this->audit = $audit;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string                   $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = '')
    {
        $user = Auth::user();

        // کاربر مهمان → هدایت به ورود
        if ($user === null) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'احراز هویت لازم است.'], 401);
            }

            session(['link' => url()->current()]);

            return redirect()->route('user.login');
        }

        $required = array_values(array_filter(array_map('trim', explode(',', (string) $permissions))));

        // اگر هیچ مجوزی مشخص نشده باشد، به‌صورت پیش‌فرض رد می‌شود (Deny by Default)
        if (empty($required)) {
            return $this->deny($request, $user, '(بدون مجوز مشخص‌شده)');
        }

        $allowed = false;

        try {
            $allowed = $user->hasAnyPermissionTo($required);
        } catch (\Exception $e) {
            // خطا در بررسی مجوز → رد (هرگز عبور نده)
            report($e);
        }

        if (!$allowed) {
            return $this->deny($request, $user, implode(',', $required));
        }

        // ── نکته مهم (رفع باگ) ───────────────────────────────────────
        // `return $next($request)` عامدانه بیرون از بلوک try قرار دارد.
        // پیش‌تر داخل try بود و در نتیجه هر استثنایی که از کنترلر یا ویو
        // پایین‌دستی پرتاب می‌شد (مثلاً خطای Blade یا خطای دیتابیس) توسط
        // catch گرفته می‌شد و به‌جای نمایش خطای واقعی، کاربر پیام
        // «دسترسی مجاز نیست» (۴۰۳ گمراه‌کننده) می‌دید. این کار اشکال‌زدایی
        // را بسیار دشوار می‌کرد و خطاهای ۵۰۰ واقعی را پنهان می‌نمود.
        return $next($request);
    }

    /**
     * رد درخواست و ثبت رخداد امنیتی.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User                $user
     * @param  string                   $required
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function deny($request, $user, $required)
    {
        // ثبت تلاش دسترسی غیرمجاز — برای تشخیص حملات Broken Access Control
        $this->audit->log(
            AuditLog::EVENT_ACCESS_DENIED,
            'تلاش دسترسی غیرمجاز به مسیر ' . $request->path() . ' — مجوز لازم: ' . $required,
            null,
            [],
            [],
            AuditLog::SEVERITY_WARNING
        );

        if ($request->expectsJson()) {
            return response()->json(['message' => 'دسترسی شما به این بخش مجاز نیست.'], 403);
        }

        abort(403, 'دسترسی شما به این بخش مجاز نیست.');
    }
}
