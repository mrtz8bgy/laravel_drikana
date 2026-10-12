@extends('layouts.app')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{--
    مدیریت دسته‌بندی مقالات
    ─────────────────────────────────────────────────────────────
    ایجاد، ویرایش و حذف دسته‌بندی. نامک (slug) در سرور ساخته و
    یکتاسازی می‌شود؛ هرگز مستقیماً از ورودی کاربر گرفته نمی‌شود.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('دسته‌بندی مقالات') }}</h3>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت به مقالات') }}
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="u-margin-0-padding-right-20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('افزودن دسته‌بندی جدید') }}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.articles.categories.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="cat_name">{{ __('نام دسته‌بندی') }} <span class="text-danger">*</span></label>
                        <input type="text" id="cat_name" name="name" class="form-control"
                               maxlength="191" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="cat_desc">{{ __('توضیحات') }}</label>
                        <textarea id="cat_desc" name="description" class="form-control"
                                  rows="3" maxlength="500">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="cat_sort">{{ __('ترتیب نمایش') }}</label>
                        <input type="number" id="cat_sort" name="sort_order" class="form-control"
                               min="0" max="9999" value="{{ old('sort_order', 0) }}">
                    </div>

                    <div class="form-group">
                        <label class="switch">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="slider round"></span>
                        </label>
                        <label class="u-margin-right-10px">{{ __('فعال') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="demo-pli-add"></i> {{ __('افزودن دسته‌بندی') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('دسته‌بندی‌های موجود') }}</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">{{ __('نام') }}</th>
                                <th width="20%">{{ __('نامک') }}</th>
                                <th width="10%" class="text-center">{{ __('ترتیب') }}</th>
                                <th width="12%" class="text-center">{{ __('وضعیت') }}</th>
                                <th width="23%">{{ __('عملیات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        @if($category->description)
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $category->slug }}</code></td>
                                    <td class="text-center">{{ $category->sort_order }}</td>
                                    <td class="text-center">
                                        @if($category->is_active)
                                            <span class="label label-success">{{ __('فعال') }}</span>
                                        @else
                                            <span class="label label-danger">{{ __('غیرفعال') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                           data-toggle="modal" data-target="#editCategory{{ $category->id }}">
                                            <i class="demo-pli-pen-5"></i> {{ __('ویرایش') }}
                                        </a>
                                        <a href="#"
                                           onclick="confirm_modal('{{ route('admin.articles.categories.delete', $category->id) }}'); return false;"
                                           class="btn btn-sm btn-danger">
                                            <i class="demo-pli-recycling"></i> {{ __('حذف') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        {{ __('هیچ دسته‌بندی تعریف نشده است.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- مودال‌های ویرایش --}}
@foreach($categories as $category)
    <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.articles.categories.update', $category->id) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ __('ویرایش دسته‌بندی') }}: {{ $category->name }}</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('نام دسته‌بندی') }}</label>
                            <input type="text" name="name" class="form-control"
                                   maxlength="191" required value="{{ $category->name }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('توضیحات') }}</label>
                            <textarea name="description" class="form-control" rows="3" maxlength="500">{{ $category->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('ترتیب نمایش') }}</label>
                            <input type="number" name="sort_order" class="form-control"
                                   min="0" max="9999" value="{{ $category->sort_order }}">
                        </div>

                        <div class="form-group">
                            <label class="switch">
                                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <label class="u-margin-right-10px">{{ __('فعال') }}</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('انصراف') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('ذخیره') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
