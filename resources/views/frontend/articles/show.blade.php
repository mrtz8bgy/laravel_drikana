@extends('frontend.layouts.app')

{{--
    ── نکته فنی مهم (رفع باگ) ──────────────────────────────────────
    سینتکس تک‌خطی `@section('name', $expression)` در Blade تنها با
    عبارات ساده درست کار می‌کند. وقتی عبارت شامل پرانتزهای تودرتو یا
    عملگر `?:` باشد، الگوی regex داخلی Blade دچار سوءتفسیر می‌شود و
    تعریف section ناتمام می‌ماند؛ نتیجه‌اش بازماندن یک output buffer
    و در نتیجه پاسخ ناقص است.

    بنابراین در اینجا از سینتکس بلوکی @section ... @endsection استفاده
    می‌شود که همیشه امن است.
    ───────────────────────────────────────────────────────────────
--}}
@section('meta_title')
    {{ $article->meta_title ?: $article->title }}
@endsection

@section('meta_description')
    {{ $article->meta_description ?: ($article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 155)) }}
@endsection

@section('meta_keywords')
    {{ $article->keywords }}
@endsection

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{--
    نمایش عمومی یک مقاله منتشرشده
    ─────────────────────────────────────────────────────────────
    ── نکته امنیتی مهم ─────────────────────────────────────────────
    محتوای مقاله با `{!! !!}` نمایش داده می‌شود. این کار تنها به این
    دلیل ایمن است که محتوا پیش از ذخیره با `HtmlSanitizer` پاک‌سازی
    شده و تگ‌های اسکریپت، iframe، object، رویدادهای جاوااسکریپت و
    آدرس‌های javascript: از آن حذف شده‌اند.

    هرگز نباید این الگو برای محتوایی به کار رود که پاک‌سازی نشده است.
    برای اطمینان، مسیر ذخیره مقاله در ArticleService همیشه از
    HtmlSanitizer عبور می‌کند.

    سایر فیلدها (عنوان، خلاصه، نام نویسنده) با روش استاندارد Blade
    یعنی {{ }} و با escape خودکار نمایش داده می‌شوند.
--}}

<div class="container u-padding-24px-15px">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            {{-- مسیر راهنما --}}
            <ol class="breadcrumb u-background-transparent-padding-right-0">
                <li><a href="{{ route('articles.index') }}">{{ __('مقالات') }}</a></li>
                @if($article->category)
                    <li><a href="{{ route('articles.category', $article->category->slug) }}">{{ $article->category->name }}</a></li>
                @endif
                <li class="active">{{ \Illuminate\Support\Str::limit($article->title, 40) }}</li>
            </ol>

            <article class="panel panel-default">
                <div class="panel-body">

                    <h1 class="u-margin-top-0-font-size-26px-line-height-1-6">{{ $article->title }}</h1>

                    <p class="text-muted u-font-size-13px">
                        @if($article->category)
                            <span class="label label-info u-font-weight-400">{{ $article->category->name }}</span>
                            &nbsp;
                        @endif

                        <i class="fa fa-user"></i> {{ $article->display_author }}
                        &nbsp;|&nbsp;
                        <i class="fa fa-calendar"></i>
                        {{ $article->published_at ? $article->published_at->format('Y/m/d') : $article->created_at->format('Y/m/d') }}
                        &nbsp;|&nbsp;
                        <i class="fa fa-eye"></i> {{ number_format($article->views_count) }}
                        {{ __('بازدید') }}
                    </p>

                    @if($article->featured_image)
                        <img src="{{ $article->featured_image_url }}"
 alt="{{ $article->featured_image_alt ?: $article->title }}"
 class="img-responsive u-width-100-margin-15px-0-20px">
                    @endif

                    @if($article->excerpt)
                        <div class="alert alert-info u-font-size-15px-line-height-1-9">
                            {{ $article->excerpt }}
                        </div>
                    @endif

                    {{-- محتوای پاک‌سازی‌شده مقاله --}}
                    <div class="article-content u-font-size-15px-line-height-2">
                        {!! $article->content !!}
                    </div>

                    @if($article->images->count() > 0)
                        <hr>
                        <h4>{{ __('تصاویر مقاله') }}</h4>
                        <div class="row">
                            @foreach($article->images as $image)
                                <div class="col-sm-6 u-margin-bottom-12px">
                                    <img src="{{ $image->url }}"
                                         alt="{{ $image->alt }}"
                                         class="img-responsive img-thumbnail">
                                    @if($image->caption)
                                        <small class="text-muted">{{ $image->caption }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($article->keywords)
                        <hr>
                        <p>
                            <strong>{{ __('برچسب‌ها') }}:</strong>
                            @foreach($article->keywords_array as $keyword)
                                <span class="label label-default">{{ trim($keyword) }}</span>
                            @endforeach
                        </p>
                    @endif

                </div>
            </article>

            {{-- ============================================= --}}
            {{-- مقالات مرتبط --}}
            {{-- ============================================= --}}
            @if($related->count() > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ __('مقالات مرتبط') }}</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @foreach($related as $item)
                                <div class="col-sm-6 col-md-3 u-margin-bottom-15px">
                                    <a href="{{ $item->public_url }}">
                                        @if($item->featured_image)
                                            <img class="u-height-100px-width-100-object-fit-cover" src="{{ $item->featured_image_url }}"
 alt="{{ $item->featured_image_alt ?: $item->title }}">
                                        @endif
                                        <h5 class="u-font-size-13px-line-height-1-6-margin-top-6px">
                                            {{ \Illuminate\Support\Str::limit($item->title, 55) }}
                                        </h5>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection
