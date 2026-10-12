<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>{{ config('app.name', 'Laravel') }} | ورود به پنل مدیریت</title>
    
    {{-- استایل به فایل مستقل منتقل شد: css/drikana/auth.css --}}
    <link rel="stylesheet" href="{{ asset('css/drikana/auth.css') }}">
    {{-- کلاس‌های استخراج‌شده از استایل درون‌خطی ویوها (بازبینی L-04) --}}
    <link rel="stylesheet" href="{{ asset('css/drikana/utilities.css') }}">
</head>
<body>
    @php
        $generalsetting = \App\GeneralSetting::first();
        $languages = \App\Language::all();
        $currentLocale = Session::get('locale', Config::get('app.locale'));
    @endphp
    
    <div id="container">
        <div class="cls-content">
            <div class="login-wrapper">
                <!-- ===== سایدبار ===== -->
                <div class="login-sidebar">
                    <div class="sidebar-content">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 class="u-color-var-gold-primary-font-size-2rem">دریکانا</h2>
                            @endif
                        </div>
                        
                        <h1>به پنل مدیریت خوش آمدید</h1>
                        <p>سیستم جامع مدیریت و فروش طلا و جواهر با معماری امن و پیشرفته</p>
                        
                        <div class="sidebar-stats">
                            <div class="stat-item">
                                <span class="stat-number">۱۰۰۰+</span>
                                <span class="stat-label">قطعه ثبت‌شده</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">۵۰۰+</span>
                                <span class="stat-label">کاربر فعال</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">۲۴/۷</span>
                                <span class="stat-label">پشتیبانی</span>
                            </div>
                        </div>
                        
                        @if(count($languages) > 1)
                        <div class="language-selector">
                            <form id="languageForm" method="POST" action="{{ route('language.change') }}">
                                @csrf
                                <select name="lang" id="languageSelect" onchange="document.getElementById('languageForm').submit()">
                                    @foreach($languages as $language)
                                        <option value="{{ $language->code }}" {{ $currentLocale == $language->code ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <div class="sidebar-footer">
                        <p>© {{ date('Y') }} {{ config('app.name') }}. تمامی حقوق محفوظ است.</p>
                    </div>
                </div>
                
                <!-- ===== بخش اصلی فرم ===== -->
                <div class="login-main">
                    <div class="login-header">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 class="u-color-var-gold-primary">دریکانا</h2>
                            @endif
                        </div>
                        <h2>ورود به پنل مدیریت</h2>
                        <div class="gold-divider"></div>
                        <p>برای دسترسی به داشبورد مدیریت وارد شوید</p>
                    </div>
                    
                    @if(session('status'))
                        <div class="alert alert-success animate__fadeIn">
                            <i>✓</i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger animate__fadeIn">
                            <i>✕</i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="username">
                                <i>👤</i> نام کاربری
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">👤</span>
                                <input type="text" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       placeholder="نام کاربری خود را وارد کنید"
                                       required 
                                       autofocus>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">
                                <i>🔒</i> رمز عبور
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">🔒</span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="رمز عبور خود را وارد کنید"
                                       required>
                                <span class="password-toggle" onclick="togglePassword()">👁</span>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>مرا به خاطر بسپار</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-password">
                                    🔑 رمز عبور را فراموش کرده‌اید؟
                                </a>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn-login" id="submitBtn">
                            ✦ ورود به پنل مدیریت
                        </button>
                        
                        <div class="social-login">
                            <p>ورود با حساب‌های دیگر</p>
                            <div class="social-icons">
                                <a href="#" class="social-icon">G</a>
                                <a href="#" class="social-icon">GH</a>
                                <a href="#" class="social-icon">T</a>
                                <a href="#" class="social-icon">IG</a>
                            </div>
                        </div>
                        
                        <div class="footer-text">
                            <p>حساب کاربری ندارید؟ <a href="{{ route('register') }}">ثبت‌نام کنید</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // =============================================
        // نمایش/مخفی کردن رمز عبور
        // =============================================
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = '👁';
            }
        }
        
        // =============================================
        // حالت بارگذاری هنگام ارسال فرم
        // =============================================
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '⏳ در حال ورود...';
            });
        }
        
        // =============================================
        // بستن خودکار پیام‌ها بعد از ۵ ثانیه
        // =============================================
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
        
        // =============================================
        // افکت فوکوس روی ورودی‌ها
        // =============================================
        const inputs = document.querySelectorAll('.input-wrapper input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.borderColor = 'var(--gold-primary)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.borderColor = '';
            });
        });
    </script>
    
    @yield('script')
</body>
</html>