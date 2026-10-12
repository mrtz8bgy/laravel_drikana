@extends('frontend.layouts.app')

@section('content')

{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/store-category.css') }}">
@endpush

<!-- هدر صفحه -->
<div class="page-header">
    <div class="container">
        <h1><i class="la la-list-alt ml-2"></i> {{ __('دسته‌بندی محصولات') }}</h1>
        <p>{{ __('تمام دسته‌بندی‌های محصولات فروشگاه ما را در یک نگاه ببینید') }}</p>
    </div>
</div>

<div class="all-category-wrap py-5 u-background-1a1a4a">
    <div class="container">
        <div class="row">
            <!-- منوی دسته‌بندی‌ها (سمت راست) -->
            <div class="col-lg-3">
                <div class="all-category-menu">
                    <ul class="clearfix no-scrollbar">
                        @foreach ($categories as $index => $category)
                            <li class="@if($index == 0) active @endif">
                                <a href="#cat-{{ $category->id }}" class="category-scroll-link">
                                    <div class="menu-icon">
                                        @if($category->icon && file_exists(public_path($category->icon)))
                                            <img loading="lazy" src="{{ asset($category->icon) }}" alt="{{ $category->name }}">
                                        @else
                                            <div class="default-icon">
                                                @php
                                                    $icons = [
                                                        'طلا' => 'fa-diamond',
                                                        'سکه' => 'fa-money',
                                                        'ساعت' => 'fa-clock-o',
                                                        'جواهر' => 'fa-gem',
                                                        'زنانه' => 'fa-female',
                                                        'مردانه' => 'fa-male',
                                                        'انگشتر' => 'fa-circle-o',
                                                        'دستبند' => 'fa-chain',
                                                        'گردنبند' => 'fa-necklace',
                                                        'پابند' => 'fa-chain-broken',
                                                        'سنگ' => 'fa-diamond',
                                                        'اکسسوری' => 'fa-shopping-bag',
                                                        'هدیه' => 'fa-gift',
                                                        'لوازم' => 'fa-cogs',
                                                        'جانبی' => 'fa-plug',
                                                        'آرایشی' => 'fa-paint-brush',
                                                        'بهداشتی' => 'fa-heartbeat',
                                                        'ابزار' => 'fa-wrench',
                                                        'یراق' => 'fa-cog',
                                                        'خانگی' => 'fa-home',
                                                        'دیجیتال' => 'fa-laptop',
                                                        'موبایل' => 'fa-mobile',
                                                        'تبلت' => 'fa-tablet',
                                                        'کامپیوتر' => 'fa-desktop',
                                                        'مد' => 'fa-tshirt',
                                                        'پوشاک' => 'fa-shopping-bag',
                                                        'کفش' => 'fa-shoe-prints',
                                                        'کیف' => 'fa-briefcase',
                                                        'ورزش' => 'fa-futbol-o',
                                                        'سفر' => 'fa-plane',
                                                        'کتاب' => 'fa-book',
                                                        'تحریر' => 'fa-pencil',
                                                        'اسباب' => 'fa-gamepad',
                                                        'بازی' => 'fa-puzzle-piece',
                                                        'کودک' => 'fa-child',
                                                        'مادر' => 'fa-heart',
                                                        'صنایع' => 'fa-hand-rock-o',
                                                        'دستی' => 'fa-hand-peace-o',
                                                        'هنری' => 'fa-paint-brush',
                                                    ];
                                                    $defaultIcon = 'fa-tag';
                                                    foreach ($icons as $keyword => $icon) {
                                                        if (strpos($category->name, $keyword) !== false) {
                                                            $defaultIcon = $icon;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <i class="fa {{ $defaultIcon }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="menu-text">
                                        <span class="cat-name">{{ __($category->name) }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- محتوای دسته‌بندی‌ها -->
            <div class="col-lg-9">
                @foreach ($categories as $category)
                    <div class="category-card" id="cat-{{ $category->id }}" data-category-id="{{ $category->id }}">
                        <div class="category-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="category-icon-wrapper">
                                        @if($category->icon && file_exists(public_path($category->icon)))
                                            <img loading="lazy" src="{{ asset($category->icon) }}" alt="{{ $category->name }}">
                                        @else
                                            <div class="default-icon">
                                                @php
                                                    $cardIcons = [
                                                        'طلا' => 'fa-diamond',
                                                        'ساعت' => 'fa-clock-o',
                                                        'جواهر' => 'fa-gem',
                                                        'اکسسوری' => 'fa-shopping-bag',
                                                        'هدیه' => 'fa-gift',
                                                        'آرایشی' => 'fa-paint-brush',
                                                        'ابزار' => 'fa-wrench',
                                                        'خانگی' => 'fa-home',
                                                        'دیجیتال' => 'fa-laptop',
                                                        'مد' => 'fa-tshirt',
                                                        'ورزش' => 'fa-futbol-o',
                                                        'کتاب' => 'fa-book',
                                                        'اسباب' => 'fa-gamepad',
                                                    ];
                                                    $cardIcon = 'fa-tag';
                                                    foreach ($cardIcons as $keyword => $icon) {
                                                        if (strpos($category->name, $keyword) !== false) {
                                                            $cardIcon = $icon;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <i class="fa {{ $cardIcon }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="category-name">
                                        <a href="{{ route('products.category', $category->slug) }}">{{ __($category->name) }}</a>
                                    </h3>
                                </div>
                                <div class="category-badge">
                                    <i class="la la-folder"></i> {{ $category->subcategories->count() }} {{ __('زیردسته') }}
                                </div>
                            </div>
                        </div>

                        @if($category->subcategories && count($category->subcategories) > 0)
                            <div class="subcategories-grid">
                                @foreach ($category->subcategories as $subcategory)
                                    <div class="subcategory-item">
                                        <h4 class="subcategory-title">
                                            <a href="{{ route('products.subcategory', $subcategory->slug) }}">{{ __($subcategory->name) }}</a>
                                        </h4>
                                        @if($subcategory->subsubcategories && count($subcategory->subsubcategories) > 0)
                                            <ul class="subsubcategory-list">
                                                @foreach ($subcategory->subsubcategories->take(8) as $subsubcategory)
                                                    <li>
                                                        <a href="{{ route('products.subsubcategory', $subsubcategory->slug) }}">
                                                            {{ __($subsubcategory->name) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                @if($subcategory->subsubcategories->count() > 8)
                                                    <li>
                                                        <a href="{{ route('products.subcategory', $subcategory->slug) }}" class="text-gold">
                                                            {{ __('مشاهده همه') }} ({{ $subcategory->subsubcategories->count() }})
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        @else
                                            <div class="text-muted small mt-2">
                                                <i class="la la-info-circle"></i> {{ __('بدون زیرزیردسته') }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="la la-folder-open u-font-size-64px-color-ccc"></i>
                                <p class="text-muted mt-3">{{ __('هیچ زیردسته‌ای برای این دسته‌بندی ثبت نشده است.') }}</p>
                                <a href="{{ route('products.category', $category->slug) }}" class="btn btn-gold mt-2">
                                    <i class="la la-eye"></i> {{ __('مشاهده محصولات این دسته') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    // اسکرول نرم به دسته‌بندی مورد نظر
    document.querySelectorAll('.category-scroll-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var targetId = this.getAttribute('href');
            var targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                // حذف کلاس active از همه آیتم‌ها
                document.querySelectorAll('.all-category-menu ul li').forEach(function(item) {
                    item.classList.remove('active');
                });
                // اضافه کردن کلاس active به آیتم کلیک شده
                this.closest('li').classList.add('active');
                
                // اسکرول نرم
                var offsetTop = targetElement.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // برجسته کردن منوی سمت چپ هنگام اسکرول
    window.addEventListener('scroll', function() {
        var categories = document.querySelectorAll('.category-card');
        var scrollPosition = window.scrollY + 120;
        
        categories.forEach(function(category) {
            var categoryTop = category.offsetTop;
            var categoryBottom = categoryTop + category.offsetHeight;
            var categoryId = category.getAttribute('id');
            
            if (scrollPosition >= categoryTop && scrollPosition < categoryBottom) {
                document.querySelectorAll('.all-category-menu ul li').forEach(function(item) {
                    item.classList.remove('active');
                    var link = item.querySelector('a');
                    if (link && link.getAttribute('href') === '#' + categoryId) {
                        item.classList.add('active');
                    }
                });
            }
        });
    });
</script>

@endsection