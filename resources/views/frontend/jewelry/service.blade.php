@extends('frontend.layouts.app')

@section('meta_title', 'سرویس شناسنامه طلا و جواهر | دریکانا')

@section('content')
<section class="d-page-hero">
    <div class="container">
        <span class="kicker">CERTIFICATE SERVICE</span>
        <h1>سرویس <span class="gold">شناسنامه طلا</span> و جواهرات</h1>
        <p>اطلاعات کامل از خدمات صدور، تمدید و انتقال شناسنامه رسمی دریکانا.</p>
    </div>
</section>

<section style="padding:60px 0 80px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-card-lux">
                    <div class="d-card-lux-title"><i class="la la-certificate"></i> به‌زودی...</div>
                    <p style="color:var(--d-gray-300); line-height:2.2;">
                        صفحه سرویس شناسنامه در حال تکمیل است. از طریق منوی اصلی می‌توانید از امکانات <a href="{{ route('jewelry.certificates.verify_page') }}">استعلام شناسنامه</a> و <a href="{{ route('jewelry.missing_reports.create_public') }}">اعلام سرقت</a> استفاده کنید.
                    </p>
                    <a href="{{ route('home') }}" class="btn-lux btn-lux-primary mt-3"><i class="la la-home"></i> بازگشت به خانه</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
