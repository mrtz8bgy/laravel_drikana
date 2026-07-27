<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1 ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <title>{{ config('app.name', 'Laravel') }} | ثبت‌نام در پنل مدیریت</title>
    
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
            margin: 0;
            padding: 20px;
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
        
        .animate__fadeIn { animation: fadeIn 0.8s ease; }
        .animate__slideUp { animation: slideUp 0.8s ease; }
        
        /* ================================================
           کانتینر اصلی
           ================================================ */
        #container {
            width: 100%;
            max-width: 1200px;
            position: relative;
            z-index: 1;
        }
        
        .cls-content {
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        /* ================================================
           کارت ثبت‌نام – شیشه‌ای با حاشیه طلایی
           ================================================ */
        .register-wrapper {
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
        
        .register-wrapper::before {
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
        .register-sidebar {
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
        
        [dir="rtl"] .register-sidebar {
            border-left: none;
            border-right: 1px solid var(--border-gold);
        }
        
        .register-sidebar::before {
            content: '◆';
            position: absolute;
            top: -50px;
            right: -50px;
            font-size: 300px;
            color: rgba(196, 169, 90, 0.04);
            pointer-events: none;
        }
        
        .register-sidebar::after {
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
        .register-main {
            flex: 1.2;
            padding: 50px 45px;
            background: rgba(255, 255, 255, 0.02);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .register-header .logo img {
            max-width: 140px;
            height: auto;
            margin-bottom: 20px;
        }
        
        .register-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        
        .register-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .register-header .gold-divider {
            width: 60px;
            height: 3px;
            background: var(--gold-gradient);
            margin: 12px auto 0;
            border-radius: 10px;
        }
        
        /* ================================================
           فرم
           ================================================ */
        .register-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
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
           چک‌باکس شرایط
           ================================================ */
        .terms-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .terms-check:hover {
            color: var(--text-light);
        }
        
        .terms-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--gold-primary);
            cursor: pointer;
            border-radius: 4px;
            flex-shrink: 0;
        }
        
        .terms-check a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .terms-check a:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }
        
        /* ================================================
           دکمه ثبت‌نام – طلایی با انیمیشن
           ================================================ */
        .btn-register {
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
        
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(196, 169, 90, 0.3);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .btn-register i {
            margin-left: 8px;
            transition: var(--transition);
        }
        
        [dir="rtl"] .btn-register i {
            margin-left: 0;
            margin-right: 8px;
        }
        
        .btn-register:hover i {
            transform: translateX(4px);
        }
        
        [dir="rtl"] .btn-register:hover i {
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
        
        .alert i {
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .invalid-feedback {
            display: block;
            color: #f87171;
            font-size: 0.85rem;
            margin-top: 6px;
        }
        
        /* ================================================
           بخش اجتماعی
           ================================================ */
        .social-register {
            margin-top: 28px;
            text-align: center;
        }
        
        .social-register p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 14px;
            position: relative;
        }
        
        .social-register p::before,
        .social-register p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 25%;
            height: 1px;
            background: var(--border-gold);
        }
        
        .social-register p::before { left: 0; }
        .social-register p::after { right: 0; }
        
        [dir="rtl"] .social-register p::before { left: auto; right: 0; }
        [dir="rtl"] .social-register p::after { right: auto; left: 0; }
        
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
            .register-wrapper {
                flex-direction: column;
                max-width: 500px;
                margin: 0 auto;
            }
            
            .register-sidebar {
                border-left: none;
                border-right: none;
                border-bottom: 1px solid var(--border-gold);
            }
            
            [dir="rtl"] .register-sidebar {
                border-left: none;
                border-right: none;
            }
            
            .register-sidebar {
                padding: 35px 30px;
            }
            
            .register-main {
                padding: 35px 30px;
            }
            
            .sidebar-stats {
                gap: 20px;
                flex-wrap: wrap;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
        
        @media (max-width: 576px) {
            .register-main {
                padding: 25px 18px;
            }
            
            .register-sidebar {
                padding: 25px 18px;
            }
            
            .sidebar-content h1 {
                font-size: 1.6rem;
            }
            
            .social-register p::before,
            .social-register p::after {
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
            <div class="register-wrapper">
                <!-- ===== سایدبار ===== -->
                <div class="register-sidebar">
                    <div class="sidebar-content">
                        <div class="logo">
                            @if($generalsetting && $generalsetting->logo != null)
                                <img src="{{ asset($generalsetting->logo) }}" alt="{{ config('app.name') }}">
                            @else
                                <h2 style="color: var(--gold-primary); font-size: 2rem;">دریکانا</h2>
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
                                <h2 style="color: var(--gold-primary);">دریکانا</h2>
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