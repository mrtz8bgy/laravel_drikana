{{-- resources/views/admin/jewelry/missing_reports/print.blade.php --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چاپ گزارش سرقت/مفقودی</title>
    <style>
        body {
            font-family: 'Vazir', Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .title {
            color: #e67e22;
            font-size: 24px;
            margin: 0;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .report-number {
            background: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            background: #34495e;
            color: white;
            padding: 10px 15px;
            margin: 0;
            font-size: 18px;
        }
        .section-body {
            padding: 15px;
            background: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: right;
            border: 1px solid #ddd;
        }
        table th {
            background: #ecf0f1;
            width: 200px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
            color: #7f8c8d;
            font-size: 12px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        .image-container {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 8px;
        }
        .jewelry-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: left; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
            چاپ
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer;">
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
                            <span class="badge" style="background: #27ae60; color: white;">فعال</span>
                        @else
                            <span class="badge" style="background: #e74c3c; color: white;">غیرفعال</span>
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