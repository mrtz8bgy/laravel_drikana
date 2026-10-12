@extends('layouts.app')

@section('content')

{{--
    فهرست کاربران و نقش‌هایشان
    ─────────────────────────────────────────────────────────────
    جست‌وجو بر اساس نام/ایمیل/تلفن و فیلتر بر اساس نقش.
    تمام کوئری‌ها در کنترلر با parameter binding اجرا می‌شوند.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('مدیریت کاربران و نقش‌ها') }}</h3>

        {{-- ساخت کاربر ادمین جدید و دسترسی به نقش‌ها.
             نمایش دکمه‌ها با @can انجام می‌شود، اما اعمال واقعی مجوز در
             میدل‌ور همان مسیرها است؛ پنهان‌بودن دکمه جایگزین کنترل سرور نیست. --}}
        <div class="pull-left">
            @can('users.create')
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="demo-pli-add-user"></i> {{ __('کاربر ادمین جدید') }}
                </a>
            @endcan

            @can('roles.view')
                <a href="{{ route('roles.index') }}" class="btn btn-default">
                    <i class="demo-pli-shield"></i> {{ __('نقش‌ها و سطح دسترسی') }}
                </a>
            @endcan
        </div>
    </div>
</div>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('error') }}
    </div>
@endif

{{-- ============================================= --}}
{{-- فیلترها --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-body">
        <form method="GET" action="{{ route('users.index') }}" class="form-inline">
            <div class="form-group u-margin-left-10px">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="{{ __('جست‌وجو: نام، ایمیل یا تلفن') }}"
                       maxlength="100"
                       value="{{ e(request('search')) }}">
            </div>

            <div class="form-group u-margin-left-10px">
                <select name="role" class="form-control">
                    <option value="">{{ __('همه نقش‌ها') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" @if(request('role') === $role->slug) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group u-margin-left-10px">
                <select name="user_type" class="form-control">
                    <option value="">{{ __('همه انواع کاربر') }}</option>
                    @foreach(['admin' => 'مدیر', 'staff' => 'کارمند', 'seller' => 'فروشنده', 'customer' => 'مشتری'] as $value => $label)
                        <option value="{{ $value }}" @if(request('user_type') === $value) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="demo-pli-magnifier"></i> {{ __('اعمال فیلتر') }}
            </button>

            @if(request()->hasAny(['search', 'role', 'user_type']))
                <a href="{{ route('users.index') }}" class="btn btn-default">{{ __('پاک‌کردن') }}</a>
            @endif
        </form>
    </div>
</div>

{{-- ============================================= --}}
{{-- جدول کاربران --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ __('کاربران') }}
            <small class="text-muted">({{ $users->total() }})</small>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">{{ __('نام') }}</th>
                        <th width="20%">{{ __('ایمیل') }}</th>
                        <th width="12%">{{ __('تلفن') }}</th>
                        <th width="10%" class="text-center">{{ __('نوع') }}</th>
                        <th width="20%">{{ __('نقش‌ها') }}</th>
                        <th width="13%">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>

                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === Auth::id())
                                    <span class="label label-info u-font-size-10px">{{ __('شما') }}</span>
                                @endif
                            </td>

                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: '—' }}</td>

                            <td class="text-center">
                                @php
                                    $typeLabels = [
                                        'admin'    => ['danger', 'مدیر'],
                                        'staff'    => ['warning', 'کارمند'],
                                        'seller'   => ['primary', 'فروشنده'],
                                        'customer' => ['default', 'مشتری'],
                                    ];
                                    $type = isset($typeLabels[$user->user_type]) ? $typeLabels[$user->user_type] : ['default', $user->user_type];
                                @endphp
                                <span class="label label-{{ $type[0] }}">{{ $type[1] }}</span>
                            </td>

                            <td>
                                @forelse($user->roles as $role)
                                    <span class="label label-success u-display-inline-block-margin-2px-font-weight-400">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-muted">{{ __('بدون نقش') }}</span>
                                @endforelse
                            </td>

                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" type="button">
                                        {{ __('عملیات') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        @if(Auth::user()->hasPermissionTo('users.view'))
                                            <li>
                                                <a href="{{ route('users.show', $user->id) }}">
                                                    <i class="demo-pli-view-list"></i> {{ __('مشاهده دسترسی‌ها') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::user()->hasPermissionTo('roles.assign'))
                                            <li>
                                                <a href="{{ route('users.roles.edit', $user->id) }}">
                                                    <i class="demo-pli-gear"></i> {{ __('تغییر نقش‌ها') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                {{ __('کاربری با این مشخصات یافت نشد.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection
