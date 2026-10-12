<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>{{ config('app.name', 'Laravel') }} | ثبت‌نام در پنل مدیریت</title>
    
    {{-- استایل به فایل مستقل منتقل شد: css/drikana/auth-register.css --}}
    <link rel="stylesheet" href="{{ asset('css/drikana/auth-register.css') }}">
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
            <div class="register-wrapper">
                <!-- ===== سایدبار ===== -->
                <div class="register-sidebar">
                    <div class="sidebar-content">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 class="u-color-var-gold-primary-font-size-2rem">دریکانا</h2>
                            @endif
                        </div>
                        
                        <h1>به خانواده دریکانا بپیوندید</h1>
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
                <div class="register-main">
                    <div class="register-header">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 class="u-color-var-gold-primary">دریکانا</h2>
                            @endif
                        </div>
                        <h2>ایجاد حساب کاربری جدید</h2>
                        <div class="gold-divider"></div>
                        <p>برای دسترسی به پنل مدیریت ثبت‌نام کنید</p>
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
                    
                    <form method="POST" action="{{ route('register') }}" class="register-form" id="registerForm">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">
                                    <i>👤</i> نام کامل
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">👤</span>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="نام کامل خود را وارد کنید"
                                           required 
                                           autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="email">
                                    <i>✉</i> ایمیل
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">✉</span>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="ایمیل خود را وارد کنید"
                                           required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-row">
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
                                    <span class="password-toggle" onclick="togglePassword('password', this)">👁</span>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="password-confirm">
                                    <i>✅</i> تکرار رمز عبور
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">✅</span>
                                    <input type="password" 
                                           id="password-confirm" 
                                           name="password_confirmation" 
                                           placeholder="رمز عبور را مجدد وارد کنید"
                                           required>
                                    <span class="password-toggle" onclick="togglePassword('password-confirm', this)">👁</span>
                                </div>
                            </div>
                        </div>
                        
                        <label class="terms-check">
                            <input type="checkbox" name="terms" required>
                            <span>با <a href="#" target="_blank">قوانین و مقررات</a> موافقم</span>
                        </label>
                        
                        <button type="submit" class="btn-register" id="submitBtn">
                            ✦ ثبت‌نام در پنل مدیریت
                        </button>
                        
                        <div class="social-register">
                            <p>ثبت‌نام با حساب‌های دیگر</p>
                            <div class="social-icons">
                                <a href="#" class="social-icon">G</a>
                                <a href="#" class="social-icon">GH</a>
                                <a href="#" class="social-icon">T</a>
                                <a href="#" class="social-icon">IG</a>
                            </div>
                        </div>
                        
                        <div class="footer-text">
                            <p>حساب کاربری دارید؟ <a href="{{ route('login') }}">وارد شوید</a></p>
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
        function togglePassword(inputId, toggleBtn) {
            const passwordInput = document.getElementById(inputId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.innerHTML = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.innerHTML = '👁';
            }
        }
        
        // =============================================
        // حالت بارگذاری هنگام ارسال فرم
        // =============================================
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '⏳ در حال ثبت‌نام...';
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
        
        // =============================================
        // نمایش خطاهای اعتبارسنجی سمت کلاینت
        // =============================================
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(e) {
            const termsCheck = document.querySelector('input[name="terms"]');
            if (!termsCheck.checked) {
                e.preventDefault();
                alert('لطفاً با قوانین و مقررات موافقت کنید.');
                termsCheck.focus();
            }
        });
    </script>
    
    @yield('script')
</body>
</html>