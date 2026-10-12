@extends('frontend.layouts.app')

@section('meta_title', 'اعلام سرقت و مفقودی طلا و جواهر | سامانه رهگیری دریکانا')
@section('meta_description', 'در صورت سرقت یا مفقودی طلا، جواهر یا ساعت خود، از طریق سامانه دریکانا گزارش ثبت کنید تا قطعه در شبکه سراسری رهگیری شود.')

@section('content')
{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/ownership.css') }}">
@endpush

<section class="stolen-hero">
    <div class="container">
        <span class="kicker"><i class="la la-shield"></i> سامانه یکپارچه رهگیری</span>
        <h1>اعلام <span class="red">سرقت</span> / <span class="gold">مفقودی</span> طلا و جواهر</h1>
        <p>در صورت سرقت یا مفقودی هرگونه طلا، جواهر، ساعت لوکس، سنگ قیمتی یا اشیاء ارزشمند، مراتب را سریعاً در این سامانه ثبت کنید. قطعه شما بلافاصله در شبکه ملی دریکانا، طلافروشان، همکاران و مراجع قانونی به عنوان «ممنوع‌المعامله» ثبت می‌شود.</p>
    </div>
</section>

<section class="stolen-section">
    <div class="container">

        <div class="two-col">
            <!-- Form -->
            <div class="report-form-card">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid rgba(212,175,55,.12);">
                    <div style="width:46px; height:46px; border-radius:50%; background:linear-gradient(135deg,#ef4444,#b91c1c); display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px;">
                        <i class="la la-bullhorn"></i>
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:18px; font-weight:800;">ثبت گزارش سرقت / مفقودی</h3>
                        <p style="margin:4px 0 0; font-size:12px; color:var(--d-gray-500);">تمام اطلاعات محرمانه باقی می‌ماند</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert-lux success"><i class="la la-check-circle"></i><span>{{ session('success') }}</span></div>
                @endif
                @if(session('warning'))
                    <div class="alert-lux warn"><i class="la la-exclamation-triangle"></i><span>{{ session('warning') }}</span></div>
                @endif
                @if($errors->any())
                    <div class="alert-lux danger">
                        <i class="la la-times-circle"></i>
                        <div>
                            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                        </div>
                    </div>
                @endif

                @auth
                    <form method="POST" action="{{ route('jewelry.missing_reports.store_public') }}">
                        @csrf
                        <div class="form-row">
                            <label><span class="req">*</span> نوع گزارش</label>
                            <div class="radio-group">
                                <label class="radio-card" onclick="this.parentElement.querySelectorAll('.radio-card').forEach(el=>el.classList.remove('active')); this.classList.add('active');">
                                    <input type="radio" name="type" value="theft" required>
                                    <div class="rc-icon">🚨</div>
                                    <div class="rc-label">سرقت شده</div>
                                </label>
                                <label class="radio-card" onclick="this.parentElement.querySelectorAll('.radio-card').forEach(el=>el.classList.remove('active')); this.classList.add('active');">
                                    <input type="radio" name="type" value="lost">
                                    <div class="rc-icon">🔍</div>
                                    <div class="rc-label">مفقود شده</div>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <label><span class="req">*</span> انتخاب قطعه از گنجینه من</label>
                            @if($jewelries && $jewelries->count() > 0)
                                <select name="jewelry_id" required class="form-control-lux">
                                    <option value="">-- قطعه را انتخاب کنید --</option>
                                    @foreach($jewelries as $j)
                                        <option value="{{ $j->id }}">{{ $j->product_name }} | سریال: {{ $j->serial_number }} | {{ $j->metal_type }} {{ $j->weight ? $j->weight . ' گرم' : '' }}</option>
                                    @endforeach
                                </select>
                                <div class="hint">فقط قطعاتی که شما به عنوان مالک در سامانه ثبت شده‌اند نمایش داده می‌شوند.</div>
                            @else
                                <div style="padding:14px; background:rgba(251,191,36,.08); border-radius:var(--r-sm); border:1px dashed rgba(251,191,36,.3); color:#fde68a; font-size:13px; text-align:center;">
                                    <i class="la la-info-circle" style="margin-left:6px;"></i>
                                    شما هنوز هیچ قطعه‌ای در گنجینه خود ثبت نکرده‌اید. برای ثبت شناسنامه جدید <a href="{{ route('admin.jewelry.certificates.create') }}">کلیک کنید</a>.
                                </div>
                            @endif
                        </div>

                        <div class="form-row">
                            <label>مکان تقریبی حادثه</label>
                            <input type="text" name="location" placeholder="مثلاً: تهران، خیابان ولیعصر، پلاک ..." value="{{ old('location') }}">
                        </div>

                        <div class="form-row">
                            <label><span class="req">*</span> شرح کامل حادثه</label>
                            <textarea name="description" placeholder="توضیح دهید چه زمانی و چگونه قطعه به سرقت رفت یا مفقود شد، مشخصات ظاهری خاص قطعه، و هر اطلاعاتی که به شناسایی کمک می‌کند...">{{ old('description') }}</textarea>
                            <div class="hint">هرچه توضیحات دقیق‌تر باشد، شانس بازیابی قطعه بالاتر می‌رود.</div>
                        </div>

                        <button type="submit" class="btn-lux btn-lux-primary" style="width:100%; justify-content:center; padding:15px 30px; font-size:16px;">
                            <i class="la la-paper-plane"></i> ثبت گزارش و آغاز رهگیری
                        </button>
                    </form>
                @else
                    <div class="guest-notice">
                        <i class="la la-lock"></i>
                        <p>برای ثبت رسمی گزارش سرقت یا مفقودی، لطفاً ابتدا <a href="{{ route('user.login') }}">وارد حساب کاربری</a> شوید. در صورت نداشتن حساب، <a href="{{ route('user.registration') }}">ثبت‌نام</a> کنید. برای گزارش فوری بدون ورود، می‌توانید با پشتیبانی <a href="tel:02112345678">تماس بگیرید</a>.</p>
                    </div>
                    <a href="{{ route('user.login') }}" class="btn-lux btn-lux-primary" style="width:100%; justify-content:center; padding:14px;">
                        <i class="la la-sign-in"></i> ورود و ثبت گزارش
                    </a>
                @endauth
            </div>

            <!-- Info Panel -->
            <div>
                <div class="info-panel">
                    <h4><i class="la la-list-ol"></i> مراحل رهگیری پس از ثبت گزارش</h4>
                    <div class="info-step">
                        <div class="sn">۱</div>
                        <div class="sc">
                            <h6>ثبت فوری در سامانه</h6>
                            <p>گزارش شما در کمتر از چند دقیقه در سامانه ثبت شده و کد پیگیری دریافت می‌کنید.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="sn">۲</div>
                        <div class="sc">
                            <h6>بررسی کارشناسان</h6>
                            <p>کارشناسان دریکانا در کوتاه‌ترین زمان با شما تماس گرفته و صحت گزارش را تایید می‌کنند.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="sn">۳</div>
                        <div class="sc">
                            <h6>اعلام به شبکه همکاران</h6>
                            <p>مشخصات قطعه به شبکه طلافروشان، مراکز خریدوفروش، تعمیرکاران و pawnshop ها ارسال می‌شود.</p>
                        </div>
                    </div>
                    <div class="info-step">
                        <div class="sn">۴</div>
                        <div class="sc">
                            <h6>رهگیری هوشمند</h6>
                            <p>در صورت استعلام یا مشاهده قطعه در هر نقطه، به شما و مراجع قضایی اطلاع‌رسانی می‌شود.</p>
                        </div>
                    </div>

                    <div class="stats-row">
                        <div class="stat-mini">
                            <strong>{{ $stats['total_certificates'] ?? 0 }}</strong>
                            <span>قطعه شناسنامه‌دار</span>
                        </div>
                        <div class="stat-mini">
                            <strong>{{ $stats['recovered'] ?? 0 }}</strong>
                            <span>مورد بازیابی شده</span>
                        </div>
                        <div class="stat-mini">
                            <strong>{{ $stats['pending'] ?? 0 }}</strong>
                            <span>گزارش فعال</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:20px; background:rgba(255,255,255,.03); border:1px solid rgba(212,175,55,.12); border-radius:var(--r-md); padding:22px;">
                    <h5 style="font-size:15px; font-weight:700; margin:0 0 12px; display:flex; align-items:center; gap:8px; color:var(--d-gold);">
                        <i class="la la-phone"></i> تماس اضطراری
                    </h5>
                    <p style="font-size:13px; color:var(--d-gray-300); line-height:2; margin:0 0 12px;">
                        در صورت نیاز فوری با خطوط ویژه پشتیبانی ۲۴ ساعته تماس بگیرید:
                    </p>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <a href="tel:02112345678" style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:rgba(212,175,55,.08); border-radius:var(--r-sm); color:var(--d-gold-light); font-weight:700; text-decoration:none;">
                            <i class="la la-phone"></i> ۰۲۱-۱۲۳۴۵۶۷۸
                        </a>
                        <a href="tel:110" style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:rgba(239,68,68,.1); border-radius:var(--r-sm); color:#fca5a5; font-weight:700; text-decoration:none;">
                            <i class="la la-warning"></i> پلیس ۱۱۰
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    // Default select first radio card if any
    document.querySelectorAll('.radio-group').forEach(g => {
        const first = g.querySelector('.radio-card');
        if(first) first.classList.add('active');
    });
</script>
@endsection
