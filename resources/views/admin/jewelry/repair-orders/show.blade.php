{{-- resources/views/admin/jewelry/repair-orders/show.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'جزییات سفارش تعمیر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.repair-orders.index') }}">سفارشات تعمیر</a></li>
    <li class="breadcrumb-item active">جزییات سفارش {{ $repairOrder->order_number ?? '---' }}</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            {{-- پیام موفقیت --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- هدر سفارش --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        سفارش تعمیر {{ $repairOrder->order_number ?? '---' }}
                    </h4>
                    <div>
                        <span class="badge bg-{{ $repairOrder->status_color ?? 'secondary' }} p-2 fs-6">
                            {{ $repairOrder->status_label ?? 'نامشخص' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- ستون راست: اطلاعات سفارش --}}
                <div class="col-md-6">
                    {{-- کارت اطلاعات سفارش --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>اطلاعات سفارش</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="180">شماره سفارش</th>
                                    <td>{{ $repairOrder->order_number ?? '---' }}</td>
                                </tr>
                                <tr>
                                    <th>نوع تعمیر</th>
                                    <td>
                                        @if(!empty($repairOrder->repair_type))
                                            @if($repairOrder->repair_type == 'cleaning')
                                                تمیزکاری
                                            @elseif($repairOrder->repair_type == 'restoration')
                                                بازسازی
                                            @elseif($repairOrder->repair_type == 'resizing')
                                                تغییر اندازه
                                            @elseif($repairOrder->repair_type == 'other')
                                                سایر
                                            @else
                                                {{ $repairOrder->repair_type }}
                                            @endif
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ درخواست</th>
                                    <td>{{ $repairOrder->created_at ? jdate($repairOrder->created_at)->format('Y/m/d') : '---' }}</td>
                                </tr>
                                <tr>
                                    <th>تاریخ برنامه‌ریزی</th>
                                    <td>
                                        @if(!empty($repairOrder->scheduled_date))
                                            {{ jdate($repairOrder->scheduled_date)->format('Y/m/d') }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ تکمیل</th>
                                    <td>
                                        @if(!empty($repairOrder->completion_date))
                                            {{ jdate($repairOrder->completion_date)->format('Y/m/d') }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ تحویل</th>
                                    <td>
                                        @if(!empty($repairOrder->delivery_date))
                                            {{ jdate($repairOrder->delivery_date)->format('Y/m/d') }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>مکان تعمیر</th>
                                    <td>
                                        @if(!empty($repairOrder->location))
                                            @if($repairOrder->location == 'workshop')
                                                <span class="badge bg-info">کارگاه</span>
                                            @elseif($repairOrder->location == 'customer')
                                                <span class="badge bg-success">محل مشتری</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $repairOrder->location }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">تعیین نشده</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>اولویت</th>
                                    <td>
                                        @if(!empty($repairOrder->priority))
                                            @if($repairOrder->priority == 'urgent')
                                                <span class="badge bg-danger">فوری</span>
                                            @else
                                                <span class="badge bg-secondary">عادی</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">عادی</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>هزینه تخمینی</th>
                                    <td>
                                        @if(!empty($repairOrder->estimated_cost))
                                            {{ number_format($repairOrder->estimated_cost) }} تومان
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>هزینه نهایی</th>
                                    <td>
                                        @if(!empty($repairOrder->final_cost))
                                            <strong class="text-success">{{ number_format($repairOrder->final_cost) }} تومان</strong>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- کارت توضیحات --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>توضیحات مشکل</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $repairOrder->description ?? '---' }}</p>
                        </div>
                    </div>

                    {{-- کارت یادداشت‌های داخلی --}}
                    @if(!empty($repairOrder->notes))
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>یادداشت‌های داخلی</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $repairOrder->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ستون چپ: اطلاعات قطعه و مالک --}}
                <div class="col-md-6">
                    {{-- کارت اطلاعات قطعه --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-gem me-2"></i>اطلاعات قطعه</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($repairOrder->jewelry))
                                @php
                                    $jewelry = $repairOrder->jewelry;
                                @endphp
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        @if(!empty($jewelry->certificate_file))
                                            @php
                                                $extension = pathinfo($jewelry->certificate_file, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                            @endphp
                                            @if($isImage)
                                                <img src="{{ asset('storage/' . $jewelry->certificate_file) }}" 
                                                     alt="تصویر قطعه" 
                                                     class="img-fluid img-thumbnail mb-2"
                                                     style="max-height: 120px; cursor: pointer;"
                                                     onclick="openImageModal('{{ asset('storage/' . $jewelry->certificate_file) }}')">
                                            @else
                                                <div class="bg-light p-3 text-center rounded">
                                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                    <p class="mt-2">
                                                        <a href="{{ asset('storage/' . $jewelry->certificate_file) }}" target="_blank">
                                                            مشاهده فایل
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-light p-3 text-center rounded">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">بدون تصویر</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <th>نام قطعه</th>
                                                <td>{{ $jewelry->product_name ?? '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>شماره سریال</th>
                                                <td>{{ $jewelry->serial_number ?? '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>نوع فلز</th>
                                                <td>{{ $jewelry->metal_type ?? '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>عیار</th>
                                                <td>{{ $jewelry->purity ?? '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>وزن</th>
                                                <td>{{ !empty($jewelry->weight) ? number_format($jewelry->weight, 2) . ' گرم' : '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>نوع سنگ</th>
                                                <td>{{ $jewelry->stone_type ?? '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>تاریخ صدور</th>
                                                <td>{{ !empty($jewelry->issued_at) ? jdate($jewelry->issued_at)->format('Y/m/d') : '---' }}</td>
                                            </tr>
                                            <tr>
                                                <th>وضعیت قطعه</th>
                                                <td>
                                                    @if(!empty($jewelry->is_active) && $jewelry->is_active)
                                                        <span class="badge bg-success">فعال</span>
                                                    @else
                                                        <span class="badge bg-danger">غیرفعال</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    اطلاعات قطعه برای این سفارش یافت نشد.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- کارت اطلاعات مالک --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>اطلاعات مالک</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($repairOrder->jewelry) && !empty($repairOrder->jewelry->owner))
                                @php
                                    $owner = $repairOrder->jewelry->owner;
                                @endphp
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="150">نام مالک</th>
                                        <td>{{ $owner->full_name ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>کد ملی</th>
                                        <td>{{ $owner->national_id ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تلفن</th>
                                        <td>{{ $owner->phone ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>ایمیل</th>
                                        <td>{{ $owner->email ?? '---' }}</td>
                                    </tr>
                                    @if(!empty($owner->address))
                                    <tr>
                                        <th>آدرس</th>
                                        <td>{{ $owner->address }}</td>
                                    </tr>
                                    @endif
                                </table>
                                
                                {{-- اطلاعات کاربری مرتبط --}}
                                @if(!empty($owner->user))
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small class="d-block"><i class="fas fa-user-circle me-1"></i> حساب کاربری: {{ $owner->user->name ?? '' }}</small>
                                        <small class="d-block"><i class="fas fa-envelope me-1"></i> {{ $owner->user->email ?? '' }}</small>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    اطلاعات مالک برای این قطعه یافت نشد.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- کارت اطلاعات کاربر ثبت‌کننده --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>اطلاعات ثبت‌کننده</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($repairOrder->user))
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="150">نام کاربر</th>
                                        <td>{{ $repairOrder->user->name ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>ایمیل</th>
                                        <td>{{ $repairOrder->user->email ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تلفن</th>
                                        <td>{{ $repairOrder->user->phone ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <th>نقش</th>
                                        <td>
                                            @if(!empty($repairOrder->user->role))
                                                @if($repairOrder->user->role == 'admin')
                                                    <span class="badge bg-danger">مدیر</span>
                                                @elseif($repairOrder->user->role == 'technician')
                                                    <span class="badge bg-info">تکنسین</span>
                                                @else
                                                    <span class="badge bg-secondary">کاربر عادی</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">نامشخص</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted text-center">اطلاعات ثبت‌کننده موجود نیست</p>
                            @endif
                        </div>
                    </div>

                    {{-- کارت تکنسین --}}
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>تکنسین مسئول</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($repairOrder->technician))
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-user-circle fa-3x text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $repairOrder->technician->name ?? '---' }}</h5>
                                        <p class="mb-0 text-muted">{{ $repairOrder->technician->email ?? '---' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-0">هنوز تکنسینی تعیین نشده است</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- بخش تصاویر آپلود شده --}}
            @if(!empty($repairOrder->photo))
                @php
                    $photos = is_string($repairOrder->photo) ? json_decode($repairOrder->photo, true) : $repairOrder->photo;
                @endphp
                @if(!empty($photos) && is_array($photos))
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>تصاویر ضمیمه شده</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($photos as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="card-img-top" 
                                             alt="تصویر تعمیرات"
                                             style="height: 150px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                        <div class="card-body p-2 text-center">
                                            <a href="{{ asset('storage/' . $image) }}" 
                                               class="btn btn-sm btn-primary" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i> مشاهده
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endif

            {{-- تاریخچه وضعیت --}}
            @if(!empty($repairOrder->statusHistory) && $repairOrder->statusHistory->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>تاریخچه تغییرات وضعیت</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($repairOrder->statusHistory as $history)
                            <div class="timeline-item">
                                <div class="timeline-badge bg-{{ $history->status_color ?? 'secondary' }}">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>
                                        @if($history->status == 'pending')
                                            در انتظار
                                        @elseif($history->status == 'in_progress')
                                            در حال انجام
                                        @elseif($history->status == 'completed')
                                            تکمیل شده
                                        @elseif($history->status == 'delivered')
                                            تحویل شده
                                        @elseif($history->status == 'cancelled')
                                            لغو شده
                                        @else
                                            {{ $history->status ?? '---' }}
                                        @endif
                                    </h6>
                                    <p class="text-muted small mb-1">
                                        {{ !empty($history->created_at) ? jdate($history->created_at)->format('Y/m/d H:i') : '---' }} 
                                        - توسط {{ $history->user->name ?? 'سیستم' }}
                                    </p>
                                    @if(!empty($history->notes))
                                        <p class="mb-0">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- فرم تغییر وضعیت (برای تکنسین و مدیر) --}}
            @if(Auth::user() && in_array(Auth::user()->role, ['admin', 'technician']) && (!empty($repairOrder->status) && $repairOrder->status != 'delivered'))
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>تغییر وضعیت سفارش</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jewelry.repair-orders.update_status', $repairOrder->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <select name="status" class="form-select" required>
                                    <option value="">انتخاب وضعیت جدید</option>
                                    <option value="pending" {{ $repairOrder->status == 'pending' ? 'disabled' : '' }}>در انتظار</option>
                                    <option value="in_progress" {{ $repairOrder->status == 'in_progress' ? 'disabled' : '' }}>در حال انجام</option>
                                    <option value="completed" {{ $repairOrder->status == 'completed' ? 'disabled' : '' }}>تکمیل شده</option>
                                    <option value="delivered" {{ $repairOrder->status == 'delivered' ? 'disabled' : '' }}>تحویل شده</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="final_cost" class="form-control" placeholder="هزینه نهایی (تومان)" 
                                       value="{{ $repairOrder->final_cost ?? '' }}" step="1000" min="0">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="notes" class="form-control" placeholder="توضیحات (اختیاری)">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-sync-alt me-2"></i>بروزرسانی وضعیت
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- دکمه‌های عملیات --}}
            <div class="d-flex justify-content-between mb-4">
                <a href="{{ route('admin.jewelry.repair-orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>بازگشت به لیست
                </a>
                @if(Auth::user() && Auth::user()->role == 'admin')
                    <a href="{{ route('admin.jewelry.repair-orders.edit', $repairOrder->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>ویرایش سفارش
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- مودال نمایش تصویر --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تصویر قطعه</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline-item {
    position: relative;
    padding-right: 40px;
    margin-bottom: 20px;
}
.timeline-badge {
    position: absolute;
    right: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.timeline-content {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 5px;
}
</style>
@endsection

@section('scripts')
<script>
// تابع باز کردن تصویر در مودال
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(alert) {
        alert.classList.remove('show');
        setTimeout(function() {
            alert.remove();
        }, 300);
    });
}, 5000);
</script>
@endsection