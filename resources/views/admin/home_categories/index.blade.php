@extends('layouts.app')

@section('content')

{{--
    فهرست دسته‌بندی‌های صفحه اصلی
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو در پروژه وجود نداشت، در حالی که
    HomeCategoryController@index آن را برمی‌گرداند؛ بنابراین باز کردن
    صفحه «دسته‌بندی‌های خانه» در پنل مدیریت با خطای 500 متوقف می‌شد.
--}}

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('home_categories.create') }}" class="btn btn-rounded btn-info pull-right">
            {{ __('Add New Home Category') }}
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
        <h3 class="panel-title">{{ __('Home Categories') }}</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="8%">#</th>
                        <th>{{ __('Name') }}</th>
                        <th width="15%">{{ __('Order') }}</th>
                        <th width="18%">{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($home_categories as $key => $category)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->order }}</td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                            data-toggle="dropdown" type="button">
                                        {{ __('Actions') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ route('home_categories.edit', $category->id) }}">{{ __('Edit') }}</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                               onclick="confirm_modal('{{ route('home_categories.destroy', $category->id) }}'); return false;">
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
