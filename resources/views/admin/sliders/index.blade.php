@extends('layouts.app')

@section('content')

{{--
    فهرست اسلایدرها
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو در پروژه وجود نداشت، در حالی که
    SliderController@index آن را برمی‌گرداند.
--}}

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('sliders.create') }}" class="btn btn-rounded btn-info pull-right">
            {{ __('Add New Slider') }}
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
        <h3 class="panel-title">{{ __('Sliders') }}</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="8%">#</th>
                        <th width="25%">{{ __('Photo') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th width="18%">{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $key => $slider)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if($slider->photo)
                                    <img class="u-max-height-70px-max-width-100" src="{{ asset($slider->photo) }}" alt="">
                                @endif
                            </td>
                            <td>{{ $slider->link }}</td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                            data-toggle="dropdown" type="button">
                                        {{ __('Actions') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ route('sliders.destroy', $slider->id) }}"
                                               onclick="return confirm('{{ __('Are you sure?') }}');">
                                                {{ __('Delete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('No data found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
