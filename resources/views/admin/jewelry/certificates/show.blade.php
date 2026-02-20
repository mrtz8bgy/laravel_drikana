@extends('admin.jewelry.layouts.app')

@section('title', 'شناسنامه: ' . $certificate->serial_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.certificates.index') }}">شناسنامه‌ها</a></li>
    <li class="breadcrumb-item active">{{ $certificate->serial_number }}</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-certificate"></i>
                شناسنامه شماره: 
                <span class="text-primary">{{ $certificate->serial_number }}</span>
            </h1>
            <div class="btn-group">
                <a href="{{ route('admin.jewelry.certificates.edit', $certificate) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> ویرایش
                </a>
                <a href="{{ route('admin.jewelry.certificates.print', $certificate) }}" 
                   class="btn btn-outline-info" target="_blank">
                    <i class="fas fa-print"></i> چاپ
                </a>
                <a href="{{ route('admin.jewelry.certificates.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-info-circle"></i> اطلاعات شناسنامه
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">شماره سریال:</th>
                            <td>
                                <span class="badge bg-dark fs-6">{{ $certificate->serial_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>نام محصول:</th>
                            <td class="fw-bold">{{ $certificate->product_name }}</td>
                        </tr>
                        <tr>
                            <th>نوع فلز:</th>
                            <td>
                                <span class="badge bg-info">{{ $certificate->metal_type }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>عیار:</th>
                            <td>
                                @if($certificate->purity)
                                    {{ $certificate->purity }} ({{ round($certificate->purity/10, 1) }} عیار)
                                @else
                                    <span class="text-muted">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>وزن:</th>
                            <td>
                                @if($certificate->weight)
                                    {{ number_format($certificate->weight, 2) }} گرم
                                @else
                                    <span class="text-muted">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>نوع سنگ:</th>
                            <td>{{ $certificate->stone_type ?: '<span class="text-muted">ثبت نشده</span>' }}</td>
                        </tr>
                        <tr>
                            <th>تاریخ صدور:</th>
                            <td>
                                @if($certificate->issued_at)
                                    {{ $certificate->issued_at->format('Y/m/d') }}
                                @else
                                    <span class="text-muted">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- مالک -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-user"></i> مالک جواهر
                </div>
                <div class="card-body">
                    @if($certificate->owner)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $certificate->owner->full_name }}</h5>
                                <p class="mb-1">
                                    <i class="fas fa-phone"></i> {{ $certificate->owner->phone }}
                                </p>
                                @if($certificate->owner->national_id)
                                    <p class="mb-1">
                                        <i class="fas fa-id-card"></i> کد ملی: {{ $certificate->owner->national_id }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.jewelry.owners.show', $certificate->owner) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                        
                        @if($certificate->owner->address)
                            <div class="border-top pt-3 mt-3">
                                <h6><i class="fas fa-map-marker-alt"></i> آدرس:</h6>
                                <p class="text-muted mb-0">{{ $certificate->owner->address }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                            <p>مالک مشخص نشده است</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- اطلاعات تکمیلی -->
        <div class="col-md-6">
            <!-- توضیحات -->
            @if($certificate->description)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-sticky-note"></i> توضیحات
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $certificate->description }}</p>
                </div>
            </div>
            @endif

            <!-- فایل ضمیمه -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-file-alt"></i> فایل شناسنامه
                </div>
                <div class="card-body">
                    @if($certificate->certificate_file)
                        <div class="text-center">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                            <p class="mb-3">فایل شناسنامه ضمیمه شده است</p>
                            <div class="btn-group">
                                <a href="{{ Storage::url($certificate->certificate_file) }}" 
                                   class="btn btn-primary" target="_blank">
                                    <i class="fas fa-eye"></i> مشاهده
                                </a>
                                <a href="{{ route('admin.jewelry.certificates.download', $certificate) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-download"></i> دانلود
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-file-excel fa-3x mb-3"></i>
                            <p>فایل شناسنامه ضمیمه نشده است</p>
                            <a href="{{ route('admin.jewelry.certificates.edit', $certificate) }}" 
                               class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-upload"></i> آپلود فایل
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- اطلاعات سیستمی -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-cog"></i> اطلاعات سیستمی
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">شناسه:</th>
                            <td>#{{ $certificate->id }}</td>
                        </tr>
                        <tr>
                            <th>تاریخ ایجاد:</th>
                            <td>{{ $certificate->created_at->format('Y/m/d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخرین ویرایش:</th>
                            <td>{{ $certificate->updated_at->format('Y/m/d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>وضعیت:</th>
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
                        </tr>
                        <tr>
                            <th>تعداد انتقال‌ها:</th>
                            <td>
                                <span class="badge bg-info">{{ $transfers->count() }}</span>
                                انتقال
                            </td>
                        </tr>
                        <tr>
                            <th>تعداد تعمیرات:</th>
                            <td>
                                <span class="badge bg-warning">{{ $repairOrders->count() }}</span>
                                سفارش تعمیر
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- انتقال‌ها -->
    @if($transfers->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-exchange-alt"></i> تاریخچه انتقال‌ها
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>از مالک</th>
                            <th>به مالک</th>
                            <th>تاریخ انتقال</th>
                            <th>علت انتقال</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($transfer->fromOwner)
                                    {{ $transfer->fromOwner->full_name }}
                                @else
                                    <span class="text-muted">سیستم</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->toOwner)
                                    {{ $transfer->toOwner->full_name }}
                                @else
                                    <span class="text-muted">سیستم</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->transfer_date)
                                    {{ $transfer->transfer_date->format('Y/m/d') }}
                                @endif
                            </td>
                            <td>{{ $transfer->reason ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- سفارشات تعمیر -->
    @if($repairOrders->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-tools"></i> تاریخچه تعمیرات
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تاریخ ثبت</th>
                            <th>شرح مشکل</th>
                            <th>وضعیت</th>
                            <th>هزینه</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repairOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($order->problem_description, 50) }}</td>
                            <td>
                                @php
                                    $statusColor = [
                                        'در انتظار' => 'warning',
                                        'در حال تعمیر' => 'info',
                                        'تکمیل شده' => 'success',
                                        'لغو شده' => 'danger'
                                    ][$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                @if($order->cost)
                                    {{ number_format($order->cost) }} تومان
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- دکمه‌های اقدام -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="btn-group">
                    <a href="{{ route('admin.jewelry.certificates.edit', $certificate) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit"></i> ویرایش شناسنامه
                    </a>
                    
                    @if($certificate->certificate_file)
                    <a href="{{ route('admin.jewelry.certificates.download', $certificate) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-download"></i> دانلود فایل
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.jewelry.certificates.print', $certificate) }}" 
                       class="btn btn-outline-info" target="_blank">
                        <i class="fas fa-print"></i> چاپ شناسنامه
                    </a>
                </div>
                
                <div class="btn-group">
                    <form action="{{ route('admin.jewelry.certificates.destroy', $certificate) }}" 
                          method="POST" 
                          onsubmit="return confirm('آیا از حذف این شناسنامه مطمئن هستید؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف شناسنامه
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.jewelry.certificates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> بازگشت به لیست
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
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
</style>
@endpush
@endsection