@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #4895ef;
        --primary-dark: #3a56d4;
        --secondary-color: #4cc9f0;
        --success-color: #4ade80;
        --success-light: #86efac;
        --warning-color: #fbbf24;
        --warning-light: #fcd34d;
        --danger-color: #f87171;
        --danger-light: #fca5a5;
        --info-color: #60a5fa;
        --purple-color: #a78bfa;
        --pink-color: #f472b6;
        --orange-color: #fb923c;
        
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --bg-gray: #f1f5f9;
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 20px 40px rgba(67, 97, 238, 0.1);
        --transition: all 0.3s ease;
        --border-radius: 20px;
        --border-radius-sm: 16px;
    }

    body {
        background: var(--bg-light);
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
    }

    /* هشدار SMTP */
    .smtp-alert {
        background: linear-gradient(135deg, #fff5f5, #fff0f0);
        border: 1px solid var(--danger-color);
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 30px;
        animation: slideDown 0.5s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .smtp-alert h4 {
        color: var(--danger-color);
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    .smtp-alert .btn {
        background: var(--danger-color);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 500;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .smtp-alert .btn:hover {
        background: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(248, 113, 113, 0.3);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* کارت‌های آمار */
    .dashboard-stats {
        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .stat-card {
        background: var(--bg-white);
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 25px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-card-left {
        border-left: 4px solid var(--primary-color);
    }

    .stat-rotate {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%) rotate(180deg);
        color: rgba(67, 97, 238, 0.1);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.2);
    }

    .stat-content {
        position: relative;
        z-index: 1;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 10px;
        display: block;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 5px;
        line-height: 1;
    }

    .stat-sub {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 5px 0;
    }

    /* کارت‌های کوچک */
    .small-stat-card {
        background: var(--bg-white);
        border-radius: var(--border-radius-sm);
        padding: 20px;
        margin-bottom: 25px;
        transition: var(--transition);
        text-align: center;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        animation: slideUp 0.6s ease;
    }

    .small-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }

    .small-stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        font-size: 1.5rem;
        margin: 0 auto 15px;
    }

    .small-stat-card .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .small-stat-card .stat-value {
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .small-stat-card .btn-link {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 16px;
        background: var(--bg-gray);
        border-radius: 10px;
    }

    .small-stat-card .btn-link:hover {
        background: var(--primary-color);
        color: white;
        gap: 10px;
    }

    /* رنگ‌های مختلف برای کارت‌ها */
    .stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); }
    .stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, var(--success-color), #22c55e); }
    .stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, var(--warning-color), #f59e0b); }
    .stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, var(--danger-color), #ef4444); }
    .stat-card:nth-child(5) .stat-icon { background: linear-gradient(135deg, var(--purple-color), #8b5cf6); }
    .stat-card:nth-child(6) .stat-icon { background: linear-gradient(135deg, var(--pink-color), #ec4899); }

    .small-stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); }
    .small-stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, var(--success-color), #22c55e); }
    .small-stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, var(--warning-color), #f59e0b); }
    .small-stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, var(--danger-color), #ef4444); }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* پنل‌های جدول */
    .table-panel {
        background: var(--bg-white);
        border-radius: var(--border-radius);
        margin-bottom: 30px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        animation: fadeIn 1s ease;
    }

    .panel-header {
        padding: 20px 25px;
        border-bottom: 2px solid var(--border-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .panel-header h3 {
        color: var(--text-primary);
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-header h3 .emoji-icon {
        font-size: 1.4rem;
    }

    .panel-body {
        padding: 25px;
    }

    /* جداول */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 15px;
        border-bottom: 2px solid var(--primary-color);
        text-align: right;
    }

    .table tbody tr {
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .table tbody td {
        padding: 15px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        font-size: 0.95rem;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* بج‌ها */
    .badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary-color);
    }

    .badge-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #16a34a;
    }

    .badge-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }

    .badge-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }

    .badge-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }

    /* دکمه‌ها */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
    }

    .btn-block {
        width: 100%;
    }

    .btn-link {
        background: transparent;
        color: var(--primary-color);
        padding: 8px 16px;
        border-radius: 10px;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-link:hover {
        background: var(--bg-gray);
        color: var(--primary-dark);
        transform: translateX(-5px);
    }

    /* آیکون‌های متنی */
    .emoji-icon {
        font-size: 1.2rem;
        margin-right: 5px;
    }

    /* RTL Support */
    [dir="rtl"] .stat-rotate {
        right: auto;
        left: 20px;
        transform: translateY(-50%) rotate(0deg);
    }

    [dir="rtl"] .btn-link:hover {
        transform: translateX(5px);
    }

    [dir="rtl"] .btn-primary .emoji-icon,
    [dir="rtl"] .btn-link .emoji-icon {
        transform: rotate(180deg);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-value {
            font-size: 2rem;
        }
        
        .small-stat-card .stat-value {
            font-size: 1.8rem;
        }
        
        .panel-header {
            padding: 15px 20px;
        }
        
        .panel-body {
            padding: 15px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 10px;
            font-size: 0.9rem;
        }

        .stat-rotate {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 20px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }
</style>

@if(env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
    <div class="row">
        <div class="col-sm-12">
            <div class="smtp-alert text-center">
                <h4>
                    <span class="emoji-icon">⚠️</span>
                    {{__('Please Configure SMTP Setting to work all email sending funtionality')}}.
                </h4>
                <a class="btn" href="{{ route('smtp_settings.index') }}">
                    <span class="emoji-icon">⚙️</span>
                    تنظیمات SMTP
                </a>
            </div>
        </div>
    </div>
@endif

@if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
<div class="dashboard-stats">
    <div class="row">
        <div class="col-md-6">
            <div class="stat-card stat-card-left">
                <div class="stat-rotate">{{__('PRODUCTS')}}</div>
                <div class="stat-icon">
                    <span>📦</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">محصولات منتشر شده</span>
                    <div class="stat-value">{{ \App\Product::where('published', 1)->get()->count() }}</div>
                    
                    @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                        <div class="stat-sub">
                            <span class="emoji-icon" style="color: var(--success-color);">🏪</span>
                            فروشندگان: {{ \App\Product::where('published', 1)->where('added_by', 'seller')->get()->count() }}
                        </div>
                    @endif
                    
                    <div class="stat-sub">
                        <span class="emoji-icon" style="color: var(--primary-color);">👑</span>
                        ادمین: {{ \App\Product::where('published', 1)->where('added_by', 'admin')->get()->count() }}
                    </div>
                    
                    <br>
                    <a href="{{ route('products.admin') }}" class="btn-primary">
                        مدیریت محصولات
                        <span class="emoji-icon">←</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="small-stat-card">
                        <div class="stat-icon">
                            <span>🏷️</span>
                        </div>
                        <span class="stat-label">دسته‌بندی محصولات</span>
                        <div class="stat-value">{{ \App\Category::all()->count() }}</div>
                        <a href="{{ route('categories.create') }}" class="btn-link">
                            ایجاد دسته‌بندی
                            <span class="emoji-icon">➕</span>
                        </a>
                    </div>
                    
                    <div class="small-stat-card">
                        <div class="stat-icon">
                            <span>📚</span>
                        </div>
                        <span class="stat-label">زیرمجموعه سطح ۲</span>
                        <div class="stat-value">{{ \App\SubSubCategory::all()->count() }}</div>
                        <a href="{{ route('subsubcategories.create') }}" class="btn-link">
                            ایجاد زیرمجموعه
                            <span class="emoji-icon">➕</span>
                        </a>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="small-stat-card">
                        <div class="stat-icon">
                            <span>🗂️</span>
                        </div>
                        <span class="stat-label">زیرمجموعه سطح ۱</span>
                        <div class="stat-value">{{ \App\SubCategory::all()->count() }}</div>
                        <a href="{{ route('subcategories.create') }}" class="btn-link">
                            ایجاد زیرمجموعه
                            <span class="emoji-icon">➕</span>
                        </a>
                    </div>
                    
                    <div class="small-stat-card">
                        <div class="stat-icon">
                            <span>®️</span>
                        </div>
                        <span class="stat-label">برندها</span>
                        <div class="stat-value">{{ \App\Brand::all()->count() }}</div>
                        <a href="{{ route('brands.create') }}" class="btn-link">
                            ایجاد برند
                            <span class="emoji-icon">➕</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if((Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions))) && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <span>🏪</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">همه فروشندگان</span>
                    <div class="stat-value">{{ \App\Seller::all()->count() }}</div>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">
                        مدیریت فروشندگان
                        <span class="emoji-icon">←</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <span>✅</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">فروشندگان تایید شده</span>
                    <div class="stat-value">{{ \App\Seller::where('verification_status', 1)->get()->count() }}</div>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">
                        مدیریت فروشندگان
                        <span class="emoji-icon">←</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <span>⏳</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">فروشندگان در انتظار تایید</span>
                    <div class="stat-value">{{ \App\Seller::where('verification_status', 0)->count() }}</div>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">
                        مدیریت فروشندگان
                        <span class="emoji-icon">←</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
    <div class="row">
        <div class="col-md-6">
            <div class="table-panel">
                <div class="panel-header">
                    <h3>
                        <span class="emoji-icon">📈</span>
                        {{__('Category wise product sale')}}
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{__('Category Name')}}</th>
                                    <th class="text-center">{{__('Sale')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Category::all() as $key => $category)
                                    <tr>
                                        <td>{{ __($category->name) }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">
                                                {{ \App\Product::where('category_id', $category->id)->sum('num_of_sale') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-panel">
                <div class="panel-header">
                    <h3>
                        <span class="emoji-icon">📦</span>
                        {{__('Category wise product stock')}}
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{__('Category Name')}}</th>
                                    <th class="text-center">{{__('Stock')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Category::all() as $key => $category)
                                    @php
                                        $products = \App\Product::where('category_id', $category->id)->get();
                                        $qty = 0;
                                        foreach ($products as $key => $product) {
                                            if ($product->variant_product) {
                                                foreach ($product->stocks as $key => $stock) {
                                                    $qty += $stock->qty;
                                                }
                                            }
                                            else {
                                                $qty += $product->current_stock;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ __($category->name) }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-success">
                                                {{ $qty }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- بخش آمار اضافی با رنگ‌های متنوع -->
<div class="row">
    <div class="col-md-3">
        <div class="small-stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--purple-color), #8b5cf6);">
                <span>👥</span>
            </div>
            <span class="stat-label">کاربران فعال</span>
            <div class="stat-value">1,234</div>
            <a href="#" class="btn-link">
                مشاهده جزئیات
                <span class="emoji-icon">←</span>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--pink-color), #ec4899);">
                <span>🛒</span>
            </div>
            <span class="stat-label">سرقت و مفقودی </span>
            <div class="stat-value">---</div>
            <a href="./admin/jewelry/missing_reports" class="btn-link">
                مشاهده جزئیات
                <span class="emoji-icon">←</span>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--orange-color), #f97316);">
                <span>💰</span>
            </div>
            <span class="stat-label">مدیریت شناسنامه‌ها</span>
            <div class="stat-value">---</div>
            <a href="./admin/jewelry/certificates" class="btn-link">
                مشاهده جزئیات
                <span class="emoji-icon">←</span>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="small-stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--info-color), #3b82f6);">
                <span>👁️</span>
            </div>
            <span class="stat-label">مدیریت مالکان طلا و جواهر </span>
            <div class="stat-value">---</div>
            <a href="./admin/jewelry/owners" class="btn-link">
                مشاهده جزئیات
                <span class="emoji-icon">←</span>
            </a>
        </div>
    </div>
</div>

@endsection