<header class="d-header">
    <!-- Top Bar -->
    <div class="d-topbar">
        <div class="container">
            <div class="d-topbar-inner">
                <div class="d-contact-info">
                    <span><i class="la la-phone"></i> پشتیبانی ۲۴ ساعته: {{ \App\GeneralSetting::first()->phone ?? '۰۲۱-۱۲۳۴۵۶۷۸' }}</span>
                    <span><i class="la la-envelope"></i> {{ \App\GeneralSetting::first()->email ?? 'info@drikana.com' }}</span>
                    <span><i class="la la-map-marker"></i> تهران، بازار بزرگ طلا</span>
                </div>
                <ul class="d-top-links">
                    <li><a href="{{ route('orders.track') }}"><i class="la la-map-pin"></i> رهگیری سفارش</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}"><i class="la la-user"></i> پنل من</a></li>
                        <li><a href="{{ route('wishlists.index') }}"><i class="la la-heart-o"></i> علاقه‌مندی‌ها</a></li>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="la la-sign-out"></i> خروج</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                        </li>
                    @else
                        <li><a href="{{ route('user.login') }}"><i class="la la-sign-in"></i> ورود</a></li>
                        <li><a href="{{ route('user.registration') }}"><i class="la la-user-plus"></i> ثبت‌نام</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Bar -->
    <div class="d-mainbar">
        <div class="container">
            <div class="d-mainbar-inner">
                <button class="d-mobile-toggle" onclick="document.querySelector('.mobile-side-menu').classList.add('open');" aria-label="منو">
                    <i class="la la-bars"></i>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="d-logo">
                    @php
                        $gs = \App\GeneralSetting::first();
                        $logoUrl = ($gs && $gs->logo && file_exists(public_path($gs->logo))) ? asset($gs->logo) : asset('frontend/images/logo/drikana-logo.svg');
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Drikana" style="height:48px;" onerror="this.src='{{ asset('frontend/images/logo/drikana-logo.svg') }}'">
                </a>

                <!-- Search -->
                <div class="d-search">
                    <form action="{{ route('search') }}" method="GET" class="d-search-form">
                        <input type="text" id="search" name="q" placeholder="جستجوی طلا، جواهر، ساعت، سنگ قیمتی..." autocomplete="off" required>
                        <button type="submit" aria-label="جستجو"><i class="la la-search la-flip-horizontal"></i></button>
                        <div class="typed-search-box d-none">
                            <div class="search-preloader"><div class="loader"><div></div><div></div><div></div></div></div>
                            <div class="search-nothing d-none"></div>
                            <div id="search-content"></div>
                        </div>
                    </form>
                </div>

                <!-- Header Icons -->
                <div class="d-header-icons">
                    <a href="{{ route('jewelry.certificates.verify_page') }}" class="d-icon-btn" title="استعلام شناسنامه" style="text-decoration:none;">
                        <i class="la la-certificate"></i>
                    </a>
                    <a href="{{ route('jewelry.missing_reports.create_public') }}" class="d-icon-btn hide-sm" title="اعلام سرقت/مفقودی" style="text-decoration:none;">
                        <i class="la la-shield"></i>
                    </a>
                    <a href="{{ route('compare') }}" class="d-icon-btn hide-sm" title="مقایسه" style="text-decoration:none;">
                        <i class="la la-refresh"></i>
                        @if(Session::has('compare'))<span class="badge">{{ count(Session::get('compare')) }}</span>@else<span class="badge">0</span>@endif
                    </a>
                    <a href="{{ route('wishlists.index') }}" class="d-icon-btn" title="علاقه‌مندی" style="text-decoration:none;">
                        <i class="la la-heart-o"></i>
                    </a>
                    <div class="d-user-dropdown">
                        <a href="{{ route('cart') }}" class="d-icon-btn" title="سبد خرید" style="text-decoration:none;" id="cart_items_sidenav_box">
                            <i class="la la-shopping-cart"></i>
                            @if(Session::has('cart'))<span class="badge">{{ count(Session::get('cart')) }}</span>@else<span class="badge">0</span>@endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Navigation -->
    <div class="d-nav">
        <div class="container">
            <div class="d-nav-inner">
                <a href="{{ route('categories.all') }}" class="d-cat-btn">
                    <i class="la la-bars"></i>
                    <span>همه دسته‌بندی‌ها</span>
                </a>
                <ul class="d-nav-links">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}"><i class="la la-home"></i> خانه</a></li>
                    <li><a href="#"><i class="la la-diamond"></i> طلا و جواهر</a></li>
                    <li><a href="#"><i class="la la-clock-o"></i> ساعت</a></li>
                    <li><a href="#"><i class="la la-gem"></i> سنگ‌های قیمتی</a></li>
                    <li><a href="{{ route('jewelry.certificates.verify_page') }}"><i class="la la-certificate"></i> استعلام شناسنامه</a></li>
                    <li><a href="{{ route('jewelry.missing_reports.create_public') }}"><i class="la la-bullhorn"></i> اعلام سرقت</a></li>
                    <li><a href="#"><i class="la la-phone"></i> تماس با ما</a></li>
                </ul>
                <div class="d-nav-cta">
                    @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                        <a href="{{ route('shops.create') }}" class="btn-lux btn-lux-primary" style="padding:8px 20px;font-size:13px;">
                            <i class="la la-plus"></i> فروشنده شوید
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

@php
    try {
        $luxMegaCategories = \Illuminate\Support\Facades\Schema::hasTable('categories')
            ? \App\Category::with(['subcategories' => function($q){
                $q->orderBy('name')->with(['subsubcategories' => function($sq){ $sq->orderBy('name')->take(8); }]);
            }])->whereHas('subcategories')->orderBy('name')->take(11)->get()
            : collect();
    } catch (\Exception $e) { $luxMegaCategories = collect(); }
    $luxCatIcons = [
        'انگشتر' => 'drikana/ring.svg', 'حلقه' => 'drikana/ring.svg', 'ring' => 'drikana/ring.svg',
        'گردنبند' => 'drikana/necklace.svg', 'ساعت' => 'drikana/watch.svg', 'watch' => 'drikana/watch.svg',
        'الماس' => 'drikana/diamond.svg', 'سنگ' => 'drikana/diamond.svg', 'تاج' => 'drikana/crown.svg',
        'سکه' => 'drikana/coin.svg', 'شمش' => 'drikana/coin.svg', 'طلا' => 'drikana/coin.svg',
        'هدیه' => 'drikana/gift.svg', 'عتیقه' => 'drikana/antique.svg',
    ];
@endphp
<section class="lux-mega-section">
    <div class="lux-mega-container">
        <div class="lux-mega-trigger" id="luxMegaTrigger">
            <a href="#" class="lux-cat-btn" onclick="return false;">
                <i class="la la-bars"></i>
                <span>همه دسته‌بندی‌های طلا و جواهر</span>
                <i class="la la-angle-down"></i>
            </a>
            <ul class="lux-mega-list" id="luxMegaList">
                @foreach($luxMegaCategories as $cat)
                    @php
                        $catIcon = null;
                        foreach($luxCatIcons as $key => $icon) {
                            if(mb_strpos($cat->name, $key) !== false) { $catIcon = $icon; break; }
                        }
                    @endphp
                    <li class="lux-mega-item">
                        <a href="{{ route('products.category', $cat->slug) }}" class="lux-mega-link">
                            <span class="lux-mega-ico">
                                @if($catIcon)
                                    <img src="{{ asset('frontend/images/'.$catIcon) }}" alt="{{ $cat->name }}">
                                @elseif($cat->icon && file_exists(public_path($cat->icon)))
                                    <img src="{{ asset($cat->icon) }}" alt="{{ $cat->name }}">
                                @else
                                    <i class="la la-diamond"></i>
                                @endif
                            </span>
                            <span class="lux-mega-name">{{ $cat->name }}</span>
                            @if($cat->subcategories->isNotEmpty())
                                <i class="la la-angle-left lux-mega-arrow"></i>
                            @endif
                        </a>
                        @if($cat->subcategories->isNotEmpty())
                            <div class="lux-mega-sub">
                                <div class="row" style="margin:0;">
                                    @foreach($cat->subcategories->take(9) as $sub)
                                        <div class="col-lg-4 col-md-6" style="padding:6px;">
                                            <a href="{{ route('products.subcategory', $sub->slug) }}" class="lux-sub-cat-title">
                                                @if($sub->icon && file_exists(public_path($sub->icon)))
                                                    <img src="{{ asset($sub->icon) }}" alt="" style="width:18px; height:18px; object-fit:contain;">
                                                @else
                                                    <i class="la la-folder-o" style="font-size:14px;"></i>
                                                @endif
                                                <span>{{ $sub->name }}</span>
                                            </a>
                                            @if($sub->subsubcategories->isNotEmpty())
                                                <ul class="lux-sub-cat-list">
                                                    @foreach($sub->subsubcategories as $subsub)
                                                        <li><a href="{{ route('products.subsubcategory', $subsub->slug) }}">{{ $subsub->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('products.category', $cat->slug) }}" class="lux-mega-banner" style="text-decoration:none;">
                                    <img src="{{ asset('frontend/images/drikana/diamond.svg') }}" alt="">
                                    <div class="lux-mega-banner-text">
                                        <strong>مشاهده همه {{ $cat->name }}</strong>
                                        <span>جدیدترین مدل‌ها با بهترین قیمت و شناسنامه معتبر</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </li>
                @endforeach
                <li class="lux-mega-item lux-mega-viewall">
                    <a href="{{ route('categories.all') }}" class="lux-mega-link">
                        <i class="la la-th-large" style="font-size:18px;"></i>
                        <span>مشاهده همه دسته‌بندی‌ها</span>
                        <i class="la la-arrow-left"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- Mobile side menu (kept from original with dark theme adjustments) -->
@include('frontend.partials.mobile_menu_lux')

<script>
(function(){
    var trigger = document.getElementById('luxMegaTrigger');
    var list = document.getElementById('luxMegaList');
    var btn = trigger ? trigger.querySelector('.lux-cat-btn') : null;
    var items = trigger ? trigger.querySelectorAll('.lux-mega-item') : [];
    function isMobile(){ return window.innerWidth <= 992; }

    // Toggle root list on mobile
    if(btn) btn.addEventListener('click', function(e){
        e.preventDefault();
        if(!isMobile()) return;
        trigger.classList.toggle('open');
    });

    items.forEach(function(item){
        var link = item.querySelector('.lux-mega-link');
        link.addEventListener('click', function(e){
            if(!isMobile()) return;
            var hasSub = item.querySelector('.lux-mega-sub');
            if(hasSub){
                e.preventDefault();
                items.forEach(function(i){ if(i!==item) i.classList.remove('open'); });
                item.classList.toggle('open');
            }
        });
        // Desktop hover
        item.addEventListener('mouseenter', function(){
            if(!isMobile()) return;
        });
    });

    // Close on outside click (mobile)
    document.addEventListener('click', function(e){
        if(!trigger) return;
        if(!trigger.contains(e.target)){
            trigger.classList.remove('open');
            items.forEach(function(i){ i.classList.remove('open'); });
        }
    });

    // Also wire up the "همه دسته‌بندی‌ها" button in nav to toggle
    var catBtn = document.querySelector('.d-cat-btn');
    if(catBtn && trigger && catBtn !== btn){
        catBtn.addEventListener('click', function(e){
            e.preventDefault();
            if(isMobile()){
                trigger.classList.toggle('open');
                trigger.scrollIntoView({behavior:'smooth', block:'start'});
            } else {
                // simulate hover
                trigger.classList.toggle('force-open');
            }
        });
    }
})();
</script>
