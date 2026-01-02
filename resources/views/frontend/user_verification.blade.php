@extends('frontend.layouts.app')

@section('content')
<section class="gry-bg py-4">
    <div class="profile">
        <div class="container">
            <div class="row">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                    <div class="card">
                        <div class="text-center px-35 pt-5">
                            <h1 class="heading heading-4 strong-500">تأیید شماره همراه</h1>
                        </div>
                        
                        <div class="px-5 py-3 py-lg-4">
                            {{-- نمایش کد در محیط توسعه --}}
                            @if($showDebugCode ?? false)
                            <div class="alert alert-info text-center mb-4">
                                <strong>کد تأیید (فقط برای توسعه):</strong>
                                <h3 class="my-2">{{ $user->verification_code ?? env('DEBUG_OTP_CODE', '123456') }}</h3>
                                <small class="text-muted">
                                    این کد فقط در محیط توسعه نمایش داده می‌شود.<br>
                                    برای تغییر آن فایل .env را ویرایش کنید.
                                </small>
                            </div>
                            @endif
                            
                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <p class="mb-4 text-center">
                                @if(auth()->check() && auth()->user()->phone)
                                    کد تأیید به شماره <strong>{{ auth()->user()->phone }}</strong> ارسال شد.
                                @endif
                                لطفاً کد ۶ رقمی را وارد کنید.
                            </p>
                            
                            <form class="form-default" role="form" action="{{ route('verification.verify') }}" method="POST">
                                @csrf
                                
                                <div class="form-group">
                                    <div class="input-group input-group--style-1">
                                        <span class="input-group-addon">
                                            <i class="text-md la la-lock"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control{{ $errors->has('verification_code') ? ' is-invalid' : '' }}" 
                                               placeholder="{{ __('کد تأیید ۶ رقمی') }}" 
                                               name="verification_code" 
                                               maxlength="6" 
                                               required 
                                               autofocus
                                               value="{{ old('verification_code') }}"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        
                                        @if ($errors->has('verification_code'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('verification_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">
                                        {{ __('تأیید و ادامه') }}
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center mt-4">
                                <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link">
                                        {{ __('ارسال مجدد کد') }}
                                    </button>
                                </form>
                                
                                <a href="{{ route('logout') }}" class="btn btn-link text-danger" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('انصراف و خروج') }}
                                </a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Auto focus on OTP input
        $('input[name="verification_code"]').focus();
        
        // Auto submit after 6 digits
        $('input[name="verification_code"]').on('input', function() {
            if ($(this).val().length === 6) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection