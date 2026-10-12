@extends('frontend.layouts.app')

@section('meta_title', __('درخواست‌های انتقال مالکیت من'))
@section('content')

{{--
    فهرست درخواست‌های انتقال مالکیت کاربر جاری
    ─────────────────────────────────────────────────────────────
    کنترلر تنها درخواست‌هایی را برمی‌گرداند که کاربر در آن‌ها
    درخواست‌دهنده، مالک مبدأ یا مالک مقصد باشد.
--}}

<div class="container" style="padding: 24px 15px;">

    <div class="row">
        <div class="col-sm-12">
            <h2>{{ __('درخواست‌های انتقال مالکیت من') }}</h2>
            <a href="{{ route('my-jewelry.dashboard') }}" class="btn btn-default btn-sm">
                {{ __('بازگشت به جواهرات من') }}
            </a>
        </div>
    </div>

    <br>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('قطعه') }}</th>
                            <th>{{ __('مالک مقصد') }}</th>
                            <th>{{ __('نوع') }}</th>
                            <th>{{ __('تاریخ درخواست') }}</th>
                            <th>{{ __('وضعیت') }}</th>
                            <th>{{ __('عملیات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $key => $transfer)
                            <tr>
                                <td>{{ $transfers->firstItem() + $key }}</td>
                                <td>
                                    @if($transfer->certificate)
                                        <code>{{ $transfer->certificate->serial_number }}</code>
                                    @endif
                                </td>
                                <td>{{ optional($transfer->toOwner)->full_name ?: '—' }}</td>
                                <td>{{ $transfer->transfer_type ?: '—' }}</td>
                                <td><small>{{ $transfer->created_at }}</small></td>
                                <td>
                                    @php
                                        $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'default'];
                                        $labels = ['pending' => 'در انتظار تأیید', 'approved' => 'تأییدشده', 'rejected' => 'ردشده', 'cancelled' => 'لغوشده'];
                                    @endphp
                                    <span class="label label-{{ isset($badges[$transfer->status]) ? $badges[$transfer->status] : 'default' }}">
                                        {{ isset($labels[$transfer->status]) ? $labels[$transfer->status] : $transfer->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('my-jewelry.transfers.show', $transfer->id) }}"
                                       class="btn btn-xs btn-default">{{ __('جزئیات') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    {{ __('هیچ درخواست انتقالی ثبت نشده است.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>

</div>

@endsection
