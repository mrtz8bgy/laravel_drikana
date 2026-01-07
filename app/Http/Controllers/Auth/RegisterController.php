<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\BusinessSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            // ثبت‌نام با ایمیل
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();

            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                flash(__('Registration successfull.'))->success();
            } else {
                $user->sendEmailVerificationNotification();
                flash(__('Registration successfull. Please verify your email.'))->success();
            }
        } else {
            // ثبت‌نام با شماره همراه
             // تولید کد OTP
            $verification_code = $this->generateOTPCode();
            
            $user = User::create([
                'name' => $data['name'],
                'phone' => convertNumToEng($data['phone']),
                'password' => Hash::make($data['password']),
                'verification_code' => $verification_code,
                'phone_verified_at' => null
            ]);

            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();

            // ارسال کد OTP
            $this->sendOTPCode($user);
            
            // لاگ کردن کد در محیط توسعه
            if (config('app.env') != 'production') {
                \Log::info("کد OTP برای ثبت‌نام: {$verification_code} - شماره: {$user->phone}");
            }
            
            flash(__('Registration successfull. Please verify your phone.'))->success();
        }

        // مدیریت کد معرف
        if (Cookie::has('referral_code')) {
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if ($referred_by_user != null) {
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        return $user;
    }

    /**
     * تولید کد OTP
     */
    private function generateOTPCode()
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
     * ارسال کد OTP
     */
    private function sendOTPCode($user)
    {
        // فقط در محیط production واقعاً SMS ارسال کن
        if (config('app.env') != 'production') {
            \Log::info("در حالت توسعه - کد برای {$user->phone}: {$user->verification_code}");
            return true;
        }
        
        // در حالت production از OTPVerificationController استفاده کن
        $otpController = new OTPVerificationController;
        return $otpController->send_code($user);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // اعتبارسنجی ایمیل یا شماره
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->first() != null) {
                flash('آدرس ایمیل شما قبلا در سیستم ثبت شده است.')->error();
                return back()->withInput();
            }
        } else {
            $phone = convertNumToEng($request->phone);
            
            if (User::where('phone', $phone)->first() != null) {
                flash('شماره موبایل شما قبلا در سیستم ثبت شده است.')->error();
                return back()->withInput();
            }

            if (!preg_match('/^((0)(9){1}[0-9]{9})+$/', $phone)) {
                flash('شماره موبایل وارد شده نامعتبر است.')->error();
                return back()->withInput();
            }
        }

        // اعتبارسنجی داده‌ها
        $this->validator($request->all())->validate();

        // ایجاد کاربر
        event(new Registered($user = $this->create($request->all())));

        // لاگین کاربر
        $this->guard()->login($user);

        // هدایت به صفحه مناسب
        return $this->registered($request, $user);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if ($user->email == null) {
            // کاربر با شماره همراه ثبت‌نام کرده - هدایت به صفحه تأیید کد
            return redirect()->route('verification');
        } else {
            // کاربر با ایمیل ثبت‌نام کرده
            return redirect()->route('home');
        }
    }
}