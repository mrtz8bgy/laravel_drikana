@extends('layouts.app')

@section('content')

{{--
    گزارش رویدادهای امنیتی (Audit Log)
    ─────────────────────────────────────────────────────────────
    تمام عملیات حساس سیستم در این جدول ثبت می‌شود: تغییر نقش و مجوز،
    تغییر مالکیت، انتشار مقاله، ورود ناموفق و دسترسی غیرمجاز.

    نکته: این جدول هرگز رمز عبور، توکن یا کلید API ذخیره نمی‌کند.
    مقادیر حساس پیش از ذخیره در AuditLogger پاک‌سازی می‌شوند.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3>{{ __('گزارش‌های امنیتی و Audit Log') }}</h3>
    </div>
</div>

{{-- ============================================= --}}
{{-- آمار خلاصه --}}
{{-- ============================================= --}}
<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-primary">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['total']) }}</h2>
                <small>{{ __('کل رویدادها') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-danger">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['critical']) }}</h2>
                <small>{{ __('رویدادهای بحرانی') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-warning">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['warning']) }}</h2>
                <small>{{ __('هشدارها') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-info">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['today']) }}</h2>
                <small>{{ __('رویدادهای امروز') }}</small>
            </div>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- فیلترها --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-body">
        <form method="GET" action="{{ route('audit_logs.index') }}" class="form-inline">

            <div class="form-group u-margin-left-8px">
                <input type="text"
                       name="event"
                       class="form-control"
                       placeholder="{{ __('نوع رویداد') }}"
                       maxlength="100"
                       value="{{ e(request('event')) }}">
            </div>

            <div class="form-group u-margin-left-8px">
                <select name="severity" class="form-control">
                    <option value="">{{ __('همه سطوح') }}</option>
                    @foreach([
                        \App\Models\AuditLog::SEVERITY_INFO     => 'اطلاع',
                        \App\Models\AuditLog::SEVERITY_NOTICE   => 'توجه',
                        \App\Models\AuditLog::SEVERITY_WARNING  => 'هشدار',
                        \App\Models\AuditLog::SEVERITY_CRITICAL => 'بحرانی',
                    ] as $value => $label)
                        <option value="{{ $value }}" @if(request('severity') === $value) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group u-margin-left-8px">
                <input type="number" name="user_id" class="form-control"
                       placeholder="{{ __('شناسه کاربر') }}" min="1"
                       value="{{ e(request('user_id')) }}">
            </div>

            <div class="form-group u-margin-left-8px">
                <input type="date" name="date_from" class="form-control"
                       value="{{ e(request('date_from')) }}" title="{{ __('از تاریخ') }}">
            </div>

            <div class="form-group u-margin-left-8px">
                <input type="date" name="date_to" class="form-control"
                       value="{{ e(request('date_to')) }}" title="{{ __('تا تاریخ') }}">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="demo-pli-magnifier"></i> {{ __('جست‌وجو') }}
            </button>

            @if(request()->hasAny(['event', 'severity', 'user_id', 'date_from', 'date_to']))
                <a href="{{ route('audit_logs.index') }}" class="btn btn-default">{{ __('پاک‌کردن') }}</a>
            @endif
        </form>
    </div>
</div>

{{-- ============================================= --}}
{{-- جدول رویدادها --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ __('رویدادهای ثبت‌شده') }}
            <small class="text-muted">({{ $logs->total() }})</small>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">{{ __('زمان') }}</th>
                        <th width="18%">{{ __('رویداد') }}</th>
                        <th width="10%" class="text-center">{{ __('سطح') }}</th>
                        <th width="15%">{{ __('کاربر') }}</th>
                        <th width="12%">{{ __('IP') }}</th>
                        <th width="25%">{{ __('شرح') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $key => $log)
                        @php
                            $severityColors = [
                                'info'     => 'info',
                                'notice'   => 'primary',
                                'warning'  => 'warning',
                                'critical' => 'danger',
                            ];
                            $color = isset($severityColors[$log->severity]) ? $severityColors[$log->severity] : 'default';
                        @endphp
                        <tr class="{{ $log->severity === 'critical' ? 'danger' : '' }}">
                            <td>{{ $logs->firstItem() + $key }}</td>

                            <td>
                                <small>{{ $log->created_at }}</small>
                            </td>

                            <td>
                                <code class="u-font-size-11px">{{ $log->event }}</code>
                            </td>

                            <td class="text-center">
                                <span class="label label-{{ $color }}">{{ $log->severity }}</span>
                            </td>

                            <td>
                                @if($log->user_id)
                                    <a href="{{ route('users.show', $log->user_id) }}">
                                        {{ optional($log->user)->name ?: ('#' . $log->user_id) }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('سیستم') }}</span>
                                @endif
                            </td>

                            <td><small>{{ $log->ip_address ?: '—' }}</small></td>

                            <td>
                                <small>{{ $log->description }}</small>

                                @if($log->old_values || $log->new_values)
                                    <br>
                                    <a href="#" data-toggle="modal" data-target="#logDetail{{ $log->id }}">
                                        <small>{{ __('مشاهده جزئیات تغییر') }}</small>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                {{ __('هیچ رویدادی با این شرایط یافت نشد.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- مودال‌های جزئیات تغییر --}}
{{-- ============================================= --}}
@foreach($logs as $log)
    @if($log->old_values || $log->new_values)
        <div class="modal fade" id="logDetail{{ $log->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ __('جزئیات تغییر') }} — {{ $log->event }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>{{ __('مقدار پیشین') }}</h5>
                                <pre class="u-max-height-300px-overflow-auto-direction-ltr-text-align-left">{{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            <div class="col-sm-6">
                                <h5>{{ __('مقدار جدید') }}</h5>
                                <pre class="u-max-height-300px-overflow-auto-direction-ltr-text-align-left">{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>

                        @if($log->user_agent)
                            <hr>
                            <small class="text-muted">{{ __('مرورگر') }}: {{ $log->user_agent }}</small>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('بستن') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
