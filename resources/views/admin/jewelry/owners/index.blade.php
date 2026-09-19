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
<style>
    .owner-list-page .owner-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .owner-list-page .owner-list-header h2 {
        margin: 0;
        color: #07091a;
        font-weight: 800;
    }
    .owner-list-page .owner-list-add,
    .owner-list-page .owner-list-action {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 13px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        text-decoration: none !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    .owner-list-page .owner-list-add {
        color: #fff !important;
        background: linear-gradient(135deg, #d4af37, #b8892a) !important;
        border: 1px solid #b8892a !important;
    }
    .owner-list-page .owner-list-table th,
    .owner-list-page .owner-list-table td {
        vertical-align: middle;
    }
    .owner-list-page .owner-list-actions-cell {
        min-width: 220px;
    }
    .owner-list-page .owner-list-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .owner-list-page .owner-list-delete-form {
        display: inline-flex;
        margin: 0;
    }
    .owner-list-page .owner-list-view {
        color: #fff !important;
        background: #087990 !important;
        border: 1px solid #087990 !important;
    }
    .owner-list-page .owner-list-edit {
        color: #664d03 !important;
        background: #ffda6a !important;
        border: 1px solid #ffcd39 !important;
    }
    .owner-list-page .owner-list-delete {
        color: #fff !important;
        background: #dc3545 !important;
        border: 1px solid #b02a37 !important;
    }
    .owner-list-page .owner-list-action:hover,
    .owner-list-page .owner-list-add:hover {
        filter: brightness(0.94);
        transform: translateY(-1px);
    }
    .owner-list-page .owner-list-pagination {
        margin-top: 20px;
    }
    @media (max-width: 576px) {
        .owner-list-page .owner-list-header {
            align-items: stretch;
            flex-direction: column;
        }
        .owner-list-page .owner-list-add {
            width: 100%;
        }
        .owner-list-page .owner-list-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush
@endsection
