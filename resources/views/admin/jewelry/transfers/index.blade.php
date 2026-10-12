@extends('admin.jewelry.layouts.app')

@section('title', 'درخواست‌های انتقال مالکیت')

@section('jewelry_content')

{{--
    فهرست درخواست‌های انتقال مالکیت (پنل مدیریت)
    ─────────────────────────────────────────────────────────────
    دسترسی نیازمند مجوز `jewelry.transfers.view` است.
    هر ردیف وضعیت گردش‌کار خود را نشان می‌دهد:
    Pending / Approved / Rejected / Cancelled
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">درخواست‌های انتقال مالکیت</h4>

    @if(auth()->user()->hasPermissionTo('jewelry.transfers.create'))
        <a href="{{ route('admin.jewelry.transfers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> ثبت درخواست جدید
        </a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- آمار --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ number_format($stats['pending']) }}</h3>
                <small class="text-muted">در انتظار تأیید</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-success">{{ number_format($stats['approved']) }}</h3>
                <small class="text-muted">تأییدشده</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-danger">{{ number_format($stats['rejected']) }}</h3>
                <small class="text-muted">ردشده</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-secondary">{{ number_format($stats['cancelled']) }}</h3>
                <small class="text-muted">لغوشده</small>
            </div>
        </div>
    </div>
</div>

{{-- فیلترها --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jewelry.transfers.index') }}" class="row g-2 align-items-end">

            <div class="col-md-3">
                <label class="form-label">وضعیت</label>
                <select name="status" class="form-select">
                    <option value="">همه وضعیت‌ها</option>
                    @foreach([
                        'pending'   => 'در انتظار تأیید',
                        'approved'  => 'تأییدشده',
                        'rejected'  => 'ردشده',
                        'cancelled' => 'لغوشده',
                    ] as $value => $label)
                        <option value="{{ $value }}" @if(request('status') === $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">شناسه قطعه</label>
                <input type="number" name="certificate_id" class="form-control" min="1"
                       value="{{ e(request('certificate_id')) }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">از تاریخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ e(request('date_from')) }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">تا تاریخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ e(request('date_to')) }}">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">جست‌وجو</button>
            </div>

        </form>
    </div>
</div>

{{-- جدول --}}
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>قطعه</th>
                    <th>از مالک</th>
                    <th>به مالک</th>
                    <th>نوع انتقال</th>
                    <th>درخواست‌دهنده</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $key => $transfer)
                    <tr>
                        <td>{{ $transfers->firstItem() + $key }}</td>

                        <td>
                            @if($transfer->certificate)
                                <span class="badge bg-light text-dark">
                                    {{ $transfer->certificate->serial_number }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $transfer->certificate->product_name }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>

                        <td>
                            <small>{{ optional($transfer->fromOwner)->full_name ?: '—' }}</small>
                        </td>

                        <td>
                            <small>{{ optional($transfer->toOwner)->full_name ?: '—' }}</small>
                        </td>

                        <td><small>{{ $transfer->transfer_type ?: '—' }}</small></td>

                        <td>
                            <small>{{ optional($transfer->requester)->name ?: '—' }}</small>
                        </td>

                        <td>
                            <small>{{ $transfer->created_at }}</small>
                        </td>

                        <td>
                            @php
                                $badges = [
                                    'pending'   => 'warning',
                                    'approved'  => 'success',
                                    'rejected'  => 'danger',
                                    'cancelled' => 'secondary',
                                ];
                                $labels = [
                                    'pending'   => 'در انتظار تأیید',
                                    'approved'  => 'تأییدشده',
                                    'rejected'  => 'ردشده',
                                    'cancelled' => 'لغوشده',
                                ];
                            @endphp
                            <span class="badge bg-{{ isset($badges[$transfer->status]) ? $badges[$transfer->status] : 'secondary' }}">
                                {{ isset($labels[$transfer->status]) ? $labels[$transfer->status] : $transfer->status }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('admin.jewelry.transfers.show', $transfer->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                جزئیات
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            هیچ درخواست انتقالی یافت نشد.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $transfers->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection
