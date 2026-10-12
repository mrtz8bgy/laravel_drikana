@extends('admin.jewelry.layouts.app')

@section('title', 'ثبت درخواست انتقال مالکیت')

@section('jewelry_content')

{{--
    فرم ثبت درخواست انتقال مالکیت (پنل مدیریت)
    ─────────────────────────────────────────────────────────────
    درخواست در وضعیت «در انتظار تأیید» ثبت می‌شود و مالکیت را
    تغییر نمی‌دهد. تغییر مالکیت فقط پس از تأیید نهایی رخ می‌دهد.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">ثبت درخواست انتقال مالکیت</h4>
    <a href="{{ route('admin.jewelry.transfers.index') }}" class="btn btn-outline-secondary">بازگشت</a>
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

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('admin.jewelry.transfers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-3">
        <div class="card-header">اطلاعات انتقال</div>
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">قطعه (شناسنامه) <span class="text-danger">*</span></label>
                    <select name="certificate_id" class="form-select" required>
                        <option value="">— انتخاب کنید —</option>
                        @foreach($certificates as $certificate)
                            <option value="{{ $certificate->id }}"
                                @if((string) old('certificate_id', isset($certificate) ? $certificate->id : '') === (string) $certificate->id) selected @endif>
                                {{ $certificate->serial_number }} — {{ $certificate->product_name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">فقط قطعاتی نمایش داده می‌شوند که شما به آن‌ها دسترسی دارید.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">مالک جدید <span class="text-danger">*</span></label>
                    <select name="to_owner_id" class="form-select" required>
                        <option value="">— انتخاب کنید —</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" @if(old('to_owner_id') == $owner->id) selected @endif>
                                {{ $owner->full_name }} @if($owner->phone) ({{ $owner->phone }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">نوع انتقال <span class="text-danger">*</span></label>
                    <select name="transfer_type" class="form-select" required>
                        @foreach(['فروش', 'هدیه', 'ارث', 'معاوضه', 'سایر'] as $type)
                            <option value="{{ $type }}" @if(old('transfer_type') === $type) selected @endif>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">تاریخ انتقال <span class="text-danger">*</span></label>
                    <input type="date" name="transfer_date" class="form-control"
                           value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">مبلغ اعلامی (ریال)</label>
                    <input type="number" name="price" class="form-control" min="0" step="1000"
                           value="{{ old('price') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">دلیل و توضیحات درخواست <span class="text-danger">*</span></label>
                    <textarea name="notes" class="form-control" rows="4" maxlength="2000" required>{{ old('notes') }}</textarea>
                    <small class="text-muted">توضیح دهید چرا این انتقال انجام می‌شود. این متن در سابقه ثبت می‌شود.</small>
                </div>

                <div class="col-12">
                    <label class="form-label">مدارک پیوست (اختیاری)</label>
                    <input type="file" name="contract_file" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">فرمت‌های مجاز: PDF، JPG، PNG — حداکثر ۵ مگابایت</small>
                </div>

            </div>

        </div>
    </div>

    <div class="alert alert-info">
        <strong>توجه:</strong>
        ثبت این درخواست مالکیت قطعه را تغییر نمی‌دهد. تغییر مالکیت تنها پس از
        بررسی مدارک و تأیید نهایی توسط فردی غیر از درخواست‌دهنده انجام می‌شود.
    </div>

    <div class="text-end">
        <a href="{{ route('admin.jewelry.transfers.index') }}" class="btn btn-outline-secondary">انصراف</a>
        <button type="submit" class="btn btn-primary">ثبت درخواست</button>
    </div>

</form>

@endsection
