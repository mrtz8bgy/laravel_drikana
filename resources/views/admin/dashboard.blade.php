@extends('layouts.app')

@section('content')

{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/admin.css') }}">
@endpush

@if(config('mail.username') == null && config('mail.password') == null)
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

@php
    $vendor_system_enabled = false;
    try {
        $vendor_setting = \Illuminate\Support\Facades\Schema::hasTable('business_settings')
            ? \App\BusinessSetting::where('type', 'vendor_system_activation')->first()
            : null;
        $vendor_system_enabled = $vendor_setting && $vendor_setting->value == 1;
    } catch (\Exception $e) {
        $vendor_system_enabled = false;
    }
@endphp

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
                    
                    @if ($vendor_system_enabled)
                        <div class="stat-sub">
                            <span class="emoji-icon u-color-var-success-color">🏪</span>
                            فروشندگان: {{ \Illuminate\Support\Facades\Schema::hasTable('products') ? \App\Product::where('published', 1)->where('added_by', 'seller')->get()->count() : 0 }}
                        </div>
                    @endif
                    
                    <div class="stat-sub">
                        <span class="emoji-icon u-color-var-primary-color">👑</span>
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

@if((Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions))) && $vendor_system_enabled && \Illuminate\Support\Facades\Schema::hasTable('sellers'))
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
                    <div class="stat-value">{{ \App\Seller::where('verification_status', 1)->count() }}</div>
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
            <div class="stat-icon u-background-linear-gradient-135deg-var-purple-color-8b5cf6">
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
            <div class="stat-icon u-background-linear-gradient-135deg-var-pink-color-ec4899">
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
            <div class="stat-icon u-background-linear-gradient-135deg-var-orange-color-f97316">
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
            <div class="stat-icon u-background-linear-gradient-135deg-var-info-color-3b82f6">
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