@extends('admin.jewelry.layouts.app')

@section('title', 'گزارش تعمیرات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.reports.certificates') }}">گزارشات</a></li>
    <li class="breadcrumb-item active">گزارش تعمیرات</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        گزارش تعمیرات
                    </h4>
                    <a href="{{ route('admin.jewelry.reports.export') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-download me-2"></i>خروجی اکسل
                    </a>
                </div>
                
                <div class="card-body">
                    {{-- فرم فیلتر --}}
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light">
                            <i class="fas fa-filter me-2"></i>فیلتر گزارش
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.jewelry.reports.repairs') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">وضعیت</label>
                                    <select name="status" class="form-select">
                                        <option value="">همه وضعیت‌ها</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>در حال انجام</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تحویل شده</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">از تاریخ</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">تا تاریخ</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>اعمال فیلتر
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- آمار کلی --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">کل سفارشات</h6>
                                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>در انتظار</h6>
                                    <h3>{{ $stats['pending'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>در حال انجام</h6>
                                    <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>تکمیل شده</h6>
                                    <h3>{{ $stats['completed'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- نمودار توزیع (در صورت وجود داده) --}}
                    @if(isset($repairTypeDistribution) && $repairTypeDistribution->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">توزیع نوع تعمیرات</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>نوع تعمیر</th>
                                                <th>تعداد</th>
                                                <th>درصد</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($repairTypeDistribution as $item)
                                            <tr>
                                                <td>
                                                    @switch($item->repair_type)
                                                        @case('cleaning') تمیزکاری @break
                                                        @case('restoration') بازسازی @break
                                                        @case('resizing') تغییر اندازه @break
                                                        @default سایر
                                                    @endswitch
                                                </td>
                                                <td>{{ $item->count }}</td>
                                                <td>
                                                    @php
                                                        $percent = $stats['total'] > 0 ? round(($item->count / $stats['total']) * 100) : 0;
                                                    @endphp
                                                    {{ $percent }}%
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">آمار هزینه‌ها</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th>مجموع هزینه‌های تخمینی</th>
                                            <td>{{ number_format($stats['estimated_total'] ?? 0) }} تومان</td>
                                        </tr>
                                        <tr>
                                            <th>مجموع هزینه‌های نهایی</th>
                                            <td>{{ number_format($stats['final_total'] ?? 0) }} تومان</td>
                                        </tr>
                                        <tr>
                                            <th>میانگین هزینه هر تعمیر</th>
                                            <td>
                                                @php
                                                    $avg = $stats['total'] > 0 ? ($stats['final_total'] / $stats['total']) : 0;
                                                @endphp
                                                {{ number_format($avg) }} تومان
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- جدول گزارش --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>شماره سفارش</th>
                                    <th>قطعه</th>
                                    <th>نوع تعمیر</th>
                                    <th>تاریخ درخواست</th>
                                    <th>تاریخ تکمیل</th>
                                    <th>وضعیت</th>
                                    <th>هزینه (تومان)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repairOrders ?? [] as $index => $order)
                                <tr>
                                    <td>{{ $repairOrders->firstItem() + $index }}</td>
                                    <td>
                                        <a href="{{ route('admin.jewelry.repair-orders.show', $order->id) }}">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $order->certificate->product_name ?? '---' }}
                                        <br>
                                        <small class="text-muted">{{ $order->certificate->serial_number ?? '' }}</small>
                                    </td>
                                    <td>
                                        @switch($order->repair_type)
                                            @case('cleaning') تمیزکاری @break
                                            @case('restoration') بازسازی @break
                                            @case('resizing') تغییر اندازه @break
                                            @default سایر
                                        @endswitch
                                    </td>
                                    <td>{{ jdate($order->created_at)->format('Y/m/d') }}</td>
                                    <td>{{ $order->completed_at ? jdate($order->completed_at)->format('Y/m/d') : '---' }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending') <span class="badge bg-warning">در انتظار</span> @break
                                            @case('in_progress') <span class="badge bg-info">در حال انجام</span> @break
                                            @case('completed') <span class="badge bg-success">تکمیل شده</span> @break
                                            @case('delivered') <span class="badge bg-secondary">تحویل شده</span> @break
                                        @endswitch
                                    </td>
                                    <td>{{ number_format($order->final_cost ?? $order->estimated_cost ?? 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">هیچ داده‌ای برای نمایش وجود ندارد</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    @if(isset($repairOrders) && method_exists($repairOrders, 'links'))
                    <div class="mt-3">
                        {{ $repairOrders->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection