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

    <style>
        /* ============================================ */
        /* Bootstrap 5 RTL Core Styles - آفلاین */
        /* ============================================ */
        
        /* فونت سیستمی */
        * {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "IranSans", Tahoma, sans-serif;
        }
        
        /* Reset & Base */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #f8f9fa;
            text-align: right;
        }
        
        /* Grid System */
        .container-fluid {
            width: 100%;
            padding-right: 0.75rem;
            padding-left: 0.75rem;
            margin-right: auto;
            margin-left: auto;
        }
        
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-0.5 * var(--bs-gutter-x));
            margin-left: calc(-0.5 * var(--bs-gutter-x));
        }
        
        .row > * {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-top: var(--bs-gutter-y);
        }
        
        .col {
            flex: 1 0 0%;
        }
        
        .col-1 { flex: 0 0 auto; width: 8.33333333%; }
        .col-2 { flex: 0 0 auto; width: 16.66666667%; }
        .col-3 { flex: 0 0 auto; width: 25%; }
        .col-4 { flex: 0 0 auto; width: 33.33333333%; }
        .col-5 { flex: 0 0 auto; width: 41.66666667%; }
        .col-6 { flex: 0 0 auto; width: 50%; }
        .col-7 { flex: 0 0 auto; width: 58.33333333%; }
        .col-8 { flex: 0 0 auto; width: 66.66666667%; }
        .col-9 { flex: 0 0 auto; width: 75%; }
        .col-10 { flex: 0 0 auto; width: 83.33333333%; }
        .col-11 { flex: 0 0 auto; width: 91.66666667%; }
        .col-12 { flex: 0 0 auto; width: 100%; }
        
        .col-md-1 { flex: 0 0 auto; width: 8.33333333%; }
        .col-md-2 { flex: 0 0 auto; width: 16.66666667%; }
        .col-md-3 { flex: 0 0 auto; width: 25%; }
        .col-md-4 { flex: 0 0 auto; width: 33.33333333%; }
        .col-md-5 { flex: 0 0 auto; width: 41.66666667%; }
        .col-md-6 { flex: 0 0 auto; width: 50%; }
        .col-md-7 { flex: 0 0 auto; width: 58.33333333%; }
        .col-md-8 { flex: 0 0 auto; width: 66.66666667%; }
        .col-md-9 { flex: 0 0 auto; width: 75%; }
        .col-md-10 { flex: 0 0 auto; width: 83.33333333%; }
        .col-md-11 { flex: 0 0 auto; width: 91.66666667%; }
        .col-md-12 { flex: 0 0 auto; width: 100%; }
        
        .col-lg-1 { flex: 0 0 auto; width: 8.33333333%; }
        .col-lg-2 { flex: 0 0 auto; width: 16.66666667%; }
        .col-lg-3 { flex: 0 0 auto; width: 25%; }
        .col-lg-4 { flex: 0 0 auto; width: 33.33333333%; }
        .col-lg-5 { flex: 0 0 auto; width: 41.66666667%; }
        .col-lg-6 { flex: 0 0 auto; width: 50%; }
        .col-lg-7 { flex: 0 0 auto; width: 58.33333333%; }
        .col-lg-8 { flex: 0 0 auto; width: 66.66666667%; }
        .col-lg-9 { flex: 0 0 auto; width: 75%; }
        .col-lg-10 { flex: 0 0 auto; width: 83.33333333%; }
        .col-lg-11 { flex: 0 0 auto; width: 91.66666667%; }
        .col-lg-12 { flex: 0 0 auto; width: 100%; }
        
        /* Spacing */
        .p-0 { padding: 0 !important; }
        .p-1 { padding: 0.25rem !important; }
        .p-2 { padding: 0.5rem !important; }
        .p-3 { padding: 1rem !important; }
        .p-4 { padding: 1.5rem !important; }
        .p-5 { padding: 3rem !important; }
        
        .px-0 { padding-right: 0 !important; padding-left: 0 !important; }
        .px-1 { padding-right: 0.25rem !important; padding-left: 0.25rem !important; }
        .px-2 { padding-right: 0.5rem !important; padding-left: 0.5rem !important; }
        .px-3 { padding-right: 1rem !important; padding-left: 1rem !important; }
        .px-4 { padding-right: 1.5rem !important; padding-left: 1.5rem !important; }
        .px-5 { padding-right: 3rem !important; padding-left: 3rem !important; }
        
        .py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
        .py-1 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
        .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        .py-3 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        .py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        
        .m-0 { margin: 0 !important; }
        .m-1 { margin: 0.25rem !important; }
        .m-2 { margin: 0.5rem !important; }
        .m-3 { margin: 1rem !important; }
        .m-4 { margin: 1.5rem !important; }
        .m-5 { margin: 3rem !important; }
        
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-5 { margin-bottom: 3rem !important; }
        
        .mt-0 { margin-top: 0 !important; }
        .mt-1 { margin-top: 0.25rem !important; }
        .mt-2 { margin-top: 0.5rem !important; }
        .mt-3 { margin-top: 1rem !important; }
        .mt-4 { margin-top: 1.5rem !important; }
        .mt-5 { margin-top: 3rem !important; }
        
        .me-0 { margin-left: 0 !important; }
        .me-1 { margin-left: 0.25rem !important; }
        .me-2 { margin-left: 0.5rem !important; }
        .me-3 { margin-left: 1rem !important; }
        
        .ms-0 { margin-right: 0 !important; }
        .ms-1 { margin-right: 0.25rem !important; }
        .ms-2 { margin-right: 0.5rem !important; }
        .ms-3 { margin-right: 1rem !important; }
        
        [dir="rtl"] .me-0 { margin-right: 0 !important; margin-left: 0 !important; }
        [dir="rtl"] .me-1 { margin-right: 0.25rem !important; margin-left: 0 !important; }
        [dir="rtl"] .me-2 { margin-right: 0.5rem !important; margin-left: 0 !important; }
        [dir="rtl"] .me-3 { margin-right: 1rem !important; margin-left: 0 !important; }
        [dir="rtl"] .ms-0 { margin-left: 0 !important; margin-right: 0 !important; }
        [dir="rtl"] .ms-1 { margin-left: 0.25rem !important; margin-right: 0 !important; }
        [dir="rtl"] .ms-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
        [dir="rtl"] .ms-3 { margin-left: 1rem !important; margin-right: 0 !important; }
        
        /* Forms */
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-select {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem 0.375rem 2.25rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 0.75rem center;
            background-size: 16px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            appearance: none;
        }
        
        .form-select:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        textarea.form-control {
            min-height: calc(1.5em + 0.75rem + 2px);
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            border-radius: 0.375rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }
        
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
        
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }
        
        .btn-warning {
            color: #000;
            background-color: #ffc107;
            border-color: #ffc107;
        }
        
        .btn-info {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
        
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        /* Tables */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
            border-collapse: collapse;
        }
        
        .table > :not(caption) > * > * {
            padding: 0.5rem 0.5rem;
            background-color: transparent;
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px transparent;
        }
        
        .table > thead {
            vertical-align: bottom;
            background-color: #f8f9fa;
        }
        
        .table > thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .table-hover > tbody > tr:hover > * {
            background-color: rgba(0, 0, 0, 0.075);
        }
        
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        
        .table-bordered > :not(caption) > * {
            border-width: 1px 0;
        }
        
        .table-bordered > :not(caption) > * > * {
            border-width: 0 1px;
        }
        
        /* Cards */
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.375rem;
        }
        
        .card-header {
            padding: 0.5rem 1rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .card-header:first-child {
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .card-body {
            flex: 1 1 auto;
            padding: 1rem 1rem;
        }
        
        .card-footer {
            padding: 0.5rem 1rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        /* Alerts */
        .alert {
            position: relative;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }
        
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
        
        .alert-warning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }
        
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
        
        .alert-dismissible {
            padding-left: 3rem;
        }
        
        .btn-close {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            padding: 1rem;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
            border: 0;
            border-radius: 0.375rem;
            opacity: 0.5;
            cursor: pointer;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }
        
        .bg-primary { background-color: #0d6efd !important; color: white; }
        .bg-success { background-color: #198754 !important; color: white; }
        .bg-danger { background-color: #dc3545 !important; color: white; }
        .bg-warning { background-color: #ffc107 !important; color: black; }
        .bg-info { background-color: #0dcaf0 !important; color: black; }
        .bg-secondary { background-color: #6c757d !important; color: white; }
        
        /* Text Colors */
        .text-primary { color: #0d6efd !important; }
        .text-success { color: #198754 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #0dcaf0 !important; }
        .text-muted { color: #6c757d !important; }
        .text-dark { color: #212529 !important; }
        .text-white { color: #fff !important; }
        
        /* Background Colors */
        .bg-white { background-color: #fff !important; }
        .bg-light { background-color: #f8f9fa !important; }
        .bg-dark { background-color: #212529 !important; }
        
        /* Flexbox */
        .d-flex { display: flex !important; }
        .d-inline-flex { display: inline-flex !important; }
        .d-none { display: none !important; }
        .d-block { display: block !important; }
        
        .flex-row { flex-direction: row !important; }
        .flex-column { flex-direction: column !important; }
        .justify-content-start { justify-content: flex-start !important; }
        .justify-content-end { justify-content: flex-end !important; }
        .justify-content-center { justify-content: center !important; }
        .justify-content-between { justify-content: space-between !important; }
        .align-items-start { align-items: flex-start !important; }
        .align-items-end { align-items: flex-end !important; }
        .align-items-center { align-items: center !important; }
        
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }
        
        /* Dropdown */
        .dropdown {
            position: relative;
        }
        
        .dropdown-toggle {
            cursor: pointer;
        }
        
        .dropdown-toggle::after {
            display: inline-block;
            margin-right: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
        
        .dropdown-menu {
            position: absolute;
            z-index: 1000;
            display: none;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0;
            font-size: 1rem;
            color: #212529;
            text-align: right;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.375rem;
        }
        
        .dropdown-menu.show {
            display: block;
        }
        
        .dropdown-menu-start {
            right: 0;
            left: auto;
        }
        
        [dir="rtl"] .dropdown-menu-start {
            right: auto;
            left: 0;
        }
        
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.25rem 1rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            cursor: pointer;
        }
        
        .dropdown-item:hover {
            background-color: #e9ecef;
        }
        
        .dropdown-divider {
            height: 0;
            margin: 0.5rem 0;
            overflow: hidden;
            border-top: 1px solid rgba(0, 0, 0, 0.15);
        }
        
        /* Border */
        .border { border: 1px solid #dee2e6 !important; }
        .border-top { border-top: 1px solid #dee2e6 !important; }
        .border-bottom { border-bottom: 1px solid #dee2e6 !important; }
        .rounded { border-radius: 0.375rem !important; }
        .rounded-circle { border-radius: 50% !important; }
        
        /* Shadow */
        .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
        .shadow { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }
        
        /* Width */
        .w-100 { width: 100% !important; }
        .w-50 { width: 50% !important; }
        .w-25 { width: 25% !important; }
        
        /* Sidebar Custom */
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
        
        .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-menu {
            overflow-y: auto;
            max-height: calc(100vh - 150px);
        }
        
        /* Navbar */
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
        
        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin-bottom: 1rem;
            list-style: none;
            background-color: transparent;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            float: right;
            padding-right: 0.5rem;
            padding-left: 0.5rem;
            color: #6c757d;
            content: "/";
        }
        
        [dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
            float: left;
        }
        
        /* Content */
        .content {
            padding: 20px;
            min-height: calc(100vh - 70px);
        }
        
        /* Fade Animation */
        .fade {
            transition: opacity 0.15s linear;
        }
        
        .fade:not(.show) {
            opacity: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .col-md-3 { width: 100%; }
            .col-md-9 { width: 100%; }
        }
    </style>


        /* ====== تم طلایی-سرمه‌ای دریکانا برای پنل جواهرات ====== */
        @import url('https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css');
        :root { --jg:#d4af37; --jg-light:#f4d47c; --jg-dark:#07091a; --jg-navy:#12173a; }
        body { background:#f3efe6; font-family:'Vazirmatn',Tahoma,sans-serif !important; }
        .sidebar {
            background: linear-gradient(180deg, #07091a 0%, #12173a 60%, #1a1f4d 100%) !important;
            border-left: 3px solid #d4af37;
        }
        .sidebar a { color: rgba(255,255,255,.75); border-right:3px solid transparent; border-radius:8px; margin-bottom:4px; transition: all .25s; }
        .sidebar a:hover, .sidebar a.active {
            background: linear-gradient(90deg, rgba(212,175,55,.15), rgba(212,175,55,.05)) !important;
            color: #f4d47c !important;
            border-right-color: #d4af37 !important;
        }
        .sidebar-brand {
            background: linear-gradient(135deg, rgba(212,175,55,.1), transparent);
            border-bottom: 1px solid rgba(212,175,55,.2) !important;
        }
        .sidebar-brand h4 {
            background: linear-gradient(135deg, #f4d47c, #d4af37);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
            font-weight:800; letter-spacing:.5px;
        }
        .navbar-top {
            background: linear-gradient(90deg, #fff, #fffdf7) !important;
            border-bottom: 2px solid #d4af37;
        }
        .page-header h1 { color:#07091a; font-weight:800; }
        .btn-primary { background:linear-gradient(135deg,#d4af37,#b8892a); border-color:#b8892a; }
        .btn-primary:hover { background:linear-gradient(135deg,#b8892a,#d4af37); border-color:#b8892a; }
        .badge.bg-danger { background:linear-gradient(135deg,#ef4444,#b91c1c) !important; }
        .card, .table { border-color: rgba(212,175,55,.2); }
        .card-header, .table thead { background: linear-gradient(90deg, rgba(212,175,55,.08), rgba(212,175,55,.02)); }
        a.text-danger { color:#e53e3e !important; }
        .sidebar a.text-danger:hover { background:rgba(229,62,62,.12) !important; color:#fca5a5 !important; border-right-color:#e53e3e !important; }
        @media (max-width:768px){ .sidebar { min-height:auto; } }

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