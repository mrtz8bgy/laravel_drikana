<?php

namespace App\Http\Controllers;

use App\OtpConfiguration;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OTPController extends Controller
{
    public function credentials_index()
    {
        return view('admin.otp_systems.configurations.index');
    }

    public function configure_index(Request $request)
    {
        return view('admin.otp_systems.configurations.activation');
    }

    public function updateActivationSettings(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'required|string',
        ]);

        $model = OtpConfiguration::where('type', $request->input('type'))->update([
            'value' => $request->input('value'),
        ]);

        if ($model) {
            return '1';
        }

        return '0';
    }

    public function update_credentials(Request $request)
    {
        $request->validate([
            'types' => 'required|array',
        ]);

        foreach ($request->input('types') as $item) {
            putPermanentEnv($item, $request->input($item));
        }

        return redirect()->back();
    }

    public function otp_login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('frontend.user_login_otp');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $request->validate([
            'phone' => 'required|string',
            'step' => 'required|integer',
        ]);

        $step = $request->input('step');

        if ($step == '2') {
            $request->validate([
                'code' => 'required|string',
            ]);
        }

        $phone = convertNumToEng($request->input('phone'));

        if (!preg_match('/^((0)(9){1}[0-9]{9})+$/', $phone)) {
            return redirect()->back()->withErrors(['phone' => 'Ø´Ù…Ø§Ø±Ù‡ Ù…ÙˆØ¨Ø§ÛŒÙ„ ÙˆØ§Ø±Ø¯ Ø´Ø¯Ù‡ Ù†Ø§Ù…Ø¹ØªØ¨Ø± Ø§Ø³Øª.']);
        }

        if ($step == '1') {
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return redirect()->back()->withErrors(['phone' => 'Ú©Ø§Ø±Ø¨Ø±ÛŒ Ø¨Ø§ Ø§ÛŒÙ† Ø´Ù…Ø§Ø±Ù‡ Ø¯Ø± Ø³ÛŒØ³ØªÙ… ÛŒØ§ÙØª Ù†Ø´Ø¯.']);
            }

            $user->update(['verification_code' => rand(100000, 999999)]);

            $otpController = new OTPVerificationController();
            $otpController->send_code($user);

            \session()->flash('otp_step', 2);

            return redirect()->back()->withInput([
                'phone' => $phone,
            ]);
        } elseif ($step == '2') {
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return redirect()->back()->withErrors(['phone' => 'Ú©Ø§Ø±Ø¨Ø±ÛŒ Ø¨Ø§ Ø§ÛŒÙ† Ø´Ù…Ø§Ø±Ù‡ Ø¯Ø± Ø³ÛŒØ³ØªÙ… ÛŒØ§ÙØª Ù†Ø´Ø¯.']);
            }

            $code = convertNumToEng($request->input('code'));

            if (strlen((string)$code) != 6) {
                return redirect()->back()->withErrors(['code' => 'Ú©Ø¯ ÙˆØ§Ø±Ø¯ Ø´Ø¯Ù‡ Ù†Ø§Ù…Ø¹ØªØ¨Ø± Ø§Ø³Øª.']);
            }

            if ($user->verification_code != $code) {
                return redirect()->back()->withErrors(['code' => 'Ú©Ø¯ ÙˆØ§Ø±Ø¯ Ø´Ø¯Ù‡ Ù†Ø§Ù…Ø¹ØªØ¨Ø± Ø§Ø³Øª.']);
            }

            Auth::login($user);

            return redirect()->route('home');
        }
    }
}



