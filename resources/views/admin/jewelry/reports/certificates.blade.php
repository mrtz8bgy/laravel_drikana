@extends('admin.jewelry.layouts.app')

@section('title', 'گزارشات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jewelry.dashboard') }}">داشبورد</a></li>
    <li class="breadcrumb-item active">گزارشات</li>
@endsection

@section('jewelry_content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>گزارشات</h4>
                </div>
                <div class="card-body">
                    <p>صفحه گزارشات در حال توسعه می‌باشد.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection