<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
// ... سایر use ها

// سپس بقیه روت‌های شما
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ============================================
// بخش دمو (Demo) — حذف شده به دلایل امنیتی
// ============================================
//
// [امنیت - CRITICAL] روترهای قبلی زیر بدون هیچ احراز هویتی قابل فراخوانی بودند
// و DemoController@cron_1 با یک درخواست GET تمام جداول دیتابیس را دراپ می‌کرد:
//
//     Route::get('/demo/cron_1', 'DemoController@cron_1');
//     Route::get('/demo/cron_2', 'DemoController@cron_2');
//
// علت شکست محافظ DEMO_MODE در DemoController نیز آن بود که در سازنده (constructor)
// دستور `return false;` صرفاً از سازنده خارج می‌شد و هیچ‌گاه جلوی اجرای اکشن را
// نمی‌گرفت. علاوه بر آن فایل موردنیاز `demo.sql` در مخزن وجود ندارد؛ لذا اجرای
// cron_1 فقط منجر به «دراپ کامل داده‌ها» و سپس خطا می‌شد.
//
// تصمیم: حذف کامل روترها و حذف فایل DemoController.php.
// برای ریست محیط دمو در آینده باید یک Artisan Command محافظت‌شده (فقط local)
// ساخته شود، نه یک اندپوینت HTTP عمومی.

// ============================================
// احراز هویت و کاربران (Authentication & Users)
// ============================================
// توجه: `Auth::routes()` مسیرهای ثبت‌نام را بدون محدودسازی نرخ ثبت
// می‌کند. چون «ثبت‌نام» در این سامانه یک پیامک تأیید می‌فرستد، بدون
// محدودسازی می‌توان اعتبار پنل پیامک را با اسکریپت ساده سوزاند
// (SMS Bombing). محدودسازی در کنترلر و تنها روی متد `register` اعمال
// شده است تا صفحات GET نمایش فرم، بی‌دلیل محدود نشوند.
Auth::routes(['verify' => true]);
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('/language', 'LanguageController@changeLanguage')->name('language.change');
Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

// ورود با شبکه‌های اجتماعی (Social Login)
Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

// صفحات ورود و ثبت‌نام کاربران
Route::get('/users/login', 'HomeController@login')->name('user.login');
Route::get('/users/registration', 'HomeController@registration')->name('user.registration');
// [امنیت — محدودسازی نرخ] این مسیر رمز عبور را با Hash::check بررسی
// می‌کند؛ یعنی یک «مسیر ورود» است، نه فقط نمایش سبد خرید. بدون
// محدودسازی، حمله دیکشنری و شمارش کاربران (اختلاف پاسخ برای ایمیل
// موجود و ناموجود) ممکن بود.
Route::post('/users/login/cart', 'HomeController@cart_login')
     ->name('cart.login.submit')
     ->middleware('throttle:10,1');

// routes/web.php

// برای کاربران احراز هویت شده
Route::middleware(['auth'])->group(function () {
    // نمایش فرم تأیید کد
    Route::get('/verification', 'OTPVerificationController@verification')
         ->name('verification');
    
    // تأیید کد
    // نام این روت‌ها با پیشوند «phone» مشخص شده است.
    //
    // ── چرا؟ (رفع تداخل نام) ───────────────────────────────────────
    // دستور `Auth::routes(['verify' => true])` در همین فایل، روت‌های
    // تأیید ایمیل لاراول را با نام‌های `verification.verify` و
    // `verification.resend` ثبت می‌کند. اگر این دو روت هم همان نام‌ها را
    // داشته باشند، یکی روی دیگری سایه می‌افتد و فراخوانی
    // `route('verification.verify')` می‌تواند به مسیر اشتباه برود.
    // اکنون نام‌ها تفکیک شده‌اند: ایمیل از نام‌های پیش‌فرض لاراول و
    // تلفن از پیشوند phone استفاده می‌کند.
    // [امنیت — محدودسازی نرخ] این دو روت، نسخه «احراز‌شده» همان
    // مسیرهای OTP هستند: POST /verification/verify کد را بررسی می‌کند و
    // POST /verification/resend یک پیامک تازه می‌فرستد. بدون محدودسازی،
    // کد کوتاه با آزمون و خطا حدس‌زدنی است و اعتبار پنل پیامک
    // می‌سوزد. کلید محدودسازی «آی‌پی + نام مسیر» است.
    Route::post('/verification/verify', 'OTPVerificationController@verify_phone')
         ->name('verification.phone.verify')
         ->middleware('throttle:10,1');

    // ارسال مجدد کد
    Route::post('/verification/resend', 'OTPVerificationController@resend_verification_code')
         ->name('verification.phone.code_resend')
         ->middleware('throttle:3,1');
});

// ============================================
// دسته‌بندی‌ها و زیردسته‌ها (Categories & Subcategories)
// ============================================
Route::post('/subcategories/get_subcategories_by_category', 'SubCategoryController@get_subcategories_by_category')->name('subcategories.get_subcategories_by_category');
Route::post('/subsubcategories/get_subsubcategories_by_subcategory', 'SubSubCategoryController@get_subsubcategories_by_subcategory')->name('subsubcategories.get_subsubcategories_by_subcategory');
Route::post('/subsubcategories/get_brands_by_subsubcategory', 'SubSubCategoryController@get_brands_by_subsubcategory')->name('subsubcategories.get_brands_by_subsubcategory');
Route::post('/subsubcategories/get_attributes_by_subsubcategory', 'SubSubCategoryController@get_attributes_by_subsubcategory')->name('subsubcategories.get_attributes_by_subsubcategory');

// ============================================
// صفحه اصلی (Home Page)
// ============================================
Route::get('/', 'HomeController@index')->name('home');

// بخش‌های صفحه اصلی (AJAX)
Route::post('/home/section/featured', 'HomeController@load_featured_section')->name('home.section.featured');
Route::post('/home/section/best_selling', 'HomeController@load_best_selling_section')->name('home.section.best_selling');
Route::post('/home/section/home_categories', 'HomeController@load_home_categories_section')->name('home.section.home_categories');
Route::post('/home/section/best_sellers', 'HomeController@load_best_sellers_section')->name('home.section.best_sellers');

// منوی دسته‌بندی (AJAX)
Route::post('/category/nav-element-list', 'HomeController@get_category_items')->name('category.elements');

// صفحه جزئیات پیش‌نهاد شگفت‌انگیز (Flash Deal)
Route::get('/flash-deal/{slug}', 'HomeController@flash_deal_details')->name('flash-deal-details');

// نقشه سایت (Sitemap)
Route::get('/sitemap.xml', function(){
    return base_path('sitemap.xml');
});

// ============================================
// محصولات مشتری (Customer Products)
// ============================================
Route::get('/customer-products', 'CustomerProductController@customer_products_listing')->name('customer.products');
Route::get('/customer-products?subsubcategory={subsubcategory_slug}', 'CustomerProductController@search')->name('customer_products.subsubcategory');
Route::get('/customer-products?subcategory={subcategory_slug}', 'CustomerProductController@search')->name('customer_products.subcategory');
Route::get('/customer-products?category={category_slug}', 'CustomerProductController@search')->name('customer_products.category');
Route::get('/customer-products?city={city_id}', 'CustomerProductController@search')->name('customer_products.city');
Route::get('/customer-products?q={search}', 'CustomerProductController@search')->name('customer_products.search');
Route::get('/customer-product/{slug}', 'CustomerProductController@customer_product')->name('customer.product');
Route::get('/customer-packages', 'HomeController@premium_package_index')->name('customer_packages_list_show');

// ============================================
// محصولات و فروشگاه‌ها (Products & Shops)
// ============================================
Route::get('/product/{slug}', 'HomeController@product')->name('product');
Route::get('/page/{slug}', 'PageController@show_custom_page')->name('custom-pages.show_custom_page');
Route::get('/products', 'HomeController@listing')->name('products');
Route::get('/search?category={category_slug}', 'HomeController@search')->name('products.category');
Route::get('/search?subcategory={subcategory_slug}', 'HomeController@search')->name('products.subcategory');
Route::get('/search?subsubcategory={subsubcategory_slug}', 'HomeController@search')->name('products.subsubcategory');
Route::get('/search?brand={brand_slug}', 'HomeController@search')->name('products.brand');
Route::post('/product/variant_price', 'HomeController@variant_price')->name('products.variant_price');
Route::get('/shops/visit/{slug}', 'HomeController@shop')->name('shop.visit');
Route::get('/shops/visit/{slug}/{type}', 'HomeController@filter_shop')->name('shop.visit.type');

// ============================================
// سبد خرید (Cart)
// ============================================
Route::get('/cart', 'CartController@index')->name('cart');
Route::post('/cart/nav-cart-items', 'CartController@updateNavCart')->name('cart.nav_cart');
Route::post('/cart/show-cart-modal', 'CartController@showCartModal')->name('cart.showCartModal');
Route::post('/cart/addtocart', 'CartController@addToCart')->name('cart.addToCart');
Route::post('/cart/removeFromCart', 'CartController@removeFromCart')->name('cart.removeFromCart');
Route::post('/cart/updateQuantity', 'CartController@updateQuantity')->name('cart.updateQuantity');

// ============================================
// پرداخت و تسویه‌حساب (Checkout & Payment)
// ============================================
Route::group(['middleware' => ['checkout']], function(){
    Route::get('/checkout', 'CheckoutController@get_shipping_info')->name('checkout.shipping_info');
    Route::any('/checkout/delivery_info', 'CheckoutController@store_shipping_info')->name('checkout.store_shipping_infostore');
    Route::post('/checkout/payment_select', 'CheckoutController@store_delivery_info')->name('checkout.store_delivery_info');
});

Route::get('/checkout/order-confirmed', 'CheckoutController@order_confirmed')->name('order_confirmed');
Route::any('/checkout/payment', 'CheckoutController@checkout')->name('payment.checkout');
Route::post('/get_pick_ip_points', 'HomeController@get_pick_ip_points')->name('shipping_info.get_pick_ip_points');
Route::get('/checkout/payment_select', 'CheckoutController@get_payment_info')->name('checkout.payment_info');
Route::post('/checkout/apply_coupon_code', 'CheckoutController@apply_coupon_code')->name('checkout.apply_coupon_code');
Route::post('/checkout/remove_coupon_code', 'CheckoutController@remove_coupon_code')->name('checkout.remove_coupon_code');

// درگاه پرداخت پی‌پال (PayPal)
Route::get('/paypal/payment/done', 'PaypalController@getDone')->name('payment.done');
Route::get('/paypal/payment/cancel', 'PaypalController@getCancel')->name('payment.cancel');

// درگاه پرداخت زرین‌پال (Zarinpal)
Route::get('/zarinpal/back_url', 'ZarinpalController@back_url')->name('payment.backurl');

// درگاه پرداخت SSLCOMMERZ
Route::get('/sslcommerz/pay', 'PublicSslCommerzPaymentController@index');
Route::POST('/sslcommerz/success', 'PublicSslCommerzPaymentController@success');
Route::POST('/sslcommerz/fail', 'PublicSslCommerzPaymentController@fail');
Route::POST('/sslcommerz/cancel', 'PublicSslCommerzPaymentController@cancel');
Route::POST('/sslcommerz/ipn', 'PublicSslCommerzPaymentController@ipn');

// درگاه پرداخت استرایپ (Stripe)
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');

// ============================================
// مقایسه محصولات (Compare)
// ============================================
Route::get('/compare', 'CompareController@index')->name('compare');
Route::get('/compare/reset', 'CompareController@reset')->name('compare.reset');
Route::post('/compare/addToCompare', 'CompareController@addToCompare')->name('compare.addToCompare');

// ============================================
# عضویت در خبرنامه (Subscribers)
// ============================================
Route::resource('subscribers','SubscriberController');

// ============================================
# برندها، دسته‌بندی‌ها و جستجو (Brands, Categories & Search)
// ============================================
Route::get('/brands', 'HomeController@all_brands')->name('brands.all');
Route::get('/categories', 'HomeController@all_categories')->name('categories.all');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/search?q={search}', 'HomeController@search')->name('suggestion.search');
Route::post('/ajax-search', 'HomeController@ajax_search')->name('search.ajax');
// [حذف امنیتی — C-04] مسیر POST /config_content حذف شد.
// این مسیر بدون احراز هویت و بدون توکن CSRF به تابعی می‌رسید که
// file_put_contents را روی مسیر دلخواه اجرا می‌کرد (نوشتن وب‌شل).
// جزئیات: docs/security-audit.md

// ============================================
# صفحات سیاست‌ها و قوانین (Policy Pages)
// ============================================
Route::get('/sellerpolicy', 'HomeController@sellerpolicy')->name('sellerpolicy');
Route::get('/returnpolicy', 'HomeController@returnpolicy')->name('returnpolicy');
Route::get('/supportpolicy', 'HomeController@supportpolicy')->name('supportpolicy');
Route::get('/terms', 'HomeController@terms')->name('terms');
Route::get('/privacypolicy', 'HomeController@privacypolicy')->name('privacypolicy');
Route::get('/contact-us', 'HomeController@contact')->name('contact');

// ============================================
# بخش کاربران (User Area)
// ============================================
Route::group(['middleware' => ['user']], function(){
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::post('/customer/update-profile', 'HomeController@customer_update_profile')->name('customer.profile.update');
    Route::post('/seller/update-profile', 'HomeController@seller_update_profile')->name('seller.profile.update');

    // تاریخچه خرید (Purchase History)
    Route::resource('purchase_history','PurchaseHistoryController');
    Route::post('/purchase_history/details', 'PurchaseHistoryController@purchase_history_details')->name('purchase_history.details');

    // لیست علاقه‌مندی‌ها (Wishlist)
    Route::resource('wishlists','WishlistController');
    Route::post('/wishlists/remove', 'WishlistController@remove')->name('wishlists.remove');

    // کیف پول (Wallet)
    Route::get('/wallet', 'WalletController@index')->name('wallet.index');
    Route::post('/recharge', 'WalletController@recharge')->name('wallet.recharge');

    // تیکت پشتیبانی (Support Ticket)
    Route::resource('support_ticket','SupportTicketController');
    Route::post('support_ticket/reply','SupportTicketController@seller_store')->name('support_ticket.seller_store');

    // پکیج‌های مشتری (Customer Packages)
    Route::post('/customer_packages/purchase', 'CustomerPackageController@purchase_package')->name('customer_packages.purchase');

    // محصولات مشتری (Customer Products)
    Route::resource('customer_products', 'CustomerProductController');
    Route::post('/customer_products/published', 'CustomerProductController@updatePublished')->name('customer_products.published');
    Route::post('/customer_products/status', 'CustomerProductController@updateStatus')->name('customer_products.update.status');

    // تاریخچه خرید دیجیتال (Digital Purchase History)
    Route::get('digital_purchase_history', 'PurchaseHistoryController@digital_index')->name('digital_purchase_history.index');
});


// ============================================
# بخش فروشندگان (Seller Area)
// ============================================
Route::group(['prefix' =>'seller', 'middleware' => ['seller', 'verified']], function(){
    Route::get('/products', 'HomeController@seller_product_list')->name('seller.products');
    Route::get('/product/upload', 'HomeController@show_product_upload_form')->name('seller.products.upload');
    Route::get('/product/{id}/edit', 'HomeController@show_product_edit_form')->name('seller.products.edit');
    // این منبع صفحهٔ «ایجاد» ندارد (متد create کنترلر خالی است)؛ با
    // except(['create']) به‌جای صفحهٔ خالی، پاسخ ۴۰۴ برگردانده می‌شود.
    Route::resource('payments','PaymentController')->except(['create']);

    // درخواست تایید فروشگاه (Shop Verification)
    Route::get('/shop/apply_for_verification', 'ShopController@verify_form')->name('shop.verify');
    Route::post('/shop/apply_for_verification', 'ShopController@verify_form_store')->name('shop.verify.store');

    // نظرات (Reviews)
    Route::get('/reviews', 'ReviewController@seller_reviews')->name('reviews.seller');

    // محصولات دیجیتال (Digital Products)
    Route::get('/digitalproducts', 'HomeController@seller_digital_product_list')->name('seller.digitalproducts');
    Route::get('/digitalproducts/upload', 'HomeController@show_digital_product_upload_form')->name('seller.digitalproducts.upload');
    Route::get('/digitalproducts/{id}/edit', 'HomeController@show_digital_product_edit_form')->name('seller.digitalproducts.edit');
});

// ============================================
# محصولات، سفارشات و مدیریت (Products, Orders & Management)
// ============================================
Route::group(['middleware' => ['auth']], function(){
    // محصولات (Products)
    Route::post('/products/store/','ProductController@store')->name('products.store');
    Route::post('/products/update/{id}','ProductController@update')->name('products.update');
    Route::get('/products/duplicate/{id}', 'ProductController@duplicate')->name('products.duplicate');
    Route::post('/products/sku_combination', 'ProductController@sku_combination')->name('products.sku_combination');
    Route::post('/products/sku_combination_edit', 'ProductController@sku_combination_edit')->name('products.sku_combination_edit');
    // نام این روت با پیشوند admin. مشخص شده تا با روت عمومی
    // `products.featured` (نمایش محصولات ویژه در فروشگاه) تداخل نداشته باشد.
    Route::post('/products/featured', 'ProductController@updateFeatured')
        ->name('admin.products.featured');
    Route::post('/products/published', 'ProductController@updatePublished')->name('products.published');
    // حذف محصول: این مسیر هم در پنل مدیریت (با مجوز products.delete) و هم در
    // داشبورد فروشنده (فقط محصولات خودِ او) استفاده می‌شود. کنترل دسترسی در
    // ProductController::destroy انجام می‌شود (میدل‌ور permission اینجا
    // نمی‌آید چون فروشندهها مجوز پنل مدیریت ندارند).
    Route::delete('/products/{id}', 'ProductController@destroy')->name('products.destroy');

    // فاکتورها (Invoices)
    Route::get('invoice/customer/{order_id}', 'InvoiceController@customer_invoice_download')->name('customer.invoice.download');
    Route::get('invoice/seller/{order_id}', 'InvoiceController@seller_invoice_download')->name('seller.invoice.download');

    // سفارشات (Orders)
    Route::resource('orders','OrderController');
    Route::post('/orders/details', 'OrderController@order_details')->name('orders.details');
    Route::post('/orders/update_delivery_status', 'OrderController@update_delivery_status')->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', 'OrderController@update_payment_status')->name('orders.update_payment_status');

    // نظرات (Reviews)
    Route::resource('/reviews', 'ReviewController');

    // درخواست برداشت وجه (Withdraw Requests)
    Route::resource('/withdraw_requests', 'SellerWithdrawRequestController');
    Route::get('/withdraw_requests_all', 'SellerWithdrawRequestController@request_index')->name('withdraw_requests_all');
    Route::post('/withdraw_request/payment_modal', 'SellerWithdrawRequestController@payment_modal')->name('withdraw_request.payment_modal');
    Route::post('/withdraw_request/message_modal', 'SellerWithdrawRequestController@message_modal')->name('withdraw_request.message_modal');

    // مکالمات و پیام‌ها (Conversations & Messages)
    Route::resource('conversations','ConversationController');
    Route::post('conversations/refresh','ConversationController@refresh')->name('conversations.refresh');
    Route::resource('messages','MessageController');

    // آپلود دسته‌ای محصولات (Bulk Product Upload)
    Route::get('/product-bulk-upload/index', 'ProductBulkUploadController@index')->name('product_bulk_upload.index');
    Route::post('/bulk-product-upload', 'ProductBulkUploadController@bulk_upload')->name('bulk_product_upload');
    Route::get('/product-csv-download/{type}', 'ProductBulkUploadController@import_product')->name('product_csv.download');
    Route::get('/vendor-product-csv-download/{id}', 'ProductBulkUploadController@import_vendor_product')->name('import_vendor_product.download');
    Route::group(['prefix' =>'bulk-upload/download'], function(){
        Route::get('/category', 'ProductBulkUploadController@pdf_download_category')->name('pdf.download_category');
        Route::get('/sub_category', 'ProductBulkUploadController@pdf_download_sub_category')->name('pdf.download_sub_category');
        Route::get('/sub_sub_category', 'ProductBulkUploadController@pdf_download_sub_sub_category')->name('pdf.download_sub_sub_category');
        Route::get('/brand', 'ProductBulkUploadController@pdf_download_brand')->name('pdf.download_brand');
        Route::get('/seller', 'ProductBulkUploadController@pdf_download_seller')->name('pdf.download_seller');
    });

    // خروجی دسته‌ای محصولات (Product Bulk Export)
    Route::get('/product-bulk-export', 'ProductBulkUploadController@export')->name('product_bulk_export.index');

    // محصولات دیجیتال (Digital Products)
    Route::resource('digitalproducts','DigitalProductController');
    Route::get('/digitalproducts/download/{id}', 'DigitalProductController@download')->name('digitalproducts.download');
});

// ============================================
# فروشگاه‌ها و پیگیری سفارش (Shops & Order Tracking)
// ============================================
Route::resource('shops', 'ShopController');
Route::get('/track_your_order', 'HomeController@trackOrder')->name('orders.track');

// ============================================
# سایر درگاه‌های پرداخت (Other Payment Gateways)
// ============================================
Route::get('/instamojo/payment/pay-success', 'InstamojoController@success')->name('instamojo.success');
Route::post('rozer/payment/pay-success', 'RazorpayController@payment')->name('payment.rozer');
Route::get('/paystack/payment/callback', 'PaystackController@handleGatewayCallback');
Route::get('/vogue-pay', 'VoguePayController@showForm');
Route::get('/vogue-pay/success/{id}', 'VoguePayController@paymentSuccess');
Route::get('/vogue-pay/failure/{id}', 'VoguePayController@paymentFailure');

// ============================================
# آدرس‌ها (Addresses)
// ============================================
Route::resource('addresses','AddressController');
Route::get('/addresses/set_default/{id}', 'AddressController@set_default')->name('addresses.set_default');


// ============================================
// مدیریت جواهرات (Jewelry Management) — پنل مدیریت
// ============================================
//
// [امنیت - H-01] پیش از این اصلاح، تمام روت‌های زیر در سه گروه جداگانه با
// میدل‌ور «auth» تنها ثبت شده بودند. نتیجه آن بود که:
//
//   1) هیچ بررسی مجوزی انجام نمی‌شد؛ هر کاربر لاگین‌شده (حتی یک مشتری عادی)
//      می‌توانست به /admin/jewelry/repair-orders و سایر مسیرها دسترسی بگیرد.
//   2) گروه `admin/jewelry/repair-orders` و `admin/jewelry/reports/certificates`
//      دو بار تعریف شده بودند و تعریف دوم (بدون میدل‌ور admin) روی اولی سایه
//      می‌انداخت.
//
// اصلاح: یک گروه واحد با میدل‌ور «auth» + میدل‌ور مجوزمحور «permission:*».
// سیاست Deny-by-Default است: کاربری که مجوز لازم را نداشته باشد، رد می‌شود.
// مدیر کل سیستم (user_type=admin) از طریق Gate::before دسترسی کامل دارد.
//
Route::prefix('admin/jewelry')
    ->name('admin.jewelry.')
    ->middleware(['auth'])
    ->group(function () {

        // -------------------------------------------
        // داشبورد ماژول
        // -------------------------------------------
        Route::get('/dashboard', 'Jewelry\DashboardController@index')
            ->name('dashboard')
            ->middleware('permission:jewelry.dashboard.view');

        // -------------------------------------------
        // مالکان جواهرات (Owners)
        // -------------------------------------------
        Route::get('owners/search', 'Jewelry\JewelryOwnerController@search')
            ->name('owners.search')
            ->middleware('permission:jewelry.owners.view');
        Route::get('owners/select-list', 'Jewelry\JewelryOwnerController@getOwnersForSelect')
            ->name('owners.select_list')
            ->middleware('permission:jewelry.owners.view');

        Route::get('owners', 'Jewelry\JewelryOwnerController@index')
            ->name('owners.index')
            ->middleware('permission:jewelry.owners.view');
        Route::get('owners/create', 'Jewelry\JewelryOwnerController@create')
            ->name('owners.create')
            ->middleware('permission:jewelry.owners.create');
        Route::post('owners', 'Jewelry\JewelryOwnerController@store')
            ->name('owners.store')
            ->middleware('permission:jewelry.owners.create');
        Route::get('owners/{owner}', 'Jewelry\JewelryOwnerController@show')
            ->name('owners.show')
            ->middleware('permission:jewelry.owners.view');
        Route::get('owners/{owner}/edit', 'Jewelry\JewelryOwnerController@edit')
            ->name('owners.edit')
            ->middleware('permission:jewelry.owners.update');
        Route::match(['put', 'patch'], 'owners/{owner}', 'Jewelry\JewelryOwnerController@update')
            ->name('owners.update')
            ->middleware('permission:jewelry.owners.update');
        Route::delete('owners/{owner}', 'Jewelry\JewelryOwnerController@destroy')
            ->name('owners.destroy')
            ->middleware('permission:jewelry.owners.delete');

        // -------------------------------------------
        // شناسنامه‌ها (Certificates)
        // -------------------------------------------
        Route::get('certificates', 'Jewelry\JewelryCertificateController@index')
            ->name('certificates.index')
            ->middleware('permission:jewelry.certificates.view');
        Route::get('certificates/create', 'Jewelry\JewelryCertificateController@create')
            ->name('certificates.create')
            ->middleware('permission:jewelry.certificates.create');
        Route::post('certificates', 'Jewelry\JewelryCertificateController@store')
            ->name('certificates.store')
            ->middleware('permission:jewelry.certificates.create');
        Route::get('certificates/{certificate}', 'Jewelry\JewelryCertificateController@show')
            ->name('certificates.show')
            ->middleware('permission:jewelry.certificates.view');
        Route::get('certificates/{certificate}/edit', 'Jewelry\JewelryCertificateController@edit')
            ->name('certificates.edit')
            ->middleware('permission:jewelry.certificates.update');
        Route::match(['put', 'patch'], 'certificates/{certificate}', 'Jewelry\JewelryCertificateController@update')
            ->name('certificates.update')
            ->middleware('permission:jewelry.certificates.update');
        Route::delete('certificates/{certificate}', 'Jewelry\JewelryCertificateController@destroy')
            ->name('certificates.destroy')
            ->middleware('permission:jewelry.certificates.delete');

        Route::get('certificates/{certificate}/download', 'Jewelry\JewelryCertificateController@downloadFile')
            ->name('certificates.download')
            ->middleware('permission:jewelry.certificates.download');
        Route::get('certificates/{certificate}/print', 'Jewelry\JewelryCertificateController@print')
            ->name('certificates.print')
            ->middleware('permission:jewelry.certificates.print');

        // تاریخچه انتقالات یک شناسنامه
        // ── رفع باگ ────────────────────────────────────────────────────
        // متد `transferHistory` در کنترلر وجود داشت و ویو آن هم ساخته شده
        // بود، اما هیچ روتی به آن اشاره نمی‌کرد؛ یعنی صفحه تاریخچه
        // انتقالات یک شناسنامه کاملاً غیرقابل دسترس بود.
        // ── عملیات مدیریتی شناسنامه ────────────────────────────────
        // ── رفع باگ (Stage 2) ─────────────────────────────────────────
        // کلاس `Jewelry\Admin\JewelryCertificateController` پنج متد
        // مدیریتی داشت (verify، reject، destroy، transferHistory و
        // quickVerify) اما «هیچ روتی» به آن اشاره نمی‌کرد؛ یعنی ۲۴۲ خط
        // کد تأیید/رد شناسنامه کاملاً غیرقابل دسترس بود.
        //
        // نکته: متدهای index/show/destroy آن کلاس عمداً روت نمی‌گیرند،
        // زیرا کنترلر `Jewelry\JewelryCertificateController` همین
        // قابلیت‌ها را با مجوزهای مشخص پوشش می‌دهد و ثبت روت تکراری
        // باعث سایه‌افتادن و ابهام می‌شود.

        // تاریخچه انتقالات یک شناسنامه
        Route::get('certificates/{certificate}/transfer-history', 'Jewelry\Admin\JewelryCertificateController@transferHistory')
            ->name('certificates.transfer_history')
            ->middleware('permission:jewelry.certificates.view');

        // تأیید شناسنامه — عملیات حساس، مجوز جداگانه
        Route::post('certificates/{certificate}/verify', 'Jewelry\Admin\JewelryCertificateController@verify')
            ->name('certificates.verify')
            ->middleware('permission:jewelry.certificates.verify');

        // رد شناسنامه — دلیل رد الزامی است و در audit_logs ثبت می‌شود
        Route::post('certificates/{certificate}/reject', 'Jewelry\Admin\JewelryCertificateController@reject')
            ->name('certificates.reject')
            ->middleware('permission:jewelry.certificates.verify');

        // بررسی سریع شناسنامه (AJAX) از پنل مدیریت
        Route::post('certificates/quick-verify', 'Jewelry\Admin\JewelryCertificateController@quickVerify')
            ->name('certificates.quick_verify')
            ->middleware('permission:jewelry.certificates.verify');

        // -------------------------------------------
        // سفارشات تعمیر (Repair Orders)
        // — تکنسین تعمیرات به این بخش دسترسی دارد
        // -------------------------------------------
        Route::get('repair-orders', 'Jewelry\Admin\RepairOrderController@index')
            ->name('repair-orders.index')
            ->middleware('permission:jewelry.repairs.view');
        Route::get('repair-orders/create', 'Jewelry\Admin\RepairOrderController@create')
            ->name('repair-orders.create')
            ->middleware('permission:jewelry.repairs.create');
        Route::post('repair-orders', 'Jewelry\Admin\RepairOrderController@store')
            ->name('repair-orders.store')
            ->middleware('permission:jewelry.repairs.create');
        Route::get('repair-orders/jewelry/{jewelryId}/history', 'Jewelry\Admin\RepairOrderController@jewelryHistory')
            ->name('repair-orders.jewelry_history')
            ->middleware('permission:jewelry.repairs.view');
        Route::get('repair-orders/{repair_order}', 'Jewelry\Admin\RepairOrderController@show')
            ->name('repair-orders.show')
            ->middleware('permission:jewelry.repairs.view');
        Route::get('repair-orders/{repair_order}/edit', 'Jewelry\Admin\RepairOrderController@edit')
            ->name('repair-orders.edit')
            ->middleware('permission:jewelry.repairs.update');
        Route::match(['put', 'patch'], 'repair-orders/{repair_order}', 'Jewelry\Admin\RepairOrderController@update')
            ->name('repair-orders.update')
            ->middleware('permission:jewelry.repairs.update');
        Route::get('repair-orders/{id}/status-history', 'Jewelry\Admin\RepairOrderController@statusHistory')
            ->name('repair-orders.status_history')
            ->middleware('permission:jewelry.repairs.view');

        // ثبت عیب، شرح تعمیر، هزینه و قطعات مصرفی
        Route::post('repair-orders/{id}/status', 'Jewelry\Admin\RepairOrderController@updateStatus')
            ->name('repair-orders.update_status')
            ->middleware('permission:jewelry.repairs.update_status');

        // تخصیص تکنسین
        Route::get('repair-orders/{id}/assign', 'Jewelry\Admin\RepairOrderController@assignForm')
            ->name('repair-orders.assign_form')
            ->middleware('permission:jewelry.repairs.assign');
        Route::post('repair-orders/{id}/assign', 'Jewelry\Admin\RepairOrderController@assign')
            ->name('repair-orders.assign')
            ->middleware('permission:jewelry.repairs.assign');

        // بارگذاری تصویر/مدرک توسط تکنسین
        Route::post('repair-orders/{id}/photo', 'Jewelry\Admin\RepairOrderController@uploadAdminPhoto')
            ->name('repair-orders.upload_photo')
            ->middleware('permission:jewelry.repairs.update_status');

        Route::delete('repair-orders/{repair_order}', 'Jewelry\Admin\RepairOrderController@destroy')
            ->name('repair-orders.destroy')
            ->middleware('permission:jewelry.repairs.delete');

        // -------------------------------------------
        // گزارش سرقت و مفقودی (Missing Reports)
        // -------------------------------------------
        Route::get('missing_reports', 'MissingReportController@index')
            ->name('missing_reports.index')
            ->middleware('permission:jewelry.missing_reports.view');
        Route::get('missing_reports/create', 'MissingReportController@create')
            ->name('missing_reports.create')
            ->middleware('permission:jewelry.missing_reports.create');
        Route::post('missing_reports', 'MissingReportController@store')
            ->name('missing_reports.store')
            ->middleware('permission:jewelry.missing_reports.create');
        Route::get('missing_reports/get-jewelry-info/{id}', 'MissingReportController@getJewelryInfo')
            ->name('missing_reports.get_info')
            ->middleware('permission:jewelry.missing_reports.view');
        Route::get('missing_reports/{missing_report}', 'MissingReportController@show')
            ->name('missing_reports.show')
            ->middleware('permission:jewelry.missing_reports.view');
        Route::post('missing_reports/{id}/status', 'MissingReportController@updateStatus')
            ->name('missing_reports.update_status')
            ->middleware('permission:jewelry.missing_reports.update_status');
        Route::get('missing_reports/{id}/print', 'MissingReportController@print')
            ->name('missing_reports.print')
            ->middleware('permission:jewelry.missing_reports.print');
        // سند کلانتری/مدرک مفقودی؛ فقط دارنده مجوز دانلود
        Route::get('missing_reports/{id}/document', 'MissingReportController@downloadDocument')
            ->name('missing_reports.document')
            ->middleware('permission:jewelry.missing_reports.view');

        // -------------------------------------------
        // انتقال مالکیت (Ownership Transfers)
        // — گردش‌کار کامل در OwnershipTransferController و سرویس مربوطه
        // -------------------------------------------
        Route::get('transfers', 'Jewelry\OwnershipTransferController@index')
            ->name('transfers.index')
            ->middleware('permission:jewelry.transfers.view');
        Route::get('transfers/create', 'Jewelry\OwnershipTransferController@create')
            ->name('transfers.create')
            ->middleware('permission:jewelry.transfers.create');
        Route::get('transfers/create/certificate/{certificate}', 'Jewelry\OwnershipTransferController@createForCertificate')
            ->name('transfers.createForCertificate')
            ->middleware('permission:jewelry.transfers.create');
        Route::post('transfers', 'Jewelry\OwnershipTransferController@store')
            ->name('transfers.store')
            ->middleware('permission:jewelry.transfers.create');
        Route::get('transfers/{transfer}', 'Jewelry\OwnershipTransferController@show')
            ->name('transfers.show')
            ->middleware('permission:jewelry.transfers.view');
        Route::post('transfers/{transfer}/approve', 'Jewelry\OwnershipTransferController@approve')
            ->name('transfers.approve')
            ->middleware('permission:jewelry.transfers.approve');
        Route::post('transfers/{transfer}/reject', 'Jewelry\OwnershipTransferController@reject')
            ->name('transfers.reject')
            ->middleware('permission:jewelry.transfers.reject');
        Route::post('transfers/{transfer}/cancel', 'Jewelry\OwnershipTransferController@cancel')
            ->name('transfers.cancel')
            ->middleware('permission:jewelry.transfers.cancel');

        // -------------------------------------------
        // گزارش‌ها
        // -------------------------------------------
        // -------------------------------------------
        // گزارش‌های مدیریتی (سراسری)
        //
        // [اصلاح طراحی — Stage 2]
        // پیش‌تر این روت‌ها به Jewelry\ReportController اشاره می‌کردند؛
        // اما آن کنترلر گزارش‌ها را به مالکیت کاربر جاری (Auth::id())
        // محدود می‌کند. یعنی مدیر سیستم در پنل مدیریت عملاً گزارش خالی
        // می‌دید.
        //
        // اکنون این روت‌ها به Jewelry\Admin\ReportController متصل
        // شده‌اند که گزارش سراسری می‌سازد. آن کنترلر (۴۴۲ خط) پیش‌تر
        // هیچ روتی نداشت و کاملاً غیرقابل دسترس بود.
        //
        // کنترلر گزارش مالک‌محور حذف نشده و در بخش «جواهرات من» باقی است.
        // -------------------------------------------
        Route::get('reports', 'Jewelry\Admin\ReportController@summary')
            ->name('reports.summary')
            ->middleware('permission:jewelry.reports.view');

        Route::get('reports/certificates', 'Jewelry\Admin\ReportController@certificates')
            ->name('reports.certificates')
            ->middleware('permission:jewelry.reports.view');

        Route::get('reports/repairs', 'Jewelry\Admin\ReportController@repairs')
            ->name('reports.repairs')
            ->middleware('permission:jewelry.reports.view');

        Route::get('reports/transfers', 'Jewelry\Admin\ReportController@transfers')
            ->name('reports.transfers')
            ->middleware('permission:jewelry.reports.view');

        Route::get('reports/live-stats', 'Jewelry\Admin\ReportController@liveStats')
            ->name('reports.live_stats')
            ->middleware('permission:jewelry.reports.view');

        Route::get('reports/charts', 'Jewelry\Admin\ReportController@charts')
            ->name('reports.charts')
            ->middleware('permission:jewelry.reports.view');

        Route::post('reports/export', 'Jewelry\Admin\ReportController@export')
            ->name('reports.export')
            ->middleware('permission:jewelry.reports.export');
    });


// ============================================
// پنل کاربری اشیاء قیمتی (سمت مشتری)
// ============================================
// این بخش برای «مالک» قطعه است؛ بنابراین به مالکیت رکورد محدود می‌شود
// و در کنترلرها با Policy بررسی می‌گردد (نه با permission سراسری).
Route::middleware(['auth'])->prefix('my-jewelry')->name('my-jewelry.')->group(function () {
    Route::get('/', 'Jewelry\JewelryOwnerController@myDashboard')->name('dashboard');

    // انتقال مالکیت توسط مالک فعلی (ایجاد درخواست)
    Route::get('transfers', 'Jewelry\OwnershipTransferController@myIndex')->name('transfers.index');
    Route::get('transfers/create/{certificate}', 'Jewelry\OwnershipTransferController@myCreate')
        ->name('transfers.create');
    Route::post('transfers', 'Jewelry\OwnershipTransferController@myStore')->name('transfers.store');
    Route::get('transfers/{transfer}', 'Jewelry\OwnershipTransferController@myShow')->name('transfers.show');
    Route::post('transfers/{transfer}/cancel', 'Jewelry\OwnershipTransferController@cancel')
        ->name('transfers.cancel');

    // سوابق مالکیت قطعات کاربر
    Route::get('certificates/{certificate}/history', 'Jewelry\OwnershipTransferController@history')
        ->name('certificates.history');

    // -------------------------------------------
    // گزارش‌های مالک‌محور
    //
    // این گزارش‌ها تنها داده‌های خود کاربر را نشان می‌دهند؛
    // محدودسازی در خود کنترلر با Auth::id() اعمال می‌شود.
    // -------------------------------------------
    Route::get('reports/certificates', 'Jewelry\ReportController@certificateReport')
        ->name('reports.certificates');

    Route::get('reports/repairs', 'Jewelry\ReportController@repairReport')
        ->name('reports.repairs');

    Route::get('reports/transfers', 'Jewelry\ReportController@transferReport')
        ->name('reports.transfers');

    Route::get('reports/financial', 'Jewelry\ReportController@financialReport')
        ->name('reports.financial');

    Route::post('reports/export', 'Jewelry\ReportController@export')
        ->name('reports.export');
});


// روت API داخلی برای دریافت اطلاعات قطعه (فقط کاربران احراز هویت‌شده)
Route::middleware(['auth'])->get('/api/jewelry/{id}/info', 'MissingReportController@getJewelryInfo')
    ->name('api.jewelry.info');
// مسیرهای محصولات ویژه برای صفحه اصلی
Route::get('/products/featured', [ProductController::class, 'featured_products'])->name('products.featured');
Route::get('/products/best-selling', [ProductController::class, 'best_selling_products'])->name('products.best_selling');
Route::get('/products/new', [ProductController::class, 'new_products'])->name('products.new');
Route::get('/products/discounted', [ProductController::class, 'discounted_products'])->name('products.discounted');
Route::get('/products/todays-deal', [HomeController::class, 'todays_deal_products'])->name('products.todays_deal');
Route::get('/products/mens-jewelry', [ProductController::class, 'mens_jewelry'])->name('products.mens');
Route::get('/products/womens-jewelry', [ProductController::class, 'womens_jewelry'])->name('products.womens');


// ==========================================
// روت‌های عمومی مارکت‌پلیس طلا و جواهر دریکانا
// ==========================================

// استعلام عمومی شناسنامه طلا (بدون نیاز به ورود)
Route::get('/jewelry/certificate-verify', 'Jewelry\\JewelryCertificateController@verifyPage')
    ->name('jewelry.certificates.verify_page');

// صفحه اعلام سرقت/مفقودی عمومی (نمایش فرم)
Route::get('/jewelry/report-stolen', 'FrontendJewelryController@reportStolenForm')
    ->name('jewelry.missing_reports.create_public');
Route::post('/jewelry/report-stolen', 'FrontendJewelryController@reportStolenStore')
    ->name('jewelry.missing_reports.store_public');

// صفحه رهگیری عمومی گواهی
Route::get('/jewelry/track/{serial?}', 'FrontendJewelryController@track')
    ->name('jewelry.track');

// صفحه درباره سرویس شناسنامه
Route::get('/jewelry/certificate-service', function () {
    return view('frontend.jewelry.service');
})->name('jewelry.service');

// ==========================================================
// روت‌های عمومی مقالات طلا و جواهرات (CMS)
// ==========================================================
// این بخش کاملاً عمومی است و تنها مقاله‌های «منتشرشده» را نشان می‌دهد.
Route::get('/articles', 'ArticleController@index')->name('articles.index');
Route::get('/articles/search', 'ArticleController@search')->name('articles.search');
Route::get('/articles/category/{slug}', 'ArticleController@category')->name('articles.category');
Route::get('/articles/{slug}', 'ArticleController@show')
    ->name('articles.show')
    ->where('slug', '[A-Za-z0-9][A-Za-z0-9-]*');


