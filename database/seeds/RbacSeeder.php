<?php

use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\PermissionRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder نقش‌ها و مجوزهای اولیه.
 *
 * ── اصول امنیتی این Seeder ──────────────────────────────────────────
 *
 * ۱) Idempotent است: اجرای چندباره آن داده تکراری نمی‌سازد و مجوزهایی
 *    که مدیر بعداً اضافه کرده را حذف نمی‌کند.
 *
 * ۲) هیچ رمز عبوری در این Seeder ساخته نمی‌شود. ساخت نخستین مدیر کل از
 *    طریق Artisan Command اختصاصی (`php artisan drikana:make-super-admin`)
 *    انجام می‌شود تا رمز عبور در کد یا تاریخچه Git ذخیره نشود.
 *
 * ۳) نقش‌های سیستمی اگر وجود داشته باشند، فقط «تکمیل» می‌شوند و
 *    مجوزهای سفارشی که مدیر افزوده، حذف نمی‌گردد.
 */
class RbacSeeder extends Seeder
{
    public function run()
    {
        // =========================================================
        // ۱) ایجاد مجوزها
        // =========================================================
        $flat = PermissionRegistry::flattened();
        $created = 0;
        $updated = 0;

        foreach ($flat as $slug => $meta) {
            $existing = Permission::query()->where('slug', $slug)->first();

            if ($existing) {
                // فقط عنوان و گروه را به‌روز کن — نه حذف/بازنویسی مجوز
                if ($existing->name !== $meta['name'] || $existing->group !== $meta['group']) {
                    $existing->update([
                        'name'  => $meta['name'],
                        'group' => $meta['group'],
                    ]);
                    $updated++;
                }
                continue;
            }

            Permission::create([
                'slug'        => $slug,
                'name'        => $meta['name'],
                'group'       => $meta['group'],
                'description' => null,
            ]);
            $created++;
        }

        $this->command->info("مجوزها: {$created} مورد ایجاد، {$updated} مورد به‌روزرسانی.");

        // =========================================================
        // ۲) ایجاد نقش‌ها و اتصال مجوزها
        // =========================================================
        $allSlugs = PermissionRegistry::slugs();

        foreach (PermissionRegistry::roles() as $slug => $definition) {

            $role = Role::query()->where('slug', $slug)->first();

            if (!$role) {
                $role = new Role();
                $role->slug = $slug;
                $role->permissions = json_encode([], JSON_UNESCAPED_UNICODE);
            }

            $role->name        = $definition['name'];
            $role->description = isset($definition['description']) ? $definition['description'] : null;
            $role->level       = isset($definition['level']) ? $definition['level'] : 0;
            $role->is_system   = true;
            $role->is_active   = true;
            $role->save();

            // محاسبه مجوزهای هدف
            if ($definition['permissions'] === '*') {
                $targetPermissions = $allSlugs;
            } else {
                $targetPermissions = $definition['permissions'];
            }

            // ─────────────────────────────────────────────────
            // ادغام با مجوزهای موجود، بدون حذف مجوزهای سفارشی مدیر
            // ─────────────────────────────────────────────────
            $currentPermissions = $role->permissionSlugs();

            // برای نقش super_admin همیشه همه مجوزها اعمال می‌شود
            if ($definition['permissions'] === '*') {
                $merged = $targetPermissions;
            } else {
                // مجوزهای تعریف‌شده در Registry حتماً باید باشند؛
                // مجوزهای اضافه‌ای که مدیر داده، حفظ می‌شوند
                $merged = array_values(array_unique(array_merge($currentPermissions, $targetPermissions)));
            }

            $role->syncPermissions($merged);

            $this->command->info(
                "نقش «{$definition['name']}» ({$slug}): " . count($merged) . ' مجوز'
            );
        }

        // =========================================================
        // ۳) انتساب نقش «کاربر عادی» به کاربران غیرمدیر بدون نقش
        //    (بدون حذف نقش‌های موجود کاربران)
        //
        // مستثنا کردن مدیران عمدی است: آن‌ها در گام ۴ نقش «مدیر کل
        // سیستم» را می‌گیرند و داشتن هر دو نقش، گزارش‌های دسترسی را
        // مبهم می‌کند. منتسب‌کردن اینجا «حداقل دسترسی» برای کاربران
        // عادی است، نه یک انتساب خودکار برای همه.
        // =========================================================
        $customerRole = Role::query()->where('slug', 'customer')->first();

        if ($customerRole) {
            $usersWithoutRole = DB::table('users')
                // مدیران سامانه در گام ۴ نقش «مدیر کل سیستم» را می‌گیرند؛
                // دادن نقش «کاربر عادی» به آن‌ها فقط انتساب زائد می‌سازد.
                ->where(function ($q) {
                    $q->whereNull('user_type')
                      ->orWhere('user_type', '!=', 'admin');
                })
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('role_user')
                      ->whereRaw('role_user.user_id = users.id');
                })
                ->pluck('id');

            $assigned = 0;
            foreach ($usersWithoutRole as $userId) {
                DB::table('role_user')->insertOrIgnore([
                    'user_id'     => $userId,
                    'role_id'     => $customerRole->id,
                    'assigned_by' => null,
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $assigned++;
            }

            $this->command->info("نقش «کاربر عادی» به {$assigned} کاربر بدون نقش اختصاص یافت.");
        }

        // =========================================================
        // ۴) انتساب نقش «مدیر کل سیستم» به مدیران موجود
        //    (حفظ دسترسی فعلی — جلوگیری از قفل‌شدن پنل)
        // =========================================================
        $superAdminRole = Role::query()->where('slug', 'super_admin')->first();

        if ($superAdminRole) {
            $adminIds = DB::table('users')->where('user_type', 'admin')->pluck('id');
            $attached = 0;

            foreach ($adminIds as $adminId) {
                $exists = DB::table('role_user')
                    ->where('user_id', $adminId)
                    ->where('role_id', $superAdminRole->id)
                    ->exists();

                if (!$exists) {
                    DB::table('role_user')->insert([
                        'user_id'     => $adminId,
                        'role_id'     => $superAdminRole->id,
                        'assigned_by' => null,
                        'assigned_at' => now(),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    $attached++;
                }
            }

            $this->command->info("نقش «مدیر کل سیستم» به {$attached} مدیر موجود اختصاص یافت.");
        }

        // =========================================================
        // ۵) پاک‌سازی کش
        // =========================================================
        Permission::flushCache();
        Role::flushCache();
        \App\User::flushAllPermissionCaches();

        $this->command->info('کش مجوزها پاک‌سازی شد.');
        $this->command->info('✅ RBAC با موفقیت نصب شد.');
    }
}
