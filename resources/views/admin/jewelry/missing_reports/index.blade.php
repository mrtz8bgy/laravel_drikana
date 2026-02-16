@extends('admin.jewelry.layouts.app')

@section('title', 'لیست گزارش‌های سرقت و مفقودی')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item active">گزارش‌های سرقت و مفقودی</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        لیست گزارش‌های سرقت و مفقودی
                    </h4>
                    <a href="{{ route('admin.jewelry.missing_reports.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-2"></i>
                        ثبت گزارش جدید
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    {{-- فرم فیلتر --}}
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light">
                            <i class="fas fa-filter me-2"></i>فیلتر گزارش‌ها
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.jewelry.missing_reports.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">جستجو</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="نام قطعه یا شماره سریال..." 
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">نوع</label>
                                    <select name="type" class="form-select">
                                        <option value="">همه</option>
                                        <option value="theft" {{ request('type') == 'theft' ? 'selected' : '' }}>سرقت</option>
                                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>مفقودی</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">وضعیت</label>
                                    <select name="status" class="form-select">
                                        <option value="">همه</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                        <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>پیدا شده</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">از تاریخ</label>
                                    <input type="date" 
                                           name="date_from" 
                                           class="form-control" 
                                           value="{{ request('date_from') }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">تا تاریخ</label>
                                    <input type="date" 
                                           name="date_to" 
                                           class="form-control" 
                                           value="{{ request('date_to') }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>اعمال فیلتر
                                    </button>
                                    <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>پاک کردن فیلترها
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- نمایش تعداد نتایج --}}
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        تعداد گزارش‌های یافت شده: <strong>{{ $reports->count() }}</strong>
                    </div>
                    
                    {{-- آمار خلاصه --}}
                    @php
                        $totalReports = $reports->count();
                        $pendingCount = $reports->where('status', 'pending')->count();
                        $foundCount = $reports->where('status', 'found')->count();
                        $theftCount = $reports->where('type', 'theft')->count();
                        $lostCount = $reports->where('type', 'lost')->count();
                    @endphp
                    
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-light">
                                <div class="card-body py-2 text-center">
                                    <small class="text-muted">کل گزارش‌ها</small>
                                    <h5 class="mb-0">{{ $totalReports }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body py-2 text-center">
                                    <small>در انتظار</small>
                                    <h5 class="mb-0">{{ $pendingCount }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body py-2 text-center">
                                    <small>پیدا شده</small>
                                    <h5 class="mb-0">{{ $foundCount }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body py-2 text-center">
                                    <small>سرقت</small>
                                    <h5 class="mb-0">{{ $theftCount }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body py-2 text-center">
                                    <small>مفقودی</small>
                                    <h5 class="mb-0">{{ $lostCount }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- جدول نتایج --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">#</th>
                                    <th>شماره گزارش</th>
                                    <th>اطلاعات قطعه</th>
                                    <th>اطلاعات مالک</th>
                                    <th>نوع</th>
                                    <th>تاریخ ثبت</th>
                                    <th>وضعیت</th>
                                    <th width="120">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $report->id }}</strong>
                                        <br>
                                        <small class="text-muted">{{ jdate($report->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($report->jewelry && $report->jewelry->certificate_file)
                                                @php
                                                    $extension = pathinfo($report->jewelry->certificate_file, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                                @endphp
                                                @if($isImage)
                                                    <img src="{{ asset('storage/' . $report->jewelry->certificate_file) }}" 
                                                         alt="تصویر" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-file-pdf text-white"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-gem text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $report->jewelry->product_name ?? '---' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $report->jewelry->serial_number ?? 'بدون سریال' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $owner = $report->jewelry->owner ?? null;
                                        @endphp
                                        <strong>{{ $owner->full_name ?? '---' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $owner->phone ?? '---' }}</small>
                                    </td>
                                    <td>
                                        @if($report->type == 'theft')
                                            <span class="badge bg-danger p-2">سرقت</span>
                                        @else
                                            <span class="badge bg-warning p-2">مفقودی</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-nowrap">{{ jdate($report->created_at)->format('Y/m/d') }}</span>
                                    </td>
                                    <td>
                                        @if($report->status == 'pending')
                                            <span class="badge bg-secondary p-2">در انتظار</span>
                                        @else
                                            <span class="badge bg-success p-2">پیدا شد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.jewelry.missing_reports.show', $report->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="مشاهده جزییات">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($report->status == 'pending')
                                            <a href="{{ route('admin.jewelry.missing_reports.print', $report->id) }}" 
                                               class="btn btn-sm btn-secondary" 
                                               target="_blank"
                                               data-bs-toggle="tooltip" 
                                               title="چاپ گزارش">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">هیچ گزارشی یافت نشد</h5>
                                        <a href="{{ route('admin.jewelry.missing_reports.create') }}" class="btn btn-warning mt-2">
                                            <i class="fas fa-plus-circle me-2"></i>ثبت گزارش جدید
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- نمایش فیلترهای فعال --}}
                    @if(request()->hasAny(['search', 'type', 'status', 'date_from', 'date_to']))
                        <div class="mt-3 p-2 bg-light rounded">
                            <i class="fas fa-filter me-2 text-primary"></i>
                            <span class="text-muted">فیلترهای فعال:</span>
                            @if(request('search'))
                                <span class="badge bg-primary me-2">جستجو: {{ request('search') }}</span>
                            @endif
                            @if(request('type'))
                                <span class="badge bg-primary me-2">نوع: {{ request('type') == 'theft' ? 'سرقت' : 'مفقودی' }}</span>
                            @endif
                            @if(request('status'))
                                <span class="badge bg-primary me-2">وضعیت: {{ request('status') == 'pending' ? 'در انتظار' : 'پیدا شده' }}</span>
                            @endif
                            @if(request('date_from'))
                                <span class="badge bg-primary me-2">از تاریخ: {{ request('date_from') }}</span>
                            @endif
                            @if(request('date_to'))
                                <span class="badge bg-primary me-2">تا تاریخ: {{ request('date_to') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // فعال کردن tooltip ها
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // اعتبارسنجی تاریخ
        $('input[type="date"]').on('change', function() {
            var dateFrom = $('input[name="date_from"]').val();
            var dateTo = $('input[name="date_to"]').val();
            
            if(dateFrom && dateTo && dateFrom > dateTo) {
                alert('تاریخ شروع نمی‌تواند بزرگتر از تاریخ پایان باشد');
                $(this).val('');
            }
        });
    });
</script>
@endsection