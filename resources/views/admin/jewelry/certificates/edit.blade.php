{{-- resources/views/admin/jewelry/certificates/edit.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'ویرایش شناسنامه: ' . $certificate->serial_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.certificates.index') }}">شناسنامه‌ها</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.certificates.show', $certificate) }}">{{ $certificate->serial_number }}</a></li>
    <li class="breadcrumb-item active">ویرایش</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="page-header">
        <h1>
            <i class="fas fa-edit"></i>
            ویرایش شناسنامه
            <small class="text-muted">{{ $certificate->serial_number }}</small>
        </h1>
    </div>

    <form method="POST" 
          action="{{ route('admin.jewelry.certificates.update', $certificate) }}" 
          enctype="multipart/form-data">
        @include('admin.jewelry.certificates.form')
    </form>
</div>
@endsection