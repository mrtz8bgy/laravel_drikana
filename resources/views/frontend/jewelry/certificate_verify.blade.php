@extends('frontend.layouts.app')

@section('meta_title', 'استعلام اصالت شناسنامه طلا و جواهر | دریکانا')
@section('meta_description', 'با وارد کردن شماره سریال شناسنامه، اصالت طلا، جواهر، ساعت و اشیاء قیمتی خود را در سامانه دریکانا استعلام کنید.')

@section('content')
{{-- استایل این صفحه به فایل مستقل زیر منتقل شده است (مرحله ۳ بازسازی)
     تا از CSS درون‌خطی پرهیز شود. --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/ownership.css') }}">
@endpush

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
