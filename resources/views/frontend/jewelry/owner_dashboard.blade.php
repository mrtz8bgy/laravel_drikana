@extends('frontend.layouts.app')

@section('meta_title', __('جواهرات من'))
@section('content')

{{--
    داشبورد مالک — بخش «جواهرات من»
    ─────────────────────────────────────────────────────────────
    تمام کوئری‌ها در کنترلر به شناسه کاربر جاری محدود شده‌اند،
    بنابراین امکان دسترسی به دارایی کاربران دیگر (IDOR) وجود ندارد.

    نکته حقوقی: سوابق این سامانه «سابقه ثبت‌شده در سیستم» است و
    به‌تنهایی سند مالکیت قانونی محسوب نمی‌شود. مرجع قانونی، اسناد
    رسمی و مدارک کاغذی است.
--}}

<div class="container" style="padding: 24px 15px;">

    <div class="row">
        <div class="col-sm-12">
            <h2>{{ __('جواهرات من') }}</h2>
            <p class="text-muted">
                {{ __('فهرست قطعاتی که مالکیت آن‌ها به نام شما ثبت شده است.') }}
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ============================================= --}}
    {{-- آمار خلاصه --}}
    {{-- ============================================= --}}
    <div class="row" style="margin-bottom: 24px;">
        <div class="col-sm-3">
            <div class="panel panel-default text-center">
                <div class="panel-body">
                    <h3 style="margin: 0;">{{ number_format($stats['total_certificates']) }}</h3>
                    <small class="text-muted">{{ __('کل قطعات') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-warning text-center">
                <div class="panel-body">
                    <h3 style="margin: 0;">{{ number_format($stats['pending_transfers']) }}</h3>
                    <small class="text-muted">{{ __('انتقال در انتظار تأیید') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-info text-center">
                <div class="panel-body">
                    <h3 style="margin: 0;">{{ number_format($stats['active_repairs']) }}</h3>
                    <small class="text-muted">{{ __('تعمیرات در جریان') }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-danger text-center">
                <div class="panel-body">
                    <h3 style="margin: 0;">{{ number_format($stats['missing_reports']) }}</h3>
                    <small class="text-muted">{{ __('گزارش مفقودی') }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- قطعات --}}
    {{-- ============================================= --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('قطعات من') }}</strong>
                </div>
                <div class="panel-body">

                    @if($certificates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('شماره سری') }}</th>
                                        <th>{{ __('نام قطعه') }}</th>
                                        <th>{{ __('فلز / عیار') }}</th>
                                        <th>{{ __('وزن') }}</th>
                                        <th>{{ __('وضعیت') }}</th>
                                        <th>{{ __('عملیات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($certificates as $key => $certificate)
                                        <tr>
                                            <td>{{ $certificates->firstItem() + $key }}</td>
                                            <td><code>{{ $certificate->serial_number }}</code></td>
                                            <td>{{ $certificate->product_name }}</td>
                                            <td>{{ $certificate->metal_type }} / {{ $certificate->purity }}</td>
                                            <td>{{ $certificate->weight }} گرم</td>
                                            <td>
                                                @if($certificate->is_verified)
                                                    <span class="label label-success">{{ __('تأییدشده') }}</span>
                                                @else
                                                    <span class="label label-default">{{ __('در انتظار تأیید') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('my-jewelry.certificates.history', $certificate->id) }}"
                                                   class="btn btn-xs btn-default">
                                                    {{ __('سابقه مالکیت') }}
                                                </a>

                                                @if(!$certificate->pendingTransfers()->exists())
                                                    <a href="{{ route('my-jewelry.transfers.create', $certificate->id) }}"
                                                       class="btn btn-xs btn-primary">
                                                        {{ __('درخواست انتقال') }}
                                                    </a>
                                                @else
                                                    <span class="text-muted" style="font-size: 12px;">
                                                        {{ __('درخواست در انتظار') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $certificates->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ __('هنوز قطعه‌ای به نام شما ثبت نشده است.') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('my-jewelry.transfers.index') }}" class="btn btn-primary">
                {{ __('درخواست‌های انتقال من') }}
            </a>
        </div>
    </div>

    <hr>
    <p class="text-muted" style="font-size: 12px;">
        {{ __('توجه: سوابق این سامانه، سابقه ثبت‌شده در سیستم است و به‌تنهایی سند مالکیت قانونی محسوب نمی‌شود.') }}
    </p>

</div>

@endsection
