@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ asset($detailedProduct->meta_img) }}">

    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
@endsection

@section('content')
    <section class="product-details-area gry-bg">
        <div class="container">
            <div class="bg-white">
                <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-6">
                        <div class="product-gal sticky-top d-flex flex-row-reverse">
                            @php
                                $photosArray = json_decode($detailedProduct->photos, true);
                            @endphp
                            @if(is_array($photosArray) && count($photosArray) > 0)
                                <div class="product-gal-img">
                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload" data-src="{{ asset($photosArray[0]) }}" xoriginal="{{ asset($photosArray[0]) }}" />
                                </div>
                                <div class="product-gal-thumb">
                                    <div class="xzoom-thumbs">
                                        @foreach ($photosArray as $key => $photo)
                                            <a href="{{ asset($photo) }}">
                                                <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom-gallery lazyload" width="80" data-src="{{ asset($photo) }}" @if($key == 0) xpreview="{{ asset($photo) }}" @endif>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="product-description-wrapper">
                            <h1 class="product-title mb-2 yekan">
                                {{ __($detailedProduct->name) }}
                            </h1>

                            <div class="row align-items-center my-1">
                                <div class="col-6">
                                    <div class="rating">
                                        @php
                                            $total = 0;
                                            if($detailedProduct->reviews && $detailedProduct->reviews->count() > 0) {
                                                $total = $detailedProduct->reviews->count();
                                            }
                                        @endphp
                                        <span class="star-rating">
                                            {{ renderStarRating($detailedProduct->rating) }}
                                        </span>
                                        <span class="rating-count ml-1">({{ $total }} {{__('reviews')}})</span>
                                    </div>
                                </div>
                                <div class="col-6 text-right">
                                    <ul class="inline-links inline-links--style-1">
                                        @php
                                            $qty = 0;
                                            if($detailedProduct->variant_product){
                                                if($detailedProduct->stocks && $detailedProduct->stocks->count() > 0){
                                                    foreach ($detailedProduct->stocks as $key => $stock) {
                                                        $qty += $stock->qty;
                                                    }
                                                }
                                            }
                                            else{
                                                $qty = $detailedProduct->current_stock;
                                            }
                                        @endphp
                                            <li>
                                                <button type="button" class="btn p-0 btn-link" style="font-size: 18px;" title="افزودن به علاقه مندی" onclick="addToWishList({{ $detailedProduct->id }})">
                                                    <i class="la la-heart-o d-inline-block nav-box-icon"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-link btn-icon-left p-0" style="font-size: 18px;" title="مقایسه" onclick="addToCompare({{ $detailedProduct->id }})">
                                                    <i class="la la-refresh d-inline-block nav-box-icon"></i>
                                                </button>
                                            </li>
                                        @if ($qty > 0)
                                            <li>
                                                <span class="badge badge-md badge-pill bg-green">{{__('In stock')}}</span>
                                            </li>
                                        @else
                                            <li>
                                                <span class="badge badge-md badge-pill bg-red">{{__('Out of stock')}}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="sold-by col-auto">
                                    <div class="product-description-label ml-2">{{__('Sold by')}}:</div>
                                    @if ($detailedProduct->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}">{{ $detailedProduct->user->shop->name }}</a>
                                    @else
                                        {{ config('app.name', 'Laravel') }}
                                    @endif
                                </div>
                            </div>

                            @if(home_price($detailedProduct->id) != home_discounted_price($detailedProduct->id))
                                <div class="row no-gutters mt-4 mb-4">
                                    <div class="col-12">
                                        <div class="product-price-old">
                                            <del>{{ home_price($detailedProduct->id) }}</del>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters mt-1">
                                    <div class="col-1">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-11">
                                        <div class="product-price">
                                            <strong>{{ home_discounted_price($detailedProduct->id) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row no-gutters mt-3">
                                    <div class="col-1">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-11">
                                        <div class="product-price">
                                            <strong>{{ home_discounted_price($detailedProduct->id) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

                                @if($detailedProduct->choice_options != null)
                                    @php
                                        $choiceOptions = json_decode($detailedProduct->choice_options, true);
                                    @endphp
                                    @if(is_array($choiceOptions) && count($choiceOptions) > 0)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                @foreach ($choiceOptions as $key => $choice)
                                                    @if(is_array($choice) && isset($choice['attribute_id']))
                                                        <div class="d-inline-block mr-3">
                                                            <div class="product-description-label mt-2 d-inline">{{ \App\Attribute::find($choice['attribute_id'])->name ?? '' }}:</div>
                                                            <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 d-inline">
                                                                @foreach ($choice['values'] as $key2 => $value)
                                                                    <li>
                                                                        <input type="radio" id="{{ $choice['attribute_id'] }}-{{ $value }}" name="attribute_id_{{ $choice['attribute_id'] }}" value="{{ $value }}" @if($key2 == 0) checked @endif>
                                                                        <label for="{{ $choice['attribute_id'] }}-{{ $value }}">{{ $value }}</label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @php
                                    $colorsArray = json_decode($detailedProduct->colors, true);
                                @endphp
                                @if(is_array($colorsArray) && count($colorsArray) > 0)
                                    <div class="row no-gutters mt-4">
                                        <div class="col-1">
                                            <div class="product-description-label mt-2">{{__('Color')}}:</div>
                                        </div>
                                        <div class="col-11">
                                            <ul class="list-inline checkbox-color mb-1">
                                                @foreach ($colorsArray as $key => $color)
                                                    <li>
                                                        <input type="radio" id="{{ $detailedProduct->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0) checked @endif>
                                                        <label style="background: {{ $color }};" for="{{ $detailedProduct->id }}-color-{{ $key }}" data-toggle="tooltip"></label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <div class="row no-gutters mt-2">
                                    <div class="col-1">
                                        <div class="product-description-label mt-2">{{__('Quantity')}}:</div>
                                    </div>
                                    <div class="col-11">
                                        <div class="product-quantity d-flex align-items-center">
                                            <div class="input-group input-group--style-2 pr-3" style="width: 200px;">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number" type="button" data-type="minus" data-field="quantity" disabled="disabled">
                                                        <i class="la la-minus"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" class="form-control input-number text-center" placeholder="1" value="1" min="1" max="10">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number" type="button" data-type="plus" data-field="quantity">
                                                        <i class="la la-plus"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="avialable-amount">(<span id="available-quantity">{{ $qty }}</span> {{__('available')}})</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters pb-3 d-none mt-4" id="chosen_price_div">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Total Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong id="chosen_price"></strong>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="d-table width-100 mt-3">
                                <div class="d-table-cell">
                                    @if ($qty > 0)
                                        <button type="button" class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy-now" onclick="buyNow()">
                                            <i class="la la-shopping-cart"></i> {{__('Buy Now')}}
                                        </button>
                                        <button type="button" class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2 add-to-cart" onclick="addToCart()">
                                            <i class="la la-shopping-cart"></i>
                                            <span class="d-none d-md-inline-block"> {{__('Add to cart')}}</span>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-styled btn-base-3 btn-icon-left strong-700" disabled>
                                            <i class="la la-cart-arrow-down"></i> {{__('ناموجود')}}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="row no-gutters mt-4">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{__('Share')}}:</div>
                                </div>
                                <div class="col-10">
                                    <div id="share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gry-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="product-desc-tab bg-white">
                        <div class="tabs tabs--style-2">
                            <ul class="nav nav-tabs justify-content-center sticky-top bg-white">
                                <li class="nav-item">
                                    <a href="#tab_default_1" data-toggle="tab" class="nav-link text-uppercase strong-600 active show">توضیحات</a>
                                </li>
                                @if($detailedProduct->video_link != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_2" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Video')}}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="#tab_default_4" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Reviews')}}</a>
                                </li>
                            </ul>

                            <div class="tab-content pt-0">
                                <div class="tab-pane active show" id="tab_default_1">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mw-100 overflow--hidden product-description">
                                                    <?php echo $detailedProduct->description; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_default_2">
                                    <div class="fluid-paragraph py-2">
                                        <div class="embed-responsive embed-responsive-16by9 mb-5">
                                            @if ($detailedProduct->video_provider == 'youtube' && $detailedProduct->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $detailedProduct->video_link)[1] }}"></iframe>
                                            @elseif ($detailedProduct->video_provider == 'dailymotion' && $detailedProduct->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                                            @elseif ($detailedProduct->video_provider == 'vimeo' && $detailedProduct->video_link != null)
                                                <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}" width="500" height="281" frameborder="0" allowfullscreen></iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_default_4">
                                    <div class="fluid-paragraph py-4">
                                        @if($detailedProduct->reviews && $detailedProduct->reviews->count() > 0)
                                            @foreach ($detailedProduct->reviews as $key => $review)
                                                <div class="block block-comment">
                                                    <div class="block-image">
                                                        <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($review->user->avatar_original) }}" class="rounded-circle lazyload">
                                                    </div>
                                                    <div class="block-body">
                                                        <div class="block-body-inner">
                                                            <div class="row no-gutters">
                                                                <div class="col">
                                                                    <h3 class="heading heading-6">
                                                                        <a href="javascript:;">{{ $review->user->name }}</a>
                                                                    </h3>
                                                                    <span class="comment-date">{{ date('d-m-Y', strtotime($review->created_at)) }}</span>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="rating text-right clearfix d-block">
                                                                        <span class="star-rating star-rating-sm float-right">
                                                                            @for ($i=0; $i < $review->rating; $i++)
                                                                                <i class="fa fa-star active"></i>
                                                                            @endfor
                                                                            @for ($i=0; $i < 5-$review->rating; $i++)
                                                                                <i class="fa fa-star"></i>
                                                                            @endfor
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="comment-text">{{ $review->comment }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center">{{ __('There have been no reviews for this product yet.') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#share').share({
                networks: ['facebook','twitter','linkedin','tumblr','in1','stumbleupon','digg'],
                theme: 'square'
            });
            getVariantPrice();
        });

        function CopyToClipboard(containerid) {
            if (document.selection) {
                var range = document.body.createTextRange();
                range.moveToElementText(document.getElementById(containerid));
                range.select().createTextRange();
                document.execCommand("Copy");
            } else if (window.getSelection) {
                var range = document.createRange();
                document.getElementById(containerid).style.display = "block";
                range.selectNode(document.getElementById(containerid));
                window.getSelection().addRange(range);
                document.execCommand("Copy");
                document.getElementById(containerid).style.display = "none";
            }
            showFrontendAlert('success', 'Copied');
        }

        function show_chat_modal(){
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }
    </script>
@endsection