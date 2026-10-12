@extends('admin.jewelry.layouts.app')

@section('title', 'تاریخچه انتقالات شناسنامه')

@section('jewelry_content')

{{--
    تاریخچه انتقالات یک شناسنامه
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    JewelryCertificateController@transferHistory آن را برمی‌گرداند.

    نکته: سابقه مالکیت تغییرناپذیر است و تنها از طریق ثبت رکورد
    جدید قابل اصلاح است؛ نه با ویرایش یا حذف رکوردهای پیشین.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">تاریخچه انتقالات شناسنامه</h4>
    <a href="{{ route('admin.jewelry.certificates.show', $certificate->id) }}"
       class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="card mb-3">
    <div class="card-header">مشخصات شناسنامه</div>
    <div class="card-body">
        <table class="table table-sm mb-0">
            <tr><th width="25%">شناسه</th><td>#{{ $certificate->id }}</td></tr>
            <tr><th>شماره سری</th><td>{{ $certificate->serial_number }}</td></tr>
            <tr><th>نام قطعه</th><td>{{ $certificate->product_name }}</td></tr>
            <tr><th>مالک فعلی</th><td>{{ optional($certificate->owner)->full_name ?: '—' }}</td></tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        فهرست انتقالات
        <span class="badge bg-secondary">{{ $transfers->count() }}</span>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>از مالک</th>
                    <th>به مالک</th>
                    <th>نوع انتقال</th>
                    <th>مبلغ</th>
                    <th>تاریخ انتقال</th>
                    <th>وضعیت</th>
                    <th>تأییدکننده</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $key => $transfer)
                    <tr>
                        <td>{{ $key + 1 }}</td>
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
                        <td><small>{{ optional($transfer->approver)->name ?: '—' }}</small></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">هیچ انتقالی ثبت نشده است.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
