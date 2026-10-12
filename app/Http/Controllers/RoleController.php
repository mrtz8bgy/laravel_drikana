<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\RbacService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * کنترلر مدیریت نقش‌ها.
 *
 * ── چه چیزی نسبت به قبل اصلاح شد؟ ──────────────────────────────────
 * کنترلر قبلی (`RoleController`) مشکلات زیر را داشت:
 *
 *   • بدون هیچ بررسی مجوز — هر مدیری (و با روت‌های قدیمی، هر کاربر
 *     لاگین‌شده) می‌توانست نقش بسازد
 *   • بدون اعتبارسنجی ورودی (`$request->name` خام ذخیره می‌شد)
 *   • `decrypt($id)` روی پارامتر URL — اگر رمزگشایی شکست می‌خورد خطای
 *     عمومی 500 می‌داد و امکان دستکاری شناسه داشت
 *   • مجوزها فقط در ستون JSON ذخیره می‌شدند که هیچ‌جا خوانده نمی‌شد
 *   • امکان حذف نقش‌های سیستمی بدون محافظ
 *   • بدون Audit Log
 */
class RoleController extends Controller
{
    /**
     * @var \App\Services\Rbac\RbacService
     */
    protected $rbac;

    public function __construct(RbacService $rbac)
    {
        $this->middleware('auth');

        // بررسی مجوز در سطح روتر — لایه اول دفاع
        $this->middleware('permission:roles.view')->only(['index', 'show']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.update')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);

        $this->rbac = $rbac;
    }

    /**
     * فهرست نقش‌ها.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::query()
            ->withCount('users')
            ->ordered()
            ->paginate(20);

        // تعداد مجوزهای هر نقش برای نمایش
        $permissionCounts = [];
        foreach ($roles as $role) {
            $permissionCounts[$role->getKey()] = count($role->permissionSlugs());
        }

        return view('admin.roles.index', compact('roles', 'permissionCounts'));
    }

    /**
     * فرم ایجاد نقش جدید.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissionGroups = Permission::grouped();

        // کاربر تنها می‌تواند مجوزهایی را اعطا کند که خودش دارد
        // (محافظ افزایش سطح دسترسی — نمایش در UI، اعمال در سرویس)
        $actorPermissions = Auth::user()->effectivePermissions();
        $isSuperAdmin = Auth::user()->isSuperAdmin();

        return view('admin.roles.create', compact('permissionGroups', 'actorPermissions', 'isSuperAdmin'));
    }

    /**
     * ذخیره نقش جدید.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|min:2|max:100',
            'slug'          => 'nullable|string|max:100|regex:/^[a-zA-Z0-9_]+$/',
            'description'   => 'nullable|string|max:500',
            'level'         => 'nullable|integer|min:0|max:100',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|max:150',
            'is_active'     => 'nullable|boolean',
        ], [
            'name.required'   => 'نام نقش الزامی است.',
            'slug.regex'      => 'شناسه نقش فقط می‌تواند شامل حروف لاتین، رقم و زیرخط باشد.',
            'permissions.*'   => 'یکی از مجوزهای انتخاب‌شده نامعتبر است.',
        ]);

        try {
            $role = $this->rbac->createRole($validated, Auth::user());

            return redirect()
                ->route('roles.index')
                ->with('success', 'نقش «' . $role->name . '» با موفقیت ایجاد شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ایجاد نقش.');
        }
    }

    /**
     * نمایش جزئیات نقش.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $role = Role::query()->with('users')->findOrFail($id);

        // ── رفع باگ ──────────────────────────────────────────────────
        // پیش‌تر این مجموعه با groupBy('group') گروه‌بندی می‌شد و به ویو
        // پاس داده می‌شد. اما ویو خودش مجوزها را بر اساس پیشوند slug
        // گروه‌بندی می‌کند و انتظار یک مجموعه «تخت» از مدل‌های Permission
        // دارد. نتیجه این ناهماهنگی، خطای
        // «Property [slug] does not exist on this collection instance»
        // و ۵۰۰ شدن صفحه جزئیات نقش بود.
        $permissions = $role->permissionModels()->orderBy('group')->get();

        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * فرم ویرایش نقش.
     *
     * ── رفع باگ ────────────────────────────────────────────────────
     * کنترلر قبلی از `Role::findOrFail(decrypt($id))` استفاده می‌کرد.
     * اگر پارامتر رمزنگاری‌شده نامعتبر بود، استثنای عمومی می‌داد.
     * اکنون از شناسه عددی با `findOrFail` و بررسی مجوز استفاده می‌شود.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = Role::query()->findOrFail($id);

        $permissionGroups = Permission::grouped();
        $rolePermissions  = $role->permissionSlugs();
        $actorPermissions = Auth::user()->effectivePermissions();
        $isSuperAdmin     = Auth::user()->isSuperAdmin();

        return view('admin.roles.edit', compact(
            'role', 'permissionGroups', 'rolePermissions', 'actorPermissions', 'isSuperAdmin'
        ));
    }

    /**
     * به‌روزرسانی نقش.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $role = Role::query()->findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|min:2|max:100',
            'slug'          => 'nullable|string|max:100|regex:/^[a-zA-Z0-9_]+$/',
            'description'   => 'nullable|string|max:500',
            'level'         => 'nullable|integer|min:0|max:100',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|max:150',
            'is_active'     => 'nullable|boolean',
        ]);

        try {
            $this->rbac->updateRole($role, $validated, Auth::user());

            return redirect()
                ->route('roles.index')
                ->with('success', 'نقش «' . $role->name . '» با موفقیت به‌روزرسانی شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ویرایش نقش.');
        }
    }

    /**
     * حذف نقش.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $role = Role::query()->findOrFail($id);

        try {
            $this->rbac->deleteRole($role, Auth::user());

            return redirect()
                ->route('roles.index')
                ->with('success', 'نقش با موفقیت حذف شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در حذف نقش.');
        }
    }
}
