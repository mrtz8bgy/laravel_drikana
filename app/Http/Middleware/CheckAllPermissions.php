<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * میدل‌ور بررسی مجوز با شرط «همه» (AND).
 *
 *     Route::get('/x')->middleware('permission_all:ownership.transfers.approve,ownership.transfers.view');
 *
 * یعنی کاربر باید هر دو مجوز را داشته باشد. مکمل میدل‌ور `permission:`
 * است که شرط OR دارد.
 */
class CheckAllPermissions
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

        if ($user === null) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'احراز هویت لازم است.'], 401);
            }

            session(['link' => url()->current()]);

            return redirect()->route('user.login');
        }

        $required = array_values(array_filter(array_map('trim', explode(',', (string) $permissions))));

        if (empty($required)) {
            $this->audit->log(
                AuditLog::EVENT_ACCESS_DENIED,
                'میدل‌ور permission_all بدون مجوز مشخص فراخوانی شد — رد پیش‌فرض: ' . $request->path(),
                null, [], [], AuditLog::SEVERITY_WARNING
            );

            abort(403, 'دسترسی شما به این بخش مجاز نیست.');
        }

        $allowed = false;

        try {
            $allowed = $user->hasAllPermissionsTo($required);
        } catch (\Exception $e) {
            report($e);
        }

        // بیرون از try — استثناهای پایین‌دستی نباید به ۴۰۳ تبدیل شوند
        if ($allowed) {
            return $next($request);
        }

        $this->audit->log(
            AuditLog::EVENT_ACCESS_DENIED,
            'تلاش دسترسی غیرمجاز (نیازمند همه مجوزها): ' . $request->path(),
            null, [], [], AuditLog::SEVERITY_WARNING
        );

        if ($request->expectsJson()) {
            return response()->json(['message' => 'دسترسی شما به این بخش مجاز نیست.'], 403);
        }

        abort(403, 'دسترسی شما به این بخش مجاز نیست.');
    }
}
