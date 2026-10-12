@extends('admin.jewelry.layouts.app')

@section('title', 'داشبورد سامانه جواهرات')

@section('breadcrumb')
    <li class="breadcrumb-item active">داشبورد سامانه</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h2 class="mb-1">داشبورد سامانه جواهرات</h2>
            <p class="text-muted mb-0">
                @if($isSuperAdmin)
                    نمای کلی اطلاعات و فعالیت‌های سامانه
                @else
                    خلاصه اطلاعات و فعالیت‌های شما
                @endif
            </p>
        </div>
        <div class="mt-2">
            @can('jewelry.owners.create')
                <a href="{{ route('admin.jewelry.owners.create') }}" class="btn btn-outline-primary mb-1">ثبت مالک</a>
            @endcan
            @can('jewelry.certificates.create')
                <a href="{{ route('admin.jewelry.certificates.create') }}" class="btn btn-outline-primary mb-1">ثبت شناسنامه</a>
            @endcan
            @can('jewelry.repairs.create')
                <a href="{{ route('admin.jewelry.repair-orders.create') }}" class="btn btn-primary mb-1">ثبت سفارش تعمیر</a>
            @endcan
        </div>
    </div>

    @if($stats['pending_verification'] || $stats['urgent_repairs'] || $stats['pending_transfers'] || $stats['pending_missing_reports'])
        <div class="card border-warning mb-4">
            <div class="card-header text-warning"><strong>موارد نیازمند پیگیری</strong></div>
            <div class="card-body d-flex flex-wrap">
                @if($stats['pending_verification'])
                    <span class="badge badge-warning mr-2 mb-2">{{ number_format($stats['pending_verification']) }} شناسنامه در انتظار بررسی</span>
                @endif
                @if($stats['urgent_repairs'])
                    <span class="badge badge-danger mr-2 mb-2">{{ number_format($stats['urgent_repairs']) }} تعمیر فوری در جریان</span>
                @endif
                @if($stats['pending_transfers'])
                    @can('jewelry.transfers.view')
                        <a href="{{ route('admin.jewelry.transfers.index') }}" class="badge badge-warning mr-2 mb-2">
                            {{ number_format($stats['pending_transfers']) }} درخواست انتقال در انتظار تأیید
                        </a>
                    @else
                        <span class="badge badge-warning mr-2 mb-2">{{ number_format($stats['pending_transfers']) }} درخواست انتقال در انتظار تأیید</span>
                    @endcan
                @endif
                @if($stats['pending_missing_reports'])
                    @can('jewelry.missing_reports.view')
                        <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="badge badge-danger mr-2 mb-2">
                            {{ number_format($stats['pending_missing_reports']) }} گزارش سرقت یا مفقودی باز
                        </a>
                    @else
                        <span class="badge badge-danger mr-2 mb-2">{{ number_format($stats['pending_missing_reports']) }} گزارش سرقت یا مفقودی باز</span>
                    @endcan
                @endif
            </div>
        </div>
    @endif

    <div class="row">
        @foreach([
            ['owners_count', 'مالکان', 'fa-users', 'primary', 'admin.jewelry.owners.index', 'jewelry.owners.view'],
            ['certificates_count', 'کل شناسنامه‌ها', 'fa-certificate', 'info', 'admin.jewelry.certificates.index', 'jewelry.certificates.view'],
            ['verified_certificates', 'شناسنامه‌های تأییدشده', 'fa-check-circle', 'success', 'admin.jewelry.certificates.index', 'jewelry.certificates.view'],
            ['pending_verification', 'در انتظار بررسی', 'fa-clock', 'warning', 'admin.jewelry.certificates.index', 'jewelry.certificates.view'],
            ['active_repairs', 'تعمیرات فعال', 'fa-tools', 'warning', 'admin.jewelry.repair-orders.index', 'jewelry.repairs.view'],
            ['completed_repairs', 'تکمیل یا تحویل‌شده', 'fa-check-double', 'success', 'admin.jewelry.repair-orders.index', 'jewelry.repairs.view'],
            ['pending_transfers', 'انتقال در انتظار تأیید', 'fa-exchange-alt', 'primary', 'admin.jewelry.transfers.index', 'jewelry.transfers.view'],
            ['pending_missing_reports', 'گزارش مفقودی باز', 'fa-exclamation-triangle', 'danger', 'admin.jewelry.missing_reports.index', 'jewelry.missing_reports.view'],
        ] as $card)
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card h-100 border-{{ $card[3] }}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">{{ $card[1] }}</div>
                            <div class="h2 mb-0">{{ number_format($stats[$card[0]]) }}</div>
                        </div>
                        <i class="fas {{ $card[2] }} fa-2x text-{{ $card[3] }}" aria-hidden="true"></i>
                    </div>
                    @can($card[5])
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="{{ route($card[4]) }}" class="small">مشاهده فهرست</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>آخرین شناسنامه‌ها</strong>
                    @can('jewelry.certificates.view')
                        <a href="{{ route('admin.jewelry.certificates.index') }}" class="small">مشاهده همه</a>
                    @endcan
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>قطعه</th><th>مالک</th><th>سریال</th><th>وضعیت</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentCertificates as $certificate)
                                @php
                                    $certificateStatus = $certificate->rejected_at
                                        ? ['ردشده', 'danger']
                                        : ($certificate->is_verified ? ['تأییدشده', 'success'] : ['در انتظار', 'warning']);
                                @endphp
                                <tr>
                                    <td>{{ $certificate->product_name }}</td>
                                    <td>{{ optional($certificate->owner)->full_name ?: '—' }}</td>
                                    <td><code>{{ $certificate->serial_number }}</code></td>
                                    <td><span class="badge badge-{{ $certificateStatus[1] }}">{{ $certificateStatus[0] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">هنوز شناسنامه‌ای ثبت نشده است.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>آخرین سفارش‌های تعمیر</strong>
                    @can('jewelry.repairs.view')
                        <a href="{{ route('admin.jewelry.repair-orders.index') }}" class="small">مشاهده همه</a>
                    @endcan
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>شماره سفارش</th><th>قطعه</th><th>وضعیت</th><th>اولویت</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentRepairs as $repair)
                                <tr>
                                    <td>{{ $repair->order_number ?: '#' . $repair->id }}</td>
                                    <td>{{ optional($repair->certificate)->product_name ?: '—' }}</td>
                                    <td><span class="badge badge-{{ $repair->status_color }}">{{ $repair->status_label }}</span></td>
                                    <td>{{ $repair->priority_label }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">سفارشی ثبت نشده است.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>آخرین انتقال‌های مالکیت</strong>
                    @can('jewelry.transfers.view')
                        <a href="{{ route('admin.jewelry.transfers.index') }}" class="small">مشاهده همه</a>
                    @endcan
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>شناسنامه</th><th>از مالک</th><th>به مالک</th><th>وضعیت</th></tr></thead>
                        <tbody>
                            @forelse($recentTransfers as $transfer)
                                <tr>
                                    <td>{{ optional($transfer->certificate)->serial_number ?: '—' }}</td>
                                    <td>{{ optional($transfer->fromOwner)->full_name ?: '—' }}</td>
                                    <td>{{ optional($transfer->toOwner)->full_name ?: '—' }}</td>
                                    <td><span class="badge badge-{{ $transfer->status_color }}">{{ $transfer->status_label }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">انتقالی ثبت نشده است.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-header"><strong>ترکیب فلز شناسنامه‌ها</strong></div>
                <div class="card-body">
                    @forelse($metalDistribution as $metal)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ $metal->metal_type ?: 'نامشخص' }}</span>
                                <strong>{{ number_format($metal->total) }}</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: {{ $stats['certificates_count'] ? round($metal->total * 100 / $stats['certificates_count']) : 0 }}%"
                                     aria-valuenow="{{ $stats['certificates_count'] ? round($metal->total * 100 / $stats['certificates_count']) : 0 }}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">داده‌ای برای نمایش وجود ندارد.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-header"><strong>آخرین گزارش‌های سرقت و مفقودی</strong></div>
                <div class="card-body">
                    @forelse($recentMissingReports as $report)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $report->type === 'theft' ? 'سرقت' : 'مفقودی' }}</strong>
                                <span class="badge badge-{{ $report->status === 'pending' ? 'danger' : 'success' }}">
                                    {{ $report->status === 'pending' ? 'باز' : 'پیدا شده' }}
                                </span>
                            </div>
                            <small class="text-muted">{{ optional($report->jewelryCertificate)->product_name ?: 'قطعه نامشخص' }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">گزارشی ثبت نشده است.</p>
                    @endforelse
                    @can('jewelry.missing_reports.view')
                        <a href="{{ route('admin.jewelry.missing_reports.index') }}" class="small">مشاهده گزارش‌ها</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @can('jewelry.reports.view')
        <div class="text-left mb-4">
            <a href="{{ route('admin.jewelry.reports.summary') }}" class="btn btn-outline-secondary">رفتن به گزارش‌های تفصیلی</a>
        </div>
    @endcan
</div>
@endsection
