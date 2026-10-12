@extends('layouts.app')

@section('content')

{{--
    جزئیات دسترسی‌های یک کاربر
    ─────────────────────────────────────────────────────────────
    نمایش نقش‌ها و مجوزهای مؤثر کاربر. اگر کاربر مدیر کل باشد،
    همه مجوزهای سیستم نشان داده می‌شود و در بالای صفحه هشدار
    مربوطه درج می‌گردد.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('دسترسی‌های کاربر') }}: {{ $user->name }}</h3>
        <a href="{{ route('users.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
        </a>
    </div>
</div>

<br>

<div class="row">
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('اطلاعات کاربر') }}</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">{{ __('نام') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('ایمیل') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('تلفن') }}</th>
                        <td>{{ $user->phone ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('نوع کاربر') }}</th>
                        <td>{{ $user->user_type }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('نقش قدیمی') }}</th>
                        <td><code>{{ $user->role ?: '—' }}</code></td>
                    </tr>
                    <tr>
                        <th>{{ __('تاریخ عضویت') }}</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                </table>

                @if($user->isSuperAdmin())
                    <div class="alert alert-danger">
                        <i class="demo-pli-warning-window"></i>
                        <strong>{{ __('مدیر کل سیستم') }}</strong><br>
                        {{ __('این کاربر به همه بخش‌ها دسترسی کامل دارد و تمام بررسی‌های مجوز برای او نادیده گرفته می‌شود.') }}
                    </div>
                @endif

                <h5>{{ __('نقش‌های انتساب‌یافته') }}</h5>
                @forelse($user->roles as $role)
                    <span class="label label-success u-display-inline-block-margin-3px-font-weight-400">
                        {{ $role->name }}
                        @if(!$role->is_active)
                            <small>({{ __('غیرفعال') }})</small>
                        @endif
                    </span>
                @empty
                    <p class="text-muted">{{ __('این کاربر هیچ نقشی ندارد.') }}</p>
                @endforelse

                @if(Auth::user()->hasPermissionTo('roles.assign'))
                    <a href="{{ route('users.roles.edit', $user->id) }}" class="btn btn-primary btn-block u-margin-top-12px">
                        <i class="demo-pli-gear"></i> {{ __('تغییر نقش‌های این کاربر') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('مجوزهای مؤثر') }}</h3>
            </div>
            <div class="panel-body">

                @php
                    $total = 0;
                    foreach ($permissions as $group) { $total += $group->count(); }
                @endphp

                @if($total === 0)
                    <div class="alert alert-warning">
                        {{ __('این کاربر هیچ مجوز مؤثری ندارد. تنها به بخش‌های عمومی سایت دسترسی خواهد داشت.') }}
                    </div>
                @else
                    @foreach($permissions as $groupName => $items)
                        <div class="panel panel-default">
                            <div class="panel-heading u-padding-6px-12px">
                                <strong>{{ $groupName ?: __('عمومی') }}</strong>
                                <span class="badge">{{ $items->count() }}</span>
                            </div>
                            <div class="panel-body u-padding-8px-12px">
                                @foreach($items as $perm)
                                    <span class="label label-info u-display-inline-block-margin-3px-font-weight-400">
                                        {{ $perm->name }}
                                        <small class="u-opacity-75">({{ $perm->slug }})</small>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
