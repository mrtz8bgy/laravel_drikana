{{-- resources/views/admin/jewelry/owners/create.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<div class="container">
    <h2 class="mb-4">ایجاد مالک جدید</h2>

    <form action="{{ route('admin.jewelry.owners.store') }}" method="POST">
        @csrf

        @include('admin.jewelry.owners.form')
    </form>
</div>
@endsection
