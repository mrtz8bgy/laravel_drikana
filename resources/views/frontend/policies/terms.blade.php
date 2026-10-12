@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="p-4 bg-white">
                        @php
                            // نام ردیف در جدول policies مقدار «terms_conditions» است
                            // (نه «terms»). پیش‌تر این نام اشتباه بود و صفحه ۵۰۰ می‌داد.
                            // در نبودِ ردیف هم به‌جای خطا، متن خالی برگردانده می‌شود.
                            $termsPolicy = \App\Policy::where('name', 'terms_conditions')->first();
                        @endphp

                        {!! $termsPolicy ? $termsPolicy->content : '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
