<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'پنل مدیریت جواهرات')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Persian Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        * {
            font-family: Vazirmatn, Tahoma, sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a2530 100%);
            color: white;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
            border-right: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-right-color: #3498db;
        }
        .sidebar a i {
            width: 20px;
            text-align: center;
            margin-left: 10px;
        }
        .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            min-height: calc(100vh - 70px);
        }
        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .page-header {
            margin-bottom: 30px;
        }
        .page-header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }
        .alert {
            border: none;
            border-radius: 8px;
        }
    </style>
    
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
                            <i class="fas fa-gem text-warning"></i>
                            مدیریت جواهرات
                        </h4>
                        <small class="text-muted">پنل ادمین</small>
                    </div>
                    
                    <div class="sidebar-menu px-3">
                        <a href="{{ route('admin.jewelry.dashboard') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> داشبورد
                        </a>
                        
                        <a href="{{ route('admin.jewelry.owners.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/owners*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> مدیریت مالکان
                        </a>
                        
                        <a href="{{ route('admin.jewelry.certificates.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/certificates*') ? 'active' : '' }}">
                            <i class="fas fa-certificate"></i> شناسنامه‌ها
                        </a>
                        
                        <a href="{{ route('admin.jewelry.repair-orders.index') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/repair-orders*') ? 'active' : '' }}">
                            <i class="fas fa-tools"></i> سفارشات تعمیر
                        </a>
                        
                        <a href="{{ route('admin.jewelry.reports.certificates') }}" 
                           class="mb-2 rounded {{ request()->is('admin/jewelry/reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> گزارشات
                        </a>
                        
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <a href="#" class="text-danger" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> خروج از سیستم
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 p-0">
                <!-- Top Navbar -->
                <nav class="navbar-top">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
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
                                   data-bs-toggle="dropdown">
                                    <div class="me-2 text-end">
                                        <div class="fw-bold">مدیر سیستم</div>
                                        <small class="text-muted">ادمین</small>
                                    </div>
                                    <i class="fas fa-user-circle fs-4"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-start">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> پروفایل</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> تنظیمات</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> خروج
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="content">
                    <!-- Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('jewelry_content')
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>