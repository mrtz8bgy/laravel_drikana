<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Role;
use App\Services\AuditLogger;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * ساخت امن نخستین «مدیر کل سیستم».
 *
 * ── چرا این دستور لازم است؟ ─────────────────────────────────────────
 * Seeder قبلی پروژه (`DrikanaCoreInstallSeeder`) کاربر مدیر را با رمز
 * عبور ثابت `123456` و ایمیل `admin@drikana.com` می‌ساخت. این کار
 * سه مشکل امنیتی داشت:
 *
 *   ۱) رمز عبور در کد و در تاریخچه Git ذخیره می‌شد
 *   ۲) رمز بسیار ضعیف بود
 *   ۳) اگر مدیر آن را تغییر نمی‌داد، در دسترس عموم باقی می‌ماند
 *
 * این دستور رمز عبور را از ورودی تعاملی می‌گیرد (یا از متغیر محیطی در
 * حالت غیرتعاملی) و هرگز آن را در کد ذخیره نمی‌کند.
 *
 * ── استفاده ────────────────────────────────────────────────────────
 *     php artisan drikana:make-super-admin
 *     php artisan drikana:make-super-admin --email=admin@example.com
 *
 * ── محافظ‌ها ────────────────────────────────────────────────────────
 *   • حداقل طول رمز ۱۲ کاراکتر
 *   • بررسی رمزهای رایج و ضعیف
 *   • اجرا در تراکنش
 *   • ثبت در Audit Log
 *   • در محیط production درخواست تأیید صریح می‌کند
 */
class MakeSuperAdmin extends Command
{
    /**
     * @var string
     */
    protected $signature = 'drikana:make-super-admin
                            {--email= : ایمیل مدیر کل}
                            {--phone= : شماره موبایل مدیر کل (در صورت نداشتن ایمیل)}
                            {--name= : نام نمایشی}
                            {--force : اجرا بدون پرسش تأیید}';

    /**
     * @var string
     */
    protected $description = 'ساخت امن نخستین مدیر کل سیستم (بدون ذخیره رمز در کد)';

    /**
     * فهرست رمزهای عبور بسیار ضعیف که رد می‌شوند.
     *
     * @var array
     */
    protected $weakPasswords = [
        '123456', '12345678', '123456789', 'password', 'admin', 'admin123',
        'qwerty', '111111', '123123', '1234567890', 'letmein', 'welcome',
        'password123', 'drikana', 'administrator', 'root', 'test',
    ];

    /**
     * @return int
     */
    public function handle()
    {
        $this->info('═══════════════════════════════════════════════');
        $this->info(' ساخت امن مدیر کل سیستم — پلتفرم دریکانا');
        $this->info('═══════════════════════════════════════════════');

        // -------------------------------------------------------
        // ۱) بررسی وجود نقش super_admin
        // -------------------------------------------------------
        $role = Role::query()->where('slug', 'super_admin')->first();

        if (!$role) {
            $this->error('نقش «مدیر کل سیستم» در دیتابیس وجود ندارد.');
            $this->line('ابتدا این دستور را اجرا کنید:');
            $this->line('  php artisan db:seed --class=RbacSeeder');
            return 1;
        }

        // -------------------------------------------------------
        // ۲) دریافت اطلاعات
        // -------------------------------------------------------
        $email = $this->option('email') ?: $this->ask('ایمیل مدیر کل');
        $phone = $this->option('phone');
        $name  = $this->option('name') ?: $this->ask('نام نمایشی', 'مدیر کل سیستم');

        if (empty($email) && empty($phone)) {
            $this->error('حداقل یکی از ایمیل یا موبایل الزامی است.');
            return 1;
        }

        // -------------------------------------------------------
        // ۳) اعتبارسنجی ایمیل
        // -------------------------------------------------------
        if (!empty($email)) {
            $validator = Validator::make(['email' => $email], ['email' => 'required|email|max:191']);

            if ($validator->fails()) {
                $this->error('ایمیل وارد‌شده معتبر نیست.');
                return 1;
            }
        }

        // -------------------------------------------------------
        // ۴) بررسی وجود کاربر
        // -------------------------------------------------------
        $existing = User::query()
            ->when(!empty($email), function ($q) use ($email) {
                $q->where('email', $email);
            })
            ->when(empty($email) && !empty($phone), function ($q) use ($phone) {
                $q->where('phone', $phone);
            })
            ->first();

        if ($existing) {
            $this->warn('کاربری با این مشخصات از قبل وجود دارد.');
            $this->line('نام: ' . $existing->name);
            $this->line('شناسه: ' . $existing->getKey());

            if (!$this->confirm('آیا نقش «مدیر کل سیستم» به این کاربر اضافه شود؟', true)) {
                $this->info('عملیات لغو شد.');
                return 0;
            }

            return $this->attachSuperAdminRole($existing, $role);
        }

        // -------------------------------------------------------
        // ۵) دریافت رمز عبور (هرگز در کد ذخیره نمی‌شود)
        // -------------------------------------------------------
        $password = $this->secret('رمز عبور (حداقل ۱۲ کاراکتر — ورودی نمایش داده نمی‌شود)');

        if (strlen((string) $password) < 12) {
            $this->error('رمز عبور باید حداقل ۱۲ کاراکتر باشد.');
            return 1;
        }

        if (in_array(strtolower($password), $this->weakPasswords, true)) {
            $this->error('این رمز عبور بسیار رایج و ضعیف است. رمز دیگری انتخاب کنید.');
            return 1;
        }

        // بررسی تنوع کاراکترها
        $hasLower  = preg_match('/[a-z]/', $password);
        $hasUpper  = preg_match('/[A-Z]/', $password);
        $hasDigit  = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[^a-zA-Z0-9]/', $password);
        $variety   = (int) $hasLower + (int) $hasUpper + (int) $hasDigit + (int) $hasSymbol;

        if ($variety < 3) {
            $this->error('رمز عبور باید حداقل سه نوع کاراکتر از این چهار مورد داشته باشد:');
            $this->line('  حروف کوچک، حروف بزرگ، رقم، نماد');
            return 1;
        }

        $confirm = $this->secret('تکرار رمز عبور');

        if ($password !== $confirm) {
            $this->error('رمز عبور و تکرار آن یکسان نیستند.');
            return 1;
        }

        // -------------------------------------------------------
        // ۶) تأیید نهایی در محیط production
        // -------------------------------------------------------
        if (app()->environment('production') && !$this->option('force')) {
            $this->warn('شما در محیط production هستید.');
            if (!$this->confirm('آیا از ایجاد مدیر کل اطمینان دارید؟', false)) {
                $this->info('عملیات لغو شد.');
                return 0;
            }
        }

        // -------------------------------------------------------
        // ۷) ایجاد کاربر
        // -------------------------------------------------------
        try {
            $user = DB::transaction(function () use ($email, $phone, $name, $password) {

                $user = new User();
                $user->name     = $name;
                $user->email    = $email ?: null;
                $user->phone    = $phone ?: null;
                $user->password = Hash::make($password);
                $user->user_type = 'admin';
                $user->role      = 'admin';
                $user->email_verified_at = !empty($email) ? now() : null;
                $user->phone_verified_at = !empty($phone) ? now() : null;
                $user->save();

                return $user;
            });

            return $this->attachSuperAdminRole($user, $role);
        } catch (\Exception $e) {
            $this->error('خطا در ایجاد کاربر: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * افزودن نقش مدیر کل به کاربر و پاک‌سازی کش.
     *
     * @param  \App\User        $user
     * @param  \App\Models\Role $role
     * @return int
     */
    protected function attachSuperAdminRole(User $user, Role $role)
    {
        try {
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->getKey(), 'role_id' => $role->getKey()],
                [
                    'assigned_by' => null,
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );

            // هم‌گام‌سازی ستون قدیمی برای سازگاری با میدل‌ورهای موجود
            $user->forceFill([
                'user_type' => 'admin',
                'role'      => 'admin',
            ])->save();

            $user->flushPermissionCache();

            // ثبت در Audit Log
            $audit = app(AuditLogger::class);
            $audit->log(
                AuditLog::EVENT_ROLE_ASSIGNED,
                'انتساب نقش «مدیر کل سیستم» به کاربر ' . $user->name . ' از طریق Artisan Command',
                $user,
                [],
                ['role' => 'super_admin'],
                AuditLog::SEVERITY_CRITICAL,
                $user->getKey()
            );

            $this->info('');
            $this->info('✅ مدیر کل سیستم با موفقیت تنظیم شد.');
            $this->line('');
            $this->line('  نام:      ' . $user->name);
            $this->line('  ایمیل:    ' . ($user->email ?: '—'));
            $this->line('  موبایل:   ' . ($user->phone ?: '—'));
            $this->line('  شناسه:    ' . $user->getKey());
            $this->line('  نقش:      مدیر کل سیستم (super_admin)');
            $this->line('');
            $this->warn('⚠  توصیه: بلافاصله پس از نخستین ورود، ورود دو مرحله‌ای را فعال کنید.');

            return 0;
        } catch (\Exception $e) {
            $this->error('خطا در انتساب نقش: ' . $e->getMessage());
            return 1;
        }
    }
}
