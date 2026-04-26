<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>{{ config('app.name', 'Laravel') }} | ورود به پنل مدیریت</title>
    
    {{-- =============================================== --}}
    {{-- تمام لینک‌های خارجی حذف یا کامنت شدند --}}
    {{-- فونت‌ها، آیکون‌ها و انیمیشن‌ها به صورت داخلی جایگزین شدند --}}
    {{-- =============================================== --}}
    
    <style>
        /* فونت سیستمی جایگزین Inter */
        @import url('data:image/svg+xml;charset=utf-8,/* خالی */');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "IranSans", Tahoma, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* آیکون‌های Font Awesome به صورت داخلی با SVG جایگزین شدند */
        .fas, .far, .fab {
            font-family: inherit;
            font-style: normal;
            display: inline-block;
            width: 1em;
            text-align: center;
        }
        
        /* انیمیشن‌های جایگزین animate.css */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate__animated {
            animation-duration: 0.8s;
            animation-fill-mode: both;
        }
        
        .animate__fadeIn {
            animation-name: fadeIn;
        }
        
        .animate__fadeInUp {
            animation-name: fadeInUp;
        }
        
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --primary-light: #4895ef;
            --secondary-color: #4cc9f0;
            --success-color: #4ade80;
            --warning-color: #fbbf24;
            --danger-color: #f87171;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 30px 50px rgba(67, 97, 238, 0.15);
            --transition: all 0.3s ease;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        #container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            animation: fadeIn 1s ease;
        }
        
        #container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9) 0%, rgba(76, 201, 240, 0.9) 100%);
            backdrop-filter: blur(10px);
        }
        
        .cls-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
        }
        
        .login-wrapper {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-sidebar {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .login-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .sidebar-content {
            position: relative;
            z-index: 1;
            color: white;
        }
        
        .sidebar-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .sidebar-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .sidebar-stats {
            display: flex;
            gap: 30px;
            margin-top: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .login-main {
            flex: 1;
            padding: 60px 50px;
            background: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header .logo {
            margin-bottom: 30px;
        }
        
        .login-header .logo img {
            max-width: 180px;
            height: auto;
        }
        
        .login-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: var(--gray-500);
            font-size: 1rem;
        }
        
        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.95rem;
        }
        
        .form-group label i {
            margin-right: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1.1rem;
            transition: var(--transition);
            z-index: 1;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--gray-100);
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
        }
        
        .input-wrapper input:focus + .input-icon {
            color: var(--primary-color);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-400);
            transition: var(--transition);
            z-index: 1;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }
        
        .remember-me span {
            color: var(--gray-600);
            font-size: 0.95rem;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--box-shadow-hover);
        }
        
        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-login i {
            margin-right: 8px;
            transition: var(--transition);
        }
        
        .btn-login:hover i {
            transform: translateX(5px);
        }
        
        .social-login {
            margin-top: 30px;
            text-align: center;
        }
        
        .social-login p {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-bottom: 15px;
            position: relative;
        }
        
        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: var(--gray-200);
        }
        
        .social-login p::before {
            left: 0;
        }
        
        .social-login p::after {
            right: 0;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
            border: 2px solid transparent;
            font-size: 1.2rem;
        }
        
        .social-icon:hover {
            background: white;
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert-success {
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }
        
        .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }
        
        .alert-warning {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid var(--warning-color);
            color: var(--warning-color);
        }
        
        .alert i {
            font-size: 1.2rem;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: var(--gray-500);
            font-size: 0.9rem;
        }
        
        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .footer-text a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .language-selector {
            margin-top: 30px;
        }
        
        .language-selector select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: left 15px center;
            padding-left: 45px;
        }
        
        .language-selector select option {
            background: white;
            color: var(--gray-800);
            padding: 10px;
        }
        
        .language-selector select:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .language-selector select:focus {
            outline: none;
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        [dir="rtl"] .input-wrapper .input-icon {
            left: auto;
            right: 15px;
        }
        
        [dir="rtl"] .input-wrapper input {
            padding: 15px 45px 15px 15px;
        }
        
        [dir="rtl"] .password-toggle {
            right: auto;
            left: 15px;
        }
        
        [dir="rtl"] .btn-login i {
            margin-right: 0;
            margin-left: 8px;
        }
        
        [dir="rtl"] .btn-login:hover i {
            transform: translateX(-5px);
        }
        
        [dir="rtl"] .social-login p::before {
            left: auto;
            right: 0;
        }
        
        [dir="rtl"] .social-login p::after {
            right: auto;
            left: 0;
        }
        
        [dir="rtl"] .language-selector select {
            background-position: right 15px center;
            padding-right: 45px;
            padding-left: 15px;
        }
        
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .login-sidebar {
                padding: 40px;
            }
            
            .login-main {
                padding: 40px;
            }
        }
        
        @media (max-width: 576px) {
            .login-main {
                padding: 30px 20px;
            }
            
            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .sidebar-stats {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
        
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    @php
        $generalsetting = \App\GeneralSetting::first();
        $languages = \App\Language::all();
        $currentLocale = Session::get('locale', Config::get('app.locale'));
    @endphp
    
    <div id="container"
        @if ($generalsetting && $generalsetting->admin_login_background != null)
            style="background-image:url('{{ asset($generalsetting->admin_login_background) }}');"
        @else
            style="background-image:url('{{ asset('img/bg-img/login-bg.jpg') }}');"
        @endif>
        
        <div class="cls-content">
            <div class="login-wrapper animate__animated animate__fadeIn">
                <!-- Sidebar -->
                <div class="login-sidebar">
                    <div class="sidebar-content">
                        <div class="logo mb-4">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}" style="max-width: 150px; filter: brightness(0) invert(1);">
                            @else
                                <h2 style="color: white; font-size: 2rem;">{{ config('app.name') }}</h2>
                            @endif
                        </div>
                        
                        <h1>به پنل مدیریت خوش آمدید</h1>
                        <p>سیستم مدیریت محتوای حرفه‌ای با امکانات پیشرفته و رابط کاربری زیبا</p>
                        
                        <div class="sidebar-stats">
                            <div class="stat-item">
                                <span class="stat-number">15k+</span>
                                <span class="stat-label">کاربر فعال</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">50+</span>
                                <span class="stat-label">ماژول</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24/7</span>
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
                    
                    <div class="sidebar-footer text-white">
                        <p style="font-size: 0.9rem; opacity: 0.7;">© {{ date('Y') }} {{ config('app.name') }}. تمامی حقوق محفوظ است.</p>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="login-main">
                    <div class="login-header">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 style="color: var(--primary-color);">{{ config('app.name') }}</h2>
                            @endif
                        </div>
                        <h2>ورود به پنل مدیریت</h2>
                        <p>برای دسترسی به داشبورد مدیریت وارد شوید</p>
                    </div>
                    
                    @if(session('status'))
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            <span>✓</span>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <span>⚠</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="username">
                                👤 نام کاربری
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
                                🔒 رمز عبور
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">🔒</span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="رمز عبور خود را وارد کنید"
                                       required>
                                <span class="password-toggle" onclick="togglePassword()">
                                    👁
                                </span>
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
                            🔓 ورود به پنل مدیریت
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
        // Password visibility toggle
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
        
        // Form loading state
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '⏳ در حال ورود...';
            });
        }
        
        // Auto-hide alerts after 5 seconds
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
        
        // Input focus effects
        const inputs = document.querySelectorAll('.input-wrapper input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
    
    @yield('script')
</body>
</html>