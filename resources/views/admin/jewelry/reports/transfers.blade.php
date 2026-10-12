@extends('admin.jewelry.layouts.app')

@section('title', 'گزارش انتقالات')

@section('jewelry_content')

{{--
    گزارش انتقالات مالکیت
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] مسیر ویو در کنترلر اشتباه بود
    (`jewelry.reports.transfers` به‌جای `admin.jewelry.reports.transfers`)
    و صفحه گزارش انتقالات با خطای 500 باز نمی‌شد.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">گزارش انتقالات مالکیت</h4>
    <a href="{{ route('admin.jewelry.dashboard') }}" class="btn btn-outline-secondary">بازگشت</a>
</div>

{{-- آمار --}}
<div class="row g-3 mb-4">
    @foreach([
        'total'    => ['کل انتقالات', 'secondary'],
        'pending'  => ['در انتظار تأیید', 'warning'],
        'approved' => ['تأییدشده', 'success'],
        'rejected' => ['ردشده', 'danger'],
    ] as $key => $meta)
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0 text-{{ $meta[1] }}">
                        {{ number_format(isset($stats[$key]) ? $stats[$key] : 0) }}
                    </h3>
                    <small class="text-muted">{{ $meta[0] }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- فیلتر --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jewelry.reports.transfers') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">از تاریخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ e(request('date_from')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">تا تاریخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ e(request('date_to')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">وضعیت</label>
                <select name="status" class="form-select">
                    <option value="">همه</option>
                    @foreach(['pending' => 'در انتظار', 'approved' => 'تأییدشده', 'rejected' => 'ردشده', 'cancelled' => 'لغوشده'] as $v => $l)
                        <option value="{{ $v }}" @if(request('status') === $v) selected @endif>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
        </form>
    </div>
</div>

{{-- توزیع نوع انتقال --}}
@if(isset($transferTypeDistribution) && count($transferTypeDistribution) > 0)
    <div class="card mb-3">
        <div class="card-header">توزیع بر اساس نوع انتقال</div>
        <div class="card-body">
            <table class="table table-sm mb-0">
                <thead><tr><th>نوع انتقال</th><th>تعداد</th></tr></thead>
                <tbody>
                    @foreach($transferTypeDistribution as $type => $count)
                        <tr><td>{{ $type }}</td><td>{{ number_format($count) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- جدول --}}
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>قطعه</th>
                    <th>از مالک</th>
                    <th>به مالک</th>
                    <th>نوع</th>
                    <th>مبلغ</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $key => $transfer)
                    <tr>
                        <td>{{ $transfers->firstItem() + $key }}</td>
                        <td><small>{{ $transfer->certificate ? $transfer->certificate->serial_number : '—' }}</small></td>
                        <td><small>{{ optional($transfer->fromOwner)->full_name ?: '—' }}</small></td>
                        <td><small>{{ optional($transfer->toOwner)->full_name ?: '—' }}</small></td>
                        <td><small>{{ $transfer->transfer_type }}</small></td>
                        <td><small>{{ $transfer->price ? number_format($transfer->price) : '—' }}</small></td>
                        <td><small>{{ $transfer->transfer_date }}</small></td>
                        <td>
                            @php
                                $b = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ isset($b[$transfer->status]) ? $b[$transfer->status] : 'secondary' }}">
                                {{ $transfer->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">داده‌ای یافت نشد.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">{{ $transfers->appends(request()->query())->links() }}</div>
    </div>
</div>

@endsection
