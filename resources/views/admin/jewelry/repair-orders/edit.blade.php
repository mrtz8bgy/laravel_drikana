{{-- resources/views/admin/jewelry/repair-orders/edit.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'ویرایش سفارش تعمیر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.repair-orders.index') }}">سفارشات تعمیر</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.repair-orders.show', $order->id) }}">{{ $order->order_number }}</a></li>
    <li class="breadcrumb-item active">ویرایش</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        ویرایش سفارش {{ $order->order_number }}
                    </h4>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.jewelry.repair-orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            {{-- اطلاعات فقط خواندنی --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">شماره سفارش</label>
                                <input type="text" class="form-control" value="{{ $order->order_number }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">وضعیت فعلی</label>
                                <input type="text" class="form-control" value="{{ $order->status_label }}" readonly>
                            </div>
                            
                            {{-- قطعه (فقط خواندنی) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">قطعه</label>
                                <input type="text" class="form-control" 
                                       value="{{ $order->jewelry->product_name }} - {{ $order->jewelry->serial_number }}" readonly>
                            </div>
                            
                            {{-- نوع تعمیر (فقط خواندنی) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نوع تعمیر</label>
                                <input type="text" class="form-control" value="{{ $order->repair_type_label }}" readonly>
                            </div>
                            
                            {{-- فیلدهای قابل ویرایش --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاریخ برنامه‌ریزی</label>
                                <input type="date" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                       value="{{ old('scheduled_date', $order->scheduled_date ? $order->scheduled_date->format('Y-m-d') : '') }}">
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">هزینه تخمینی (تومان)</label>
                                <input type="number" name="estimated_cost" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       value="{{ old('estimated_cost', $order->estimated_cost) }}" min="0" step="1000">
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تکنسین مسئول</label>
                                <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">تعیین نشده</option>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->user_id }}" 
                                            {{ old('assigned_to', $order->assigned_to) == $technician->user_id ? 'selected' : '' }}>
                                            {{ $technician->user->name }} - {{ $technician->specialty }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label">یادداشت‌های داخلی</label>
                                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="یادداشت برای تکنسین یا مدیر...">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- تاریخ‌های مهم --}}
                            @if($order->completion_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاریخ تکمیل</label>
                                <input type="text" class="form-control" value="{{ jdate($order->completion_date)->format('Y/m/d') }}" readonly>
                            </div>
                            @endif
                            
                            @if($order->delivery_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاریخ تحویل</label>
                                <input type="text" class="form-control" value="{{ jdate($order->delivery_date)->format('Y/m/d') }}" readonly>
                            </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>بروزرسانی سفارش
                            </button>
                            <a href="{{ route('admin.jewelry.repair-orders.show', $order->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>انصراف
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection