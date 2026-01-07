{{-- resources/views/jewelry/layouts/app.blade.php --}}
@extends('frontend.layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('jewelry.partials.sidebar')
            </div>
            <div class="col-lg-9">
                @yield('jewelry_content')
            </div>
        </div>
    </div>
</div>
@endsection