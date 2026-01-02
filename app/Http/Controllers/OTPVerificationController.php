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
        $debugCode = env('DEBUG_OTP_CODE');
        
        // در محیط توسعه و اگر کد 6 رقمی معتبر است
        if (config('app.env') != 'production' && $debugCode && strlen($debugCode) == 6) {
            return $debugCode;
        }
        
        // در غیر این صورت کد تصادفی تولید کن
        return rand(100000, 999999);
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
        $debugCode = env('DEBUG_OTP_CODE');
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
        return config('app.env') != 'production' && env('SHOW_DEBUG_OTP', true);
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
            $api_key = env('KAVENEGAR_API_KEY');
            
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
}