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
                            {--password-file= : فایل حاوی رمز عبور (برای اجرای غیرتعاملی)}
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
     * خواندن رمز عبور از منبع غیرتعاملی (فایل یا متغیر محیطی).
     *
     * ── چرا اولویت به فایل است؟ ─────────────────────────────────────
     * متغیر محیطی در فهرست فرایندهای سیستم (`ps e`, `top`،
     * `/proc/<pid>/environ`) برای همان کاربر و معمولاً root قابل دیدن
     * است و می‌تواند در log استقرار هم بیفتد. فایل با مجوز ۶۰۰ این
     * مشکل را ندارد.
     *
     * @return array آرایه‌ای با کلیدهای «ok» (بولی) و «message» (رشته)
     *               و در صورت موفقیت «password»
     */
    protected function resolvePasswordFromSource()
    {
        // ---------------------------------------------------
        // ۱) فایل رمز عبور — امن‌ترین راه غیرتعاملی
        // ---------------------------------------------------
        $file = $this->option('password-file') ?: env('DRIKANA_ADMIN_PASSWORD_FILE');

        if (!empty($file)) {
            $path = (string) $file;

            if (!is_file($path)) {
                return ['ok' => false, 'message' => 'فایل رمز عبور یافت نشد: ' . $path];
            }

            if (!is_readable($path)) {
                return ['ok' => false, 'message' => 'فایل رمز عبور قابل خواندن نیست: ' . $path];
            }

            // هشدار امنیتی: فایل رمز عبور نباید برای دیگران خواندنی باشد
            $permissions = @fileperms($path);
            if ($permissions !== false && ($permissions & 0077) !== 0) {
                $this->warn('⚠ فایل رمز عبور برای گروه/دیگران خواندنی است. مجوز 600 توصیه می‌شود:');
                $this->line('    chmod 600 ' . $path);
            }

            // فقط خط اول خوانده می‌شود تا کاراکتر انتهای فایل مشکل نسازد
            $contents = (string) file_get_contents($path);
            $line = strtok($contents, "\r\n");

            if ($line === false || trim($line) === '') {
                return ['ok' => false, 'message' => 'فایل رمز عبور خالی است: ' . $path];
            }

            return ['ok' => true, 'password' => trim($line)];
        }

        // ---------------------------------------------------
        // ۲) متغیر محیطی — راحت ولی کم‌امن‌تر
        // ---------------------------------------------------
        $envPassword = env('DRIKANA_ADMIN_PASSWORD');

        if (!empty($envPassword)) {
            $this->warn('⚠ رمز عبور از متغیر محیطی خوانده شد. این روش در فهرست فرایندها');
            $this->warn('  قابل دیدن است و برای اتوماسیون موقت مناسب است، نه استقرار همیشگی.');

            return ['ok' => true, 'password' => (string) $envPassword];
        }

        // ---------------------------------------------------
        // ۳) هیچ منبعی تنظیم نشده → ورودی تعاملی
        // ---------------------------------------------------
        return ['ok' => false, 'message' => null];
    }

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
        // ایمیل: گزینه خط فرمان → متغیر محیطی → پرسش تعاملی
        $email = $this->option('email') ?: env('DRIKANA_ADMIN_EMAIL');

        if (empty($email) && $this->input->isInteractive()) {
            $email = $this->ask('ایمیل مدیر کل');
        }

        $phone = $this->option('phone') ?: env('DRIKANA_ADMIN_PHONE');
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
        // -------------------------------------------------------
        // منبع رمز عبور — به ترتیب اولویت:
        //   ۱) --password-file (یا DRIKANA_ADMIN_PASSWORD_FILE)
        //      امن‌ترین راه غیرتعاملی: رمز در فهرست فرایندها دیده نمی‌شود.
        //   ۲) DRIKANA_ADMIN_PASSWORD
        //      راحت ولی کم‌امن‌تر: متغیر محیطی می‌تواند در `ps e` و
        //      فایل‌های log دیده شود. تنها برای اتوماسیون کنترل‌شده.
        //   ۳) ورودی تعاملی (پیش‌فرض)
        // -------------------------------------------------------
        $source = $this->resolvePasswordFromSource();

        // منبع تنظیم شده اما خطا داشته → توقف، نه بازگشت به ورودی تعاملی.
        // اگر اینجا به پرسش تعاملی برمی‌گشتیم، اسکریپت‌های خودکار بی‌صدا
        // روی یک رمز متفاوت متوقف می‌شدند.
        if ($source['ok'] === false && !empty($source['message'])) {
            $this->error($source['message']);

            return 1;
        }

        $passwordFromSource = $source['ok'] ? $source['password'] : null;

        if ($passwordFromSource !== null) {
            $password = $passwordFromSource;
            $confirm  = $passwordFromSource;
            $this->warn('رمز عبور از منبع غیرتعاملی خوانده شد — پرسش تأیید رد می‌شود.');
        } else {
            $password = $this->secret('رمز عبور (حداقل ۱۲ کاراکتر — ورودی نمایش داده نمی‌شود)');
        }

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

        if ($passwordFromSource === null) {
            $confirm = $this->secret('تکرار رمز عبور');
        }

        if ($password !== $confirm) {
            $this->error('رمز عبور و تکرار آن یکسان نیستند.');
            return 1;
        }

        // -------------------------------------------------------
        // ۶) تأیید نهایی در محیط production
        // -------------------------------------------------------
        if (app()->environment('production') && !$this->option('force') && $this->input->isInteractive()) {
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
            // ── یادآوری‌های امنیتی ────────────────────────────────────
            //
            // توجه: این نسخه «ورود دو مرحله‌ای» (2FA) ندارد؛ بنابراین
            // پیام تازه‌ای که کاربر را به فعال‌کردن قابلیتی ناموجود
            // راهنمایی کند، نوشته نمی‌شود (بازبینی [L-14] از گزارش
            // امنیتی). در عوض، سه اقدام واقعی و قابل انجام یادآوری
            // می‌شود.
            $this->warn('⚠  این حساب دسترسی کامل به تمام بخش‌های سامانه دارد.');
            $this->line('   • رمز عبور را در جای امن نگه دارید و آن را با کسی به اشتراک نگذارید.');
            $this->line('   • سامانه را فقط روی HTTPS منتشر کنید (رمز در غیر این صورت لو می‌رود).');
            $this->line('   • ورودها و تغییرات دسترسی را در /admin/audit-logs پیگیری کنید.');
            $this->line('');
            $this->line('   نکته: ورود دو مرحله‌ای در این نسخه پیاده‌سازی نشده است');
            $this->line('   (مورد باز در docs/security-audit.md).');

            return 0;
        } catch (\Exception $e) {
            $this->error('خطا در انتساب نقش: ' . $e->getMessage());
            return 1;
        }
    }
}
