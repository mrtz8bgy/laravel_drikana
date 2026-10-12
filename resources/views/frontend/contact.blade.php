@extends('frontend.layouts.app')

@section('meta_title', 'تماس با ما | دریکانا')

@section('content')
    <section class="gry-bg py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-2">تماس با دریکانا</h1>
                            <p class="text-muted mb-0">برای راهنمایی درباره خرید، فروش و خدمات دریکانا با ما در ارتباط باشید.</p>
                        </div>

                        <div class="row">
                            @if($contact && $contact->phone)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <h2 class="h6">تلفن</h2>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}" dir="ltr">
                                            {{ $contact->phone }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($contact && $contact->email)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <h2 class="h6">پست الکترونیکی</h2>
                                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                    </div>
                                </div>
                            @endif

                            @if($contact && $contact->address)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <h2 class="h6">نشانی</h2>
                                        <a href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($contact->address) }}" target="_blank" rel="noopener noreferrer">
                                            {{ $contact->address }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(!$contact || (!$contact->phone && !$contact->email && !$contact->address))
                            <div class="alert alert-info mb-0" role="status">
                                اطلاعات تماس در حال حاضر ثبت نشده است. لطفاً از بخش
                                <a href="{{ route('supportpolicy') }}">پشتیبانی</a>
                                راهنمایی بگیرید.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
