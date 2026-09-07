<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrikanaCoreEcommerceTables extends Migration
{
    public function up()
    {
        // ─────────────────────────────────────────────
        // 1. Sellers (multi-vendor)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('sellers')) {
            Schema::create('sellers', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->tinyInteger('verification_status')->default(0);
                $t->text('verification_info')->nullable();
                $t->tinyInteger('cash_on_delivery_status')->default(0);
                $t->tinyInteger('sslcommerz_status')->default(0);
                $t->tinyInteger('stripe_status')->default(0);
                $t->string('paypal_client_id', 255)->nullable();
                $t->string('paypal_client_secret', 255)->nullable();
                $t->string('ssl_store_id', 255)->nullable();
                $t->string('ssl_password', 255)->nullable();
                $t->string('stripe_key', 255)->nullable();
                $t->string('stripe_secret', 255)->nullable();
                $t->tinyInteger('instamojo_status')->default(0);
                $t->string('instamojo_api_key', 255)->nullable();
                $t->string('instamojo_token', 255)->nullable();
                $t->tinyInteger('razorpay_status')->default(0);
                $t->string('razorpay_api_key', 255)->nullable();
                $t->string('razorpay_secret', 255)->nullable();
                $t->tinyInteger('paypal_status')->default(0);
                $t->double('admin_to_pay', 20, 2)->default(0.00);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 2. Shops (per-seller storefront)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->string('name', 255)->nullable();
                $t->string('logo', 255)->nullable();
                $t->text('sliders')->nullable();
                $t->text('address')->nullable();
                $t->string('facebook', 255)->nullable();
                $t->string('google', 255)->nullable();
                $t->string('twitter', 255)->nullable();
                $t->string('youtube', 255)->nullable();
                $t->string('instagram', 255)->nullable();
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->text('delivery_pickup_latitude')->nullable();
                $t->text('delivery_pickup_longitude')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 3. Customers
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 4. Brands
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->tinyInteger('published')->default(1);
                $t->string('logo', 255)->nullable();
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 5. Categories / SubCategories / SubSubCategories
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->string('banner', 255)->nullable();
                $t->string('icon', 255)->nullable();
                $t->string('image', 255)->nullable();
                $t->tinyInteger('published')->default(1);
                $t->tinyInteger('featured')->default(0);
                $t->tinyInteger('top')->default(0);
                $t->integer('level')->default(0);
                $t->integer('parent_id')->nullable();
                $t->double('commission', 8, 2)->default(0);
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('sub_categories')) {
            Schema::create('sub_categories', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->integer('category_id')->unsigned();
                $t->tinyInteger('published')->default(1);
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('sub_sub_categories')) {
            Schema::create('sub_sub_categories', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->integer('sub_category_id')->unsigned();
                $t->integer('category_id')->unsigned();
                $t->tinyInteger('published')->default(1);
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 6. Products
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->string('added_by', 20)->default('admin');
                $t->integer('user_id')->unsigned()->nullable();
                $t->integer('category_id')->unsigned()->nullable();
                $t->integer('subcategory_id')->unsigned()->nullable();
                $t->integer('subsubcategory_id')->unsigned()->nullable();
                $t->integer('brand_id')->unsigned()->nullable();
                $t->string('photos', 2000)->nullable();
                $t->string('thumbnail_img', 255)->nullable();
                $t->string('featured_img', 255)->nullable();
                $t->string('flash_deal_img', 255)->nullable();
                $t->string('video_provider', 50)->nullable();
                $t->string('video_link', 255)->nullable();
                $t->string('tags', 1000)->nullable();
                $t->longText('description')->nullable();
                $t->double('unit_price', 20, 2);
                $t->double('purchase_price', 20, 2)->nullable();
                $t->integer('variant_product')->default(0);
                $t->string('attributes', 1000)->nullable();
                $t->text('choice_options')->nullable();
                $t->text('colors')->nullable();
                $t->tinyInteger('published')->default(1);
                $t->tinyInteger('featured')->default(0);
                $t->tinyInteger('todays_deal')->default(0);
                $t->tinyInteger('current_stock')->default(0);
                $t->string('unit', 20)->default('pcs');
                $t->double('weight', 8, 2)->default(0);
                $t->double('discount', 20, 2)->default(0);
                $t->string('discount_type', 20)->default('amount');
                $t->double('tax', 8, 2)->default(0);
                $t->string('tax_type', 20)->default('amount');
                $t->string('shipping_type', 20)->default('flat_rate');
                $t->double('shipping_cost', 20, 2)->default(0);
                $t->integer('num_of_sale')->default(0);
                $t->string('slug', 255)->nullable()->unique();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->string('pdf', 255)->nullable();
                $t->tinyInteger('refundable')->default(1);
                $t->integer('est_shipping_days')->nullable();
                $t->double('rating', 3, 2)->default(0.00);
                $t->string('barcode', 255)->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('product_stocks')) {
            Schema::create('product_stocks', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('product_id')->unsigned();
                $t->string('variant', 255)->nullable();
                $t->string('sku', 255)->nullable();
                $t->double('price', 20, 2)->default(0);
                $t->integer('qty')->default(0);
                $t->string('image', 255)->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 7. Carts
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned()->nullable();
                $t->string('temp_user_id', 255)->nullable();
                $t->integer('product_id')->unsigned();
                $t->text('variation')->nullable();
                $t->double('price', 20, 2)->default(0);
                $t->double('tax', 20, 2)->default(0);
                $t->double('shipping_cost', 20, 2)->default(0);
                $t->integer('quantity')->default(1);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 8. Orders & details
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $t) {
                $t->increments('id');
                $t->string('code', 50);
                $t->integer('user_id')->unsigned()->nullable();
                $t->integer('seller_id')->unsigned()->nullable();
                $t->string('payment_type', 50)->nullable();
                $t->tinyInteger('payment_status')->default(0);
                $t->string('payment_status_string', 20)->default('unpaid');
                $t->string('delivery_status', 20)->default('pending');
                $t->string('shipping_type', 20)->default('home_delivery');
                $t->double('grand_total', 20, 2)->default(0);
                $t->double('coupon_discount', 20, 2)->default(0);
                $t->text('shipping_address')->nullable();
                $t->integer('pickup_point_id')->nullable();
                $t->text('note')->nullable();
                $t->integer('viewed')->default(0);
                $t->integer('delivery_viewed')->default(0);
                $t->integer('payment_status_viewed')->default(0);
                $t->tinyInteger('commission_calculated')->default(0);
                $t->string('trx_id', 255)->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('order_details')) {
            Schema::create('order_details', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('order_id')->unsigned();
                $t->integer('seller_id')->unsigned()->nullable();
                $t->integer('product_id')->unsigned();
                $t->text('variation')->nullable();
                $t->double('price', 20, 2)->default(0);
                $t->double('tax', 20, 2)->default(0);
                $t->double('shipping_cost', 20, 2)->default(0);
                $t->integer('quantity')->default(1);
                $t->string('delivery_status', 20)->default('pending');
                $t->string('payment_status', 20)->default('unpaid');
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 9. Reviews
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('product_id')->unsigned();
                $t->integer('user_id')->unsigned();
                $t->integer('rating')->default(0);
                $t->text('comment')->nullable();
                $t->integer('status')->default(1);
                $t->tinyInteger('viewed')->default(0);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 10. Wishlists
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('product_id')->unsigned();
                $t->integer('user_id')->unsigned();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 11. Sliders
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('sliders')) {
            Schema::create('sliders', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('position')->default(0);
                $t->string('photo', 255)->nullable();
                $t->string('link', 500)->nullable();
                $t->tinyInteger('published')->default(1);
                $t->string('heading_text_color', 30)->nullable();
                $t->string('heading_text', 255)->nullable();
                $t->string('subheading_text_color', 30)->nullable();
                $t->string('subheading_text', 255)->nullable();
                $t->string('button_text_color', 30)->nullable();
                $t->string('button_background_color', 30)->nullable();
                $t->string('button_text', 50)->nullable();
                $t->string('text_position', 30)->default('left');
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 12. Banners
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $t) {
                $t->increments('id');
                $t->string('photo', 255);
                $t->integer('position')->default(0);
                $t->string('link', 500)->nullable();
                $t->tinyInteger('published')->default(1);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 13. Settings
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('business_settings')) {
            Schema::create('business_settings', function (Blueprint $t) {
                $t->increments('id');
                $t->string('type', 100);
                $t->longText('value')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('general_settings')) {
            Schema::create('general_settings', function (Blueprint $t) {
                $t->increments('id');
                $t->string('site_name', 255)->nullable();
                $t->string('address', 500)->nullable();
                $t->string('phone', 30)->nullable();
                $t->string('email', 100)->nullable();
                $t->string('facebook', 255)->nullable();
                $t->string('instagram', 255)->nullable();
                $t->string('twitter', 255)->nullable();
                $t->string('youtube', 255)->nullable();
                $t->string('logo', 255)->nullable();
                $t->string('favicon', 255)->nullable();
                $t->string('admin_logo', 255)->nullable();
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->string('system_default_currency', 10)->default('USD');
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('seo_settings')) {
            Schema::create('seo_settings', function (Blueprint $t) {
                $t->increments('id');
                $t->text('meta_title')->nullable();
                $t->text('meta_description')->nullable();
                $t->text('meta_keywords')->nullable();
                $t->text('og_title')->nullable();
                $t->text('og_description')->nullable();
                $t->string('og_image', 255)->nullable();
                $t->text('twitter_title')->nullable();
                $t->text('twitter_description')->nullable();
                $t->string('twitter_image', 255)->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 14. Currencies
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 20);
                $t->string('symbol', 10);
                $t->double('exchange_rate', 20, 2)->default(1.00);
                $t->tinyInteger('status')->default(1);
                $t->string('code', 10);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 15. Home categories
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('home_categories')) {
            Schema::create('home_categories', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('category_id')->unsigned();
                $t->integer('position')->default(0);
                $t->tinyInteger('status')->default(1);
                $t->text('subsubcategories')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 16. Countries (address book)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $t) {
                $t->increments('id');
                $t->string('code', 10)->nullable();
                $t->string('name', 255);
                $t->string('phonecode', 10)->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 17. Addresses
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->string('address', 500);
                $t->string('country', 100)->nullable();
                $t->string('city', 100)->nullable();
                $t->string('postal_code', 20)->nullable();
                $t->string('phone', 30)->nullable();
                $t->tinyInteger('set_default')->default(0);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 18. Languages
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 100);
                $t->string('code', 20);
                $t->string('app_lang_code', 20)->nullable();
                $t->tinyInteger('status')->default(1);
                $t->tinyInteger('is_rtl')->default(0);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 19. Coupons
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $t) {
                $t->increments('id');
                $t->string('code', 50)->unique();
                $t->string('type', 20)->default('cart');
                $t->text('details')->nullable();
                $t->double('discount', 20, 2)->default(0);
                $t->string('discount_type', 20)->default('amount');
                $t->date('start_date')->nullable();
                $t->date('end_date')->nullable();
                $t->tinyInteger('status')->default(1);
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('coupon_id')->unsigned();
                $t->integer('user_id')->unsigned();
                $t->double('discount', 20, 2)->default(0);
                $t->integer('order_id')->unsigned()->nullable();
                $t->date('date')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 20. Subscribers
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('subscribers')) {
            Schema::create('subscribers', function (Blueprint $t) {
                $t->increments('id');
                $t->string('email', 100)->unique();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 21. Policies / Pages
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('policies')) {
            Schema::create('policies', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 50);
                $t->longText('content')->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 22. Wallets
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->double('amount', 20, 2)->default(0);
                $t->string('payment_method', 50)->nullable();
                $t->string('payment_details', 500)->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 23. Tickets (support)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $t) {
                $t->increments('id');
                $t->string('code', 50)->unique()->nullable();
                $t->integer('user_id')->unsigned();
                $t->integer('order_id')->nullable();
                $t->string('subject', 255);
                $t->text('details');
                $t->string('files', 1000)->nullable();
                $t->tinyInteger('viewed')->default(0);
                $t->tinyInteger('status')->default(0);
                $t->tinyInteger('client_viewed')->default(0);
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('ticket_id')->unsigned();
                $t->integer('user_id')->unsigned();
                $t->text('reply');
                $t->string('files', 1000)->nullable();
                $t->tinyInteger('viewed')->default(0);
                $t->tinyInteger('client_viewed')->default(0);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 24. Flash deals
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('flash_deals')) {
            Schema::create('flash_deals', function (Blueprint $t) {
                $t->increments('id');
                $t->string('title', 255);
                $t->integer('status')->default(1);
                $t->integer('featured')->default(0);
                $t->string('background_color', 30)->nullable();
                $t->string('text_color', 30)->nullable();
                $t->string('banner', 255)->nullable();
                $t->string('slug', 255)->nullable();
                $t->dateTime('start_date')->nullable();
                $t->dateTime('end_date')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('flash_deal_products')) {
            Schema::create('flash_deal_products', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('flash_deal_id')->unsigned();
                $t->integer('product_id')->unsigned();
                $t->double('discount', 20, 2)->default(0);
                $t->string('discount_type', 20)->default('amount');
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 25. Attributes / Colors
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('attributes')) {
            Schema::create('attributes', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('colors')) {
            Schema::create('colors', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 100);
                $t->string('code', 50);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 26. Roles / Staff
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 100);
                $t->text('permissions')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $t) {
                $t->increments('id');
                $t->integer('user_id')->unsigned();
                $t->integer('role_id')->unsigned()->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 27. Links (footer)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('links')) {
            Schema::create('links', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name', 255);
                $t->string('url', 500)->nullable();
                $t->integer('position')->default(0);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 28. Searches history
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('searches')) {
            Schema::create('searches', function (Blueprint $t) {
                $t->increments('id');
                $t->text('query');
                $t->integer('count')->default(1);
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 29. Payments
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $t) {
                $t->increments('id');
                $t->string('payment_type', 50);
                $t->integer('order_id')->nullable();
                $t->double('amount', 20, 2)->default(0);
                $t->string('payment_status', 20)->default('due');
                $t->text('payment_details')->nullable();
                $t->text('txn_code')->nullable();
                $t->string('method', 50)->nullable();
                $t->timestamps();
            });
        }

        // ─────────────────────────────────────────────
        // 30. App settings (used by admin)
        // ─────────────────────────────────────────────
        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $t) {
                $t->increments('id');
                $t->string('key', 100)->unique();
                $t->longText('value')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('searches');
        Schema::dropIfExists('links');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('flash_deal_products');
        Schema::dropIfExists('flash_deals');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('home_categories');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('seo_settings');
        Schema::dropIfExists('general_settings');
        Schema::dropIfExists('business_settings');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_stocks');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sub_sub_categories');
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('sellers');
    }
}
