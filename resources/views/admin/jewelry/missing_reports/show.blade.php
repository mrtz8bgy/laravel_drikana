{{-- resources/views/admin/jewelry/missing_reports/show.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4>جزییات گزارش سرقت/مفقودی</h4>
                    <div>
                        <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="btn btn-light btn-sm">بازگشت به لیست</a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    {{-- اطلاعات گزارش --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-secondary text-white">
                                    <h5>اطلاعات گزارش</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="200">شماره گزارش</th>
                                            <td>{{ $report->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>نوع گزارش</th>
                                            <td>
                                                @if($report->type == 'theft')
                                                    <span class="badge badge-danger p-2">سرقت</span>
                                                @else
                                                    <span class="badge badge-warning p-2">مفقودی</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>وضعیت</th>
                                            <td>
                                                @if($report->status == 'pending')
                                                    <span class="badge badge-secondary p-2">در انتظار بررسی</span>
                                                @else
                                                    <span class="badge badge-success p-2">پیدا شد / مختومه</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>تاریخ ثبت گزارش</th>
                                            <td>{{ jdate($report->created_at)->format('Y/m/d - H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>آخرین بروزرسانی</th>
                                            <td>{{ jdate($report->updated_at)->format('Y/m/d - H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-secondary text-white">
                                    <h5>اطلاعات مالک</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $owner = $report->jewelry->owner ?? null;
                                    @endphp
                                    
                                    @if($owner)
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th width="150">نام مالک</th>
                                                <td>{{ $owner->full_name ?? 'نامشخص' }}</td>
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
                                            @if($owner->address)
                                            <tr>
                                                <th>آدرس</th>
                                                <td>{{ $owner->address }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                        
                                        {{-- اطلاعات کاربری مرتبط --}}
                                        @if($owner->user)
                                            <div class="mt-2 text-muted small">
                                                <i class="fas fa-info-circle"></i>
                                                حساب کاربری: {{ $owner->user->email ?? '' }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            اطلاعات مالک برای این قطعه یافت نشد.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- اطلاعات قطعه با تصویر --}}
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h5>اطلاعات قطعه</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- ستون تصویر --}}
                                <div class="col-md-4 text-center mb-3">
                                    @if($report->jewelry->certificate_file)
                                        @php
                                            $extension = pathinfo($report->jewelry->certificate_file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                        @endphp
                                        
                                        @if($isImage)
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <strong>تصویر قطعه</strong>
                                                </div>
                                                <div class="card-body text-center">
                                                    <a href="{{ asset('storage/' . $report->jewelry->certificate_file) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $report->jewelry->certificate_file) }}" 
                                                             alt="تصویر قطعه" 
                                                             class="img-fluid img-thumbnail" 
                                                             style="max-height: 200px; cursor: pointer;"
                                                             onclick="openImageModal(this.src)">
                                                    </a>
                                                    <p class="mt-2">
                                                        <a href="{{ asset('storage/' . $report->jewelry->certificate_file) }}" 
                                                           class="btn btn-sm btn-info" 
                                                           target="_blank">
                                                            <i class="fas fa-eye"></i> مشاهده در اندازه واقعی
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <strong>فایل ضمیمه</strong>
                                                </div>
                                                <div class="card-body text-center">
                                                    <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                                    <p>
                                                        <a href="{{ asset('storage/' . $report->jewelry->certificate_file) }}" 
                                                           class="btn btn-sm btn-danger" 
                                                           target="_blank">
                                                            <i class="fas fa-download"></i> دانلود فایل
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <strong>تصویر قطعه</strong>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                                <p class="text-muted">تصویری برای این قطعه وجود ندارد</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- ستون اطلاعات --}}
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th width="200">نام قطعه</th>
                                                    <td>{{ $report->jewelry->product_name ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>شماره سریال</th>
                                                    <td>{{ $report->jewelry->serial_number ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>نوع فلز</th>
                                                    <td>{{ $report->jewelry->metal_type ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>عیار</th>
                                                    <td>{{ $report->jewelry->purity ?? '---' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th width="200">وزن</th>
                                                    <td>{{ $report->jewelry->weight ?? '---' }} گرم</td>
                                                </tr>
                                                <tr>
                                                    <th>نوع سنگ</th>
                                                    <td>{{ $report->jewelry->stone_type ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاریخ صدور</th>
                                                    <td>{{ $report->jewelry->issued_at ? jdate($report->jewelry->issued_at)->format('Y/m/d') : '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>وضعیت</th>
                                                    <td>
                                                        @if($report->jewelry->is_active)
                                                            <span class="badge badge-success">فعال</span>
                                                        @else
                                                            <span class="badge badge-danger">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($report->jewelry->description)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <strong>توضیحات قطعه</strong>
                                        </div>
                                        <div class="card-body">
                                            {{ $report->jewelry->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- اطلاعات حادثه --}}
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h5>اطلاعات حادثه</h5>
                        </div>
                        <div class="card-body">
                            @if($report->location)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>موقعیت مکانی:</strong>
                                    <p class="mt-2">{{ $report->location }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($report->description)
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>توضیحات تکمیلی:</strong>
                                    <p class="mt-2">{{ $report->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- دکمه‌های عملیات --}}
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            @if($report->status == 'pending')
                                <form action="{{ route('admin.jewelry.missing_reports.update_status', $report->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="found">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('آیا از پیدا شدن قطعه اطمینان دارید؟')">
                                        <i class="fas fa-check"></i> ثبت به عنوان پیدا شده
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i> بازگشت
                            </a>
                            
                            @if($report->status == 'pending')
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#printModal">
                                    <i class="fas fa-print"></i> چاپ گزارش
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- مودال نمایش تصویر در اندازه بزرگ --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="imageModalLabel">تصویر قطعه</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                <a href="" id="downloadImageBtn" class="btn btn-info" download>
                    <i class="fas fa-download"></i> دانلود
                </a>
            </div>
        </div>
    </div>
</div>

{{-- مودال چاپ --}}
@if($report->status == 'pending')
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="printModalLabel">چاپ گزارش</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>آیا می‌خواهید گزارش را چاپ کنید؟</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    این گزارش شامل اطلاعات کامل قطعه و مشخصات مالک می‌باشد.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                <a href="{{ route('admin.jewelry.missing_reports.print', $report->id) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print"></i> چاپ
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    // فعال کردن tooltip ها
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    
    // تابع باز کردن تصویر در مودال
    function openImageModal(imageSrc) {
        $('#modalImage').attr('src', imageSrc);
        $('#downloadImageBtn').attr('href', imageSrc);
        $('#imageModal').modal('show');
    }
</script>
@endsection