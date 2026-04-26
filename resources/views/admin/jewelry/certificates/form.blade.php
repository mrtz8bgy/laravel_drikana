{{-- resources/views/admin/jewelry/certificates/form.blade.php --}}
@csrf

@if(isset($certificate) && $certificate->id)
    @method('PUT')
@endif

<div class="row">
    <!-- ستون سمت راست: اطلاعات اصلی -->
    <div class="col-md-6">
        <!-- مالک -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                👤 اطلاعات مالک
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="owner_id" class="form-label">مالک جواهر <span class="text-danger">*</span></label>
                    <select class="form-control @error('owner_id') is-invalid @enderror" 
                            id="owner_id" 
                            name="owner_id" 
                            required>
                        <option value="">-- انتخاب مالک --</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" 
                                {{ old('owner_id', isset($certificate) ? $certificate->owner_id : '') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->full_name }} - {{ $owner->phone }}
                                @if(!empty($owner->national_id))
                                    (کد ملی: {{ $owner->national_id }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('owner_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">اگر مالک در لیست نیست، ابتدا از بخش <a href="{{ route('admin.jewelry.owners.create') }}" target="_blank">مدیریت مالکان</a> اضافه کنید.</small>
                </div>
            </div>
        </div>

        <!-- اطلاعات شناسنامه -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                📄 اطلاعات شناسنامه
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="product_name" class="form-label">نام محصول <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('product_name') is-invalid @enderror" 
                           id="product_name" 
                           name="product_name" 
                           value="{{ old('product_name', isset($certificate) ? $certificate->product_name : '') }}"
                           required
                           placeholder="مثال: النگوی طلا ۱۸ عیار، انگشتر الماس، دستبند نقره">
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="serial_number" class="form-label">شماره سریال <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('serial_number') is-invalid @enderror" 
                           id="serial_number" 
                           name="serial_number" 
                           value="{{ old('serial_number', isset($certificate) ? $certificate->serial_number : '') }}"
                           required
                           placeholder="شماره منحصر به فرد شناسنامه">
                    @error('serial_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">این شماره برای جستجو و تأیید استفاده می‌شود.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="issued_at" class="form-label">تاریخ صدور <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('issued_at') is-invalid @enderror" 
                                   id="issued_at" 
                                   name="issued_at" 
                                   value="{{ old('issued_at', isset($certificate) && $certificate->issued_at ? $certificate->issued_at->format('Y-m-d') : date('Y-m-d')) }}"
                                   required>
                            @error('issued_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="certificate_file" class="form-label">فایل شناسنامه</label>
                            <input type="file" 
                                   class="form-control @error('certificate_file') is-invalid @enderror" 
                                   id="certificate_file" 
                                   name="certificate_file"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            @error('certificate_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر ۵ مگابایت)</small>
                            
                            @if(isset($certificate) && !empty($certificate->certificate_file))
                                <div class="mt-2">
                                    <a href="{{ Storage::url($certificate->certificate_file) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        👁 مشاهده فایل فعلی
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ستون سمت چپ: مشخصات فنی -->
    <div class="col-md-6">
        <!-- مشخصات فنی -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                💎 مشخصات فنی جواهر
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="metal_type" class="form-label">نوع فلز <span class="text-danger">*</span></label>
                            <select class="form-control @error('metal_type') is-invalid @enderror" 
                                    id="metal_type" 
                                    name="metal_type" 
                                    required>
                                <option value="">-- انتخاب کنید --</option>
                                <option value="طلا" {{ old('metal_type', isset($certificate) ? $certificate->metal_type : '') == 'طلا' ? 'selected' : '' }}>طلا</option>
                                <option value="نقره" {{ old('metal_type', isset($certificate) ? $certificate->metal_type : '') == 'نقره' ? 'selected' : '' }}>نقره</option>
                                <option value="پلاتین" {{ old('metal_type', isset($certificate) ? $certificate->metal_type : '') == 'پلاتین' ? 'selected' : '' }}>پلاتین</option>
                                <option value="سایر" {{ old('metal_type', isset($certificate) ? $certificate->metal_type : '') == 'سایر' ? 'selected' : '' }}>سایر</option>
                            </select>
                            @error('metal_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="purity" class="form-label">عیار (درصد)</label>
                            <input type="number" 
                                   step="1" 
                                   min="0" 
                                   max="1000"
                                   class="form-control @error('purity') is-invalid @enderror" 
                                   id="purity" 
                                   name="purity" 
                                   value="{{ old('purity', isset($certificate) ? $certificate->purity : '') }}"
                                   placeholder="مثال: 750 برای طلای ۱۸ عیار">
                            @error('purity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">عدد بین 0 تا 1000 (مثلاً 750 = 75% = 18 عیار)</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="weight" class="form-label">وزن (گرم)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0"
                                   class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight', isset($certificate) ? $certificate->weight : '') }}"
                                   placeholder="مثال: 12.5">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stone_type" class="form-label">نوع سنگ</label>
                            <select class="form-control @error('stone_type') is-invalid @enderror" 
                                    id="stone_type" 
                                    name="stone_type">
                                <option value="">-- انتخاب کنید --</option>
                                <option value="الماس" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'الماس' ? 'selected' : '' }}>الماس</option>
                                <option value="یاقوت" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'یاقوت' ? 'selected' : '' }}>یاقوت</option>
                                <option value="زمررد" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'زمررد' ? 'selected' : '' }}>زمررد</option>
                                <option value="یاقوت کبود" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'یاقوت کبود' ? 'selected' : '' }}>یاقوت کبود</option>
                                <option value="مروارید" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'مروارید' ? 'selected' : '' }}>مروارید</option>
                                <option value="فیروزه" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'فیروزه' ? 'selected' : '' }}>فیروزه</option>
                                <option value="عقیق" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'عقیق' ? 'selected' : '' }}>عقیق</option>
                                <option value="سایر" {{ old('stone_type', isset($certificate) ? $certificate->stone_type : '') == 'سایر' ? 'selected' : '' }}>سایر</option>
                            </select>
                            @error('stone_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- توضیحات و وضعیت -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                📝 توضیحات و وضعیت
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="description" class="form-label">توضیحات</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4"
                              placeholder="هرگونه توضیح اضافی درباره جواهر، طرح، ویژگی‌های خاص، تاریخچه، etc.">{{ old('description', isset($certificate) ? $certificate->description : '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($certificate) && $certificate->id)
                <div class="mb-3">
                    <div class="form-check form-switch">
                        @php
                            $isActive = old('is_active', isset($certificate) ? $certificate->is_active : true);
                        @endphp
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ $isActive ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            شناسنامه فعال است
                        </label>
                    </div>
                    <small class="text-muted">در صورت غیرفعال کردن، شناسنامه در جستجوها نمایش داده نمی‌شود.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- دکمه‌های اقدام -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                @php
                    $backUrl = isset($certificate) && $certificate->id 
                        ? route('admin.jewelry.certificates.show', $certificate) 
                        : route('admin.jewelry.certificates.index');
                @endphp
                <a href="{{ $backUrl }}" class="btn btn-secondary">
                    ✖ انصراف
                </a>
            </div>
            
            <div class="btn-group">
                @if(isset($certificate) && $certificate->id)
                    <button type="submit" class="btn btn-primary">
                        💾 بروزرسانی شناسنامه
                    </button>
                @else
                    <button type="submit" class="btn btn-success">
                        ➕ ثبت شناسنامه جدید
                    </button>
                @endif
                
                @if(isset($certificate) && $certificate->id)
                    <a href="{{ route('admin.jewelry.certificates.print', $certificate) }}" 
                       class="btn btn-outline-info" target="_blank">
                        🖨 چاپ
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // اعتبارسنجی شماره سریال
        const serialNumberInput = document.getElementById('serial_number');
        if (serialNumberInput) {
            serialNumberInput.addEventListener('blur', function() {
                const serialNumber = this.value.trim();
                if (serialNumber.length > 0) {
                    console.log('Checking serial number:', serialNumber);
                }
            });
        }
        
        // تغییرات بر اساس نوع فلز
        const metalTypeSelect = document.getElementById('metal_type');
        const purityField = document.getElementById('purity');
        
        if (metalTypeSelect && purityField) {
            metalTypeSelect.addEventListener('change', function() {
                const metalType = this.value;
                
                if (metalType === 'طلا') {
                    purityField.placeholder = 'مثال: 750 برای طلای ۱۸ عیار';
                } else if (metalType === 'نقره') {
                    purityField.placeholder = 'مثال: 925 برای نقره استرلینگ';
                } else if (metalType === 'پلاتین') {
                    purityField.placeholder = 'مثال: 950 برای پلاتین';
                } else {
                    purityField.placeholder = 'درصد خلوص';
                }
            });
            
            // پیش‌بارگذاری metal_type
            if (metalTypeSelect.value) {
                metalTypeSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // اعتبارسنجی فرم قبل از ارسال
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const ownerId = document.getElementById('owner_id')?.value;
                const productName = document.getElementById('product_name')?.value.trim();
                const serialNumber = document.getElementById('serial_number')?.value.trim();
                const metalType = document.getElementById('metal_type')?.value;
                
                if (!ownerId) {
                    alert('لطفا مالک جواهر را انتخاب کنید.');
                    e.preventDefault();
                    return false;
                }
                
                if (!productName) {
                    alert('لطفا نام محصول را وارد کنید.');
                    e.preventDefault();
                    return false;
                }
                
                if (!serialNumber) {
                    alert('لطفا شماره سریال را وارد کنید.');
                    e.preventDefault();
                    return false;
                }
                
                if (!metalType) {
                    alert('لطفا نوع فلز را انتخاب کنید.');
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });
        }
    });
</script>