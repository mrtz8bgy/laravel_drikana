{{-- Footer services bar --}}
@php $gs = \App\GeneralSetting::first(); @endphp

<footer class="d-footer">
    <div class="container">
        <div class="d-footer-grid">
            {{-- Column 1: Brand --}}
            <div class="d-footer-col d-footer-brand">
                <a href="{{ route('home') }}" class="d-logo" style="text-decoration:none;">
                    <div class="d-logo-mark">
                        @if($gs->logo != null && file_exists(public_path($gs->logo)))
                            <img src="{{ asset($gs->logo) }}" alt="Drikana" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                        @else
                            D
                        @endif
                    </div>
                    <div class="d-logo-text">
                        <span class="brand-name">DRIKANA</span>
                        <span class="brand-tag">Luxury Marketplace</span>
                    </div>
                </a>
                <p>
                    دریکانا، مارکت‌پلیس تخصصی طلا، جواهرات، ساعت‌های لوکس، سنگ‌های قیمتی و اشیاء ارزشمند.
                    با ارائه شناسنامه معتبر، رهگیری امن و گارانتی اصالت کالا، خریدی مطمئن را تجربه کنید.
                </p>
                <div class="d-footer-social">
                    @if($gs->instagram)<a href="{{ $gs->instagram }}" target="_blank" aria-label="Instagram"><i class="fa fa-instagram"></i></a>@endif
                    @if($gs->facebook)<a href="{{ $gs->facebook }}" target="_blank" aria-label="Facebook"><i class="fa fa-facebook"></i></a>@endif
                    @if($gs->twitter)<a href="{{ $gs->twitter }}" target="_blank" aria-label="Twitter"><i class="fa fa-twitter"></i></a>@endif
                    @if($gs->youtube)<a href="{{ $gs->youtube }}" target="_blank" aria-label="Youtube"><i class="fa fa-youtube-play"></i></a>@endif
                    @if($gs->google_plus)<a href="{{ $gs->google_plus }}" target="_blank" aria-label="Google+"><i class="fa fa-google-plus"></i></a>@endif
                    <a href="#" aria-label="Telegram"><i class="fa fa-telegram"></i></a>
                    <a href="#" aria-label="Whatsapp"><i class="fa fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- Column 2: Links --}}
            <div class="d-footer-col">
                <h5>دسترسی سریع</h5>
                <ul class="d-footer-links">
                    <li><a href="{{ route('home') }}">صفحه اصلی</a></li>
                    <li><a href="{{ route('categories.all') }}">همه دسته‌بندی‌ها</a></li>
                    <li><a href="{{ route('products.featured') }}">محصولات ویژه</a></li>
                    <li><a href="{{ route('products.best_selling') }}">پرفروش‌ترین‌ها</a></li>
                    <li><a href="{{ route('products.todays_deal') }}">تخفیف‌های امروز</a></li>
                    <li><a href="{{ route('brands.all') }}">برندها</a></li>
                    <li><a href="#">وبلاگ و مقالات</a></li>
                </ul>
            </div>

            {{-- Column 3: Services --}}
            <div class="d-footer-col">
                <h5>خدمات مشتریان</h5>
                <ul class="d-footer-links">
                    <li><a href="{{ route('orders.track') }}">رهگیری سفارش</a></li>
                    <li><a href="{{ route('jewelry.certificates.verify_page') }}">استعلام شناسنامه طلا</a></li>
                    <li><a href="{{ route('jewelry.missing_reports.create') }}">اعلام سرقت / مفقودی</a></li>
                    <li><a href="{{ route('sellerpolicy') }}">قوانین فروشندگان</a></li>
                    <li><a href="{{ route('returnpolicy') }}">شرایط بازگشت کالا</a></li>
                    <li><a href="{{ route('supportpolicy') }}">پشتیبانی</a></li>
                    <li><a href="{{ route('privacypolicy') }}">حریم خصوصی</a></li>
                    <li><a href="{{ route('terms') }}">قوانین استفاده</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact + Newsletter --}}
            <div class="d-footer-col">
                <h5>ارتباط با ما</h5>
                <ul class="d-footer-contact">
                    <li><i class="la la-map-marker"></i> <span>{{ $gs->address ?? 'تهران، خیابان فردوسی، بازار بزرگ طلا، پلاک ۱۲۳' }}</span></li>
                    <li><i class="la la-phone"></i> <span>{{ $gs->phone ?? '۰۲۱-۱۲۳۴۵۶۷۸' }}</span></li>
                    <li><i class="la la-envelope"></i> <span>{{ $gs->email ?? 'info@drikana.com' }}</span></li>
                    <li><i class="la la-clock-o"></i> <span>شنبه تا پنجشنبه، ۹ الی ۲۰</span></li>
                </ul>
                <div style="margin-top:18px;">
                    <div style="color: var(--d-gold); font-size:13px; font-weight:600; margin-bottom:8px;">
                        <i class="la la-bullhorn"></i> عضویت در خبرنامه
                    </div>
                    <form method="POST" action="{{ route('subscribers.store') }}" class="d-newsletter-form">
                        @csrf
                        <input type="email" name="email" placeholder="ایمیل شما..." required>
                        <button type="submit" aria-label="عضویت"><i class="la la-paper-plane la-flip-horizontal"></i></button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="d-footer-bottom">
            <div>© {{ date('Y') }} {{ $gs->site_name ?? 'Drikana' }} — تمامی حقوق محفوظ است. طراحی شده با ❤️ برای بازار طلا و جواهر.</div>
            <div class="pay-methods">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 40 24'><rect width='40' height='24' fill='%23d4af37' rx='3'/><text x='50%25' y='56%25' font-family='sans-serif' font-size='9' fill='%2307091a' text-anchor='middle' font-weight='900'>VISA</text></svg>" alt="Visa">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 40 24'><rect width='40' height='24' fill='%231a1f4d' rx='3'/><text x='50%25' y='56%25' font-family='sans-serif' font-size='8' fill='%23d4af37' text-anchor='middle' font-weight='900'>Master</text></svg>" alt="MasterCard">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 40 24'><rect width='40' height='24' fill='%23d4af37' rx='3'/><text x='50%25' y='56%25' font-family='sans-serif' font-size='8' fill='%2307091a' text-anchor='middle' font-weight='900'>SHETAB</text></svg>" alt="Shetab">
                @if(\App\BusinessSetting::where('type','cash_payment')->first()->value == 1)
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 40 24'><rect width='40' height='24' fill='%23f5f3f0' rx='3'/><text x='50%25' y='56%25' font-family='sans-serif' font-size='7' fill='%2307091a' text-anchor='middle' font-weight='900'>Cash</text></svg>" alt="Cash on delivery">
                @endif
            </div>
        </div>
    </div>
</footer>
