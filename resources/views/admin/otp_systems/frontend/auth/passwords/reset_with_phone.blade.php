@extends('layouts.blank')

@section('content')

{{--
    ─────────────────────────────────────────────────────────────
    مرحله دوم بازنشانی رمز با شماره همراه
    ─────────────────────────────────────────────────────────────

    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    Auth\ForgotPasswordController@sendResetLinkEmail (ثبت‌شده از طریق
    Auth::routes() با نام password.email) آن را برمی‌گرداند. بنابراین
    هر کاربری که در فرم «فراموشی رمز» شماره همراه وارد می‌کرد، با
    خطای 500 مواجه می‌شد.

    مسیر فایل عمداً همان مسیر مورد انتظار کنترلر است
    (`otp_systems.frontend.auth.passwords.reset_with_phone`) و در
    پوشه admin/otp_systems قرار دارد، نه پوشه تم frontend.

    نکات امنیتی:
      • فرم با متد POST و توکن CSRF ارسال می‌شود.
      • ورودی‌ها با pattern و maxlength محدود شده‌اند.
      • هیچ اطلاعاتی درباره وجود/عدم وجود شماره افشا نمی‌شود.
--}}

<div class="container u-padding-40px-15px">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('تأیید شماره همراه و تغییر رمز') }}</h3>
                </div>
                <div class="panel-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="u-margin-0-padding-right-20px">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="alert alert-info">
                        {{ __('کد تأیید به شماره همراه شما ارسال شد. کد و رمز عبور جدید را وارد کنید.') }}
                    </div>

                    <form method="POST" action="{{ route('password.update.phone') }}">
                        @csrf

                        <div class="form-group">
                            <label for="rp_phone">{{ __('شماره همراه') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="rp_phone"
                                   name="phone"
                                   class="form-control"
                                   maxlength="11"
                                   pattern="09[0-9]{9}"
                                   inputmode="numeric"
                                   placeholder="09xxxxxxxxx"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="rp_code">{{ __('کد تأیید ۶ رقمی') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="rp_code"
                                   name="code"
                                   class="form-control"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="rp_password">{{ __('رمز عبور جدید') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   id="rp_password"
                                   name="password"
                                   class="form-control"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                            <small class="text-muted">{{ __('حداقل ۸ کاراکتر') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="rp_password_confirmation">{{ __('تکرار رمز عبور جدید') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   id="rp_password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('تغییر رمز عبور') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
