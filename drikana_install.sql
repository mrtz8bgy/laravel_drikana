-- ===========================================================================
-- DRIKANA v2.0 — فایل نصب کامل دیتابیس (MySQL/MariaDB)
-- ===========================================================================
-- روش استفاده:
--   1) وارد phpMyAdmin شوید
--   2) دیتابیس drikana-db را انتخاب کنید
--   3) از تب "Import" این فایل را آپلود و اجرا کنید
-- یا در command line:
--   mysql -u root -p drikana-db < drikana_install.sql
-- ===========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:30";

-- ---------------------------------------------------------------------------
-- 1) users (افزودن ستون‌های گم‌شده؛ جدول users از قبل وجود دارد)
-- ---------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS _drikana_add_col;
DELIMITER ;;
CREATE PROCEDURE _drikana_add_col(IN t VARCHAR(64), IN c VARCHAR(64), IN d TEXT)
BEGIN
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=t AND COLUMN_NAME=c) THEN
        SET @s = CONCAT('ALTER TABLE `', t, '` ADD COLUMN `', c, '` ', d);
        PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
    END IF;
END;;
DELIMITER ;

ALTER TABLE `users` ENGINE = InnoDB;
CALL _drikana_add_col('users','address','TEXT NULL AFTER remember_token');
CALL _drikana_add_col('users','city','VARCHAR(100) NULL AFTER address');
CALL _drikana_add_col('users','postal_code','VARCHAR(20) NULL AFTER city');
CALL _drikana_add_col('users','phone','VARCHAR(20) NULL AFTER postal_code');
CALL _drikana_add_col('users','country','VARCHAR(50) NULL AFTER phone');
CALL _drikana_add_col('users','provider_id','VARCHAR(100) NULL AFTER country');
CALL _drikana_add_col('users','verification_code','VARCHAR(100) NULL AFTER provider_id');
CALL _drikana_add_col('users','phone_verified_at','TIMESTAMP NULL AFTER verification_code');
CALL _drikana_add_col('users','avatar','VARCHAR(255) NULL AFTER phone_verified_at');
CALL _drikana_add_col('users','user_type','VARCHAR(20) NULL DEFAULT ''customer'' AFTER avatar');
CALL _drikana_add_col('users','balance','DOUBLE(20,2) DEFAULT 0 AFTER user_type');
CALL _drikana_add_col('users','banned','TINYINT(1) DEFAULT 0 AFTER balance');
CALL _drikana_add_col('users','referred_by','INT NULL AFTER banned');
DROP PROCEDURE _drikana_add_col;

-- ---------------------------------------------------------------------------
-- 2) sellers
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sellers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `verification_status` TINYINT(1) NOT NULL DEFAULT 0,
  `verification_info` TEXT NULL,
  `cash_on_delivery_status` TINYINT(1) NOT NULL DEFAULT 0,
  `sslcommerz_status` TINYINT(1) NOT NULL DEFAULT 0,
  `stripe_status` TINYINT(1) NOT NULL DEFAULT 0,
  `paypal_client_id` VARCHAR(255) NULL,
  `paypal_client_secret` VARCHAR(255) NULL,
  `ssl_store_id` VARCHAR(255) NULL,
  `ssl_password` VARCHAR(255) NULL,
  `stripe_key` VARCHAR(255) NULL,
  `stripe_secret` VARCHAR(255) NULL,
  `instamojo_status` TINYINT(1) NOT NULL DEFAULT 0,
  `instamojo_api_key` VARCHAR(255) NULL,
  `instamojo_token` VARCHAR(255) NULL,
  `razorpay_status` TINYINT(1) NOT NULL DEFAULT 0,
  `razorpay_api_key` VARCHAR(255) NULL,
  `razorpay_secret` VARCHAR(255) NULL,
  `paypal_status` TINYINT(1) NOT NULL DEFAULT 0,
  `admin_to_pay` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 3) shops
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `shops` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `sliders` TEXT NULL,
  `address` TEXT NULL,
  `facebook` VARCHAR(255) NULL,
  `google` VARCHAR(255) NULL,
  `twitter` VARCHAR(255) NULL,
  `youtube` VARCHAR(255) NULL,
  `instagram` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `delivery_pickup_latitude` TEXT NULL,
  `delivery_pickup_longitude` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `shops_slug_unique` (`slug`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 4) customers
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 5) brands
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `logo` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 6) categories / sub_categories / sub_sub_categories
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `banner` VARCHAR(255) NULL,
  `icon` VARCHAR(255) NULL,
  `image` VARCHAR(255) NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `top` TINYINT(1) NOT NULL DEFAULT 0,
  `level` INT NOT NULL DEFAULT 0,
  `parent_id` INT NULL,
  `commission` DOUBLE(8,2) NOT NULL DEFAULT 0,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sub_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `icon` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `sub_categories_slug_unique` (`slug`), KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sub_sub_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `sub_category_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `sub_sub_categories_slug_unique` (`slug`),
  KEY `category_id` (`category_id`), KEY `sub_category_id` (`sub_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 7) products / product_stocks
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `added_by` VARCHAR(20) NOT NULL DEFAULT 'admin',
  `user_id` int(10) UNSIGNED NULL,
  `category_id` int(10) UNSIGNED NULL,
  `subcategory_id` int(10) UNSIGNED NULL,
  `subsubcategory_id` int(10) UNSIGNED NULL,
  `brand_id` int(10) UNSIGNED NULL,
  `photos` VARCHAR(2000) NULL,
  `thumbnail_img` VARCHAR(255) NULL,
  `featured_img` VARCHAR(255) NULL,
  `flash_deal_img` VARCHAR(255) NULL,
  `video_provider` VARCHAR(50) NULL,
  `video_link` VARCHAR(255) NULL,
  `tags` VARCHAR(1000) NULL,
  `description` LONGTEXT NULL,
  `unit_price` DOUBLE(20,2) NOT NULL,
  `purchase_price` DOUBLE(20,2) NULL,
  `variant_product` TINYINT(1) NOT NULL DEFAULT 0,
  `attributes` VARCHAR(1000) NULL,
  `choice_options` TEXT NULL,
  `colors` TEXT NULL,
  `variations` TEXT NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `todays_deal` TINYINT(1) NOT NULL DEFAULT 0,
  `current_stock` INT NOT NULL DEFAULT 0,
  `unit` VARCHAR(20) NOT NULL DEFAULT 'pcs',
  `weight` DOUBLE(8,2) NOT NULL DEFAULT 0,
  `discount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `discount_type` VARCHAR(20) NOT NULL DEFAULT 'amount',
  `tax` DOUBLE(8,2) NOT NULL DEFAULT 0,
  `tax_type` VARCHAR(20) NOT NULL DEFAULT 'amount',
  `shipping_type` VARCHAR(20) NOT NULL DEFAULT 'flat_rate',
  `shipping_cost` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `num_of_sale` INT NOT NULL DEFAULT 0,
  `earn_point` DOUBLE(8,2) NOT NULL DEFAULT 0,
  `refundable` TINYINT(1) NOT NULL DEFAULT 1,
  `digital` TINYINT(1) NOT NULL DEFAULT 0,
  `rating` DOUBLE(3,2) NOT NULL DEFAULT 0,
  `barcode` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `pdf` VARCHAR(255) NULL,
  `est_shipping_days` INT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `category_id` (`category_id`), KEY `brand_id` (`brand_id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_stocks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variant` VARCHAR(255) NULL,
  `sku` VARCHAR(255) NULL,
  `price` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `qty` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 8) carts
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL,
  `temp_user_id` VARCHAR(255) NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation` TEXT NULL,
  `price` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `tax` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `shipping_cost` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `quantity` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 9) orders / order_details
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `user_id` int(10) UNSIGNED NULL,
  `seller_id` int(10) UNSIGNED NULL,
  `payment_type` VARCHAR(50) NULL,
  `payment_status` TINYINT(1) NOT NULL DEFAULT 0,
  `payment_status_string` VARCHAR(20) NOT NULL DEFAULT 'unpaid',
  `delivery_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `shipping_type` VARCHAR(20) NOT NULL DEFAULT 'home_delivery',
  `grand_total` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `coupon_discount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `shipping_address` TEXT NULL,
  `pickup_point_id` INT NULL,
  `note` TEXT NULL,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `delivery_viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `payment_status_viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `commission_calculated` TINYINT(1) NOT NULL DEFAULT 0,
  `trx_id` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(10) UNSIGNED NOT NULL,
  `seller_id` int(10) UNSIGNED NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation` TEXT NULL,
  `price` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `tax` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `shipping_cost` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `quantity` INT NOT NULL DEFAULT 1,
  `delivery_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `payment_status` VARCHAR(20) NOT NULL DEFAULT 'unpaid',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `order_id` (`order_id`), KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 10) reviews
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rating` INT NOT NULL DEFAULT 0,
  `comment` TEXT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `product_id` (`product_id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 11) wishlists
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `product_id` (`product_id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 12) sliders
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `position` INT NOT NULL DEFAULT 0,
  `photo` VARCHAR(255) NULL,
  `link` VARCHAR(500) NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `heading_text_color` VARCHAR(30) NULL,
  `heading_text` VARCHAR(255) NULL,
  `subheading_text_color` VARCHAR(30) NULL,
  `subheading_text` VARCHAR(255) NULL,
  `button_text_color` VARCHAR(30) NULL,
  `button_background_color` VARCHAR(30) NULL,
  `button_text` VARCHAR(50) NULL,
  `text_position` VARCHAR(30) NOT NULL DEFAULT 'left',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 13) banners
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `photo` VARCHAR(255) NOT NULL,
  `position` INT NOT NULL DEFAULT 0,
  `link` VARCHAR(500) NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 14) business_settings / general_settings / seo_settings / app_settings
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(100) NOT NULL,
  `value` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `general_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name` VARCHAR(255) NULL,
  `address` VARCHAR(500) NULL,
  `phone` VARCHAR(30) NULL,
  `email` VARCHAR(100) NULL,
  `facebook` VARCHAR(255) NULL,
  `instagram` VARCHAR(255) NULL,
  `twitter` VARCHAR(255) NULL,
  `youtube` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `favicon` VARCHAR(255) NULL,
  `admin_logo` VARCHAR(255) NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `system_default_currency` VARCHAR(10) NOT NULL DEFAULT 'IRT',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `meta_keywords` TEXT NULL,
  `og_title` TEXT NULL,
  `og_description` TEXT NULL,
  `og_image` VARCHAR(255) NULL,
  `twitter_title` TEXT NULL,
  `twitter_description` TEXT NULL,
  `twitter_image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL,
  `value` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `app_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 15) currencies
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `symbol` VARCHAR(10) NOT NULL,
  `exchange_rate` DOUBLE(20,2) NOT NULL DEFAULT 1,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `code` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 16) home_categories
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `home_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(10) UNSIGNED NOT NULL,
  `position` INT NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `subsubcategories` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 17) countries / addresses
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(10) NULL,
  `name` VARCHAR(255) NOT NULL,
  `phonecode` VARCHAR(10) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `country` VARCHAR(100) NULL,
  `city` VARCHAR(100) NULL,
  `postal_code` VARCHAR(20) NULL,
  `phone` VARCHAR(30) NULL,
  `set_default` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 18) languages
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `app_lang_code` VARCHAR(20) NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `is_rtl` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 19) coupons / coupon_usages
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `type` VARCHAR(20) NOT NULL DEFAULT 'cart',
  `details` TEXT NULL,
  `discount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `discount_type` VARCHAR(20) NOT NULL DEFAULT 'amount',
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `coupon_usages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `discount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `order_id` int(10) UNSIGNED NULL,
  `date` DATE NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 20) subscribers
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 21) policies
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `policies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `content` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 22) wallets
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `amount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `payment_method` VARCHAR(50) NULL,
  `payment_details` VARCHAR(500) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 23) tickets / ticket_replies
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` INT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `details` TEXT NOT NULL,
  `files` VARCHAR(1000) NULL,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `client_viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `tickets_code_unique` (`code`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reply` TEXT NOT NULL,
  `files` VARCHAR(1000) NULL,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `client_viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `ticket_id` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 24) flash_deals / flash_deal_products
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `flash_deals` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `status` INT NOT NULL DEFAULT 1,
  `featured` INT NOT NULL DEFAULT 0,
  `background_color` VARCHAR(30) NULL,
  `text_color` VARCHAR(30) NULL,
  `banner` VARCHAR(255) NULL,
  `slug` VARCHAR(255) NULL,
  `start_date` DATETIME NULL,
  `end_date` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `flash_deal_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `flash_deal_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `discount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `discount_type` VARCHAR(20) NOT NULL DEFAULT 'amount',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `flash_deal_id` (`flash_deal_id`), KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 25) attributes / colors
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 26) roles / staff
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `permissions` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 27) links (footer)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(500) NULL,
  `position` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 28) searches
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `query` TEXT NOT NULL,
  `count` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- 29) payments
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_type` VARCHAR(50) NOT NULL,
  `order_id` INT NULL,
  `amount` DOUBLE(20,2) NOT NULL DEFAULT 0,
  `payment_status` VARCHAR(20) NOT NULL DEFAULT 'due',
  `payment_details` TEXT NULL,
  `txn_code` TEXT NULL,
  `method` VARCHAR(50) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================================
-- ==== داده‌های پیش‌فرض (Seeds)
-- ===========================================================================
-- ۱) ادمین پیش‌فرض
INSERT IGNORE INTO `users` (`id`,`name`,`email`,`password`,`user_type`,`role`,`email_verified_at`,`phone`,`city`,`address`,`created_at`,`updated_at`)
VALUES (1,'مدیریت دریکانا','admin@drikana.com','$2y$10$EevvGh1JxK7jRkZ8Qe3Ybu0x8Nn3bTqOY3VvH2n2U4w9Z0mG7g3Gy','admin','admin',NOW(),'09120000000','تهران','تهران، بازار بزرگ طلا و جواهر',NOW(),NOW());

-- در صورت وجود پسورد اشتباه، پسورد را ریست می‌کنیم (123456)
UPDATE `users` SET `password` = '$2y$10$Eqrgi/fpC06smGaWs2EruOgZx7IyN46YB2WaMkwp4exo/dvHIXzvS' WHERE `email` = 'admin@drikana.com';

-- ۲) ارزها
INSERT IGNORE INTO `currencies` (`id`,`name`,`symbol`,`code`,`exchange_rate`,`status`,`created_at`,`updated_at`) VALUES
(1,'تومان','تومان','IRT',1,1,NOW(),NOW()),
(2,'ریال','ریال','IRR',10,0,NOW(),NOW()),
(3,'US Dollar','$','USD',0.00002,0,NOW(),NOW());

-- ۳) زبان‌ها
INSERT IGNORE INTO `languages` (`id`,`name`,`code`,`app_lang_code`,`status`,`is_rtl`,`created_at`,`updated_at`) VALUES
(1,'Persian (فارسی)','fa','fa',1,1,NOW(),NOW()),
(2,'English','en','en',0,0,NOW(),NOW());

-- ۴) تنظیمات عمومی
INSERT IGNORE INTO `general_settings` (`id`,`site_name`,`address`,`phone`,`email`,`facebook`,`instagram`,`twitter`,`youtube`,`logo`,`favicon`,`admin_logo`,`meta_title`,`meta_description`,`system_default_currency`,`created_at`,`updated_at`)
VALUES (1,'دریکانا | بازار آنلاین طلا و جواهر','تهران، بازار بزرگ طلا، پلاک ۱','021-88776655','info@drikana.com','#','#','#','#','frontend/images/logo/drikana-logo.svg','frontend/images/logo/favicon.svg','frontend/images/logo/drikana-logo.svg','دریکانا | مرجع تخصصی خرید، فروش و استعلام طلا، جواهر و ساعت لوکس','دریکانا، بازار آنلاین تخصصی طلا، جواهر، سنگ‌های قیمتی، ساعت‌های لوکس و سکه. استعلام اصالت شناسنامه، رهگیری و گزارش سرقت جواهرات.','IRT',NOW(),NOW());

-- ۵) تنظیمات کسب‌وکار (vendor_system=0 تا فروشنده‌ای اضافه نشود)
INSERT IGNORE INTO `business_settings` (`type`,`value`,`created_at`,`updated_at`) VALUES
('vendor_system_activation','0',NOW(),NOW()),
('digital_product','0',NOW(),NOW()),
('wallet_system','1',NOW(),NOW()),
('coupon_system','1',NOW(),NOW()),
('pickup_point','0',NOW(),NOW()),
('refund_request','1',NOW(),NOW()),
('otp_system','0',NOW(),NOW()),
('guest_checkout_activation','1',NOW(),NOW()),
('conversation_system','0',NOW(),NOW()),
('color_option','1',NOW(),NOW()),
('attribute_option','1',NOW(),NOW()),
('seller_verification','0',NOW(),NOW()),
('email_verification','0',NOW(),NOW()),
('facebook_pixel','0',NOW(),NOW()),
('google_analytics','0',NOW(),NOW()),
('facebook_chat','0',NOW(),NOW()),
('twitter_pixel','0',NOW(),NOW()),
('google_recaptcha','0',NOW(),NOW()),
('mail_verify','0',NOW(),NOW()),
('sslcommerz_payment','0',NOW(),NOW()),
('stripe_payment','0',NOW(),NOW()),
('paypal_payment','0',NOW(),NOW()),
('razorpay_payment','0',NOW(),NOW()),
('instamojo_payment','0',NOW(),NOW()),
('cash_payment','1',NOW(),NOW()),
('bank_payment','0',NOW(),NOW()),
('wallet_payment','0',NOW(),NOW()),
('sslcommerz_sandbox','1',NOW(),NOW()),
('paypal_sandbox','1',NOW(),NOW()),
('stripe_sandbox','1',NOW(),NOW()),
('razorpay_sandbox','1',NOW(),NOW()),
('instamojo_sandbox','1',NOW(),NOW()),
('top_categories','4',NOW(),NOW()),
('best_selling','1',NOW(),NOW()),
('best_selling_products','12',NOW(),NOW()),
('homepage_slider_count','3',NOW(),NOW()),
('menu_categories','10',NOW(),NOW()),
('page_builder','1',NOW(),NOW()),
('tax','0',NOW(),NOW()),
('shipping_cost','0',NOW(),NOW()),
('shipping_type','flat_rate',NOW(),NOW()),
('shipping_delivery_type','home_delivery',NOW(),NOW()),
('force_ssl','0',NOW(),NOW()),
('vendor_commission','10',NOW(),NOW()),
('website_color','#d4af37',NOW(),NOW()),
('base_currency','IRT',NOW(),NOW()),
('current_version','2.0',NOW(),NOW());

-- ۶) SEO
INSERT IGNORE INTO `seo_settings` (`id`,`meta_title`,`meta_description`,`meta_keywords`,`created_at`,`updated_at`)
VALUES (1,'دریکانا | بازار تخصصی طلا، جواهر و ساعت لوکس','خرید و فروش امن طلا، جواهر، الماس، ساعت‌های لوکس، سکه و نقره با استعلام اصالت آنلاین.','طلا,جواهر,الماس,ساعت لوکس,سکه,نقره,انگشتر عقد,گردنبند,دریکانا',NOW(),NOW());

-- ۷) فروشگاه پیش‌فرض ادمین
INSERT IGNORE INTO `shops` (`id`,`user_id`,`name`,`logo`,`slug`,`address`,`meta_title`,`meta_description`,`created_at`,`updated_at`)
VALUES (1,1,'گالری رسمی دریکانا','frontend/images/logo/drikana-logo.svg','drikana-official','تهران، بازار بزرگ طلا، پلاک ۱','گالری رسمی دریکانا','گالری رسمی و شعبه مرکزی دریکانا، ارائه دهنده طلا، جواهر و ساعت‌های لوکس اصل.',NOW(),NOW());

INSERT IGNORE INTO `sellers` (`id`,`user_id`,`verification_status`,`cash_on_delivery_status`,`admin_to_pay`,`created_at`,`updated_at`)
VALUES (1,1,1,1,0,NOW(),NOW());

-- ۸) اسلایدرها
INSERT IGNORE INTO `sliders` (`id`,`position`,`photo`,`link`,`published`,`heading_text`,`heading_text_color`,`subheading_text`,`subheading_text_color`,`button_text`,`button_text_color`,`button_background_color`,`text_position`,`created_at`,`updated_at`) VALUES
(1,0,'frontend/images/drikana/hero-bg.svg','#categories',1,'مجموعه‌ای بی‌نظیر از طلا و جواهرات اصل','#ffffff','استعلام اصالت آنلاین، رهگیری هوشمند و خرید امن از بازار تخصصی دریکانا','#d4af37','مشاهده محصولات','#07091a','#d4af37','right',NOW(),NOW()),
(2,1,'frontend/images/drikana/hero-bg.svg','/jewelry/verify',1,'استعلام اصالت شناسنامه طلا','#ffffff','با وارد کردن کد شناسنامه، اصالت قطعه طلا یا جواهر را بررسی کنید','#d4af37','استعلام آنلاین','#07091a','#d4af37','right',NOW(),NOW()),
(3,2,'frontend/images/drikana/hero-bg.svg','/jewelry/report-stolen',1,'گزارش سرقت یا مفقودی جواهر','#ffffff','با ثبت جواهر مفقودی خود به شبکه سراسری رهگیری دریکانا بپیوندید','#d4af37','ثبت گزارش','#07091a','#d4af37','right',NOW(),NOW());

-- ۹) لینک‌های فوتر
INSERT IGNORE INTO `links` (`id`,`name`,`url`,`position`,`created_at`,`updated_at`) VALUES
(1,'درباره دریکانا','/pages/about',1,NOW(),NOW()),
(2,'قوانین استفاده','/pages/terms',2,NOW(),NOW()),
(3,'حریم خصوصی','/pages/privacy',3,NOW(),NOW()),
(4,'تماس با ما','/pages/contact',4,NOW(),NOW()),
(5,'استعلام اصالت','/jewelry/verify',5,NOW(),NOW()),
(6,'ثبت گزارش سرقت','/jewelry/report-stolen',6,NOW(),NOW());

-- ۱۰) سیاست‌ها
INSERT IGNORE INTO `policies` (`id`,`name`,`content`,`created_at`,`updated_at`) VALUES
(1,'return_policy','در صورت مغایرت با شناسنامه، بازگشت وجه تا ۷ روز.',NOW(),NOW()),
(2,'support_policy','پشتیبانی ۲۴ ساعته دریکانا.',NOW(),NOW()),
(3,'privacy_policy','اطلاعات کاربران شما نزد ما محفوظ است.',NOW(),NOW()),
(4,'terms_conditions','قوانین کلی استفاده از بازار دریکانا.',NOW(),NOW()),
(5,'seller_policy','فروشندگان منتخب پس از احراز هویت.',NOW(),NOW());

-- ۱۱) دسته‌بندی‌های جواهر
INSERT IGNORE INTO `categories` (`id`,`name`,`icon`,`image`,`published`,`featured`,`top`,`level`,`commission`,`slug`,`meta_title`,`meta_description`,`created_at`,`updated_at`) VALUES
(1,'انگشتر و حلقه','frontend/images/drikana/ring.svg','frontend/images/drikana/ring.svg',1,1,1,0,10,'rings','انگشتر و حلقه','انواع انگشتر نامزدی، عقد و حلقه ازدواج طلا و جواهر',NOW(),NOW()),
(2,'گردنبند و آویز','frontend/images/drikana/necklace.svg','frontend/images/drikana/necklace.svg',1,1,1,0,10,'necklaces','گردنبند و آویز','گردنبند طلا، مروارید و زنجیرهای لوکس',NOW(),NOW()),
(3,'ساعت لوکس','frontend/images/drikana/watch.svg','frontend/images/drikana/watch.svg',1,1,1,0,15,'luxury-watches','ساعت لوکس','ساعت‌های اصل Rolex, Cartier, Omega و برندهای لوکس',NOW(),NOW()),
(4,'الماس و سنگ قیمتی','frontend/images/drikana/diamond.svg','frontend/images/drikana/diamond.svg',1,1,1,0,12,'diamonds-gemstones','الماس و سنگ قیمتی','الماس تراش، زمرد، یاقوت، یاقوت کبود',NOW(),NOW()),
(5,'دستبند و گوشواره','frontend/images/drikana/gift.svg','frontend/images/drikana/gift.svg',1,1,1,0,10,'bracelets-earrings','دستبند و گوشواره','دستبند طلا، النگو، گوشواره',NOW(),NOW()),
(6,'سکه و شمش','frontend/images/drikana/coin.svg','frontend/images/drikana/coin.svg',1,1,1,0,5,'coins-bullion','سکه و شمش','سکه بهار آزادی، امامی، نیم‌ست، ربع‌ست، شمش طلا',NOW(),NOW()),
(7,'نیم‌ست و سرویس کامل','frontend/images/drikana/crown.svg','frontend/images/drikana/crown.svg',1,1,1,0,12,'jewelry-sets','نیم‌ست و سرویس کامل','سرویس عروس، نیم‌ست یاقوت، زمرد، الماس',NOW(),NOW()),
(8,'عتیقه و کلکسیون','frontend/images/drikana/antique.svg','frontend/images/drikana/antique.svg',1,0,0,0,15,'antiques','عتیقه و کلکسیون','جواهرات عتیقه و کلکسیونی',NOW(),NOW());

-- چند زیردسته
INSERT IGNORE INTO `sub_categories` (`id`,`category_id`,`name`,`published`,`slug`,`created_at`,`updated_at`) VALUES
(1,1,'انگشتر نامزدی',1,'engagement-rings',NOW(),NOW()),
(2,1,'حلقه ازدواج',1,'wedding-bands',NOW(),NOW()),
(3,1,'انگشتر مردانه',1,'mens-rings',NOW(),NOW()),
(4,2,'گردنبند مروارید',1,'pearl-necklaces',NOW(),NOW()),
(5,2,'زنجیر طلا',1,'gold-chains',NOW(),NOW()),
(6,3,'ساعت مردانه',1,'mens-watches',NOW(),NOW()),
(7,3,'ساعت زنانه',1,'womens-watches',NOW(),NOW()),
(8,4,'الماس برلیان',1,'diamonds',NOW(),NOW()),
(9,4,'زمرد',1,'emeralds',NOW(),NOW()),
(10,4,'یاقوت سرخ',1,'rubies',NOW(),NOW()),
(11,5,'دستبند طلا',1,'bracelets',NOW(),NOW()),
(12,5,'گوشواره',1,'earrings',NOW(),NOW()),
(13,6,'سکه بهار آزادی',1,'bahar-azadi',NOW(),NOW()),
(14,6,'شمش طلا',1,'gold-bars',NOW(),NOW()),
(15,7,'سرویس عروس',1,'bridal-sets',NOW(),NOW()),
(16,7,'نیم‌ست',1,'half-sets',NOW(),NOW()),
(17,8,'ساعت عتیقه',1,'vintage-watches',NOW(),NOW()),
(18,8,'جواهرات کلکسیونی',1,'collectible-jewelry',NOW(),NOW());

-- ۱۲) برندها
INSERT IGNORE INTO `brands` (`id`,`name`,`published`,`slug`,`created_at`,`updated_at`) VALUES
(1,'Rolex',1,'rolex',NOW(),NOW()),
(2,'Cartier',1,'cartier',NOW(),NOW()),
(3,'Tiffany & Co.',1,'tiffany-co',NOW(),NOW()),
(4,'Bvlgari',1,'bvlgari',NOW(),NOW()),
(5,'Omega',1,'omega',NOW(),NOW()),
(6,'Vacheron Constantin',1,'vacheron-constantin',NOW(),NOW()),
(7,'Van Cleef & Arpels',1,'van-cleef-arpels',NOW(),NOW()),
(8,'Drikana Signature',1,'drikana-signature',NOW(),NOW());

-- ۱۳) محصولات نمونه
INSERT IGNORE INTO `products` (`id`,`name`,`added_by`,`user_id`,`category_id`,`brand_id`,`thumbnail_img`,`photos`,`unit_price`,`purchase_price`,`discount`,`discount_type`,`description`,`tags`,`slug`,`featured`,`todays_deal`,`published`,`refundable`,`variant_product`,`current_stock`,`rating`,`num_of_sale`,`unit`,`tax`,`tax_type`,`shipping_type`,`shipping_cost`,`meta_title`,`meta_description`,`created_at`,`updated_at`) VALUES
(1,'انگشتر الماس نامزدی دریکانا سری رؤیا','admin',1,1,8,'frontend/images/drikana/ring.svg','frontend/images/drikana/ring.svg',285000000,260000000,5,'percent','<p>انگشتر نامزدی طلای ۱۸ عیار با الماس ۰٫۷ قیراط برلیان تراش. همراه با شناسنامه معتبر GIA.</p>','انگشتر,الماس,نامزدی,طلا ۱۸','diamond-engagement-ring-roy',1,0,1,1,0,3,5.00,4,'عدد',0,'amount','flat_rate',0,'انگشتر الماس نامزدی دریکانا سری رؤیا','انگشتر نامزدی طلای ۱۸ عیار با الماس ۰٫۷ قیراط برلیان تراش',NOW(),NOW()),
(2,'گردنبند مروارید طبیعی کواترو','admin',1,2,3,'frontend/images/drikana/necklace.svg','frontend/images/drikana/necklace.svg',52000000,48000000,0,'amount','<p>گردنبند مروارید طبیعی آب شیرین، رشته ۴۵ سانتی، قفل طلای ۱۸ عیار.</p>','گردنبند,مروارید,طلا','pearl-necklace-cuatro',1,1,1,1,0,5,4.90,7,'عدد',0,'amount','flat_rate',0,'گردنبند مروارید طبیعی کواترو','گردنبند مروارید طبیعی آب شیرین با قفل طلای ۱۸ عیار',NOW(),NOW()),
(3,'ساعت مردانه Rolex Submariner Date اصل','admin',1,3,1,'frontend/images/drikana/watch.svg','frontend/images/drikana/watch.svg',4200000000,3900000000,3,'percent','<p>ساعت اتوماتیک مردانه رولکس سابمارینر دیت، استیل ضدخش، مقاوم در برابر آب تا عمق ۳۰۰ متر. با جعبه و کارت گارانتی اصلی.</p>','ساعت,رولکس,لوکس,مردانه','rolex-submariner-date',1,0,1,0,0,1,5.00,2,'عدد',0,'amount','flat_rate',0,'ساعت مردانه Rolex Submariner Date','ساعت مردانه اصل رولکس سابمارینر دیت با گارانتی',NOW(),NOW()),
(4,'دستبند زمرد نقره با طلای سفید','admin',1,5,8,'frontend/images/drikana/diamond.svg','frontend/images/drikana/diamond.svg',145000000,130000000,0,'amount','<p>دستبند زنانه با زمرد کلمبیا و طلای سفید ۱۸ عیار، با شناسنامه اتحادیه طلا.</p>','دستبند,زمرد,طلا سفید','emerald-bracelet-silver-gold',1,1,1,1,0,2,4.80,3,'عدد',0,'amount','flat_rate',0,'دستبند زمرد نقره با طلای سفید','دستبند زنانه با زمرد کلمبیا و طلای سفید ۱۸ عیار',NOW(),NOW()),
(5,'نیم‌ست یاقوت سرخ آتوسا','admin',1,7,8,'frontend/images/drikana/crown.svg','frontend/images/drikana/crown.svg',195000000,180000000,7,'percent','<p>نیم‌ست یاقوت سرخ برمه با طلای ۱۸ عیار شامل گردنبند، گوشواره و انگشتر.</p>','نیم ست,یاقوت,زرشکی,عروس','ruby-half-set-atousa',1,0,1,1,0,2,5.00,1,'عدد',0,'amount','flat_rate',0,'نیم‌ست یاقوت سرخ آتوسا','نیم‌ست یاقوت سرخ برمه با طلای ۱۸ عیار شامل گردنبند، گوشواره و انگشتر',NOW(),NOW()),
(6,'حلقه ازدواج Cartier Love اصل','admin',1,1,2,'frontend/images/drikana/ring.svg','frontend/images/drikana/ring.svg',98000000,90000000,0,'amount','<p>حلقه ازدواج کارتیه لاو طلای ۱۸ عیار، با حک لوگو، اصل با جعبه و برگ ضمانت.</p>','حلقه,ازدواج,کارتیه,طلا ۱۸','cartier-love-wedding-ring',1,1,1,0,0,4,4.90,5,'عدد',0,'amount','flat_rate',0,'حلقه ازدواج Cartier Love اصل','حلقه ازدواج کارتیه لاو طلای ۱۸ عیار با جعبه و برگ ضمانت',NOW(),NOW());

-- featured_img = thumbnail_img
UPDATE `products` SET `featured_img` = `thumbnail_img`;

-- Home categories (first 4)
INSERT IGNORE INTO `home_categories` (`id`,`category_id`,`position`,`status`,`created_at`,`updated_at`) VALUES
(1,1,0,1,NOW(),NOW()),
(2,2,1,1,NOW(),NOW()),
(3,3,2,1,NOW(),NOW()),
(4,4,3,1,NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
-- ===========================================================================
-- پایان فایل — پس از اجرا، کش لاراول را پاک کنید:
--   php artisan view:clear
--   php artisan config:clear
--   php artisan cache:clear
-- ===========================================================================
