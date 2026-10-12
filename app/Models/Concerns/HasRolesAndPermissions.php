<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * رفتار نقش و مجوز برای مدل User.
 *
 * ── اصول پیاده‌سازی ─────────────────────────────────────────────────
 *
 * ۱) Deny by Default — هر بررسی مجوزی که نتواند قطعاً تأیید شود، «رد» است.
 *
 * ۲) Super Admin Bypass — مدیر کل سیستم (slug = super_admin) یا کاربری که
 *    `user_type = admin` دارد، همه مجوزها را دارد. این استثنا برای حفظ
 *    دسترسی فعلی مدیر پروژه ضروری است تا پس از نصب RBAC قفل نشود.
 *
 * ۳) چند نقش برای یک کاربر — از طریق جدول واسط role_user.
 *
 * ۴) کش — مجموعه مجوزهای مؤثر کاربر کش می‌شود تا در هر درخواست به کوئری
 *    اضافه تبدیل نشود. کش با هر تغییر نقش/مجوز پاک می‌گردد.
 */
trait HasRolesAndPermissions
{
    /** مدت اعتبار کش مجوزهای مؤثر کاربر (ثانیه) */
    protected static $permissionCacheTtl = 1800;

    /**
     * نقش‌های کاربر.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * مجوزهای مستقیم کاربر (در حال حاضر استفاده نمی‌شود؛ برای توسعه آینده).
     * اگر در آینده جدول permission_user اضافه شد، اینجا وصل می‌شود.
     */
    public function directPermissions()
    {
        return collect();
    }

    // =========================================================
    // بررسی نقش
    // =========================================================

    /**
     * آیا کاربر «مدیر کل سیستم» است؟
     *
     * دو مسیر بررسی می‌شود تا سازگاری با داده موجود حفظ شود:
     *   • user_type = 'admin'  (سیستم قدیمی پروژه)
     *   • نقش با slug = 'super_admin' در جدول role_user (سیستم جدید)
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        if ($this->user_type === 'admin') {
            return true;
        }

        return $this->roles()->where('roles.slug', 'super_admin')->exists();
    }

    /**
     * بررسی داشتن یک نقش مشخص بر اساس slug.
     *
     * @param  string|array $slugs
     * @return bool
     */
    public function hasRole($slugs)
    {
        $slugs = is_array($slugs) ? $slugs : [$slugs];

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()->whereIn('roles.slug', $slugs)->exists();
    }

    /**
     * آیا کاربر حداقل یکی از نقش‌های داده‌شده را دارد؟
     *
     * @param  array $slugs
     * @return bool
     */
    public function hasAnyRole(array $slugs)
    {
        return $this->hasRole($slugs);
    }

    /**
     * آیا کاربر همه نقش‌های داده‌شده را دارد؟
     *
     * @param  array $slugs
     * @return bool
     */
    public function hasAllRoles(array $slugs)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $owned = $this->roleSlugs();

        foreach ($slugs as $slug) {
            if (!in_array($slug, $owned, true)) {
                return false;
            }
        }

        return !empty($slugs);
    }

    /**
     * فهرست slug نقش‌های کاربر.
     *
     * @return array
     */
    public function roleSlugs()
    {
        return $this->roles()->pluck('roles.slug')->all();
    }

    // =========================================================
    // بررسی مجوز
    // =========================================================

    /**
     * بررسی داشتن یک مجوز مشخص.
     *
     * @param  string $permissionSlug
     * @return bool
     */
    public function hasPermissionTo($permissionSlug)
    {
        // مدیر کل سیستم همه مجوزها را دارد
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permissionSlug, $this->effectivePermissions(), true);
    }

    /**
     * بررسی داشتن حداقل یکی از مجوزهای داده‌شده.
     *
     * @param  array $permissionSlugs
     * @return bool
     */
    public function hasAnyPermissionTo(array $permissionSlugs)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return count(array_intersect($permissionSlugs, $this->effectivePermissions())) > 0;
    }

    /**
     * بررسی داشتن همه مجوزهای داده‌شده.
     *
     * @param  array $permissionSlugs
     * @return bool
     */
    public function hasAllPermissionsTo(array $permissionSlugs)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return count(array_intersect($permissionSlugs, $this->effectivePermissions())) === count($permissionSlugs);
    }

    /**
     * مجموعه کل مجوزهای مؤثر کاربر (اجتماع مجوزهای همه نقش‌های فعال).
     *
     * @return array
     */
    public function effectivePermissions()
    {
        $userId = $this->getKey();

        if ($userId === null) {
            return [];
        }

        return Cache::remember($this->permissionCacheKey($userId), static::$permissionCacheTtl, function () {
            // فقط نقش‌های فعال مجوز می‌دهند
            $roleIds = $this->roles()->where('roles.is_active', true)->pluck('roles.id')->all();

            if (empty($roleIds)) {
                return [];
            }

            return DB::table('permission_role')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->whereIn('permission_role.role_id', $roleIds)
                ->distinct()
                ->pluck('permissions.slug')
                ->all();
        });
    }

    // =========================================================
    // انتساب نقش و مجوز
    // =========================================================

    /**
     * افزودن یک یا چند نقش به کاربر.
     *
     * @param  string|array $roleSlugs
     * @param  int|null     $assignedBy  شناسه کاربری که انتساب را انجام می‌دهد (Audit)
     * @return void
     */
    public function assignRole($roleSlugs, $assignedBy = null)
    {
        $roleSlugs = is_array($roleSlugs) ? $roleSlugs : [$roleSlugs];

        $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();

        if (empty($roleIds)) {
            return;
        }

        $payload = [];
        foreach ($roleIds as $roleId) {
            $payload[$roleId] = [
                'assigned_by' => $assignedBy,
                'assigned_at' => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // syncWithoutDetaching → نقش‌های قبلی حفظ می‌شوند و نقش‌های جدید افزوده
        $this->roles()->syncWithoutDetaching($payload);

        $this->flushPermissionCache();
    }

    /**
     * جایگزینی کامل نقش‌های کاربر.
     *
     * @param  array    $roleSlugs
     * @param  int|null $assignedBy
     * @return void
     */
    public function syncRoles(array $roleSlugs, $assignedBy = null)
    {
        $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();

        $payload = [];
        foreach ($roleIds as $roleId) {
            $payload[$roleId] = [
                'assigned_by' => $assignedBy,
                'assigned_at' => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        $this->roles()->sync($payload);

        $this->flushPermissionCache();
    }

    /**
     * حذف یک یا چند نقش از کاربر.
     *
     * @param  string|array $roleSlugs
     * @return void
     */
    public function removeRole($roleSlugs)
    {
        $roleSlugs = is_array($roleSlugs) ? $roleSlugs : [$roleSlugs];

        $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();

        if (!empty($roleIds)) {
            $this->roles()->detach($roleIds);
            $this->flushPermissionCache();
        }
    }

    // =========================================================
    // کش
    // =========================================================

    /**
     * کلید کش مجوزهای کاربر.
     *
     * @param  int $userId
     * @return string
     */
    public function permissionCacheKey($userId)
    {
        return 'drikana.user.' . $userId . '.permissions';
    }

    /**
     * پاک‌سازی کش مجوزهای این کاربر.
     *
     * @return void
     */
    public function flushPermissionCache()
    {
        Cache::forget($this->permissionCacheKey($this->getKey()));
    }

    /**
     * پاک‌سازی کش همه کاربران — پس از تغییر مجوزهای یک نقش لازم است.
     *
     * @return void
     */
    public static function flushAllPermissionCaches()
    {
        DB::table('users')->select('id')->orderBy('id')->chunk(500, function ($users) {
            foreach ($users as $user) {
                Cache::forget('drikana.user.' . $user->id . '.permissions');
            }
        });
    }
}
