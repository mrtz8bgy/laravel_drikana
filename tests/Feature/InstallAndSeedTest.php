<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\PermissionRegistry;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * تست‌های نصب، سیدرها و ساخت نخستین مدیر کل.
 *
 * ── چرا این تست‌ها لازم‌اند؟ ─────────────────────────────────────────
 * مسیر «نصب» را نمی‌توان دستی آزمود؛ چون فقط یک بار اجرا می‌شود و اگر
 * خراب باشد، کشفش بعد از استقرار و روی دیتابیس واقعی رخ می‌دهد. این
 * تست‌ها همان مسیر را روی دیتابیس آزمایشی اجرا می‌کنند و سه چیز را
 * تضمین می‌کنند:
 *
 *   ۱) سیدر مجوزها «تکرارپذیر» است (اجرای دوباره داده تکراری نمی‌سازد)
 *   ۲) نقش‌های پیش‌فرض دقیقاً همان تعداد مجوز مستندشده را دارند
 *   ۳) دستور ساخت مدیر کل، رمز ضعیف را رد می‌کند و در نبود منبع رمز
 *      بی‌صدا به ورودی تعاملی برنمی‌گردد
 */
class InstallAndSeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * اجرای سیدر نقش‌ها و مجوزها از طریق کنسول.
     *
     * @return int کد خروج
     */
    protected function seedRbac()
    {
        return Artisan::call('db:seed', [
            '--class' => 'RbacSeeder',
            '--force' => true,
        ]);
    }

    /**
     * اجرای دستور ساخت مدیر کل با رمز داده‌شده در یک فایل موقت.
     *
     * @param  string      $password
     * @param  string|null $file     اگر null باشد، مسیر ناموجود استفاده می‌شود
     * @return int کد خروج دستور
     */
    protected function runMakeSuperAdmin($password, $file = null)
    {
        $path = $file === null ? tempnam(sys_get_temp_dir(), 'drikana_pw_') : $file;

        if ($file === null) {
            file_put_contents($path, $password . "\n");
            chmod($path, 0600);
        }

        $exit = Artisan::call('drikana:make-super-admin', [
            '--email'         => 'install-test-' . uniqid() . '@example.com',
            '--name'          => 'مدیر آزمون',
            '--password-file' => $path,
            '--force'         => true,
        ]);

        if ($file === null && is_file($path)) {
            unlink($path);
        }

        return $exit;
    }

    // =================================================================
    // ۱) سیدر مجوزها — درستی داده
    // =================================================================

    /**
     * تعداد مجوزهای دیتابیس باید با رجیستری کد یکی باشد.
     *
     * اگر کسی مجوزی به رجیستری اضافه کند و سیدر را اجرا نکند، یا
     * برعکس، این تست می‌شکند و فاصله کد/دیتابیس پیش از استقرار دیده
     * می‌شود.
     */
    public function test_permission_registry_matches_database()
    {
        $this->seedRbac();

        $registryCount = count(PermissionRegistry::flattened());
        $dbCount = Permission::query()->count();

        $this->assertGreaterThan(0, $registryCount, 'رجیستری مجوزها خالی است.');
        $this->assertEquals(
            $registryCount,
            $dbCount,
            'تعداد مجوزهای دیتابیس با رجیستری کد یکسان نیست. سیدر RbacSeeder را اجرا کنید.'
        );
    }

    /**
     * سیدر باید تکرارپذیر باشد: اجرای دوباره نباید داده تکراری بسازد
     * و نباید انتساب‌های موجود را پاک کند.
     */
    public function test_rbac_seeder_is_idempotent()
    {
        $this->seedRbac();
        $this->seedRbac();

        $firstPermissions = Permission::query()->count();
        $firstMappings = DB::table('permission_role')->count();

        $this->seedRbac();

        $this->assertEquals($firstPermissions, Permission::query()->count());
        $this->assertEquals($firstMappings, DB::table('permission_role')->count());
    }

    /**
     * هر slug مجوز باید یکتا باشد — تضمین سمت دیتابیس.
     */
    public function test_permission_slugs_are_unique()
    {
        $this->seedRbac();

        $duplicates = DB::table('permissions')
            ->select('slug', DB::raw('COUNT(*) AS c'))
            ->groupBy('slug')
            ->having('c', '>', 1)
            ->get();

        $this->assertCount(0, $duplicates, 'مجوز تکراری در دیتابیس وجود دارد.');
    }

    // =================================================================
    // ۲) نقش‌های پیش‌فرض
    // =================================================================

    /**
     * شش نقش پیش‌فرض باید وجود داشته باشند.
     */
    public function test_six_default_roles_exist()
    {
        $this->seedRbac();

        foreach (['super_admin', 'store_manager', 'ownership_manager',
                  'repair_technician', 'content_manager', 'customer'] as $slug) {
            $this->assertNotNull(
                Role::query()->where('slug', $slug)->first(),
                "نقش پیش‌فرض «{$slug}» در دیتابیس وجود ندارد."
            );
        }
    }

    /**
     * تعداد مجوز هر نقش پیش‌فرض باید همان مقدار مستندشده در
     * docs/roles-and-permissions.md باشد.
     *
     * این تست جلوی «افزایش خاموش دسترسی» را می‌گیرد: اگر کسی به‌طور
     * تصادفی مجوزی به نقش تکنسین تعمیرات اضافه کند، اینجا دیده می‌شود.
     */
    public function test_default_role_permission_counts()
    {
        $this->seedRbac();

        $expected = [
            'super_admin'       => Permission::query()->count(), // همه مجوزها
            'store_manager'     => 22,
            'ownership_manager' => 17,
            'repair_technician' => 7,
            'content_manager'   => 11,
            'customer'          => 0,
        ];

        foreach ($expected as $slug => $count) {
            $role = Role::query()->where('slug', $slug)->firstOrFail();

            $this->assertEquals(
                $count,
                (int) DB::table('permission_role')->where('role_id', $role->getKey())->count(),
                "نقش «{$slug}» باید {$count} مجوز داشته باشد."
            );
        }
    }

    /**
     * نقش «کاربر عادی» هیچ مجوز مدیریتی ندارد — دسترسی‌های آن از
     * راه Policy و بررسی مالکیت رکورد انجام می‌شود.
     */
    public function test_customer_role_has_no_administrative_permission()
    {
        $this->seedRbac();

        $customer = Role::query()->where('slug', 'customer')->firstOrFail();

        $this->assertEquals(
            0,
            (int) DB::table('permission_role')->where('role_id', $customer->getKey())->count()
        );
    }

    /**
     * نقش «مدیر کل سیستم» باید همه مجوزها را داشته باشد — وگرنه
     * مدیری که برای تعمیر پنل آمده، خودش بیرون می‌ماند.
     */
    public function test_super_admin_has_every_permission()
    {
        $this->seedRbac();

        $superAdmin = Role::query()->where('slug', 'super_admin')->firstOrFail();
        $allSlugs = array_keys(PermissionRegistry::flattened());

        $granted = $superAdmin->permissionSlugs();

        $missing = array_diff($allSlugs, $granted);

        $this->assertCount(
            0,
            $missing,
            'این مجوزها به مدیر کل داده نشده‌اند: ' . implode(', ', $missing)
        );
    }

    /**
     * هر مجوز دیتابیس باید حداقل به یک نقش داده شده باشد.
     *
     * مجوزی که به هیچ نقشی داده نشده باشد، عملاً یک راه انحرافی برای
     * «دسترسی غیرقابل رهگیری» می‌سازد: کسی می‌تواند از پنل آن را به
     * نقش دلخواه بدهد بدون اینکه کسی متوجه شود از ابتدا بی‌استفاده
     * بوده.
     */
    public function test_every_permission_is_granted_to_at_least_one_role()
    {
        $this->seedRbac();

        $orphans = DB::table('permissions')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('permission_role')
                  ->whereRaw('permission_role.permission_id = permissions.id');
            })
            ->pluck('slug')
            ->all();

        $this->assertCount(
            0,
            $orphans,
            'این مجوزها به هیچ نقشی داده نشده‌اند: ' . implode(', ', $orphans)
        );
    }

    // =================================================================
    // ۳) داده نمونه — نباید پیش‌فرض اجرا شود
    // =================================================================

    /**
     * سیدر اصلی نباید به‌صورت پیش‌فرض داده نمونه بسازد.
     *
     * نصب روی سرور واقعی نباید محصول و مشتری جعلی ایجاد کند. این تست
     * منطق پرچم را با بازتاب (reflection) بررسی می‌کند تا نیازی به
     * اجرای واقعی سیدر نباشد.
     */
    public function test_demo_data_is_not_seeded_by_default()
    {
        putenv('DRIKANA_SEED_DEMO_DATA');

        $seeder = new \DatabaseSeeder();
        $method = new \ReflectionMethod($seeder, 'demoDataRequested');
        $method->setAccessible(true);

        $this->assertFalse(
            $method->invoke($seeder),
            'سیدر اصلی نباید بدون پرچم محیطی داده نمونه بسازد.'
        );
    }

    /**
     * با پرچم محیطی، درخواست داده نمونه پذیرفته می‌شود.
     *
     * برای پرهیز از وابستگی به کش env، مقدار را در دو جا می‌گذاریم.
     */
    public function test_demo_data_flag_enables_sample_seeders()
    {
        putenv('DRIKANA_SEED_DEMO_DATA=true');
        $_ENV['DRIKANA_SEED_DEMO_DATA'] = 'true';

        try {
            $seeder = new \DatabaseSeeder();
            $method = new \ReflectionMethod($seeder, 'demoDataRequested');
            $method->setAccessible(true);

            $this->assertTrue($method->invoke($seeder));
        } finally {
            putenv('DRIKANA_SEED_DEMO_DATA');
            unset($_ENV['DRIKANA_SEED_DEMO_DATA']);
        }
    }

    // =================================================================
    // ۴) ساخت نخستین مدیر کل
    // =================================================================

    /**
     * رمز کوتاه باید رد شود و هیچ کاربری ساخته نشود.
     */
    public function test_make_super_admin_rejects_short_password()
    {
        $this->seedRbac();

        $before = User::query()->count();
        $exit = $this->runMakeSuperAdmin('Short1!');

        $this->assertEquals(1, $exit, 'رمز کوتاه باید با کد خروج ۱ رد شود.');
        $this->assertEquals($before, User::query()->count(), 'کاربری نباید ساخته شده باشد.');
    }

    /**
     * رمز رایج و ضعیف باید رد شود.
     */
    public function test_make_super_admin_rejects_common_weak_password()
    {
        $this->seedRbac();

        $before = User::query()->count();
        $exit = $this->runMakeSuperAdmin('password123');

        $this->assertEquals(1, $exit, 'رمز رایج باید رد شود.');
        $this->assertEquals($before, User::query()->count());
    }

    /**
     * رمز بدون تنوع کاراکتر (تنها حرف کوچک و رقم) باید رد شود.
     */
    public function test_make_super_admin_rejects_low_variety_password()
    {
        $this->seedRbac();

        $exit = $this->runMakeSuperAdmin('abcdefgh1234');

        $this->assertEquals(1, $exit, 'رمز با تنوع کم باید رد شود.');
    }

    /**
     * نبود فایل رمز عبور باید خطا بدهد — نه اینکه بی‌صدا به پرسش
     * تعاملی برگردد. بازگشت به پرسش، اسکریپت خودکار را در محیط بی‌ترمینال
     * بی‌صدا متوقف می‌کند.
     */
    public function test_make_super_admin_fails_on_missing_password_file()
    {
        $this->seedRbac();

        $before = User::query()->count();
        $exit = $this->runMakeSuperAdmin('', sys_get_temp_dir() . '/drikana-no-such-file-' . uniqid());

        $this->assertEquals(1, $exit, 'فایل ناموجود باید کد خروج ۱ بدهد.');
        $this->assertEquals($before, User::query()->count());
    }

    /**
     * مسیر موفق: رمز قوی از فایل ⇒ کاربر ساخته می‌شود، رمز هش می‌شود،
     * نقش مدیر کل داده می‌شود و رخداد در Audit Log ثبت می‌گردد.
     */
    public function test_make_super_admin_creates_working_account()
    {
        $this->seedRbac();

        $email = 'created-' . uniqid() . '@example.com';
        $password = 'Dr!kana-2026-' . uniqid();

        $path = tempnam(sys_get_temp_dir(), 'drikana_pw_');
        file_put_contents($path, $password . "\n");
        chmod($path, 0600);

        $exit = Artisan::call('drikana:make-super-admin', [
            '--email'         => $email,
            '--name'          => 'مدیر کل آزمون',
            '--password-file' => $path,
            '--force'         => true,
        ]);

        unlink($path);

        $this->assertEquals(0, $exit, 'ساخت مدیر کل باید موفق باشد.');

        $user = User::query()->where('email', $email)->first();

        $this->assertNotNull($user, 'کاربر مدیر کل ساخته نشد.');

        // رمز عبور باید هش‌شده ذخیره شده باشد و با مقدار خام یکی نباشد
        $this->assertNotEquals($password, $user->password, 'رمز عبور به‌صورت خام ذخیره شده است.');
        $this->assertTrue(
            Hash::check($password, $user->password),
            'هش ذخیره‌شده با رمز وارد‌شده مطابقت ندارد.'
        );

        // نقش مدیر کل باید داده شده باشد
        $this->assertTrue(
            $user->roles()->where('slug', 'super_admin')->exists(),
            'نقش «مدیر کل سیستم» به کاربر داده نشد.'
        );

        $this->assertTrue(
            $user->hasPermissionTo('roles.assign'),
            'کاربر مدیر کل باید مجوز واگذاری نقش داشته باشد.'
        );

        // رخداد باید در Audit Log ثبت شده باشد
        $this->assertGreaterThan(
            0,
            DB::table('audit_logs')
                ->where('user_id', $user->getKey())
                ->where('event', 'role.assigned')
                ->count(),
            'واگذاری نقش مدیر کل در Audit Log ثبت نشده است.'
        );
    }
}
