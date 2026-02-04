@extends('admin.jewelry.layouts.app')

@section('title', isset($owner) ? 'ویرایش مالک: ' . $owner->full_name : 'ایجاد مالک جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.owners.index') }}">مالکان</a></li>
    <li class="breadcrumb-item active">{{ isset($owner) ? 'ویرایش' : 'ایجاد' }}</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="page-header">
        <h1>
            <i class="fas fa-user-edit"></i>
            @if(isset($owner) && $owner->id)
                ویرایش مالک: {{ $owner->full_name }}
            @else
                ایجاد مالک جدید
            @endif
        </h1>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-circle"></i>
            اطلاعات مالک
        </div>
        <div class="card-body">
            <form action="{{ isset($owner) ? route('admin.jewelry.owners.update', $owner) : route('admin.jewelry.owners.store') }}" 
                  method="POST">
                @csrf
                
                @if(isset($owner) && $owner->id)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                نام کامل <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name', isset($owner) ? $owner->full_name : '') }}"
                                   required
                                   placeholder="نام و نام خانوادگی">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">نام و نام خانوادگی مالک جواهر را وارد کنید</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="national_id" class="form-label">کد ملی</label>
                            <input type="text" 
                                   class="form-control @error('national_id') is-invalid @enderror" 
                                   id="national_id" 
                                   name="national_id" 
                                   value="{{ old('national_id', isset($owner) ? $owner->national_id : '') }}"
                                   maxlength="10"
                                   placeholder="۱۰ رقم کد ملی">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                تلفن <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', isset($owner) ? $owner->phone : '') }}"
                                   required
                                   placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">ایمیل</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', isset($owner) ? $owner->email : '') }}"
                                   placeholder="example@domain.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">آدرس</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="3"
                              placeholder="آدرس کامل مالک">{{ old('address', isset($owner) ? $owner->address : '') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">یادداشت‌ها</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3"
                              placeholder="هرگونه توضیح یا یادداشت اضافی">{{ old('notes', isset($owner) ? $owner->notes : '') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('admin.jewelry.owners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i> بازگشت به لیست
                        </a>
                    </div>
                    
                    <div class="btn-group">
                        @if(isset($owner) && $owner->id)
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> بروزرسانی اطلاعات
                            </button>
                            <a href="{{ route('admin.jewelry.owners.show', $owner) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i> مشاهده
                            </a>
                        @else
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle me-2"></i> ایجاد مالک جدید
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($owner) && $owner->id)
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-history"></i> اطلاعات سیستمی
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>شناسه:</strong> {{ $owner->id }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>تاریخ ایجاد:</strong> {{ $owner->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>آخرین ویرایش:</strong> {{ $owner->updated_at->format('Y/m/d H:i') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>تعداد شناسنامه‌ها:</strong> 
                        <span class="badge bg-primary">{{ $owner->certificates()->count() }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    .invalid-feedback {
        display: block;
        margin-top: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // اعتبارسنجی سمت کلاینت
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(event) {
            const fullName = document.getElementById('full_name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            
            if (!fullName) {
                alert('لطفا نام کامل را وارد کنید');
                event.preventDefault();
                return false;
            }
            
            if (!phone) {
                alert('لطفا شماره تلفن را وارد کنید');
                event.preventDefault();
                return false;
            }
            
            // اعتبارسنجی شماره تلفن (شروع با ۰ و ۱۱ رقم)
            const phoneRegex = /^0\d{10}$/;
            if (!phoneRegex.test(phone)) {
                alert('لطفا شماره تلفن معتبر وارد کنید (۱۱ رقم و شروع با ۰)');
                event.preventDefault();
                return false;
            }
            
            // اعتبارسنجی کد ملی (اگر وارد شده)
            const nationalId = document.getElementById('national_id').value.trim();
            if (nationalId && !/^\d{10}$/.test(nationalId)) {
                alert('کد ملی باید ۱۰ رقم باشد');
                event.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
@endsection