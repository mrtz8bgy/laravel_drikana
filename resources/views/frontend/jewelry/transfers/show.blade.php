@extends('frontend.layouts.app')

@section('meta_title', __('جزئیات درخواست انتقال'))
@section('content')

<div class="container" style="padding: 24px 15px;">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>{{ __('جزئیات درخواست انتقال') }} #{{ $transfer->id }}</h2>
            <a href="{{ route('my-jewelry.transfers.index') }}" class="btn btn-default btn-sm">{{ __('بازگشت') }}</a>

            <br><br>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-bordered" style="margin: 0;">
                        <tr>
                            <th width="35%">{{ __('وضعیت') }}</th>
                            <td>
                                @php
                                    $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'default'];
                                    $labels = ['pending' => 'در انتظار تأیید', 'approved' => 'تأییدشده', 'rejected' => 'ردشده', 'cancelled' => 'لغوشده'];
                                @endphp
                                <span class="label label-{{ isset($badges[$transfer->status]) ? $badges[$transfer->status] : 'default' }}">
                                    {{ isset($labels[$transfer->status]) ? $labels[$transfer->status] : $transfer->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('قطعه') }}</th>
                            <td>{{ $transfer->certificate ? ($transfer->certificate->serial_number . ' — ' . $transfer->certificate->product_name) : '—' }}</td>
                        </tr>
                        <tr><th>{{ __('مالک مبدأ') }}</th><td>{{ optional($transfer->fromOwner)->full_name ?: '—' }}</td></tr>
                        <tr><th>{{ __('مالک مقصد') }}</th><td>{{ optional($transfer->toOwner)->full_name ?: '—' }}</td></tr>
                        <tr><th>{{ __('نوع انتقال') }}</th><td>{{ $transfer->transfer_type ?: '—' }}</td></tr>
                        <tr><th>{{ __('تاریخ درخواست') }}</th><td>{{ $transfer->created_at }}</td></tr>
                        @if($transfer->approved_at)
                            <tr><th>{{ __('تاریخ تأیید') }}</th><td>{{ $transfer->approved_at }}</td></tr>
                        @endif
                        @if($transfer->approval_note)
                            <tr><th>{{ __('یادداشت بررسی') }}</th><td>{{ $transfer->approval_note }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($transfer->status === 'pending' && (int) $transfer->requested_by === (int) Auth::id())
                <form action="{{ route('my-jewelry.transfers.cancel', $transfer->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('دلیل لغو (اختیاری)') }}</label>
                        <input type="text" name="reason" class="form-control" maxlength="500">
                    </div>
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('{{ __('آیا از لغو این درخواست اطمینان دارید؟') }}');">
                        {{ __('لغو درخواست') }}
                    </button>
                </form>
            @endif

            @if($history->count() > 0)
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading"><strong>{{ __('سابقه مالکیت این قطعه') }}</strong></div>
                    <div class="panel-body">
                        <table class="table table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('از مالک') }}</th>
                                    <th>{{ __('به مالک') }}</th>
                                    <th>{{ __('نوع') }}</th>
                                    <th>{{ __('تاریخ') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $row)
                                    <tr>
                                        <td>{{ $row->sequence_no }}</td>
                                        <td>{{ $row->from_owner_id ? ('#' . $row->from_owner_id) : '—' }}</td>
                                        <td>#{{ $row->to_owner_id }}</td>
                                        <td><small>{{ $row->event_type }}</small></td>
                                        <td><small>{{ $row->effective_at }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection
