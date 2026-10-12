@extends('admin.jewelry.layouts.app')

@section('title', 'جزئیات درخواست انتقال')

@section('jewelry_content')

{{--
    جزئیات کامل یک درخواست انتقال مالکیت
    ─────────────────────────────────────────────────────────────
    شامل: اطلاعات قطعه، مالک مبدأ و مقصد، دلیل و توضیحات درخواست،
    سابقه کامل مالکیت (دفتر کل) و بررسی یکپارچگی داده.

    دکمه‌های تأیید/رد/لغو بر اساس مجوز کاربر نمایش داده می‌شوند؛
    اعمال واقعی در OwnershipTransferService انجام و مجدداً بررسی می‌شود.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">درخواست انتقال #{{ $transfer->id }}</h4>
    <a href="{{ route('admin.jewelry.transfers.index') }}" class="btn btn-outline-secondary">
        بازگشت
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- هشدار ناهم‌خوانی --}}
@if(isset($consistency) && !$consistency['consistent'])
    <div class="alert alert-danger">
        <strong>هشدار یکپارچگی داده</strong><br>
        مالک ثبت‌شده در شناسنامه با آخرین رکورد دفتر کل مالکیت مطابقت ندارد.
        این وضعیت باید توسط مدیر سیستم بررسی شود.
        <br>
        <small>
            مالک شناسنامه: #{{ $consistency['certificate_owner_id'] }} —
            مالک دفتر کل: #{{ $consistency['ledger_owner_id'] ?: 'نامشخص' }}
        </small>
    </div>
@endif

<div class="row g-3">

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">اطلاعات قطعه</div>
            <div class="card-body">
                @if($transfer->certificate)
                    <table class="table table-sm mb-0">
                        <tr><th width="35%">شناسه</th><td>#{{ $transfer->certificate->id }}</td></tr>
                        <tr><th>شماره سری</th><td>{{ $transfer->certificate->serial_number }}</td></tr>
                        <tr><th>نام قطعه</th><td>{{ $transfer->certificate->product_name }}</td></tr>
                        <tr><th>فلز</th><td>{{ $transfer->certificate->metal_type }}</td></tr>
                        <tr><th>عیار</th><td>{{ $transfer->certificate->purity }}</td></tr>
                        <tr><th>وزن</th><td>{{ $transfer->certificate->weight }} گرم</td></tr>
                        <tr><th>مالک فعلی</th><td>{{ optional($transfer->certificate->owner)->full_name ?: '—' }}</td></tr>
                    </table>
                @else
                    <p class="text-muted">اطلاعات قطعه در دسترس نیست.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">اطلاعات درخواست</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <th width="35%">وضعیت</th>
                        <td>
                            @php
                                $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                                $labels = ['pending' => 'در انتظار تأیید', 'approved' => 'تأییدشده', 'rejected' => 'ردشده', 'cancelled' => 'لغوشده'];
                            @endphp
                            <span class="badge bg-{{ isset($badges[$transfer->status]) ? $badges[$transfer->status] : 'secondary' }}">
                                {{ isset($labels[$transfer->status]) ? $labels[$transfer->status] : $transfer->status }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>مالک مبدأ</th><td>{{ optional($transfer->fromOwner)->full_name ?: '—' }}</td></tr>
                    <tr><th>مالک مقصد</th><td>{{ optional($transfer->toOwner)->full_name ?: '—' }}</td></tr>
                    <tr><th>نوع انتقال</th><td>{{ $transfer->transfer_type ?: '—' }}</td></tr>
                    <tr><th>تاریخ انتقال</th><td>{{ $transfer->transfer_date ?: '—' }}</td></tr>
                    <tr><th>مبلغ اعلامی</th><td>{{ $transfer->price ? number_format($transfer->price) : '—' }}</td></tr>
                    <tr><th>درخواست‌دهنده</th><td>{{ optional($transfer->requester)->name ?: '—' }}</td></tr>
                    <tr><th>زمان درخواست</th><td>{{ $transfer->created_at }}</td></tr>
                    <tr><th>تأییدکننده</th><td>{{ optional($transfer->approver)->name ?: '—' }}</td></tr>
                    <tr><th>زمان تأیید</th><td>{{ $transfer->approved_at ?: '—' }}</td></tr>
                    @if($transfer->approval_note)
                        <tr><th>دلیل تأیید/رد</th><td>{{ $transfer->approval_note }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    @if($transfer->notes)
        <div class="col-12">
            <div class="card">
                <div class="card-header">دلیل و توضیحات درخواست</div>
                <div class="card-body">
                    <p class="mb-0 u-white-space-pre-line">{{ $transfer->notes }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($transfer->contract_file)
        <div class="col-12">
            <div class="card">
                <div class="card-header">مدارک پیوست</div>
                <div class="card-body">
                    <a href="{{ asset('storage/' . ltrim($transfer->contract_file, '/')) }}"
                       target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                        مشاهده فایل پیوست
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- عملیات --}}
    {{-- ============================================= --}}
    @if($transfer->status === 'pending')
        <div class="col-12">
            <div class="card">
                <div class="card-header">اقدامات</div>
                <div class="card-body">

                    @if(auth()->user()->hasPermissionTo('jewelry.transfers.approve'))
                        <form action="{{ route('admin.jewelry.transfers.approve', $transfer->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">یادداشت تأیید (اختیاری)</label>
                                    <input type="text" name="approval_note" class="form-control" maxlength="500">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100"
                                            onclick="return confirm('تأیید نهایی مالکیت را تغییر می‌دهد و قابل بازگشت نیست. ادامه می‌دهید؟');">
                                        تأیید و نهایی‌سازی انتقال
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    @if(auth()->user()->hasPermissionTo('jewelry.transfers.reject'))
                        <form action="{{ route('admin.jewelry.transfers.reject', $transfer->id) }}"
                              method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">دلیل رد <span class="text-danger">*</span></label>
                                <input type="text" name="approval_note" class="form-control"
                                       maxlength="500" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-danger w-100">رد درخواست</button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- تاریخچه مالکیت --}}
    {{-- ============================================= --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">سابقه کامل مالکیت (دفتر کل تغییرناپذیر)</div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>از مالک</th>
                            <th>به مالک</th>
                            <th>نوع رخداد</th>
                            <th>ثبت‌کننده</th>
                            <th>تاریخ اعمال</th>
                            <th>یادداشت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $row)
                            <tr>
                                <td>{{ $row->sequence_no }}</td>
                                <td>{{ $row->from_owner_id ? ('#' . $row->from_owner_id) : '—' }}</td>
                                <td>#{{ $row->to_owner_id }}</td>
                                <td><span class="badge bg-secondary">{{ $row->event_type }}</span></td>
                                <td><small>{{ $row->recorded_by ? ('#' . $row->recorded_by) : 'سیستم' }}</small></td>
                                <td><small>{{ $row->effective_at }}</small></td>
                                <td><small>{{ \Illuminate\Support\Str::limit($row->notes, 60) }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">سابقه‌ای ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
