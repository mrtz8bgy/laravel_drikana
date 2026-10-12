{{-- resources/views/admin/jewelry/missing_reports/print.blade.php --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چاپ گزارش سرقت/مفقودی</title>
{{-- استایل چاپ به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی).
     این فایل مشترک میان همه صفحات چاپی است. --}}
<link rel="stylesheet" href="{{ asset('css/drikana/print.css') }}">
    {{-- کلاس‌های استخراج‌شده از استایل درون‌خطی ویوها (بازبینی L-04) --}}
    <link rel="stylesheet" href="{{ asset('css/drikana/utilities.css') }}">
</head>
<body>
    <div class="no-print u-text-align-left-margin-bottom-20px">
        <button class="u-padding-10px-20px-background-3498db-color-white-border--cce4b2" onclick="window.print()">
            چاپ
        </button>
        <button class="u-padding-10px-20px-background-95a5a6-color-white-border--81fe04" onclick="window.close()">
            بستن
        </button>
    </div>
    
    <div class="header">
        <h1 class="title">گزارش سرقت/مفقودی طلا و جواهر</h1>
        <div class="report-number">شماره گزارش: {{ $report->id }}</div>
        <p class="subtitle">تاریخ چاپ: {{ jdate(now())->format('Y/m/d H:i') }}</p>
    </div>
    
    {{-- نمایش تصویر قطعه (در صورت وجود) --}}
    @if($report->jewelry->certificate_file)
        @php
            $extension = pathinfo($report->jewelry->certificate_file, PATHINFO_EXTENSION);
            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
        @endphp
        
        @if($isImage)
            <div class="image-container">
                <img src="{{ asset('storage/' . $report->jewelry->certificate_file) }}" 
                     alt="تصویر قطعه" 
                     class="jewelry-image">
            </div>
        @endif
    @endif
    
    <div class="section">
        <h3 class="section-header">اطلاعات گزارش</h3>
        <div class="section-body">
            <table>
                <tr>
                    <th>نوع گزارش</th>
                    <td>
                        @if($report->type == 'theft')
                            <span class="badge badge-danger">سرقت</span>
                        @else
                            <span class="badge badge-warning">مفقودی</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>وضعیت</th>
                    <td>{{ $report->status == 'pending' ? 'در انتظار بررسی' : 'پیدا شده' }}</td>
                </tr>
                <tr>
                    <th>تاریخ ثبت</th>
                    <td>{{ jdate($report->created_at)->format('Y/m/d H:i') }}</td>
                </tr>
                <tr>
                    <th>آخرین بروزرسانی</th>
                    <td>{{ jdate($report->updated_at)->format('Y/m/d H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="section">
        <h3 class="section-header">اطلاعات مالک</h3>
        <div class="section-body">
            @php
                $owner = $report->jewelry->owner ?? null;
            @endphp
            
            <table>
                <tr>
                    <th>نام و نام خانوادگی</th>
                    <td>{{ $owner->full_name ?? '---' }}</td>
                </tr>
                <tr>
                    <th>کد ملی</th>
                    <td>{{ $owner->national_id ?? '---' }}</td>
                </tr>
                <tr>
                    <th>تلفن تماس</th>
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
                
                {{-- اطلاعات کاربری مرتبط --}}
                @if($owner && $owner->user)
                <tr>
                    <th>نام کاربری</th>
                    <td>{{ $owner->user->name ?? '---' }}</td>
                </tr>
                <tr>
                    <th>ایمیل کاربری</th>
                    <td>{{ $owner->user->email ?? '---' }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    
    <div class="section">
        <h3 class="section-header">مشخصات قطعه</h3>
        <div class="section-body">
            <table>
                <tr>
                    <th>نام قطعه</th>
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
                <tr>
                    <th>وزن</th>
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
                    <th>وضعیت قطعه</th>
                    <td>
                        @if($report->jewelry->is_active)
                            <span class="badge u-background-27ae60-color-white">فعال</span>
                        @else
                            <span class="badge u-background-e74c3c-color-white">غیرفعال</span>
                        @endif
                    </td>
                </tr>
                @if($report->jewelry->description)
                <tr>
                    <th>توضیحات قطعه</th>
                    <td>{{ $report->jewelry->description }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    
    @if($report->location || $report->description)
    <div class="section">
        <h3 class="section-header">اطلاعات حادثه</h3>
        <div class="section-body">
            <table>
                @if($report->location)
                <tr>
                    <th>موقعیت مکانی</th>
                    <td>{{ $report->location }}</td>
                </tr>
                @endif
                @if($report->description)
                <tr>
                    <th>توضیحات تکمیلی</th>
                    <td>{{ $report->description }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif
    
    <div class="footer">
        <p>این سند توسط سامانه مدیریت طلا و جواهر تولید شده است.</p>
        <p>تاریخ چاپ: {{ jdate(now())->format('Y/m/d H:i') }}</p>
    </div>
</body>
</html>