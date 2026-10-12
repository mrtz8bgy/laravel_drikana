@extends('layouts.app')

@section('content')

{{-- فرم ایجاد نقش جدید — فایل کامل در admin/roles/_form.blade.php --}}

@include('admin.roles._form', ['role' => null])

@endsection
