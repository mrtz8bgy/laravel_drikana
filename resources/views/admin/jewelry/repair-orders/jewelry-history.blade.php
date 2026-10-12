@extends('admin.jewelry.layouts.app')

@section('title', 'تاریخچه تعمیرات قطعه')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/repair.css') }}">
@endpush

@section('jewelry_content')

{{--
    تاریخچه کامل تعمیرات یک قطعه جواهر
    ─────────────────────────────────────────────────────────────
    نمایش تمام سفارش‌های تعمیر مرتبط با یک شناسنامه مشخص، همراه
    با تکنسین، هزینه و وضعیت هر سفارش.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">تاریخچه تعمیرات قطعه</h4>
    <a href="{{ route('admin.jewelry.repair-orders.index') }}" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="card mb-3">
    <div class="card-header">مشخصات قطعه</div>
    <div class="card-body">
        <table class="table table-sm mb-0">
            <tr><th width="25%">شناسه</th><td>#{{ $certificate->id }}</td></tr>
            <tr><th>شماره سری</th><td>{{ $certificate->serial_number }}</td></tr>
            <tr><th>نام قطعه</th><td>{{ $certificate->product_name }}</td></tr>
            <tr><th>فلز / عیار</th><td>{{ $certificate->metal_type }} / {{ $certificate->purity }}</td></tr>
            <tr><th>وزن</th><td>{{ $certificate->weight }} گرم</td></tr>
            <tr><th>مالک</th><td>{{ optional($certificate->owner)->full_name ?: '—' }}</td></tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        سفارش‌های تعمیر
        <span class="badge bg-secondary">{{ $repairOrders->total() }}</span>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>شماره سفارش</th>
                    <th>نوع تعمیر</th>
                    <th>تکنسین</th>
                    <th>هزینه نهایی</th>
                    <th>وضعیت</th>
                    <th>تاریخ ثبت</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($repairOrders as $key => $order)
                    <tr>
                        <td>{{ $repairOrders->firstItem() + $key }}</td>
                        <td><code>{{ $order->order_number }}</code></td>
                        <td><small>{{ $order->repair_type }}</small></td>
                        <td><small>{{ optional($order->technician)->name ?: '—' }}</small></td>
                        <td>
                            <small>
                                {{ $order->final_cost ? number_format($order->final_cost) : ($order->estimated_cost ? number_format($order->estimated_cost) . ' (برآورد)' : '—') }}
                            </small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                        <td><small>{{ $order->created_at }}</small></td>
                        <td>
                            <a href="{{ route('admin.jewelry.repair-orders.show', $order->id) }}"
                               class="btn btn-sm btn-outline-primary">مشاهده</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            هیچ سفارش تعمیری برای این قطعه ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $repairOrders->links() }}
        </div>
    </div>
</div>

@endsection
