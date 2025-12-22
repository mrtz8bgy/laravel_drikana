@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-5">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h1 class="heading heading-4 strong-500">
                                    {{__('Login to your account.')}}
                                </h1>
                            </div>
                            <div class="px-5 py-3 py-lg-4">
                                <div class="">
                                    <form class="form-default" role="form" action="{{ route('otp.login') }}" method="POST">
                                        @csrf

                                        @php
                                            $step = session('otp_step');
                                        @endphp

                                        <input type="hidden" name="step" value="{{ $step ?? '1' }}">

                                        <div class="form-group">
                                            <div class="input-group input-group--style-1">
                                                <span class="input-group-addon">
                                                    <i class="text-md la la-user"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phone') }}"
                                                    placeholder="شماره موبایل"
                                                    name="phone"
                                                    id="phone"
                                                    {{ isset($step) && $step == '2' ? 'disabled': '' }}
                                                >
                                            </div>
                                        </div>

                                        @if (isset($step) && $step == '2')
                                            <input type="hidden" name="phone" value="{{ old('phone') }}">
                                            <div class="form-group">
                                                <div class="input-group input-group--style-1">
                                                    <span class="input-group-addon">
                                                        <i class="text-md la la-key"></i>
                                                    </span>

                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                                        value="{{ old('code') }}"
                                                        placeholder="کد تایید"
                                                        name="code"
                                                        id="code"
                                                    >
                                                </div>
                                            </div>
                                        @endif

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Login') }}</button>
                                        </div>
                                    </form>
                                    <div class="or or--1 mt-3 text-center">
                                        <span>یا</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('user.login') }}" class="btn btn-styled btn-block btn-red btn-icon--2 btn-icon-left px-4 mb-3">
                                            <i class="icon fa fa-envelope"></i> ورود با ایمیل
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    {{__('Need an account?')}} <a href="{{ route('user.registration') }}" class="strong-600">{{__('Register Now')}}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript"></script>
@endsection
