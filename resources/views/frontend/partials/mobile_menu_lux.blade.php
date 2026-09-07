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
                        <a href="{{ route('user.login') }}" class="btn-lux btn-lux-primary w-100 mb-2" style="justify-content:center;"><i class="la la-sign-in"></i> ورود</a>
                        <a href="{{ route('user.registration') }}" class="btn-lux btn-lux-outline w-100" style="justify-content:center;"><i class="la la-user-plus"></i> ثبت‌نام</a>
                    </div>
                @endauth
            </div>
            <div class="side-menu-list px-3">
                <ul class="side-user-menu">
                    <li><a href="{{ route('home') }}"><i class="la la-home"></i> خانه</a></li>
                    <li><a href="{{ route('categories.all') }}"><i class="la la-th-large"></i> دسته‌بندی‌ها</a></li>
                    <li><a href="{{ route('products.featured') }}"><i class="la la-star"></i> محصولات ویژه</a></li>
                    <li><a href="{{ route('jewelry.certificates.verify_page') }}"><i class="la la-certificate"></i> استعلام شناسنامه</a></li>
                    <li><a href="{{ route('jewelry.missing_reports.create') }}"><i class="la la-bullhorn"></i> اعلام سرقت/مفقودی</a></li>
                    <li><a href="{{ route('orders.track') }}"><i class="la la-map-pin"></i> رهگیری سفارش</a></li>
                    <li><a href="{{ route('cart') }}"><i class="la la-shopping-cart"></i> سبد خرید
                        @if(Session::has('cart'))<span class="badge">{{ count(Session::get('cart')) }}</span>@endif</a></li>
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

<style>
.mobile-side-menu { position: fixed; inset: 0; z-index: 1050; pointer-events: none; }
.mobile-side-menu .side-menu-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.7);
    opacity: 0; transition: opacity .3s ease;
}
.mobile-side-menu .side-menu-wrap {
    position: absolute; top: 0; right: 0; height: 100%; width: 320px; max-width: 85vw;
    transform: translateX(100%); transition: transform .35s cubic-bezier(.4,0,.2,1);
}
.mobile-side-menu.open { pointer-events: auto; }
.mobile-side-menu.open .side-menu-overlay { opacity: 1; }
.mobile-side-menu.open .side-menu-wrap { transform: translateX(0); }
.mobile-side-menu .side-menu {
    height: 100%; background: linear-gradient(180deg, #0b0f28, #07091a);
    border-left: 1px solid rgba(212,175,55,.2);
    overflow-y: auto; display: flex; flex-direction: column;
}
.mobile-side-menu .side-menu-header {
    border-bottom: 1px solid rgba(212,175,55,.15);
    background: rgba(212,175,55,.04);
}
.mobile-side-menu .side-menu-close {
    position: absolute; top: 12px; left: 12px; width: 36px; height: 36px;
    border-radius: 50%; background: rgba(255,255,255,.08); color: var(--d-gold);
    display: flex; align-items: center; justify-content: center; font-size: 20px; cursor: pointer;
}
.mobile-side-menu .widget-profile-box { color: var(--d-white); gap: 12px; }
.mobile-side-menu .widget-profile-box .image {
    width: 52px; height: 52px; border-radius: 50%;
    background-size: cover; background-position: center;
    border: 2px solid var(--d-gold);
}
.mobile-side-menu .widget-profile-box .name { color: var(--d-gold-light); font-weight: 700; font-size: 16px; }
.mobile-side-menu .side-user-menu { list-style: none; padding: 0; margin: 0; }
.mobile-side-menu .side-user-menu li { border-bottom: 1px solid rgba(212,175,55,.06); }
.mobile-side-menu .side-user-menu li a {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 8px; color: var(--d-gray-100); font-size: 14px; font-weight: 500;
}
.mobile-side-menu .side-user-menu li a i { color: var(--d-gold); font-size: 18px; width: 24px; text-align: center; }
.mobile-side-menu .side-user-menu li a:hover { background: rgba(212,175,55,.05); color: var(--d-gold); }
.mobile-side-menu .badge {
    margin-right: auto; background: var(--d-gold); color: var(--d-navy-900);
    font-size: 11px; padding: 2px 8px; border-radius: 999px; font-weight: 700;
}
</style>
