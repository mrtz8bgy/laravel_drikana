<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'پنل مدیریت اشیاء قیمتی')</title>

    {{-- =============================================== --}}
    {{-- تمام استایل‌های Bootstrap 5 به صورت آفلاین --}}
    {{-- بدون هیچ وابستگی به CDN خارجی --}}
    {{-- =============================================== --}}

    {{-- استایل به فایل مستقل منتقل شد: css/drikana/jewelry-admin.css --}}
    <link rel="stylesheet" href="{{ asset('css/drikana/jewelry-admin.css') }}">

    {{-- استایل تم طلایی-سرمه‌ای نیز به همان فایل منتقل شده است.
         نکته: خط `@import` فونت وزیرمتن در ابتدای فایل CSS قرار گرفته
         است، چون در CSS دستور @import تنها در صورتی معتبر است که پیش از
         همه قواعد بیاید؛ اگر وسط فایل بماند، مرورگر آن را نادیده می‌گیرد
         و فونت بارگذاری نمی‌شود. --}}

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0">
                <nav class="sidebar">
                    <div class="sidebar-brand p-3">
                        <h4 class="mb-0">
                            💎 مدیریت اشیاء قیمتی
                        </h4>
                        <small class="text-muted">پنل ادمین</small>
                    </div>

                    <div class="sidebar-menu px-3">
                        <a href="{{ route('admin.jewelry.dashboard') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/dashboard') ? 'active' : '' }}">
                            📊 پیشخوان اشیاء قیمتی
                        </a>

                        <a href="{{ route('admin.jewelry.owners.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/owners*') ? 'active' : '' }}">
                            👥 مدیریت مالکان
                        </a>

                        <a href="{{ route('admin.jewelry.certificates.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/certificates*') ? 'active' : '' }}">
                            📜 شناسنامه‌ها
                        </a>

                        <a href="{{ route('admin.jewelry.missing_reports.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/missing_reports*') ? 'active' : '' }}">
                            ⚠️ سرقت و مفقودی
                            @php
                                $pendingCount = \App\Models\MissingReport::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-danger">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.jewelry.missing_reports.create') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/missing_reports/create') ? 'active' : '' }}">
                            ➕ ثبت مفقودی جدید
                        </a>

                        <a href="{{ route('admin.jewelry.repair-orders.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/repair-orders*') ? 'active' : '' }}">
                            🔧 تعمیرات
                            @php
                                $pendingRepairs = \App\Models\RepairOrder::where('status', 'pending')->count();
                            @endphp
                            @if($pendingRepairs > 0)
                                <span class="badge bg-danger">{{ $pendingRepairs }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.jewelry.repair-orders.create') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/repair-orders/create') ? 'active' : '' }}">
                            ➕ ثبت سفارش جدید
                        </a>

                        <a href="{{ route('admin.jewelry.reports.certificates') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/reports*') ? 'active' : '' }}">
                            📊 گزارشات
                        </a>

                        <div class="mt-4 pt-3" style="border-top:1px solid rgba(212,175,55,.2);"></div>
                        <a href="{{ route('admin.dashboard') }}" class="mb-2 rounded">
                            🏪 پنل اصلی فروشگاه
                        </a>
                        <a href="{{ route('home') }}" class="mb-2 rounded" target="_blank">
                            🌐 مشاهده سایت
                        </a>
                        <a href="#" class="text-danger" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            🚪 خروج از سیستم
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 p-0">
                <!-- Top Navbar -->
                <nav class="navbar-top">
                    <div class="container-fluid d-flex justify-content-between align-items-center">
                        <div class="page-header">
                            <h1>@yield('title', 'پنل مدیریت')</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
                               id="userDropdown">
                                <div class="me-2 text-end">
                                    <div class="fw-bold">{{ Auth::user()->name ?? 'مدیر سیستم' }}</div>
                                    <small class="text-muted">{{ Auth::user()->role_label ?? 'کاربر' }}</small>
                                </div>
                                👤
                            </a>
                            <ul class="dropdown-menu dropdown-menu-start" id="dropdownMenu">
                                <li><a class="dropdown-item" href="#">👤 پروفایل</a></li>
                                <li><a class="dropdown-item" href="#">⚙️ تنظیمات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        🚪 خروج
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✅ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ❌ {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            ⚠️ {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('jewelry_content')
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown functionality
            const dropdownToggle = document.getElementById('userDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (dropdownToggle && dropdownMenu) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('show');
                });
                
                document.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
            
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
            
            // Close buttons
            const closeButtons = document.querySelectorAll('.btn-close');
            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    if (alert) alert.style.display = 'none';
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>