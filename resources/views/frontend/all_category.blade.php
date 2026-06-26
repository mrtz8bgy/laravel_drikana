@extends('frontend.layouts.app')

@section('content')

<style>
:root {
    --navy-dark: #0a0a2a;
    --navy-light: #1a1a4a;
    --gold: #D4AF37;
    --gold-light: #F5D76E;
    --gold-dark: #B8960F;
    --silver: #C0C0C0;
    --shadow-gold: 0 5px 20px rgba(212, 175, 55, 0.2);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 15px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 30px rgba(0,0,0,0.12);
    --shadow-hover: 0 15px 35px rgba(0,0,0,0.15);
}

/* هدر صفحه با رنگ سرمه‌ای */
.page-header {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
    padding: 60px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    border-bottom: 3px solid var(--gold);
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: repeating-linear-gradient(
        45deg,
        rgba(212, 175, 55, 0.05) 0px,
        rgba(212, 175, 55, 0.05) 2px,
        transparent 2px,
        transparent 8px
    );
    pointer-events: none;
}

.page-header h1 {
    font-size: 42px;
    font-weight: 800;
    color: var(--gold);
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    position: relative;
    z-index: 2;
}

.page-header p {
    font-size: 18px;
    color: var(--silver);
    margin-bottom: 0;
    position: relative;
    z-index: 2;
}

/* ============================================ */
/* منوی سمت راست با رنگ‌های طلایی و سرمه‌ای */
/* ============================================ */
.all-category-menu {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
    border-radius: 20px;
    box-shadow: var(--shadow-gold);
    overflow: hidden;
    margin-bottom: 30px;
    position: sticky;
    top: 20px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    width: 90%;
}

.all-category-menu ul {
    list-style: none;
    margin: 0;
    padding: 10px 0;
    max-height: 70vh;
    overflow-y: auto;
}

/* استایل اسکرول */
.all-category-menu ul::-webkit-scrollbar {
    width: 4px;
}

.all-category-menu ul::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
}

.all-category-menu ul::-webkit-scrollbar-thumb {
    background: var(--gold);
    border-radius: 10px;
}

/* آیتم منو */
.all-category-menu ul li {
    margin: 5px 10px;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.03);
}

.all-category-menu ul li a {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    color: #e0d5c1;
    transition: all 0.3s ease;
    gap: 12px;
}

/* آیکون منو */
.all-category-menu ul li .menu-icon {
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(212, 175, 55, 0.15);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.all-category-menu ul li .menu-icon img {
    width: 28px;
    height: 28px;
    object-fit: contain;
}

.all-category-menu ul li .menu-icon .default-icon {
    font-size: 24px;
    color: var(--gold);
}

/* متن منو */
.all-category-menu ul li .menu-text {
    flex: 1;
}

.all-category-menu ul li .cat-name {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
    display: block;
}

/* حالت active و hover */
.all-category-menu ul li.active,
.all-category-menu ul li:hover {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
}

.all-category-menu ul li.active .menu-icon,
.all-category-menu ul li:hover .menu-icon {
    background: rgba(255,255,255,0.25);
}

.all-category-menu ul li.active .default-icon,
.all-category-menu ul li:hover .default-icon,
.all-category-menu ul li.active .cat-name,
.all-category-menu ul li:hover .cat-name {
    color: var(--navy-dark);
}

.all-category-menu ul li.active .menu-icon img,
.all-category-menu ul li:hover .menu-icon img {
    filter: brightness(0);
}

/* ============================================ */
/* کارت‌های دسته‌بندی با رنگ طلایی و سرمه‌ای */
/* ============================================ */
.category-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e0e0e0;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
    border-color: var(--gold);
}

/* هدر کارت دسته‌بندی */
.category-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-bottom: 3px solid var(--gold);
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-header:hover {
    background: linear-gradient(135deg, #f0f0f0 0%, #f8f9fa 100%);
}

/* آیکون دسته‌بندی در کارت */
.category-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
    border-radius: 15px;
    margin-left: 15px;
    border: 1px solid var(--gold);
}

.category-icon-wrapper img {
    width: 30px;
    height: 30px;
    object-fit: contain;
}

.category-icon-wrapper .default-icon {
    font-size: 28px;
    color: var(--gold);
}

/* نام دسته‌بندی */
.category-name {
    font-size: 22px;
    font-weight: 800;
    margin: 0;
    display: inline-block;
    vertical-align: middle;
}

.category-name a {
    color: var(--navy-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.category-name a:hover {
    color: var(--gold-dark);
}

/* تعداد زیردسته‌ها */
.category-badge {
    float: left;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    padding: 5px 15px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    color: var(--navy-dark);
}

.category-badge i {
    margin-left: 5px;
}

/* زیردسته‌ها */
.subcategories-grid {
    padding: 25px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.subcategory-item {
    transition: all 0.3s ease;
}

/* عنوان زیردسته */
.subcategory-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--gold);
    display: inline-block;
}

.subcategory-title a {
    color: var(--navy-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.subcategory-title a:hover {
    color: var(--gold-dark);
}

/* لیست زیرزیردسته‌ها */
.subsubcategory-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.subsubcategory-list li {
    margin-bottom: 10px;
}

.subsubcategory-list li a {
    color: #666;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.subsubcategory-list li a::before {
    content: '▹';
    color: var(--gold-dark);
    font-size: 10px;
    transition: transform 0.3s ease;
}

.subsubcategory-list li a:hover {
    color: var(--gold-dark);
    transform: translateX(5px);
}

.subsubcategory-list li a:hover::before {
    transform: translateX(3px);
}

/* استایل دکمه طلایی */
.btn-gold {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    color: var(--navy-dark);
    border: none;
    border-radius: 30px;
    padding: 8px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
    color: var(--navy-dark);
}

.text-gold {
    color: var(--gold-dark) !important;
}

/* انیمیشن ورود کارت‌ها */
.category-card {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ریسپانسیو */
@media (max-width: 992px) {
    .page-header h1 { font-size: 32px; }
    .page-header p { font-size: 16px; }
    .category-name { font-size: 18px; }
    .subcategories-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; }
}

@media (max-width: 768px) {
    .page-header { padding: 40px 0; }
    .page-header h1 { font-size: 24px; }
    .category-header { padding: 15px 20px; }
    .category-name { font-size: 16px; }
    .category-icon-wrapper { width: 45px; height: 45px; }
    .category-icon-wrapper img { width: 24px; height: 24px; }
    .subcategories-grid { padding: 15px; gap: 15px; }
    .all-category-menu ul li .menu-icon { width: 40px; height: 40px; }
    .all-category-menu ul li .menu-icon .default-icon { font-size: 20px; }
    .all-category-menu ul li .cat-name { font-size: 12px; }
}

@media (max-width: 576px) {
    .subcategories-grid { grid-template-columns: 1fr; }
}
</style>

<!-- هدر صفحه -->
<div class="page-header">
    <div class="container">
        <h1><i class="la la-list-alt ml-2"></i> {{ __('دسته‌بندی محصولات') }}</h1>
        <p>{{ __('تمام دسته‌بندی‌های محصولات فروشگاه ما را در یک نگاه ببینید') }}</p>
    </div>
</div>

<div class="all-category-wrap py-5" style="background: #1a1a4a;">
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
                                <i class="la la-folder-open" style="font-size: 64px; color: #ccc;"></i>
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