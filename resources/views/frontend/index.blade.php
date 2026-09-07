@extends('frontend.layouts.app')

@php
    use App\Product;
    use App\Category;
    use App\Brand;
    use App\FlashDeal;
    use App\Slider;
    use App\BusinessSetting;
    use App\CustomerProduct;
    use App\Addon;
@endphp

@section('content')

@php
    // ═══ حفاظت در برابر جدول‌های نصب‌نشده ═══
    try {
        $megaCategories = \Illuminate\Support\Facades\Schema::hasTable('categories')
            ? \App\Category::with(['subcategories' => function($query) {
                $query->where('published', 1)->with(['subsubcategories' => function($q){
                    $q->where('published', 1);
                }]);
            }])->where('top', 1)->where('published', 1)->get()
            : collect();
    } catch (\Exception $e) { $megaCategories = collect(); }

    // ایمن‌سازی سایر متغیرهای صفحه اصلی
    try {
        $num_todays_deal = \Illuminate\Support\Facades\Schema::hasTable('products')
            ? count(filter_products(\App\Product::where('published', 1)->where('todays_deal', 1))->get())
            : 0;
    } catch (\Exception $e) { $num_todays_deal = 0; }

    try {
        $total_customers = \Illuminate\Support\Facades\Schema::hasTable('users')
            ? \App\User::where('user_type','customer')->orWhereNull('user_type')->count()
            : 0;
    } catch (\Exception $e) { $total_customers = 0; }
@endphp

<style>
/* ============================================ */
/* تم سرمه‌ای، طلایی و سفید - طراحی لوکس و حرفه‌ای */
/* ============================================ */
:root {
    --color-navy: #0f0e1a;
    --color-navy-light: #1a1735;
    --color-navy-card: #1e1b3a;
    --color-gold: #d4af37;
    --color-gold-light: #f0d080;
    --color-gold-glow: #e8c547;
    --color-white: #ffffff;
    --color-white-smoke: #f5f5f7;
    --color-gray: #b0aec0;
    --color-gray-dark: #6b6980;
    --color-shadow: rgba(212, 175, 55, 0.15);
    --shadow-gold: 0 4px 25px rgba(212, 175, 55, 0.25);
    --shadow-gold-hover: 0 8px 40px rgba(212, 175, 55, 0.4);
    --gradient-gold: linear-gradient(135deg, #d4af37 0%, #f0d080 50%, #b8962e 100%);
    --gradient-navy: linear-gradient(135deg, #0f0e1a 0%, #1a1735 50%, #2a2555 100%);
    --font-base: 'Vazirmatn', 'Segoe UI', sans-serif;
}

/* ===== ریست کلی و بدنه ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-base);
    background: #020544;
    color: var(--color-white);
    line-height: 1.8;
    direction: rtl;
}

a {
    color: var(--color-gold);
    transition: all 0.3s ease;
}

a:hover {
    color: var(--color-gold-light);
    text-decoration: none;
}

/* ===== کانتینر ===== */
.container {
    max-width: 1400px;
    padding: 0 15px;
}

.container-fluid {
    padding: 0;
}

/* ============================================ */
/* استایل‌های مستقل مگا منو - بدون تداخل با سایر استایل‌ها */
/* ============================================ */
.mega-menu-section {
    background: var(--color-gold);
    border-bottom: 2px solid var(--color-gold);
    position: relative;
    z-index: 1100;
    width: 100%;
    direction: rtl;
    
}

.mega-menu-wrapper {
    max-width: 100%;
    padding: 0;
    margin: 0;
}

.mega-menu-nav {
    display: flex;
    align-items: center;
    padding: 0;
    margin: 0;
}

.mega-menu-trigger {
    position: relative;
    width: 100%;
    display: block;
    text-align: right;
}

.mega-menu-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background:var(--gradient-gold);
    color: var(--color-navy);
    padding: 14px 28px;
    font-weight: 700;
    font-size: 16px;
    border: none;
    border-radius: 0;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
    justify-content: center;
    font-family: var(--font-base);
}

.mega-menu-btn:hover {
    background: var(--color-gold-light);
    color: var(--color-navy);
}

.mega-menu-btn i {
    font-size: 20px;
}

/* ===== لیست اصلی دسته‌بندی‌ها ===== */
.mega-menu-list {
    position: absolute;
    top: calc(100% + 0px);
    right: 0 !important;
    left: auto !important;
    width: min(360px, 92vw);
    background: var(--color-navy-card);
    list-style: none;
    margin: 0;
    padding: 0;
    display: none;
    box-shadow: 0 22px 70px rgba(0, 0, 0, 0.85);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 0 0 14px 14px;
    max-height: min(78vh, 680px);
    overflow-y: auto;
    overflow-x: visible;
    min-width: 280px;
    z-index: 1200;
    text-align: right;
}

.mega-menu-trigger:hover .mega-menu-list,
.mega-menu-trigger.active .mega-menu-list {
    display: block;
}

.mega-menu-item {
    position: relative;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    list-style: none;
    overflow: visible;
}

.mega-menu-item:last-child {
    border-bottom: none;
}

.mega-menu-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 13px 20px;
    color: var(--color-white-smoke);
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-family: var(--font-base);
}

.mega-menu-link:hover {
    background: rgba(212, 175, 55, 0.12);
    color: var(--color-gold);
    padding-right: 28px;
}

.mega-cat-icon {
    width: 28px;
    height: 28px;
    object-fit: contain;
    filter: brightness(0.8);
    transition: all 0.3s ease;
}

.mega-menu-link:hover .mega-cat-icon {
    filter: brightness(1);
}

.mega-arrow {
    margin-right: auto;
    font-size: 16px;
    color: var(--color-gray);
    transition: all 0.3s ease;
}

.mega-menu-link:hover .mega-arrow {
    color: var(--color-gold);
}

/* ============================================ */
/* مگا ساب منو (زیردسته‌ها) - کاملاً مستقل */
/* ============================================ */
.mega-sub-menu {
    position: absolute;
    top: -1px;
    right: calc(100% + 4px) !important;
    left: auto !important;
    width: min(720px, calc(100vw - 390px));
    background: var(--color-navy-card);
    border-radius: 14px;
    box-shadow: 0 22px 70px rgba(0, 0, 0, 0.92);
    opacity: 0;
    visibility: hidden;
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(212, 175, 55, 0.18);
    padding: 20px;
    max-height: min(78vh, 680px);
    overflow-y: auto;
    overflow-x: visible;
    z-index: 1300;
    pointer-events: none;
    display: none;
    min-width: 280px;
    margin-right: -4px;
}

/* Ensure columns and submenu don't clip third-level lists */
.mega-sub-container [class*="col-"] {
    overflow: visible;
}

/* Show third-level lists when submenu is open */
.mega-menu-item:hover .mega-sub-menu .mega-sub-sub-list,
.mega-menu-item.active .mega-sub-menu .mega-sub-sub-list,
.mega-menu-item:focus-within .mega-sub-menu .mega-sub-sub-list {
    display: block !important;
    max-height: none !important;
}

.mega-menu-item:hover > .mega-sub-menu,
.mega-menu-item.active > .mega-sub-menu,
.mega-menu-item:focus-within > .mega-sub-menu {
    display: block !important;
    opacity: 1;
    visibility: visible;
    right: calc(100% + 4px) !important;
    left: auto !important;
    pointer-events: auto;
    transform: translateX(0);
}

.mega-menu-item > .mega-sub-menu {
    transform: translateX(6px);
}

/* جلوگیری از بسته شدن منو هنگام حرکت موس */
.mega-sub-menu:hover {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
}

.mega-sub-container {
    padding: 5px;
    width: 100%;
}

/* Fix: ensure mega submenu sits above other page elements (slider, arrows) and is not clipped */
.mega-menu-section {
    z-index: 99999 !important;
}

.mega-menu-list {
    /* allow submenu to extend without being clipped by parent */
    overflow: visible !important;
}

.mega-sub-menu {
    z-index: 100000 !important;
    /* make sure the submenu is positioned above transforms/backdrop-filters */
    position: absolute !important;
}

.mega-sub-container .row {
    margin: 0;
}

.mega-sub-container [class*="col-"] {
    padding: 5px;
}

.mega-sub-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    color: var(--color-white-smoke);
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-family: var(--font-base);
}

.mega-sub-link:hover {
    background: rgba(212, 175, 55, 0.1);
    color: var(--color-gold);
}

.mega-sub-icon {
    width: 24px;
    height: 24px;
    object-fit: contain;
}

.mega-sub-icon i {
    font-size: 20px;
    color: var(--color-gold);
}

/* ===== سطح سوم (زیرزیردسته‌ها) ===== */
.mega-sub-sub-list {
    list-style: none;
    margin: 0;
    padding: 0 12px 8px 12px;
    display: none;
    transition: max-height 0.22s ease;
    max-height: 0;
}

.mega-sub-sub-list li {
    padding: 2px 0;
    list-style: none;
}

/* نمایش زیرزیر دسته در هاور زیرلینک */
.mega-sub-link:hover + .mega-sub-sub-list,
.mega-sub-sub-list:hover {
    display: block;
    max-height: 400px;
}

.mega-sub-sub-list li a {
    color: var(--color-gray);
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    padding: 3px 12px;
    display: block;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-family: var(--font-base);
}

.mega-sub-sub-list li a:hover {
    color: var(--color-gold);
    background: rgba(212, 175, 55, 0.05);
}

/* ===== لینک مشاهده همه ===== */
.mega-view-all .mega-menu-link {
    color: var(--color-gold);
    font-weight: 700;
    justify-content: center;
    gap: 8px;
}

.mega-view-all .mega-menu-link:hover {
    background: rgba(212, 175, 55, 0.15);
}

/* ============================================ */
/* استایل اسکرول مگا منو */
/* ============================================ */
.mega-menu-list::-webkit-scrollbar,
.mega-sub-menu::-webkit-scrollbar {
    width: 4px;
}

.mega-menu-list::-webkit-scrollbar-track,
.mega-sub-menu::-webkit-scrollbar-track {
    background: var(--color-navy);
    border-radius: 4px;
}

.mega-menu-list::-webkit-scrollbar-thumb,
.mega-sub-menu::-webkit-scrollbar-thumb {
    background: var(--color-gold);
    border-radius: 4px;
}

/* ============================================ */
/* ریسپانسیو مگا منو */
/* ============================================ */
@media (max-width: 1200px) {
    .mega-sub-menu {
        width: 550px;
    }
}

@media (max-width: 767px) {
    .mega-sub-menu {
        display: none !important;
        position: relative !important;
        top: auto !important;
        right: auto !important;
        left: auto !important;
        width: 100% !important;
        box-shadow: none !important;
        border: 1px solid rgba(212, 175, 55, 0.12) !important;
        padding: 8px 10px 10px !important;
        opacity: 0 !important;
        visibility: hidden !important;
        max-height: 0 !important;
        background: rgba(255, 255, 255, 0.03) !important;
        border-radius: 10px !important;
        margin-top: 6px;
        min-width: 100% !important;
    }
    
    .mega-menu-item:hover > .mega-sub-menu,
    .mega-menu-item.active > .mega-sub-menu,
    .mega-menu-item:focus-within > .mega-sub-menu {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        max-height: 2000px !important;
    }
    
    .mega-menu-item.active .mega-arrow {
        transform: rotate(180deg);
    }
    
    .mega-menu-list {
        position: static;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .mega-menu-trigger:hover .mega-menu-list {
        display: none;
    }
    
    .mega-menu-trigger.active .mega-menu-list {
        display: block;
    }
    
    .mega-sub-sub-list {
        padding-right: 20px;
    }
    
    .mega-sub-container [class*="col-"] {
        padding: 3px 5px;
    }
}

@media (max-width: 576px) {
    .mega-sub-container [class*="col-"] {
        padding: 2px 5px;
    }
    
    .mega-sub-link {
        font-size: 13px;
        padding: 6px 10px;
    }
    
    .mega-sub-sub-list li a {
        font-size: 12px;
        padding: 2px 10px;
    }
}

/* ============================================ */
/* اسلایدر تمام عرض (۱۰۰٪) - با Slick */
/* ============================================ */
.fullwidth-slider-section {
    width: 100%;
    position: relative;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.fullwidth-slider-section .slick-carousel {
    width: 100%;
}

.fullwidth-slider-section .slick-slide {
    position: relative;
    height: 520px;
}

.fullwidth-slider-section .slick-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.fullwidth-slider-section .slick-slide > div {
    height: 100%;
}

.slide-content-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, rgba(15, 14, 26, 0.85) 0%, rgba(15, 14, 26, 0.4) 60%, rgba(15, 14, 26, 0.1) 100%);
    display: flex;
    align-items: center;
    padding: 50px;
    pointer-events: none;
}

.slide-content-overlay a {
    pointer-events: auto;
}

.slide-content {
    max-width: 600px;
}

.slide-content h2 {
    color: var(--color-white);
    font-size: 48px;
    font-weight: 900;
    margin-bottom: 18px;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
}

.slide-content h2 span {
    color: var(--color-gold);
}

.slide-content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 18px;
    margin-bottom: 25px;
    text-shadow: 0 1px 10px rgba(0, 0, 0, 0.3);
}

.slide-btn-custom {
    display: inline-block;
    background: var(--gradient-gold);
    color: var(--color-navy);
    padding: 14px 38px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-gold);
    font-size: 15px;
    pointer-events: auto;
}

.slide-btn-custom:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: var(--shadow-gold-hover);
    color: var(--color-navy);
}

/* استایل‌های Slick برای اسلایدر */
.fullwidth-slider-section .slick-prev,
.fullwidth-slider-section .slick-next {
    position: absolute; /* مشکل دقیقا اینجاست وقتی پوزیشن غیر فعال شود زیر منو ها نمایش داده میشوند بررسی کن که چرا این اتفاق میافتد*/
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    background: rgba(15, 14, 26, 0.8);
    border-radius: 50%;
    z-index: 100;
    cursor: pointer;
    border: 2px solid var(--color-gold);
    box-shadow: var(--shadow-gold);
    transition: all 0.3s ease;
    font-size: 0;
    color: var(--color-gold);
    display: flex !important;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
}

.fullwidth-slider-section .slick-prev {
    left: 20px;
}

.fullwidth-slider-section .slick-next {
    right: 20px;
}

.fullwidth-slider-section .slick-prev:hover,
.fullwidth-slider-section .slick-next:hover {
    background: var(--gradient-gold);
    color: var(--color-navy);
}

.fullwidth-slider-section .slick-prev::before,
.fullwidth-slider-section .slick-next::before {
    display: none;
}

.fullwidth-slider-section .slick-prev::after {
    content: '\f104';
    font-family: 'FontAwesome';
    font-size: 22px;
    color: var(--color-gold);
}

.fullwidth-slider-section .slick-next::after {
    content: '\f105';
    font-family: 'FontAwesome';
    font-size: 22px;
    color: var(--color-gold);
}

.fullwidth-slider-section .slick-prev:hover::after,
.fullwidth-slider-section .slick-next:hover::after {
    color: var(--color-navy);
}

/* دات‌های اسلایدر */
.fullwidth-slider-section .slick-dots {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 20;
}

.fullwidth-slider-section .slick-dots li {
    display: inline-block;
    margin: 0 5px;
}

.fullwidth-slider-section .slick-dots li button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    border: 1px solid rgba(212, 175, 55, 0.3);
    cursor: pointer;
    font-size: 0;
    padding: 0;
    transition: all 0.3s ease;
}

.fullwidth-slider-section .slick-dots li.slick-active button {
    background: var(--color-gold);
    width: 30px;
    border-radius: 6px;
    border-color: var(--color-gold);
}

/* ============================================ */
/* فلش دیل - اسکرول افقی زیر بنر */
/* ============================================ */
.flash-deal-section {
    background: var(--color-navy-card);
    padding: 15px 0;
    border-top: 1px solid rgba(212, 175, 55, 0.1);
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    margin-bottom: 30px;
}

.flash-deal-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
}

.flash-deal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 0 10px 0;
}

.flash-deal-header h4 {
    color: var(--color-gold);
    font-weight: 700;
    font-size: 18px;
    margin: 0;
}

.flash-deal-header h4 i {
    margin-left: 8px;
}

.flash-deal-header .badge-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 2px 12px;
    border-radius: 20px;
    font-size: 12px;
    margin-right: 8px;
}

.flash-deal-view-all {
    color: var(--color-gray);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.flash-deal-view-all:hover {
    color: var(--color-gold);
}

.flash-deal-scroll {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 10px 0 5px 0;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.flash-deal-scroll::-webkit-scrollbar {
    height: 4px;
}

.flash-deal-scroll::-webkit-scrollbar-track {
    background: var(--color-navy);
    border-radius: 4px;
}

.flash-deal-scroll::-webkit-scrollbar-thumb {
    background: var(--color-gold);
    border-radius: 4px;
}

.flash-deal-item-horizontal {
    flex: 0 0 140px;
    background: var(--color-navy);
    border-radius: 12px;
    padding: 10px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(212, 175, 55, 0.08);
}

.flash-deal-item-horizontal:hover {
    transform: translateY(-4px);
    border-color: var(--color-gold);
    box-shadow: var(--shadow-gold);
}

.flash-deal-item-horizontal img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 8px;
}

.flash-deal-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.flash-deal-price {
    color: var(--color-gold);
    font-weight: 700;
    font-size: 15px;
}

.flash-deal-old-price {
    color: var(--color-gray-dark);
    font-size: 12px;
    text-decoration: line-through;
}

/* ============================================ */
/* کارت محصولات - با max-lines برای متن بلند */
/* ============================================ */

.product-card-2 {
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: var(--color-navy-card);
    border: 1px solid rgba(212, 175, 55, 0.12);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.product-card-2:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-gold-hover);
    border-color: var(--color-gold);
}

.card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: var(--color-navy);
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card-2:hover .card-image img {
    transform: scale(1.06);
}

/* ===== عنوان محصول با max-lines ===== */
.product-title {
    height: auto;
    min-height: 48px;
    margin: 8px 0 4px;
    overflow: hidden;
}

.product-title a {
    font-size: 14px;
    font-weight: 600;
    color: var(--color-white-smoke);
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
    max-height: 3em;
    word-break: break-word;
}

.product-title a:hover {
    color: var(--color-gold);
}

.price-box {
    min-height: 52px;
    margin-bottom: 4px;
}

.product-price {
    font-size: 18px;
    font-weight: 800;
    color: var(--color-gold);
}

.old-product-price {
    font-size: 13px;
    color: var(--color-gray-dark);
    text-decoration: line-through;
}

/* ===== ستاره‌های امتیاز ===== */
.star-rating {
    color: var(--color-gold);
    font-size: 13px;
}

.text-success {
    color: #4ade80 !important;
    font-weight: 500;
}

/* ===== نشان تخفیف ===== */
.discount-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 2px 12px rgba(239, 68, 68, 0.4);
}

/* ============================================ */
/* بخش آیکون‌های تبلیغاتی */
/* ============================================ */
.mobile-icons-section {
    width: 100%;
    margin-bottom: 40px;
    background: var(--gradient-navy);
    padding: 30px 0;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(212, 175, 55, 0.1);
}

.mobile-icons-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 50%, rgba(212, 175, 55, 0.05) 0%, transparent 60%);
    pointer-events: none;
}

.mobile-icons-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 15px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.mobile-icon-item {
    flex: 1;
    text-align: center;
    min-width: 70px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.mobile-icon-circle {
    width: 65px;
    height: 65px;
    margin: 0 auto 10px;
    background: rgba(212, 175, 55, 0.12);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.mobile-icon-item:hover .mobile-icon-circle {
    transform: scale(1.12) translateY(-3px);
    background: rgba(212, 175, 55, 0.25);
    border-color: var(--color-gold);
    box-shadow: var(--shadow-gold);
}

.mobile-icon-emoji {
    font-size: 30px;
}

.mobile-icon-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-white-smoke);
    margin: 0;
    letter-spacing: 0.3px;
}

/* ============================================ */
/* عنوان‌های بخش‌ها */
/* ============================================ */
.section-title-1 {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(212, 175, 55, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.section-title-1 h3 {
    font-size: 24px;
    font-weight: 800;
    color: var(--color-white);
    letter-spacing: -0.3px;
}

.section-title-1 h3 i {
    color: var(--color-gold);
    margin-left: 8px;
}

.section-title-1 .inline-links {
    list-style: none;
    margin: 0;
    padding: 0;
}

.section-title-1 .inline-links li {
    display: inline-block;
}

.section-title-1 .inline-links li a {
    color: var(--color-gray);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.section-title-1 .inline-links li a:hover {
    color: var(--color-gold);
}

.section-title-1 .inline-links li a i {
    margin-right: 4px;
}

/* ============================================ */
/* دسته بندی و برند - اسکرول افقی */
/* ============================================ */

.categories-section,
.brands-section {
    position: relative;
}

.categories-scroll-wrapper,
.brands-scroll-wrapper {
    position: relative;
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    padding: 10px 0 20px;
    margin-bottom: 5px;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

.categories-scroll-wrapper::-webkit-scrollbar,
.brands-scroll-wrapper::-webkit-scrollbar {
    height: 4px;
}

.categories-scroll-wrapper::-webkit-scrollbar-track,
.brands-scroll-wrapper::-webkit-scrollbar-track {
    background: var(--color-navy);
    border-radius: 4px;
}

.categories-scroll-wrapper::-webkit-scrollbar-thumb,
.brands-scroll-wrapper::-webkit-scrollbar-thumb {
    background: var(--color-gold);
    border-radius: 4px;
}

.categories-scroll-container,
.brands-scroll-container {
    display: inline-flex;
    gap: 15px;
    white-space: nowrap;
    padding: 0 5px;
}

.category-scroll-item,
.brand-scroll-item {
    display: inline-block;
    width: 140px;
    flex-shrink: 0;
}

/* ===== کارت دسته‌بندی با فونت بزرگتر ===== */
.category-card-horizontal,
.brand-card-horizontal {
    display: block;
    background: var(--color-navy-card);
    border-radius: 16px;
    padding: 18px 12px;
    text-align: center;
    text-decoration: none;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(212, 175, 55, 0.08);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
}

.category-card-horizontal:hover,
.brand-card-horizontal:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-gold);
    border-color: var(--color-gold);
}

.category-icon-box,
.brand-icon-box {
    width: 70px;
    height: 70px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(212, 175, 55, 0.08);
    border-radius: 50%;
    transition: all 0.3s ease;
    border: 1px solid rgba(212, 175, 55, 0.1);
}

.category-card-horizontal:hover .category-icon-box,
.brand-card-horizontal:hover .brand-icon-box {
    background: rgba(212, 175, 55, 0.2);
    border-color: var(--color-gold);
    box-shadow: var(--shadow-gold);
}

.category-icon-img,
.brand-icon-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.category-icon-default,
.brand-icon-default {
    font-size: 30px;
    color: var(--color-gold);
}

/* ===== نام دسته‌بندی با فونت بزرگتر و max-lines ===== */
.category-name-horizontal,
.brand-name-horizontal {
    font-size: 15px;
    font-weight: 700;
    color: var(--color-white-smoke);
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    max-height: 2.8em;
    word-break: break-word;
    white-space: normal;
    max-width: 120px;
    margin: 0 auto;
}

.category-card-horizontal:hover .category-name-horizontal,
.brand-card-horizontal:hover .brand-name-horizontal {
    color: var(--color-gold);
}

/* ===== دکمه‌های ناوبری اسکرول ===== */
.scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    background: var(--color-navy-card);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: var(--shadow-gold);
    transition: all 0.3s ease;
    z-index: 10;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.scroll-btn:hover {
    background: var(--gradient-gold);
    transform: translateY(-50%) scale(1.1);
}

.scroll-btn:hover i {
    color: var(--color-navy);
}

.scroll-btn i {
    font-size: 18px;
    color: var(--color-gold);
    transition: all 0.3s ease;
}

.scroll-left {
    left: 5px;
}

.scroll-right {
    right: 5px;
}

/* ============================================ */
/* باکس‌های سفید داخل سایت */
/* ============================================ */
.bg-white.shadow-sm.rounded-3 {
    background: var(--color-navy-card) !important;
    border-radius: 16px !important;
    border: 1px solid rgba(212, 175, 55, 0.08);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3) !important;
}

/* ============================================ */
/* ریسپانسیو کامل */
/* ============================================ */

@media (max-width: 1200px) {
    .card-image { height: 190px; }
    .slide-content h2 { font-size: 40px; }
    .fullwidth-slider-section .slick-slide { height: 420px; }
}

@media (max-width: 992px) {
    .fullwidth-slider-section .slick-slide { height: 380px; }
    .slide-content h2 { font-size: 32px; }
    .slide-content p { font-size: 15px; }
    .fullwidth-slider-section .slick-prev,
    .fullwidth-slider-section .slick-next { width: 40px; height: 40px; }
    .card-image { height: 170px; }
    .category-scroll-item,
    .brand-scroll-item { width: 125px; }
    .scroll-btn { width: 34px; height: 34px; }
    .scroll-btn i { font-size: 16px; }
    .scroll-left { left: 0; }
    .scroll-right { right: 0; }
    .product-title a { font-size: 13px; }
}

@media (max-width: 768px) {
    .fullwidth-slider-section .slick-slide { height: 300px; }
    .slide-content-overlay { padding: 30px; }
    .slide-content h2 { font-size: 26px; margin-bottom: 12px; }
    .slide-content p { font-size: 13px; margin-bottom: 18px; }
    .slide-btn-custom { padding: 10px 24px; font-size: 13px; }
    .mobile-icon-circle { width: 52px; height: 52px; }
    .mobile-icon-emoji { font-size: 24px; }
    .mobile-icon-title { font-size: 12px; }
    .card-image { height: 150px; }
    .product-title a { font-size: 13px; }
    .product-price { font-size: 16px; }
    .section-title-1 h3 { font-size: 20px; }
    .category-scroll-item,
    .brand-scroll-item { width: 115px; }
    .category-name-horizontal,
    .brand-name-horizontal { font-size: 14px; }
    .category-icon-box,
    .brand-icon-box { width: 60px; height: 60px; }
    .category-icon-default,
    .brand-icon-default { font-size: 26px; }
    .scroll-btn { width: 30px; height: 30px; }
    .scroll-btn i { font-size: 14px; }
    .flash-deal-item-horizontal { flex: 0 0 120px; }
    .fullwidth-slider-section .slick-prev,
    .fullwidth-slider-section .slick-next { width: 36px; height: 36px; }
}

@media (max-width: 576px) {
    .fullwidth-slider-section .slick-slide { height: 230px; }
    .slide-content-overlay { padding: 20px; }
    .slide-content h2 { font-size: 20px; margin-bottom: 8px; }
    .slide-content p { font-size: 11px; margin-bottom: 12px; display: none; }
    .slide-btn-custom { padding: 8px 16px; font-size: 11px; }
    .mega-menu-btn { padding: 10px 14px; font-size: 12px; }
    .mega-menu-btn i { font-size: 16px; }
    .mega-menu-link { padding: 10px 14px; font-size: 13px; }
    .mobile-icon-circle { width: 46px; height: 46px; }
    .mobile-icon-emoji { font-size: 20px; }
    .mobile-icon-title { font-size: 10px; }
    .card-image { height: 130px; }
    .product-title a { font-size: 12px; }
    .product-price { font-size: 14px; }
    .section-title-1 h3 { font-size: 17px; }
    .category-scroll-item,
    .brand-scroll-item { width: 100px; }
    .category-name-horizontal,
    .brand-name-horizontal { font-size: 13px; max-width: 90px; }
    .category-icon-box,
    .brand-icon-box { width: 52px; height: 52px; }
    .category-icon-default,
    .brand-icon-default { font-size: 22px; }
    .category-icon-img,
    .brand-icon-img { width: 32px; height: 32px; }
    .flash-deal-item-horizontal { flex: 0 0 100px; }
    .flash-deal-item-horizontal img { width: 60px; height: 60px; }
    .flash-deal-price { font-size: 13px; }
    .fullwidth-slider-section .slick-prev,
    .fullwidth-slider-section .slick-next { width: 30px; height: 30px; }
    .fullwidth-slider-section .slick-prev::after,
    .fullwidth-slider-section .slick-next::after { font-size: 16px; }
    .fullwidth-slider-section .slick-prev { left: 10px; }
    .fullwidth-slider-section .slick-next { right: 10px; }
    .fullwidth-slider-section .slick-dots li button { width: 8px; height: 8px; }
    .fullwidth-slider-section .slick-dots li.slick-active button { width: 20px; }

    /* Mobile-specific mega menu tweaks */
    .mega-menu-btn {
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        margin: 0 10px 10px;
        justify-content: space-between;
        direction:rtl;
    }

    .mega-menu-list {
        position: fixed !important;
        top: 64px !important;
        right: 8px !important;
        left: 8px !important;
        width: auto !important;
        max-height: calc(100vh - 90px) !important;
        background: linear-gradient(180deg, #17152f 0%, #10111d 100%) !important;
        border-radius: 14px !important;
        padding: 8px 6px 10px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        border: 1px solid rgba(212, 175, 55, 0.16) !important;
        z-index: 2000 !important;
    }

    .mega-menu-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        margin: 2px 0;
    }

    .mega-menu-item:last-child {
        border-bottom: none !important;
    }

    .mega-menu-link {
        padding: 12px 14px !important;
        font-size: 14.5px !important;
        color: var(--color-white-smoke) !important;
        background: transparent !important;
        border-radius: 10px;
        min-height: 46px;
    }

    .mega-menu-link:hover,
    .mega-menu-item.active > .mega-menu-link {
        background: rgba(212, 175, 55, 0.15) !important;
        color: var(--color-gold) !important;
    }

    .mega-menu-link .mega-cat-icon {
        width: 28px;
        height: 28px;
        opacity: 0.95;
    }

    .mega-arrow {
        color: var(--color-gold);
        font-size: 16px;
    }

    .mega-sub-menu {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        left: auto !important;
        width: 100% !important;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        box-shadow: none !important;
        border-radius: 10px !important;
        padding: 0 !important;
        margin-top: 4px;
        visibility: visible !important;
        pointer-events: auto !important;
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(212, 175, 55, 0.08) !important;
    }

    .mega-menu-item.active > .mega-sub-menu {
        max-height: 2000px;
        padding: 6px 0 4px 0 !important;
    }

    .mega-sub-link {
        padding: 10px 12px !important;
        font-size: 13.5px !important;
        color: var(--color-white-smoke) !important;
        border-radius: 8px;
    }

    .mega-sub-link:hover {
        background: rgba(212, 175, 55, 0.12) !important;
        color: var(--color-gold) !important;
    }

    .mega-sub-sub-list {
        padding: 2px 0 4px 12px !important;
        display: block !important;
    }

    .mega-sub-sub-list li a {
        color: var(--color-gray) !important;
        font-size: 12.5px !important;
        padding: 4px 10px !important;
    }

    .mega-sub-sub-list li a:hover {
        color: var(--color-gold) !important;
        background: rgba(212, 175, 55, 0.08) !important;
    }
}

@media (max-width: 420px) {
    .fullwidth-slider-section .slick-slide { height: 180px; }
    .slide-content h2 { font-size: 16px; }
    .slide-btn-custom { padding: 6px 12px; font-size: 10px; }
    .card-image { height: 110px; }
    .product-title a { font-size: 11px; }
    .category-scroll-item,
    .brand-scroll-item { width: 85px; }
    .category-name-horizontal,
    .brand-name-horizontal { font-size: 11px; max-width: 75px; }
    .category-icon-box,
    .brand-icon-box { width: 44px; height: 44px; }
    .category-icon-default,
    .brand-icon-default { font-size: 18px; }
    .category-icon-img,
    .brand-icon-img { width: 28px; height: 28px; }
    .scroll-btn { width: 26px; height: 26px; }
    .scroll-btn i { font-size: 12px; }
    .scroll-left { left: -2px; }
    .scroll-right { right: -2px; }
}

/* ============================================ */
/* انیمیشن‌ها */
/* ============================================ */
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

.product-card-2 {
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes pulseGold {
    0%,
    100% {
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
    }
    50% {
        box-shadow: 0 0 40px rgba(212, 175, 55, 0.4);
    }
}

.category-card-horizontal:hover {
    animation: pulseGold 1.5s ease-in-out infinite;
}

/* ===== استایل اسکرول ===== */
.c-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.c-scrollbar::-webkit-scrollbar-track {
    background: var(--color-navy);
    border-radius: 4px;
}

.c-scrollbar::-webkit-scrollbar-thumb {
    background: var(--color-gold);
    border-radius: 4px;
}

/* ===== متن‌های اضافی ===== */
.text-muted {
    color: var(--color-gray-dark) !important;
}

.gry-bg {
    background:#02042d !important;
}

.heading-6 {
    font-size: 16px;
    font-weight: 700;
}

.strong-400 {
    font-weight: 400;
}

.strong-600 {
    font-weight: 600;
}

.strong-700 {
    font-weight: 700;
}

/* ===== برچسب محصول ===== */
.product-label.label-hot {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    padding: 2px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

/* ============================================ */
/* بنرهای تبلیغاتی پایین صفحه */
/* ============================================ */
.media-banner {
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.media-banner:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-gold);
}

.media-banner img {
    width: 100%;
    border-radius: 16px;
}
</style>

<!-- ============================================ -->
<!-- مگا منوی دسته‌بندی - کاملاً مستقل -->

<!-- مگا منوی  من در اینجا زیر دسته بندیها را نشان نمیدهد از تو میخواهم این قسمت را برام تکمیل کنی به طوری که زیر دسته بندی ها نمایش داده شوند -->

<!-- ============================================ -->
<section class="mega-menu-section">
    <div class="container-fluid px-0">
        <div class="mega-menu-wrapper">
            <nav class="mega-menu-nav">
                <div class="mega-menu-trigger" id="megaMenuTrigger">
                    <a href="#" class="mega-menu-btn" id="megaMenuBtn">
                        <i class="la la-bars"></i>
                        <span>{{__('همه دسته‌بندی‌ها')}}</span>
                        <i class="la la-angle-down"></i>
                    </a>
                    
                    <ul class="mega-menu-list" id="megaMenuList">
                        @foreach ($megaCategories as $key => $category)
                            <li class="mega-menu-item">
                                <a href="{{ route('products.category', $category->slug) }}" class="mega-menu-link">
                                    @if($category->icon && file_exists(public_path($category->icon)))
                                        <img class="mega-cat-icon lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                    @else
                                        <i class="la la-folder-open" style="font-size:20px;color:var(--color-gold);width:30px;text-align:center;"></i>
                                    @endif
                                    <span>{{ __($category->name) }}</span>
                                    @if($category->subcategories && $category->subcategories->isNotEmpty())
                                        <i class="la la-angle-left mega-arrow"></i>
                                    @endif
                                </a>

                                @if($category->subcategories && $category->subcategories->isNotEmpty())
                                    <div class="mega-sub-menu">
                                        <div class="mega-sub-container">
                                            <div class="row">
                                                @foreach ($category->subcategories as $subcategory)
                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <a href="{{ route('products.subcategory', $subcategory->slug) }}" class="mega-sub-link">
                                                            @if($subcategory->icon && file_exists(public_path($subcategory->icon)))
                                                                <img class="mega-sub-icon lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($subcategory->icon) }}" width="24" alt="{{ __($subcategory->name) }}">
                                                            @else
                                                                <i class="la la-folder-o" style="font-size:20px;color:var(--color-gold);width:24px;text-align:center;"></i>
                                                            @endif
                                                            <span>{{ __($subcategory->name) }}</span>
                                                        </a>

                                                        @if($subcategory->subsubcategories && $subcategory->subsubcategories->isNotEmpty())
                                                            <ul class="mega-sub-sub-list">
                                                                @foreach ($subcategory->subsubcategories->take(8) as $subSubCategory)
                                                                    <li>
                                                                        <a href="{{ route('products.subsubcategory', $subSubCategory->slug) }}">
                                                                            {{ __($subSubCategory->name) }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                                @if($subcategory->subsubcategories->count() > 8)
                                                                    <li>
                                                                        <a href="{{ route('products.subcategory', $subcategory->slug) }}">
                                                                            {{ __('مشاهده بیشتر') }}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                        
                        <li class="mega-menu-item mega-view-all">
                            <a href="{{ route('categories.all') }}" class="mega-menu-link">
                                <i class="la la-plus-circle"></i>
                                <span>{{__('مشاهده همه دسته‌بندی‌ها')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- HERO لوکس جدید دریکانا -->
<!-- ============================================ -->
<section class="d-hero">
    <div class="container">
        <div class="d-hero-inner">
            <div class="d-hero-content">
                <span class="d-hero-badge">
                    <span class="dot"></span>
                    معتبرترین مارکت‌پلیس طلا و جواهر ایران
                </span>
                <h1>
                    درخشش <span class="gold-line">اعتماد</span>
                    در هر <span class="gold-line">قطعه</span>
                </h1>
                <p>
                    خرید و فروش طلا، جواهرات، ساعت‌های لوکس و سنگ‌های قیمتی با
                    <strong style="color:var(--d-gold);">شناسنامه معتبر</strong>،
                    <strong style="color:var(--d-gold);">ضمانت اصالت</strong> و
                    <strong style="color:var(--d-gold);">امکان رهگیری دائمی</strong>.
                    دریکانا، جایی که ارزش‌ها ماندگارند.
                </p>
                <div class="d-hero-cta">
                    <a href="{{ route('categories.all') }}" class="btn-lux btn-lux-primary">
                        <i class="la la-shopping-bag"></i> مشاهده محصولات
                    </a>
                    <a href="{{ route('jewelry.certificates.verify_page') }}" class="btn-lux btn-lux-outline">
                        <i class="la la-certificate"></i> استعلام شناسنامه
                    </a>
                </div>
                <div class="d-hero-stats">
                    <div class="d-hero-stat">
                        <strong>+۱۰ سال</strong>
                        <span>سابقه درخشان</span>
                    </div>
                    <div class="d-hero-stat">
                        <strong>+۵۰K</strong>
                        <span>قطعه شناسنامه‌دار</span>
                    </div>
                    <div class="d-hero-stat">
                        <strong>۱۰۰٪</strong>
                        <span>گارانتی اصالت</span>
                    </div>
                    <div class="d-hero-stat">
                        <strong>۲۴/۷</strong>
                        <span>پشتیبانی تخصصی</span>
                    </div>
                </div>
            </div>
            <div class="d-hero-visual">
                <div class="d-hero-ring r1"></div>
                <div class="d-hero-ring r2"></div>
                <div class="d-hero-ring r3"></div>
                <div class="d-hero-diamond"></div>
                <div class="d-hero-float-card d-fc-1">
                    <div class="fc-icon"><i class="la la-certificate"></i></div>
                    <div class="fc-text">
                        <strong>شناسنامه معتبر</strong>
                        <span>قابل استعلام آنلاین</span>
                    </div>
                </div>
                <div class="d-hero-float-card d-fc-2">
                    <div class="fc-icon"><i class="la la-shield"></i></div>
                    <div class="fc-text">
                        <strong>ضمانت اصالت</strong>
                        <span>تضمین ۱۰۰٪ کالا</span>
                    </div>
                </div>
                <div class="d-hero-float-card d-fc-3">
                    <div class="fc-icon"><i class="la la-map-pin"></i></div>
                    <div class="fc-text">
                        <strong>رهگیری هوشمند</strong>
                        <span>از دسترسی دزدها دور بمانید</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- سرویس‌های طلایی -->
<!-- ============================================ -->
<section class="d-services">
    <div class="container">
        <div class="d-services-grid">
            <div class="d-service-card">
                <div class="d-service-icon">🛡️</div>
                <h5>گارانتی اصالت</h5>
                <p>تمام قطعات قبل از فروش توسط کارشناسان خبره ارزیابی و تأیید می‌شوند.</p>
            </div>
            <div class="d-service-card">
                <div class="d-service-icon">📜</div>
                <h5>شناسنامه رسمی</h5>
                <p>هر قطعه دارای شناسنامه معتبر با شماره سریال یکتا و قابل استعلام است.</p>
            </div>
            <div class="d-service-card">
                <div class="d-service-icon">🚨</div>
                <h5>رهگیری سرقت</h5>
                <p>ثبت فوری سرقت/مفقودی و اطلاع‌رسانی به شبکه همکاران سراسر کشور.</p>
            </div>
            <div class="d-service-card">
                <div class="d-service-icon">🚚</div>
                <h5>ارسال امن</h5>
                <p>ارسال کاملاً بیمه شده با اسکورت مسلح برای قطعات فوق‌العاده ارزشمند.</p>
            </div>
            <div class="d-service-card">
                <div class="d-service-icon">💎</div>
                <h5>تنوع بی‌نظیر</h5>
                <p>بیش از ده‌ها هزار مدل طلا، جواهر، ساعت لوکس و سنگ قیمتی.</p>
            </div>
            <div class="d-service-card">
                <div class="d-service-icon">🔧</div>
                <h5>خدمات پس از فروش</h5>
                <p>تعمیر، سایز کردن، تمیز کردن و بروزرسانی رایگان شناسنامه.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- فلش دیل

<!-- ============================================ -->
<!-- فلش دیل (تخفیف امروز) - زیر بنر -->
<!-- ============================================ -->
@php
    $num_todays_deal = count(filter_products(\App\Product::where('published', 1)->where('todays_deal', 1))->get());
@endphp

@if($num_todays_deal > 0)
<section class="flash-deal-section">
    <div class="flash-deal-wrapper">
        <div class="flash-deal-header">
            <h4><i class="la la-bolt"></i> {{ __('تخفیف امروز') }} <span class="badge badge-danger">{{__('داغ')}}</span></h4>
            <a href="{{ route('products.todays_deal') }}" class="flash-deal-view-all">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a>
        </div>
        <div class="flash-deal-scroll">
            @foreach (filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get() as $key => $product)
                @if ($product != null)
                    <a href="{{ route('product', $product->slug) }}" class="flash-deal-item-horizontal">
                        <img class="lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->flash_deal_img) }}" alt="{{ __($product->name) }}">
                        <div class="flash-deal-info">
                            <span class="flash-deal-price">{{ home_discounted_base_price($product->id) }}</span>
                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                <del class="flash-deal-old-price">{{ home_base_price($product->id) }}</del>
                            @endif
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================ -->
<!-- دسته بندی های معروف - اسکرول افقی -->
<!-- ============================================ -->
<section class="mb-5 categories-section">
    <div class="container">
        <div class="section-title-1">
            <h3><i class="la la-tags"></i> {{__('دسته بندی های معروف')}}</h3>
            <ul class="inline-links">
                <li>
                    <a href="{{ route('categories.all') }}">{{__('نمایش همه')}} <i class="la la-angle-left"></i></a>
                </li>
            </ul>
        </div>
        
        <div class="scroll-btn scroll-left" id="categoriesScrollLeft">
            <i class="la la-angle-right"></i>
        </div>
        <div class="scroll-btn scroll-right" id="categoriesScrollRight">
            <i class="la la-angle-left"></i>
        </div>
        
        <div class="categories-scroll-wrapper" id="categoriesScrollWrapper">
            <div class="categories-scroll-container">
                @php
                    $topCategories = Category::where('top', 1)->take(15)->get();
                    if(count($topCategories) < 8){
                        $topCategories = Category::take(12)->get();
                    }
                @endphp
                @foreach ($topCategories as $category)
                    <div class="category-scroll-item">
                        <a href="{{ route('products.category', $category->slug) }}" class="category-card-horizontal">
                            <div class="category-icon-box">
                                @if($category->icon && file_exists(public_path($category->icon)))
                                    <img src="{{ asset($category->icon) }}" alt="{{ __($category->name) }}" class="category-icon-img">
                                @else
                                    <div class="category-icon-default">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="category-name-horizontal">
                                {{ __($category->name) }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- آیکون‌های تبلیغاتی -->
<!-- ============================================ -->
<div class="mobile-icons-section">
    <div class="mobile-icons-container">
        @php
            $banners = App\Banner::where('position', 1)->where('published', 1)->get();
            $icons = ['💎', '👑', '🎁', '🔥', '✨', '💍', '⌚', '📿', '💼', '🛍️'];
        @endphp
        
        @if(count($banners) > 0)
            @foreach ($banners as $key => $banner)
                <a href="{{ $banner->url }}" target="_blank" class="mobile-icon-item">
                    <div class="mobile-icon-circle">
                        <span class="mobile-icon-emoji">{{ $icons[$key % count($icons)] }}</span>
                    </div>
                    <p class="mobile-icon-title">ویژه</p>
                </a>
            @endforeach
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- برند های معروف - اسکرول افقی -->
<!-- ============================================ -->
<section class="mb-5 brands-section">
    <div class="container">
        <div class="section-title-1">
            <h3><i class="la la-building"></i> {{__('برند های معروف')}}</h3>
            <ul class="inline-links">
                <li>
                    <a href="{{ route('brands.all') }}">{{__('نمایش همه')}} <i class="la la-angle-left"></i></a>
                </li>
            </ul>
        </div>
        
        <div class="scroll-btn scroll-left" id="brandsScrollLeft">
            <i class="la la-angle-right"></i>
        </div>
        <div class="scroll-btn scroll-right" id="brandsScrollRight">
            <i class="la la-angle-left"></i>
        </div>
        
        <div class="brands-scroll-wrapper" id="brandsScrollWrapper">
            <div class="brands-scroll-container">
                @php
                    $topBrands = Brand::where('top', 1)->take(15)->get();
                    if(count($topBrands) < 9){
                        $topBrands = Brand::take(12)->get();
                    }
                @endphp
                @foreach ($topBrands as $brand)
                    <div class="brand-scroll-item">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="brand-card-horizontal">
                            <div class="brand-icon-box">
                                @if($brand->logo && file_exists(public_path($brand->logo)))
                                    <img src="{{ asset($brand->logo) }}" alt="{{ __($brand->name) }}" class="brand-icon-img">
                                @else
                                    <div class="brand-icon-default">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="brand-name-horizontal">
                                {{ __($brand->name) }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش محصولات ویژه -->
<!-- ============================================ -->
<section class="mb-5">
    <div class="container">
        <div class="px-3 py-4 p-md-4 bg-white shadow-sm rounded-3">
            <div class="section-title-1">
                <h3><i class="la la-star"></i> {{__('محصولات ویژه')}}</h3>
                <ul class="inline-links">
                    <li><a href="{{ route('products.featured') }}">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $featured_products = filter_products(Product::where('published', 1)->where('featured', 1))->take(12)->get();
                    @endphp
                    @foreach ($featured_products as $product)
                        <div class="caorusel-card">
                            <div class="product-card-2 card card-product shop-cards">
                                <div class="card-body p-0">
                                    <div class="card-image">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                    </div>
                                    <div class="p-md-3 p-2">
                                        <div class="price-box">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            <br>
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش پرفروش‌ترین محصولات -->
<!-- ============================================ -->
<section class="mb-5">
    <div class="container">
        <div class="px-3 py-4 p-md-4 bg-white shadow-sm rounded-3">
            <div class="section-title-1">
                <h3><i class="la la-fire"></i> {{__('پرفروش‌ترین محصولات')}}</h3>
                <ul class="inline-links">
                    <li><a href="{{ route('products.best_selling') }}">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $best_selling_products = filter_products(Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->take(12)->get();
                    @endphp
                    @foreach ($best_selling_products as $product)
                        <div class="caorusel-card">
                            <div class="product-card-2 card card-product shop-cards">
                                <div class="card-body p-0">
                                    <div class="card-image">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                    </div>
                                    <div class="p-md-3 p-2">
                                        <div class="price-box">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            <br>
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                        </h2>
                                        <div class="text-success small mt-1">
                                            <i class="la la-shopping-cart"></i> {{ $product->num_of_sale }} فروش
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش جدیدترین محصولات -->
<!-- ============================================ -->
<section class="mb-5">
    <div class="container">
        <div class="px-3 py-4 p-md-4 bg-white shadow-sm rounded-3">
            <div class="section-title-1">
                <h3><i class="la la-clock-o"></i> {{__('جدیدترین محصولات')}}</h3>
                <ul class="inline-links">
                    <li><a href="{{ route('products.new') }}">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $new_products = filter_products(Product::where('published', 1)->orderBy('created_at', 'desc'))->take(12)->get();
                    @endphp
                    @foreach ($new_products as $product)
                        <div class="caorusel-card">
                            <div class="product-card-2 card card-product shop-cards">
                                <div class="card-body p-0">
                                    <div class="card-image">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                    </div>
                                    <div class="p-md-3 p-2">
                                        <div class="price-box">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            <br>
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                        </h2>
                                        <div class="text-muted small mt-1">
                                            <i class="la la-calendar"></i> {{ date('Y/m/d', strtotime($product->created_at)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش محصولات با تخفیف ویژه -->
<!-- ============================================ -->
<section class="mb-5">
    <div class="container">
        <div class="px-3 py-4 p-md-4 bg-white shadow-sm rounded-3">
            <div class="section-title-1">
                <h3><i class="la la-gift"></i> {{__('تخفیف ویژه')}}</h3>
                <ul class="inline-links">
                    <li><a href="{{ route('products.discounted') }}">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $discounted_products = filter_products(Product::where('published', 1)->where('discount', '>', 0))->take(12)->get();
                    @endphp
                    @foreach ($discounted_products as $product)
                        <div class="caorusel-card">
                            <div class="product-card-2 card card-product shop-cards">
                                <div class="card-body p-0">
                                    <div class="card-image">
                                        <span class="discount-badge">
                                            -{{ $product->discount }}%
                                        </span>
                                        <a href="{{ route('product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                    </div>
                                    <div class="p-md-3 p-2">
                                        <div class="price-box">
                                            <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            <br>
                                            <span class="product-price strong-600 text-danger">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<div id="section_featured"></div>
<div id="section_best_selling"></div>
<div id="section_home_categories"></div>

@if(BusinessSetting::where('type', 'classified_product')->first()->value == 1)
    @php
        $customer_products = CustomerProduct::where('status', '1')->where('published', '1')->take(16)->get();
    @endphp
   @if (count($customer_products) > 0)
       <section class="mb-5">
           <div class="container">
               <div class="px-3 py-4 p-md-4 bg-white shadow-sm rounded-3">
                   <div class="section-title-1">
                       <h3><i class="la la-bullhorn"></i> {{__('تبلیغات')}}</h3>
                       <ul class="inline-links">
                           <li><a href="{{ route('customer.products') }}">{{__('مشاهده همه')}} <i class="la la-angle-left"></i></a></li>
                       </ul>
                   </div>
                   <div class="caorusel-box arrow-round">
                       <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                           @foreach ($customer_products as $key => $customer_product)
                               <div class="product-card-2 card card-product my-2 mx-1 mx-sm-2 shop-cards shop-tech">
                                   <div class="card-body p-0">
                                       <div class="card-image">
                                           <a href="{{ route('customer.product', $customer_product->slug) }}" class="d-block">
                                               <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($customer_product->thumbnail_img) }}" alt="{{ __($customer_product->name) }}">
                                           </a>
                                       </div>
                                       <div class="p-sm-3 p-2">
                                           <div class="price-box">
                                               <span class="product-price strong-600">{{ single_price($customer_product->unit_price) }}</span>
                                           </div>
                                           <h2 class="product-title p-0 text-truncate-1">
                                               <a href="{{ route('customer.product', $customer_product->slug) }}">{{ __($customer_product->name) }}</a>
                                           </h2>
                                           <div>
                                               @if($customer_product->conditon == 'new')
                                                   <span class="product-label label-hot">{{__('جدید')}}</span>
                                               @elseif($customer_product->conditon == 'used')
                                                   <span class="product-label label-hot">{{__('دست دوم')}}</span>
                                               @endif
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           @endforeach
                       </div>
                   </div>
               </div>
           </div>
       </section>
   @endif
@endif

<div class="mb-5">
    <div class="container">
        <div class="row gutters-10">
            @foreach (App\Banner::where('position', 2)->where('published', 1)->get() as $key => $banner)
                <div class="col-lg-{{ 12/count(App\Banner::where('position', 2)->where('published', 1)->get()) }}">
                    <div class="media-banner mb-3 mb-lg-0">
                        <a href="{{ $banner->url }}" target="_blank" class="banner-container">
                            <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ asset($banner->photo) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div id="section_best_sellers"></div>

<!-- ============================================ -->
<!-- بخش دسته‌بندی‌های طلا و جواهر -->
<!-- ============================================ -->
<section class="d-categories">
    <div class="container">
        <div class="section-heading">
            <span class="kicker">CATEGORIES</span>
            <h2>گنجینه‌ای از <span class="gold">زیباترین</span> محصولات</h2>
            <p>از انگشترهای نامزدی ظریف تا ساعت‌های کلکسیونی و الماس‌های کمیاب، هر آنچه از یک مارکت‌پلیس لوکس انتظار دارید.</p>
            <span class="divider-gold"></span>
        </div>
        <div class="d-cat-grid">
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/ring.svg') }}" alt="حلقه" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(212,175,55,.4));">
                <h4>حلقه و انگشتر</h4>
                <span class="cat-count">۲,۴۰۰+ مدل</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/necklace.svg') }}" alt="گردنبند" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(212,175,55,.4));">
                <h4>گردنبند و آویز</h4>
                <span class="cat-count">طلای ظریف</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/watch.svg') }}" alt="ساعت" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(212,175,55,.4));">
                <h4>ساعت لوکس</h4>
                <span class="cat-count">رولکس، پتک و...</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/diamond.svg') }}" alt="الماس" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(110,180,230,.4));">
                <h4>الماس و سنگ قیمتی</h4>
                <span class="cat-count">قیمتی و نیمه‌قیمتی</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/crown.svg') }}" alt="تاج" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(212,175,55,.4));">
                <h4>جواهرات سلطنتی</h4>
                <span class="cat-count">کلکسیون ویژه</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/coin.svg') }}" alt="سکه" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(212,175,55,.4));">
                <h4>شمش و سکه</h4>
                <span class="cat-count">سرمایه‌گذاری</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/gift.svg') }}" alt="هدیه" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(200,30,60,.4));">
                <h4>پک هدیه لوکس</h4>
                <span class="cat-count">بسته‌بندی ویژه</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/antique.svg') }}" alt="عتیقه" class="cat-ico" style="width:90px;height:90px;object-fit:contain;filter:drop-shadow(0 8px 20px rgba(200,160,80,.4));">
                <h4>اشیاء عتیقه</h4>
                <span class="cat-count">کلکسیونرها</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش شناسنامه و امنیت -->
<!-- ============================================ -->
<section class="d-trust">
    <div class="container">
        <div class="d-trust-inner">
            <div class="d-trust-content">
                <span class="kicker" style="color:var(--d-gold); font-weight:700; letter-spacing:3px; font-size:13px;">
                    CERTIFICATE & SECURITY
                </span>
                <h2 style="font-size:clamp(24px,3vw,38px); font-weight:800; margin:10px 0 20px; line-height:1.4;">
                    هر قطعه، یک <span style="background:var(--grad-gold); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">شناسنامه معتبر</span>؛ آرامش خاطر شما
                </h2>
                <p style="color:var(--d-gray-300); font-size:15px; line-height:2.1; margin-bottom:0;">
                    سامانه شناسنامه دیجیتال دریکانا، سندی رسمی و غیرقابل جعل است که تمامی مشخصات فنی، عیار، وزن، سنگ‌های به‌کاررفته و تاریخچه مالکیت قطعه را در خود جای می‌دهد. هرگونه نقل و انتقال، تعمیر یا تغییر وضعیت در پرونده قطعه به صورت دائمی ثبت می‌شود.
                </p>
                <div class="d-trust-features">
                    <div class="d-trust-feature">
                        <div class="tf-icon"><i class="la la-qrcode"></i></div>
                        <div>
                            <h5>QR-Code اختصاصی</h5>
                            <p>با اسکن کد روی هر شناسنامه، بلافاصله به صفحه استعلام هدایت می‌شوید.</p>
                        </div>
                    </div>
                    <div class="d-trust-feature">
                        <div class="tf-icon"><i class="la la-chain"></i></div>
                        <div>
                            <h5>تاریخچه شفاف</h5>
                            <p>تمام انتقالات مالکیت، تعمیرات و کارشناسی‌ها در بلوک‌زنجیره اختصاصی ثبت می‌شود.</p>
                        </div>
                    </div>
                    <div class="d-trust-feature">
                        <div class="tf-icon"><i class="la la-id-card"></i></div>
                        <div>
                            <h5>احراز هویت مالک</h5>
                            <p>هرگونه معامله یا انتقال با احراز هویت رسمی مالک انجام می‌شود.</p>
                        </div>
                    </div>
                    <div class="d-trust-feature">
                        <div class="tf-icon"><i class="la la-bell"></i></div>
                        <div>
                            <h5>هشدار سرقت لحظه‌ای</h5>
                            <p>در صورت اعلام سرقت، تمام همکاران و مراکز از ممنوع‌المعامله بودن قطعه آگاه می‌شوند.</p>
                        </div>
                    </div>
                </div>
                <div style="margin-top:30px; display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('jewelry.certificates.verify_page') }}" class="btn-lux btn-lux-primary">
                        <i class="la la-search"></i> استعلام آنلاین شناسنامه
                    </a>
                    <a href="{{ route('jewelry.missing_reports.create_public') }}" class="btn-lux btn-lux-outline">
                        <i class="la la-bullhorn"></i> اعلام سرقت/مفقودی
                    </a>
                </div>
            </div>
            <div class="d-trust-visual">
                <div class="d-cert-mockup">
                    <div class="cert-head">
                        <h6>Certificate No. DRK</h6>
                        <h3>گواهی اصالت</h3>
                    </div>
                    <div class="cert-row"><span>محصول:</span><span>انگشتر الماس</span></div>
                    <div class="cert-row"><span>فلز:</span><span>طلای ۱۸ عیار</span></div>
                    <div class="cert-row"><span>وزن:</span><span>۸.۴ گرم</span></div>
                    <div class="cert-row"><span>سنگ:</span><span>الماس ۱.۲ قیراط</span></div>
                    <div class="cert-row"><span>مالک:</span><span>—</span></div>
                    <div class="cert-row"><span>تاریخ صدور:</span><span>۱۴۰۳/۰۶/۱۵</span></div>
                    <div class="cert-seal">DRIKANA<br>CERTIFIED</div>
                    <div class="cert-qr"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- استعلام شناسنامه CTA -->
<!-- ============================================ -->
<section class="d-verify-cta">
    <div class="container">
        <div class="d-verify-box">
            <div class="d-verify-icon"><i class="la la-certificate"></i></div>
            <h2 style="font-size:clamp(22px,3vw,34px); font-weight:800; margin:0 0 12px;">
                همین حالا <span style="background:var(--grad-gold); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">اصالت</span> قطعه خود را بررسی کنید
            </h2>
            <p style="color:var(--d-gray-300); margin:0;">
                با وارد کردن شماره سریال شناسنامه، از مشخصات، اصالت و وضعیت قطعه خود مطلع شوید.
            </p>
            <form method="GET" action="{{ route('jewelry.certificates.verify_page') }}" class="d-verify-form">
                <input type="text" name="serial" placeholder="شماره سریال شناسنامه را وارد کنید..." required>
                <button type="submit"><i class="la la-search la-flip-horizontal"></i> استعلام کن</button>
            </form>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- سوالات متداول -->
<!-- ============================================ -->
<section class="d-faq">
    <div class="container">
        <div class="section-heading">
            <span class="kicker">FAQ</span>
            <h2>سوالات <span class="gold">متداول</span></h2>
            <p>پاسخ به مهم‌ترین پرسش‌های شما درباره خرید طلا، شناسنامه و خدمات دریکانا.</p>
            <span class="divider-gold"></span>
        </div>
        <div class="d-faq-list">
            <div class="d-faq-item open">
                <div class="d-faq-q">
                    <span>شناسنامه طلا چیست و چرا اهمیت دارد؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>شناسنامه طلا سندی رسمی و دیجیتال است که مشخصات فنی قطعه شامل وزن، عیار، نوع و مشخصات سنگ، مالک و تاریخچه آن را ثبت می‌کند. با شناسنامه، اصالت قطعه قابل پیگیری و انتقال مالکیت به صورت شفاف و امن انجام می‌شود.</p></div>
            </div>
            <div class="d-faq-item">
                <div class="d-faq-q">
                    <span>چگونه از اصالت کالایی که می‌خرم مطمئن شوم؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>تمام محصولات دریکانا قبل از درج توسط کارشناسان رسمی ارزیابی شده و دارای شناسنامه معتبر با شماره سریال یکتا هستند. شما می‌توانید در هر لحظه با وارد کردن شماره سریال در صفحه «استعلام شناسنامه»، اصالت و وضعیت قطعه را بررسی کنید.</p></div>
            </div>
            <div class="d-faq-item">
                <div class="d-faq-q">
                    <span>در صورت سرقت یا مفقودی طلا چه کار باید بکنم؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>بلافاصله از پنل کاربری خود وارد بخش «اعلام سرقت/مفقودی» شوید و گزارش را ثبت کنید. قطعه شما ظرف چند دقیقه در شبکه همکاران، مراکز خریدوفروش، تعمیرگاه‌ها و لیست ممنوع‌المعامله دریکانا قرار می‌گیرد.</p></div>
            </div>
            <div class="d-faq-item">
                <div class="d-faq-q">
                    <span>آیا امکان فروش طلای کارکرده در دریکانا وجود دارد؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>بله. شما می‌توانید با ثبت‌نام به عنوان فروشنده یا از بخش «فروش طلای من» درخواست کارشناسی رایگان قطعه خود را ثبت کنید. پس از ارزیابی توسط کارشناسان، قطعه با شناسنامه جدید در مارکت‌پلیس عرضه می‌شود.</p></div>
            </div>
            <div class="d-faq-item">
                <div class="d-faq-q">
                    <span>شیوه ارسال قطعات گران‌بها چگونه است؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>قطعات با ارزش بالا با بیمه کامل و اسکورت امنیتی به درب منزل شما ارسال می‌شوند. برای سفارش‌های معمولی نیز بسته‌بندی ایمن و بیمه شده توسط پست پیشتاز یا تیپاکس انجام می‌شود.</p></div>
            </div>
            <div class="d-faq-item">
                <div class="d-faq-q">
                    <span>هزینه صدور شناسنامه جدید چقدر است؟</span>
                    <i class="la la-angle-down"></i>
                </div>
                <div class="d-faq-a"><p>برای قطعاتی که از دریکانا خریداری می‌شوند، صدور شناسنامه کاملاً رایگان است. برای قطعات متفرقه، هزینه کارشناسی و صدور شناسنامه بر اساس وزن و نوع قطعه محاسبه می‌شود.</p></div>
            </div>
        </div>
    </div>
</section>

<div id="section_best_sellers"></div>

<!-- ============================================ -->
<!-- اسکریپت‌های جاوااسکریپت -->
<!-- ============================================ -->
<script>
    // ===== اسکرول دسته‌بندی‌ها و برندها =====
    function setupScrollButtons(wrapperId, leftBtnId, rightBtnId) {
        const wrapper = document.getElementById(wrapperId);
        const leftBtn = document.getElementById(leftBtnId);
        const rightBtn = document.getElementById(rightBtnId);
        
        if (!wrapper) return;
        
        const scrollAmount = 280;
        
        if (leftBtn) {
            leftBtn.addEventListener('click', function() {
                wrapper.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
        }
        
        if (rightBtn) {
            rightBtn.addEventListener('click', function() {
                wrapper.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        }
    }
    
    setupScrollButtons('categoriesScrollWrapper', 'categoriesScrollLeft', 'categoriesScrollRight');
    setupScrollButtons('brandsScrollWrapper', 'brandsScrollLeft', 'brandsScrollRight');
    
    // ===== مگا منو - کنترل کامل با هاور و کلیک =====
    (function() {
        var megaTrigger = document.getElementById('megaMenuTrigger');
        var megaBtn = document.getElementById('megaMenuBtn');
        var megaItems = document.querySelectorAll('.mega-menu-item');
        var isMobile = window.innerWidth <= 992;
        
        // تابع برای تنظیم رویدادها بر اساس سایز صفحه
        function setupMegaMenu() {
            isMobile = window.innerWidth <= 992;
            
            if (isMobile) {
                // حالت موبایل - فقط کلیک
                megaTrigger.removeEventListener('mouseenter', onMouseEnter);
                megaTrigger.removeEventListener('mouseleave', onMouseLeave);
                
                if (megaBtn) {
                    megaBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        megaTrigger.classList.toggle('active');
                    });
                }
                
                // کلیک روی آیتم‌های دارای زیرمنو
                megaItems.forEach(function(item) {
                    var link = item.querySelector('.mega-menu-link');
                    var hasSubMenu = item.querySelector('.mega-sub-menu');
                    
                    if (link && hasSubMenu) {
                        link.removeEventListener('click', onItemClick);
                        link.addEventListener('click', onItemClick);
                    }

                    item.removeEventListener('mouseover', onItemMouseEnter);
                    item.removeEventListener('mouseleave', onItemMouseLeave);
                    item.classList.remove('active');
                });
            } else {
                // حالت دسکتاپ - هاور
                megaTrigger.addEventListener('mouseenter', onMouseEnter);
                megaTrigger.addEventListener('mouseleave', onMouseLeave);
                
                // حذف رویدادهای کلیک در دسکتاپ
                if (megaBtn) {
                    megaBtn.removeEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        megaTrigger.classList.toggle('active');
                    });
                }
                
                megaItems.forEach(function(item) {
                    var link = item.querySelector('.mega-menu-link');
                    if (link) {
                        link.removeEventListener('click', onItemClick);
                    }

                    item.removeEventListener('mouseover', onItemMouseEnter);
                    item.removeEventListener('mouseleave', onItemMouseLeave);
                    item.addEventListener('mouseover', onItemMouseEnter);
                    item.addEventListener('mouseleave', onItemMouseLeave);
                });
            }
        }
        
        function setMobileSubmenuState(item, isOpen) {
            if (!item) return;
            if (isOpen) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        }

        function onMouseEnter() {
            this.classList.add('active');
        }
        
        function onMouseLeave() {
            this.classList.remove('active');
            megaItems.forEach(function(item) {
                item.classList.remove('active');
            });
        }

        function onItemMouseEnter() {
            megaItems.forEach(function(item) {
                item.classList.remove('active');
            });
            this.classList.add('active');
        }

        function onItemMouseLeave() {
            this.classList.remove('active');
        }
        
        function onItemClick(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                e.stopPropagation();
                var parent = this.closest('.mega-menu-item');
                if (parent) {
                    var hasSubMenu = parent.querySelector('.mega-sub-menu');
                    if (!hasSubMenu) {
                        // آیتم زیرمنو ندارد، صفحه را بارگذاری کن
                        window.location.href = this.href;
                        return;
                    }

                    // بستن تمام آیتم‌های دیگر
                    megaItems.forEach(function(other) {
                        if (other !== parent) {
                            other.classList.remove('active');
                        }
                    });

                    // تبدیل وضعیت آیتم فعلی
                    parent.classList.toggle('active');
                }
            }
        }
        
        // بستن منو با کلیک خارج
        document.addEventListener('click', function(e) {
            if (megaTrigger && !megaTrigger.contains(e.target)) {
                megaTrigger.classList.remove('active');
                megaItems.forEach(function(item) {
                    item.classList.remove('active');
                });
            }
        });
        
        // تنظیم مجدد در تغییر سایز
        window.addEventListener('resize', function() {
            setupMegaMenu();
            if (megaTrigger) {
                megaTrigger.classList.remove('active');
                megaItems.forEach(function(item) {
                    item.classList.remove('active');
                });
            }
        });
        
        // اجرای اولیه
        setupMegaMenu();
    })();
    
    // ===== بارگذاری بخش‌های داینامیک =====
    $(document).ready(function(){
        $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#section_featured').html(data);
            if(typeof slickInit === 'function') slickInit();
        });

        $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#section_best_selling').html(data);
            if(typeof slickInit === 'function') slickInit();
        });

        $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#section_home_categories').html(data);
            if(typeof slickInit === 'function') slickInit();
        });

        $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#section_best_sellers').html(data);
            if(typeof slickInit === 'function') slickInit();
        });
    });
</script>

@endsection