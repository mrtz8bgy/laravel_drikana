@extends('admin.jewelry.layouts.app')

@section('title', 'گزارش مالی')

@section('jewelry_content')

{{--
    گزارش مالی انتقالات (فروش‌های ثبت‌شده)
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] مسیر ویو در کنترلر اشتباه بود و صفحه گزارش
    مالی با خطای 500 باز نمی‌شد.

    توجه: این گزارش تنها انتقالاتی را نشان می‌دهد که مبلغ اعلامی
    ثبت‌شده دارند و «مبنای رسمی مالی» نیستند.
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">گزارش مالی انتقالات</h4>
    <a href="{{ route('admin.jewelry.dashboard') }}" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body">
            <h3 class="mb-0">{{ number_format($financialStats['total_sales']) }}</h3>
            <small class="text-muted">تعداد فروش</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body">
            <h3 class="mb-0 text-success">{{ number_format($financialStats['total_revenue']) }}</h3>
            <small class="text-muted">مجموع مبالغ</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body">
            <h3 class="mb-0">{{ number_format($financialStats['average_price']) }}</h3>
            <small class="text-muted">میانگین</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body">
            <h3 class="mb-0">{{ number_format($financialStats['max_price']) }}</h3>
            <small class="text-muted">بیشترین</small>
        </div></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        {{-- این ویو در حال حاضر روت مستقیمی ندارد؛ ارجاع نام‌دارِ محافظت‌شده
     تا اگر روزی به صفحه وصل شد، نبودِ روت باعث خطای ۵۰۰ نشود. --}}
        <form method="GET" action="{{ Route::has('admin.jewelry.reports.financial') ? route('admin.jewelry.reports.financial') : url()->current() }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">از تاریخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ e(request('date_from')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">تا تاریخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ e(request('date_to')) }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
        </form>
    </div>
</div>

@if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
    <div class="card mb-3">
        <div class="card-header">درآمد شش ماه گذشته</div>
        <div class="card-body">
            <table class="table table-sm mb-0">
                <thead><tr><th>ماه</th><th>تعداد</th><th>مجموع مبلغ</th></tr></thead>
                <tbody>
                    @foreach($monthlyRevenue as $row)
                        <tr>
                            <td>{{ $row->month }}</td>
                            <td>{{ number_format($row->count) }}</td>
                            <td>{{ number_format($row->revenue) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>قطعه</th>
                    <th>خریدار</th>
                    <th>مبلغ</th>
                    <th>تاریخ انتقال</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $key => $sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $key }}</td>
                        <td><small>{{ $sale->certificate ? $sale->certificate->serial_number : '—' }}</small></td>
                        <td><small>{{ optional($sale->toOwner)->full_name ?: '—' }}</small></td>
                        <td><small>{{ number_format($sale->price) }}</small></td>
                        <td><small>{{ $sale->transfer_date }}</small></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">داده‌ای یافت نشد.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">{{ $sales->appends(request()->query())->links() }}</div>
    </div>
</div>

<div class="alert alert-warning mt-3 u-font-size-13px">
    این گزارش صرفاً مبالغ اعلامی ثبت‌شده در سیستم را نشان می‌دهد و مبنای رسمی مالی یا مالیاتی نیست.
</div>

@endsection
