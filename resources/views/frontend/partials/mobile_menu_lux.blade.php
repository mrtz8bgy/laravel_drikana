{{-- Mobile Menu (Luxury Drikana) --}}
<div class="mobile-side-menu d-lg-none" id="mobileSideMenu">
    <div class="side-menu-overlay" onclick="this.parentElement.classList.remove('open');"></div>
    <div class="side-menu-wrap">
        <div class="side-menu">
            <div class="side-menu-header">
                <div class="side-menu-close" onclick="document.getElementById('mobileSideMenu').classList.remove('open');">
                    <i class="la la-close"></i>
                </div>
                @auth
                    <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                        @if (Auth::user()->avatar_original != null)
                            <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}');"></div>
                        @else
                            <div class="image" style="background-image:url('{{ asset('frontend/images/user.png') }}');"></div>
                        @endif
                        <div class="name">{{ Auth::user()->name }}</div>
                    </div>
                @else
                    <div class="px-3 py-3">
                        <a href="{{ route('user.login') }}" class="btn-lux btn-lux-primary w-100 mb-2 u-justify-content-center"><i class="la la-sign-in"></i> ورود</a>
                        <a href="{{ route('user.registration') }}" class="btn-lux btn-lux-outline w-100 u-justify-content-center"><i class="la la-user-plus"></i> ثبت‌نام</a>
                    </div>
                @endauth
            </div>
            <div class="side-menu-list px-3">
                <ul class="side-user-menu">
                    <li><a href="{{ route('home') }}"><i class="la la-home"></i> خانه</a></li>
                    <li><a href="{{ route('categories.all') }}"><i class="la la-th-large"></i> دسته‌بندی‌ها</a></li>
                    <li><a href="{{ route('products.featured') }}"><i class="la la-star"></i> محصولات ویژه</a></li>
                    <li><a href="{{ route('jewelry.certificates.verify_page') }}"><i class="la la-certificate"></i> استعلام شناسنامه</a></li>
                    <li><a href="{{ route('jewelry.missing_reports.create_public') }}"><i class="la la-bullhorn"></i> اعلام سرقت/مفقودی</a></li>
                    <li><a href="{{ route('orders.track') }}"><i class="la la-map-pin"></i> رهگیری سفارش</a></li>
                    <li><a href="{{ route('articles.index') }}"><i class="la la-book"></i> مقالات و راهنما</a></li>
                    <li><a href="{{ route('contact') }}"><i class="la la-phone"></i> تماس با ما</a></li>
                    <li><a href="{{ route('cart') }}"><i class="la la-shopping-cart"></i> سبد خرید
                        @if(Session::has('cart'))<span class="badge">{{ count((Session::get('cart') ?? [])) }}</span>@endif</a></li>
                    <li><a href="{{ route('wishlists.index') }}"><i class="la la-heart-o"></i> علاقه‌مندی‌ها</a></li>
                    <li><a href="{{ route('compare') }}"><i class="la la-refresh"></i> مقایسه</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}"><i class="la la-dashboard"></i> داشبورد</a></li>
                        <li><a href="{{ route('purchase_history.index') }}"><i class="la la-file-text"></i> تاریخچه خرید</a></li>
                        <li><a href="{{ route('profile') }}"><i class="la la-user"></i> پروفایل</a></li>
                        <li><a href="{{ route('support_ticket.index') }}"><i class="la la-support"></i> پشتیبانی</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</div>
