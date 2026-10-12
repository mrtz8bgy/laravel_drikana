@extends('layouts.app')

@section('content')

{{--
    فهرست مجوزهای سیستم
    ─────────────────────────────────────────────────────────────
    مجوزهای تعریف‌شده در PermissionRegistry قابل حذف نیستند، چون کد
    برنامه (میدل‌ور روت‌ها) به شناسه آن‌ها وابسته است. این موضوع در
    کنترلر بررسی می‌شود و در این صفحه با برچزسب «سیستمی» نمایش داده
    می‌شود.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('مدیریت مجوزها') }}</h3>

        @if(Auth::user()->hasPermissionTo('permissions.create'))
            <button type="button" class="btn btn-info pull-left" data-toggle="modal" data-target="#createPermissionModal">
                <i class="demo-pli-add"></i> {{ __('افزودن مجوز سفارشی') }}
            </button>
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
    $totalPermissions = 0;
    foreach ($permissions as $group) { $totalPermissions += $group->count(); }
@endphp

<div class="alert alert-info">
    <i class="demo-pli-information"></i>
    {{ __('در مجموع') }} <strong>{{ $totalPermissions }}</strong>
    {{ __('مجوز در') }} <strong>{{ $permissions->count() }}</strong>
    {{ __('گروه تعریف شده است.') }}
</div>

@foreach($permissions as $groupName => $items)

    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{ $groupName ?: __('عمومی') }}
                <span class="badge badge-info">{{ $items->count() }}</span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="30%">{{ __('شناسه (slug)') }}</th>
                            <th width="30%">{{ __('عنوان') }}</th>
                            <th width="30%">{{ __('توضیحات') }}</th>
                            <th width="10%" class="text-center">{{ __('عملیات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $permission)
                            @php
                                $isProtected = in_array($permission->slug, $protectedSlugs, true);
                            @endphp
                            <tr>
                                <td>
                                    <code>{{ $permission->slug }}</code>
                                    @if($isProtected)
                                        <span class="label label-warning u-font-size-10px">
                                            {{ __('سیستمی') }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <small class="text-muted">{{ $permission->description ?: '—' }}</small>
                                </td>
                                <td class="text-center">
                                    @if($isProtected)
                                        {{-- مجوز سیستمی: کد برنامه به آن وابسته است --}}
                                        <span class="text-muted" title="{{ __('این مجوز در کد برنامه استفاده می‌شود و قابل حذف نیست.') }}">
                                            <i class="demo-pli-lock-2"></i>
                                        </span>
                                    @elseif(Auth::user()->hasPermissionTo('permissions.delete'))
                                        <a href="#"
                                           onclick="confirm_modal('{{ route('permissions.destroy.delete', $permission->id) }}'); return false;"
                                           class="text-danger"
                                           title="{{ __('حذف') }}">
                                            <i class="demo-pli-recycling"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endforeach

{{-- ============================================= --}}
{{-- مودال ایجاد مجوز سفارشی --}}
{{-- ============================================= --}}
@if(Auth::user()->hasPermissionTo('permissions.create'))
<div class="modal fade" id="createPermissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ __('افزودن مجوز سفارشی') }}</h4>
                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">
                        {{ __('مجوز سفارشی تنها در صورتی اثر دارد که در کد برنامه (میدل‌ور یا Policy) نیز بررسی شود. این فرم برای تعریف مجوزهایی است که بعداً در کد استفاده خواهند شد.') }}
                    </div>

                    <div class="form-group">
                        <label for="perm_slug">{{ __('شناسه مجوز') }} <span class="text-danger">*</span></label>
                        <input type="text"
                               id="perm_slug"
                               name="slug"
                               class="form-control"
                               maxlength="150"
                               pattern="[a-z][a-z0-9]*(\.[a-z][a-z0-9_]*)+"
                               placeholder="module.action"
                               value="{{ old('slug') }}"
                               required>
                        <small class="text-muted">{{ __('به شکل group.action — مثال: reports.export') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="perm_name">{{ __('عنوان مجوز') }} <span class="text-danger">*</span></label>
                        <input type="text"
                               id="perm_name"
                               name="name"
                               class="form-control"
                               maxlength="191"
                               value="{{ old('name') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="perm_group">{{ __('گروه') }}</label>
                        <input type="text"
                               id="perm_group"
                               name="group"
                               class="form-control"
                               maxlength="100"
                               value="{{ old('group') }}">
                    </div>

                    <div class="form-group">
                        <label for="perm_desc">{{ __('توضیحات') }}</label>
                        <textarea id="perm_desc" name="description" class="form-control" rows="2" maxlength="500">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('انصراف') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('ایجاد مجوز') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endif

@endsection
