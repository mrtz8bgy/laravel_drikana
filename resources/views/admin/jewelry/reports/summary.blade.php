@extends('admin.jewelry.layouts.app')

@section('title', 'گزارش جامع اشیاء قیمتی')

@section('jewelry_content')

{{--
    گزارش جامع ماژول جواهرات
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت و کنترلر
    `Jewelry\Admin\ReportController` هم هیچ روتی نداشت؛ یعنی ۴۴۲ خط
    کد گزارش‌گیری نوشته شده بود اما در دسترس نبود. اکنون:
      • ویو ساخته شده است
      • روت‌های /admin/jewelry/reports/* به این کنترلر متصل شده‌اند

    دسترسی نیازمند مجوز `jewelry.reports.view` است (سطح روت).
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">گزارش جامع</h4>
    <div>
        <a href="{{ route('admin.jewelry.reports.certificates') }}" class="btn btn-outline-primary btn-sm">شناسنامه‌ها</a>
        <a href="{{ route('admin.jewelry.reports.repairs') }}" class="btn btn-outline-primary btn-sm">تعمیرات</a>
        <a href="{{ route('admin.jewelry.reports.transfers') }}" class="btn btn-outline-primary btn-sm">انتقالات</a>
    </div>
</div>

{{-- ============================================= --}}
{{-- آمار کلی --}}
{{-- ============================================= --}}
<div class="row g-3 mb-4">
    @foreach([
        'total_certificates'       => ['کل شناسنامه‌ها', 'secondary'],
        'verified_certificates'    => ['تأییدشده', 'success'],
        'pending_certificates'     => ['در انتظار تأیید', 'warning'],
        'total_repair_orders'      => ['کل سفارش‌های تعمیر', 'secondary'],
        'active_repairs'           => ['تعمیرات در جریان', 'info'],
        'completed_repairs'        => ['تعمیرات تمام‌شده', 'success'],
        'total_transfers'          => ['کل انتقالات', 'primary'],
        'total_users_with_jewelry' => ['مالکان دارای قطعه', 'dark'],
    ] as $key => $meta)
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="mb-0 text-{{ $meta[1] }}">
                        {{ number_format(isset($stats[$key]) ? $stats[$key] : 0) }}
                    </h3>
                    <small class="text-muted">{{ $meta[0] }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">

    {{-- توزیع فلز --}}
    @if(isset($metalDistribution) && count($metalDistribution) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">توزیع بر اساس نوع فلز</div>
                <div class="card-body">
                    {{--
                        نکته: کنترلر این داده را با select() خام و COUNT(*) می‌سازد،
                        بنابراین هر آیتم یک شیء با خصیصه‌های metal_type و count است
                        (نه یک آرایه کلید => مقدار). دسترسی با $metal => $count باعث
                        خطای «number_format() expects parameter 1 to be float, object given»
                        می‌شد.
                    --}}
                    <table class="table table-sm mb-0">
                        <thead><tr><th>فلز</th><th>تعداد</th></tr></thead>
                        <tbody>
                            @foreach($metalDistribution as $row)
                                <tr>
                                    <td>{{ $row->metal_type ?: 'نامشخص' }}</td>
                                    <td>{{ number_format((int) $row->count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- توزیع وضعیت تعمیرات --}}
    @if(isset($repairStatusDistribution) && count($repairStatusDistribution) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">توزیع وضعیت تعمیرات</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>وضعیت</th><th>تعداد</th></tr></thead>
                        <tbody>
                            @foreach($repairStatusDistribution as $row)
                                <tr>
                                    <td>{{ $row->status ?: 'نامشخص' }}</td>
                                    <td>{{ number_format((int) $row->count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- آمار ماهانه --}}
    @if(isset($monthlyCertificates) && count($monthlyCertificates) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">شناسنامه‌های صادرشده در ۶ ماه گذشته</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>ماه</th><th>تعداد</th></tr></thead>
                        <tbody>
                            @foreach($monthlyCertificates as $row)
                                <tr>
                                    <td>{{ $row->month }}</td>
                                    <td>{{ number_format((int) $row->count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(isset($monthlyRepairs) && count($monthlyRepairs) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">سفارش‌های تعمیر ۶ ماه گذشته</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>ماه</th><th>تعداد</th></tr></thead>
                        <tbody>
                            @foreach($monthlyRepairs as $row)
                                <tr>
                                    <td>{{ $row->month }}</td>
                                    <td>{{ number_format((int) $row->count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- آخرین شناسنامه‌ها --}}
    @if(isset($recentCertificates) && count($recentCertificates) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">آخرین شناسنامه‌های صادرشده</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>شماره سری</th><th>قطعه</th><th>تاریخ</th></tr></thead>
                        <tbody>
                            @foreach($recentCertificates as $certificate)
                                <tr>
                                    <td><small>{{ $certificate->serial_number }}</small></td>
                                    <td><small>{{ $certificate->product_name }}</small></td>
                                    <td><small>{{ optional($certificate->created_at)->format('Y-m-d') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- آخرین تعمیرات --}}
    @if(isset($recentRepairs) && count($recentRepairs) > 0)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">آخرین سفارش‌های تعمیر</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>شماره سفارش</th><th>مشتری</th><th>وضعیت</th></tr></thead>
                        <tbody>
                            @foreach($recentRepairs as $repair)
                                <tr>
                                    <td><small>{{ $repair->order_number }}</small></td>
                                    <td><small>{{ optional($repair->user)->name ?: '—' }}</small></td>
                                    <td><small>{{ $repair->status }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection
