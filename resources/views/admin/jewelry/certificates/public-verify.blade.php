@extends('frontend.layouts.app')

@section('meta_title', 'استعلام شناسنامه ' . $certificate->serial_number)

@section('content')
<section class="d-page-hero">
    <div class="container">
        <span class="kicker"><i class="la la-certificate"></i> نتیجه استعلام</span>
        <h1>گواهی <span class="gold">اصالت کالا</span></h1>
        <p>نتیجه استعلام سریال <strong style="color:var(--d-gold);" dir="ltr">{{ $certificate->serial_number }}</strong> از سامانه شناسنامه دریکانا.</p>
    </div>
</section>

<section style="padding:50px 0 80px;">
    <div class="container">
        <div class="row" style="justify-content:center;">
            <div class="col-lg-8">
                @if($certificate->is_verified && (!property_exists($certificate,'rejected_at') || !$certificate->rejected_at))
                    <div class="alert-lux success" style="margin-bottom:24px;">
                        <i class="la la-check-circle"></i>
                        <div>
                            <strong style="display:block; margin-bottom:4px;">✅ این شناسنامه معتبر است.</strong>
                            <span style="font-size:13px; opacity:.85;">لطقاً پیش از معامله، مشخصات قطعه را با اطلاعات درج شده تطبیق دهید.</span>
                        </div>
                    </div>
                @elseif(property_exists($certificate,'rejected_at') && $certificate->rejected_at)
                    <div class="alert-lux danger" style="margin-bottom:24px;">
                        <i class="la la-times-circle"></i>
                        <div>
                            <strong style="display:block; margin-bottom:4px;">❌ این شناسنامه باطل شده است.</strong>
                            <span style="font-size:13px; opacity:.85;">لطفاً از معامله این قطعه خودداری کنید.</span>
                        </div>
                    </div>
                @else
                    <div class="alert-lux warn" style="margin-bottom:24px;">
                        <i class="la la-clock-o"></i>
                        <div>
                            <strong style="display:block; margin-bottom:4px;">⏳ این شناسنامه در انتظار تأیید نهایی است.</strong>
                            <span style="font-size:13px; opacity:.85;">تأیید اصالت نهایی توسط کارشناسان دریکانا در حال انجام است.</span>
                        </div>
                    </div>
                @endif

                <div class="d-cert-display">
                    <div class="d-cert-head">
                        <div class="cert-sub">Drikana · Certificate of Authenticity</div>
                        <h3>گواهی اصالت قطعه</h3>
                    </div>
                    <div class="d-cert-info-grid">
                        <div class="d-cert-info-item">
                            <div class="label">شماره سریال</div>
                            <div class="value" dir="ltr">{{ $certificate->serial_number }}</div>
                        </div>
                        <div class="d-cert-info-item">
                            <div class="label">نام محصول</div>
                            <div class="value">{{ $certificate->product_name }}</div>
                        </div>
                        <div class="d-cert-info-item">
                            <div class="label">نوع فلز</div>
                            <div class="value">{{ $certificate->metal_type }}</div>
                        </div>
                        <div class="d-cert-info-item">
                            <div class="label">مالک فعلی</div>
                            <div class="value">{{ optional($certificate->currentOwner())->full_name ?? '—' }}</div>
                        </div>
                        <div class="d-cert-info-item">
                            <div class="label">تاریخ صدور</div>
                            <div class="value">{{ $certificate->issued_at ? $certificate->issued_at->format('Y/m/d') : '—' }}</div>
                        </div>
                        <div class="d-cert-info-item">
                            <div class="label">وضعیت</div>
                            <div class="value">
                                @if($certificate->is_verified)
                                    <span style="color:#15803d; font-weight:800;">✓ معتبر</span>
                                @elseif($certificate->rejected_at ?? null)
                                    <span style="color:#b91c1c; font-weight:800;">✗ باطل شده</span>
                                @else
                                    <span style="color:#a16207; font-weight:800;">⏳ در انتظار تأیید</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div style="text-align:center; margin-top:30px;">
                    <a href="{{ route('jewelry.certificates.verify_page') }}" class="btn-lux btn-lux-outline">
                        <i class="la la-angle-right"></i> استعلام مجدد
                    </a>
                    <a href="{{ route('home') }}" class="btn-lux btn-lux-primary" style="margin-right:10px;">
                        <i class="la la-home"></i> بازگشت به سایت
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
