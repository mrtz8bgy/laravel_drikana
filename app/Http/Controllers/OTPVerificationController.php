<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SoapClient;

class OTPVerificationController extends Controller
{
    public function verification(Request $request)
    {
        return view('otp_systems.frontend.user_verification');
    }

    public function verify_phone(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('home');
        }

        $request->validate([
            'verification_code' => 'required|digits:6'
        ]);

        $user = Auth::user();

        if ($user->verification_code !== $request->input('verification_code')) {
            flash('کد تایید وارد شده نامعتبر است.');
            return back();
        }

        $user->markPhoneAsVerified();

        return redirect()->route('home');
    }

    public function send_code(User $user)
    {
        sendOTPSMS($user->phone, $user->verification_code);

        return true;
    }
}
