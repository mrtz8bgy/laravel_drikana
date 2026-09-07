@extends('frontend.layouts.app')

@section('meta_title', 'اعلام سرقت و مفقودی طلا و جواهر | سامانه رهگیری دریکانا')
@section('meta_description', 'در صورت سرقت یا مفقودی طلا، جواهر یا ساعت خود، از طریق سامانه دریکانا گزارش ثبت کنید تا قطعه در شبکه سراسری رهگیری شود.')

@section('content')
<style>
    .stolen-hero {
        background: linear-gradient(135deg, #2a0a0a 0%, #1a0505 40%, var(--d-navy-900) 100%);
        padding: 70px 0 50px; text-align:center; position:relative; overflow:hidden;
        border-bottom: 1px solid rgba(239,68,68,.2);
    }
    .stolen-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(circle at 50% 100%, rgba(239,68,68,.15), transparent 60%); }
    .stolen-hero > * { position:relative; z-index:2; }
    .stolen-hero .kicker { display:inline-block; font-size:12px; letter-spacing:3px; color:#fca5a5; font-weight:700; margin-bottom:12px; padding:6px 18px; border:1px solid rgba(239,68,68,.3); border-radius:var(--r-pill); }
    .stolen-hero h1 { font-size:clamp(26px,4vw,44px); font-weight:800; margin:0 0 14px; }
    .stolen-hero h1 .red { color:#ef4444; }
    .stolen-hero h1 .gold { background:var(--grad-gold); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .stolen-hero p { color:var(--d-gray-300); max-width:720px; margin:0 auto; font-size:15px; line-height:2; }

    .stolen-section { padding: 60px 0 80px; }
    .two-col { display:grid; grid-template-columns:1.4fr 1fr; gap:30px; align-items:start; }

    .report-form-card {
        background: rgba(255,255,255,.03); border:1px solid rgba(212,175,55,.15);
        border-radius: var(--r-lg); padding:32px; box-shadow:var(--shadow-md);
    }
    .form-row { margin-bottom:18px; }
    .form-row label { display:block; font-size:13px; font-weight:700; color:var(--d-gold-light); margin-bottom:8px; }
    .form-row label .req { color:#ef4444; margin-right:4px; }
    .form-row input, .form-row select, .form-row textarea {
        width:100%; background:rgba(255,255,255,.05); border:1px solid rgba(212,175,55,.2);
        color:var(--d-white); border-radius:var(--r-sm); padding:11px 14px;
        font-family:var(--f-base); font-size:14px; transition:all var(--tr-base);
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus {
        outline:none; border-color:var(--d-gold); box-shadow:0 0 0 4px rgba(212,175,55,.12);
    }
    .form-row textarea { min-height:120px; resize:vertical; }
    .form-row .hint { font-size:12px; color:var(--d-gray-500); margin-top:6px; }

    .radio-group { display:flex; gap:10px; flex-wrap:wrap; }
    .radio-card {
        flex:1; min-width:140px; padding:16px; border-radius:var(--r-md);
        border:1px solid rgba(212,175,55,.2); background:rgba(255,255,255,.03);
        cursor:pointer; text-align:center; transition:all var(--tr-base);
    }
    .radio-card input { display:none; }
    .radio-card .rc-icon { font-size:32px; margin-bottom:8px; color:var(--d-gold); }
    .radio-card .rc-label { font-weight:700; font-size:14px; }
    .radio-card:hover { border-color:var(--d-gold); }
    .radio-card.active { background:linear-gradient(135deg, rgba(212,175,55,.15), rgba(212,175,55,.05)); border-color:var(--d-gold); box-shadow:var(--shadow-gold); }

    .info-panel {
        background: linear-gradient(145deg, rgba(239,68,68,.08), rgba(212,175,55,.04));
        border:1px solid rgba(239,68,68,.2);
        border-radius:var(--r-lg); padding:28px;
    }
    .info-panel h4 { font-size:17px; font-weight:800; margin-bottom:18px; display:flex; align-items:center; gap:10px; color:#fca5a5; }
    .info-panel h4 i { color:#ef4444; font-size:22px; }
    .info-step { display:flex; gap:12px; margin-bottom:16px; align-items:flex-start; }
    .info-step .sn { width:32px; height:32px; border-radius:50%; background:var(--grad-gold); color:var(--d-navy-900); font-weight:900; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; }
    .info-step .sc h6 { font-size:14px; margin:0 0 4px; color:var(--d-white); }
    .info-step .sc p { font-size:12px; color:var(--d-gray-300); line-height:1.7; margin:0; }

    .stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:20px; }
    .stat-mini { text-align:center; padding:14px; background:rgba(0,0,0,.25); border-radius:var(--r-md); border:1px solid rgba(255,255,255,.06); }
    .stat-mini strong { display:block; font-size:22px; font-weight:900; background:var(--grad-gold); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .stat-mini span { font-size:11px; color:var(--d-gray-300); margin-top:4px; display:block; }

    @if(Auth::check())
    @else
    .guest-notice {
        background:rgba(251,191,36,.08); border:1px solid rgba(251,191,36,.25);
        border-radius:var(--r-md); padding:14px 18px; margin-bottom:20px;
        display:flex; gap:12px; align-items:flex-start;
    }
    .guest-notice i { font-size:22px; color:#fbbf24; flex-shrink:0; }
    .guest-notice p { margin:0; font-size:13px; color:#fde68a; line-height:1.8; }
    .guest-notice a { color:var(--d-gold); font-weight:700; }
    @endif

    @media(max-width:992px){ .two-col{grid-template-columns:1fr;} }
</style>

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
