@extends('frontend.layouts.app')

@section('meta_title', 'رهگیری سفارش و قطعات طلا | دریکانا')
@section('meta_description', 'با وارد کردن کد سفارش یا شماره سریال، وضعیت سفارش یا قطعه خود را در سامانه دریکانا رهگیری کنید.')

@section('content')
<section class="d-page-hero">
    <div class="container">
        <span class="kicker"><i class="la la-map-pin"></i> سامانه رهگیری</span>
        <h1>رهگیری <span class="gold">سفارش و قطعات</span></h1>
        <p>با وارد کردن شماره سریال شناسنامه یا کد سفارش، می‌توانید آخرین وضعیت قطعه یا مرسوله خود را به صورت زنده مشاهده کنید.</p>
    </div>
</section>

<section style="padding: 60px 0 80px;">
    <div class="container">
        <div class="row" style="justify-content:center;">
            <div class="col-lg-8">
                <div class="d-card-lux">
                    <div class="d-card-lux-title"><i class="la la-search la-flip-horizontal"></i> رهگیری سریع</div>
                    <form method="GET" action="{{ route('jewelry.track') }}" style="display:flex; gap:12px; flex-wrap:wrap;">
                        <input type="text" name="serial" value="{{ $serial ?? old('serial') }}" class="form-control-lux" placeholder="شماره سریال شناسنامه را وارد کنید..." required style="flex:1; min-width:200px; letter-spacing:2px;">
                        <button type="submit" class="btn-lux btn-lux-primary"><i class="la la-map-marker"></i> رهگیری</button>
                    </form>
                    <p style="margin-top:14px; font-size:12px; color:var(--d-gray-500);">
                        <i class="la la-info-circle"></i> همچنین می‌توانید از صفحات <a href="{{ route('orders.track') }}">رهگیری سفارش</a> یا <a href="{{ route('jewelry.certificates.verify_page') }}">استعلام شناسنامه</a> استفاده کنید.
                    </p>
                </div>

                @if($message)
                    <div class="alert-lux danger" style="margin-top:24px;">
                        <i class="la la-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @endif

                @if($certificate)
                    <div class="d-card-lux" style="margin-top:24px;">
                        <div class="d-card-lux-title"><i class="la la-gem"></i> اطلاعات قطعه</div>

                        @if($missingReport)
                            <div class="alert-lux danger" style="margin-bottom:20px;">
                                <i class="la la-exclamation-triangle"></i>
                                <div>
                                    <strong style="display:block; margin-bottom:4px;">⚠ این قطعه در سامانه به عنوان «{{ $missingReport->type == 'theft' ? 'مسروقه' : 'مفقودی' }}» ثبت شده است.</strong>
                                    <span style="font-size:13px; opacity:.85;">تاریخ ثبت: {{ $missingReport->created_at->format('Y/m/d') }} · وضعیت: در حال پیگیری</span>
                                </div>
                            </div>
                        @endif

                        <div class="d-cert-info-grid">
                            <div class="d-cert-info-item">
                                <div class="label">شماره سریال</div>
                                <div class="value" style="direction:ltr; letter-spacing:2px;">{{ $certificate->serial_number }}</div>
                            </div>
                            <div class="d-cert-info-item">
                                <div class="label">نام قطعه</div>
                                <div class="value">{{ $certificate->product_name }}</div>
                            </div>
                            <div class="d-cert-info-item">
                                <div class="label">نوع فلز / وزن</div>
                                <div class="value">{{ $certificate->metal_type }} {{ $certificate->weight ? '· ' . $certificate->weight . ' گرم' : '' }}</div>
                            </div>
                            <div class="d-cert-info-item">
                                <div class="label">عیار / سنگ</div>
                                <div class="value">{{ $certificate->purity ? $certificate->purity . ' عیار' : '—' }} {{ $certificate->stone_type ? '· ' . $certificate->stone_type : '' }}</div>
                            </div>
                            <div class="d-cert-info-item">
                                <div class="label">مالک فعلی</div>
                                <div class="value">{{ optional($certificate->owner)->full_name ?: '—' }}</div>
                            </div>
                            <div class="d-cert-info-item">
                                <div class="label">وضعیت</div>
                                <div class="value">
                                    @if($missingReport)
                                        <span class="status-badge status-stolen">در لیست پیگیری</span>
                                    @else
                                        <span class="status-badge status-verified">معتبر و فعال</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($certificate->transfers && $certificate->transfers->count() > 0)
                            <div style="margin-top:30px;">
                                <h5 style="font-size:15px; font-weight:700; color:var(--d-gold); margin-bottom:16px;">
                                    <i class="la la-history"></i> تاریخچه انتقال مالکیت
                                </h5>
                                @php
                                    $transfers = $certificate->transfers->sortBy('transfer_date')->values();
                                    $total = $transfers->count();
                                @endphp
                                <div class="d-steps">
                                    <div class="d-step done">
                                        <div class="d-step-num"><i class="la la-certificate"></i></div>
                                        <div class="d-step-title">صدور شناسنامه</div>
                                    </div>
                                    @foreach($transfers as $i => $t)
                                        <div class="d-step done">
                                            <div class="d-step-num">{{ $i + 1 }}</div>
                                            <div class="d-step-title">{{ optional($t->toOwner)->full_name ?: 'انتقال' }}</div>
                                        </div>
                                    @endforeach
                                    <div class="d-step active">
                                        <div class="d-step-num"><i class="la la-map-marker"></i></div>
                                        <div class="d-step-title">وضعیت فعلی</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div style="margin-top:24px; display:flex; gap:10px; flex-wrap:wrap;">
                            <a href="{{ route('jewelry.certificates.verify_page') }}?serial={{ $certificate->serial_number }}" class="btn-lux btn-lux-outline" style="padding:10px 20px; font-size:13px;">
                                <i class="la la-file-text-o"></i> مشاهده شناسنامه کامل
                            </a>
                            @auth
                                <a href="{{ route('jewelry.missing_reports.create_public') }}" class="btn-lux btn-lux-outline" style="padding:10px 20px; font-size:13px; border-color:#ef4444; color:#fca5a5;">
                                    <i class="la la-warning"></i> اعلام مفقودی/سرقت
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
