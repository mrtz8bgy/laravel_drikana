{{-- resources/views/admin/jewelry/owners/index.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<div class="owner-list-page">
<div class="owner-list-header">
    <h2>مالکان جواهرات</h2>
    <a href="{{ route('admin.jewelry.owners.create') }}" class="btn owner-list-add">افزودن مالک جدید</a>
</div>

{{-- پیام‌های موفقیت یا خطا --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- جدول مالکان --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped owner-list-table">
        <thead>
            <tr>
                <th>#</th>
                <th>نام و نام خانوادگی</th>
                <th>کد ملی</th>
                <th>تلفن</th>
                <th>تعداد شناسنامه‌ها</th>
                <th>اقدامات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
                <tr>
                    <td>{{ $loop->iteration + ($owners->currentPage() - 1) * $owners->perPage() }}</td>
                    <td>{{ $owner->full_name }}</td>
                    <td>{{ $owner->national_id ?? '-' }}</td>
                    <td>{{ $owner->phone }}</td>
                    <td>{{ $owner->certificates_count }}</td>
                    <td class="owner-list-actions-cell">
                        <div class="owner-list-actions">
                        <a href="{{ route('admin.jewelry.owners.show', $owner) }}" class="btn owner-list-action owner-list-view">نمایش</a>
                        <a href="{{ route('admin.jewelry.owners.edit', $owner) }}" class="btn owner-list-action owner-list-edit">ویرایش</a>
                        <form action="{{ route('admin.jewelry.owners.destroy', $owner) }}" method="POST" class="owner-list-delete-form" onsubmit="return confirm('آیا مطمئن هستید؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn owner-list-action owner-list-delete">حذف</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">هیچ مالک جواهری ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center owner-list-pagination">
    {{ $owners->links() }}
</div>
</div>

@push('styles')
{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/jewelry-owners.css') }}">
@endpush
@endpush
@endsection
