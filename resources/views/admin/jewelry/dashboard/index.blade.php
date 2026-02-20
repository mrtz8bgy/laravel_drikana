{{-- resources/views/admin/jewelry/dashboard.blade.php --}}
@extends('admin.jewelry.layouts.app')

@section('jewelry_content')
<h1> پیشخوان اشیاء قیمتی</h1>

{{-- مثال آمار و اطلاعات کلی --}}
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card p-3">
            <h5>تعداد مالکان</h5>
            <p>{{ \App\Models\JewelryOwner::where('user_id', auth()->id())->count() }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>تعداد شناسنامه‌ها</h5>
            <p>{{ \App\Models\JewelryCertificate::whereHas('owner', fn($q) => $q->where('user_id', auth()->id()))->count() }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>سفارشات تعمیر فعال</h5>
            <p>{{ \App\Models\RepairOrder::where('user_id', auth()->id())->whereIn('status',['در انتظار','در حال تعمیر'])->count() }}</p>
        </div>
    </div>
</div>
@endsection
