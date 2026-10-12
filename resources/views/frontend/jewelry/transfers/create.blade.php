@extends('frontend.layouts.app')

@section('meta_title', __('درخواست انتقال مالکیت'))
@section('content')

{{--
    فرم درخواست انتقال مالکیت توسط مالک فعلی
    ─────────────────────────────────────────────────────────────
    مالکیت قطعه در کنترلر بررسی شده است؛ کاربر تنها می‌تواند برای
    قطعه‌ای که واقعاً مالک آن است درخواست ثبت کند.
--}}

<div class="container" style="padding: 24px 15px;">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>{{ __('درخواست انتقال مالکیت') }}</h2>
            <a href="{{ route('my-jewelry.dashboard') }}" class="btn btn-default btn-sm">
                {{ __('بازگشت') }}
            </a>

            <br><br>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-right: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('مشخصات قطعه') }}</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" style="margin: 0;">
                        <tr><th width="35%">{{ __('شماره سری') }}</th><td>{{ $certificate->serial_number }}</td></tr>
                        <tr><th>{{ __('نام قطعه') }}</th><td>{{ $certificate->product_name }}</td></tr>
                        <tr><th>{{ __('فلز / عیار') }}</th><td>{{ $certificate->metal_type }} / {{ $certificate->purity }}</td></tr>
                        <tr><th>{{ __('وزن') }}</th><td>{{ $certificate->weight }} گرم</td></tr>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('اطلاعات انتقال') }}</strong>
                </div>
                <div class="panel-body">
                    <form action="{{ route('my-jewelry.transfers.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">

                        <div class="form-group">
                            <label>{{ __('مالک جدید') }} <span class="text-danger">*</span></label>
                            <select name="to_owner_id" class="form-control" required>
                                <option value="">{{ __('— انتخاب کنید —') }}</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" @if(old('to_owner_id') == $owner->id) selected @endif>
                                        {{ $owner->full_name }} @if($owner->phone) ({{ $owner->phone }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                {{ __('اگر مالک مورد نظر در فهرست نیست، ابتدا باید توسط پشتیبانی ثبت شود.') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('نوع انتقال') }} <span class="text-danger">*</span></label>
                            <select name="transfer_type" class="form-control" required>
                                @foreach(['فروش', 'هدیه', 'ارث', 'معاوضه', 'سایر'] as $type)
                                    <option value="{{ $type }}" @if(old('transfer_type') === $type) selected @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('تاریخ انتقال') }} <span class="text-danger">*</span></label>
                            <input type="date" name="transfer_date" class="form-control"
                                   value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('دلیل و توضیحات') }} <span class="text-danger">*</span></label>
                            <textarea name="notes" class="form-control" rows="4" maxlength="2000" required>{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-info">
                            {{ __('پس از ثبت، درخواست شما در وضعیت «در انتظار تأیید» قرار می‌گیرد. تا زمان تأیید، مالکیت قطعه تغییر نمی‌کند و شما می‌توانید درخواست را لغو کنید.') }}
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('ثبت درخواست انتقال') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
