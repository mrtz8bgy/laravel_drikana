@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <h3>استعلام اصالت شناسنامه</h3>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>سریال:</strong> {{ $certificate->serial_number }}</li>
        <li class="list-group-item"><strong>محصول:</strong> {{ $certificate->product_name }}</li>
        <li class="list-group-item"><strong>فلز:</strong> {{ $certificate->metal_type }}</li>
        <li class="list-group-item"><strong>مالک فعلی:</strong> {{ optional($certificate->currentOwner())->full_name ?? '-' }}</li>
        <li class="list-group-item"><strong>تاریخ صدور:</strong> {{ $certificate->issued_at?->format('Y/m/d') ?? '-' }}</li>
    </ul>

    @if($certificate->is_verified)
        <div class="alert alert-success">این شناسنامه معتبر است</div>
    @elseif($certificate->rejected_at)
        <div class="alert alert-danger">این شناسنامه رد شده است</div>
    @else
        <div class="alert alert-warning">در انتظار تأیید</div>
    @endif
</div>
@endsection
