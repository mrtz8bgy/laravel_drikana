<?php

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
// بخش دمو (Demo)
// ============================================
Route::get('/demo/cron_1', 'DemoController@cron_1');
Route::get('/demo/cron_2', 'DemoController@cron_2');

// ============================================
// احراز هویت و کاربران (Authentication & Users)
// ============================================
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
Route::post('/users/login/cart', 'HomeController@cart_login')->name('cart.login.submit');

// برای کاربران احراز هویت شده
Route::middleware(['auth'])->group(function () {
    Route::get('/verification', 'OTPVerificationController@verification')->name('verification');
    Route::post('/verification/verify', 'OTPVerificationController@verify_phone')->name('verification.verify');
    Route::post('/verification/resend', 'OTPVerificationController@resend_verification_code')->name('verification.resend');
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
// 🆕 بخش‌های جدید محصولات (New Product Sections)
// ============================================

// محصولات ویژه (Featured Products)
Route::get('/products/featured', 'HomeController@featured_products')->name('products.featured');

// پرفروش‌ترین محصولات (Best Selling)
Route::get('/products/best-selling', 'HomeController@best_selling_products')->name('products.best_selling');

// جدیدترین محصولات (New Arrivals)
Route::get('/products/new', 'HomeController@new_products')->name('products.new');

// محصولات با تخفیف (Discounted Products)
Route::get('/products/discounted', 'HomeController@discounted_products')->name('products.discounted');

// جواهرات مردانه (Men's Jewelry)
Route::get('/products/mens-jewelry', 'HomeController@mens_jewelry')->name('products.mens');

// جواهرات زنانه (Women's Jewelry)
Route::get('/products/womens-jewelry', 'HomeController@womens_jewelry')->name('products.womens');

// محبوب‌ترین محصولات (Most Popular)
Route::get('/products/popular', 'HomeController@popular_products')->name('products.popular');

// محصولات با بالاترین امتیاز (Top Rated)
Route::get('/products/top-rated', 'HomeController@top_rated_products')->name('products.top_rated');

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
Route::post('/config_content', 'HomeController@product_content')->name('configs.update_status');

// ============================================
# صفحات سیاست‌ها و قوانین (Policy Pages)
// ============================================
Route::get('/sellerpolicy', 'HomeController@sellerpolicy')->name('sellerpolicy');
Route::get('/returnpolicy', 'HomeController@returnpolicy')->name('returnpolicy');
Route::get('/supportpolicy', 'HomeController@supportpolicy')->name('supportpolicy');
Route::get('/terms', 'HomeController@terms')->name('terms');
Route::get('/privacypolicy', 'HomeController@privacypolicy')->name('privacypolicy');

// ============================================
# بخش کاربران (User Area)
// ============================================
Route::group(['middleware' => ['user', 'verification']], function(){
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::post('/customer/update-profile', 'HomeController@customer_update_profile')->name('customer.profile.update');
    Route::post('/seller/update-profile', 'HomeController@seller_update_profile')->name('seller.profile.update');

    Route::resource('purchase_history','PurchaseHistoryController');
    Route::post('/purchase_history/details', 'PurchaseHistoryController@purchase_history_details')->name('purchase_history.details');
    Route::get('/purchase_history/destroy/{id}', 'PurchaseHistoryController@destroy')->name('purchase_history.destroy');

    Route::resource('wishlists','WishlistController');
    Route::post('/wishlists/remove', 'WishlistController@remove')->name('wishlists.remove');

    Route::get('/wallet', 'WalletController@index')->name('wallet.index');
    Route::post('/recharge', 'WalletController@recharge')->name('wallet.recharge');

    Route::resource('support_ticket','SupportTicketController');
    Route::post('support_ticket/reply','SupportTicketController@seller_store')->name('support_ticket.seller_store');

    Route::post('/customer_packages/purchase', 'CustomerPackageController@purchase_package')->name('customer_packages.purchase');

    Route::resource('customer_products', 'CustomerProductController');
    Route::post('/customer_products/published', 'CustomerProductController@updatePublished')->name('customer_products.published');
    Route::post('/customer_products/status', 'CustomerProductController@updateStatus')->name('customer_products.update.status');

    Route::get('digital_purchase_history', 'PurchaseHistoryController@digital_index')->name('digital_purchase_history.index');
});

Route::get('/customer_products/destroy/{id}', 'CustomerProductController@destroy')->name('customer_products.destroy');

// ============================================
# بخش فروشندگان (Seller Area)
// ============================================
Route::group(['prefix' =>'seller', 'middleware' => ['seller', 'verified']], function(){
    Route::get('/products', 'HomeController@seller_product_list')->name('seller.products');
    Route::get('/product/upload', 'HomeController@show_product_upload_form')->name('seller.products.upload');
    Route::get('/product/{id}/edit', 'HomeController@show_product_edit_form')->name('seller.products.edit');
    Route::resource('payments','PaymentController');

    Route::get('/shop/apply_for_verification', 'ShopController@verify_form')->name('shop.verify');
    Route::post('/shop/apply_for_verification', 'ShopController@verify_form_store')->name('shop.verify.store');

    Route::get('/reviews', 'ReviewController@seller_reviews')->name('reviews.seller');

    Route::get('/digitalproducts', 'HomeController@seller_digital_product_list')->name('seller.digitalproducts');
    Route::get('/digitalproducts/upload', 'HomeController@show_digital_product_upload_form')->name('seller.digitalproducts.upload');
    Route::get('/digitalproducts/{id}/edit', 'HomeController@show_digital_product_edit_form')->name('seller.digitalproducts.edit');
});

// ============================================
# محصولات، سفارشات و مدیریت (Products, Orders & Management)
// ============================================
Route::group(['middleware' => ['auth']], function(){
    Route::post('/products/store/','ProductController@store')->name('products.store');
    Route::post('/products/update/{id}','ProductController@update')->name('products.update');
    Route::get('/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
    Route::get('/products/duplicate/{id}', 'ProductController@duplicate')->name('products.duplicate');
    Route::post('/products/sku_combination', 'ProductController@sku_combination')->name('products.sku_combination');
    Route::post('/products/sku_combination_edit', 'ProductController@sku_combination_edit')->name('products.sku_combination_edit');
    Route::post('/products/featured', 'ProductController@updateFeatured')->name('products.featured');
    Route::post('/products/published', 'ProductController@updatePublished')->name('products.published');

    Route::get('invoice/customer/{order_id}', 'InvoiceController@customer_invoice_download')->name('customer.invoice.download');
    Route::get('invoice/seller/{order_id}', 'InvoiceController@seller_invoice_download')->name('seller.invoice.download');

    Route::resource('orders','OrderController');
    Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');
    Route::post('/orders/details', 'OrderController@order_details')->name('orders.details');
    Route::post('/orders/update_delivery_status', 'OrderController@update_delivery_status')->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', 'OrderController@update_payment_status')->name('orders.update_payment_status');

    Route::resource('/reviews', 'ReviewController');

    Route::resource('/withdraw_requests', 'SellerWithdrawRequestController');
    Route::get('/withdraw_requests_all', 'SellerWithdrawRequestController@request_index')->name('withdraw_requests_all');
    Route::post('/withdraw_request/payment_modal', 'SellerWithdrawRequestController@payment_modal')->name('withdraw_request.payment_modal');
    Route::post('/withdraw_request/message_modal', 'SellerWithdrawRequestController@message_modal')->name('withdraw_request.message_modal');

    Route::resource('conversations','ConversationController');
    Route::post('conversations/refresh','ConversationController@refresh')->name('conversations.refresh');
    Route::resource('messages','MessageController');

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

    Route::get('/product-bulk-export', 'ProductBulkUploadController@export')->name('product_bulk_export.index');

    Route::resource('digitalproducts','DigitalProductController');
    Route::get('/digitalproducts/destroy/{id}', 'DigitalProductController@destroy')->name('digitalproducts.destroy');
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
Route::get('/addresses/destroy/{id}', 'AddressController@destroy')->name('addresses.destroy');
Route::get('/addresses/set_default/{id}', 'AddressController@set_default')->name('addresses.set_default');

// ============================================
// مدیریت جواهرات (Jewelry Management)
// ============================================
Route::middleware(['auth'])->prefix('admin/jewelry')->name('admin.jewelry.')->group(function () {
    Route::get('/dashboard', 'Jewelry\DashboardController@index')->name('dashboard');
    Route::resource('owners', 'Jewelry\JewelryOwnerController');
    Route::get('owners/search', 'Jewelry\JewelryOwnerController@search')->name('owners.search');
    Route::get('owners/select-list', 'Jewelry\JewelryOwnerController@getOwnersForSelect')->name('owners.select_list');
    Route::resource('certificates', 'Jewelry\JewelryCertificateController');
    Route::get('certificates/{certificate}/download', 'Jewelry\JewelryCertificateController@downloadFile')->name('certificates.download');
    Route::get('certificates/{certificate}/print', 'Jewelry\JewelryCertificateController@print')->name('certificates.print');
    Route::get('certificates/verify/{serial_number}', 'Jewelry\JewelryCertificateController@publicVerify')->name('certificates.public_verify');
    Route::resource('repair-orders', 'Jewelry\RepairOrderController');
    Route::get('transfers/create', 'Jewelry\JewelryTransferController@create')->name('transfers.create');
    Route::get('transfers/create/certificate/{certificate}', 'Jewelry\JewelryTransferController@createForCertificate')->name('transfers.createForCertificate');
    Route::post('transfers', 'Jewelry\JewelryTransferController@store')->name('transfers.store');
    Route::get('reports/certificates', 'Jewelry\ReportController@certificateReport')->name('reports.certificates');
});

// ================================================
// روت‌های ماژول ثبت سرقت و مفقودی
// ================================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    Route::prefix('jewelry')->name('jewelry.')->group(function() {
        Route::resource('missing_reports', 'MissingReportController')->only(['index', 'create', 'store', 'show']);
        Route::post('missing_reports/{id}/status', 'MissingReportController@updateStatus')->name('missing_reports.update_status');
        Route::get('missing_reports/{id}/print', 'MissingReportController@print')->name('missing_reports.print');
        Route::get('missing_reports/get-jewelry-info/{id}', 'MissingReportController@getJewelryInfo')->name('missing_reports.get_info');
    });
});

Route::middleware(['auth'])->get('/api/jewelry/{id}/info', 'MissingReportController@getJewelryInfo');

// روت‌های ماژول تعمیرات
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    Route::prefix('jewelry')->name('jewelry.')->group(function() {
        Route::resource('repair-orders', 'Jewelry\Admin\RepairOrderController');
        Route::post('repair-orders/{id}/status', 'Jewelry\Admin\RepairOrderController@updateStatus')->name('repair-orders.update_status');
        Route::get('repair-orders/jewelry/{jewelryId}/history', 'Jewelry\Admin\RepairOrderController@jewelryHistory')->name('repair-orders.jewelry_history');
    });
});

// ================================================
// روت‌های گزارشات
// ================================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    Route::prefix('jewelry')->name('jewelry.')->group(function() {
        Route::get('reports/certificates', 'Jewelry\ReportController@certificateReport')->name('reports.certificates');
        Route::get('reports/repairs', 'Jewelry\ReportController@repairReport')->name('reports.repairs');
        Route::get('reports/transfers', 'Jewelry\ReportController@transferReport')->name('reports.transfers');
        Route::get('reports/financial', 'Jewelry\ReportController@financialReport')->name('reports.financial');
        Route::post('reports/export', 'Jewelry\ReportController@export')->name('reports.export');
    });
});