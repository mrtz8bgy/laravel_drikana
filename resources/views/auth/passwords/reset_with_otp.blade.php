@extends('layouts.blank')

@section('content')

{{--
    ─────────────────────────────────────────────────────────────
    فرم بازنشانی رمز عبور با کد پیامکی (OTP)
    ─────────────────────────────────────────────────────────────

    این صفحه در مسیر «رمز عبور را فراموش کرده‌ام» نمایش داده می‌شود.

    نکات امنیتی:
      • توکن CSRF در فرم درج شده است.
      • ورودی‌ها با maxlength و pattern محدود شده‌اند.
      • پیام خطا در سرور یکسان است (چه شماره وجود داشته باشد چه نه)
        تا از افشای وجود کاربران جلوگیری شود (User Enumeration).
      • کد یک‌بارمصرف پس از استفاده باطل می‌شود.
--}}

<div class="container u-padding-40px-15px">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('بازنشانی رمز عبور با کد پیامکی') }}</h3>
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

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update.phone') }}">
                        @csrf

                        <div class="form-group">
                            <label for="phone">{{ __('شماره همراه') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   class="form-control"
                                   maxlength="11"
                                   pattern="09[0-9]{9}"
                                   inputmode="numeric"
                                   placeholder="09xxxxxxxxx"
                                   value="{{ old('phone', $phone) }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="code">{{ __('کد تأیید ۶ رقمی') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   class="form-control"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   required>
                            <small class="text-muted">
                                {{ __('اگر کد را دریافت نکرده‌اید، از صفحه ورود گزینه «ارسال مجدد کد» را بزنید.') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('رمز عبور جدید') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                            <small class="text-muted">{{ __('حداقل ۸ کاراکتر') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('تکرار رمز عبور جدید') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('تغییر رمز عبور') }}
                        </button>

                        <br>
                        <a href="{{ route('login') }}" class="btn btn-link btn-block">
                            {{ __('بازگشت به صفحه ورود') }}
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
