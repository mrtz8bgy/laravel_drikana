@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4>ثبت سرقت یا مفقودی طلا و جواهر</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.jewelry.missing_reports.store') }}">
                        @csrf
                        
                        {{-- انتخاب قطعه --}}
                        <div class="form-group">
                            <label>انتخاب قطعه *</label>
                            <select name="jewelry_id" id="jewelry_id" class="form-control @error('jewelry_id') is-invalid @enderror" required>
                                <option value="">انتخاب کنید</option>
                                @foreach($jewelries as $jewelry)
                                    <option value="{{ $jewelry->id }}" {{ old('jewelry_id') == $jewelry->id ? 'selected' : '' }}>
                                        {{ $jewelry->product_name ?? 'بدون نام' }} - 
                                        {{ $jewelry->serial_number ?? 'بدون سریال' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jewelry_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- نمایش اطلاعات کامل قطعه و مالک --}}
                        <div id="jewelry_info" class="alert alert-info" style="display:none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3">📋 اطلاعات قطعه انتخاب شده:</h5>
                                    
                                    {{-- تصویر قطعه (اگر وجود داشته باشد) --}}
                                    <div id="jewelry_image_container" style="display:none;" class="text-center mb-3">
                                        <img id="jewelry_image" src="" alt="تصویر قطعه" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    
                                    {{-- اطلاعات جزئی --}}
                                    <div id="jewelry_details" class="row">
                                        {{-- اطلاعات به صورت داینامیک توسط جاوااسکریپت پر می‌شود --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- نوع گزارش --}}
                        <div class="form-group">
                            <label>نوع گزارش *</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_lost" value="lost" {{ old('type') == 'lost' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_lost">مفقودی</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_theft" value="theft" {{ old('type') == 'theft' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_theft">سرقت</label>
                            </div>
                        </div>
                        
                        {{-- موقعیت مکانی --}}
                        <div class="form-group">
                            <label>آدرس تقریبی محل حادثه</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" 
                                   placeholder="مثال: خیابان ولیعصر، نرسیده به چهارراه ...">
                            @error('location')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- توضیحات --}}
                        <div class="form-group">
                            <label>توضیحات تکمیلی</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="هر توضیح دیگری که می‌تواند کمک کند...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-warning">ثبت گزارش</button>
                        <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="btn btn-secondary">انصراف</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('jewelry_id').addEventListener('change', function() {
    let jewelryId = this.value;
    let infoDiv = document.getElementById('jewelry_info');
    let detailsP = document.getElementById('jewelry_details');
    let imageContainer = document.getElementById('jewelry_image_container');
    let jewelryImage = document.getElementById('jewelry_image');
    
    if(jewelryId) {
        fetch(`/admin/jewelry/missing_reports/get-jewelry-info/${jewelryId}`)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    infoDiv.style.display = 'none';
                    alert('خطا در دریافت اطلاعات: ' + data.error);
                } else {
                    infoDiv.style.display = 'block';
                    
                    // نمایش تصویر اگر وجود داشته باشد
                    if(data.certificate_file) {
                        let imageUrl = `/storage/${data.certificate_file}`;
                        jewelryImage.src = imageUrl;
                        imageContainer.style.display = 'block';
                    } else {
                        imageContainer.style.display = 'none';
                    }
                    
                    // ساخت HTML برای نمایش اطلاعات
                    let html = `
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-header bg-info text-white py-2">
                                    <strong>🏷️ مشخصات قطعه</strong>
                                </div>
                                <div class="card-body py-2">
                                    <table class="table table-sm table-bordered mb-0">
                                        <tr>
                                            <th width="120">نام قطعه:</th>
                                            <td>${data.name}</td>
                                        </tr>
                                        <tr>
                                            <th>شماره سریال:</th>
                                            <td>${data.serial_number}</td>
                                        </tr>
                                        <tr>
                                            <th>نوع فلز:</th>
                                            <td>${data.metal_type}</td>
                                        </tr>
                                        <tr>
                                            <th>عیار:</th>
                                            <td>${data.purity}</td>
                                        </tr>
                                        <tr>
                                            <th>وزن:</th>
                                            <td>${data.weight} گرم</td>
                                        </tr>
                                        <tr>
                                            <th>نوع سنگ:</th>
                                            <td>${data.stone_type}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-header bg-success text-white py-2">
                                    <strong>👤 اطلاعات مالک</strong>
                                </div>
                                <div class="card-body py-2">
                                    <table class="table table-sm table-bordered mb-0">
                                        <tr>
                                            <th width="120">نام مالک:</th>
                                            <td>${data.owner_name}</td>
                                        </tr>
                                        <tr>
                                            <th>تلفن:</th>
                                            <td>${data.owner_phone || '---'}</td>
                                        </tr>
                                        <tr>
                                            <th>کد ملی:</th>
                                            <td>${data.owner_national_id || '---'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    detailsP.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                infoDiv.style.display = 'none';
                alert('خطا در ارتباط با سرور');
            });
    } else {
        infoDiv.style.display = 'none';
        imageContainer.style.display = 'none';
    }
});

// اجرای اولیه اگر مقدار old وجود داشته باشد
document.addEventListener('DOMContentLoaded', function() {
    let selectedValue = '{{ old('jewelry_id') }}';
    if(selectedValue) {
        document.getElementById('jewelry_id').value = selectedValue;
        // ایجاد تغییر مصنوعی برای نمایش اطلاعات
        let event = new Event('change');
        document.getElementById('jewelry_id').dispatchEvent(event);
    }
});
</script>

<style>
/* استایل برای نمایش بهتر اطلاعات */
.card-header {
    padding: 8px 15px;
}
.table-sm th, .table-sm td {
    padding: 5px;
    font-size: 0.9rem;
}
#jewelry_info {
    max-height: 400px;
    overflow-y: auto;
}
</style>
@endsection