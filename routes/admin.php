<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin', 'HomeController@admin_dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
	Route::resource('categories','CategoryController');
	Route::post('/categories/featured', 'CategoryController@updateFeatured')->name('categories.featured');

	Route::resource('subcategories','SubCategoryController');

	Route::resource('subsubcategories','SubSubCategoryController');

	Route::resource('brands','BrandController');

	Route::get('/products/admin','ProductController@admin_products')->name('products.admin');
	Route::get('/products/seller','ProductController@seller_products')->name('products.seller');
	Route::get('/products/create','ProductController@create')->name('products.create');
	// Legacy stock-management views use the product controller's existing form
	// and SKU endpoints. Keep these aliases so old admin links remain usable.
	Route::get('/stocks/create', 'ProductController@create')->name('stocks.create');
	Route::post('/stocks/store', 'ProductController@store')->name('stocks.store');
	Route::post('/stocks/sku_combinations', 'ProductController@sku_combination')->name('stocks.sku_combinations');
	Route::get('/products/admin/{id}/edit','ProductController@admin_product_edit')->name('products.admin.edit');
	Route::get('/products/seller/{id}/edit','ProductController@seller_product_edit')->name('products.seller.edit');
	Route::get('/products/todays_deal', 'HomeController@todays_deal_products')->name('admin.products.todays_deal_page');
	Route::post('/products/todays_deal', 'ProductController@updateTodaysDeal')->name('admin.products.todays_deal');
	Route::post('/products/get_products_by_subsubcategory', 'ProductController@get_products_by_subsubcategory')->name('products.get_products_by_subsubcategory');

	Route::resource('sellers','SellerController');
	Route::get('/sellers/view/{id}/verification', 'SellerController@show_verification_request')->name('sellers.show_verification_request');
	Route::get('/sellers/approve/{id}', 'SellerController@approve_seller')->name('sellers.approve');
	Route::get('/sellers/reject/{id}', 'SellerController@reject_seller')->name('sellers.reject');
	Route::post('/sellers/payment_modal', 'SellerController@payment_modal')->name('sellers.payment_modal');
	Route::get('/seller/payments', 'PaymentController@payment_histories')->name('sellers.payment_histories');
	Route::get('/seller/payments/show/{id}', 'PaymentController@show')->name('sellers.payment_history');

	// این منابع صفحهٔ «ایجاد» ندارند (فرم ساخت در جای دیگری از پنل است و
	// متد create کنترلر خالی است). با except(['create']) به‌جای صفحهٔ خالی،
	// پاسخ ۴۰۴ تمیز برگردانده می‌شود.
	Route::resource('customers','CustomerController')->except(['create']);

	Route::get('/newsletter', 'NewsletterController@index')->name('newsletters.index');
	Route::post('/newsletter/send', 'NewsletterController@send')->name('newsletters.send');

	Route::resource('profile','ProfileController')->except(['create']);

	Route::post('/business-settings/update', 'BusinessSettingsController@update')->name('business_settings.update');
	Route::post('/business-settings/update/activation', 'BusinessSettingsController@updateActivationSettings')->name('business_settings.update.activation');
	Route::get('/activation', 'BusinessSettingsController@activation')->name('activation.index');
	Route::get('/payment-method', 'BusinessSettingsController@payment_method')->name('payment_method.index');
	Route::get('/social-login', 'BusinessSettingsController@social_login')->name('social_login.index');
	Route::get('/smtp-settings', 'BusinessSettingsController@smtp_settings')->name('smtp_settings.index');
	Route::get('/google-analytics', 'BusinessSettingsController@google_analytics')->name('google_analytics.index');
	Route::get('/facebook-chat', 'BusinessSettingsController@facebook_chat')->name('facebook_chat.index');
	// [سخت‌سازی M-10] این مسیر مستقیم در فایل .env می‌نویسد؛ پس علاوه بر
	// میدل‌ور admin، به مجوز «تنظیمات» هم نیاز دارد (دفاع لایه‌ای).
	Route::post('/env_key_update', 'BusinessSettingsController@env_key_update')
		->name('env_key_update.update')
		->middleware('permission:settings.update');
	Route::post('/payment_method_update', 'BusinessSettingsController@payment_method_update')->name('payment_method.update');
	Route::post('/google_analytics', 'BusinessSettingsController@google_analytics_update')->name('google_analytics.update');
	Route::post('/facebook_chat', 'BusinessSettingsController@facebook_chat_update')->name('facebook_chat.update');
	Route::post('/facebook_pixel', 'BusinessSettingsController@facebook_pixel_update')->name('facebook_pixel.update');
	Route::get('/currency', 'CurrencyController@currency')->name('currency.index');
    Route::post('/currency/update', 'CurrencyController@updateCurrency')->name('currency.update');
    Route::post('/your-currency/update', 'CurrencyController@updateYourCurrency')->name('your_currency.update');
	Route::get('/currency/create', 'CurrencyController@create')->name('currency.create');
	Route::post('/currency/store', 'CurrencyController@store')->name('currency.store');
	Route::post('/currency/currency_edit', 'CurrencyController@edit')->name('currency.edit');
	Route::post('/currency/update_status', 'CurrencyController@update_status')->name('currency.update_status');
	Route::get('/verification/form', 'BusinessSettingsController@seller_verification_form')->name('seller_verification_form.index');
	Route::post('/verification/form', 'BusinessSettingsController@seller_verification_form_update')->name('seller_verification_form.update');
	Route::get('/vendor_commission', 'BusinessSettingsController@vendor_commission')->name('business_settings.vendor_commission');
	Route::post('/vendor_commission_update', 'BusinessSettingsController@vendor_commission_update')->name('business_settings.vendor_commission.update');

	Route::resource('/languages', 'LanguageController');
	Route::post('/languages/update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');
	Route::post('/languages/key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');

	Route::get('/frontend_settings/home', 'HomeController@home_settings')->name('home_settings.index');
	Route::post('/frontend_settings/home/top_10', 'HomeController@top_10_settings')->name('top_10_settings.store');
	Route::get('/sellerpolicy/{type}', 'PolicyController@index')->name('sellerpolicy.index');
	Route::get('/returnpolicy/{type}', 'PolicyController@index')->name('returnpolicy.index');
	Route::get('/supportpolicy/{type}', 'PolicyController@index')->name('supportpolicy.index');
	Route::get('/terms/{type}', 'PolicyController@index')->name('terms.index');
	Route::get('/privacypolicy/{type}', 'PolicyController@index')->name('privacypolicy.index');

	//Policy Controller
	Route::post('/policies/store', 'PolicyController@store')->name('policies.store');

	Route::group(['prefix' => 'frontend_settings'], function(){
		Route::resource('sliders','SliderController');

		// مسیر «ساخت بنر» باید پیش از Route::resource ثبت شود؛ در غیر این صورت
		// GET /home_banners/create با مسیر show (…/{id}) تطبیق داده می‌شد و
		// متد create بدون پارامتر position فراخوانی می‌گشت (خطای ۵۰۰).
		// پارامتر position اختیاری است چون برخی لینک‌های قدیمی بدون آن ساخته شده‌اند.
		Route::get('/home_banners/create/{position?}', 'BannerController@create')->name('home_banners.create');
		Route::get('/home_banners/create/{position}', 'BannerController@create')->name('home_banners.create.position');
		Route::resource('home_banners','BannerController')->except(['create']);
		Route::post('/home_banners/update_status', 'BannerController@update_status')->name('home_banners.update_status');

		Route::resource('home_categories','HomeCategoryController');
		Route::post('/home_categories/update_status', 'HomeCategoryController@update_status')->name('home_categories.update_status');
		Route::post('/home_categories/get_subsubcategories_by_category', 'HomeCategoryController@getSubSubCategories')->name('home_categories.get_subsubcategories_by_category');
	});

	Route::resource('staffs','StaffController');


	Route::resource('flash_deals','FlashDealController');
	Route::post('/flash_deals/update_status', 'FlashDealController@update_status')->name('flash_deals.update_status');
	Route::post('/flash_deals/update_featured', 'FlashDealController@update_featured')->name('flash_deals.update_featured');
	Route::post('/flash_deals/product_discount', 'FlashDealController@product_discount')->name('flash_deals.product_discount');
	Route::post('/flash_deals/product_discount_edit', 'FlashDealController@product_discount_edit')->name('flash_deals.product_discount_edit');

	Route::get('/orders', 'OrderController@admin_orders')->name('orders.index.admin');
	Route::get('/orders/{id}/show', 'OrderController@show')->name('admin.orders.show');
	Route::get('/sales/{id}/show', 'OrderController@sales_show')->name('sales.show');
	Route::get('/sales', 'OrderController@sales')->name('sales.index');

	Route::resource('links','LinkController');

	Route::resource('generalsettings','GeneralSettingController')->except(['create']);
	Route::get('/logo','GeneralSettingController@logo')->name('generalsettings.logo');
	Route::post('/logo','GeneralSettingController@storeLogo')->name('generalsettings.logo.store');
	Route::get('/color','GeneralSettingController@color')->name('generalsettings.color');
	Route::post('/color','GeneralSettingController@storeColor')->name('generalsettings.color.store');

	Route::resource('seosetting','SEOController')->except(['create']);

	Route::post('/pay_to_seller', 'CommissionController@pay_to_seller')->name('commissions.pay_to_seller');

	//Reports
	Route::get('/stock_report', 'ReportController@stock_report')->name('stock_report.index');
	Route::get('/in_house_sale_report', 'ReportController@in_house_sale_report')->name('in_house_sale_report.index');
	Route::get('/seller_report', 'ReportController@seller_report')->name('seller_report.index');
	Route::get('/seller_sale_report', 'ReportController@seller_sale_report')->name('seller_sale_report.index');
	Route::get('/wish_report', 'ReportController@wish_report')->name('wish_report.index');

	//Coupons
	Route::resource('coupon','CouponController');
	Route::post('/coupon/get_form', 'CouponController@get_coupon_form')->name('coupon.get_coupon_form');
	Route::post('/coupon/get_form_edit', 'CouponController@get_coupon_form_edit')->name('coupon.get_coupon_form_edit');

	//Reviews
	Route::get('/reviews', 'ReviewController@index')->name('admin.reviews.index');
	Route::post('/reviews/published', 'ReviewController@updatePublished')->name('reviews.published');

	//Support_Ticket
	Route::get('support_ticket/','SupportTicketController@admin_index')->name('support_ticket.admin_index');
	Route::get('support_ticket/{id}/show','SupportTicketController@admin_show')->name('support_ticket.admin_show');
	Route::post('support_ticket/reply','SupportTicketController@admin_store')->name('support_ticket.admin_store');

	//Pickup_Points
	Route::resource('pick_up_points','PickupPointController');


	Route::get('orders_by_pickup_point','OrderController@order_index')->name('pick_up_point.order_index');
	Route::get('/orders_by_pickup_point/{id}/show', 'OrderController@pickup_point_order_sales_show')->name('pick_up_point.order_show');

	Route::get('invoice/admin/{order_id}', 'InvoiceController@admin_invoice_download')->name('admin.invoice.download');

	//conversation of seller customer
	Route::get('conversations','ConversationController@admin_index')->name('conversations.admin_index');
	Route::get('conversations/{id}/show','ConversationController@admin_show')->name('conversations.admin_show');


    Route::post('/sellers/profile_modal', 'SellerController@profile_modal')->name('sellers.profile_modal');
    Route::post('/sellers/approved', 'SellerController@updateApproved')->name('sellers.approved');

	Route::resource('attributes','AttributeController');

	Route::resource('addons','AddonController');
	Route::post('/addons/activation', 'AddonController@activation')->name('addons.activation');

	Route::get('/customer-bulk-upload/index', 'CustomerBulkUploadController@index')->name('customer_bulk_upload.index');
	Route::post('/bulk-user-upload', 'CustomerBulkUploadController@user_bulk_upload')->name('bulk_user_upload');
	Route::post('/bulk-customer-upload', 'CustomerBulkUploadController@customer_bulk_file')->name('bulk_customer_upload');
	Route::get('/user', 'CustomerBulkUploadController@pdf_download_user')->name('pdf.download_user');
	//Customer Package
	Route::resource('customer_packages','CustomerPackageController');
	//Classified Products
	Route::get('/classified_products', 'CustomerProductController@customer_product_index')->name('classified_products');
	Route::post('/classified_products/published', 'CustomerProductController@updatePublished')->name('classified_products.published');

	//Shipping Configuration
	Route::get('/shipping_configuration', 'BusinessSettingsController@shipping_configuration')->name('shipping_configuration.index');
	Route::post('/shipping_configuration/update', 'BusinessSettingsController@shipping_configuration_update')->name('shipping_configuration.update');

	Route::resource('pages', 'PageController');

	Route::resource('countries','CountryController')->except(['create']);
	Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status');
});

// =========================================================
// گروه RBAC — نقش‌ها، مجوزها، کاربران، مقالات و Audit Log
// =========================================================
//
// [چرا این گروه از گروه بالایی جدا است؟]
//
// گروه بالای این فایل میدل‌ور «admin» دارد؛ یعنی تنها کاربرانی را می‌پذیرد
// که user_type آن‌ها admin یا staff باشد و برای بقیه ۴۰۴ برمی‌گرداند.
//
// این با نیازمندی RBAC در تناقض بود: «مدیر محتوا» و «مدیر مالکیت» کاربرانی
// هستند که باید بدون user_type=admin به بخش‌های مجاز خود دسترسی داشته باشند.
// اگر این روت‌ها داخل گروه بالا می‌ماندند، هرگز قابل استفاده نبودند.
//
// بنابراین این گروه تنها با auth + permission:* محافظت می‌شود.
// مدیر کل سیستم به‌واسطه Gate::before همیشه دسترسی کامل دارد و سایر نقش‌ها
// بر پایه مجوزهای صریح کار می‌کنند. «رد پیش‌فرض» برقرار است، چون هر روت
// میدل‌ور permission مخصوص خود را دارد.
//
// نام‌ها با پیشوند admin. ثبت می‌شوند تا با روت‌های عمومی /articles که نام
// articles.* دارند تداخل نکنند (سبب تولید URL اشتباه می‌شد).
//
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

    // ---------------------------------------------------------
    // نقش‌ها
    // ---------------------------------------------------------
    Route::get('/roles',            'RoleController@index')->name('roles.index')->middleware('permission:roles.view');
    Route::get('/roles/create',     'RoleController@create')->name('roles.create')->middleware('permission:roles.create');
    Route::post('/roles',           'RoleController@store')->name('roles.store')->middleware('permission:roles.create');
    Route::get('/roles/{id}',       'RoleController@show')->name('roles.show')->middleware('permission:roles.view');
    Route::get('/roles/{id}/edit',  'RoleController@edit')->name('roles.edit')->middleware('permission:roles.update');
    Route::match(['put', 'patch'], '/roles/{id}', 'RoleController@update')->name('roles.update')->middleware('permission:roles.update');
    Route::delete('/roles/{id}',    'RoleController@destroy')->name('roles.destroy.delete')->middleware('permission:roles.delete');

    // ---------------------------------------------------------
    // کاربران و انتساب نقش
    //
    // ⚠ ترتیب مهم است: روت‌های ثابت (`/users/create`) باید پیش از روت
    // پارامتری (`/users/{id}`) ثبت شوند. لاراول مسیرها را به «ترتیب
    // ثبت» تطبیق می‌دهد؛ اگر `/users/{id}` جلوتر باشد، درخواست
    // `/users/create` با `id = "create"` تفسیر می‌شود و ۴۰۴ می‌دهد.
    // ---------------------------------------------------------
    Route::get('/users',             'UserRoleController@index')->name('users.index')->middleware('permission:users.view');
    Route::get('/users/create',      'UserRoleController@create')->name('users.create')->middleware('permission:users.create');
    Route::post('/users',            'UserRoleController@store')->name('users.store')->middleware('permission:users.create');
    Route::get('/users/{id}',        'UserRoleController@show')->name('users.show')->middleware('permission:users.view');
    Route::get('/users/{id}/roles',  'UserRoleController@editRoles')->name('users.roles.edit')->middleware('permission:roles.assign');
    Route::post('/users/{id}/roles', 'UserRoleController@updateRoles')->name('users.roles.update')->middleware('permission:roles.assign');

    // ---------------------------------------------------------
    // مجوزها
    // ---------------------------------------------------------
    Route::get('/permissions',               'UserRoleController@permissions')->name('permissions.index')->middleware('permission:permissions.view');
    Route::post('/permissions',              'UserRoleController@storePermission')->name('permissions.store')->middleware('permission:permissions.create');
    Route::delete('/permissions/{id}',       'UserRoleController@destroyPermission')->name('permissions.destroy.delete')->middleware('permission:permissions.delete');

    // ---------------------------------------------------------
    // Audit Log
    // ---------------------------------------------------------
    Route::get('/audit-logs',        'UserRoleController@auditLogs')->name('audit_logs.index')->middleware('permission:audit_logs.view');

    // ---------------------------------------------------------
    // دسته‌بندی مقالات
    // ---------------------------------------------------------
    Route::get('/articles/categories',              'Admin\ArticleController@categories')->name('admin.articles.categories')->middleware('permission:articles.categories.manage');
    Route::post('/articles/categories',             'Admin\ArticleController@storeCategory')->name('admin.articles.categories.store')->middleware('permission:articles.categories.manage');
    Route::post('/articles/categories/{id}/update', 'Admin\ArticleController@updateCategory')->name('admin.articles.categories.update')->middleware('permission:articles.categories.manage');
    Route::get('/articles/categories/{id}/delete',  'Admin\ArticleController@deleteCategory')->name('admin.articles.categories.delete')->middleware('permission:articles.categories.manage');

    // ---------------------------------------------------------
    // مقالات (CMS)
    // مسیرهای ثابت باید پیش از /articles/{id} ثبت شوند
    // ---------------------------------------------------------
    Route::get('/articles',               'Admin\ArticleController@index')->name('admin.articles.index')->middleware('permission:articles.view,articles.view_own');
    Route::get('/articles/create',        'Admin\ArticleController@create')->name('admin.articles.create')->middleware('permission:articles.create');
    Route::post('/articles',              'Admin\ArticleController@store')->name('admin.articles.store')->middleware('permission:articles.create');
    Route::get('/articles/images/{imageId}/delete', 'Admin\ArticleController@deleteImage')->name('admin.articles.images.delete')->middleware('permission:articles.images.manage');
    Route::get('/articles/{id}/edit',     'Admin\ArticleController@edit')->name('admin.articles.edit')->middleware('permission:articles.update');
    Route::match(['put', 'patch'], '/articles/{id}', 'Admin\ArticleController@update')->name('admin.articles.update')->middleware('permission:articles.update');
    Route::delete('/articles/{id}',       'Admin\ArticleController@destroy')->name('admin.articles.destroy')->middleware('permission:articles.delete');
    Route::get('/articles/{id}/delete',   'Admin\ArticleController@destroy')->name('admin.articles.delete')->middleware('permission:articles.delete');
    Route::post('/articles/{id}/submit',  'Admin\ArticleController@submit')->name('admin.articles.submit')->middleware('permission:articles.submit');
    Route::post('/articles/{id}/publish', 'Admin\ArticleController@publish')->name('admin.articles.publish')->middleware('permission:articles.publish');
    Route::post('/articles/{id}/unpublish', 'Admin\ArticleController@unpublish')->name('admin.articles.unpublish')->middleware('permission:articles.publish');
    Route::post('/articles/{id}/images',  'Admin\ArticleController@uploadImage')->name('admin.articles.images.upload')->middleware('permission:articles.images.manage');
    Route::get('/articles/{id}',          'Admin\ArticleController@show')->name('admin.articles.show')->middleware('permission:articles.view,articles.view_own');
});
