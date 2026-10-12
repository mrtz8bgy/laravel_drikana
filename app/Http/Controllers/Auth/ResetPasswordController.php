<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/';

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
        // `POST /password/reset` توکن بازیابی را می‌پذیرد. بدون
        // محدودسازی، آزمودن انبوه توکن‌ها (Token Brute Force) ممکن
        // است. عدد ۱۰ در دقیقه، توکن نشست را برای کاربر واقعی کافی
        // نگه می‌دارد و فضای آزمون مهاجم را می‌بندد.
        $this->middleware('throttle:10,1')->only('reset');
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
        {
            return redirect()->route('admin.dashboard')
                            ->with('status', trans($response));
        }

        return redirect()->route('home')
                            ->with('status', trans($response));
    }
}
