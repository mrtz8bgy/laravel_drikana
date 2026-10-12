@extends('layouts.app')

@section('content')

{{--
    ─────────────────────────────────────────────────────────────────
    فرم ساخت کاربر ادمین جدید
    ─────────────────────────────────────────────────────────────────

    متغیرهای ورودی:
      $roles                : نقش‌هایی که کاربر جاری اجازه اعطای آن‌ها را دارد
      $canCreateSuperAdmin  : آیا کاربر جاری می‌تواند حساب «مدیر کل» بسازد؟

    ── نکات امنیتی ──────────────────────────────────────────────────
      • فهرست نقش‌ها در کنترلر بر پایه «سطح» کاربر جاری فیلتر می‌شود؛
        نقش بالاتر از سطح خود کاربر اینجا نمایش داده نمی‌شود.
      • گزینه «مدیر کل سیستم» تنها برای مدیر کل نمایش داده می‌شود و در
        کنترلر هم مستقلاً بررسی می‌شود. پنهان‌کردن گزینه در صفحه، امنیت
        نیست — لایه دوم در کنترلر است.
      • رمز عبور به‌صورت «تولید خودکار» پیش‌فرض است تا رمز تکراری و
        ضعیف ساخته نشود. رمز تولیدشده تنها یک بار در صفحه بعد نمایش
        داده می‌شود.
      • این فرم CSRF دارد و داده‌ها با POST ارسال می‌شوند.
--}}

@php
    // مقادیر پیش‌فرض هنگام بازگشت با خطا، همان چیزی است که کاربر زده بود
    $oldAccountType = old('account_type', 'staff');
    $oldPasswordMode = old('password_mode', 'auto');
    $oldRoles = old('roles', []);
@endphp

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('ساخت کاربر ادمین جدید') }}</h3>
        <a href="{{ route('users.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت به فهرست کاربران') }}
        </a>
    </div>
</div>

<br>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="u-margin-0-padding-right-20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- راهنمای سطوح دسترسی --}}
<div class="alert alert-info">
    <i class="demo-pli-information"></i>
    <strong>{{ __('ساخت «کاربر با نقش» تفاوت دارد با ساخت «مدیر کل سیستم»:') }}</strong>
    <ul class="u-margin-10px-0-0-padding-right-20px">
        <li>
            {{ __('کاربر با نقش (پیشنهادشده) تنها به بخش‌هایی دسترسی دارد که نقش‌های انتخابی شما اجازه می‌دهند.') }}
            <br>
            <small>{{ __('مثال: نقش «مدیر محتوا» فقط به مقالات دسترسی می‌دهد و به محصولات، سفارش‌ها و کاربران کاری ندارد.') }}</small>
        </li>
        @if($canCreateSuperAdmin)
            <li>
                {{ __('مدیر کل سیستم به همه بخش‌ها دسترسی دارد و می‌تواند نقش بدهد و بگیرد.') }}
                <br>
                <small class="text-danger">{{ __('این حساب را فقط زمانی بسازید که واقعاً لازم است — هر حساب مدیر کل، یک نقطه نفوذ کامل است.') }}</small>
            </li>
        @endif
    </ul>
</div>

<div class="col-lg-10 col-lg-offset-1">
    <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
        @csrf

        {{-- ================================================= --}}
        {{-- ۱) اطلاعات پایه --}}
        {{-- ================================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('اطلاعات کاربر') }}</h3>
            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label for="name">{{ __('نام و نام خانوادگی') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           maxlength="191"
                           required>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">{{ __('ایمیل') }}</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   maxlength="191"
                                   dir="ltr">
                            <small class="text-muted">
                                {{ __('برای ورود و بازیابی رمز عبور به کار می‌رود.') }}
                            </small>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">{{ __('شماره موبایل') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   maxlength="20"
                                   dir="ltr">
                            <small class="text-muted">
                                {{ __('حداقل یکی از ایمیل یا موبایل الزامی است.') }}
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================================================= --}}
        {{-- ۲) نوع حساب --}}
        {{-- ================================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('نوع حساب و سطح دسترسی') }}</h3>
            </div>

            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-6">
                        <label class="radio-inline">
                            <input type="radio"
                                   name="account_type"
                                   value="staff"
                                   @if($oldAccountType === 'staff') checked @endif>
                            <strong>{{ __('کاربر با نقش') }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ __('دسترسی محدود به نقش‌های انتخابی — پیشنهادشده') }}
                            </small>
                        </label>
                    </div>

                    @if($canCreateSuperAdmin)
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio"
                                       name="account_type"
                                       value="admin"
                                       @if($oldAccountType === 'admin') checked @endif>
                                <strong class="text-danger">{{ __('مدیر کل سیستم') }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ __('دسترسی کامل — برای موارد استثنایی') }}
                                </small>
                            </label>
                        </div>
                    @else
                        {{-- کاربر غیرمجاز: مقدار ثابت ارسال می‌شود و کنترلر هم
                             مستقلاً نوع حساب را بررسی می‌کند. --}}
                        <input type="hidden" name="account_type" value="staff">
                        <div class="col-sm-6">
                            <div class="alert alert-warning u-margin-bottom-0">
                                {{ __('ساخت حساب «مدیر کل سیستم» تنها توسط مدیر کل سیستم امکان‌پذیر است.') }}
                            </div>
                        </div>
                    @endif
                </div>

                <hr>

                <h5>{{ __('نقش‌ها') }}</h5>
                <p class="text-muted">
                    {{ __('یک کاربر می‌تواند چند نقش داشته باشد. مجوزهای نهایی، اجتماع مجوزهای همه نقش‌های او است.') }}
                </p>

                @forelse($roles as $role)
                    @php
                        $isChecked = in_array($role->slug, (array) $oldRoles, true);
                        $isSuper   = ($role->slug === 'super_admin');
                    @endphp

                    <div class="row u-padding-8px-0-border-bottom-1px-solid-eee">
                        <div class="col-sm-8">
                            <strong>{{ $role->name }}</strong>
                            <span class="label label-default">{{ $role->level }}</span>

                            @if($isSuper)
                                <span class="label label-danger u-font-size-10px">
                                    {{ __('حساس') }}
                                </span>
                            @endif

                            @if($role->description)
                                <br>
                                <small class="text-muted">{{ $role->description }}</small>
                            @endif
                        </div>

                        <div class="col-sm-4 text-left">
                            <label class="switch">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->slug }}"
                                       @if($isChecked) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">
                        {{ __('هیچ نقشی برای انتساب در دسترس نیست. ابتدا از بخش «نقش‌ها» یک نقش بسازید.') }}
                    </div>
                @endforelse

            </div>
        </div>

        {{-- ================================================= --}}
        {{-- ۳) رمز عبور --}}
        {{-- ================================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('رمز عبور') }}</h3>
            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="radio-inline">
                        <input type="radio"
                               name="password_mode"
                               value="auto"
                               id="password_mode_auto"
                               @if($oldPasswordMode === 'auto') checked @endif>
                        {{ __('تولید خودکار رمز قوی') }}
                    </label>

                    <label class="radio-inline">
                        <input type="radio"
                               name="password_mode"
                               value="manual"
                               id="password_mode_manual"
                               @if($oldPasswordMode === 'manual') checked @endif>
                        {{ __('تعیین دستی رمز') }}
                    </label>
                </div>

                <div class="alert alert-info" id="auto-password-note">
                    {{ __('رمز عبور به‌صورت تصادفی و قوی ساخته می‌شود و پس از ذخیره، «تنها یک بار» به شما نمایش داده می‌شود. آن را یادداشت کنید و در اولین ورود، تغییر دهید.') }}
                </div>

                <div class="u-display-none" id="manual-password-fields">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">{{ __('رمز عبور') }}</label>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_confirmation">{{ __('تکرار رمز عبور') }}</label>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <small class="text-muted">
                        {{ __('حداقل ۱۲ کاراکتر و حداقل سه نوع از: حرف کوچک، حرف بزرگ، رقم، نماد.') }}
                    </small>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-body text-left">
                <a href="{{ route('users.index') }}" class="btn btn-default">
                    {{ __('انصراف') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="demo-pli-add"></i> {{ __('ساخت کاربر') }}
                </button>
            </div>
        </div>

    </form>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        (function () {
            var autoRadio   = document.getElementById('password_mode_auto');
            var manualRadio = document.getElementById('password_mode_manual');
            var autoNote    = document.getElementById('auto-password-note');
            var manualBox   = document.getElementById('manual-password-fields');

            if (!autoRadio || !manualRadio || !autoNote || !manualBox) {
                return;
            }

            /**
             * نمایش/پنهان‌کردن بخش رمز دستی بر پایه گزینه انتخابی.
             *
             * فیلدهای رمز دستی همیشه در فرم هستند تا داده وارد‌شده هنگام
             * جابه‌جایی بین دو حالت از دست نرود؛ فقط نمایش کنترل می‌شود.
             */
            function sync() {
                var isManual = manualRadio.checked;

                manualBox.style.display = isManual ? 'block' : 'none';
                autoNote.style.display  = isManual ? 'none' : 'block';
            }

            autoRadio.addEventListener('change', sync);
            manualRadio.addEventListener('change', sync);

            sync();
        })();
    </script>
@endsection
