@extends('layouts.app')

@section('content')

{{-- فرم ویرایش نقش — فایل کامل در admin/roles/_form.blade.php --}}

@include('admin.roles._form', ['role' => $role])

@endsection
