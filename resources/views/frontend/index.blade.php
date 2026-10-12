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

{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/store.css') }}">
@endpush

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
                                        <i class="la la-folder-open u-font-size-20px-color-var-color-gold-width-30px-text-ali-7e344c"></i>
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
                                                                <i class="la la-folder-o u-font-size-20px-color-var-color-gold-width-24px-text-ali-dfa443"></i>
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

{{-- ═══════════════════════════════════════════════════════════════
     بنر اصلی صفحه (اسلایدر) — بازگردانی‌شده
     ───────────────────────────────────────────────────────────────
     [رفع اشکال] اسلایدر از جدول `sliders` تغذیه می‌شود و پنل مدیریت
     (frontend_settings/sliders) از قبل امکان بارگذاری، فعال/غیرفعال و
     حذف اسلاید را داشت — اما هیچ‌جا از سایت رندر نمی‌شد. در بازطراحی
     لوکس، بخش بنر با «HERO استاتیک» جای‌گزین شده بود و مارک‌آپ اسلایدر
     از ویو حذف شده بود؛ در نتیجه اسلایدهای بارگذاری‌شده در صفحه اصلی
     دیده نمی‌شدند.

     CSS این بخش از قبل در `public/css/drikana/store.css` بود
     (`.fullwidth-slider-section` و کلاس‌های `.slide-content*`)، پس
     مارک‌آپ زیر همان طراحی آماده را زنده می‌کند — بدون CSS تازه.

     نکته: اگر هیچ اسلاید منتشرشده‌ای (با تصویر موجود) نباشد، این بخش
     به‌کل رندر نمی‌شود و صفحه اصلی دقیقاً مثل قبل می‌ماند.
     ═══════════════════════════════════════════════════════════════ --}}
@if(isset($sliders) && $sliders->count() > 0)
<section class="fullwidth-slider-section">
    <div class="slick-carousel"
         data-slick-items="1"
         data-slick-xs-items="1"
         data-slick-sm-items="1"
         data-slick-md-items="1"
         data-slick-lg-items="1"
         data-slick-xl-items="1"
         data-slick-arrows="true"
         data-slick-dots="true"
         data-slick-autoplay="true"
         data-slick-rows="1">

        @foreach($sliders as $slider)
            @php
                // پیوند خالی یا «#» یعنی اسلاید بدون مقصد. در آن حالت عنصر
                // پیوند ساخته نمی‌شود تا کلیک، تب خالی باز نکند. برای
                // پیوندهای بیرونی `rel="noopener noreferrer"` لازم است تا
                // صفحه مقصد به `window.opener` دسترسی نداشته باشد.
                $slideLink = trim((string) $slider->link);
                $slideLink = ($slideLink === '' || $slideLink === '#') ? null : $slideLink;
            @endphp

            <div>
                @if($slideLink)
                    <a href="{{ $slideLink }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset($slider->photo) }}"
                             alt="{{ __('اسلاید') }} {{ $loop->iteration }}"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    </a>
                @else
                    <img src="{{ asset($slider->photo) }}"
                         alt="{{ __('اسلاید') }} {{ $loop->iteration }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                @endif

                <div class="slide-content-overlay">
                    <div class="slide-content">
                        <h2>خرید <span>طلایی</span><br>با بهترین قیمت</h2>
                        <p>کیفیت را با قیمت مناسب تجربه کنید</p>

                        @if($slideLink)
                            <a href="{{ $slideLink }}" target="_blank" rel="noopener noreferrer" class="slide-btn-custom">
                                {{ __('مشاهده و خرید') }} <i class="la la-angle-left"></i>
                            </a>
                        @else
                            <a href="{{ route('categories.all') }}" class="slide-btn-custom">
                                {{ __('مشاهده و خرید') }} <i class="la la-angle-left"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

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
                    <strong class="u-color-var-d-gold">شناسنامه معتبر</strong>،
                    <strong class="u-color-var-d-gold">ضمانت اصالت</strong> و
                    <strong class="u-color-var-d-gold">امکان رهگیری دائمی</strong>.
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

@php
    $classified_product_enabled = false;
    try {
        $classified_product_enabled = \Illuminate\Support\Facades\Schema::hasTable('business_settings')
            && \App\BusinessSetting::where('type', 'classified_product')->first()
            && \App\BusinessSetting::where('type', 'classified_product')->first()->value == 1;
    } catch (\Exception $e) {
        $classified_product_enabled = false;
    }
@endphp

@if($classified_product_enabled)
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
                            <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ asset($banner->photo) }}" alt="{{ config('app.name') }} promo" class="img-fluid lazyload">
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
            <a href="{{ route('products.category', ['category_slug' => '---uQqCB']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/ring.svg') }}" alt="حلقه" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ddc6d9">
                <h4>حلقه و انگشتر</h4>
                <span class="cat-count">۲,۴۰۰+ مدل</span>
            </a>
            <a href="{{ route('products.category', ['category_slug' => '-----MB8NO']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/necklace.svg') }}" alt="گردنبند" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ddc6d9">
                <h4>گردنبند و آویز</h4>
                <span class="cat-count">طلای ظریف</span>
            </a>
            <a href="{{ route('products.category', ['category_slug' => '----FoS0v']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/watch.svg') }}" alt="ساعت" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ddc6d9">
                <h4>ساعت لوکس</h4>
                <span class="cat-count">رولکس، پتک و...</span>
            </a>
            <a href="{{ route('products.category', ['category_slug' => '---iLNhI']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/diamond.svg') }}" alt="الماس" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ffea0d">
                <h4>الماس و سنگ قیمتی</h4>
                <span class="cat-count">قیمتی و نیمه‌قیمتی</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/crown.svg') }}" alt="تاج" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ddc6d9">
                <h4>جواهرات سلطنتی</h4>
                <span class="cat-count">کلکسیون ویژه</span>
            </a>
            <a href="{{ route('products.category', ['category_slug' => '--2EJpD']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/coin.svg') }}" alt="سکه" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-ddc6d9">
                <h4>شمش و سکه</h4>
                <span class="cat-count">سرمایه‌گذاری</span>
            </a>
            <a href="{{ route('products.category', ['category_slug' => '-----H1Zlr']) }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/gift.svg') }}" alt="هدیه" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-13829f">
                <h4>پک هدیه لوکس</h4>
                <span class="cat-count">بسته‌بندی ویژه</span>
            </a>
            <a href="{{ route('categories.all') }}" class="d-cat-card">
                <img src="{{ asset('frontend/images/drikana/antique.svg') }}" alt="عتیقه" class="cat-ico u-width-90px-height-90px-object-fit-contain-filter-drop-s-70c493">
                <h4>اشیاء عتیقه</h4>
                <span class="cat-count">کلکسیونرها</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- بخش مقالات و راهنما (برای همه کاربران سایت) -->
<!-- ============================================ -->
{{--
    مقالات عمومی: تنها مقالات «منتشرشده» نمایش داده می‌شوند.
    کوئری در HomeController@index اجرا شده و اینجا فقط نمایش داده می‌شود.
    اگر مقاله‌ای منتشر نشده باشد، کل بخش نمایش داده نمی‌شود تا صفحه
    اصلی با یک بخش خالی زشت نشود.
--}}
@if(isset($latestArticles) && count($latestArticles) > 0)
<section class="d-articles">
    <div class="container">
        <div class="section-heading">
            <span class="kicker">MAGAZINE &amp; GUIDE</span>
            <h2>مجله <span class="gold">دریکانا</span> — راهنمای طلا و جواهر</h2>
            <p>شناخت سنگ‌های قیمتی، نکات نگهداری اشیاء گران‌بها و هر آنچه پیش از خرید باید بدانید.</p>
            <span class="divider-gold"></span>
        </div>

        <div class="d-art-grid">
            @foreach($latestArticles as $article)
                <article class="d-art-card">
                    <a href="{{ route('articles.show', $article->slug) }}" class="d-art-thumb">
                        @if($article->featured_image_url)
                            <img src="{{ $article->featured_image_url }}"
                                 alt="{{ $article->featured_image_alt ?: $article->title }}"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}"
                                 alt="{{ $article->title }}"
                                 loading="lazy">
                        @endif

                        @if($article->category)
                            <span class="d-art-badge">{{ $article->category->name }}</span>
                        @endif
                    </a>

                    <div class="d-art-body">
                        <h3 class="d-art-title">
                            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                        </h3>

                        @if($article->excerpt)
                            <p class="d-art-excerpt">{{ \Illuminate\Support\Str::limit($article->excerpt, 110) }}</p>
                        @endif

                        <div class="d-art-meta">
                            <span>
                                📅
                                {{ ($article->published_at ?: $article->created_at)->format('Y/m/d') }}
                            </span>
                            @if($article->views_count)
                                <span>👁 {{ number_format($article->views_count) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if(isset($articleCategories) && count($articleCategories) > 0)
            <div class="d-art-topics">
                @foreach($articleCategories as $category)
                    <a href="{{ route('articles.category', $category->slug) }}" class="d-art-topic">
                        {{ $category->name }}
                        <span>{{ $category->published_articles_count }}</span>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="d-art-actions">
            <a href="{{ route('articles.index') }}" class="btn-lux btn-lux-primary">
                📖 مشاهده همه مقالات
            </a>
            <a href="{{ route('articles.search') }}" class="btn-lux btn-lux-outline">
                🔍 جست‌وجو در مقالات
            </a>
        </div>
    </div>
</section>
@endif

<!-- ============================================ -->
<!-- بخش شناسنامه و امنیت -->
<!-- ============================================ -->
<section class="d-trust">
    <div class="container">
        <div class="d-trust-inner">
            <div class="d-trust-content">
                <span class="kicker u-color-var-d-gold-font-weight-700-letter-spacing-3px-fon-f179e3">
                    CERTIFICATE & SECURITY
                </span>
                <h2 class="u-font-size-clamp-24px-3vw-38px-font-weight-800-margin-10-372c2c">
                    هر قطعه، یک <span class="u-background-var-grad-gold-webkit-background-clip-text-ba-1929d0">شناسنامه معتبر</span>؛ آرامش خاطر شما
                </h2>
                <p class="u-color-var-d-gray-300-font-size-15px-line-height-2-1-mar-8d9f21">
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
                <div class="u-margin-top-30px-display-flex-gap-12px-flex-wrap-wrap">
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
            <h2 class="u-font-size-clamp-22px-3vw-34px-font-weight-800-margin-0-0-12px">
                همین حالا <span class="u-background-var-grad-gold-webkit-background-clip-text-ba-1929d0">اصالت</span> قطعه خود را بررسی کنید
            </h2>
            <p class="u-color-var-d-gray-300-margin-0">
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