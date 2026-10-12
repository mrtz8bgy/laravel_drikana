@extends('admin.jewelry.layouts.app')

@section('title', 'گزارش شناسنامه‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.reports.summary') }}">گزارش‌ها</a></li>
    <li class="breadcrumb-item active">گزارش شناسنامه‌ها</li>
@endsection

@section('jewelry_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h2 class="mb-1">گزارش شناسنامه‌های جواهرات</h2>
            <p class="text-muted mb-0">جستجو و بررسی وضعیت شناسنامه‌های ثبت‌شده</p>
        </div>
        <a href="{{ route('admin.jewelry.dashboard') }}" class="btn btn-outline-secondary">بازگشت به داشبورد</a>
    </div>

    <div class="row mb-4">
        @foreach([
            'total' => ['کل شناسنامه‌ها', 'primary', 'fa-certificate'],
            'verified' => ['تأییدشده', 'success', 'fa-check-circle'],
            'pending' => ['در انتظار بررسی', 'warning', 'fa-clock'],
            'rejected' => ['ردشده', 'danger', 'fa-times-circle'],
        ] as $key => $card)
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card h-100 border-{{ $card[1] }}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">{{ $card[0] }}</div>
                            <div class="h3 mb-0">{{ number_format($stats[$key]) }}</div>
                        </div>
                        <i class="fas {{ $card[2] }} fa-2x text-{{ $card[1] }}" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>جستجو و فیلتر</strong></div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jewelry.reports.certificates') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="certificate-search">جستجو</label>
                        <input id="certificate-search" type="search" name="search" class="form-control"
                               value="{{ request('search') }}" placeholder="شماره سریال، نام قطعه یا مالک">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="verification-status">وضعیت تأیید</label>
                        <select id="verification-status" name="verification_status" class="form-control">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>تأییدشده</option>
                            <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                            <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>ردشده</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="metal-type">نوع فلز</label>
                        <select id="metal-type" name="metal_type" class="form-control">
                            <option value="">همه فلزها</option>
                            @foreach($metalTypes as $metalType)
                                <option value="{{ $metalType }}" {{ request('metal_type') === $metalType ? 'selected' : '' }}>
                                    {{ $metalType }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date-from">از تاریخ ثبت</label>
                        <input id="date-from" type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date-to">تا تاریخ ثبت</label>
                        <input id="date-to" type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="d-flex flex-wrap">
                    <button type="submit" class="btn btn-primary mr-2 mb-2">اعمال فیلتر</button>
                    <a href="{{ route('admin.jewelry.reports.certificates') }}" class="btn btn-outline-secondary mb-2">پاک‌کردن فیلترها</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <strong>فهرست شناسنامه‌ها</strong>
            <span class="text-muted">{{ number_format($certificates->total()) }} نتیجه</span>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>شماره سریال</th>
                        <th>نام قطعه</th>
                        <th>مالک</th>
                        <th>فلز</th>
                        <th>عیار</th>
                        <th>وزن (گرم)</th>
                        <th>تاریخ صدور</th>
                        <th>وضعیت</th>
                        <th>جزئیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $index => $certificate)
                        @php
                            $status = $certificate->rejected_at
                                ? ['ردشده', 'danger']
                                : ($certificate->is_verified ? ['تأییدشده', 'success'] : ['در انتظار بررسی', 'warning']);
                        @endphp
                        <tr>
                            <td>{{ $certificates->firstItem() + $index }}</td>
                            <td><code>{{ $certificate->serial_number }}</code></td>
                            <td>{{ $certificate->product_name }}</td>
                            <td>{{ optional($certificate->owner)->full_name ?: '—' }}</td>
                            <td>{{ $certificate->metal_type ?: '—' }}</td>
                            <td>{{ $certificate->purity ?: '—' }}</td>
                            <td>{{ $certificate->weight !== null ? number_format($certificate->weight, 2) : '—' }}</td>
                            <td>{{ $certificate->issued_at ? $certificate->issued_at->format('Y-m-d') : '—' }}</td>
                            <td><span class="badge badge-{{ $status[1] }}">{{ $status[0] }}</span></td>
                            <td>
                                @can('jewelry.certificates.view')
                                    <a href="{{ route('admin.jewelry.certificates.show', $certificate->id) }}"
                                       class="btn btn-sm btn-outline-primary">نمایش</a>
                                @else
                                    —
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                @if(request()->query())
                                    شناسنامه‌ای با این فیلترها پیدا نشد.
                                @else
                                    هنوز شناسنامه‌ای ثبت نشده است.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection