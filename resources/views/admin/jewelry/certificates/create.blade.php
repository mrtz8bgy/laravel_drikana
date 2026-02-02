{{-- resources/views/admin/jewelry/certificates/create.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'ثبت شناسنامه جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.certificates.index') }}">شناسنامه‌ها</a></li>
    <li class="breadcrumb-item active">ثبت جدید</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="page-header">
        <h1>
            <i class="fas fa-plus-circle"></i>
            ثبت شناسنامه جدید
        </h1>
        <p class="text-muted">برای ثبت شناسنامه جدید جواهر، فرم زیر را تکمیل کنید.</p>
    </div>

    <form method="POST" 
          action="{{ route('admin.jewelry.certificates.store') }}" 
          enctype="multipart/form-data">
        @include('admin.jewelry.certificates.form')
    </form>
</div>
@endsection