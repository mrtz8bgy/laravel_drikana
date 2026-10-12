@extends('frontend.layouts.app')

@section('meta_title', $currentCategory ? ($currentCategory->name . ' — ' . __('مقالات')) : __('مقالات طلا، جواهر و سنگ‌های قیمتی'))
@section('meta_description', __('مقالات آموزشی درباره طلا، جواهرات، سنگ‌های قیمتی، شناسنامه، اصالت‌سنجی و نگهداری اشیاء قیمتی.'))

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{--
    فهرست عمومی مقالات
    ─────────────────────────────────────────────────────────────
    تنها مقالات منتشرشده نمایش داده می‌شوند (فیلتر در کنترلر با
    scopePublished). جست‌وجو و مرتب‌سازی با parameter binding و
    فهرست سفید انجام می‌شود.
--}}

<div class="container u-padding-24px-15px">

    {{-- ============================================= --}}
    {{-- سرصفحه --}}
    {{-- ============================================= --}}
    <div class="row">
        <div class="col-sm-12">
            <h1 class="u-margin-bottom-6px">
                @if($currentCategory)
                    {{ $currentCategory->name }}
                @else
                    {{ __('مقالات و راهنماها') }}
                @endif
            </h1>
            <p class="text-muted">
                @if($currentCategory && $currentCategory->description)
                    {{ $currentCategory->description }}
                @else
                    {{ __('آموزش، راهنما و دانستنی‌های طلا، جواهر و سنگ‌های قیمتی') }}
                @endif
            </p>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- جست‌وجو و مرتب‌سازی --}}
    {{-- ============================================= --}}
    <div class="row u-margin-bottom-20px-2">
        <div class="col-sm-12">
            <form method="GET" action="{{ route('articles.index') }}" class="form-inline">
                <div class="form-group u-margin-left-8px">
                    <input type="text"
                           name="q"
                           class="form-control"
                           placeholder="{{ __('جست‌وجو در مقالات...') }}"
                           maxlength="100"
                           value="{{ e(request('q')) }}">
                </div>

                <div class="form-group u-margin-left-8px">
                    <select name="sort" class="form-control">
                        @foreach([
                            'latest'  => 'جدیدترین',
                            'oldest'  => 'قدیمی‌ترین',
                            'popular' => 'پربازدیدترین',
                            'title'   => 'بر اساس عنوان',
                        ] as $value => $label)
                            <option value="{{ $value }}" @if(request('sort') === $value) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('جست‌وجو') }}</button>
            </form>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- دسته‌بندی‌ها --}}
    {{-- ============================================= --}}
    @if($categories->count() > 0)
        <div class="row u-margin-bottom-24px">
            <div class="col-sm-12">
                <a href="{{ route('articles.index') }}"
                   class="btn btn-sm {{ !$currentCategory ? 'btn-primary' : 'btn-default' }}">
                    {{ __('همه') }}
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('articles.category', $category->slug) }}"
                       class="btn btn-sm {{ $currentCategory && $currentCategory->id === $category->id ? 'btn-primary' : 'btn-default' }}">
                        {{ $category->name }}
                        <span class="badge">{{ $category->published_articles_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row">

        {{-- ============================================= --}}
        {{-- فهرست مقالات --}}
        {{-- ============================================= --}}
        <div class="col-md-9">
            <div class="row">
                @forelse($articles as $article)
                    <div class="col-sm-6 col-md-4 u-margin-bottom-24px">
                        <div class="thumbnail u-height-100">

                            <a href="{{ $article->public_url }}">
                                @if($article->featured_image)
                                    <img class="u-height-170px-width-100-object-fit-cover" src="{{ $article->featured_image_url }}"
 alt="{{ $article->featured_image_alt ?: $article->title }}">
                                @else
                                    <div class="u-height-170px-background-f0f0f0-display-flex-align-items-b8b441">
                                        <span class="text-muted">{{ __('بدون تصویر') }}</span>
                                    </div>
                                @endif
                            </a>

                            <div class="caption">
                                @if($article->category)
                                    <a href="{{ route('articles.category', $article->category->slug) }}"
 class="label label-info u-font-weight-400">
                                        {{ $article->category->name }}
                                    </a>
                                    <br><br>
                                @endif

                                <h4 class="u-margin-top-0-font-size-15px-line-height-1-6">
                                    <a class="u-color-inherit" href="{{ $article->public_url }}">
                                        {{ \Illuminate\Support\Str::limit($article->title, 70) }}
                                    </a>
                                </h4>

                                <p class="text-muted u-font-size-13px">
                                    {{ \Illuminate\Support\Str::limit($article->excerpt ?: strip_tags($article->content), 90) }}
                                </p>

                                <small class="text-muted">
                                    <i class="fa fa-clock-o"></i>
                                    {{ $article->published_at ? $article->published_at->format('Y/m/d') : $article->created_at->format('Y/m/d') }}
                                    &nbsp;|&nbsp;
                                    <i class="fa fa-eye"></i> {{ number_format($article->views_count) }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            {{ __('هیچ مقاله‌ای یافت نشد.') }}
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- نوار کنار --}}
        {{-- ============================================= --}}
        <div class="col-md-3">

            @if($featured->count() > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ __('مقالات ویژه') }}</strong>
                    </div>
                    <div class="panel-body">
                        @foreach($featured as $item)
                            <div class="u-margin-bottom-12px-padding-bottom-12px-border-bottom-1p-24d51a">
                                <a class="u-font-size-13px-line-height-1-7" href="{{ $item->public_url }}">
                                    {{ \Illuminate\Support\Str::limit($item->title, 65) }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ $item->published_at ? $item->published_at->format('Y/m/d') : '' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ __('دسته‌بندی‌ها') }}</strong>
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled u-margin-0">
                        @foreach($categories as $category)
                            <li class="u-padding-5px-0">
                                <a href="{{ route('articles.category', $category->slug) }}">
                                    {{ $category->name }}
                                    <span class="badge">{{ $category->published_articles_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
