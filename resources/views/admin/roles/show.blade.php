@extends('layouts.app')

@section('content')

{{--
    جزئیات یک نقش
    ─────────────────────────────────────────────────────────────
    نمایش نام، سطح، تعداد کاربران و فهرست کامل مجوزهای نقش.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('جزئیات نقش') }}: {{ $role->name }}</h3>
        <a href="{{ route('roles.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
        </a>
    </div>
</div>

<br>

<div class="row">
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('اطلاعات نقش') }}</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">{{ __('نام') }}</th>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('شناسه') }}</th>
                        <td><code>{{ $role->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>{{ __('سطح') }}</th>
                        <td>{{ $role->level }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('نوع') }}</th>
                        <td>
                            @if($role->is_system)
                                <span class="label label-warning">{{ __('سیستمی') }}</span>
                            @else
                                <span class="label label-default">{{ __('کاربرساخته') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('وضعیت') }}</th>
                        <td>
                            @if($role->is_active)
                                <span class="label label-success">{{ __('فعال') }}</span>
                            @else
                                <span class="label label-danger">{{ __('غیرفعال') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('تعداد مجوزها') }}</th>
                        <td>{{ count($permissions) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('تاریخ ایجاد') }}</th>
                        <td>{{ $role->created_at }}</td>
                    </tr>
                </table>

                @if($role->description)
                    <div class="alert alert-info u-margin-top-10px">
                        {{ $role->description }}
                    </div>
                @endif

                @if(Auth::user()->hasPermissionTo('roles.update'))
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-block">
                        <i class="demo-pli-pen-5"></i> {{ __('ویرایش مجوزهای این نقش') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('مجوزهای این نقش') }}
                    <span class="badge badge-info">{{ count($permissions) }}</span>
                </h3>
            </div>
            <div class="panel-body">

                @if(empty($permissions))
                    <div class="alert alert-warning">
                        {{ __('این نقش هیچ مجوزی ندارد. کاربران با این نقش فقط به بخش‌های عمومی دسترسی خواهند داشت.') }}
                    </div>
                @else
                    @php
                        // گروه‌بندی مجوزها بر اساس پیشوند قبل از نقطه
                        $grouped = [];
                        foreach ($permissions as $perm) {
                            $parts = explode('.', $perm->slug);
                            $group = isset($parts[0]) ? $parts[0] : 'other';
                            $grouped[$group][] = $perm;
                        }
                    @endphp

                    @foreach($grouped as $groupName => $items)
                        <div class="panel panel-default">
                            <div class="panel-heading u-padding-6px-12px">
                                <strong><code>{{ $groupName }}</code></strong>
                                <span class="badge">{{ count($items) }}</span>
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
