@extends('layouts.app')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{-- ایجاد مقاله جدید — فرم در admin/articles/_form.blade.php --}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('ایجاد مقاله جدید') }}</h3>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
        </a>
    </div>
</div>

<br>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @include('admin.articles._form', ['article' => null, 'categories' => $categories])

    <div class="row">
        <div class="col-sm-12 text-left">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-default">{{ __('انصراف') }}</a>
            <button type="submit" class="btn btn-primary">
                <i class="demo-pli-check"></i> {{ __('ذخیره پیش‌نویس') }}
            </button>
        </div>
    </div>
</form>

@endsection
