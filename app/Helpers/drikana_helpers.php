<?php
/**
 * Drikana Safe DB helpers
 * — prevent "Base table or view not found" errors when tables don't exist yet
 */

if (!function_exists('safe_table_exists')) {
    function safe_table_exists($table) {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('safe_business_setting')) {
    function safe_business_setting($key, $default = null) {
        try {
            if (!safe_table_exists('business_settings')) return $default;
            $row = \Illuminate\Support\Facades\DB::table('business_settings')->where('type', $key)->first();
            return $row ? $row->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('drikana_setting')) {
    function drikana_setting($key, $default = null) {
        return safe_business_setting($key, $default);
    }
}

// =========================================================================
// دسترسی منوی مدیریت (سازگار با سیستم قدیمی «staff»)
// =========================================================================

if (!function_exists('drikana_admin_staff_permissions')) {
    /**
     * شناسه‌های مجوز منوی مدیریت برای کاربر جاری — بر اساس سیستم قدیمی
     * «staff → role → permissions» پروژه.
     *
     * ── چرا این تابع لازم است؟ (رفع باگ) ─────────────────────────────
     * در فایل `inc/admin_sidenav.blade.php` پانزده بار عبارت زیر تکرار
     * شده بود:
     *
     *     Auth::user()->user_type == 'admin'
     *     || in_array('5', json_decode(Auth::user()->staff->role->permissions))
     *
     * این عبارت دو اشکال جدی داشت:
     *   ۱) برای کاربری که رکورد `staff` ندارد (مثلاً مدیر سیستمی که با
     *      نقش جدید RBAC وارد شده، یا مشتری‌ای که به صفحه‌ای با ویوی
     *      ادمین می‌رسد) مقدار `->staff` برابر null است و
     *      `null->role` خطای Fatal از نوع «Trying to get property of
     *      non-object» می‌دهد → صفحه با ۵۰۰ از کار می‌افتاد.
     *   ۲) `json_decode()` بدون آرگومان دوم یک stdClass برمی‌گرداند و
     *      `in_array()` روی آن در PHP 7.4 هشدار می‌داد (و در PHP 8
     *      خطای TypeError می‌شد).
     *
     * این تابع هر دو مشکل را برطرف می‌کند و همیشه آرایه برمی‌گرداند.
     *
     * @return array<int,string>
     */
    function drikana_admin_staff_permissions()
    {
        try {
            $user = \Illuminate\Support\Facades\Auth::user();

            if ($user === null) {
                return [];
            }

            // مدیر کل سیستم همیشه همه منوها را می‌بیند
            if (isset($user->user_type) && $user->user_type === 'admin') {
                return ['*'];
            }

            $staff = isset($user->staff) ? $user->staff : null;
            $role  = $staff ? $staff->role : null;
            $json  = $role ? $role->permissions : null;

            if (empty($json)) {
                return [];
            }

            $decoded = json_decode($json, true);

            return is_array($decoded) ? array_map('strval', $decoded) : [];
        } catch (\Exception $e) {
            // هر خطای غیرمنتظره → بدون دسترسی منو (Fail-safe)
            return [];
        }
    }
}

if (!function_exists('drikana_admin_menu_allowed')) {
    /**
     * آیا کاربر جاری به آیتم‌های منوی مدیریت با شناسه‌های داده‌شده دسترسی دارد؟
     *
     * جایگزین امن عبارت قدیمی در `inc/admin_sidenav.blade.php`.
     *
     * @param  string|array $legacyIds  شناسه‌های عددی مجوز منو (مثلاً '5' یا ['5','6'])
     * @return bool
     */
    function drikana_admin_menu_allowed($legacyIds = [])
    {
        $owned = drikana_admin_staff_permissions();

        if (in_array('*', $owned, true)) {
            return true;
        }

        $legacyIds = is_array($legacyIds) ? $legacyIds : [$legacyIds];

        foreach ($legacyIds as $id) {
            if (in_array((string) $id, $owned, true)) {
                return true;
            }
        }

        return false;
    }
}
