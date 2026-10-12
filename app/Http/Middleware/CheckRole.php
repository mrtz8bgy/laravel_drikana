<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * میدل‌ور بررسی نقش (Role Middleware).
 *
 *     Route::get('/x')->middleware('role:store_manager,super_admin');
 *
 * برای تفکیک سطح بالای دسترسی استفاده می‌شود. برای عملیات دقیق‌تر،
 * میدل‌ور `permission:` ترجیح دارد چون اصل «کمترین دسترسی» را بهتر
 * پیاده می‌کند.
 */
class CheckRole
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
     * @param  string                   $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = '')
    {
        $user = Auth::user();

        if ($user === null) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'احراز هویت لازم است.'], 401);
            }

            session(['link' => url()->current()]);

            return redirect()->route('user.login');
        }

        $required = array_values(array_filter(array_map('trim', explode(',', (string) $roles))));

        if (empty($required)) {
            abort(403, 'دسترسی شما به این بخش مجاز نیست.');
        }

        $allowed = false;

        try {
            $allowed = $user->hasAnyRole($required);
        } catch (\Exception $e) {
            report($e);
        }

        // بیرون از try — استثناهای پایین‌دستی نباید به ۴۰۳ تبدیل شوند
        if ($allowed) {
            return $next($request);
        }

        $this->audit->log(
            AuditLog::EVENT_ACCESS_DENIED,
            'تلاش دسترسی غیرمجاز (نقش): ' . $request->path() . ' — نقش لازم: ' . implode(',', $required),
            null, [], [], AuditLog::SEVERITY_WARNING
        );

        if ($request->expectsJson()) {
            return response()->json(['message' => 'دسترسی شما به این بخش مجاز نیست.'], 403);
        }

        abort(403, 'دسترسی شما به این بخش مجاز نیست.');
    }
}
