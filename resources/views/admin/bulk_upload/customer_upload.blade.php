@extends('layouts.app')

@section('content')

{{--
    بارگذاری گروهی مشتریان
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    CustomerBulkUploadController@index و روت customer_bulk_upload.index
    آن را برمی‌گرداندند.

    نکته امنیتی: نوع فایل آپلودی در سمت سرور با کتابخانه Excel بررسی
    می‌شود؛ ولی در فرم نیز با accept محدود شده است.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3>{{ __('Bulk Customer Upload') }}</h3>
    </div>
</div>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="u-margin-0-padding-right-20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('Upload Customer Excel File') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('bulk_customer_upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="customer_bulk_file">{{ __('Excel File') }}</label>
                        <input type="file" id="customer_bulk_file" name="customer_bulk_file"
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                        <small class="text-muted">
                            {{ __('فرمت‌های مجاز: xls، xlsx، csv') }}
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('Download Sample Files') }}</h3>
            </div>
            <div class="panel-body">
                <a href="{{ route('customer_bulk_upload.index') }}" class="btn btn-default">
                    {{ __('Refresh') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
