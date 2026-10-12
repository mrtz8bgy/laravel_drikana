<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditLogger;
use App\Services\Rbac\PermissionRegistry;
use App\Services\Rbac\RbacService;
use App\Services\Security\PasswordPolicy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    public function __construct(RbacService $rbac, AuditLogger $audit)
    {
        $this->middleware('auth');

        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:roles.assign')->only(['editRoles', 'updateRoles']);
        $this->middleware('permission:permissions.view')->only(['permissions']);
        $this->middleware('permission:permissions.create')->only(['storePermission']);
        $this->middleware('permission:permissions.delete')->only(['destroyPermission']);
        $this->middleware('permission:audit_logs.view')->only(['auditLogs']);

        $this->rbac  = $rbac;
        $this->audit = $audit;
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
     * فرم ساخت کاربر ادمین جدید.
     *
     * ── دو نوع حساب ─────────────────────────────────────────────────
     *   ۱) «کاربر با نقش» (staff) — پیش‌فرض و مسیر درست RBAC.
     *      دسترسی این کاربر دقیقاً از نقش‌هایی می‌آید که به او می‌دهید.
     *   ۲) «مدیر کل سیستم» (admin) — حساب با دسترسی کامل.
     *      این گزینه تنها برای مدیر کل سیستم نمایش داده می‌شود، چون
     *      `user_type = 'admin'` در `User::isSuperAdmin()` به‌معنای
     *      «همه مجوزها» است — یعنی ساخت چنین کاربری معادل ساخت یک
     *      مدیر کل است.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $actor = Auth::user();

        $roles = $this->assignableRoles($actor);

        // تنها مدیر کل سیستم می‌تواند حساب «مدیر کل» بسازد
        $canCreateSuperAdmin = $actor->isSuperAdmin();

        return view('admin.users.create', compact('roles', 'canCreateSuperAdmin'));
    }

    /**
     * ذخیره کاربر ادمین جدید.
     *
     * ── محافظ‌های امنیتی ────────────────────────────────────────────
     *   ۱) ساخت حساب `admin` تنها توسط مدیر کل سیستم
     *   ۲) انتساب نقش با `RbacService::syncUserRoles` انجام می‌شود که
     *      خودش بررسی می‌کند نقش اعطایی از سطح عامل بالاتر نباشد
     *   ۳) رمز عبور با `PasswordPolicy` سنجیده می‌شود؛ اگر تولید خودکار
     *      انتخاب شده باشد، تنها یک بار به مدیر نمایش داده می‌شود
     *   ۴) همه‌چیز در یک تراکنش: اگر انتساب نقش شکست بخورد، کاربر
     *      نیمه‌ساخته باقی نمی‌ماند
     *   ۵) رخداد در `audit_logs` ثبت می‌شود
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $actor = Auth::user();

        $validated = $request->validate([
            'name'              => 'required|string|min:3|max:191',
            'email'             => 'nullable|required_without:phone|email|max:191|unique:users,email',
            'phone'             => 'nullable|required_without:email|string|max:20|unique:users,phone',
            'account_type'      => 'required|in:staff,admin',
            'password_mode'     => 'required|in:auto,manual',
            'password'          => 'nullable|required_if:password_mode,manual|string|max:191|confirmed',
            'roles'             => 'nullable|array',
            'roles.*'           => 'string|max:100',
        ], [
            'name.required'            => 'نام کاربر الزامی است.',
            'name.min'                 => 'نام کاربر باید حداقل ۳ کاراکتر باشد.',
            'email.required_without'   => 'ایمیل یا شماره موبایل الزامی است.',
            'email.unique'             => 'این ایمیل قبلاً ثبت شده است.',
            'phone.required_without'   => 'ایمیل یا شماره موبایل الزامی است.',
            'phone.unique'             => 'این شماره موبایل قبلاً ثبت شده است.',
            'account_type.required'    => 'نوع حساب را انتخاب کنید.',
            'account_type.in'          => 'نوع حساب انتخابی معتبر نیست.',
            'password.required_if'     => 'رمز عبور را وارد کنید یا حالت «تولید خودکار» را انتخاب کنید.',
            'password.confirmed'       => 'رمز عبور و تکرار آن یکسان نیستند.',
        ]);

        // ── محافظ ۱: ساخت مدیر کل سیستم تنها توسط مدیر کل ──────────
        if ($validated['account_type'] === 'admin' && !$actor->isSuperAdmin()) {
            // ۴۰۳ صریح: این مسیر برای کاربر غیرمجاز «وجود دارد» اما ممنوع است.
            // برخلاف ناحیه مدیریت (که ۴۰۴ می‌دهیم)، اینجا کاربر درون پنل
            // است و پنهان‌کردن وجود صفحه کمکی نمی‌کند.
            abort(403, 'ساخت حساب «مدیر کل سیستم» تنها توسط مدیر کل سیستم امکان‌پذیر است.');
        }

        $roles = isset($validated['roles']) ? array_values(array_filter($validated['roles'])) : [];

        // حساب مدیر کل باید نقش super_admin داشته باشد تا در گزارش‌های
        // دسترسی هم به‌درستی دیده شود (user_type تنها میان‌بر است).
        if ($validated['account_type'] === 'admin' && !in_array('super_admin', $roles, true)) {
            $roles[] = 'super_admin';
        }

        // ── محافظ ۲: نقش‌ها باید قابل‌انتساب باشند ─────────────────
        // `syncUserRoles` خودش این بررسی را انجام می‌دهد و در صورت
        // تخلف `RuntimeException` می‌دهد که پایین‌تر مدیریت می‌شود.

        // ── رمز عبور ────────────────────────────────────────────────
        $generatedPassword = null;

        if ($validated['password_mode'] === 'auto') {
            $generatedPassword = PasswordPolicy::generate(16);
            $plainPassword = $generatedPassword;
        } else {
            $plainPassword = (string) $validated['password'];

            $policyError = PasswordPolicy::validate($plainPassword);

            if ($policyError !== null) {
                return back()->withInput()->with('error', $policyError);
            }
        }

        try {
            $user = DB::transaction(function () use ($validated, $roles, $plainPassword, $actor) {

                $user = new User();
                $user->name              = $validated['name'];
                $user->email             = isset($validated['email']) ? $validated['email'] : null;
                $user->phone             = isset($validated['phone']) ? $validated['phone'] : null;
                $user->password          = Hash::make($plainPassword);
                $user->user_type         = $validated['account_type'] === 'admin' ? 'admin' : 'staff';
                $user->role              = $validated['account_type'] === 'admin' ? 'admin' : 'staff';
                $user->email_verified_at = !empty($validated['email']) ? now() : null;
                $user->phone_verified_at = !empty($validated['phone']) ? now() : null;

                $user->save();

                // انتساب نقش‌ها از طریق سرویس RBAC (با همه محافظ‌ها)
                if (!empty($roles)) {
                    $this->rbac->syncUserRoles($user, $roles, $actor);
                }

                return $user;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ساخت کاربر.');
        }

        $this->audit->log(
            AuditLog::EVENT_USER_CREATED,
            'ساخت کاربر ادمین جدید: ' . $user->name . ' (' . ($user->email ?: $user->phone) . ')',
            $user,
            [],
            [
                'user_type' => $user->user_type,
                'roles'     => $user->roleSlugs(),
            ],
            AuditLog::SEVERITY_CRITICAL
        );

        // ── نمایش یک‌باره رمز تولیدشده ──────────────────────────────
        $redirect = redirect()
            ->route('users.show', $user->getKey())
            ->with('success', 'کاربر «' . $user->name . '» با موفقیت ساخته شد.');

        if ($generatedPassword !== null) {
            // رمز در فلش‌سشن نگه داشته می‌شود و پس از یک بار نمایش
            // پاک می‌گردد. در Audit Log و در دیتابیس چیزی ثبت نمی‌شود.
            $redirect->with('generated_password', $generatedPassword);
        }

        return $redirect;
    }

    /**
     * نقش‌هایی که کاربر جاری مجاز به اعطای آن‌هاست.
     *
     * ── قاعده ──────────────────────────────────────────────────────
     * نقش با سطح بالاتر از سطح خود کاربر نمایش داده نمی‌شود. مدیر کل
     * سیستم سقف ندارد. این فیلتر «رابط کاربری» است؛ اعمال واقعی در
     * `RbacService::assertCanAssignRoles` انجام می‌شود (Defense in
     * Depth) تا حتی با دست‌کاری درخواست هم تخلف ممکن نباشد.
     *
     * @param  \App\User $actor
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function assignableRoles($actor)
    {
        $actorLevel = $actor->isSuperAdmin()
            ? 999
            : (int) Role::query()->whereIn('slug', $actor->roleSlugs())->max('level');

        return Role::query()
            ->active()
            ->where('level', '<=', $actorLevel)
            ->ordered()
            ->get();
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

        // سطح نقشی که عامل می‌تواند اعطا کند (منطق مشترک با create())
        $actorLevel = Auth::user()->isSuperAdmin()
            ? 999
            : (int) Role::query()->whereIn('slug', Auth::user()->roleSlugs())->max('level');

        $roles = $this->assignableRoles(Auth::user());

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
