<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\PermissionRegistry;
use App\Services\Rbac\RbacService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * کنترلر مدیریت کاربران و انتساب نقش (User & Role Assignment).
 *
 * این کنترلر مسئول:
 *   • فهرست کاربران و نقش‌هایشان
 *   • انتساب / سلب نقش
 *   • فهرست و ایجاد مجوز جدید
 *   • نمایش Audit Log
 *
 * ── قاعده امنیتی کلیدی ─────────────────────────────────────────────
 * هیچ‌کدام از این عملیات از طریق Mass Assignment انجام نمی‌شود؛ همه از
 * طریق سرویس RBAC با محافظ‌های افزایش سطح دسترسی.
 */
class UserRoleController extends Controller
{
    /**
     * @var \App\Services\Rbac\RbacService
     */
    protected $rbac;

    public function __construct(RbacService $rbac)
    {
        $this->middleware('auth');

        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:roles.assign')->only(['editRoles', 'updateRoles']);
        $this->middleware('permission:permissions.view')->only(['permissions']);
        $this->middleware('permission:permissions.create')->only(['storePermission']);
        $this->middleware('permission:permissions.delete')->only(['destroyPermission']);
        $this->middleware('permission:audit_logs.view')->only(['auditLogs']);

        $this->rbac = $rbac;
    }

    // =========================================================
    // کاربران
    // =========================================================

    /**
     * فهرست کاربران با نقش‌هایشان.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // جست‌وجو — پارامترها با parameter binding امن هستند
        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // فیلتر بر اساس نقش
        if ($request->filled('role')) {
            $roleSlug = $request->get('role');
            if (in_array($roleSlug, Role::query()->pluck('slug')->all(), true)) {
                $query->whereHas('roles', function ($q) use ($roleSlug) {
                    $q->where('roles.slug', $roleSlug);
                });
            }
        }

        // فیلتر نوع کاربر (سازگاری با ستون قدیمی)
        if ($request->filled('user_type')) {
            $types = ['admin', 'customer', 'seller', 'staff'];
            if (in_array($request->get('user_type'), $types, true)) {
                $query->where('user_type', $request->get('user_type'));
            }
        }

        $users = $query->orderByDesc('id')->paginate(25);
        $roles = Role::query()->active()->ordered()->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * نمایش جزئیات یک کاربر و نقش‌هایش.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::query()->with('roles')->findOrFail($id);

        $permissions = $user->isSuperAdmin()
            ? Permission::query()->orderBy('group')->get()->groupBy('group')
            : Permission::query()
                ->whereIn('slug', $user->effectivePermissions())
                ->orderBy('group')->get()->groupBy('group');

        return view('admin.users.show', compact('user', 'permissions'));
    }

    /**
     * فرم ویرایش نقش‌های کاربر.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function editRoles($id)
    {
        $user = User::query()->with('roles')->findOrFail($id);

        // سطح نقشی که عامل می‌تواند اعطا کند
        $actorLevel = Auth::user()->isSuperAdmin()
            ? 999
            : (int) Role::query()->whereIn('slug', Auth::user()->roleSlugs())->max('level');

        $roles = Role::query()
            ->active()
            ->where('level', '<=', $actorLevel)
            ->ordered()
            ->get();

        $userRoleSlugs = $user->roleSlugs();

        return view('admin.users.roles', compact('user', 'roles', 'userRoleSlugs', 'actorLevel'));
    }

    /**
     * به‌روزرسانی نقش‌های کاربر.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRoles(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);

        $validated = $request->validate([
            'roles'   => 'nullable|array',
            'roles.*' => 'string|max:100',
        ], [
            'roles.array' => 'ساختار نقش‌های ارسالی نامعتبر است.',
        ]);

        $roleSlugs = isset($validated['roles']) ? $validated['roles'] : [];

        try {
            $this->rbac->syncUserRoles($user, $roleSlugs, Auth::user());

            return redirect()
                ->route('users.show', $user->getKey())
                ->with('success', 'نقش‌های کاربر «' . $user->name . '» با موفقیت به‌روزرسانی شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در به‌روزرسانی نقش‌ها.');
        }
    }

    // =========================================================
    // مجوزها
    // =========================================================

    /**
     * فهرست مجوزهای سیستم.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $permissions = Permission::query()
            ->orderBy('group')
            ->orderBy('slug')
            ->get()
            ->groupBy('group');

        // مجوزهایی که در کد استفاده می‌شوند قابل حذف نیستند
        $protectedSlugs = PermissionRegistry::slugs();

        return view('admin.permissions.index', compact('permissions', 'protectedSlugs'));
    }

    /**
     * ایجاد مجوز جدید.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'slug'        => 'required|string|max:150|regex:/^[a-z][a-z0-9]*(\.[a-z][a-z0-9_]*)+$/',
            'name'        => 'required|string|min:2|max:191',
            'group'       => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'slug.regex' => 'شناسه مجوز باید به شکل group.action باشد و فقط شامل حروف کوچک لاتین، رقم، نقطه و زیرخط.',
            'name.required' => 'عنوان مجوز الزامی است.',
        ]);

        try {
            $this->rbac->createPermission($validated, Auth::user());

            return redirect()
                ->route('permissions.index')
                ->with('success', 'مجوز جدید با موفقیت ایجاد شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ایجاد مجوز.');
        }
    }

    /**
     * حذف یک مجوز.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPermission($id)
    {
        $permission = Permission::query()->findOrFail($id);

        try {
            $this->rbac->deletePermission($permission, Auth::user());

            return redirect()
                ->route('permissions.index')
                ->with('success', 'مجوز با موفقیت حذف شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در حذف مجوز.');
        }
    }

    // =========================================================
    // Audit Log
    // =========================================================

    /**
     * نمایش گزارش رخدادهای حساس.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::query()->with('user');

        if ($request->filled('event')) {
            $event = (string) $request->get('event');
            // محدودسازی به الگوی امن — جلوگیری از تزریق wildcard
            if (preg_match('/^[a-z][a-z0-9._]*$/', $event)) {
                $query->where('event', 'like', $event . '%');
            }
        }

        if ($request->filled('severity')) {
            $severity = $request->get('severity');
            $allowed = [
                AuditLog::SEVERITY_INFO,
                AuditLog::SEVERITY_NOTICE,
                AuditLog::SEVERITY_WARNING,
                AuditLog::SEVERITY_CRITICAL,
            ];
            if (in_array($severity, $allowed, true)) {
                $query->where('severity', $severity);
            }
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->get('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $logs = $query->orderByDesc('id')->paginate(50);

        $stats = [
            'total'    => AuditLog::query()->count(),
            'critical' => AuditLog::query()->where('severity', AuditLog::SEVERITY_CRITICAL)->count(),
            'warning'  => AuditLog::query()->where('severity', AuditLog::SEVERITY_WARNING)->count(),
            'today'    => AuditLog::query()->whereDate('created_at', today())->count(),
        ];

        return view('admin.audit_logs.index', compact('logs', 'stats'));
    }
}
