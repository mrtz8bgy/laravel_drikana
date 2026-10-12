@extends('layouts.app')

@section('content')

{{--
    فهرست نقش‌ها
    ─────────────────────────────────────────────────────────────
    صفحه‌ای برای مدیر کل سیستم و دارندگان مجوز roles.view.
    دکمه‌های «ایجاد»، «ویرایش» و «حذف» بر اساس مجوز کاربر نمایش
    داده می‌شوند؛ اما اعمال واقعی مجوز در سرور انجام می‌شود و این
    فقط لایه تجربه کاربری است.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('مدیریت نقش‌ها') }}</h3>

        @if(Auth::user()->can('create', App\Models\Role::class) || Auth::user()->hasPermissionTo('roles.create'))
            <a href="{{ route('roles.create') }}" class="btn btn-rounded btn-info pull-left">
                <i class="demo-pli-add"></i> {{ __('ایجاد نقش جدید') }}
            </a>
        @endif
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

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ __('نقش‌های تعریف‌شده') }}</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">{{ __('نام نقش') }}</th>
                        <th width="15%">{{ __('شناسه (slug)') }}</th>
                        <th width="10%" class="text-center">{{ __('سطح') }}</th>
                        <th width="12%" class="text-center">{{ __('تعداد مجوزها') }}</th>
                        <th width="12%" class="text-center">{{ __('کاربران') }}</th>
                        <th width="10%" class="text-center">{{ __('وضعیت') }}</th>
                        <th width="16%">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $key => $role)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>
                                <strong>{{ $role->name }}</strong>

                                @if($role->is_system)
                                    <span class="label label-warning u-font-size-10px">
                                        {{ __('سیستمی') }}
                                    </span>
                                @endif

                                @if($role->description)
                                    <br>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($role->description, 60) }}</small>
                                @endif
                            </td>

                            <td><code>{{ $role->slug }}</code></td>

                            <td class="text-center">
                                @php
                                    $levelColors = [
                                        100 => 'danger',
                                        90  => 'danger',
                                        70  => 'warning',
                                        60  => 'info',
                                        50  => 'primary',
                                        10  => 'default',
                                    ];
                                    $levelColor = isset($levelColors[$role->level]) ? $levelColors[$role->level] : 'default';
                                @endphp
                                <span class="label label-{{ $levelColor }}">{{ $role->level }}</span>
                            </td>

                            <td class="text-center">
                                {{ isset($permissionCounts[$role->id]) ? $permissionCounts[$role->id] : 0 }}
                            </td>

                            <td class="text-center">{{ $role->users_count }}</td>

                            <td class="text-center">
                                @if($role->is_active)
                                    <span class="label label-success">{{ __('فعال') }}</span>
                                @else
                                    <span class="label label-danger">{{ __('غیرفعال') }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{ __('عملیات') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">

                                        @if(Auth::user()->hasPermissionTo('roles.view'))
                                            <li>
                                                <a href="{{ route('roles.show', $role->id) }}">
                                                    <i class="demo-pli-view-list"></i> {{ __('مشاهده') }}
                                                </a>
                                            </li>
                                        @endif

                                        {{-- ویرایش نقش‌های سیستمی مجاز است، اما نام و slug آن‌ها قفل است --}}
                                        @if(Auth::user()->hasPermissionTo('roles.update'))
                                            <li>
                                                <a href="{{ route('roles.edit', $role->id) }}">
                                                    <i class="demo-pli-pen-5"></i> {{ __('ویرایش مجوزها') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::user()->hasPermissionTo('roles.delete'))
                                            @if($role->is_system)
                                                {{-- نقش سیستمی قابل حذف نیست --}}
                                                <li class="disabled">
                                                    <a class="u-cursor-not-allowed-opacity-5" href="#" title="{{ __('نقش سیستمی قابل حذف نیست') }}"
 onclick="return false;">
                                                        <i class="demo-pli-recycling"></i> {{ __('حذف (سیستمی)') }}
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="#" onclick="confirm_modal('{{ route('roles.destroy.delete', $role->id) }}'); return false;"
                                                       class="text-danger">
                                                        <i class="demo-pli-recycling"></i> {{ __('حذف') }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endif

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                {{ __('هیچ نقشی تعریف نشده است.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $roles->links() }}
        </div>
    </div>
</div>

@endsection
