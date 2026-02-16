{{-- resources/views/admin/jewelry/repair-orders/create.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('title', 'ثبت سفارش تعمیر جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.repair-orders.index') }}">سفارشات تعمیر</a></li>
    <li class="breadcrumb-item active">ثبت سفارش جدید</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        ثبت سفارش تعمیر جدید
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
                    
                    <form method="POST" action="{{ route('admin.jewelry.repair-orders.store') }}" enctype="multipart/form-data" id="repairOrderForm">
                        @csrf
                        
                        <div class="row">
                            {{-- انتخاب قطعه --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">قطعه *</label>
                                <select name="jewelry_certificate_id" id="jewelry_id" class="form-select @error('jewelry_certificate_id') is-invalid @enderror" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($jewelries as $jewelry)
                                        <option value="{{ $jewelry->id }}" {{ old('jewelry_certificate_id') == $jewelry->id ? 'selected' : '' }}>
                                            {{ $jewelry->product_name }} - {{ $jewelry->serial_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jewelry_certificate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- نوع تعمیر --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نوع تعمیر *</label>
                                <select name="repair_type" class="form-select @error('repair_type') is-invalid @enderror" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="cleaning" {{ old('repair_type') == 'cleaning' ? 'selected' : '' }}>تمیزکاری</option>
                                    <option value="restoration" {{ old('repair_type') == 'restoration' ? 'selected' : '' }}>بازسازی</option>
                                    <option value="resizing" {{ old('repair_type') == 'resizing' ? 'selected' : '' }}>تغییر اندازه</option>
                                    <option value="other" {{ old('repair_type') == 'other' ? 'selected' : '' }}>سایر</option>
                                </select>
                                @error('repair_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- توضیحات مشکل --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">توضیحات مشکل *</label>
                                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="مشکل قطعه را توضیح دهید..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- مکان تعمیر --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">مکان تعمیر *</label>
                                <select name="location" class="form-select @error('location') is-invalid @enderror" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="workshop" {{ old('location') == 'workshop' ? 'selected' : '' }}>کارگاه</option>
                                    <option value="customer" {{ old('location') == 'customer' ? 'selected' : '' }}>محل مشتری</option>
                                </select>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- تاریخ برنامه‌ریزی --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاریخ برنامه‌ریزی</label>
                                <input type="date" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                       value="{{ old('scheduled_date') }}" min="{{ date('Y-m-d') }}">
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">تاریخ شروع تعمیرات</small>
                            </div>
                            
                            {{-- تاریخ تحویل تخمینی --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تاریخ تحویل تخمینی</label>
                                <input type="date" name="estimated_delivery_date" class="form-control @error('estimated_delivery_date') is-invalid @enderror" 
                                       value="{{ old('estimated_delivery_date') }}" min="{{ date('Y-m-d') }}">
                                @error('estimated_delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">تاریخ تخمینی تحویل سفارش</small>
                            </div>
                            
                            {{-- هزینه تخمینی --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">هزینه تخمینی (تومان)</label>
                                <input type="number" name="estimated_cost" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       value="{{ old('estimated_cost') }}" min="0" step="1000">
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">هزینه تقریبی تعمیرات</small>
                            </div>
                            
  {{-- انتخاب تکنسین (فقط برای مدیران) --}}

<div class="col-md-4 mb-3">
    <label class="form-label">تکنسین مسئول</label>
    <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
        <option value="">بدون تکنسین</option>
        
        @forelse($technicians ?? [] as $technician)
            @if($technician->is_active && $technician->user)
                <option value="{{ $technician->user_id }}" 
                    {{ old('assigned_to') == $technician->user_id ? 'selected' : '' }}>
                    {{ $technician->display_name }}
                </option>
            @endif
        @empty
            <option value="" disabled class="text-danger">هیچ تکنسین فعالی یافت نشد</option>
        @endforelse
    </select>
    
    @error('assigned_to')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>
        تکنسین را برای این سفارش انتخاب کنید
    </small>
</div>



                           
                            
                            {{-- اولویت --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">اولویت</label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                    <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>عادی</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>فوری</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- آپلود تصاویر --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">تصاویر قطعه (اختیاری)</label>
                                <div class="image-upload-container border rounded p-3">
                                    <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" 
                                           multiple accept=".jpg,.jpeg,.png,.gif" onchange="validateAndPreviewImages(this)">
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        فرمت‌های مجاز: jpg, jpeg, png, gif (حداکثر حجم: ۲ مگابایت، حداکثر تعداد: ۵ تصویر)
                                    </small>
                                    @error('images')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- پیش‌نمایش تصاویر --}}
                                    <div id="imagePreview" class="row mt-3"></div>
                                </div>
                            </div>
                            
                            {{-- یادداشت‌های داخلی (فقط برای مدیر) --}}
                            @if(Auth::user() && Auth::user()->role == 'admin')
                            <div class="col-md-12 mb-3">
                                <label class="form-label">یادداشت‌های داخلی</label>
                                <textarea name="notes" rows="2" class="form-control" placeholder="یادداشت برای تکنسین یا مدیر...">{{ old('notes') }}</textarea>
                                <small class="text-muted">این یادداشت‌ها فقط توسط مدیر و تکنسین دیده می‌شود</small>
                            </div>
                            @endif
                            
                            {{-- نمایش اطلاعات قطعه انتخاب شده --}}
                            <div class="col-md-12 mb-3">
                                <div id="jewelry_info" class="alert alert-info" style="display: none;">
                                    <h6 class="alert-heading">اطلاعات قطعه انتخاب شده:</h6>
                                    <div id="jewelry_details"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>ثبت سفارش
                            </button>
                            <a href="{{ route('admin.jewelry.repair-orders.index') }}" class="btn btn-secondary">
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

@section('scripts')
<script>
// اعتبارسنجی و پیش‌نمایش تصاویر
function validateAndPreviewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        // بررسی تعداد تصاویر
        if (input.files.length > 5) {
            alert('حداکثر 5 تصویر می‌توانید انتخاب کنید');
            input.value = '';
            return;
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            
            // بررسی MIME type
            if (!allowedTypes.includes(file.type)) {
                alert(`فرمت فایل "${file.name}" مجاز نیست. فرمت‌های مجاز: jpg, jpeg, png, gif`);
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            // بررسی پسوند فایل
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                alert(`پسوند فایل "${file.name}" مجاز نیست. پسوندهای مجاز: jpg, jpeg, png, gif`);
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            // بررسی حجم فایل (حداکثر 2 مگابایت)
            if (file.size > 2 * 1024 * 1024) {
                alert(`فایل "${file.name}" بزرگتر از 2 مگابایت است`);
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            // نمایش پیش‌نمایش
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <div class="card-body p-1 text-center">
                            <small class="text-muted">${file.name.substring(0, 10)}...</small>
                            <small class="d-block text-success">${(file.size / 1024).toFixed(1)} KB</small>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
            }
            
            reader.readAsDataURL(file);
        }
    }
}

// نمایش اطلاعات قطعه انتخاب شده
document.getElementById('jewelry_id').addEventListener('change', function() {
    let jewelryId = this.value;
    let infoDiv = document.getElementById('jewelry_info');
    let detailsDiv = document.getElementById('jewelry_details');
    
    if(jewelryId) {
        fetch(`/admin/jewelry/missing_reports/get-jewelry-info/${jewelryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('خطا در دریافت اطلاعات');
                }
                return response.json();
            })
            .then(data => {
                if(data.error) {
                    infoDiv.style.display = 'none';
                    alert('خطا در دریافت اطلاعات: ' + data.error);
                } else {
                    infoDiv.style.display = 'block';
                    detailsDiv.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>نام قطعه:</strong> ${data.name || '---'}<br>
                                <strong>شماره سریال:</strong> ${data.serial_number || '---'}<br>
                                <strong>نوع فلز:</strong> ${data.metal_type || '---'}<br>
                                <strong>عیار:</strong> ${data.purity || '---'}
                            </div>
                            <div class="col-md-6">
                                <strong>وزن:</strong> ${data.weight || '0'} گرم<br>
                                <strong>نوع سنگ:</strong> ${data.stone_type || 'ندارد'}<br>
                                <strong>مالک:</strong> ${data.owner_name || '---'}<br>
                                <strong>تلفن:</strong> ${data.owner_phone || '---'}
                            </div>
                        </div>
                        ${data.certificate_file ? `
                            <hr>
                            <div class="text-center">
                                <a href="/storage/${data.certificate_file}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-image me-1"></i>مشاهده تصویر قطعه
                                </a>
                            </div>
                        ` : ''}
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                infoDiv.style.display = 'none';
                alert('خطا در ارتباط با سرور');
            });
    } else {
        infoDiv.style.display = 'none';
    }
});

// نمایش پیغام در حال ارسال فرم
document.getElementById('repairOrderForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>در حال ثبت...';
    submitBtn.disabled = true;
});

// اعتبارسنجی سمت کاربر
document.getElementById('repairOrderForm').addEventListener('submit', function(e) {
    const jewelry = document.getElementById('jewelry_id').value;
    const repairType = document.querySelector('select[name="repair_type"]').value;
    const description = document.querySelector('textarea[name="description"]').value;
    
    if (!jewelry) {
        e.preventDefault();
        alert('لطفاً قطعه مورد نظر را انتخاب کنید');
        return false;
    }
    
    if (!repairType) {
        e.preventDefault();
        alert('لطفاً نوع تعمیر را انتخاب کنید');
        return false;
    }
    
    if (!description || description.length < 10) {
        e.preventDefault();
        alert('توضیحات باید حداقل 10 کاراکتر باشد');
        return false;
    }
});
</script>
@endsection