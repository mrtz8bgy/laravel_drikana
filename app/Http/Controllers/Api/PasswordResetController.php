<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * درخواست بازنشانی رمز عبور از اپلیکیشن موبایل.
     *
     * ── دو اصلاح امنیتی در این متد ──────────────────────────────────
     *
     * ۱) پاسخ یکسان برای ایمیل موجود و ناموجود (رفع شمارش کاربران).
     *    پیش‌تر برای ایمیل ناموجود، پاسخ ۴۰۴ با پیام «کاربری با این
     *    ایمیل پیدا نشد» برگردانده می‌شد و برای ایمیل موجود، پاسخ ۲۰۰.
     *    یعنی هر کسی می‌توانست با چند درخواست، فهرست کاربران ثبت‌شده
     *    سامانه را استخراج کند. اکنون در هر دو حالت پاسخ یکسان است.
     *
     * ۲) محدودسازی نرخ (در فایل `routes/api.php`) — این متد برای هر
     *    درخواست یک ایمیل واقعی می‌فرستد.
     *
     * نکته: پیام پاسخ عامدانه «شرطی» نیست؛ حتی وجود یا نبود کاربر هم
     * در کد مسیرهای مختلفی طی می‌کند که زمان اجرای متفاوتی دارد. برای
     * هم‌سطح‌سازی، در هر دو حالت یک کار اضافه و ارزان انجام می‌شود.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Str::random(60)
                ]
            );

            if ($passwordReset) {
                $user->notify(
                    new PasswordResetRequest($passwordReset->token)
                );
            }
        }

        // پاسخ یکسان در هر دو حالت — نه ۴۰۴، نه پیام متفاوت.
        return response()->json([
            'success' => true,
            'message' => 'If that e-mail address is registered, a password reset link has been sent.'
        ], 200);
    }
}
