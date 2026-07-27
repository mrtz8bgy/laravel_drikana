@extends('admin.jewelry.layouts.app')

@php
    // تعریف متغیرها برای اطمینان
    if (!isset($metalTypes)) {
        $metalTypes = ['طلا', 'نقره', 'پلاتین', 'سایر'];
    }
    if (!isset($stoneTypes)) {
        $stoneTypes = ['الماس', 'یاقوت', 'زمررد', 'یاقوت کبود', 'مروارید', 'فیروزه', 'عقیق', 'سایر'];
    }
    if (!isset($owners)) {
        $owners = \App\Models\JewelryOwner::all();
    }
@endphp

@section('title', 'مدیریت شناسنامه‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">شناسنامه‌ها</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-certificate"></i>
                مدیریت شناسنامه‌ها
            </h1>
            <a href="{{ route('admin.jewelry.certificates.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> شناسنامه جدید
            </a>
        </div>
    </div>

    <!-- فیلتر و جستجو (بدون فیلتر is_active) -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <i class="fas fa-filter"></i> فیلتر و جستجو
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jewelry.certificates.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="search" class="form-label">جستجو</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="شماره سریال، نام محصول، مالک...">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="owner_id" class="form-label">مالک</label>
                            <select class="form-control" id="owner_id" name="owner_id">
                                <option value="">همه مالکان</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->full_name }} - {{ $owner->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="metal_type" class="form-label">نوع فلز</label>
                            <select class="form-control" id="metal_type" name="metal_type">
                                <option value="all">همه انواع</option>
                                @foreach($metalTypes as $type)
                                    <option value="{{ $type }}" {{ request('metal_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="sort_by" class="form-label">مرتب‌سازی</label>
                            <select class="form-control" id="sort_by" name="sort_by">
                                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>تاریخ ایجاد</option>
                                <option value="serial_number" {{ request('sort_by') == 'serial_number' ? 'selected' : '' }}>شماره سریال</option>
                                <option value="product_name" {{ request('sort_by') == 'product_name' ? 'selected' : '' }}>نام محصول</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">ترتیب</label>
                            <select class="form-control" id="sort_order" name="sort_order">
                                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>نزولی (جدیدترین)</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>صعودی (قدیمی‌ترین)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="mb-3 w-100">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> اعمال فیلتر
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- آمار کلی (بدون is_active) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کل شناسنامه‌ها</h6>
                            <h3 class="mb-0">{{ $certificates->total() }}</h3>
                        </div>
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">شناسنامه‌های طلا</h6>
                            <h3 class="mb-0">
                                @php
                                    $goldCount = collect($certificates->items())->where('metal_type', 'طلا')->count();
                                    echo $goldCount;
                                @endphp
                            </h3>
                        </div>
                        <i class="fas fa-gem fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">شناسنامه‌های نقره</h6>
                            <h3 class="mb-0">
                                @php
                                    $silverCount = collect($certificates->items())->where('metal_type', 'نقره')->count();
                                    echo $silverCount;
                                @endphp
                            </h3>
                        </div>
                        <i class="fas fa-ring fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">شناسنامه‌های دارای عکس</h6>
                            <h3 class="mb-0">
                                @php
                                    $withImageCount = collect($certificates->items())->filter(function($cert) {
                                        return !empty($cert->certificate_file);
                                    })->count();
                                    echo $withImageCount;
                                @endphp
                            </h3>
                        </div>
                        <i class="fas fa-camera fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول شناسنامه‌ها -->
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list"></i>
                لیست شناسنامه‌ها
                <small class="ms-2">({{ $certificates->total() }} مورد)</small>
            </h5>
            <div>
                <a href="{{ route('admin.jewelry.certificates.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> جدید
                </a>
            </div>
        </div>
        
        <div class="card-body">
            @if($certificates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>شماره سریال</th>
                                <th>نام محصول</th>
                                <th>نوع فلز</th>
                                <th>مالک</th>
                                <th>تاریخ صدور</th>
                                <th>عکس</th>
                                <th width="200"><td>عملیات</td></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                            <tr>
                                <td>{{ $loop->iteration + (($certificates->currentPage() - 1) * $certificates->perPage()) }}</td>
                                <td>
                                    <strong class="font-monospace">{{ $certificate->serial_number }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $certificate->product_name }}</strong>
                                    @if($certificate->stone_type)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-gem"></i> {{ $certificate->stone_type }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $certificate->metal_type }}</span>
                                    @if($certificate->purity)
                                        <br>
                                        <small class="text-muted">{{ $certificate->purity }} ({{ round($certificate->purity/10, 1) }} عیار)</small>
                                    @endif
                                </td>
                                <td>
                                    @if($certificate->owner)
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div>{{ $certificate->owner->full_name }}</div>
                                                <small class="text-muted">{{ $certificate->owner->phone }}</small>
                                            </div>
                                            <a href="{{ route('admin.jewelry.owners.show', $certificate->owner) }}" 
                                               class="btn btn-sm btn-outline-primary ms-2" 
                                               title="مشاهده مالک">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted">ندارد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($certificate->issued_at)
                                        {{ $certificate->issued_at->format('Y/m/d') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($certificate->certificate_file)
                                        @php
                                            $extension = pathinfo($certificate->certificate_file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                        @endphp
                                        @if($isImage)
                                            <span class="badge bg-success">
                                                <i class="fas fa-camera"></i> دارد
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-file"></i> فایل
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-times"></i> ندارد
                                        </span>
                                    @endif
                                </td>
                                <td><th>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.jewelry.certificates.show', $certificate) }}" 
                                           class="btn btn-info" title="مشاهده">
                                            <i class="fas fa-eye"></i>مشاهده
                                        </a>
                                        <a href="{{ route('admin.jewelry.certificates.edit', $certificate) }}" 
                                           class="btn btn-warning" title="ویرایش">
                                            <i class="fas fa-edit"></i>ویرایش
                                        </a>
                                        <a href="{{ route('admin.jewelry.certificates.print', $certificate) }}" 
                                           class="btn btn-outline-info" title="چاپ" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('admin.jewelry.certificates.destroy', $certificate) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    title="حذف"
                                                    onclick="return confirm('آیا از حذف شناسنامه «{{ $certificate->serial_number }}» مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i>حذف
                                            </button>
                                        </form>
                                    </div>
</th>
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
                            {{ $certificates->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-certificate fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">هیچ شناسنامه‌ای ثبت نشده است</h5>
                    <p class="text-muted mb-4">می‌توانید اولین شناسنامه جواهر را ایجاد کنید</p>
                    <a href="{{ route('admin.jewelry.certificates.create') }}" 
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
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .badge {
        font-weight: 500;
        padding: 5px 10px;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection