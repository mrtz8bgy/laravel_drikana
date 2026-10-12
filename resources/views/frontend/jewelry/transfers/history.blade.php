@extends('frontend.layouts.app')

@section('meta_title', __('سابقه مالکیت قطعه'))
@section('content')

{{--
    سابقه کامل مالکیت یک قطعه
    ─────────────────────────────────────────────────────────────
    فقط مالک فعلی به این صفحه دسترسی دارد (بررسی Policy در کنترلر).

    نکته حقوقی: این سوابق، «سابقه ثبت‌شده در سیستم» است و به‌تنهایی
    سند مالکیت قانونی محسوب نمی‌شود.
--}}

<div class="container" style="padding: 24px 15px;">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>{{ __('سابقه مالکیت قطعه') }}</h2>
            <a href="{{ route('my-jewelry.dashboard') }}" class="btn btn-default btn-sm">{{ __('بازگشت') }}</a>

            <br><br>

            @if(isset($consistency) && !$consistency['consistent'])
                <div class="alert alert-danger">
                    <strong>{{ __('هشدار یکپارچگی داده') }}</strong><br>
                    {{ __('مالک ثبت‌شده در شناسنامه با آخرین رکورد سابقه مالکیت مطابقت ندارد. لطفاً با پشتیبانی تماس بگیرید.') }}
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('مشخصات قطعه') }}</strong></div>
                <div class="panel-body">
                    <table class="table table-bordered" style="margin: 0;">
                        <tr><th width="35%">{{ __('شماره سری') }}</th><td>{{ $certificate->serial_number }}</td></tr>
                        <tr><th>{{ __('نام قطعه') }}</th><td>{{ $certificate->product_name }}</td></tr>
                        <tr><th>{{ __('فلز / عیار') }}</th><td>{{ $certificate->metal_type }} / {{ $certificate->purity }}</td></tr>
                        <tr><th>{{ __('وزن') }}</th><td>{{ $certificate->weight }} گرم</td></tr>
                        <tr><th>{{ __('مالک فعلی') }}</th><td>{{ optional($certificate->owner)->full_name ?: '—' }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('زنجیره مالکیت') }}</strong>
                    <span class="label label-info">{{ $history->count() }}</span>
                </div>
                <div class="panel-body">
                    @if($history->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="22%">{{ __('از مالک') }}</th>
                                    <th width="22%">{{ __('به مالک') }}</th>
                                    <th width="16%">{{ __('نوع رخداد') }}</th>
                                    <th width="32%">{{ __('تاریخ اعمال') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $row)
                                    <tr>
                                        <td>{{ $row->sequence_no }}</td>
                                        <td>{{ $row->from_owner_id ? ('#' . $row->from_owner_id) : __('ثبت اولیه') }}</td>
                                        <td>#{{ $row->to_owner_id }}</td>
                                        <td><small>{{ $row->event_type }}</small></td>
                                        <td><small>{{ $row->effective_at }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">{{ __('سابقه‌ای برای این قطعه ثبت نشده است.') }}</p>
                    @endif
                </div>
            </div>

            <div class="alert alert-warning" style="font-size: 13px;">
                {{ __('توجه: سوابق این سامانه، سابقه ثبت‌شده در سیستم است و به‌تنهایی سند مالکیت قانونی محسوب نمی‌شود. مرجع قانونی، اسناد رسمی است.') }}
            </div>

        </div>
    </div>

</div>

@endsection
