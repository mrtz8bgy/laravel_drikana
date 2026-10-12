{{--
    ─────────────────────────────────────────────────────────────
    فرم تخصیص نقش به کاربر
    ─────────────────────────────────────────────────────────────

    متغیرهای ورودی:
      $user          : کاربر هدف
      $roles         : نقش‌هایی که کاربر جاری اجازه اعطای آن‌ها را دارد
      $userRoleSlugs : slug نقش‌های فعلی کاربر
      $actorLevel    : بالاترین سطح نقش کاربر جاری

    نکات امنیتی:
      • نقشی با سطح بالاتر از سطح کاربر جاری نمایش داده نمی‌شود
        (در کنترلر فیلتر می‌شود).
      • کاربر نمی‌تواند نقش‌های خودش را تغییر دهد — این محدودیت در
        فرم نمایش داده می‌شود و در RbacService اعمال می‌گردد.
      • غیرفعال‌کردن چک‌باکس‌ها صرفاً تجربه کاربری است؛ اعمال واقعی
        در سرویس انجام می‌شود.
--}}
@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('تخصیص نقش به کاربر') }}: {{ $user->name }}</h3>
        <a href="{{ route('users.show', $user->id) }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
        </a>
    </div>
</div>

<br>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="u-margin-0-padding-right-20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $isSelf = ($user->id === Auth::id());
@endphp

@if($isSelf)
    {{-- جلوگیری از افزایش سطح دسترسی: کاربر نمی‌تواند نقش خودش را تغییر دهد --}}
    <div class="alert alert-warning">
        <i class="demo-pli-warning-window"></i>
        <strong>{{ __('توجه') }}</strong><br>
        {{ __('شما نمی‌توانید نقش‌های خودتان را تغییر دهید. برای انجام این کار با مدیر کل سیستم هماهنگ کنید.') }}
    </div>
@endif

<div class="col-lg-8 col-lg-offset-2">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ __('نقش‌های کاربر') }}</h3>
        </div>

        <form action="{{ route('users.roles.update', $user->id) }}" method="POST">
            @csrf

            <div class="panel-body">

                <div class="alert alert-info">
                    {{ __('یک کاربر می‌تواند هم‌زمان چند نقش داشته باشد. مجوزهای نهایی، اجتماع مجوزهای تمام نقش‌های فعال او است.') }}
                </div>

                @forelse($roles as $role)
                    @php
                        // نقش‌های سیستمی حیاتی نباید از این مسیر سلب شوند
                        $isProtected = in_array($role->slug, ['super_admin'], true);
                        $isChecked   = in_array($role->slug, $userRoleSlugs, true);
                        $disabled    = $isSelf;
                    @endphp

                    <div class="row u-padding-8px-0-border-bottom-1px-solid-eee">
                        <div class="col-sm-8">
                            <strong>{{ $role->name }}</strong>
                            <span class="label label-default">{{ $role->level }}</span>

                            @if($isProtected)
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
                                       @if($isChecked) checked @endif
                                       @if($disabled) disabled @endif>
                                <span class="slider round"></span>
                            </label>

                            {{-- در حالت غیرفعال مقدار فعلی حفظ می‌شود --}}
                            @if($disabled && $isChecked)
                                <input type="hidden" name="roles[]" value="{{ $role->slug }}">
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">{{ __('هیچ نقشی برای تخصیص در دسترس نیست.') }}</p>
                @endforelse

            </div>

            <div class="panel-footer text-left">
                <a href="{{ route('users.show', $user->id) }}" class="btn btn-default">{{ __('انصراف') }}</a>
                <button type="submit" class="btn btn-primary" @if($isSelf) disabled @endif>
                    {{ __('ذخیره نقش‌ها') }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
