@extends('layouts.app')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{-- پیش‌نمایش مقاله در پنل مدیریت --}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('پیش‌نمایش مقاله') }}</h3>
        <div class="pull-left">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-default">
                <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
            </a>
            @if(Auth::user()->can('update', $article))
                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-primary">
                    <i class="demo-pli-pen-5"></i> {{ __('ویرایش') }}
                </a>
            @endif
            @if($article->status === 'published')
                <a href="{{ $article->public_url }}" target="_blank" rel="noopener" class="btn btn-info">
                    <i class="demo-pli-external"></i> {{ __('مشاهده در سایت') }}
                </a>
            @endif
        </div>
    </div>
</div>

<br>

<div class="row">
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-body">

                <h2 class="u-margin-top-0">{{ $article->title }}</h2>

                <p class="text-muted">
                    <span class="label label-{{ $article->status_color }}">{{ $article->status_label }}</span>
                    &nbsp;|&nbsp; {{ __('نویسنده') }}: {{ $article->display_author }}
                    &nbsp;|&nbsp; {{ __('بازدید') }}: {{ number_format($article->views_count) }}
                </p>

                @if($article->excerpt)
                    <blockquote>{{ $article->excerpt }}</blockquote>
                @endif

                @if($article->featured_image)
                    <img src="{{ $article->featured_image_url }}"
 alt="{{ $article->featured_image_alt }}"
 class="img-responsive u-margin-bottom-15px">
                @endif

                {{--
                    محتوای مقاله با {!! !!} نمایش داده می‌شود زیرا در سرور
                    با HtmlSanitizer پاک‌سازی شده و تنها تگ‌های مجاز باقی
                    مانده‌اند. Sanitizer پیش از ذخیره در ArticleService
                    اجرا می‌شود؛ بنابراین اینجا محتوای مورد اعتماد است.
                --}}
                <div class="article-content">
                    {!! $article->content !!}
                </div>

                @if($article->images->count() > 0)
                    <hr>
                    <h4>{{ __('تصاویر') }}</h4>
                    <div class="row">
                        @foreach($article->images as $image)
                            <div class="col-sm-4 u-margin-bottom-10px-2">
                                <img src="{{ $image->url }}" alt="{{ $image->alt }}" class="img-responsive img-thumbnail">
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('اطلاعات مقاله') }}</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><th width="40%">{{ __('شناسه') }}</th><td>#{{ $article->id }}</td></tr>
                    <tr><th>{{ __('نامک') }}</th><td><code>{{ $article->slug }}</code></td></tr>
                    <tr><th>{{ __('دسته‌بندی') }}</th><td>{{ optional($article->category)->name ?: '—' }}</td></tr>
                    <tr><th>{{ __('تاریخ ایجاد') }}</th><td>{{ $article->created_at }}</td></tr>
                    <tr><th>{{ __('تاریخ انتشار') }}</th><td>{{ $article->published_at ?: '—' }}</td></tr>
                    <tr><th>{{ __('منتشرکننده') }}</th><td>{{ optional($article->publisher)->name ?: '—' }}</td></tr>
                    <tr><th>{{ __('ویژه') }}</th><td>{{ $article->is_featured ? __('بله') : __('خیر') }}</td></tr>
                </table>

                @if($article->keywords)
                    <h5>{{ __('کلمات کلیدی') }}</h5>
                    @foreach($article->keywords_array as $keyword)
                        <span class="label label-default">{{ trim($keyword) }}</span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
