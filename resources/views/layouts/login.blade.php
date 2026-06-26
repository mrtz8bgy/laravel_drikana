<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>{{ config('app.name', 'Laravel') }} | ورود به پنل مدیریت</title>
    
    <style>
        /* ================================================
           فونت و استایل‌های پایه – هماهنگ با قالب دریکانا
           ================================================ */
        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --gold-primary: #c8a951;
            --gold-light: #f5d77b;
            --gold-gradient: linear-gradient(135deg, #f5d77b, #c8a951);
            --bg-deep: #02042d;
            --bg-card: rgba(255, 255, 255, 0.04);
            --text-light: #e6dcc8;
            --text-muted: #b0a68e;
            --border-gold: rgba(196, 169, 90, 0.25);
            --shadow-gold: 0 20px 40px -8px rgba(0,0,0,0.6);
            --radius-xl: 28px;
            --radius-lg: 20px;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            background: var(--bg-deep);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            color: var(--text-light);
        }
        
        /* ================================================
           پس‌زمینه پویا با المان‌های طلایی
           ================================================ */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(196, 169, 90, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 50%, rgba(196, 169, 90, 0.05) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 100%, rgba(196, 169, 90, 0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* ================================================
           انیمیشن‌ها
           ================================================ */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-gold {
            0%, 100% { box-shadow: 0 0 20px rgba(196, 169, 90, 0.15); }
            50% { box-shadow: 0 0 40px rgba(196, 169, 90, 0.3); }
        }
        
        .animate__fadeIn { animation: fadeIn 0.8s ease; }
        .animate__slideUp { animation: slideUp 0.8s ease; }
        
        /* ================================================
           کانتینر اصلی
           ================================================ */
        #container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        .cls-content {
            width: 100%;
            max-width: 1200px;
            position: relative;
            z-index: 1;
        }
        
        /* ================================================
           کارت لاگین – شیشه‌ای با حاشیه طلایی
           ================================================ */
        .login-wrapper {
            display: flex;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-gold);
            border: 1px solid var(--border-gold);
            animation: slideUp 0.8s ease;
            position: relative;
        }
        
        .login-wrapper::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: var(--radius-xl);
            padding: 1px;
            background: linear-gradient(135deg, rgba(196, 169, 90, 0.3), transparent 40%, transparent 60%, rgba(196, 169, 90, 0.2));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        
        /* ================================================
           سایدبار – با گرادیانت طلایی
           ================================================ */
        .login-sidebar {
            flex: 1;
            background: linear-gradient(145deg, rgba(196, 169, 90, 0.12), rgba(196, 169, 90, 0.04));
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-left: 1px solid var(--border-gold);
        }
        
        [dir="rtl"] .login-sidebar {
            border-left: none;
            border-right: 1px solid var(--border-gold);
        }
        
        .login-sidebar::before {
            content: '◆';
            position: absolute;
            top: -50px;
            right: -50px;
            font-size: 300px;
            color: rgba(196, 169, 90, 0.04);
            pointer-events: none;
        }
        
        .login-sidebar::after {
            content: '◇';
            position: absolute;
            bottom: -30px;
            left: -30px;
            font-size: 200px;
            color: rgba(196, 169, 90, 0.03);
            pointer-events: none;
        }
        
        .sidebar-content {
            position: relative;
            z-index: 1;
        }
        
        .sidebar-content .logo {
            margin-bottom: 30px;
        }
        
        .sidebar-content .logo img {
            max-width: 160px;
            height: auto;
            filter: brightness(1.1);
        }
        
        .sidebar-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        
        .sidebar-content p {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .sidebar-stats {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-gold);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        /* ================================================
           بخش اصلی فرم
           ================================================ */
        .login-main {
            flex: 1.2;
            padding: 50px 45px;
            background: rgba(255, 255, 255, 0.02);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .login-header .logo img {
            max-width: 140px;
            height: auto;
            margin-bottom: 20px;
        }
        
        .login-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .login-header .gold-divider {
            width: 60px;
            height: 3px;
            background: var(--gold-gradient);
            margin: 12px auto 0;
            border-radius: 10px;
        }
        
        /* ================================================
           فرم
           ================================================ */
        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 22px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-light);
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        
        .form-group label i {
            margin-left: 8px;
            color: var(--gold-primary);
        }
        
        [dir="rtl"] .form-group label i {
            margin-left: 0;
            margin-right: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: var(--transition);
            z-index: 1;
            opacity: 0.6;
        }
        
        [dir="rtl"] .input-wrapper .input-icon {
            right: auto;
            left: 16px;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid var(--border-gold);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-family: inherit;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-light);
        }
        
        [dir="rtl"] .input-wrapper input {
            padding: 14px 48px 14px 16px;
        }
        
        .input-wrapper input::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: var(--gold-primary);
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 0 4px rgba(196, 169, 90, 0.08);
        }
        
        .input-wrapper input:focus ~ .input-icon,
        .input-wrapper input:focus + .input-icon {
            color: var(--gold-light);
            opacity: 1;
        }
        
        .password-toggle {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            transition: var(--transition);
            z-index: 1;
            font-size: 1rem;
            opacity: 0.6;
        }
        
        [dir="rtl"] .password-toggle {
            left: auto;
            right: 16px;
        }
        
        .password-toggle:hover {
            color: var(--gold-light);
            opacity: 1;
        }
        
        /* ================================================
           گزینه‌های فرم
           ================================================ */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .remember-me:hover {
            color: var(--text-light);
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--gold-primary);
            cursor: pointer;
            border-radius: 4px;
        }
        
        .forgot-password {
            color: var(--gold-primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }
        
        /* ================================================
           دکمه ورود – طلایی با انیمیشن
           ================================================ */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--gold-gradient);
            color: var(--bg-deep);
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1.05rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(196, 169, 90, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login i {
            margin-left: 8px;
            transition: var(--transition);
        }
        
        [dir="rtl"] .btn-login i {
            margin-left: 0;
            margin-right: 8px;
        }
        
        .btn-login:hover i {
            transform: translateX(4px);
        }
        
        [dir="rtl"] .btn-login:hover i {
            transform: translateX(-4px);
        }
        
        /* ================================================
           خطا و پیام‌ها
           ================================================ */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-lg);
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease;
            border: 1px solid transparent;
            font-size: 0.95rem;
        }
        
        .alert-success {
            background: rgba(74, 222, 128, 0.08);
            border-color: rgba(74, 222, 128, 0.2);
            color: #4ade80;
        }
        
        .alert-danger {
            background: rgba(248, 113, 113, 0.08);
            border-color: rgba(248, 113, 113, 0.2);
            color: #f87171;
        }
        
        .alert-warning {
            background: rgba(251, 191, 36, 0.08);
            border-color: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        
        .alert i {
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        /* ================================================
           بخش اجتماعی
           ================================================ */
        .social-login {
            margin-top: 28px;
            text-align: center;
        }
        
        .social-login p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 14px;
            position: relative;
        }
        
        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 25%;
            height: 1px;
            background: var(--border-gold);
        }
        
        .social-login p::before { left: 0; }
        .social-login p::after { right: 0; }
        
        [dir="rtl"] .social-login p::before { left: auto; right: 0; }
        [dir="rtl"] .social-login p::after { right: auto; left: 0; }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 14px;
        }
        
        .social-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
            font-size: 1rem;
            font-weight: 600;
        }
        
        .social-icon:hover {
            background: rgba(196, 169, 90, 0.12);
            border-color: var(--gold-primary);
            color: var(--gold-light);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(196, 169, 90, 0.15);
        }
        
        /* ================================================
           فوتر و لینک‌ها
           ================================================ */
        .footer-text {
            text-align: center;
            margin-top: 28px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .footer-text a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .footer-text a:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }
        
        .sidebar-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-gold);
            font-size: 0.8rem;
            color: var(--text-muted);
            opacity: 0.7;
        }
        
        /* ================================================
           انتخابگر زبان
           ================================================ */
        .language-selector {
            margin-top: 25px;
        }
        
        .language-selector select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-gold);
            border-radius: var(--radius-lg);
            color: var(--text-light);
            font-size: 0.95rem;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23c8a951' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: left 16px center;
            padding-left: 48px;
        }
        
        [dir="rtl"] .language-selector select {
            background-position: right 16px center;
            padding-right: 48px;
            padding-left: 16px;
        }
        
        .language-selector select option {
            background: var(--bg-deep);
            color: var(--text-light);
            padding: 10px;
        }
        
        .language-selector select:hover,
        .language-selector select:focus {
            background-color: rgba(255, 255, 255, 0.06);
            border-color: var(--gold-primary);
            outline: none;
        }
        
        /* ================================================
           حالت بارگذاری
           ================================================ */
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 22px;
            height: 22px;
            margin: -11px 0 0 -11px;
            border: 2px solid var(--bg-deep);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* ================================================
           واکنشگرایی
           ================================================ */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 500px;
            }
            
            .login-sidebar {
                border-left: none;
                border-right: none;
                border-bottom: 1px solid var(--border-gold);
            }
            
            [dir="rtl"] .login-sidebar {
                border-left: none;
                border-right: none;
            }
            
            .login-sidebar {
                padding: 35px 30px;
            }
            
            .login-main {
                padding: 35px 30px;
            }
            
            .sidebar-stats {
                gap: 20px;
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 576px) {
            .login-main {
                padding: 25px 18px;
            }
            
            .login-sidebar {
                padding: 25px 18px;
            }
            
            .sidebar-content h1 {
                font-size: 1.6rem;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .social-login p::before,
            .social-login p::after {
                width: 15%;
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
                                <h2 style="color: var(--gold-primary); font-size: 2rem;">دریکانا</h2>
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
                                <h2 style="color: var(--gold-primary);">دریکانا</h2>
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