<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        // ── محدودسازی نرخ [H-03 / ردیف ۷ موارد باز] ──────────────────
        //
        // `POST /password/email` برای هر درخواست یک ایمیل واقعی می‌فرستد.
        // بدون محدودسازی:
        //   • مهاجم می‌تواند با اسکریپت ساده، صندوق ایمیل یک کاربر را پر
        //     کند (Email Bombing) و صندوق فرستنده را بدنام کند.
        //   • اختلاف زمان پاسخ، امکان «شمارش کاربران» (User Enumeration)
        //     را فراهم می‌کند: برای ایمیل موجود، درخواست سنگین‌تر و کندتر
        //     است.
        //
        // عدد ۵ در دقیقه: کاربر واقعی بیش از یک بار درخواست نمی‌کند،
        // اما پنج فرصت برای اشتباه تایپی باقی می‌ماند.
        $this->middleware('throttle:5,1')->only('sendResetLinkEmail');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $this->validateEmail($request);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($request, $response)
                        : $this->sendResetLinkFailedResponse($request, $response);
        }
        else{
            $user = User::where('phone', $request->email)->first();
            if ($user != null) {
                // [امنیت] کد بازیابی رمز عبور با random_int() ساخته
                // می‌شود؛ rand() قابل پیش‌بینی است و برای کد امنیتی
                // مناسب نیست.
                $user->verification_code = random_int(100000, 999999);
                $user->save();
                sendSMS($user->phone, config('app.name'), $user->verification_code.' is your verification code');
                return view('admin.otp_systems.frontend.auth.passwords.reset_with_phone');
            }
            else {
                flash('No account exists with this phone number')->error();
                return back();
            }
        }
    }
}
