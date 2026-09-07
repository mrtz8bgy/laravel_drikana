@extends('frontend.layouts.app')

@section('meta_title', 'استعلام اصالت شناسنامه طلا و جواهر | دریکانا')
@section('meta_description', 'با وارد کردن شماره سریال شناسنامه، اصالت طلا، جواهر، ساعت و اشیاء قیمتی خود را در سامانه دریکانا استعلام کنید.')

@section('content')
<style>
    .verify-hero { background: var(--grad-navy); padding: 70px 0 50px; text-align: center; position: relative; overflow: hidden; border-bottom: 1px solid rgba(212,175,55,.15); }
    .verify-hero::before { content:''; position:absolute; inset:0; background: radial-gradient(circle at 50% 100%, rgba(212,175,55,.18), transparent 60%); }
    .verify-hero > * { position: relative; z-index: 2; }
    .verify-hero .kicker { display:inline-block; font-size:12px; letter-spacing:3px; color:var(--d-gold); font-weight:700; margin-bottom:12px; text-transform:uppercase; padding:6px 18px; border:1px solid rgba(212,175,55,.3); border-radius:var(--r-pill); }
    .verify-hero h1 { font-size:clamp(26px,4vw,44px); font-weight:800; margin:0 0 14px; }
    .verify-hero h1 .gold { background:var(--grad-gold); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .verify-hero p { color:var(--d-gray-300); max-width:680px; margin:0 auto; font-size:15px; line-height:2; }

    .verify-search { max-width:680px; margin: -30px auto 0; position: relative; z-index: 5; }
    .verify-search-box {
        background: linear-gradient(145deg, var(--d-navy-700), var(--d-navy-800));
        border:1px solid rgba(212,175,55,.25);
        border-radius: var(--r-xl);
        padding: 28px;
        box-shadow: var(--shadow-gold);
    }
    .verify-search-box h3 { color:var(--d-gold); font-size:17px; margin-bottom:16px; font-weight:700; display:flex; align-items:center; gap:10px; }
    .verify-search-box h3 i { font-size:24px; }
    .verify-form-row { display:flex; gap:12px; }
    .verify-form-row input {
        flex:1; background:rgba(255,255,255,.05) !important; border:1px solid rgba(212,175,55,.2) !important;
        color:var(--d-white) !important; border-radius:var(--r-pill) !important; padding:14px 22px !important;
        font-family:var(--f-base); font-size:16px; text-align:center; letter-spacing:3px;
    }
    .verify-form-row input:focus { border-color:var(--d-gold) !important; box-shadow:0 0 0 4px rgba(212,175,55,.15) !important; outline:none !important; }
    .verify-form-row button {
        padding:14px 32px; border-radius:var(--r-pill); background:var(--grad-gold); color:var(--d-navy-900);
        font-weight:800; border:none; cursor:pointer; font-family:var(--f-base); font-size:15px;
        transition:all var(--tr-base); white-space:nowrap; display:inline-flex; align-items:center; gap:8px;
    }
    .verify-form-row button:hover { transform:translateY(-2px); box-shadow:var(--shadow-gold-lg); }

    .verify-section { padding: 60px 0; }

    .cert-result { margin-top: 40px; animation: fadeInUp .5s ease-out; }
    .cert-paper {
        background: linear-gradient(135deg, #fdf7e6 0%, #f4e7c3 100%);
        border-radius: var(--r-lg);
        padding: 40px;
        color: var(--d-navy-900);
        position: relative;
        box-shadow: var(--shadow-lg), inset 0 0 0 8px rgba(212,175,55,.1);
        border: 3px double var(--d-gold-dark);
    }
    .cert-paper::before, .cert-paper::after {
        content: '❖'; position:absolute; color:var(--d-gold-dark); font-size:26px;
    }
    .cert-paper::before { top:14px; right:20px; }
    .cert-paper::after { bottom:14px; left:20px; }
    .cert-header { text-align:center; padding-bottom:20px; border-bottom:2px double var(--d-gold-dark); margin-bottom:24px; }
    .cert-header .cert-badge { display:inline-block; background:var(--d-gold-dark); color:#fff; font-size:11px; letter-spacing:3px; padding:4px 16px; border-radius:var(--r-pill); margin-bottom:10px; text-transform:uppercase; }
    .cert-header h3 { color:var(--d-navy-900) !important; font-size:26px; font-weight:900; margin:0; }
    .cert-header p { color:var(--d-gold-dark); font-size:13px; letter-spacing:2px; margin:6px 0 0; text-transform:uppercase; }

    .cert-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px 30px; margin-bottom:24px; }
    .cert-meta-item { padding:8px 0; border-bottom:1px dashed rgba(184,137,42,.3); }
    .cert-meta-item .label { font-size:11px; color:var(--d-gold-dark); font-weight:700; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:4px; }
    .cert-meta-item .value { font-size:15px; color:var(--d-navy-900); font-weight:700; }

    .cert-status-badge {
        display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:var(--r-pill);
        font-weight:800; font-size:14px; margin-top:8px;
    }
    .cert-status-badge.verified { background:#15803d; color:#fff; }
    .cert-status-badge.rejected { background:#b91c1c; color:#fff; }
    .cert-status-badge.pending  { background:#a16207; color:#fff; }

    .cert-qr-box {
        position:absolute; bottom:40px; left:40px; width:80px; height:80px;
        background:#fff; border:4px solid var(--d-gold-dark); border-radius:8px;
        display:flex; align-items:center; justify-content:center; font-size:40px;
    }
    .cert-seal-box {
        position:absolute; bottom:40px; right:40px; width:90px; height:90px; border-radius:50%;
        background: radial-gradient(circle, var(--d-gold) 30%, var(--d-gold-dark) 100%);
        display:flex; align-items:center; justify-content:center; color:var(--d-navy-900); font-weight:900;
        font-size:10px; text-align:center; line-height:1.2; box-shadow: 0 4px 14px rgba(0,0,0,.3);
        transform: rotate(-12deg);
    }

    .features-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-top:50px; }
    .feature-box { text-align:center; padding:26px 20px; background:rgba(255,255,255,.03); border:1px solid rgba(212,175,55,.12); border-radius:var(--r-md); transition:all var(--tr-base); }
    .feature-box:hover { transform:translateY(-6px); border-color:var(--d-gold); box-shadow:var(--shadow-gold); }
    .feature-box .fb-icon { width:60px; height:60px; margin:0 auto 14px; border-radius:50%; background:rgba(212,175,55,.12); border:2px solid rgba(212,175,55,.25); display:flex; align-items:center; justify-content:center; font-size:26px; color:var(--d-gold); }
    .feature-box h5 { font-size:15px; font-weight:700; margin-bottom:8px; }
    .feature-box p { font-size:13px; color:var(--d-gray-300); line-height:1.8; margin:0; }

    @media (max-width:768px){
        .cert-meta-grid{grid-template-columns:1fr;}
        .cert-paper{padding:24px;}
        .cert-qr-box,.cert-seal-box{position:relative;bottom:auto;left:auto;right:auto;margin:20px auto 0;}
        .verify-form-row{flex-direction:column;}
        .verify-form-row button{justify-content:center;}
    }
</style>

<section class="verify-hero">
    <div class="container">
        <span class="kicker"><i class="la la-shield"></i> سامانه اصالت‌سنجی</span>
        <h1>استعلام <span class="gold">شناسنامه طلا</span> و جواهرات</h1>
        <p>با وارد کردن شماره سریال حک شده روی شناسنامه یا قطعه، از اصالت کالا، مشخصات فنی، وضعیت مالکیت و سابقه آن در سامانه ملی دریکانا مطلع شوید.</p>
    </div>
</section>

<div class="container">
    <div class="verify-search">
        <div class="verify-search-box">
            <h3><i class="la la-search la-flip-horizontal"></i> شماره سریال شناسنامه را وارد کنید</h3>
            <form method="GET" action="{{ route('jewelry.certificates.verify_page') }}" class="verify-form-row">
                <input type="text" name="serial" value="{{ request('serial') }}" placeholder="مثلاً: DRK-12345678" maxlength="50" required autofocus>
                <button type="submit"><i class="la la-check-circle"></i> استعلام</button>
            </form>
            <p style="margin:14px 0 0; font-size:12px; color:var(--d-gray-500); text-align:center;">
                <i class="la la-info-circle"></i> شماره سریال روی بدنه شناسنامه یا حک شده روی قطعه قابل مشاهده است.
            </p>
        </div>
    </div>
</div>

<section class="verify-section">
    <div class="container">

        @if(session('success'))
            <div class="alert-lux success" style="margin-bottom:20px;">
                <i class="la la-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="alert-lux danger" style="margin-bottom:20px;">
                <i class="la la-exclamation-triangle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        @if($message)
            <div class="alert-lux" style="padding:16px 20px; border-radius:var(--r-md); margin-bottom:20px; display:flex; align-items:center; gap:12px; background:rgba(229,62,62,.1); border:1px solid rgba(229,62,62,.3); color:#fca5a5;">
                <i class="la la-exclamation-circle" style="font-size:22px;"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        @if($certificate)
            <div class="cert-result">
                @if($missingReport)
                    <div class="alert-lux danger" style="padding:16px 20px; border-radius:var(--r-md); margin-bottom:20px; display:flex; align-items:center; gap:12px; background:rgba(229,62,62,.1); border:1px solid rgba(229,62,62,.3); color:#fca5a5;">
                        <i class="la la-exclamation-triangle" style="font-size:26px; color:#ef4444;"></i>
                        <div>
                            <strong style="display:block; font-size:15px; margin-bottom:4px;">
                                ⚠ هشدار! این قطعه در سامانه به عنوان «{{ $missingReport->type == 'theft' ? 'مسروقه' : 'مفقودی' }}» ثبت شده است.
                            </strong>
                            <span style="font-size:13px; opacity:.85;">در صورت مشاهده این قطعه، لطفاً مراتب را در اسرع وقت به پلیس و پشتیبانی دریکانا اطلاع دهید.</span>
                        </div>
                    </div>
                @endif

                <div class="cert-paper">
                    <div class="cert-header">
                        <span class="cert-badge">Certificate of Authenticity</span>
                        <h3>گواهی اصالت کالا</h3>
                        <p>Drikana Jewelry Authority · {{ $certificate->issued_at ? $certificate->issued_at->format('Y/m/d') : '---' }}</p>
                    </div>

                    <div class="cert-meta-grid">
                        <div class="cert-meta-item">
                            <div class="label">شماره سریال</div>
                            <div class="value" style="direction:ltr; letter-spacing:2px;">{{ $certificate->serial_number }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">نام محصول</div>
                            <div class="value">{{ $certificate->product_name }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">نوع فلز</div>
                            <div class="value">{{ $certificate->metal_type }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">عیار</div>
                            <div class="value">{{ $certificate->purity ? $certificate->purity . ' عیار' : '—' }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">وزن</div>
                            <div class="value">{{ $certificate->weight ? $certificate->weight . ' گرم' : '—' }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">سنگ‌های قیمتی</div>
                            <div class="value">{{ $certificate->stone_type ?: '—' }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">مالک فعلی</div>
                            <div class="value">{{ optional($certificate->owner)->full_name ?: '—' }}</div>
                        </div>
                        <div class="cert-meta-item">
                            <div class="label">تاریخ صدور</div>
                            <div class="value">{{ $certificate->issued_at ? $certificate->issued_at->format('Y/m/d') : '—' }}</div>
                        </div>
                    </div>

                    @if($certificate->description)
                        <div style="padding:14px; background:rgba(184,137,42,.08); border-radius:8px; margin-bottom:20px; font-size:13px; color:#5c4a1a;">
                            <strong>توضیحات:</strong> {{ $certificate->description }}
                        </div>
                    @endif

                    <div class="text-center">
                        @if(!$missingReport)
                            <span class="cert-status-badge verified"><i class="la la-check-circle"></i> این شناسنامه معتبر است</span>
                        @else
                            <span class="cert-status-badge rejected"><i class="la la-exclamation-triangle"></i> این قطعه در لیست مفقودی/سرقت قرار دارد</span>
                        @endif
                    </div>

                    <div class="cert-qr-box"><i class="la la-qrcode" style="color:var(--d-gold-dark);"></i></div>
                    <div class="cert-seal-box">DRIKANA<br>CERTIFIED</div>
                </div>

                @if($certificate->transfers && $certificate->transfers->count() > 0)
                    <div class="d-card-lux" style="margin-top:30px;">
                        <div class="d-card-lux-title"><i class="la la-exchange"></i> تاریخچه انتقال مالکیت</div>
                        <div style="display:flex; flex-direction:column; gap:14px;">
                            @foreach($certificate->transfers->sortByDesc('transfer_date') as $t)
                                <div style="display:flex; gap:16px; align-items:flex-start; padding:14px; background:rgba(255,255,255,.03); border-radius:var(--r-sm); border-right:3px solid var(--d-gold);">
                                    <div style="width:40px; height:40px; border-radius:50%; background:rgba(212,175,55,.15); display:flex; align-items:center; justify-content:center; color:var(--d-gold); flex-shrink:0;"><i class="la la-arrows-h"></i></div>
                                    <div style="flex:1;">
                                        <div style="font-weight:700; margin-bottom:4px; color:var(--d-white);">
                                            از <span style="color:var(--d-gold-light);">{{ optional($t->fromOwner)->full_name ?: '—' }}</span>
                                            <i class="la la-long-arrow-left" style="margin:0 10px; color:var(--d-gold);"></i>
                                            به <span style="color:var(--d-gold-light);">{{ optional($t->toOwner)->full_name ?: '—' }}</span>
                                        </div>
                                        <div style="font-size:13px; color:var(--d-gray-500);">
                                            <i class="la la-calendar"></i> {{ $t->transfer_date ? $t->transfer_date->format('Y/m/d') : '—' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @elseif(!request('serial'))
            <div class="features-grid">
                <div class="feature-box">
                    <div class="fb-icon"><i class="la la-shield"></i></div>
                    <h5>گارانتی اصالت</h5>
                    <p>تمام قطعات دارای شناسنامه معتبر با اطلاعات کامل فنی و مالکیت هستند.</p>
                </div>
                <div class="feature-box">
                    <div class="fb-icon"><i class="la la-history"></i></div>
                    <h5>تاریخچه کامل</h5>
                    <p>سوابق انتقال مالکیت، تعمیرات و کارشناسی در پرونده هر قطعه ثبت می‌شود.</p>
                </div>
                <div class="feature-box">
                    <div class="fb-icon"><i class="la la-bullhorn"></i></div>
                    <h5>اعلام سرقت</h5>
                    <p>در صورت سرقت یا مفقودی، قطعه بلافاصله در سامانه به‌عنوان ممنوع‌المعامله ثبت می‌شود.</p>
                </div>
                <div class="feature-box">
                    <div class="fb-icon"><i class="la la-globe"></i></div>
                    <h5>استعلام آنلاین</h5>
                    <p>استعلام ۲۴ ساعته از هر نقطه جهان، بدون نیاز به مراجعه حضوری.</p>
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
