{{-- resources/views/admin/jewelry/repair-orders/index.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'مدیریت سفارشات تعمیر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item active">سفارشات تعمیر</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        مدیریت سفارشات تعمیر
                    </h4>
                    <a href="{{ route('admin.jewelry.repair-orders.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-2"></i>
                        ثبت سفارش جدید
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    {{-- فرم فیلتر --}}
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light">
                            <i class="fas fa-filter me-2"></i>فیلتر سفارشات
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.jewelry.repair-orders.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">جستجو</label>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="شماره سفارش / نام قطعه / سریال..." 
                                           value="{{ request('search') }}">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">وضعیت</label>
                                    <select name="status" class="form-select">
                                        <option value="">همه</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>در حال انجام</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تحویل شده</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">نوع تعمیر</label>
                                    <select name="repair_type" class="form-select">
                                        <option value="">همه</option>
                                        <option value="cleaning" {{ request('repair_type') == 'cleaning' ? 'selected' : '' }}>تمیزکاری</option>
                                        <option value="restoration" {{ request('repair_type') == 'restoration' ? 'selected' : '' }}>بازسازی</option>
                                        <option value="resizing" {{ request('repair_type') == 'resizing' ? 'selected' : '' }}>تغییر اندازه</option>
                                        <option value="other" {{ request('repair_type') == 'other' ? 'selected' : '' }}>سایر</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">از تاریخ</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">تا تاریخ</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- آمار خلاصه --}}
                    @php
                        $total = $repairOrders->total();
                        $pending = $repairOrders->where('status', 'pending')->count();
                        $inProgress = $repairOrders->where('status', 'in_progress')->count();
                        $completed = $repairOrders->where('status', 'completed')->count();
                        $delivered = $repairOrders->where('status', 'delivered')->count();
                    @endphp
                    
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-light">
                                <div class="card-body py-2 text-center">
                                    <small class="text-muted">کل سفارشات</small>
                                    <h5 class="mb-0">{{ $total }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body py-2 text-center">
                                    <small>در انتظار</small>
                                    <h5 class="mb-0">{{ $pending }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body py-2 text-center">
                                    <small>در حال انجام</small>
                                    <h5 class="mb-0">{{ $inProgress }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body py-2 text-center">
                                    <small>تکمیل شده</small>
                                    <h5 class="mb-0">{{ $completed }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body py-2 text-center">
                                    <small>تحویل شده</small>
                                    <h5 class="mb-0">{{ $delivered }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- جدول سفارشات --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>شماره سفارش</th>
                                    <th>قطعه</th>
                                    <th>نوع تعمیر</th>
                                    <th>تاریخ درخواست</th>
                                    <th>وضعیت</th>
                                    <th>تکنسین</th>
                                    <th>هزینه</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repairOrders as $index => $order)
                                    <tr>
                                    <td>{{ $repairOrders->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->jewelry->product_name ?? '---' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->jewelry->serial_number ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $order->repair_type_label }}</td>
                                    <td>{{ jdate($order->request_date)->format('Y/m/d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }} p-2">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $order->technician->name ?? 'تعیین نشده' }}</td>
                                    <td>
                                        @if($order->final_cost)
                                            {{ number_format($order->final_cost) }} تومان
                                        @elseif($order->estimated_cost)
                                            تخمینی: {{ number_format($order->estimated_cost) }} تومان
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.jewelry.repair-orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="مشاهده جزییات">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role == 'admin')
                                            <a href="{{ route('admin.jewelry.repair-orders.edit', $order->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="ویرایش">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">هیچ سفارش تعمیری یافت نشد</h5>
                                        <a href="{{ route('admin.jewelry.repair-orders.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-2"></i>ثبت اولین سفارش
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $repairOrders->appends(request()->query())->links() }}
                    </div>
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
    });
</script>
@endsection