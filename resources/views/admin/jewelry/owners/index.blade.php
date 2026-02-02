{{-- resources/views/admin/jewelry/owners/index.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<div class="d-flex justify-content-between mb-3">
    <h2>مالکان جواهرات</h2>
    <a href="{{ route('admin.jewelry.owners.create') }}" class="btn btn-primary">افزودن مالک جدید</a>
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
    <table class="table table-bordered table-striped">
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
                    <td>
                        <a href="{{ route('admin.jewelry.owners.show', $owner) }}" class="btn btn-sm btn-info">نمایش</a>
                        <a href="{{ route('admin.jewelry.owners.edit', $owner) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('admin.jewelry.owners.destroy', $owner) }}" method="POST" class="d-inline-block" onsubmit="return confirm('آیا مطمئن هستید؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
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

{{-- صفحه‌بندی --}}
<div class="d-flex justify-content-center">
    {{ $owners->links() }}
</div>
@endsection
