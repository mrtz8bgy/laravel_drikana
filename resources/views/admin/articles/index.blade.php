@extends('layouts.app')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{--
    فهرست مقالات پنل مدیریت
    ─────────────────────────────────────────────────────────────
    دسترسی به این صفحه نیازمند مجوز `articles.view` یا
    `articles.view_own` است. دکمه‌های عملیات بر اساس مجوز کاربر
    نمایش داده می‌شوند؛ اعمال واقعی در سرور انجام می‌شود.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('مدیریت مقالات') }}</h3>

        <div class="pull-left">
            @if(Auth::user()->hasPermissionTo('articles.categories.manage'))
                <a href="{{ route('admin.articles.categories') }}" class="btn btn-default">
                    <i class="demo-pli-tag"></i> {{ __('دسته‌بندی‌ها') }}
                </a>
            @endif

            @if(Auth::user()->hasPermissionTo('articles.create'))
                <a href="{{ route('admin.articles.create') }}" class="btn btn-info">
                    <i class="demo-pli-add"></i> {{ __('مقاله جدید') }}
                </a>
            @endif
        </div>
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

{{-- ============================================= --}}
{{-- آمار --}}
{{-- ============================================= --}}
<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['draft']) }}</h2>
                <small>{{ __('پیش‌نویس') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-warning">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['pending_review']) }}</h2>
                <small>{{ __('در انتظار بررسی') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-success">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['published']) }}</h2>
                <small>{{ __('منتشرشده') }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel panel-danger">
            <div class="panel-body text-center">
                <h2 class="u-margin-0">{{ number_format($stats['archived']) }}</h2>
                <small>{{ __('بایگانی‌شده') }}</small>
            </div>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- فیلترها --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-body">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="form-inline">

            <div class="form-group u-margin-left-8px">
                <input type="text" name="search" class="form-control"
                       placeholder="{{ __('جست‌وجو در عنوان و خلاصه') }}"
                       maxlength="100"
                       value="{{ e(request('search')) }}">
            </div>

            <div class="form-group u-margin-left-8px">
                <select name="status" class="form-control">
                    <option value="">{{ __('همه وضعیت‌ها') }}</option>
                    @foreach([
                        'draft'          => 'پیش‌نویس',
                        'pending_review' => 'در انتظار بررسی',
                        'published'      => 'منتشرشده',
                        'archived'       => 'بایگانی‌شده',
                    ] as $value => $label)
                        <option value="{{ $value }}" @if(request('status') === $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group u-margin-left-8px">
                <select name="category_id" class="form-control">
                    <option value="">{{ __('همه دسته‌بندی‌ها') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if((string) request('category_id') === (string) $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="demo-pli-magnifier"></i> {{ __('جست‌وجو') }}
            </button>

            @if(request()->hasAny(['search', 'status', 'category_id']))
                <a href="{{ route('admin.articles.index') }}" class="btn btn-default">{{ __('پاک‌کردن') }}</a>
            @endif
        </form>
    </div>
</div>

{{-- ============================================= --}}
{{-- جدول مقالات --}}
{{-- ============================================= --}}
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ __('مقالات') }}
            <small class="text-muted">({{ $articles->total() }})</small>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">{{ __('عنوان') }}</th>
                        <th width="12%">{{ __('دسته‌بندی') }}</th>
                        <th width="12%">{{ __('نویسنده') }}</th>
                        <th width="11%" class="text-center">{{ __('وضعیت') }}</th>
                        <th width="10%" class="text-center">{{ __('بازدید') }}</th>
                        <th width="10%">{{ __('تاریخ') }}</th>
                        <th width="10%">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $key => $article)
                        <tr>
                            <td>{{ $articles->firstItem() + $key }}</td>

                            <td>
                                <strong>{{ \Illuminate\Support\Str::limit($article->title, 55) }}</strong>

                                @if($article->is_featured)
                                    <span class="label label-warning u-font-size-10px">{{ __('ویژه') }}</span>
                                @endif

                                <br>
                                <small class="text-muted">/{{ $article->slug }}</small>
                            </td>

                            <td>
                                <small>{{ optional($article->category)->name ?: '—' }}</small>
                            </td>

                            <td>
                                <small>{{ $article->display_author }}</small>
                            </td>

                            <td class="text-center">
                                <span class="label label-{{ $article->status_color }}">
                                    {{ $article->status_label }}
                                </span>
                            </td>

                            <td class="text-center">{{ number_format($article->views_count) }}</td>

                            <td>
                                <small>{{ $article->created_at ? $article->created_at->format('Y-m-d') : '—' }}</small>
                            </td>

                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" type="button">
                                        {{ __('عملیات') }} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">

                                        @if(Auth::user()->can('view', $article))
                                            <li>
                                                <a href="{{ route('admin.articles.show', $article->id) }}">
                                                    <i class="demo-pli-view-list"></i> {{ __('مشاهده') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::user()->can('update', $article))
                                            <li>
                                                <a href="{{ route('admin.articles.edit', $article->id) }}">
                                                    <i class="demo-pli-pen-5"></i> {{ __('ویرایش') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if($article->status === 'draft' && Auth::user()->hasPermissionTo('articles.submit'))
                                            <li>
                                                <a href="#" onclick="postAction('{{ route('admin.articles.submit', $article->id) }}'); return false;">
                                                    <i class="demo-pli-mail-send"></i> {{ __('ارسال برای بررسی') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if($article->status !== 'published' && Auth::user()->hasPermissionTo('articles.publish'))
                                            <li>
                                                <a href="#" onclick="postAction('{{ route('admin.articles.publish', $article->id) }}'); return false;"
                                                   class="text-success">
                                                    <i class="demo-pli-check"></i> {{ __('انتشار') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if($article->status === 'published' && Auth::user()->hasPermissionTo('articles.publish'))
                                            <li>
                                                <a href="#" onclick="postAction('{{ route('admin.articles.unpublish', $article->id) }}'); return false;"
                                                   class="text-warning">
                                                    <i class="demo-pli-cross"></i> {{ __('لغو انتشار') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::user()->can('delete', $article))
                                            <li>
                                                <a href="#" onclick="confirm_modal('{{ route('admin.articles.delete', $article->id) }}'); return false;"
                                                   class="text-danger">
                                                    <i class="demo-pli-recycling"></i> {{ __('حذف') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if($article->status === 'published')
                                            <li>
                                                <a href="{{ $article->public_url }}" target="_blank" rel="noopener">
                                                    <i class="demo-pli-external"></i> {{ __('مشاهده در سایت') }}
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                {{ __('هیچ مقاله‌ای یافت نشد.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $articles->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- فرم پنهان برای ارسال درخواست‌های POST (تغییر وضعیت) --}}
<form class="u-display-none" id="postActionForm" method="POST">
    @csrf
</form>

@endsection

@push('script')
<script>
    /**
     * ارسال یک درخواست POST از طریق فرم پنهان.
     *
     * چرا؟ عملیات «ارسال برای بررسی»، «انتشار» و «لغو انتشار» وضعیت را
     * تغییر می‌دهند، بنابراین باید با متد POST و همراه توکن CSRF انجام
     * شوند. استفاده از لینک GET برای این کار هم CSRF را می‌شکست و هم
     * اصل «GET نباید وضعیت را تغییر دهد» را نقض می‌کرد.
     *
     * @param {string} url
     */
    function postAction(url) {
        var form = document.getElementById('postActionForm');
        form.action = url;
        form.submit();
    }
</script>
@endpush
