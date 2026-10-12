<?php

namespace App\Services\Rbac;

/**
 * فهرست مرکزی مجوزهای سیستم (Source of Truth).
 *
 * ── چرا این کلاس لازم است؟ ──────────────────────────────────────────
 * مجوزها باید در یک نقطه تعریف شوند تا:
 *   • Seeder بتواند آن‌ها را در دیتابیس بسازد
 *   • پنل مدیریت بتواند فرم تخصیص را بسازد
 *   • امکان «مجوز تایپی اشتباه» در کد از بین برود
 *
 * ── اصل طراحی ─────────────────────────────────────────────────────
 * عملیات‌ها تفکیک شده‌اند: مشاهده ≠ ایجاد ≠ ویرایش ≠ حذف ≠ تأیید ≠ انتشار.
 * این تفکیک صریحاً در نیازمندی خواسته شده است.
 *
 * ── افزودن مجوز جدید ──────────────────────────────────────────────
 * ۱) در همین آرایه یک ورودی اضافه کنید
 * ۲) `php artisan db:seed --class=RbacSeeder` را اجرا کنید
 * ۳) در روت یا کنترلر با `permission:slug` استفاده کنید
 */
class PermissionRegistry
{
    /**
     * ساختار: 'گروه' => [ 'slug' => 'عنوان فارسی' ]
     *
     * @return array
     */
    public static function all()
    {
        return [

            // =========================================================
            // ۱) مدیریت سیستم و دسترسی‌ها
            // =========================================================
            'سیستم و دسترسی‌ها' => [
                'dashboard.view'          => 'مشاهده داشبورد مدیریت',
                'settings.view'           => 'مشاهده تنظیمات',
                'settings.update'         => 'ویرایش تنظیمات اصلی',
                'users.view'              => 'مشاهده کاربران',
                'users.create'            => 'ایجاد کاربر',
                'users.update'            => 'ویرایش کاربران',
                'users.delete'            => 'حذف کاربران',
                'roles.view'              => 'مشاهده نقش‌ها',
                'roles.create'            => 'ایجاد نقش',
                'roles.update'            => 'ویرایش نقش',
                'roles.delete'            => 'حذف نقش',
                'roles.assign'            => 'انتساب نقش به کاربران',
                'permissions.view'        => 'مشاهده فهرست مجوزها',
                'permissions.create'       => 'ایجاد مجوز جدید',
                'permissions.delete'       => 'حذف مجوز',
                'audit_logs.view'         => 'مشاهده گزارش‌های امنیتی و Audit Log',
                'security.reports.view'   => 'مشاهده گزارش‌های مدیریتی و امنیتی',
            ],

            // =========================================================
            // ۲) فروشگاه — محصولات طلا و جواهرات
            // =========================================================
            'فروشگاه — محصولات' => [
                'products.view'           => 'مشاهده محصولات',
                'products.create'         => 'ایجاد محصول',
                'products.update'         => 'ویرایش محصول',
                'products.delete'         => 'حذف محصول',
                'products.publish'        => 'انتشار / غیرفعال‌سازی محصول',
                'categories.view'         => 'مشاهده دسته‌بندی‌ها',
                'categories.manage'       => 'مدیریت دسته‌بندی‌ها',
                'brands.view'             => 'مشاهده برندها',
                'brands.manage'           => 'مدیریت برندها',
                'stock.view'              => 'مشاهده موجودی',
                'stock.update'            => 'به‌روزرسانی موجودی',
                'products.images.manage'  => 'مدیریت تصاویر محصولات',
            ],

            // =========================================================
            // ۳) فروشگاه — سفارش‌ها و فروش
            // =========================================================
            'فروشگاه — سفارش‌ها' => [
                'orders.view'             => 'مشاهده سفارش‌ها',
                'orders.update'           => 'ویرایش سفارش‌ها',
                'orders.update_status'    => 'تغییر وضعیت سفارش',
                'orders.delete'           => 'حذف سفارش',
                'orders.invoice'          => 'دانلود فاکتور',
                'sales.reports.view'      => 'مشاهده گزارش‌های فروش',
                'sellers.view'            => 'مشاهده فروشندگان',
                'sellers.approve'         => 'تأیید / رد فروشندگان',
                'coupons.manage'          => 'مدیریت کدهای تخفیف',
                'withdraw_requests.view'  => 'مشاهده درخواست‌های تسویه',
                'withdraw_requests.approve' => 'تأیید درخواست تسویه',
            ],

            // =========================================================
            // ۴) ماژول طلا و جواهرات — مالکان
            // =========================================================
            'جواهرات — مالکان' => [
                'jewelry.dashboard.view'       => 'مشاهده داشبورد اشیاء قیمتی',
                'jewelry.owners.view'          => 'مشاهده مالکان اشیاء',
                'jewelry.owners.create'        => 'ثبت مالک جدید',
                'jewelry.owners.update'        => 'ویرایش اطلاعات مالک',
                'jewelry.owners.delete'        => 'حذف مالک',
            ],

            // =========================================================
            // ۵) ماژول طلا و جواهرات — شناسنامه و گواهی اصالت
            // =========================================================
            'جواهرات — شناسنامه' => [
                'jewelry.certificates.view'     => 'مشاهده شناسنامه‌ها',
                'jewelry.certificates.create'   => 'صدور شناسنامه جدید',
                'jewelry.certificates.update'   => 'ویرایش شناسنامه',
                'jewelry.certificates.delete'   => 'حذف شناسنامه',
                'jewelry.certificates.verify'   => 'تأیید اصالت شناسنامه',
                'jewelry.certificates.download' => 'دانلود فایل شناسنامه',
                'jewelry.certificates.print'    => 'چاپ شناسنامه',
            ],

            // =========================================================
            // ۶) ماژول تعمیرات
            // =========================================================
            'جواهرات — تعمیرات' => [
                'jewelry.repairs.view'          => 'مشاهده سفارش‌های تعمیر مجاز',
                'jewelry.repairs.create'        => 'ثبت سفارش تعمیر',
                'jewelry.repairs.update'        => 'ویرایش سفارش تعمیر',
                'jewelry.repairs.update_status' => 'ثبت عیب، شرح تعمیر، هزینه و تغییر وضعیت',
                'jewelry.repairs.assign'        => 'تخصیص تکنسین به سفارش',
                'jewelry.repairs.delete'        => 'حذف سفارش تعمیر',
            ],

            // =========================================================
            // ۷) ماژول مالکیت و انتقال
            // =========================================================
            'جواهرات — مالکیت' => [
                'jewelry.transfers.view'    => 'مشاهده درخواست‌های انتقال مالکیت',
                'jewelry.transfers.create'  => 'ثبت درخواست انتقال مالکیت',
                'jewelry.transfers.approve' => 'تأیید انتقال مالکیت',
                'jewelry.transfers.reject'  => 'رد انتقال مالکیت',
                'jewelry.transfers.cancel'  => 'لغو انتقال مالکیت',
                'jewelry.transfers.delete'  => 'حذف درخواست انتقال (فقط رد/لغو شده)',
            ],

            // =========================================================
            // ۸) ماژول سرقت و مفقودی
            // =========================================================
            'جواهرات — سرقت و مفقودی' => [
                'jewelry.missing_reports.view'          => 'مشاهده گزارش‌های سرقت و مفقودی',
                'jewelry.missing_reports.create'        => 'ثبت گزارش سرقت و مفقودی',
                'jewelry.missing_reports.update_status' => 'تغییر وضعیت گزارش',
                'jewelry.missing_reports.print'         => 'چاپ گزارش',
            ],

            // =========================================================
            // ۹) گزارش‌های ماژول جواهرات
            // =========================================================
            'جواهرات — گزارش‌ها' => [
                'jewelry.reports.view'   => 'مشاهده گزارش‌های ماژول جواهرات',
                'jewelry.reports.export' => 'خروجی گرفتن از گزارش‌ها',
            ],

            // =========================================================
            // ۱۰) مقالات و محتوا
            // =========================================================
            'مقالات' => [
                'articles.view'               => 'مشاهده همه مقالات',
                'articles.view_own'           => 'مشاهده مقالات خود',
                'articles.create'             => 'ایجاد مقاله',
                'articles.update'             => 'ویرایش مقاله',
                'articles.update_any'         => 'ویرایش مقاله‌های سایر نویسندگان',
                'articles.submit'             => 'ارسال مقاله برای بررسی',
                'articles.publish'            => 'انتشار / لغو انتشار مقاله',
                'articles.delete'             => 'حذف مقاله',
                'articles.categories.manage'  => 'مدیریت دسته‌بندی مقاله‌ها',
                'articles.images.manage'      => 'مدیریت تصاویر مقاله',
            ],

            // =========================================================
            // ۱۱) محتوا و ظاهر سایت
            // =========================================================
            'محتوا و ظاهر سایت' => [
                'pages.manage'      => 'مدیریت صفحات',
                'sliders.manage'    => 'مدیریت اسلایدرها',
                'banners.manage'    => 'مدیریت بنرها',
                'policies.manage'   => 'مدیریت قوانین و مقررات',
                'seo.manage'        => 'مدیریت تنظیمات SEO',
                'languages.manage'  => 'مدیریت زبان‌ها',
            ],
        ];
    }

    /**
     * فهرست تخت مجوزها: [slug => ['name' => ..., 'group' => ...]]
     *
     * @return array
     */
    public static function flattened()
    {
        $flat = [];

        foreach (static::all() as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                $flat[$slug] = [
                    'name'  => $name,
                    'group' => $group,
                ];
            }
        }

        return $flat;
    }

    /**
     * فقط فهرست slugها.
     *
     * @return array
     */
    public static function slugs()
    {
        return array_keys(static::flattened());
    }

    /**
     * تعریف نقش‌های اولیه سیستم و مجوزهای هرکدام.
     *
     * هر نقش با فهرست slug مجوزها تعریف می‌شود. مقدار '*' یعنی همه مجوزها.
     *
     * @return array
     */
    public static function roles()
    {
        return [

            // =====================================================
            // ۱) مدیر کل سیستم — دسترسی کامل مدیریتی
            // =====================================================
            'super_admin' => [
                'name'        => 'مدیر کل سیستم',
                'description' => 'دسترسی مدیریتی به تمام بخش‌ها، مدیریت کاربران، نقش‌ها و مجوزها، تنظیمات اصلی و گزارش‌های امنیتی.',
                'level'       => 100,
                'is_system'   => true,
                'permissions' => '*',
            ],

            // =====================================================
            // ۲) مدیر فروشگاه — محصولات، دسته‌بندی، موجودی، سفارش‌ها
            //    بدون دسترسی به تغییر مالکیت
            // =====================================================
            'store_manager' => [
                'name'        => 'مدیر فروشگاه',
                'description' => 'مدیریت محصولات طلا و جواهرات، دسته‌بندی‌ها، تصاویر، موجودی و سفارش‌ها. بدون دسترسی به تغییر مالکیت.',
                'level'       => 60,
                'is_system'   => true,
                'permissions' => [
                    'dashboard.view',
                    'products.view', 'products.create', 'products.update', 'products.delete',
                    'products.publish', 'products.images.manage',
                    'categories.view', 'categories.manage',
                    'brands.view', 'brands.manage',
                    'stock.view', 'stock.update',
                    'orders.view', 'orders.update', 'orders.update_status', 'orders.invoice',
                    'sales.reports.view',
                    'coupons.manage',
                    'jewelry.dashboard.view',
                    'jewelry.certificates.view',
                    'jewelry.certificates.print',
                    // توجه: هیچ مجوزی از jewelry.transfers.* داده نشده است
                    //       مگر با مجوز مستقل (طبق نیازمندی)
                ],
            ],

            // =====================================================
            // ۳) تکنسین تعمیرات — فقط بخش تعمیرات
            // =====================================================
            'repair_technician' => [
                'name'        => 'تکنسین تعمیرات',
                'description' => 'مشاهده سفارش‌های تعمیر مجاز، ثبت عیب، شرح تعمیر، هزینه و قطعات مصرفی، تغییر وضعیت و ثبت گزارش نهایی. بدون دسترسی به سایر بخش‌ها.',
                'level'       => 40,
                'is_system'   => true,
                'permissions' => [
                    'dashboard.view',
                    'jewelry.dashboard.view',
                    'jewelry.repairs.view',
                    'jewelry.repairs.create',
                    'jewelry.repairs.update',
                    'jewelry.repairs.update_status',
                    'jewelry.certificates.view',
                ],
            ],

            // =====================================================
            // ۴) مدیر مالکیت — سوابق و انتقال مالکیت
            // =====================================================
            'ownership_manager' => [
                'name'        => 'مدیر مالکیت',
                'description' => 'مشاهده سوابق مالکیت، ثبت درخواست انتقال، بررسی و تأیید درخواست‌ها، مشاهده تاریخچه انتقال. بدون دسترسی به بخش‌های نامرتبط.',
                'level'       => 50,
                'is_system'   => true,
                'permissions' => [
                    'dashboard.view',
                    'jewelry.dashboard.view',
                    'jewelry.owners.view', 'jewelry.owners.create', 'jewelry.owners.update',
                    'jewelry.certificates.view', 'jewelry.certificates.print', 'jewelry.certificates.download',
                    'jewelry.certificates.verify',
                    'jewelry.transfers.view',
                    'jewelry.transfers.create',
                    'jewelry.transfers.approve',
                    'jewelry.transfers.reject',
                    'jewelry.transfers.cancel',
                    'jewelry.missing_reports.view',
                    'jewelry.missing_reports.update_status',
                    'jewelry.reports.view',
                ],
            ],

            // =====================================================
            // ۵) مدیر محتوا — مقالات
            // =====================================================
            'content_manager' => [
                'name'        => 'مدیر محتوا',
                'description' => 'ایجاد و ویرایش مقالات، مدیریت دسته‌بندی‌ها و تصاویر، مدیریت وضعیت انتشار مقالات.',
                'level'       => 30,
                'is_system'   => true,
                'permissions' => [
                    'dashboard.view',
                    'articles.view',
                    'articles.view_own',
                    'articles.create',
                    'articles.update',
                    'articles.submit',
                    'articles.publish',
                    'articles.delete',
                    'articles.categories.manage',
                    'articles.images.manage',
                    'pages.manage',
                ],
            ],

            // =====================================================
            // ۶) کاربر عادی — فقط صفحات عمومی و اطلاعات خودش
            // =====================================================
            'customer' => [
                'name'        => 'کاربر عادی',
                'description' => 'مشاهده صفحات عمومی، مدیریت اطلاعات حساب خود، دسترسی فقط به اطلاعات متعلق به خود.',
                'level'       => 10,
                'is_system'   => true,
                // کاربر عادی هیچ مجوز سراسری ندارد.
                // دسترسی او به اطلاعات خودش از طریق Policy و بررسی مالکیت
                // در کنترلر انجام می‌شود، نه با permission.
                'permissions' => [],
            ],
        ];
    }
}
