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

<style>
/* ============================================ */
/* استایل اسلایدر تمام عرض */
/* ============================================ */

.fullwidth-slider-section {
    width: 100%;
    position: relative;
    margin-bottom: 20px;
    overflow: hidden;
}

.slider-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.slider-track {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.slider-slide {
    min-width: 100%;
    position: relative;
    height: 550px;
    flex-shrink: 0;
}

.slider-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slide-content-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 100%);
    display: flex;
    align-items: center;
}

.slide-content-overlay h2 {
    color: white;
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.slide-content-overlay p {
    color: white;
    font-size: 18px;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.slide-btn-custom {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.slide-btn-custom:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    color: white;
}

/* دکمه‌های ناوبری */
.slider-prev, .slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.8);
    border-radius: 50%;
    z-index: 100;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    font-size: 24px;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
}

.slider-prev {
    left: 20px;
}

.slider-next {
    right: 20px;
}

.slider-prev:hover, .slider-next:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* دات‌های ناوبری */
.slider-dots {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 20;
}

.slider-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    margin: 0 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: #667eea;
    width: 30px;
    border-radius: 10px;
}

/* ============================================ */
/* مگا منوی خطی زیر اسلایدر */
/* ============================================ */

.mega-menu-section {
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 15px 0;
    margin-bottom: 30px;
    position: relative;
    z-index: 50;
}

.mega-menu-wrapper {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.mega-menu-item {
    text-align: center;
    padding: 10px 20px;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #f8f9fa;
    min-width: 100px;
}

.mega-menu-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: translateY(-5px);
}

.mega-menu-link {
    text-decoration: none;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.mega-menu-item:hover .mega-menu-link {
    color: white;
}

.mega-menu-icon {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 10px;
}

.mega-menu-title {
    font-size: 14px;
    font-weight: 500;
}

/* ============================================ */
/* بخش آیکون‌های موبایلی */
/* ============================================ */

.mobile-icons-section {
    width: 100%;
    margin-bottom: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 0;
    border-radius: 0;
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
    width: 60px;
    height: 60px;
    margin: 0 auto 8px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.mobile-icon-item:hover .mobile-icon-circle {
    transform: scale(1.1);
    background: rgba(255,255,255,0.4);
}

.mobile-icon-emoji {
    font-size: 28px;
}

.mobile-icon-title {
    font-size: 12px;
    font-weight: 500;
    color: white;
    margin: 0;
}

/* ============================================ */
/* ریسپانسیو */
/* ============================================ */

@media (max-width: 992px) {
    .slider-slide { height: 400px; }
    .slide-content-overlay h2 { font-size: 32px; }
    .slide-content-overlay p { font-size: 14px; }
    .slider-prev, .slider-next { width: 35px; height: 35px; font-size: 18px; }
    .mega-menu-item { min-width: 80px; padding: 8px 12px; }
    .mega-menu-icon { width: 30px; height: 30px; }
    .mega-menu-title { font-size: 12px; }
}

@media (max-width: 768px) {
    .slider-slide { height: 280px; }
    .slide-content-overlay h2 { font-size: 24px; }
    .slide-content-overlay p { font-size: 12px; }
    .slide-btn-custom { padding: 8px 20px; font-size: 12px; }
    .mega-menu-item { min-width: calc(25% - 10px); }
    .mobile-icon-circle { width: 50px; height: 50px; }
    .mobile-icon-emoji { font-size: 24px; }
    .mobile-icon-title { font-size: 10px; }
}

@media (max-width: 480px) {
    .slider-slide { height: 200px; }
    .slide-content-overlay h2 { font-size: 18px; }
    .slide-btn-custom { padding: 6px 12px; font-size: 10px; }
    .mega-menu-item { min-width: calc(33.33% - 10px); }
    .mega-menu-icon { width: 25px; height: 25px; }
    .mega-menu-title { font-size: 10px; }
    .mobile-icon-circle { width: 45px; height: 45px; }
    .mobile-icon-emoji { font-size: 20px; }
    .mobile-icon-title { font-size: 9px; }
}
</style>

<!-- ============================================ -->
<!-- اسلایدر تمام عرض -->
<!-- ============================================ -->

<section class="fullwidth-slider-section">
    <div class="slider-container">
        <div class="slider-track" id="sliderTrack">
            @php
                $sliders = Slider::where('published', 1)->get();
            @endphp
            @if(count($sliders) > 0)
                @foreach ($sliders as $key => $slider)
                    <div class="slider-slide">
                        <a href="{{ $slider->link }}" target="_blank">
                            <img src="{{ asset($slider->photo) }}" alt="{{ env('APP_NAME')}} promo">
                        </a>
                        <div class="slide-content-overlay">
                            <div class="container">
                                <h2>تخفیف‌های ویژه</h2>
                                <p>بهترین جواهرات با بهترین قیمت</p>
                                <a href="{{ $slider->link }}" target="_blank" class="slide-btn-custom">مشاهده بیشتر →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="slider-slide">
                    <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}" alt="placeholder">
                    <div class="slide-content-overlay">
                        <div class="container">
                            <h2>خوش آمدید</h2>
                            <p>به فروشگاه جواهرات ما خوش آمدید</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <button class="slider-prev" id="sliderPrev">←</button>
        <button class="slider-next" id="sliderNext">→</button>
        
        <div class="slider-dots" id="sliderDots"></div>
    </div>
</section>

<!-- ============================================ -->
<!-- مگا منوی خطی دسته‌بندی‌ها -->
<!-- ============================================ -->

<section class="mega-menu-section">
    <div class="mega-menu-wrapper">
        @php
            $categories = Category::where('featured', 1)->take(12)->get();
            if(count($categories) == 0){
                $categories = Category::take(12)->get();
            }
        @endphp
        
        @foreach ($categories as $category)
            <div class="mega-menu-item">
                <a href="{{ route('products.category', $category->slug) }}" class="mega-menu-link">
                    @if($category->icon)
                        <img class="mega-menu-icon" src="{{ asset($category->icon) }}" alt="{{ __($category->name) }}">
                    @else
                        <div class="mega-menu-icon" style="background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">📁</div>
                    @endif
                    <span class="mega-menu-title">{{ __($category->name) }}</span>
                </a>
            </div>
        @endforeach
        
        <div class="mega-menu-item">
            <a href="{{ route('categories.all') }}" class="mega-menu-link">
                <div class="mega-menu-icon" style="background: #764ba2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">➕</div>
                <span class="mega-menu-title">همه</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- آیکون‌های موبایلی (بنرهای تبلیغاتی) -->
<!-- ============================================ -->

<div class="mobile-icons-section">
    <div class="mobile-icons-container">
        @php
            $banners = App\Banner::where('position', 1)->where('published', 1)->get();
            $icons = ['🛍️', '💎', '🎁', '🔥', '✨', '👑', '💍', '⌚', '📿', '💼'];
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
<!-- ادامه بخش‌های قبلی -->
<!-- ============================================ -->

<!-- بخش محصولات ویژه -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    ⭐ {{__('محصولات ویژه')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.featured') }}" class="active">{{__('مشاهده همه')}}</a></li>
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
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

<!-- بخش پرفروش‌ترین محصولات -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    🔥 {{__('پرفروش‌ترین محصولات')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.best_selling') }}" class="active">{{__('مشاهده همه')}}</a></li>
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
                                        </h2>
                                        <div class="text-success small">
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

<!-- بخش جواهرات مردانه -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    👔 {{__('جواهرات مردانه')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.mens') }}" class="active">{{__('مشاهده همه')}}</a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $menCategory = Category::where('slug', 'like', '%men%')->orWhere('name', 'like', '%مردانه%')->first();
                        if($menCategory){
                            $men_products = filter_products(Product::where('published', 1)->where('category_id', $menCategory->id))->take(12)->get();
                        } else {
                            $men_products = filter_products(Product::where('published', 1)->where('tags', 'like', '%مردانه%'))->take(12)->get();
                        }
                    @endphp
                    @foreach ($men_products as $product)
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
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

<!-- بخش جواهرات زنانه -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    💍 {{__('جواهرات زنانه')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.womens') }}" class="active">{{__('مشاهده همه')}}</a></li>
                </ul>
            </div>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4" data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @php
                        $womenCategory = Category::where('slug', 'like', '%women%')->orWhere('name', 'like', '%زنانه%')->first();
                        if($womenCategory){
                            $women_products = filter_products(Product::where('published', 1)->where('category_id', $womenCategory->id))->take(12)->get();
                        } else {
                            $women_products = filter_products(Product::where('published', 1)->where('tags', 'like', '%زنانه%'))->take(12)->get();
                        }
                    @endphp
                    @foreach ($women_products as $product)
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
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

<!-- بخش جدیدترین محصولات -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    🆕 {{__('جدیدترین محصولات')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.new') }}" class="active">{{__('مشاهده همه')}}</a></li>
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
                                        </h2>
                                        <div class="text-muted small">
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

<!-- بخش محصولات با تخفیف ویژه -->
<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    🎁 {{__('تخفیف ویژه')}}
                </h3>
                <ul class="inline-links float-right">
                    <li><a href="{{ route('products.discounted') }}" class="active">{{__('مشاهده همه')}}</a></li>
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
                                        <span class="discount-badge" style="position: absolute; top: 10px; left: 10px; background: #e74c3c; color: white; padding: 3px 8px; border-radius: 5px; font-size: 12px; z-index: 1;">
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
                                            <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
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
       <section class="mb-4">
           <div class="container">
               <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                   <div class="section-title-1 clearfix">
                       <h3 class="heading-5 strong-700 mb-0 float-left">
                           <span class="mr-4">{{__('تبلیغات')}}</span>
                       </h3>
                       <ul class="inline-links float-right">
                           <li><a href="{{ route('customer.products') }}" class="active">{{__('مشاهده همه')}}</a></li>
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
                                                   <span class="product-label label-hot">{{__('new')}}</span>
                                               @elseif($customer_product->conditon == 'used')
                                                   <span class="product-label label-hot">{{__('Used')}}</span>
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

<div class="mb-4">
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

<section class="mb-3">
    <div class="container">
        <div class="row gutters-10">
            <div class="col-lg-6">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4">{{__('دسته بندی های معروف')}}</span>
                    </h3>
                    <ul class="float-right inline-links">
                        <li>
                            <a href="{{ route('categories.all') }}" class="active">{{__('نمایش همه دسته بندی ها')}}</a>
                        </li>
                    </ul>
                </div>
                <div class="row gutters-5">
                    @php
                        $topCategories = Category::where('top', 1)->take(9)->get();
                        if(count($topCategories) < 8){
                            $topCategories = Category::take(8)->get();
                        }
                    @endphp
                    @foreach ($topCategories as $category)
                        <div class="mb-3 col-6">
                            <a href="{{ route('products.category', $category->slug) }}" class="bg-white border d-block c-base-2 box-2 icon-anim pl-2">
                                <div class="row align-items-center no-gutters">
                                    <div class="col-3 text-center">
                                        <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->banner) }}" alt="{{ __($category->name) }}" class="img-fluid img lazyload">
                                    </div>
                                    <div class="info col-7">
                                        <div class="name text-truncate pr-3 py-4">{{ __($category->name) }}</div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <i class="la la-angle-left c-base-1"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4">{{__('برند های معروف')}}</span>
                    </h3>
                    <ul class="float-right inline-links">
                        <li>
                            <a href="{{ route('brands.all') }}" class="active">{{__('نمایش همه برند ها')}}</a>
                        </li>
                    </ul>
                </div>
                <div class="row gutters-5">
                    @php
                        $topBrands = Brand::where('top', 1)->take(11)->get();
                        if(count($topBrands) < 9){
                            $topBrands = Brand::take(9)->get();
                        }
                    @endphp
                    @foreach ($topBrands as $brand)
                        <div class="mb-3 col-6">
                            <a href="{{ route('products.brand', $brand->slug) }}" class="bg-white border d-block c-base-2 box-2 icon-anim pl-2">
                                <div class="row align-items-center no-gutters">
                                    <div class="col-3 text-center">
                                        <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($brand->logo) }}" alt="{{ __($brand->name) }}" class="img-fluid img lazyload">
                                    </div>
                                    <div class="info col-7">
                                        <div class="name text-truncate pr-3 py-4">{{ __($brand->name) }}</div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <i class="la la-angle-left c-base-1"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // اسلایدر ساده و مستقل - فقط یک بار
    (function() {
        function initSlider() {
            const track = document.getElementById('sliderTrack');
            const slides = document.querySelectorAll('.slider-slide');
            const prevBtn = document.getElementById('sliderPrev');
            const nextBtn = document.getElementById('sliderNext');
            const dotsContainer = document.getElementById('sliderDots');
            
            if (!track || slides.length === 0) {
                console.log('❌ اسلایدر پیدا نشد');
                return;
            }
            
            let currentIndex = 0;
            const totalSlides = slides.length;
            let autoPlayInterval;
            
            console.log('✅ اسلایدر راه‌اندازی شد. تعداد اسلایدها:', totalSlides);
            
            if (totalSlides > 1) {
                // ایجاد دات‌ها
                function createDots() {
                    if (!dotsContainer) return;
                    dotsContainer.innerHTML = '';
                    for (let i = 0; i < totalSlides; i++) {
                        const dot = document.createElement('span');
                        dot.classList.add('slider-dot');
                        if (i === currentIndex) dot.classList.add('active');
                        dot.addEventListener('click', function() {
                            goToSlide(i);
                            resetAutoPlay();
                        });
                        dotsContainer.appendChild(dot);
                    }
                }
                
                function goToSlide(index) {
                    if (index < 0) index = totalSlides - 1;
                    if (index >= totalSlides) index = 0;
                    currentIndex = index;
                    track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
                    
                    const dots = document.querySelectorAll('.slider-dot');
                    for (let i = 0; i < dots.length; i++) {
                        dots[i].classList.toggle('active', i === currentIndex);
                    }
                }
                
                function nextSlide() {
                    goToSlide(currentIndex + 1);
                }
                
                function prevSlide() {
                    goToSlide(currentIndex - 1);
                }
                
                function resetAutoPlay() {
                    if (autoPlayInterval) clearInterval(autoPlayInterval);
                    autoPlayInterval = setInterval(nextSlide, 5000);
                }
                
                if (prevBtn) prevBtn.addEventListener('click', function() { prevSlide(); resetAutoPlay(); });
                if (nextBtn) nextBtn.addEventListener('click', function() { nextSlide(); resetAutoPlay(); });
                
                createDots();
                resetAutoPlay();
                
                const container = document.querySelector('.slider-container');
                if (container) {
                    container.addEventListener('mouseenter', function() { if (autoPlayInterval) clearInterval(autoPlayInterval); });
                    container.addEventListener('mouseleave', function() { resetAutoPlay(); });
                }
            } else if (totalSlides === 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                if (dotsContainer) dotsContainer.style.display = 'none';
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSlider);
        } else {
            initSlider();
        }
    })();
    
    $(document).ready(function(){
        // بخش‌های AJAX
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