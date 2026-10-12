@extends('admin.jewelry.layouts.app')

@section('title', 'تاریخچه وضعیت سفارش')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/repair.css') }}">
@endpush

@section('jewelry_content')

{{--
    تاریخچه تغییر وضعیت‌های یک سفارش تعمیر
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    RepairOrderController@statusHistory آن را برمی‌گرداند.

    همچنین جدول `repair_order_status_logs` در پروژه ساخته نشده بود و
    کنترلر با try/catch آن را نادیده می‌گرفت؛ یعنی تاریخچه وضعیت‌ها
    هرگز ثبت نمی‌شد. این جدول در مایگریشن‌های جدید ایجاد شده است.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">تاریخچه وضعیت سفارش</h4>
    <a href="{{ route('admin.jewelry.repair-orders.show', $repairOrder->id) }}"
       class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="card mb-3">
    <div class="card-header">مشخصات سفارش</div>
    <div class="card-body">
        <table class="table table-sm mb-0">
            <tr><th width="25%">شماره سفارش</th><td>{{ $repairOrder->order_number }}</td></tr>
            <tr><th>نوع تعمیر</th><td>{{ $repairOrder->repair_type }}</td></tr>
            <tr><th>مشتری</th><td>{{ optional($repairOrder->user)->name ?: '—' }}</td></tr>
            <tr>
                <th>وضعیت فعلی</th>
                <td><span class="badge bg-info">{{ $repairOrder->status }}</span></td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        تغییرات ثبت‌شده
        <span class="badge bg-secondary">{{ $statusHistory->count() }}</span>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th width="6%">#</th>
                    <th width="16%">وضعیت پیشین</th>
                    <th width="16%">وضعیت جدید</th>
                    <th width="18%">تغییردهنده</th>
                    <th width="14%">زمان</th>
                    <th>یادداشت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statusHistory as $key => $row)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><small>{{ $row->old_status ?: '—' }}</small></td>
                        <td><small>{{ $row->new_status ?: '—' }}</small></td>
                        <td><small>{{ $row->changer_name ?: '—' }}</small></td>
                        <td><small>{{ $row->created_at }}</small></td>
                        <td><small>{{ $row->notes ?: '—' }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            هیچ تغییری در وضعیت این سفارش ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
