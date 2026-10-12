@extends('admin.jewelry.layouts.app')

@section('title', 'اختصاص سفارش به تکنسین')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/repair.css') }}">
@endpush

@section('jewelry_content')

{{--
    فرم اختصاص سفارش تعمیر به تکنسین
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    RepairOrderController@assignForm آن را برمی‌گرداند.

    نکته امنیتی: تخصیص تکنسین نیازمند مجوز `jewelry.repairs.assign`
    است که در سطح روت و کنترلر بررسی می‌شود.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">اختصاص سفارش به تکنسین</h4>
    <a href="{{ route('admin.jewelry.repair-orders.show', $repairOrder->id) }}"
       class="btn btn-outline-secondary">بازگشت</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">مشخصات سفارش</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th width="40%">شماره سفارش</th><td>{{ $repairOrder->order_number }}</td></tr>
                    <tr><th>نوع تعمیر</th><td>{{ $repairOrder->repair_type }}</td></tr>
                    <tr><th>شرح مشکل</th><td>{{ \Illuminate\Support\Str::limit($repairOrder->description, 120) }}</td></tr>
                    <tr><th>اولویت</th><td>{{ $repairOrder->priority ?: '—' }}</td></tr>
                    <tr><th>وضعیت</th><td>{{ $repairOrder->status }}</td></tr>
                    <tr><th>مشتری</th><td>{{ optional($repairOrder->user)->name ?: '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">انتخاب تکنسین</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.jewelry.repair-orders.assign', $repairOrder->id) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">تکنسین <span class="text-danger">*</span></label>
                        <select name="technician_id" class="form-select" required>
                            <option value="">— انتخاب کنید —</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}"
                                    @if((string) old('technician_id') === (string) $technician->id) selected @endif>
                                    {{ $technician->name }} @if($technician->email) ({{ $technician->email }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تاریخ تخمینی تکمیل</label>
                        <input type="date" name="estimated_completion_date" class="form-control"
                               value="{{ old('estimated_completion_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">یادداشت اختصاص</label>
                        <textarea name="assignment_notes" class="form-control"
                                  rows="3" maxlength="500">{{ old('assignment_notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        اختصاص سفارش
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
