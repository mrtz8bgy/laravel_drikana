@extends('layouts.app')

@section('content')

{{--
    فهرست بنرها
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو در پروژه وجود نداشت، در حالی که
    BannerController@index آن را برمی‌گرداند و روت home_banners به
    این متد اشاره می‌کرد. باز کردن صفحه بنرها خطای 500 می‌داد.
--}}

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('home_banners.create', 'home_banner_one') }}" class="btn btn-rounded btn-info pull-right">
            {{ __('Add New Banner') }}
        </a>
    </div>
</div>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ __('Banners') }}</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="8%">#</th>
                        <th width="20%">{{ __('Photo') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th width="20%">{{ __('Position') }}</th>
                        <th width="18%">{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $key => $banner)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if($banner->photo)
                                    <img class="u-max-height-70px-max-width-100" src="{{ asset($banner->photo) }}" alt="">
                                @endif
                            </td>
                            <td>{{ $banner->url }}</td>
                            <td><code>{{ $banner->position }}</code></td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                            data-toggle="dropdown" type="button">
                                        {{ __('Actions') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ route('home_banners.edit', $banner->id) }}">{{ __('Edit') }}</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                               onclick="confirm_modal('{{ route('home_banners.destroy', $banner->id) }}'); return false;">
                                                {{ __('Delete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">{{ __('No data found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
