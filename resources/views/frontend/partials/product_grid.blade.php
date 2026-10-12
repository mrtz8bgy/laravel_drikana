{{--
    ─────────────────────────────────────────────────────────────
    شبکه نمایش محصولات (partial قابل استفاده مجدد)
    ─────────────────────────────────────────────────────────────

    [رفع باگ — Stage 2] این partial وجود نداشت در حالی که
    HomeController@todays_deal_products آن را رندر می‌کرد؛ بنابراین
    فراخوانی AJAX بخش «پیشنهاد امروز» خطای 500 برمی‌گرداند.

    ورودی: $products — مجموعه محصولات

    این partial همان ساختار کارت محصول جاافتاده پروژه را استفاده
    می‌کند تا ظاهر سایت تغییر نکند.
--}}

@forelse($products as $key => $product)
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
        <div class="card card-product shop-cards shop-tech">
            <div class="card-body p-0">

                <div class="card-image">
                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                        <img class="img-fit lazyload mx-auto"
                             src="{{ asset('frontend/images/placeholder.jpg') }}"
                             data-src="{{ asset($product->featured_img) }}"
                             alt="{{ __($product->name) }}">
                    </a>
                </div>

                <div class="p-md-3 p-2">
                    <div class="price-box">
                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                            <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                        @endif
                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                    </div>

                    <div class="star-rating star-rating-sm mt-1">
                        {{ renderStarRating($product->rating) }}
                    </div>

                    <h2 class="product-title p-0 text-truncate-2">
                        <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                    </h2>
                </div>

            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <p class="text-center text-muted">{{ __('محصولی یافت نشد.') }}</p>
    </div>
@endforelse
