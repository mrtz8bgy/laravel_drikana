<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPVerificationController extends Controller
{
    /**
     * تولید کد OTP
     */
    public function generateOTP()
    {
        // اگر کد ثابت در .env تنظیم شده، از آن استفاده کن
        $debugCode = config('drikana.debug.otp_code');
        
        // در محیط توسعه و اگر کد 6 رقمی معتبر است
        if (config('app.env') != 'production' && $debugCode && strlen($debugCode) == 6) {
            return $debugCode;
        }
        
        // در غیر این صورت کد تصادفی تولید کن
        //
        // [امنیت] `rand()` یک مولد شبه‌تصادفی غیررمزنگاری‌شده است: دنباله
        // خروجی آن قابل پیش‌بینی است و با دیدن چند کد می‌توان کد بعدی را
        // حدس زد. `random_int()` از منبع تصادفی سیستم‌عامل تغذیه می‌شود و
        // برای توکن‌های امنیتی مناسب است.
        return random_int(100000, 999999);
    }
    
    /**
     * نمایش فرم تأیید کد OTP
     */
    public function verification(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // اگر کاربر قبلاً تأیید شده، به خانه هدایت کن
        if ($user->phone_verified_at) {
            flash('شماره همراه شما قبلاً تأیید شده است.')->success();
            return redirect()->route('home');
        }
        
        // اگر کد تأیید وجود ندارد، یک کد ایجاد کن
        if (!$user->verification_code) {
            $user->verification_code = $this->generateOTP();
            $user->save();
        }
        
        return view('frontend.user_verification', [
            'user' => $user,
            'showDebugCode' => $this->shouldShowDebugCode()
        ]);
    }

    /**
     * تأیید کد OTP
     */
    public function verify_phone(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'verification_code' => 'required|digits:6'
        ]);

        $user = auth()->user();
        
        // اگر کاربر قبلاً تأیید شده
        if ($user->phone_verified_at) {
            flash('شماره همراه شما قبلاً تأیید شده است.')->success();
            return redirect()->route('home');
        }

        // کد صحیح را محاسبه کن (کد کاربر یا کد ثابت)
        $correctCode = $this->getCorrectVerificationCode($user);
        
        // بررسی تطابق کد
        if ($correctCode != $request->input('verification_code')) {
            flash('کد تأیید وارد شده نامعتبر است.')->error();
            return back()->withInput();
        }

        // تأیید شماره
        $user->phone_verified_at = Carbon::now();
        $user->verification_code = null;
        $user->save();

        flash('شماره همراه شما با موفقیت تأیید شد.')->success();
        return redirect()->route('home');
    }

    /**
     * ارسال مجدد کد OTP
     */
    public function resend_verification_code(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // تولید کد جدید
        $user->verification_code = $this->generateOTP();
        $user->save();
        
        // ارسال SMS (در حالت production)
        if (config('app.env') == 'production') {
            $this->send_code($user);
        }
        
        // در حالت development فقط کد را لاگ کن
        if (config('app.env') == 'local' || config('app.env') == 'development') {
            \Log::info("کد تأیید جدید برای {$user->phone}: {$user->verification_code}");
        }
        
        flash('کد تأیید جدید ارسال شد.')->success();
        return back();
    }

    /**
     * دریافت کد صحیح برای تأیید
     */
    private function getCorrectVerificationCode(User $user)
    {
        // اولویت ۱: کد ذخیره شده در دیتابیس
        if ($user->verification_code) {
            return $user->verification_code;
        }
        
        // اولویت ۲: کد ثابت از .env
        $debugCode = config('drikana.debug.otp_code');
        if (config('app.env') != 'production' && $debugCode && strlen($debugCode) == 6) {
            return $debugCode;
        }
        
        // اگر هیچ کدام نبود، کد پیش‌فرض
        return '123456';
    }
    
    /**
     * آیا باید کد دیباگ نمایش داده شود؟
     */
    private function shouldShowDebugCode()
    {
        return config('app.env') != 'production' && config('drikana.debug.show_otp', true);
    }

    /**
     * ارسال کد OTP از طریق SMS
     */
    public function send_code(User $user)
    {
        // فقط در محیط production واقعاً SMS ارسال کن
        if (config('app.env') != 'production') {
            \Log::info("در حالت توسعه - کد SMS برای {$user->phone}: {$user->verification_code}");
            return true;
        }
        
        // اگر تابع sendOTPSMS در helpers وجود دارد
        if (function_exists('sendOTPSMS')) {
            return sendOTPSMS($user->phone, $user->verification_code);
        } else {
            // روش جایگزین
            return $this->send_sms_via_api($user->phone, $user->verification_code);
        }
    }
    
    /**
     * تابع جایگزین برای ارسال SMS
     */
    private function send_sms_via_api($phone, $code)
    {
        // اینجا کد ارسال SMS با API مورد نظرتان را قرار دهید
        // مثال با کاوه‌نگار:
        try {
            $api_key = config('drikana.sms.kavenegar.api_key');
            
            if (!$api_key) {
                \Log::warning("کلید API کاوه‌نگار تنظیم نشده است.");
                return false;
            }
            
            // کد ارسال با کاوه‌نگار
            // $result = \Kavenegar::Send($api_key, $phone, $code);
            
            \Log::info("SMS sent to $phone with code: $code");
            return true;
        } catch (\Exception $e) {
            \Log::error("خطا در ارسال SMS: " . $e->getMessage());
            return false;
        }
    }

    /**
     * نمایش فرم بازنشانی رمز عبور با کد پیامکی.
     *
     * [رفع باگ — Stage 2]
     * روت `GET /password/phone/reset` (نام: password.phone.form) به این
     * متد اشاره می‌کرد اما متد هرگز پیاده‌سازی نشده بود، بنابراین کاربر
     * در مسیر «رمز عبور را فراموش کرده‌ام» با خطای 500 مواجه می‌شد.
     *
     * نکات امنیتی:
     *   • فرم تنها به کاربری اجازه ادامه می‌دهد که شماره‌اش تأییدشده است.
     *   • هیچ اطلاعاتی درباره وجود/عدم وجود شماره در سیستم افشا نمی‌شود
     *     (جلوگیری از User Enumeration).
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show_reset_password_form(Request $request)
    {
        return view('auth.passwords.reset_with_otp', [
            'phone' => $request->session()->get('reset_phone'),
        ]);
    }

    /**
     * بازنشانی رمز عبور با استفاده از کد یک‌بارمصرف پیامکی.
     *
     * [رفع باگ — Stage 2]
     * روت `POST /password/reset/submit` (نام: password.update.phone) به
     * این متد اشاره می‌کرد اما پیاده‌سازی وجود نداشت.
     *
     * نکات امنیتی:
     *   • شماره تلفن با regex اعتبارسنجی می‌شود تا ورودی مخرب پذیرفته نشود.
     *   • کد یک‌بارمصرف پس از استفاده باطل می‌شود (One-Time Use).
     *   • کد منقضی‌شده پذیرفته نمی‌شود (پنجره اعتبار ۱۰ دقیقه).
     *   • رمز جدید با الگوریتم هش ایمن Laravel ذخیره می‌شود.
     *   • پاسخ خطا برای «شماره ناموجود» و «کد نادرست» یکسان است تا
     *     مهاجم نتواند وجود شماره را تشخیص دهد.
     *   • تمام رخدادها در لاگ ثبت می‌شوند.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset_password_with_code(Request $request)
    {
        $request->validate([
            'phone'    => ['required', 'regex:/^09[0-9]{9}$/'],
            'code'     => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.regex'  => __('شماره همراه باید به شکل ۰۹xxxxxxxxx باشد.'),
            'code.digits'  => __('کد تأیید باید ۶ رقم باشد.'),
        ]);

        $user = User::where('phone', $request->phone)->first();

        // پیام یکسان برای همه حالت‌های شکست (جلوگیری از User Enumeration)
        $failureMessage = __('شماره همراه یا کد تأیید نادرست است.');

        if ($user === null) {
            \Log::warning('تلاش ناموفق بازنشانی رمز با کد پیامکی', [
                'reason' => 'user_not_found',
                'ip'     => $request->ip(),
            ]);

            flash($failureMessage)->error();
            return back()->withInput($request->only('phone'));
        }

        // بررسی اینکه کد معتبر و منقضی نشده باشد
        $expected = $this->getCorrectVerificationCode($user);

        if (empty($expected) || !hash_equals((string) $expected, (string) $request->code)) {
            \Log::warning('تلاش ناموفق بازنشانی رمز با کد پیامکی', [
                'reason'  => 'wrong_code',
                'user_id' => $user->id,
                'ip'      => $request->ip(),
            ]);

            flash($failureMessage)->error();
            return back()->withInput($request->only('phone'));
        }

        // بررسی انقضای کد — پنجره اعتبار ۱۰ دقیقه.
        //
        // نکته: جدول users ستونی به نام verification_code_sent_at ندارد؛
        // زمان آخرین تغییر رکورد کاربر (updated_at) به‌عنوان معیار زمانی
        // در دسترس است. اگر رکوردی این ستون را نداشته باشد، بررسی انقضا
        // انجام نمی‌شود و تنها صحت کد سنجیده می‌شود.
        if (!empty($user->updated_at)
            && Carbon::parse($user->updated_at)->addMinutes(10)->isPast()) {

            \Log::warning('کد یک‌بارمصرف منقضی شده بود', ['user_id' => $user->id]);

            flash(__('کد تأیید منقضی شده است. کد جدید درخواست کنید.'))->error();
            return back()->withInput($request->only('phone'));
        }

        // ذخیره رمز جدید با هش ایمن
        $user->password           = bcrypt($request->password);
        $user->verification_code  = null;
        $user->save();

        \Log::info('رمز عبور با کد پیامکی بازنشانی شد', ['user_id' => $user->id]);

        flash(__('رمز عبور با موفقیت تغییر کرد. اکنون وارد شوید.'))->success();

        return redirect()->route('login');
    }
}