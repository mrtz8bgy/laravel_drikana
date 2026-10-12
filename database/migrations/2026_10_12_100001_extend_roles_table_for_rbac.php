<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * گسترش جدول موجود `roles` برای پیاده‌سازی RBAC.
 *
 * نکته مهم طراحی:
 * جدول `roles` از قبل در دیتابیس پروژه وجود دارد و شامل ستون `permissions`
 * از نوع TEXT (JSON) است که در هیچ‌جای کد استفاده نمی‌شد. طبق قانون «از ایجاد
 * ساختار تکراری جلوگیری کن»، همان جدول را توسعه می‌دهیم و ستون JSON را برای
 * سازگاری با کد قدیمی (`App\Http\Controllers\RoleController`) حفظ می‌کنیم.
 *
 * ستون‌های افزوده‌شده:
 *  - slug        : شناسه خوانا و یکتا برای ارجاع در کد و میدل‌ور (مثلاً `store_manager`)
 *  - description : توضیح نقش برای نمایش در پنل
 *  - level       : وزن نقش؛ نقش با level بالاتر در نمایش اولویت دارد
 *  - is_system   : نقش‌های سیستمی قابل حذف از پنل نیستند
 *  - is_active   : امکان غیرفعال‌سازی موقت نقش بدون حذف آن
 */
class ExtendRolesTableForRbac extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'slug')) {
                // ابتدا nullable اضافه می‌شود تا روی جدول دارای داده، خطای UNIQUE رخ ندهد
                $table->string('slug', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('roles', 'description')) {
                $table->string('description', 500)->nullable()->after('permissions');
            }
            if (!Schema::hasColumn('roles', 'level')) {
                $table->unsignedSmallInteger('level')->default(0)->after('description');
            }
            if (!Schema::hasColumn('roles', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('level');
            }
            if (!Schema::hasColumn('roles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_system');
            }
        });

        // پرکردن slug برای نقش‌های موجود بر اساس name (بدون حذف یا بازنویسی داده)
        if (Schema::hasColumn('roles', 'slug')) {
            $rows = \Illuminate\Support\Facades\DB::table('roles')->whereNull('slug')->get();
            foreach ($rows as $row) {
                $slug = $this->slugify($row->name);
                // تضمین یکتایی
                $base = $slug;
                $n = 1;
                while (\Illuminate\Support\Facades\DB::table('roles')->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . (++$n);
                }
                \Illuminate\Support\Facades\DB::table('roles')->where('id', $row->id)->update(['slug' => $slug]);
            }

            // حالا یکتایی را اعمال کن
            $exists = collect(\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM `roles` WHERE Key_name = 'roles_slug_unique'"))->count() > 0;
            if (!$exists) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->unique('slug', 'roles_slug_unique');
                });
            }
        }
    }

    /**
     * تبدیل نام نقش (فارسی یا انگلیسی) به slug امن و ASCII.
     */
    protected function slugify($name)
    {
        $map = [
            'مدیر کل سیستم' => 'super_admin',
            'مدیر فروشگاه'  => 'store_manager',
            'تکنسین تعمیرات' => 'repair_technician',
            'مدیر مالکیت'   => 'ownership_manager',
            'مدیر محتوا'    => 'content_manager',
            'کاربر عادی'    => 'customer',
            'مدیر'          => 'admin',
        ];
        if (isset($map[$name])) {
            return $map[$name];
        }
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '_', (string) $name), '_'));
        return $slug !== '' ? $slug : 'role_' . substr(md5($name . microtime()), 0, 8);
    }

    public function down()
    {
        if (!Schema::hasTable('roles')) {
            return;
        }
        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropIndex('roles_slug_unique');
            });
        } catch (\Exception $e) {
            // ایندکس وجود ندارد — نادیده بگیر
        }
        Schema::table('roles', function (Blueprint $table) {
            foreach (['slug', 'description', 'level', 'is_system', 'is_active'] as $col) {
                if (Schema::hasColumn('roles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
