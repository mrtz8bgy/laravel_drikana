<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چاپ شناسنامه: {{ $certificate->serial_number }}</title>
    <style>
        * {
            font-family: 'Tahoma', 'Arial', sans-serif;
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        @media print {
            body {
                padding: 0;
                background-color: white;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 15mm;
            }
        }
        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border: 2px solid #ccc;
            padding: 30px;
            position: relative;
            page-break-inside: avoid;
        }
        .certificate-header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .certificate-subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .certificate-logo {
            position: absolute;
            left: 30px;
            top: 30px;
            width: 100px;
            opacity: 0.1;
        }
        
        /* استایل‌های عکس */
        .jewelry-image-section {
            text-align: center;
            margin: 25px 0;
            page-break-inside: avoid;
        }
        .jewelry-image-container {
            display: inline-block;
            padding: 10px;
            border: 2px solid #f1c40f;
            border-radius: 10px;
            background-color: #fffdf6;
            margin-bottom: 10px;
        }
        .jewelry-image {
            max-width: 300px;
            max-height: 250px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .image-placeholder {
            width: 300px;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background-color: #f9f9f9;
        }
        .image-placeholder-icon {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 10px;
        }
        .image-caption {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 8px;
        }
        .image-description {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 3px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
            font-size: 14px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .owner-signature {
            text-align: left;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }
        .stamp {
            position: absolute;
            right: 40px;
            bottom: 40px;
            width: 120px;
            height: 120px;
            border: 2px dashed #c0392b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            opacity: 0.8;
        }
        .stamp-text {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #c0392b;
        }
        .print-controls {
            text-align: center;
            margin: 15px 0 25px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        .btn-print {
            background-color: #3498db;
            color: white;
        }
        .btn-back {
            background-color: #95a5a6;
            color: white;
        }
        .btn-download {
            background-color: #27ae60;
            color: white;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70px;
            color: rgba(0,0,0,0.04);
            font-weight: bold;
            z-index: -1;
            white-space: nowrap;
            pointer-events: none;
        }
        .certificate-number {
            position: absolute;
            top: 25px;
            right: 25px;
            font-size: 16px;
            color: #7f8c8d;
        }
        .print-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .print-option {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
        }
        .debug-info {
            font-size: 11px;
            color: #999;
            text-align: center;
            margin-top: 5px;
            font-family: monospace;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            margin-left: 5px;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- کنترل‌های چاپ -->
    <div class="print-controls no-print">
        <div class="print-options">
            <label class="print-option">
                <input type="checkbox" id="showImage" checked> نمایش عکس
            </label>
            <label class="print-option">
                <input type="checkbox" id="showWatermark" checked> واترمارک
            </label>
            <label class="print-option">
                <input type="checkbox" id="showStamp" checked> مهر و امضا
            </label>
        </div>
        
        <div>
            <button onclick="printCertificate()" class="btn btn-print">
                <i class="fas fa-print"></i> چاپ شناسنامه
            </button>
            <button onclick="window.history.back()" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> بازگشت
            </button>
            <button onclick="downloadImage()" class="btn btn-download" id="downloadBtn">
                <i class="fas fa-download"></i> دانلود عکس
            </button>
        </div>
        
        <div class="debug-info no-print" id="debugInfo">
            شناسنامه: {{ $certificate->serial_number }} | 
            فایل: {{ $certificate->certificate_file ? basename($certificate->certificate_file) : 'ندارد' }}
        </div>
    </div>

    <!-- شناسنامه -->
    <div class="certificate-container" id="certificateContainer">
        <!-- واترمارک -->
        <div class="watermark" id="watermark">شناسنامه طلا و جواهر</div>
        
        <!-- لوگو -->
        <div class="certificate-logo">
            <svg viewBox="0 0 100 100" width="80" height="80">
                <circle cx="50" cy="50" r="40" fill="none" stroke="#f1c40f" stroke-width="2"/>
                <polygon points="50,20 58,35 75,35 63,48 68,68 50,58 32,68 37,48 25,35 42,35" fill="#f1c40f"/>
            </svg>
        </div>
        
        <!-- شماره شناسنامه -->
        <div class="certificate-number">
            شماره: <strong>{{ $certificate->serial_number }}</strong>
        </div>
        
        <!-- هدر -->
        <div class="certificate-header">
            <div class="certificate-title">شناسنامه طلا و جواهر</div>
            <div class="certificate-subtitle">گواهی اصالت و مالکیت</div>
        </div>
        
        <!-- عکس جواهر -->
        <div class="jewelry-image-section" id="imageSection">
            @php
                // ایجاد URL صحیح برای عکس
                $hasImage = false;
                $imageUrl = null;
                $imageExtension = null;
                $imageFilename = null;
                $imagePath = null;
                
                if ($certificate->certificate_file) {
                    $imageFilename = basename($certificate->certificate_file);
                    $imagePath = $certificate->certificate_file;
                    
                        // تلاش برای ساخت URL یا درون‌ریزی تصویر به عنوان data-uri
                        try {
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                                // مسیر قابل دسترس از طریق دیسک public (معمولاً /storage/...)
                                $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath);
                            } elseif (file_exists(public_path('storage/' . $imagePath))) {
                                // اگر لینک سمبولیک ساخته شده باشد
                                $imageUrl = asset('storage/' . $imagePath);
                            } else {
                                // تلاش برای خواندن فایل و درون‌ریزی به عنوان data-uri (قابل استفاده حتی بدون لینک سمبولیک)
                                $physicalPath = storage_path('app/public/' . $imagePath);
                                if (file_exists($physicalPath) && is_readable($physicalPath)) {
                                    $contents = file_get_contents($physicalPath);
                                    $base64 = base64_encode($contents);
                                    $imageUrl = 'data:image/' . $extension . ';base64,' . $base64;
                                } else {
                                    $imageUrl = null; // نمی‌توان تصویر را پیدا کرد
                                }
                            }
                        } catch (\Exception $e) {
                            $imageUrl = null;
                        }
                    
                        // بررسی فرمت فایل
                    $extension = pathinfo($imageFilename, PATHINFO_EXTENSION);
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                    
                    if (in_array(strtolower($extension), $imageExtensions)) {
                        $hasImage = true;
                        $imageExtension = $extension;
                    }
                }
            @endphp
            
            @if($hasImage && $imageUrl)
                <div class="jewelry-image-container">
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $certificate->product_name }}"
                         class="jewelry-image"
                         id="jewelryImage"
                         onload="imageLoadedSuccessfully(this)"
                         onerror="imageFailedToLoad(this)"
                         data-filename="{{ $imageFilename }}"
                         data-path="{{ $imagePath }}"
                         data-url="{{ $imageUrl }}">
                </div>
                <div class="image-caption">عکس جواهر</div>
                <div class="image-description">
                    {{ $certificate->product_name }}
                    @if($imageExtension)
                        <span style="font-size: 11px; color: #999;">
                            ({{ strtoupper($imageExtension) }})
                        </span>
                    @endif
                    <span id="imageStatus" class="status-badge status-success">بارگذاری شد</span>
                </div>
            @else
                <div class="image-placeholder" id="imagePlaceholder">
                    <div class="image-placeholder-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div style="color: #999; font-size: 14px; text-align: center; padding: 0 10px;">
                        @if($certificate->certificate_file)
                            فایل: {{ $imageFilename }}<br>
                            <small style="color: #e74c3c; font-size: 12px;">
                                @if(!$hasImage)
                                    (فرمت تصویری نیست)
                                @else
                                    (خطا در نمایش)
                                @endif
                            </small>
                        @else
                            عکس جواهر بارگذاری نشده است
                        @endif
                    </div>
                </div>
                <div class="image-caption">عکس جواهر</div>
                <div class="image-description">{{ $certificate->product_name }}</div>
            @endif
        </div>
        
        <!-- اطلاعات شناسنامه -->
        <div class="info-section">
            <div class="section-title">مشخصات جواهر</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">شماره سریال:</span>
                    <span class="info-value">{{ $certificate->serial_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">نام محصول:</span>
                    <span class="info-value">{{ $certificate->product_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">نوع فلز:</span>
                    <span class="info-value">{{ $certificate->metal_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">عیار:</span>
                    <span class="info-value">
                        @if($certificate->purity)
                            {{ $certificate->purity }} ({{ round($certificate->purity/10, 1) }} عیار)
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">وزن:</span>
                    <span class="info-value">
                        @if($certificate->weight)
                            {{ number_format($certificate->weight, 2) }} گرم
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">نوع سنگ:</span>
                    <span class="info-value">{{ $certificate->stone_type ?: '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاریخ صدور:</span>
                    <span class="info-value">
                        @if($certificate->issued_at)
                            {{ $certificate->issued_at->format('Y/m/d') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">وضعیت:</span>
                    <span class="info-value">
                        @php
                            $isActive = isset($certificate->is_active) ? $certificate->is_active : true;
                        @endphp
                        @if($isActive)
                            <span class="status-badge status-success">فعال</span>
                        @else
                            <span class="status-badge status-error">غیرفعال</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <!-- اطلاعات مالک -->
        <div class="info-section">
            <div class="section-title">مشخصات مالک</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">نام کامل:</span>
                    <span class="info-value">
                        @if($certificate->owner)
                            {{ $certificate->owner->full_name }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">کد ملی:</span>
                    <span class="info-value">
                        @if($certificate->owner && $certificate->owner->national_id)
                            {{ $certificate->owner->national_id }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">تلفن:</span>
                    <span class="info-value">
                        @if($certificate->owner)
                            {{ $certificate->owner->phone }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">ایمیل:</span>
                    <span class="info-value">
                        @if($certificate->owner && $certificate->owner->email)
                            {{ $certificate->owner->email }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">آدرس:</span>
                    <span class="info-value" style="grid-column: span 2;">
                        @if($certificate->owner && $certificate->owner->address)
                            {{ $certificate->owner->address }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <!-- توضیحات -->
        @if($certificate->description)
        <div class="info-section">
            <div class="section-title">توضیحات</div>
            <div class="info-item">
                <div style="line-height: 1.6; text-align: justify; font-size: 14px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                    {{ $certificate->description }}
                </div>
            </div>
        </div>
        @endif
        
        <!-- مهر و امضا -->
        <div class="stamp" id="stamp">
            <div class="stamp-text">
                مهر و امضا<br>
                <small style="font-size: 11px;">مدیریت جواهرات</small>
            </div>
        </div>
        
        <!-- امضای مالک -->
        <div class="owner-signature">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <p style="margin-bottom: 40px; font-weight: bold;">امضاء مالک:</p>
                    <div style="width: 200px; border-top: 2px solid #333; margin-top: 20px;"></div>
                    <div style="margin-top: 5px; font-size: 14px; color: #666;">
                        {{ $certificate->owner ? $certificate->owner->full_name : '________' }}
                    </div>
                </div>
                <div style="flex: 1; text-align: center;">
                    <p style="margin-bottom: 40px; font-weight: bold;">مهر و امضای شرکت:</p>
                    <div style="width: 150px; height: 80px; border: 2px dashed #ccc; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #999; font-size: 12px;">مهر شرکت</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- پانویس -->
        <div style="margin-top: 30px; font-size: 11px; color: #7f8c8d; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
            @if($hasImage)
                <p><i class="fas fa-camera"></i> عکس جواهر ضمیمه شده است. برای مشاهده با کیفیت بالا، فایل اصلی را دانلود کنید.</p>
            @endif
            <p>این شناسنامه به منزله گواهی اصالت و مالکیت جواهر می‌باشد. هرگونه کپی‌برداری و جعل پیگرد قانونی دارد.</p>
            <p>تاریخ چاپ: {{ date('Y/m/d ساعت H:i') }}</p>
            <p style="font-size: 10px; color: #95a5a6; margin-top: 5px;">
                شماره سریال برای تأیید اصالت: <strong>{{ $certificate->serial_number }}</strong> | 
                شناسه: <strong>{{ $certificate->id }}</strong>
            </p>
        </div>
    </div>

    <script>
        // تنظیمات چاپ
        let printSettings = {
            showImage: true,
            showWatermark: true,
            showStamp: true
        };
        
        // مقداردهی اولیه از چک‌باکس‌ها
        document.addEventListener('DOMContentLoaded', function() {
            const showImageCheckbox = document.getElementById('showImage');
            const showWatermarkCheckbox = document.getElementById('showWatermark');
            const showStampCheckbox = document.getElementById('showStamp');
            const downloadBtn = document.getElementById('downloadBtn');
            
            if (showImageCheckbox) {
                printSettings.showImage = showImageCheckbox.checked;
                showImageCheckbox.addEventListener('change', function() {
                    printSettings.showImage = this.checked;
                    document.getElementById('imageSection').style.display = this.checked ? 'block' : 'none';
                });
            }
            
            if (showWatermarkCheckbox) {
                printSettings.showWatermark = showWatermarkCheckbox.checked;
                showWatermarkCheckbox.addEventListener('change', function() {
                    printSettings.showWatermark = this.checked;
                    document.getElementById('watermark').style.display = this.checked ? 'block' : 'none';
                });
            }
            
            if (showStampCheckbox) {
                printSettings.showStamp = showStampCheckbox.checked;
                showStampCheckbox.addEventListener('change', function() {
                    printSettings.showStamp = this.checked;
                    document.getElementById('stamp').style.display = this.checked ? 'block' : 'none';
                });
            }
            
            // غیرفعال کردن دکمه دانلود اگر عکس نباشد
            @if(!$hasImage)
                if (downloadBtn) {
                    downloadBtn.disabled = true;
                    downloadBtn.style.opacity = '0.5';
                    downloadBtn.style.cursor = 'not-allowed';
                    downloadBtn.title = 'عکسی برای دانلود وجود ندارد';
                }
            @endif
            
            // تست بارگذاری عکس
            const image = document.getElementById('jewelryImage');
            if (image) {
                console.log('عکس شناسایی شد:', image.dataset.filename);
                console.log('آدرس عکس:', image.dataset.url);
                
                // اگر عکس قبلاً لود شده
                if (image.complete) {
                    if (image.naturalHeight > 0) {
                        imageLoadedSuccessfully(image);
                    } else {
                        imageFailedToLoad(image);
                    }
                }
            }
        });
        
        // تابع چاپ
        function printCertificate() {
            // ذخیره تنظیمات فعلی
            const originalDisplay = {
                imageSection: document.getElementById('imageSection').style.display,
                watermark: document.getElementById('watermark').style.display,
                stamp: document.getElementById('stamp').style.display
            };
            
            // اعمال تنظیمات چاپ
            document.getElementById('imageSection').style.display = printSettings.showImage ? 'block' : 'none';
            document.getElementById('watermark').style.display = printSettings.showWatermark ? 'block' : 'none';
            document.getElementById('stamp').style.display = printSettings.showStamp ? 'block' : 'none';
            
            // پنهان کردن کنترل‌های چاپ
            const printControls = document.querySelector('.print-controls');
            const originalPrintControlsDisplay = printControls.style.display;
            printControls.style.display = 'none';
            
            // اضافه کردن اطلاعات چاپ
            const printInfo = document.createElement('div');
            printInfo.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #f8f9fa;
                padding: 8px;
                text-align: center;
                font-size: 11px;
                color: #666;
                border-bottom: 1px solid #ddd;
                z-index: 10000;
                display: none;
                font-family: Tahoma;
            `;
            printInfo.innerHTML = `
                <div>شناسنامه شماره: {{ $certificate->serial_number }} | {{ $certificate->product_name }}</div>
                <div>تاریخ چاپ: ${new Date().toLocaleDateString('fa-IR')} ساعت ${new Date().toLocaleTimeString('fa-IR')}</div>
            `;
            printInfo.className = 'no-print print-info';
            document.body.appendChild(printInfo);
            
            // استایل برای نمایش اطلاعات چاپ فقط هنگام چاپ
            const printStyle = document.createElement('style');
            printStyle.innerHTML = `
                @media print {
                    .print-info {
                        display: block !important;
                    }
                }
            `;
            document.head.appendChild(printStyle);
            
            // تاخیر برای اطمینان از اعمال تغییرات
            setTimeout(() => {
                window.print();
                
                // بازگردانی تنظیمات پس از چاپ
                setTimeout(() => {
                    document.getElementById('imageSection').style.display = originalDisplay.imageSection;
                    document.getElementById('watermark').style.display = originalDisplay.watermark;
                    document.getElementById('stamp').style.display = originalDisplay.stamp;
                    printControls.style.display = originalPrintControlsDisplay;
                    
                    // حذف عناصر اضافه شده
                    if (printInfo.parentNode) {
                        printInfo.parentNode.removeChild(printInfo);
                    }
                    if (printStyle.parentNode) {
                        printStyle.parentNode.removeChild(printStyle);
                    }
                }, 100);
            }, 100);
        }
        
        // دانلود عکس
        function downloadImage() {
            @if($hasImage && $imageUrl)
                try {
                    const imageUrl = '{{ $imageUrl }}';
                    const filename = 'جواهر-{{ $certificate->serial_number }}-{{ $certificate->product_name }}.{{ $imageExtension }}';
                    const cleanedFilename = filename.replace(/[^\w\-. ]/g, '_');
                    
                    const link = document.createElement('a');
                    link.href = imageUrl;
                    link.download = cleanedFilename;
                    link.target = '_blank';
                    
                    // اضافه کردن رویداد برای پیگیری دانلود
                    link.addEventListener('click', function() {
                        console.log('در حال دانلود عکس:', cleanedFilename);
                        showMessage('در حال دانلود عکس...', 'success');
                    });
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // نمایش پیام موفقیت
                    showMessage(`عکس "${cleanedFilename}" در حال دانلود است`, 'success');
                    
                } catch (error) {
                    console.error('خطا در دانلود عکس:', error);
                    showMessage('خطا در دانلود عکس. لطفاً مجدداً تلاش کنید.', 'error');
                }
            @else
                showMessage('عکسی برای دانلود وجود ندارد.', 'warning');
            @endif
        }
        
        // مدیریت لود عکس
        function imageLoadedSuccessfully(imgElement) {
            console.log('✅ عکس با موفقیت بارگذاری شد:', imgElement.dataset.filename);
            
            // نمایش وضعیت
            const statusElement = document.getElementById('imageStatus');
            if (statusElement) {
                statusElement.textContent = 'بارگذاری شد';
                statusElement.className = 'status-badge status-success';
            }
            
            // فعال کردن دکمه دانلود
            const downloadBtn = document.getElementById('downloadBtn');
            if (downloadBtn) {
                downloadBtn.disabled = false;
                downloadBtn.style.opacity = '1';
                downloadBtn.style.cursor = 'pointer';
                downloadBtn.title = 'دانلود عکس جواهر';
            }
        }
        
        function imageFailedToLoad(imgElement) {
            console.error('❌ خطا در بارگذاری عکس:', imgElement ? imgElement.dataset.filename : 'نامشخص');
            
            // نمایش وضعیت خطا
            const statusElement = document.getElementById('imageStatus');
            if (statusElement) {
                statusElement.textContent = 'خطا در بارگذاری';
                statusElement.className = 'status-badge status-error';
            }
            
            // جایگزینی با placeholder
            const imageSection = document.getElementById('imageSection');
            if (imageSection && imgElement) {
                const filename = imgElement.dataset.filename || 'نامشخص';
                const path = imgElement.dataset.path || '';
                const url = imgElement.dataset.url || '';
                
                imageSection.innerHTML = `
                    <div class="image-placeholder" id="imagePlaceholder">
                        <div class="image-placeholder-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div style="color: #e74c3c; font-size: 14px; text-align: center; padding: 0 10px;">
                            خطا در نمایش عکس<br>
                            <small style="color: #666; font-size: 12px;">
                                فایل: ${filename}<br>
                                مسیر: ${path}
                            </small>
                        </div>
                        <button onclick="testImageUrl('${url}', '${filename}')" class="btn" style="background-color: #e74c3c; color: white; padding: 5px 15px; font-size: 12px; margin-top: 10px;">
                            <i class="fas fa-bug"></i> تست آدرس
                        </button>
                    </div>
                    <div class="image-caption">عکس جواهر</div>
                    <div class="image-description">{{ $certificate->product_name }}</div>
                `;
            }
        }
        
        // تست آدرس عکس
        function testImageUrl(url, filename) {
            console.log('آدرس تست:', url);
            
            // باز کردن در تب جدید
            window.open(url, '_blank');
            
            // نمایش پیام
            showMessage(`در حال تست آدرس: ${url}`, 'info');
            
            // تست با fetch
            fetch(url, { method: 'HEAD', mode: 'no-cors' })
                .then(() => {
                    showMessage('آدرس معتبر است. لطفاً منتظر بارگذاری عکس باشید.', 'success');
                })
                .catch(error => {
                    console.error('خطای fetch:', error);
                    showMessage('خطا در دسترسی به آدرس. ممکن است فایل وجود نداشته باشد.', 'error');
                });
        }
        
        // نمایش پیام
        function showMessage(message, type = 'info') {
            const messageDiv = document.createElement('div');
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 5px;
                color: white;
                font-size: 14px;
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                box-shadow: 0 3px 10px rgba(0,0,0,0.2);
                max-width: 300px;
            `;
            
            // رنگ بر اساس نوع
            switch(type) {
                case 'success':
                    messageDiv.style.backgroundColor = '#27ae60';
                    break;
                case 'error':
                    messageDiv.style.backgroundColor = '#e74c3c';
                    break;
                case 'warning':
                    messageDiv.style.backgroundColor = '#f39c12';
                    break;
                case 'info':
                default:
                    messageDiv.style.backgroundColor = '#3498db';
            }
            
            messageDiv.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(messageDiv);
            
            // حذف خودکار پس از 5 ثانیه
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        if (messageDiv.parentNode) {
                            messageDiv.parentNode.removeChild(messageDiv);
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        // مدیریت کلیدهای صفحه‌کلید
        document.addEventListener('keydown', function(event) {
            // Ctrl+P یا Cmd+P برای چاپ
            if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
                event.preventDefault();
                printCertificate();
                showMessage('در حال چاپ شناسنامه...', 'info');
            }
            // دکمه Escape برای بازگشت
            if (event.key === 'Escape') {
                window.history.back();
            }
            // Ctrl+S یا Cmd+S برای دانلود
            if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                event.preventDefault();
                downloadImage();
            }
        });
        
        // اضافه کردن animation
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>