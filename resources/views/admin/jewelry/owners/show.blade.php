@php
use Illuminate\Support\Str;
@endphp

@extends('admin.jewelry.layouts.app')

@section('title', 'جزئیات مالک: ' . $owner->full_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.owners.index') }}">مالکان</a></li>
    <li class="breadcrumb-item active">{{ $owner->full_name }}</li>
@endsection

@section('jewelry_content')
<div class="container-fluid owner-details-page">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>جزئیات مالک: {{ $owner->full_name }}</h1>
            <div class="owner-page-actions">
                <a href="{{ route('admin.jewelry.owners.edit', $owner) }}" class="btn owner-action owner-action-primary">
                    <i class="fas fa-edit"></i> ویرایش
                </a>
                <a href="{{ route('admin.jewelry.owners.index') }}" class="btn owner-action owner-action-secondary">
                    <i class="fas fa-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- اطلاعات اصلی -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-person-badge"></i> اطلاعات شخصی
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%"><i class="fas fa-user-circle me-2"></i>نام کامل:</th>
                            <td class="fw-bold">{{ $owner->full_name }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-credit-card me-2"></i>کد ملی:</th>
                            <td>
                                @if($owner->national_id)
                                    {{ $owner->national_id }}
                                @else
                                    <span class="text-muted fst-italic">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2"></i>تلفن:</th>
                            <td>
                                <a href="tel:{{ $owner->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone"></i> {{ $owner->phone }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope me-2"></i>ایمیل:</th>
                            <td>
                                @if($owner->email)
                                    <a href="mailto:{{ $owner->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope-at"></i> {{ $owner->email }}
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt me-2"></i>آدرس:</th>
                            <td>
                                @if($owner->address)
                                    <i class="fas fa-house-door"></i> {{ $owner->address }}
                                @else
                                    <span class="text-muted fst-italic">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- اطلاعات تکمیلی -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> اطلاعات سیستمی
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%"><i class="fas fa-calendar-plus me-2"></i>تاریخ ثبت:</th>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($owner->created_at)->format('Y/m/d') }}
                                </span>
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($owner->created_at)->format('H:i') }})
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-check me-2"></i>آخرین ویرایش:</th>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($owner->updated_at)->format('Y/m/d') }}
                                </span>
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($owner->updated_at)->format('H:i') }})
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-file-text me-2"></i>تعداد شناسنامه:</th>
                            <td>
                                <span class="badge bg-primary fs-6">{{ $certificates->total() }}</span>
                                شناسنامه
                            </td>
                        </tr>
                        @if(isset($owner->notes) && $owner->notes)
                        <tr>
                            <th><i class="fas fa-sticky-note me-2"></i>یادداشت:</th>
                            <td>{{ $owner->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- عملیات سریع -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-bolt"></i> عملیات سریع
                </div>
                <div class="card-body">
                    <div class="owner-actions">
                        <a href="{{ route('admin.jewelry.certificates.create') }}?owner_id={{ $owner->id }}" 
                           class="btn owner-action owner-action-success">
                            <i class="fas fa-plus-circle"></i> ایجاد شناسنامه جدید
                        </a>
                        @if(isset($owner->phone))
                        <a href="tel:{{ $owner->phone }}" class="btn owner-action owner-action-outline-primary">
                            <i class="fas fa-phone"></i> تماس با مالک
                        </a>
                        @endif
                        @if(isset($owner->email))
                        <a href="mailto:{{ $owner->email }}" class="btn owner-action owner-action-outline-info">
                            <i class="fas fa-envelope"></i> ارسال ایمیل
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- شناسنامه‌های مرتبط -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-certificate"></i> شناسنامه‌های مالک
                <small class="ms-2">({{ $certificates->total() }} مورد)</small>
            </h5>
            <div>
                <a href="{{ route('admin.jewelry.certificates.create') }}?owner_id={{ $owner->id }}" 
                   class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> جدید
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($certificates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th width="50">#</th>
                                <th>نام محصول</th>
                                <th>شماره سریال</th>
                                <th>نوع جواهر</th>
                                <th>تاریخ صدور</th>
                                <th>وضعیت</th>
                                <th width="150">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                            <tr>
                                <td>{{ $loop->iteration + (($certificates->currentPage() - 1) * $certificates->perPage()) }}</td>
                                <td>
                                    <strong>{{ $certificate->product_name }}</strong>
                                    @if($certificate->description)
                                        <br>
                                        <small class="text-muted">
                                            {{ Str::limit($certificate->description, 30) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace">{{ $certificate->serial_number }}</span>
                                </td>
                                <td>
                                    @if($certificate->metal_type)
                                        <span class="badge bg-info">{{ $certificate->metal_type }}</span>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($certificate->issued_at)
                                        {{ \Carbon\Carbon::parse($certificate->issued_at)->format('Y/m/d') }}
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isActive = isset($certificate->is_active) ? $certificate->is_active : true;
                                    @endphp
                                    @if($isActive)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> فعال
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> غیرفعال
                                        </span>
                                    @endif
                                </td>
                                <td>
                                     <div class="owner-certificate-actions" role="group">
                                        <a href="{{ route('admin.jewelry.certificates.show', $certificate) }}" 
                                         class="btn btn-sm owner-action-outline-primary" 
                                           data-bs-toggle="tooltip" title="مشاهده">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.jewelry.certificates.edit', $certificate) }}" 
                                                         class="btn btn-sm owner-action-outline-warning"
                                           data-bs-toggle="tooltip" title="ویرایش">
                                            <i class="fas fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.jewelry.certificates.print', $certificate) }}" 
                                                         class="btn btn-sm owner-action-outline-info"
                                           data-bs-toggle="tooltip" title="چاپ"
                                           target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('admin.jewelry.certificates.destroy', $certificate) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm owner-action-outline-danger"
                                                    data-bs-toggle="tooltip" title="حذف"
                                                    onclick="return confirm('آیا از حذف شناسنامه «{{ $certificate->serial_number }}» مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($certificates->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation">
                            {{ $certificates->links() }}
                        </nav>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-file-excel fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">هیچ شناسنامه‌ای برای این مالک ثبت نشده است</h5>
                    <p class="text-muted mb-4">می‌توانید اولین شناسنامه جواهر را برای این مالک ایجاد کنید</p>
                    <a href="{{ route('admin.jewelry.certificates.create') }}?owner_id={{ $owner->id }}" 
                       class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i> ایجاد اولین شناسنامه
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .owner-details-page .owner-page-actions,
    .owner-details-page .owner-actions,
    .owner-details-page .owner-certificate-actions {
        display: flex !important;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .owner-details-page .owner-actions {
        flex-direction: column;
        align-items: stretch;
    }
    .owner-details-page .owner-certificate-actions form {
        display: inline-block;
        margin: 0;
    }
    .owner-details-page .owner-action,
    .owner-details-page .owner-certificate-actions .btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        visibility: visible !important;
        opacity: 1 !important;
        min-height: 38px;
        white-space: nowrap;
        text-decoration: none !important;
    }
    .owner-details-page .owner-actions .owner-action {
        width: 100%;
    }
    .owner-details-page .btn-sm {
        min-height: 32px;
        padding: 6px 10px;
        font-size: 0.875rem;
    }
    .owner-details-page .owner-action-primary,
    .owner-details-page .owner-action-success {
        color: #fff !important;
        background: #198754 !important;
        border: 1px solid #157347 !important;
    }
    .owner-details-page .owner-action-primary {
        background: #0d6efd !important;
        border-color: #0a58ca !important;
    }
    .owner-details-page .owner-action-secondary {
        color: #fff !important;
        background: #6c757d !important;
        border: 1px solid #5c636a !important;
    }
    .owner-details-page .owner-action-outline-primary,
    .owner-details-page .owner-action-outline-info,
    .owner-details-page .owner-action-outline-warning,
    .owner-details-page .owner-action-outline-danger {
        background: #fff !important;
        border: 1px solid currentColor !important;
    }
    .owner-details-page .owner-action-outline-primary { color: #0d6efd !important; }
    .owner-details-page .owner-action-outline-info { color: #087990 !important; }
    .owner-details-page .owner-action-outline-warning { color: #997404 !important; }
    .owner-details-page .owner-action-outline-danger { color: #dc3545 !important; }
    .owner-details-page .owner-action:hover,
    .owner-details-page .owner-certificate-actions .btn:hover {
        filter: brightness(0.94);
        transform: translateY(-1px);
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        font-weight: 600;
    }
    .table-borderless th {
        color: #6c757d;
        font-weight: 600;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .fst-italic {
        font-style: italic;
    }
</style>
@endpush

@push('scripts')
<script>
    // فعال‌سازی tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // تایید حذف
    function confirmDelete(certificateName) {
        return confirm('آیا از حذف شناسنامه «' + certificateName + '» مطمئن هستید؟');
    }
</script>
@endpush
@endsection