<?php

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ══════════════════════════════════════════════════════════════════
// [امنیت — محدودسازی نرخ درخواست] محدودسازی نرخ روی مسیرهای OTP
//
// ── چرا لازم است؟ ────────────────────────────────────────────────
// این مسیرها سه ویژگی دارند که آن‌ها را هدف حمله می‌کند:
//   ۱) نیازی به ورود ندارند
//   ۲) هر درخواست یک پیامک ارسال می‌کند (هزینه واقعی برای کسب‌وکار)
//   ۳) کد تأیید کوتاه است و بدون محدودیت، حدس‌زدن آن عملی می‌شود
//
// بدون محدودسازی نرخ، مهاجم می‌تواند:
//   • با ارسال پیاپی، اعتبار پنل پیامک را بسوزاند (SMS bombing)
//   • کد تأیید کاربر دیگری را با آزمون و خطا حدس بزند
//
// ── انتخاب اعداد ─────────────────────────────────────────────────
// محدودیت‌ها سخاوتمندانه‌اند تا کاربر واقعی اذیت نشود، اما اسکریپت
// خودکار را در چند ثانیه متوقف می‌کنند. کلید محدودسازی در لاراول
// «آدرس IP + نام مسیر» است.
// ══════════════════════════════════════════════════════════════════

// ورود با OTP — ارسال کد (پرهزینه‌ترین: هر درخواست یک پیامک)
Route::get('/users/login/otp', 'OTPController@otp_login')
    ->name('otp.login')
    ->middleware('throttle:10,1');

Route::post('/users/login/otp', 'OTPController@login')
    ->middleware('throttle:5,1');

// تأیید شماره موبایل
Route::get('/verification', 'OTPVerificationController@verification')
    ->name('verification')
    ->middleware('throttle:10,1');

Route::post('/verification', 'OTPVerificationController@verify_phone')
    ->name('verification.submit')
    ->middleware('throttle:10,1');

// ارسال دوباره کد — هر درخواست یک پیامک جدید می‌فرستد
Route::get('/verification/phone/code/resend', 'OTPVerificationController@resend_verification_code')
    ->name('verification.phone.resend')
    ->middleware('throttle:3,1');

// بازیابی رمز عبور با موبایل
Route::get('/password/phone/reset', 'OTPVerificationController@show_reset_password_form')
    ->name('password.phone.form')
    ->middleware('throttle:10,1');

Route::post('/password/reset/submit', 'OTPVerificationController@reset_password_with_code')
    ->name('password.update.phone')
    ->middleware('throttle:5,1');

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::get('/otp-configuration', 'OTPController@configure_index')->name('otp.configconfiguration');
    Route::get('/otp-credentials-configuration', 'OTPController@credentials_index')->name('otp_credentials.index');
	Route::post('/otp-configuration/update/activation', 'OTPController@updateActivationSettings')->name('otp_configurations.update.activation');
	Route::post('/otp-credentials-update', 'OTPController@update_credentials')->name('update_credentials');

    // Messaging is optional; do not register these routes if the addon is absent.
    if (class_exists('App\\Http\\Controllers\\SmsController')) {
        Route::get('/sms', 'SmsController@index')->name('sms.index');
        Route::post('/sms-send', 'SmsController@send')->name('sms.send');
    }
});
