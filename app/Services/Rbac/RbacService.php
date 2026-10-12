<?php

namespace App\Services\Rbac;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditLogger;
use App\User;
use Illuminate\Support\Facades\DB;

/**
 * سرویس مدیریت نقش و مجوز (RBAC).
 *
 * ── محافظ‌های امنیتی پیاده‌شده ──────────────────────────────────────
 *
 * ۱) جلوگیری از افزایش سطح دسترسی (Privilege Escalation)
 *    کاربر نمی‌تواند مجوزی به نقش بدهد که خودش ندارد. این قاعده در
 *    `assertCanGrantPermissions()` اعمال می‌شود.
 *
 * ۲) جلوگیری از حذف/ویرایش نقش سیستمی
 *    نقش‌های `is_system = true` (مدیر کل، مدیر فروشگاه، ...) قابل حذف
 *    نیستند و نامک‌شان (slug) قابل تغییر نیست — چون در کد به آن‌ها
 *    ارجاع داده شده است.
 *
 * ۳) جلوگیری از سلب آخرین مدیر کل
 *    اگر تنها یک کاربر با نقش `super_admin` باقی مانده باشد، حذف آن
 *    نقش از کاربر مسدود می‌شود (جلوگیری از قفل‌شدن سیستم).
 *
 * ۴) جلوگیری از انتساب نقش به خود (Self-Escalation)
 *    کاربر نمی‌تواند به خودش نقش اضافه کند.
 *
 * ۵) هر عملیات در تراکنش و با Audit Log انجام می‌شود.
 */
class RbacService
{
    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    public function __construct(AuditLogger $audit)
    {
        $this->audit = $audit;
    }

    // =========================================================
    // نقش‌ها
    // =========================================================

    /**
     * ایجاد نقش جدید.
     *
     * @param  array    $data  name, slug, description, level, permissions[]
     * @param  \App\User $actor
     * @return \App\Models\Role
     *
     * @throws \RuntimeException
     */
    public function createRole(array $data, $actor)
    {
        return DB::transaction(function () use ($data, $actor) {

            // اعتبارسنجی slug — فقط حروف لاتین کوچک، رقم، خط تیره و زیرخط
            $slug = $this->normalizeSlug(isset($data['slug']) ? $data['slug'] : $data['name']);

            if ($slug === '') {
                throw new \RuntimeException('شناسه (slug) نقش نامعتبر است.');
            }

            if (Role::query()->where('slug', $slug)->exists()) {
                throw new \RuntimeException('نقشی با این شناسه از قبل وجود دارد.');
            }

            $permissions = isset($data['permissions']) && is_array($data['permissions'])
                ? $data['permissions']
                : [];

            // محافظ افزایش سطح دسترسی
            $this->assertCanGrantPermissions($actor, $permissions);

            $role = new Role();
            $role->name        = $data['name'];
            $role->slug        = $slug;
            $role->description = isset($data['description']) ? $data['description'] : null;
            $role->level       = isset($data['level']) ? (int) $data['level'] : 0;
            $role->is_system   = false;
            $role->is_active   = isset($data['is_active']) ? (bool) $data['is_active'] : true;
            $role->permissions = json_encode([], JSON_UNESCAPED_UNICODE);
            $role->save();

            $role->syncPermissions($permissions);

            $this->audit->logModelChange(
                AuditLog::EVENT_ROLE_CREATED,
                $role,
                [],
                ['name' => $role->name, 'slug' => $role->slug, 'permissions' => $permissions],
                'ایجاد نقش «' . $role->name . '» با ' . count($permissions) . ' مجوز'
            );

            return $role->fresh();
        });
    }

    /**
     * ویرایش نقش.
     *
     * @param  \App\Models\Role $role
     * @param  array            $data
     * @param  \App\User        $actor
     * @return \App\Models\Role
     *
     * @throws \RuntimeException
     */
    public function updateRole(Role $role, array $data, $actor)
    {
        return DB::transaction(function () use ($role, $data, $actor) {

            $oldPermissions = $role->permissionSlugs();

            // نامک نقش سیستمی قابل تغییر نیست — چون در کد به آن ارجاع شده
            if (!$role->is_system && isset($data['slug'])) {
                $newSlug = $this->normalizeSlug($data['slug']);
                if ($newSlug !== $role->slug) {
                    if ($newSlug === '') {
                        throw new \RuntimeException('شناسه (slug) نقش نامعتبر است.');
                    }
                    if (Role::query()->where('slug', $newSlug)->where('id', '!=', $role->getKey())->exists()) {
                        throw new \RuntimeException('نقشی با این شناسه از قبل وجود دارد.');
                    }
                    $role->slug = $newSlug;
                }
            }

            // نقش سیستمی را نمی‌توان غیرفعال کرد (مثلاً super_admin)
            if ($role->is_system && isset($data['is_active']) && !$data['is_active']) {
                throw new \RuntimeException('نقش‌های سیستمی قابل غیرفعال‌سازی نیستند.');
            }

            if (isset($data['name'])) {
                $role->name = $data['name'];
            }
            if (array_key_exists('description', $data)) {
                $role->description = $data['description'];
            }
            if (isset($data['level'])) {
                $role->level = (int) $data['level'];
            }
            if (isset($data['is_active'])) {
                $role->is_active = (bool) $data['is_active'];
            }

            $role->save();

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                // محافظ افزایش سطح دسترسی — روی مجوزهای جدید
                $this->assertCanGrantPermissions($actor, $data['permissions']);

                // نقش super_admin همیشه همه مجوزها را دارد
                if ($role->slug === 'super_admin') {
                    $data['permissions'] = PermissionRegistry::slugs();
                }

                $role->syncPermissions($data['permissions']);

                $this->audit->logModelChange(
                    AuditLog::EVENT_ROLE_PERMISSIONS,
                    $role,
                    ['permissions' => $oldPermissions],
                    ['permissions' => $role->permissionSlugs()],
                    'به‌روزرسانی مجوزهای نقش «' . $role->name . '»'
                );
            }

            $this->audit->logModelChange(
                AuditLog::EVENT_ROLE_UPDATED,
                $role,
                [],
                ['name' => $role->name, 'slug' => $role->slug, 'is_active' => $role->is_active],
                'ویرایش نقش «' . $role->name . '»'
            );

            // مجوزهای مؤثر همه کاربران این نقش تغییر کرده → کش را پاک کن
            User::flushAllPermissionCaches();

            return $role->fresh();
        });
    }

    /**
     * حذف نقش.
     *
     * @param  \App\Models\Role $role
     * @param  \App\User        $actor
     * @return void
     *
     * @throws \RuntimeException
     */
    public function deleteRole(Role $role, $actor)
    {
        DB::transaction(function () use ($role, $actor) {

            if ($role->is_system) {
                throw new \RuntimeException(
                    'نقش‌های سیستمی قابل حذف نیستند. برای غیرفعال‌سازی موقت، '
                    . 'وضعیت «فعال» را خاموش کنید.'
                );
            }

            // اگر نقش به کاربری متصل است، هشدار بده
            $usersCount = $role->users()->count();
            if ($usersCount > 0) {
                throw new \RuntimeException(
                    'این نقش به ' . $usersCount . ' کاربر متصل است. '
                    . 'ابتدا نقش را از کاربران سلب کنید.'
                );
            }

            $snapshot = [
                'name'        => $role->name,
                'slug'        => $role->slug,
                'permissions' => $role->permissionSlugs(),
            ];

            $roleId = $role->getKey();
            $role->delete();

            $this->audit->log(
                AuditLog::EVENT_ROLE_DELETED,
                'حذف نقش «' . $snapshot['name'] . '»',
                null,
                $snapshot,
                [],
                AuditLog::SEVERITY_CRITICAL
            );

            User::flushAllPermissionCaches();
        });
    }

    // =========================================================
    // مجوزها
    // =========================================================

    /**
     * ایجاد مجوز جدید.
     *
     * @param  array     $data  slug, name, group, description
     * @param  \App\User $actor
     * @return \App\Models\Permission
     *
     * @throws \RuntimeException
     */
    public function createPermission(array $data, $actor)
    {
        $slug = strtolower(trim((string) $data['slug']));

        // الگوی سخت‌گیرانه slug مجوز: group.action یا group.sub.action
        if (!preg_match('/^[a-z][a-z0-9]*(\.[a-z][a-z0-9_]*)+$/', $slug)) {
            throw new \RuntimeException(
                'شناسه مجوز نامعتبر است. الگوی صحیح: group.action — '
                . 'مثلاً products.view یا jewelry.transfers.approve'
            );
        }

        if (Permission::query()->where('slug', $slug)->exists()) {
            throw new \RuntimeException('مجوزی با این شناسه از قبل وجود دارد.');
        }

        $permission = Permission::create([
            'slug'        => $slug,
            'name'        => $data['name'],
            'group'       => isset($data['group']) ? $data['group'] : explode('.', $slug)[0],
            'description' => isset($data['description']) ? $data['description'] : null,
        ]);

        Permission::flushCache();

        $this->audit->log(
            AuditLog::EVENT_PERMISSION_CREATED,
            'ایجاد مجوز «' . $permission->name . '» (' . $slug . ')',
            $permission,
            [],
            ['slug' => $slug, 'name' => $permission->name],
            AuditLog::SEVERITY_NOTICE
        );

        return $permission;
    }

    /**
     * حذف یک مجوز.
     *
     * @param  \App\Models\Permission $permission
     * @param  \App\User              $actor
     * @return void
     *
     * @throws \RuntimeException
     */
    public function deletePermission(Permission $permission, $actor)
    {
        // مجوزهای ثبت‌شده در کد (PermissionRegistry) قابل حذف نیستند
        if (in_array($permission->slug, PermissionRegistry::slugs(), true)) {
            throw new \RuntimeException(
                'این مجوز در کد برنامه استفاده می‌شود و قابل حذف نیست. '
                . 'حذف آن باعث از کار افتادن بخش‌های وابسته می‌شود.'
            );
        }

        $snapshot = ['slug' => $permission->slug, 'name' => $permission->name];
        $permission->delete();

        Permission::flushCache();
        User::flushAllPermissionCaches();

        $this->audit->log(
            AuditLog::EVENT_PERMISSION_DELETED,
            'حذف مجوز «' . $snapshot['name'] . '» (' . $snapshot['slug'] . ')',
            null,
            $snapshot,
            [],
            AuditLog::SEVERITY_CRITICAL
        );
    }

    // =========================================================
    // انتساب نقش به کاربر
    // =========================================================

    /**
     * هم‌گام‌سازی نقش‌های یک کاربر.
     *
     * @param  \App\User $target   کاربری که نقش‌ها به او داده می‌شود
     * @param  array     $roleSlugs
     * @param  \App\User $actor    کاربری که عملیات را انجام می‌دهد
     * @return void
     *
     * @throws \RuntimeException
     */
    public function syncUserRoles(User $target, array $roleSlugs, $actor)
    {
        DB::transaction(function () use ($target, $roleSlugs, $actor) {

            $roleSlugs = array_values(array_unique(array_filter($roleSlugs)));

            // ---------------------------------------------------
            // محافظ ۱: کاربر نمی‌تواند به خودش نقش بدهد
            // ---------------------------------------------------
            if ((int) $target->getKey() === (int) $actor->getKey()) {
                throw new \RuntimeException('شما نمی‌توانید نقش‌های خودتان را تغییر دهید.');
            }

            $oldRoles = $target->roleSlugs();

            // ---------------------------------------------------
            // محافظ ۲: جلوگیری از سلب آخرین مدیر کل سیستم
            // ---------------------------------------------------
            $removesSuperAdmin = in_array('super_admin', $oldRoles, true)
                && !in_array('super_admin', $roleSlugs, true);

            if ($removesSuperAdmin && $this->countSuperAdmins() <= 1) {
                throw new \RuntimeException(
                    'این کاربر تنها مدیر کل سیستم است. سلب این نقش باعث '
                    . 'قفل‌شدن دسترسی مدیریتی می‌شود. ابتدا مدیر کل دیگری تعیین کنید.'
                );
            }

            // ---------------------------------------------------
            // محافظ ۳: کاربر نمی‌تواند نقشی بدهد که سطح آن بالاتر از خودش است
            // ---------------------------------------------------
            $this->assertCanAssignRoles($actor, $roleSlugs);

            $target->syncRoles($roleSlugs, $actor->getKey());

            // ---------------------------------------------------
            // هم‌گام‌سازی جدول staff (سازگاری با کد قدیمی)
            // ---------------------------------------------------
            $this->syncLegacyStaffRole($target, $roleSlugs);

            // ---------------------------------------------------
            // Audit
            // ---------------------------------------------------
            $added   = array_diff($roleSlugs, $oldRoles);
            $removed = array_diff($oldRoles, $roleSlugs);

            if (!empty($added)) {
                $this->audit->log(
                    AuditLog::EVENT_ROLE_ASSIGNED,
                    'انتساب نقش(های) ' . implode(', ', $added) . ' به کاربر ' . $target->name,
                    $target,
                    ['roles' => $oldRoles],
                    ['roles' => $roleSlugs, 'added' => array_values($added)],
                    AuditLog::SEVERITY_CRITICAL
                );
            }

            if (!empty($removed)) {
                $this->audit->log(
                    AuditLog::EVENT_ROLE_REVOKED,
                    'سلب نقش(های) ' . implode(', ', $removed) . ' از کاربر ' . $target->name,
                    $target,
                    ['roles' => $oldRoles],
                    ['roles' => $roleSlugs, 'removed' => array_values($removed)],
                    AuditLog::SEVERITY_CRITICAL
                );
            }
        });
    }

    // =========================================================
    // محافظ‌های امنیتی
    // =========================================================

    /**
     * بررسی اینکه عامل، مجاز به اعطای این مجوزها هست.
     *
     * ── قاعده «کسی نمی‌تواند چیزی بدهد که خودش ندارد» ──────────────
     * اگر کاربر [A] مجوز `roles.assign` داشته باشد اما مجوز
     * `settings.update` را نداشته باشد، نمی‌تواند نقش جدیدی بسازد که
     * شامل `settings.update` باشد. در غیر این صورت می‌توانست با ایجاد
     * نقش جدید، سطح دسترسی خود را بالا ببرد.
     *
     * استثنا: مدیر کل سیستم (super_admin) که همه مجوزها را دارد.
     *
     * @param  \App\User $actor
     * @param  array     $permissionSlugs
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function assertCanGrantPermissions($actor, array $permissionSlugs)
    {
        if ($actor->isSuperAdmin()) {
            return;
        }

        $actorPermissions = $actor->effectivePermissions();
        $forbidden = array_diff($permissionSlugs, $actorPermissions);

        // حذف مقادیری که مجوز واقعی نیستند
        $forbidden = array_filter($forbidden, function ($slug) {
            return in_array($slug, PermissionRegistry::slugs(), true)
                || Permission::query()->where('slug', $slug)->exists();
        });

        if (!empty($forbidden)) {
            $this->audit->log(
                AuditLog::EVENT_ACCESS_DENIED,
                'تلاش برای اعطای مجوزهایی که عامل از آن‌ها برخوردار نیست: '
                    . implode(', ', array_slice($forbidden, 0, 10)),
                null, [], [], AuditLog::SEVERITY_WARNING
            );

            throw new \RuntimeException(
                'شما نمی‌توانید مجوزی را اعطا کنید که خودتان آن را ندارید. '
                . 'مجوزهای ممنوع: ' . implode(', ', array_slice($forbidden, 0, 5))
            );
        }
    }

    /**
     * بررسی اینکه عامل، مجاز به انتساب این نقش‌ها هست.
     *
     * قاعده: سطح نقش اعطاشده نباید از بالاترین سطح نقش‌های خود عامل بیشتر باشد.
     *
     * @param  \App\User $actor
     * @param  array     $roleSlugs
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function assertCanAssignRoles($actor, array $roleSlugs)
    {
        if ($actor->isSuperAdmin()) {
            return;
        }

        // سطح خود عامل = بالاترین level بین نقش‌هایش
        $actorLevel = (int) Role::query()
            ->whereIn('slug', $actor->roleSlugs())
            ->max('level');

        $targetMaxLevel = (int) Role::query()
            ->whereIn('slug', $roleSlugs)
            ->max('level');

        if ($targetMaxLevel > $actorLevel) {
            $this->audit->log(
                AuditLog::EVENT_ACCESS_DENIED,
                'تلاش برای انتساب نقشی با سطح بالاتر از عامل (سطح عامل: '
                    . $actorLevel . '، سطح هدف: ' . $targetMaxLevel . ')',
                null, [], [], AuditLog::SEVERITY_WARNING
            );

            throw new \RuntimeException(
                'شما نمی‌توانید نقشی با سطح دسترسی بالاتر از خودتان انتساب دهید.'
            );
        }
    }

    // =========================================================
    // کمکی
    // =========================================================

    /**
     * تعداد کاربران دارای نقش مدیر کل.
     *
     * دو منبع شمرده می‌شود: نقش RBAC و `user_type = admin`.
     *
     * @return int
     */
    public function countSuperAdmins()
    {
        $rbacCount = DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.slug', 'super_admin')
            ->distinct()
            ->count('role_user.user_id');

        $legacyCount = User::query()->where('user_type', 'admin')->count();

        return max($rbacCount, $legacyCount);
    }

    /**
     * نرمال‌سازی slug نقش.
     *
     * @param  string $value
     * @return string
     */
    protected function normalizeSlug($value)
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9_]+/', '_', $value);
        $value = trim(preg_replace('/_+/', '_', $value), '_');

        return $value;
    }

    /**
     * هم‌گام‌سازی جدول قدیمی `staff.role_id` با نقش اصلی کاربر.
     *
     * جدول `staff` از قبل در پروژه وجود داشت و مدیر سیستم را به نقش وصل
     * می‌کرد. برای اینکه کد قدیمی که از این جدول می‌خواند نشکند، نقش
     * با بالاترین سطح کاربر را در آن ستون هم می‌نویسیم.
     *
     * @param  \App\User $user
     * @param  array     $roleSlugs
     * @return void
     */
    protected function syncLegacyStaffRole(User $user, array $roleSlugs)
    {
        try {
            if (empty($roleSlugs)) {
                DB::table('staff')->where('user_id', $user->getKey())->update([
                    'role_id'    => null,
                    'updated_at' => now(),
                ]);

                return;
            }

            $primaryRoleId = Role::query()
                ->whereIn('slug', $roleSlugs)
                ->orderByDesc('level')
                ->value('id');

            $exists = DB::table('staff')->where('user_id', $user->getKey())->exists();

            if ($exists) {
                DB::table('staff')->where('user_id', $user->getKey())->update([
                    'role_id'    => $primaryRoleId,
                    'updated_at' => now(),
                ]);
            } else {
                // فقط برای کاربران با `user_type = staff` رکورد می‌سازیم
                if ($user->user_type === 'staff') {
                    DB::table('staff')->insert([
                        'user_id'    => $user->getKey(),
                        'role_id'    => $primaryRoleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // هم‌گام‌سازی جدول قدیمی نباید عملیات اصلی را متوقف کند
            report($e);
        }
    }
}
