<?php

Route::prefix('v1/auth')->group(function () {
    // [امنیت — محدودسازی نرخ] گروه `api` به‌طور پیش‌فرض ۶۰ درخواست در
    // دقیقه اجازه می‌دهد. برای «ورود» این عدد سخاوتمندانه است؛ ۶۰ تلاش
    // رمز در دقیقه یعنی حمله دیکشنری عملی. محدودیت سخت‌گیرانه‌تر روی
    // خود مسیر، جای گروه را نمی‌گیرد بلکه سقف پایین‌تری می‌گذارد.
    Route::post('login', 'Api\AuthController@login')->middleware('throttle:10,1');
    Route::post('signup', 'Api\AuthController@signup');
    Route::post('social-login', 'Api\AuthController@socialLogin');
    // [امنیت — محدودسازی نرخ] هر درخواست یک ایمیل واقعی می‌فرستد؛
    // بدون محدودسازی، Email Bombing و بدنام‌شدن دامنه ممکن است.
    Route::post('password/create', 'Api\PasswordResetController@create')
        ->name('api.auth.password.reset')
        ->middleware('throttle:5,1');
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
});

Route::prefix('v1')->group(function () {
    Route::apiResource('banners', 'Api\BannerController')->only('index')
        ->names(['index' => 'api.banners.index']);

    Route::get('brands/top', 'Api\BrandController@top');
    Route::apiResource('brands', 'Api\BrandController')->only('index')
        // پیشوند api. الزامی است: نام brands.index در بخش وب هم استفاده
        // می‌شود (admin/brands) و ثبت دوباره همان نام، روت وب را سایه می‌انداخت.
        ->names(['index' => 'api.brands.index']);

    Route::apiResource('business-settings', 'Api\BusinessSettingController')->only('index')
        ->names(['index' => 'api.business-settings.index']);

    Route::get('categories/featured', 'Api\CategoryController@featured');
    Route::get('categories/home', 'Api\CategoryController@home');
    Route::apiResource('categories', 'Api\CategoryController')->only('index')
        ->names(['index' => 'api.categories.index']);
    Route::get('sub-categories/{id}', 'Api\SubCategoryController@index')->name('api.subCategories.index');

    Route::apiResource('colors', 'Api\ColorController')->only('index')
        ->names(['index' => 'api.colors.index']);

    Route::apiResource('currencies', 'Api\CurrencyController')->only('index')
        ->names(['index' => 'api.currencies.index']);

    Route::apiResource('customers', 'Api\CustomerController')->only('show')
        ->names(['show' => 'api.customers.show']);

    Route::apiResource('general-settings', 'Api\GeneralSettingController')->only('index')
        ->names(['index' => 'api.general-settings.index']);

    Route::apiResource('home-categories', 'Api\HomeCategoryController')->only('index')
        ->names(['index' => 'api.home-categories.index']);

    Route::get('purchase-history/{id}', 'Api\PurchaseHistoryController@index')->middleware('auth:api');
    Route::get('purchase-history-details/{id}', 'Api\PurchaseHistoryDetailController@index')->name('api.purchaseHistory.details')->middleware('auth:api');

    Route::get('products/admin', 'Api\ProductController@admin');
    Route::get('products/seller', 'Api\ProductController@seller');
    Route::get('products/category/{id}', 'Api\ProductController@category')->name('api.products.category');
    Route::get('products/sub-category/{id}', 'Api\ProductController@subCategory')->name('api.products.subCategory');
    Route::get('products/sub-sub-category/{id}', 'Api\ProductController@subSubCategory')->name('api.products.subSubCategory');
    Route::get('products/brand/{id}', 'Api\ProductController@brand')->name('api.products.brand');
    Route::get('products/todays-deal', 'Api\ProductController@todaysDeal');
    Route::get('products/flash-deal', 'Api\ProductController@flashDeal');
    Route::get('products/featured', 'Api\ProductController@featured');
    Route::get('products/best-seller', 'Api\ProductController@bestSeller');
    Route::get('products/related/{id}', 'Api\ProductController@related')->name('api.products.related');
    Route::get('products/top-from-seller/{id}', 'Api\ProductController@topFromSeller')->name('api.products.topFromSeller');
    Route::get('products/search', 'Api\ProductController@search');
    Route::post('products/variant/price', 'Api\ProductController@variantPrice');
    Route::get('products/home', 'Api\ProductController@home');
    Route::apiResource('products', 'Api\ProductController')->except(['store', 'update', 'destroy'])
        ->names([
            'index' => 'api.products.index',
            'show'  => 'api.products.show',
        ]);

    Route::get('carts/{id}', 'Api\CartController@index')->middleware('auth:api');
    Route::post('carts/add', 'Api\CartController@add')->middleware('auth:api');
    Route::post('carts/change-quantity', 'Api\CartController@changeQuantity')->middleware('auth:api');
    Route::apiResource('carts', 'Api\CartController')->only('destroy')
        ->names(['destroy' => 'api.carts.destroy'])
        ->middleware('auth:api');

    Route::get('reviews/product/{id}', 'Api\ReviewController@index')->name('api.reviews.index');

    Route::get('shop/user/{id}', 'Api\ShopController@shopOfUser')->middleware('auth:api');
    Route::get('shops/details/{id}', 'Api\ShopController@info')->name('api.shops.info');
    Route::get('shops/products/all/{id}', 'Api\ShopController@allProducts')->name('api.shops.allProducts');
    Route::get('shops/products/top/{id}', 'Api\ShopController@topSellingProducts')->name('api.shops.topSellingProducts');
    Route::get('shops/products/featured/{id}', 'Api\ShopController@featuredProducts')->name('api.shops.featuredProducts');
    Route::get('shops/products/new/{id}', 'Api\ShopController@newProducts')->name('api.shops.newProducts');
    Route::get('shops/brands/{id}', 'Api\ShopController@brands')->name('api.shops.brands');
    Route::apiResource('shops', 'Api\ShopController')->only('index')
        ->names(['index' => 'api.shops.index']);

    Route::apiResource('sliders', 'Api\SliderController')->only('index')
        ->names(['index' => 'api.sliders.index']);

    Route::get('wishlists/{id}', 'Api\WishlistController@index')->middleware('auth:api');
    Route::post('wishlists/check-product', 'Api\WishlistController@isProductInWishlist')->middleware('auth:api');
    Route::apiResource('wishlists', 'Api\WishlistController')->except(['index', 'update', 'show'])
        ->names([
            'store'   => 'api.wishlists.store',
            'destroy' => 'api.wishlists.destroy',
        ])
        ->middleware('auth:api');

    Route::apiResource('settings', 'Api\SettingsController')->only('index')
        ->names(['index' => 'api.settings.index']);

    Route::get('policies/seller', 'Api\PolicyController@sellerPolicy')->name('api.policies.seller');
    Route::get('policies/support', 'Api\PolicyController@supportPolicy')->name('api.policies.support');
    Route::get('policies/return', 'Api\PolicyController@returnPolicy')->name('api.policies.return');

    Route::get('user/info/{id}', 'Api\UserController@info')->middleware('auth:api');
    Route::post('user/info/update', 'Api\UserController@updateName')->middleware('auth:api');
    Route::post('user/shipping/update', 'Api\UserController@updateShippingAddress')->middleware('auth:api');

    Route::post('coupon/apply', 'Api\CouponController@apply')->middleware('auth:api');

    Route::post('payments/pay/stripe', 'Api\StripeController@processPayment')->middleware('auth:api');
    Route::post('payments/pay/paypal', 'Api\PaypalController@processPayment')->middleware('auth:api');
    Route::post('payments/pay/cod', 'Api\PaymentController@cashOnDelivery')->middleware('auth:api');

    Route::post('order/store', 'Api\OrderController@store')->middleware('auth:api');
});

Route::fallback(function() {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
