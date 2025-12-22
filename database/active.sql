-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 10, 2020 at 06:23 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drikanadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
CREATE TABLE IF NOT EXISTS `addons`
(
    `id`                int(11)   NOT NULL AUTO_INCREMENT,
    `name`              varchar(255) COLLATE utf32_unicode_ci  DEFAULT NULL,
    `unique_identifier` varchar(255) COLLATE utf32_unicode_ci  DEFAULT NULL,
    `version`           varchar(255) COLLATE utf32_unicode_ci  DEFAULT NULL,
    `activated`         int(1)    NOT NULL                     DEFAULT 1,
    `image`             varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
    `created_at`        timestamp NULL                         DEFAULT current_timestamp(),
    `updated_at`        timestamp NOT NULL                     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 10
  DEFAULT CHARSET = utf32
  COLLATE = utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses`
(
    `id`          int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`     int(11)   NOT NULL,
    `address`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `country`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `city`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `postal_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `phone`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `set_default` int(1)    NOT NULL                   DEFAULT 0,
    `created_at`  timestamp NOT NULL                   DEFAULT current_timestamp(),
    `updated_at`  timestamp NOT NULL                   DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `address`, `country`, `city`, `postal_code`, `phone`, `set_default`,
                         `created_at`, `updated_at`)
VALUES (1, 8, 'سیدخندان پلاک 40', 'Iran (Islamic Republic of)', 'تهران', '1476822666', '09126769825', 1,
        '2020-07-11 06:46:53', '2020-07-24 03:09:45'),
       (3, 3, 'ولیعصر پاساژ رضا پلاک 1234', 'Iran (Islamic Republic of)', 'تهران', '1476822666', '9121112233', 1,
        '2020-07-12 04:17:28', '2020-07-12 04:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE IF NOT EXISTS `app_settings`
(
    `id`              int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `logo`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `currency_id`     int(11)                              DEFAULT NULL,
    `currency_format` char(10) COLLATE utf8_unicode_ci     DEFAULT NULL,
    `facebook`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `twitter`         varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `instagram`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `youtube`         varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `google_plus`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at`      timestamp        NULL                DEFAULT NULL,
    `updated_at`      timestamp        NULL                DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `name`, `logo`, `currency_id`, `currency_format`, `facebook`, `twitter`, `instagram`,
                            `youtube`, `google_plus`, `created_at`, `updated_at`)
VALUES (1, 'Active eCommerce', 'uploads/logo/matggar.png', 1, 'symbol', 'https://facebook.com', 'https://twitter.com',
        'https://instagram.com', 'https://youtube.com', 'https://google.com', '2019-08-04 16:39:15',
        '2019-08-04 16:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
    `created_at` timestamp NOT NULL                    DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL                    DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf32
  COLLATE = utf32_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`)
VALUES (1, 'سایز', '2020-02-24 05:55:07', '2020-02-24 05:55:07'),
       (2, 'قد', '2020-02-24 05:55:13', '2020-07-15 05:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `photo`      varchar(255) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `url`        varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
    `position`   int(11)   NOT NULL                    DEFAULT 1,
    `published`  int(1)    NOT NULL                    DEFAULT 0,
    `created_at` timestamp NOT NULL                    DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL                    DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 11
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `photo`, `url`, `position`, `published`, `created_at`, `updated_at`)
VALUES (4, 'uploads/banners/gz5xZsK2IcwXKzx8BIpybd0zflho4C1QeMsEiMWr.jpeg', '#', 1, 1, '2019-03-12 05:58:23',
        '2020-07-21 23:23:33'),
       (5, 'uploads/banners/dBfaGAeRI34g1Rpb8piCwJSpdeZ9dhrkYEVIkoFe.jpeg', '#', 1, 1, '2019-03-12 05:58:41',
        '2020-07-21 23:23:52'),
       (6, 'uploads/banners/5sLkaJpdA65f7xLuAanAT0uwsTzKmrRsH6kg3WkG.jpeg', '#', 2, 1, '2019-03-12 05:58:52',
        '2020-07-16 14:14:29'),
       (7, 'uploads/banners/UlzyjfJZavLAJHJ4ItrdBeOh6k02UJnyjMSW6vkX.jpeg', '#', 2, 1, '2019-05-26 05:16:38',
        '2020-07-16 14:14:12'),
       (9, 'uploads/banners/yLyzoGfMgZHtf8XfQ706SDV7cm420qdv4wRU3DVy.jpeg', '#', 1, 1, '2019-06-11 05:00:15',
        '2020-07-21 23:24:09'),
       (10, 'uploads/banners/0WFjYCatbBevDobv52ePJyj5ZkHmnjurMBbrJCIh.jpeg', '#', 1, 1, '2019-06-11 05:00:24',
        '2020-07-21 23:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands`
(
    `id`               int(11)                             NOT NULL AUTO_INCREMENT,
    `name`             varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `logo`             varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `top`              int(1)                              NOT NULL DEFAULT 0,
    `slug`             varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_title`       varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_description` text COLLATE utf8_unicode_ci                 DEFAULT NULL,
    `created_at`       timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at`       timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 20
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `top`, `slug`, `meta_title`, `meta_description`, `created_at`, `updated_at`)
VALUES (5, 'جی پلاس', 'uploads/brands/cKyXTaaOYH0ECc53CFYu7PLJsWvhKAIKbwbseMRc.jpeg', 1, 'Gplus', 'جی پلاس', NULL,
        '2020-07-04 09:50:03', '2020-07-16 14:58:56'),
       (6, 'سامسونگ', 'uploads/brands/KPa54NEfdiN4qCBXo5T82OPzpvqLKU1eITKsB6eN.png', 1, '-Va4QC', 'سامسونگ', NULL,
        '2020-07-05 07:34:22', '2020-07-16 14:58:56'),
       (7, 'هوآوی', 'uploads/brands/dkAwFnrw5R2yWxnrFbe4MkMAWcU7O1mr5Juw2GjJ.jpeg', 1, '-bWd66', 'هوآوی', NULL,
        '2020-07-05 07:34:35', '2020-07-16 14:58:56'),
       (8, 'شیائومی', 'uploads/brands/fsxU3152C6Z0lzb3tlAVgN0JlqeepgWRYOVFWzEA.png', 1, '-pNUge', 'شیائومی', NULL,
        '2020-07-08 01:32:31', '2020-07-16 14:58:56'),
       (9, 'متفرقه', NULL, 0, '-aKSdF', 'متفرقه', NULL, '2020-07-18 01:33:58', '2020-07-18 01:33:58'),
       (10, 'ایسوس', 'uploads/brands/L4Pu4BBpCAw4SOWAIsUfOR0igsuKmxJ87vUOVAlf.png', 1, '-Q3XL8', 'ایسوس', NULL,
        '2020-07-18 01:34:35', '2020-08-01 12:28:14'),
       (11, 'امرسان', 'uploads/brands/LZNqwM3tgx6mRxLAupgmcKhUZjouPnZ2t4g3psbA.jpeg', 0, '-41Ql1', 'امرسان', NULL,
        '2020-07-18 01:35:19', '2020-08-01 12:00:08'),
       (12, 'اسنوا', 'uploads/brands/7sCPPvkTixbMCleMw5pyjt3RDI0IpgDvz4Ar6gZ8.png', 0, '-gQgAK', 'اسنوا', NULL,
        '2020-07-18 01:35:54', '2020-08-01 12:01:49'),
       (13, 'ایکس ویژن', 'uploads/brands/3vo9pnriXjF63bf5aQhtTN6JizWSNn7zdCp00igq.jpeg', 1, '--ZEU5s', NULL, NULL,
        '2020-07-18 01:36:05', '2020-08-01 12:28:14'),
       (14, 'شهاب', 'uploads/brands/il0Uvj2lLBWZySZJDXBCGWPxvnL9Ad1GSx0rjTrB.png', 0, '-wCNN1', 'شهاب', NULL,
        '2020-07-18 01:36:18', '2020-08-01 12:16:21'),
       (15, 'دوو', 'uploads/brands/GfIukhtsv9w15O22br2FacD5vVQc7CXf4PqQVTvx.jpeg', 1, '-cAY6I', 'دوو', NULL,
        '2020-07-18 07:26:48', '2020-08-01 12:28:14'),
       (16, 'پارس خزر', 'uploads/brands/K4s2omQVnuIcX84JLjLEc8pfAdUdjg3zoMbCUSU2.png', 0, '-uJs1U', 'پارس خزر', NULL,
        '2020-07-18 07:38:47', '2020-08-01 12:22:18'),
       (17, 'ال جی', 'uploads/brands/PA6Fxv6VkBOUpArae5DAyFapRB4DTSMIplN2MlEH.png', 1, '--czohX', 'ال جی', NULL,
        '2020-07-18 08:22:21', '2020-08-01 12:28:14'),
       (18, 'سونی', 'uploads/brands/BVlJAh374KLhSjuPSsKGeJSQ0ZddCDtLs721zSSS.jpeg', 1, '-EIPrx', 'سونی', NULL,
        '2020-07-18 08:22:35', '2020-08-01 12:28:14'),
       (19, 'فیلیپس', 'uploads/brands/ID28ldqxUQtnAVy5mm2e5e5nUZwl70VpXdN7GOPw.png', 1, '-KxcJw', 'فیلیپس', NULL,
        '2020-07-18 08:22:50', '2020-08-01 12:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

DROP TABLE IF EXISTS `business_settings`;
CREATE TABLE IF NOT EXISTS `business_settings`
(
    `id`         int(11)                             NOT NULL AUTO_INCREMENT,
    `type`       varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `value`      longtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `created_at` timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                           NULL     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 60
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `type`, `value`, `created_at`, `updated_at`)
VALUES (1, 'home_default_currency', '1', '2018-10-16 01:35:52', '2019-01-28 01:26:53'),
       (2, 'system_default_currency', '1', '2018-10-16 01:36:58', '2020-01-26 04:22:13'),
       (3, 'currency_format', '1', '2018-10-17 03:01:59', '2018-10-17 03:01:59'),
       (4, 'symbol_format', '2', '2018-10-17 03:01:59', '2020-07-06 04:19:34'),
       (5, 'no_of_decimals', '0', '2018-10-17 03:01:59', '2020-06-30 00:03:23'),
       (6, 'product_activation', '1', '2018-10-28 01:38:37', '2019-02-04 01:11:41'),
       (7, 'vendor_system_activation', '1', '2018-10-28 07:44:16', '2019-02-04 01:11:38'),
       (8, 'show_vendors', '1', '2018-10-28 07:44:47', '2019-02-04 01:11:13'),
       (9, 'paypal_payment', '0', '2018-10-28 07:45:16', '2019-01-31 05:09:10'),
       (10, 'stripe_payment', '0', '2018-10-28 07:45:47', '2018-11-14 01:51:51'),
       (11, 'cash_payment', '1', '2018-10-28 07:46:05', '2019-01-24 03:40:18'),
       (12, 'payumoney_payment', '0', '2018-10-28 07:46:27', '2019-03-05 05:41:36'),
       (13, 'best_selling', '1', '2018-12-24 08:13:44', '2019-02-14 05:29:13'),
       (14, 'paypal_sandbox', '0', '2019-01-16 12:44:18', '2019-01-16 12:44:18'),
       (15, 'sslcommerz_sandbox', '1', '2019-01-16 12:44:18', '2019-03-14 00:07:26'),
       (16, 'sslcommerz_payment', '0', '2019-01-24 09:39:07', '2019-01-29 06:13:46'),
       (17, 'vendor_commission', '80', '2019-01-31 06:18:04', '2020-07-13 03:26:13'),
       (18, 'verification_form',
        '[{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u0648 \\u0646\\u0627\\u0645 \\u062e\\u0627\\u0646\\u0648\\u0627\\u062f\\u06af\\u06cc\"},{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u067e\\u062f\\u0631\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u0634\\u0646\\u0627\\u0633\\u0646\\u0627\\u0645\\u0647\"},{\"type\":\"text\",\"label\":\"\\u06a9\\u062f\\u0645\\u0644\\u06cc\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u067e\\u0631\\u0648\\u0627\\u0646\\u0647 \\u06a9\\u0633\\u0628\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u0647\\u0645\\u0631\\u0627\\u0647\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u062b\\u0627\\u0628\\u062a\"},{\"type\":\"text\",\"label\":\"\\u0622\\u062f\\u0631\\u0633\"}]',
        '2019-02-03 11:36:58', '2020-07-13 07:09:37'),
       (19, 'google_analytics', '0', '2019-02-06 12:22:35', '2019-02-06 12:22:35'),
       (20, 'facebook_login', '0', '2019-02-07 12:51:59', '2019-02-08 19:41:15'),
       (21, 'google_login', '0', '2019-02-07 12:52:10', '2019-02-08 19:41:14'),
       (22, 'twitter_login', '0', '2019-02-07 12:52:20', '2019-02-08 02:32:56'),
       (23, 'payumoney_payment', '1', '2019-03-05 11:38:17', '2019-03-05 11:38:17'),
       (24, 'payumoney_sandbox', '1', '2019-03-05 11:38:17', '2019-03-05 05:39:18'),
       (36, 'facebook_chat', '0', '2019-04-15 11:45:04', '2019-04-15 11:45:04'),
       (37, 'email_verification', '0', '2019-04-30 07:30:07', '2019-04-30 07:30:07'),
       (38, 'wallet_system', '0', '2019-05-19 08:05:44', '2020-08-05 00:21:08'),
       (39, 'coupon_system', '0', '2019-06-11 09:46:18', '2019-06-11 09:46:18'),
       (40, 'current_version', '2.9', '2019-06-11 09:46:18', '2019-06-11 09:46:18'),
       (41, 'instamojo_payment', '0', '2019-07-06 09:58:03', '2019-07-06 09:58:03'),
       (42, 'instamojo_sandbox', '1', '2019-07-06 09:58:43', '2019-07-06 09:58:43'),
       (43, 'razorpay', '0', '2019-07-06 09:58:43', '2019-07-06 09:58:43'),
       (44, 'paystack', '0', '2019-07-21 13:00:38', '2019-07-21 13:00:38'),
       (45, 'pickup_point', '1', '2019-10-17 11:50:39', '2020-07-14 08:34:45'),
       (46, 'maintenance_mode', '0', '2019-10-17 11:51:04', '2020-08-04 01:38:59'),
       (47, 'voguepay', '0', '2019-10-17 11:51:24', '2019-10-17 11:51:24'),
       (48, 'voguepay_sandbox', '0', '2019-10-17 11:51:38', '2019-10-17 11:51:38'),
       (50, 'category_wise_commission', '0', '2020-01-21 07:22:47', '2020-01-21 07:22:47'),
       (51, 'conversation_system', '1', '2020-01-21 07:23:21', '2020-01-21 07:23:21'),
       (52, 'guest_checkout_active', '1', '2020-01-22 07:36:38', '2020-01-22 07:36:38'),
       (53, 'facebook_pixel', '0', '2020-01-22 11:43:58', '2020-01-22 11:43:58'),
       (55, 'classified_product', '0', '2020-05-13 13:01:05', '2020-08-05 00:14:49'),
       (56, 'pos_activation_for_seller', '1', '2020-06-11 09:45:02', '2020-06-11 09:45:02'),
       (57, 'shipping_type', 'product_wise_shipping', '2020-07-04 07:02:57', '2020-07-04 07:02:57'),
       (58, 'flat_rate_shipping_cost', '0', '2020-07-04 07:02:57', '2020-07-04 07:02:57'),
       (59, 'shipping_cost_admin', '0', '2020-07-04 07:02:57', '2020-07-04 07:02:57');


-- --------------------------------------------------------

--
-- Table structure for table `otp_configurations`
--

DROP TABLE IF EXISTS `otp_configurations`;
CREATE TABLE IF NOT EXISTS `otp_configurations`
(
    `id`         int(11)                             NOT NULL AUTO_INCREMENT,
    `type`       varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `value`      longtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `created_at` timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                           NULL     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 60
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;


--
-- Dumping data for table `otp_configurations`
--

INSERT INTO `otp_configurations` (`id`, `type`, `value`, `created_at`, `updated_at`)
VALUES (1, 'melipayamak', '1', '2018-10-16 01:35:52', '2019-01-28 01:26:53'),
       (2, 'farazsms', '1', '2018-10-16 01:36:58', '2020-01-26 04:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts`
(
    `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       int(11)                      DEFAULT NULL,
    `product_id`    int(11)                      DEFAULT NULL,
    `variation`     text COLLATE utf8_unicode_ci DEFAULT NULL,
    `price`         double(8, 2)                 DEFAULT NULL,
    `tax`           double(8, 2)                 DEFAULT NULL,
    `shipping_cost` double(8, 2)                 DEFAULT NULL,
    `quantity`      int(11)          NOT NULL    DEFAULT 0,
    `created_at`    timestamp        NULL        DEFAULT current_timestamp(),
    `updated_at`    timestamp        NULL        DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 47
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories`
(
    `id`               int(11)                             NOT NULL AUTO_INCREMENT,
    `name`             varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `commision_rate`   double(8, 2)                        NOT NULL DEFAULT 0.00,
    `banner`           varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `icon`             varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `featured`         int(1)                              NOT NULL DEFAULT 0,
    `top`              int(1)                              NOT NULL DEFAULT 0,
    `digital`          int(1)                              NOT NULL DEFAULT 0,
    `slug`             varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_title`       varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_description` text COLLATE utf8_unicode_ci                 DEFAULT NULL,
    `created_at`       timestamp                           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at`       timestamp                           NULL     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 16
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `commision_rate`, `banner`, `icon`, `featured`, `top`, `digital`, `slug`,
                          `meta_title`, `meta_description`, `created_at`, `updated_at`)
VALUES (4, 'کالای دیجیتال', 0.00, 'uploads/categories/banner/PGG88oosB6MjJKp8EssvATGzExFT4hGc6zPMFfbl.png',
        'uploads/categories/icon/OakU22dIIYaZSiSjPC60eMSa38eWeL8j2pavXirA.png', 0, 1, 0, '--2EJpD', 'کالای دیجیتال',
        NULL, '2020-08-01 14:31:16', '2020-08-01 10:01:16'),
       (5, 'آرایشی، بهداشتی و سلامت', 0.00, 'uploads/categories/banner/t1ntt5tn9VS5NYor2MCg5LUwf7dVYYIPZky55Sh9.png',
        'uploads/categories/icon/On1QeG5zzAgarzYkORJ0mEM7SMUnzG9Y0v5EqswW.png', 0, 1, 0, '----FoS0v',
        'آرایشی، بهداشتی و سلامت', NULL, '2020-08-01 14:28:19', '2020-08-01 09:58:19'),
       (6, 'خودرو و ابزار', 0.00, 'uploads/categories/banner/zWEjgfKt7y6ItGvbvHYSBul9KzZ4RaZFMYGS7jxg.png',
        'uploads/categories/icon/WYZm6KbTONggvfeAgi3ZfXl8xsdxkqMEb96jQJbb.png', 0, 1, 0, '----N4OaQ', 'خودرو، ابزار',
        NULL, '2020-08-01 16:58:13', '2020-08-01 12:28:13'),
       (7, 'مد و پوشاک', 0.00, 'uploads/categories/banner/dyDrLbw3oRZFSShZCH1BNh6HVmMuwmz1G2Qt6mvz.png',
        'uploads/categories/icon/JfCi88YyB5tBAke4vs9Ngo07hN67Fef3qhUseyCj.png', 0, 1, 0, '---WKBAy', 'مد و پوشاک', NULL,
        '2020-08-01 14:25:17', '2020-08-01 09:55:17'),
       (8, 'خانه و آشپزخانه', 0.00, 'uploads/categories/banner/5ebxuvZ6pSwa6VSFiYUMX4HMxkZ4xk2BNrF6xkmU.png',
        'uploads/categories/icon/TsB0xjqOovfPYTdwbe4WmxkshzP53JMdDPEjXDp9.png', 0, 1, 0, '---uQqCB', 'خانه و آشپزخانه',
        NULL, '2020-08-01 14:23:45', '2020-08-01 09:53:45'),
       (9, 'کتاب، لوازم التحریر و هنر', 0.00, 'uploads/categories/banner/6iTmbmVRWTV0wBKvJXCq141tkhwiwD0xsNWt03kZ.png',
        'uploads/categories/icon/owfHeh0GbsVJxmWzvmK4vfRILBAPh4QsoP1qL4AR.png', 0, 1, 0, '-----MB8NO',
        'کتاب، لوازم التحریر و هنر', NULL, '2020-08-01 16:58:13', '2020-08-01 12:28:13'),
       (12, 'خوردنی و آشامیدنی', 0.00, 'uploads/categories/banner/ulmCdaZxAW31pQcr3d4hydwj5Amy9L8yvsgf5eWg.png',
        'uploads/categories/icon/MiTQa7xSwqWlybvr1QtTzFFGxFJfb3NeP1Y63ge8.png', 0, 1, 0, '---iLNhI',
        'خوردنی و آشامیدنی', NULL, '2020-08-01 16:58:13', '2020-08-01 12:28:13'),
       (13, 'ورزش و سفر', 0.00, 'uploads/categories/banner/5X5VhA9C4O4MF4fXUHJHflnPVE9shhQ6RWF8y64G.png',
        'uploads/categories/icon/KkShmKxCmBMotz7Afn3hV3zzwBr81HNz3PSxJIhd.png', 0, 1, 0, '---qY463', 'ورزش و سفر',
        'ورزش و سفر', '2020-08-01 16:58:13', '2020-08-01 12:28:13'),
       (14, 'اسباب بازی، کودک و نوزاد', 0.00, 'uploads/categories/banner/4WLcb1fJjhYIIkJSX5Tf1wneHTqHOc6jNnCTesax.png',
        'uploads/categories/icon/at36GY6k81mnpPIikV7Ct8tr2SyWJ3Muulpnvp41.png', 0, 1, 0, '-----H1Zlr',
        'اسباب بازی، کودک و نوزاد', 'اسباب بازی، کودک و نوزاد', '2020-08-01 16:58:13', '2020-08-01 12:28:13'),
       (15, 'لوازم اداری', 0.00, 'uploads/categories/banner/OAqywTGPndnQpC3ucoHXk4r8dSbluYgHCNDLfa1Y.png',
        'uploads/categories/icon/wsR7t9Yq5g8BywIZxt9MLBsqrtusybeMZRJ2ucI9.png', 0, 1, 0, '--o65zD', 'لوازم اداری',
        'لوازم اداری', '2020-08-01 16:58:13', '2020-08-01 12:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE IF NOT EXISTS `colors`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `name`       varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
    `code`       varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at` timestamp NOT NULL                  DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL                  DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 144
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`)
VALUES (1, 'IndianRed', '#CD5C5C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (2, 'LightCoral', '#F08080', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (3, 'Salmon', '#FA8072', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (4, 'DarkSalmon', '#E9967A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (5, 'LightSalmon', '#FFA07A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (6, 'Crimson', '#DC143C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (7, 'Red', '#FF0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (8, 'FireBrick', '#B22222', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (9, 'DarkRed', '#8B0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (10, 'Pink', '#FFC0CB', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (11, 'LightPink', '#FFB6C1', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (12, 'HotPink', '#FF69B4', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (13, 'DeepPink', '#FF1493', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (14, 'MediumVioletRed', '#C71585', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (15, 'PaleVioletRed', '#DB7093', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (16, 'LightSalmon', '#FFA07A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (17, 'Coral', '#FF7F50', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (18, 'Tomato', '#FF6347', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (19, 'OrangeRed', '#FF4500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (20, 'DarkOrange', '#FF8C00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (21, 'Orange', '#FFA500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (22, 'Gold', '#FFD700', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (23, 'Yellow', '#FFFF00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (24, 'LightYellow', '#FFFFE0', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (25, 'LemonChiffon', '#FFFACD', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
       (26, 'LightGoldenrodYellow', '#FAFAD2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (27, 'PapayaWhip', '#FFEFD5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (28, 'Moccasin', '#FFE4B5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (29, 'PeachPuff', '#FFDAB9', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (30, 'PaleGoldenrod', '#EEE8AA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (31, 'Khaki', '#F0E68C', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (32, 'DarkKhaki', '#BDB76B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (33, 'Lavender', '#E6E6FA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (34, 'Thistle', '#D8BFD8', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (35, 'Plum', '#DDA0DD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (36, 'Violet', '#EE82EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (37, 'Orchid', '#DA70D6', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (38, 'Fuchsia', '#FF00FF', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (39, 'Magenta', '#FF00FF', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (40, 'MediumOrchid', '#BA55D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (41, 'MediumPurple', '#9370DB', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (42, 'Amethyst', '#9966CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (43, 'BlueViolet', '#8A2BE2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (44, 'DarkViolet', '#9400D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (45, 'DarkOrchid', '#9932CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (46, 'DarkMagenta', '#8B008B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (47, 'Purple', '#800080', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (48, 'Indigo', '#4B0082', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (49, 'SlateBlue', '#6A5ACD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (50, 'DarkSlateBlue', '#483D8B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (51, 'MediumSlateBlue', '#7B68EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (52, 'GreenYellow', '#ADFF2F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (53, 'Chartreuse', '#7FFF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (54, 'LawnGreen', '#7CFC00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (55, 'Lime', '#00FF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (56, 'LimeGreen', '#32CD32', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (57, 'PaleGreen', '#98FB98', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (58, 'LightGreen', '#90EE90', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (59, 'MediumSpringGreen', '#00FA9A', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (60, 'SpringGreen', '#00FF7F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (61, 'MediumSeaGreen', '#3CB371', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (62, 'SeaGreen', '#2E8B57', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
       (63, 'ForestGreen', '#228B22', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (64, 'Green', '#008000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (65, 'DarkGreen', '#006400', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (66, 'YellowGreen', '#9ACD32', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (67, 'OliveDrab', '#6B8E23', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (68, 'Olive', '#808000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (69, 'DarkOliveGreen', '#556B2F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (70, 'MediumAquamarine', '#66CDAA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (71, 'DarkSeaGreen', '#8FBC8F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (72, 'LightSeaGreen', '#20B2AA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (73, 'DarkCyan', '#008B8B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (74, 'Teal', '#008080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (75, 'Aqua', '#00FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (76, 'Cyan', '#00FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (77, 'LightCyan', '#E0FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (78, 'PaleTurquoise', '#AFEEEE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (79, 'Aquamarine', '#7FFFD4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (80, 'Turquoise', '#40E0D0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (81, 'MediumTurquoise', '#48D1CC', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (82, 'DarkTurquoise', '#00CED1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (83, 'CadetBlue', '#5F9EA0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (84, 'SteelBlue', '#4682B4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (85, 'LightSteelBlue', '#B0C4DE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (86, 'PowderBlue', '#B0E0E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (87, 'LightBlue', '#ADD8E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (88, 'SkyBlue', '#87CEEB', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (89, 'LightSkyBlue', '#87CEFA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (90, 'DeepSkyBlue', '#00BFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (91, 'DodgerBlue', '#1E90FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (92, 'CornflowerBlue', '#6495ED', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (93, 'MediumSlateBlue', '#7B68EE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (94, 'RoyalBlue', '#4169E1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (95, 'Blue', '#0000FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (96, 'MediumBlue', '#0000CD', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (97, 'DarkBlue', '#00008B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (98, 'Navy', '#000080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
       (99, 'MidnightBlue', '#191970', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (100, 'Cornsilk', '#FFF8DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (101, 'BlanchedAlmond', '#FFEBCD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (102, 'Bisque', '#FFE4C4', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (103, 'NavajoWhite', '#FFDEAD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (104, 'Wheat', '#F5DEB3', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (105, 'BurlyWood', '#DEB887', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (106, 'Tan', '#D2B48C', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (107, 'RosyBrown', '#BC8F8F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (108, 'SandyBrown', '#F4A460', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (109, 'Goldenrod', '#DAA520', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (110, 'DarkGoldenrod', '#B8860B', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (111, 'Peru', '#CD853F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (112, 'Chocolate', '#D2691E', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (113, 'SaddleBrown', '#8B4513', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (114, 'Sienna', '#A0522D', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (115, 'Brown', '#A52A2A', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (116, 'Maroon', '#800000', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (117, 'White', '#FFFFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (118, 'Snow', '#FFFAFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (119, 'Honeydew', '#F0FFF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (120, 'MintCream', '#F5FFFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (121, 'Azure', '#F0FFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (122, 'AliceBlue', '#F0F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (123, 'GhostWhite', '#F8F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (124, 'WhiteSmoke', '#F5F5F5', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (125, 'Seashell', '#FFF5EE', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (126, 'Beige', '#F5F5DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (127, 'OldLace', '#FDF5E6', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (128, 'FloralWhite', '#FFFAF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
       (129, 'Ivory', '#FFFFF0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (130, 'AntiqueWhite', '#FAEBD7', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (131, 'Linen', '#FAF0E6', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (132, 'LavenderBlush', '#FFF0F5', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (133, 'MistyRose', '#FFE4E1', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (134, 'Gainsboro', '#DCDCDC', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (135, 'LightGrey', '#D3D3D3', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (136, 'Silver', '#C0C0C0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (137, 'DarkGray', '#A9A9A9', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (138, 'Gray', '#808080', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (139, 'DimGray', '#696969', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (140, 'LightSlateGray', '#778899', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (141, 'SlateGray', '#708090', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (142, 'DarkSlateGray', '#2F4F4F', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
       (143, 'Black', '#000000', '2018-11-05 02:12:30', '2018-11-05 02:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations`
(
    `id`              int(11)   NOT NULL AUTO_INCREMENT,
    `sender_id`       int(11)   NOT NULL,
    `receiver_id`     int(11)   NOT NULL,
    `title`           varchar(1000) COLLATE utf32_unicode_ci DEFAULT NULL,
    `sender_viewed`   int(1)    NOT NULL                     DEFAULT 1,
    `receiver_viewed` int(1)    NOT NULL                     DEFAULT 0,
    `created_at`      timestamp NOT NULL                     DEFAULT current_timestamp(),
    `updated_at`      timestamp NOT NULL                     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf32
  COLLATE = utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries`
(
    `id`         int(11)                              NOT NULL AUTO_INCREMENT,
    `code`       varchar(2) COLLATE utf8_unicode_ci   NOT NULL DEFAULT '',
    `name`       varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `status`     int(1)                               NOT NULL DEFAULT 1,
    `created_at` timestamp                            NULL     DEFAULT NULL,
    `updated_at` timestamp                            NULL     DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 297
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `status`, `created_at`, `updated_at`)
VALUES (1, 'AF', 'Afghanistan', 1, NULL, NULL),
       (2, 'AL', 'Albania', 1, NULL, NULL),
       (3, 'DZ', 'Algeria', 1, NULL, NULL),
       (4, 'DS', 'American Samoa', 1, NULL, NULL),
       (5, 'AD', 'Andorra', 1, NULL, NULL),
       (6, 'AO', 'Angola', 1, NULL, NULL),
       (7, 'AI', 'Anguilla', 1, NULL, NULL),
       (8, 'AQ', 'Antarctica', 1, NULL, NULL),
       (9, 'AG', 'Antigua and Barbuda', 1, NULL, NULL),
       (10, 'AR', 'Argentina', 1, NULL, NULL),
       (11, 'AM', 'Armenia', 1, NULL, NULL),
       (12, 'AW', 'Aruba', 1, NULL, NULL),
       (13, 'AU', 'Australia', 1, NULL, NULL),
       (14, 'AT', 'Austria', 1, NULL, NULL),
       (15, 'AZ', 'Azerbaijan', 1, NULL, NULL),
       (16, 'BS', 'Bahamas', 1, NULL, NULL),
       (17, 'BH', 'Bahrain', 1, NULL, NULL),
       (18, 'BD', 'Bangladesh', 1, NULL, NULL),
       (19, 'BB', 'Barbados', 1, NULL, NULL),
       (20, 'BY', 'Belarus', 1, NULL, NULL),
       (21, 'BE', 'Belgium', 1, NULL, NULL),
       (22, 'BZ', 'Belize', 1, NULL, NULL),
       (23, 'BJ', 'Benin', 1, NULL, NULL),
       (24, 'BM', 'Bermuda', 1, NULL, NULL),
       (25, 'BT', 'Bhutan', 1, NULL, NULL),
       (26, 'BO', 'Bolivia', 1, NULL, NULL),
       (27, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL),
       (28, 'BW', 'Botswana', 1, NULL, NULL),
       (29, 'BV', 'Bouvet Island', 1, NULL, NULL),
       (30, 'BR', 'Brazil', 1, NULL, NULL),
       (31, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL),
       (32, 'BN', 'Brunei Darussalam', 1, NULL, NULL),
       (33, 'BG', 'Bulgaria', 1, NULL, NULL),
       (34, 'BF', 'Burkina Faso', 1, NULL, NULL),
       (35, 'BI', 'Burundi', 1, NULL, NULL),
       (36, 'KH', 'Cambodia', 1, NULL, NULL),
       (37, 'CM', 'Cameroon', 1, NULL, NULL),
       (38, 'CA', 'Canada', 1, NULL, NULL),
       (39, 'CV', 'Cape Verde', 1, NULL, NULL),
       (40, 'KY', 'Cayman Islands', 1, NULL, NULL),
       (41, 'CF', 'Central African Republic', 1, NULL, NULL),
       (42, 'TD', 'Chad', 1, NULL, NULL),
       (43, 'CL', 'Chile', 1, NULL, NULL),
       (44, 'CN', 'China', 1, NULL, NULL),
       (45, 'CX', 'Christmas Island', 1, NULL, NULL),
       (46, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL),
       (47, 'CO', 'Colombia', 1, NULL, NULL),
       (48, 'KM', 'Comoros', 1, NULL, NULL),
       (49, 'CG', 'Congo', 1, NULL, NULL),
       (50, 'CK', 'Cook Islands', 1, NULL, NULL),
       (51, 'CR', 'Costa Rica', 1, NULL, NULL),
       (52, 'HR', 'Croatia (Hrvatska)', 1, NULL, NULL),
       (53, 'CU', 'Cuba', 1, NULL, NULL),
       (54, 'CY', 'Cyprus', 1, NULL, NULL),
       (55, 'CZ', 'Czech Republic', 1, NULL, NULL),
       (56, 'DK', 'Denmark', 1, NULL, NULL),
       (57, 'DJ', 'Djibouti', 1, NULL, NULL),
       (58, 'DM', 'Dominica', 1, NULL, NULL),
       (59, 'DO', 'Dominican Republic', 1, NULL, NULL),
       (60, 'TP', 'East Timor', 1, NULL, NULL),
       (61, 'EC', 'Ecuador', 1, NULL, NULL),
       (62, 'EG', 'Egypt', 1, NULL, NULL),
       (63, 'SV', 'El Salvador', 1, NULL, NULL),
       (64, 'GQ', 'Equatorial Guinea', 1, NULL, NULL),
       (65, 'ER', 'Eritrea', 1, NULL, NULL),
       (66, 'EE', 'Estonia', 1, NULL, NULL),
       (67, 'ET', 'Ethiopia', 1, NULL, NULL),
       (68, 'FK', 'Falkland Islands (Malvinas)', 1, NULL, NULL),
       (69, 'FO', 'Faroe Islands', 1, NULL, NULL),
       (70, 'FJ', 'Fiji', 1, NULL, NULL),
       (71, 'FI', 'Finland', 1, NULL, NULL),
       (72, 'FR', 'France', 1, NULL, NULL),
       (73, 'FX', 'France, Metropolitan', 1, NULL, NULL),
       (74, 'GF', 'French Guiana', 1, NULL, NULL),
       (75, 'PF', 'French Polynesia', 1, NULL, NULL),
       (76, 'TF', 'French Southern Territories', 1, NULL, NULL),
       (77, 'GA', 'Gabon', 1, NULL, NULL),
       (78, 'GM', 'Gambia', 1, NULL, NULL),
       (79, 'GE', 'Georgia', 1, NULL, NULL),
       (80, 'DE', 'Germany', 1, NULL, NULL),
       (81, 'GH', 'Ghana', 1, NULL, NULL),
       (82, 'GI', 'Gibraltar', 1, NULL, NULL),
       (83, 'GK', 'Guernsey', 1, NULL, NULL),
       (84, 'GR', 'Greece', 1, NULL, NULL),
       (85, 'GL', 'Greenland', 1, NULL, NULL),
       (86, 'GD', 'Grenada', 1, NULL, NULL),
       (87, 'GP', 'Guadeloupe', 1, NULL, NULL),
       (88, 'GU', 'Guam', 1, NULL, NULL),
       (89, 'GT', 'Guatemala', 1, NULL, NULL),
       (90, 'GN', 'Guinea', 1, NULL, NULL),
       (91, 'GW', 'Guinea-Bissau', 1, NULL, NULL),
       (92, 'GY', 'Guyana', 1, NULL, NULL),
       (93, 'HT', 'Haiti', 1, NULL, NULL),
       (94, 'HM', 'Heard and Mc Donald Islands', 1, NULL, NULL),
       (95, 'HN', 'Honduras', 1, NULL, NULL),
       (96, 'HK', 'Hong Kong', 1, NULL, NULL),
       (97, 'HU', 'Hungary', 1, NULL, NULL),
       (98, 'IS', 'Iceland', 1, NULL, NULL),
       (99, 'IN', 'India', 1, NULL, NULL),
       (100, 'IM', 'Isle of Man', 1, NULL, NULL),
       (101, 'ID', 'Indonesia', 1, NULL, NULL),
       (102, 'IR', 'Iran (Islamic Republic of)', 1, NULL, NULL),
       (103, 'IQ', 'Iraq', 1, NULL, NULL),
       (104, 'IE', 'Ireland', 1, NULL, NULL),
       (105, 'IL', 'Israel', 1, NULL, NULL),
       (106, 'IT', 'Italy', 1, NULL, NULL),
       (107, 'CI', 'Ivory Coast', 1, NULL, NULL),
       (108, 'JE', 'Jersey', 1, NULL, NULL),
       (109, 'JM', 'Jamaica', 1, NULL, NULL),
       (110, 'JP', 'Japan', 1, NULL, NULL),
       (111, 'JO', 'Jordan', 1, NULL, NULL),
       (112, 'KZ', 'Kazakhstan', 1, NULL, NULL),
       (113, 'KE', 'Kenya', 1, NULL, NULL),
       (114, 'KI', 'Kiribati', 1, NULL, NULL),
       (115, 'KP', 'Korea, Democratic People\'s Republic of', 1, NULL, NULL),
       (116, 'KR', 'Korea, Republic of', 1, NULL, NULL),
       (117, 'XK', 'Kosovo', 1, NULL, NULL),
       (118, 'KW', 'Kuwait', 1, NULL, NULL),
       (119, 'KG', 'Kyrgyzstan', 1, NULL, NULL),
       (120, 'LA', 'Lao People\'s Democratic Republic', 1, NULL, NULL),
       (121, 'LV', 'Latvia', 1, NULL, NULL),
       (122, 'LB', 'Lebanon', 1, NULL, NULL),
       (123, 'LS', 'Lesotho', 1, NULL, NULL),
       (124, 'LR', 'Liberia', 1, NULL, NULL),
       (125, 'LY', 'Libyan Arab Jamahiriya', 1, NULL, NULL),
       (126, 'LI', 'Liechtenstein', 1, NULL, NULL),
       (127, 'LT', 'Lithuania', 1, NULL, NULL),
       (128, 'LU', 'Luxembourg', 1, NULL, NULL),
       (129, 'MO', 'Macau', 1, NULL, NULL),
       (130, 'MK', 'Macedonia', 1, NULL, NULL),
       (131, 'MG', 'Madagascar', 1, NULL, NULL),
       (132, 'MW', 'Malawi', 1, NULL, NULL),
       (133, 'MY', 'Malaysia', 1, NULL, NULL),
       (134, 'MV', 'Maldives', 1, NULL, NULL),
       (135, 'ML', 'Mali', 1, NULL, NULL),
       (136, 'MT', 'Malta', 1, NULL, NULL),
       (137, 'MH', 'Marshall Islands', 1, NULL, NULL),
       (138, 'MQ', 'Martinique', 1, NULL, NULL),
       (139, 'MR', 'Mauritania', 1, NULL, NULL),
       (140, 'MU', 'Mauritius', 1, NULL, NULL),
       (141, 'TY', 'Mayotte', 1, NULL, NULL),
       (142, 'MX', 'Mexico', 1, NULL, NULL),
       (143, 'FM', 'Micronesia, Federated States of', 1, NULL, NULL),
       (144, 'MD', 'Moldova, Republic of', 1, NULL, NULL),
       (145, 'MC', 'Monaco', 1, NULL, NULL),
       (146, 'MN', 'Mongolia', 1, NULL, NULL),
       (147, 'ME', 'Montenegro', 1, NULL, NULL),
       (148, 'MS', 'Montserrat', 1, NULL, NULL),
       (149, 'MA', 'Morocco', 1, NULL, NULL),
       (150, 'MZ', 'Mozambique', 1, NULL, NULL),
       (151, 'MM', 'Myanmar', 1, NULL, NULL),
       (152, 'NA', 'Namibia', 1, NULL, NULL),
       (153, 'NR', 'Nauru', 1, NULL, NULL),
       (154, 'NP', 'Nepal', 1, NULL, NULL),
       (155, 'NL', 'Netherlands', 1, NULL, NULL),
       (156, 'AN', 'Netherlands Antilles', 1, NULL, NULL),
       (157, 'NC', 'New Caledonia', 1, NULL, NULL),
       (158, 'NZ', 'New Zealand', 1, NULL, NULL),
       (159, 'NI', 'Nicaragua', 1, NULL, NULL),
       (160, 'NE', 'Niger', 1, NULL, NULL),
       (161, 'NG', 'Nigeria', 1, NULL, NULL),
       (162, 'NU', 'Niue', 1, NULL, NULL),
       (163, 'NF', 'Norfolk Island', 1, NULL, NULL),
       (164, 'MP', 'Northern Mariana Islands', 1, NULL, NULL),
       (165, 'NO', 'Norway', 1, NULL, NULL),
       (166, 'OM', 'Oman', 1, NULL, NULL),
       (167, 'PK', 'Pakistan', 1, NULL, NULL),
       (168, 'PW', 'Palau', 1, NULL, NULL),
       (169, 'PS', 'Palestine', 1, NULL, NULL),
       (170, 'PA', 'Panama', 1, NULL, NULL),
       (171, 'PG', 'Papua New Guinea', 1, NULL, NULL),
       (172, 'PY', 'Paraguay', 1, NULL, NULL),
       (173, 'PE', 'Peru', 1, NULL, NULL),
       (174, 'PH', 'Philippines', 1, NULL, NULL),
       (175, 'PN', 'Pitcairn', 1, NULL, NULL),
       (176, 'PL', 'Poland', 1, NULL, NULL),
       (177, 'PT', 'Portugal', 1, NULL, NULL),
       (178, 'PR', 'Puerto Rico', 1, NULL, NULL),
       (179, 'QA', 'Qatar', 1, NULL, NULL),
       (180, 'RE', 'Reunion', 1, NULL, NULL),
       (181, 'RO', 'Romania', 1, NULL, NULL),
       (182, 'RU', 'Russian Federation', 1, NULL, NULL),
       (183, 'RW', 'Rwanda', 1, NULL, NULL),
       (184, 'KN', 'Saint Kitts and Nevis', 1, NULL, NULL),
       (185, 'LC', 'Saint Lucia', 1, NULL, NULL),
       (186, 'VC', 'Saint Vincent and the Grenadines', 1, NULL, NULL),
       (187, 'WS', 'Samoa', 1, NULL, NULL),
       (188, 'SM', 'San Marino', 1, NULL, NULL),
       (189, 'ST', 'Sao Tome and Principe', 1, NULL, NULL),
       (190, 'SA', 'Saudi Arabia', 1, NULL, NULL),
       (191, 'SN', 'Senegal', 1, NULL, NULL),
       (192, 'RS', 'Serbia', 1, NULL, NULL),
       (193, 'SC', 'Seychelles', 1, NULL, NULL),
       (194, 'SL', 'Sierra Leone', 1, NULL, NULL),
       (195, 'SG', 'Singapore', 1, NULL, NULL),
       (196, 'SK', 'Slovakia', 1, NULL, NULL),
       (197, 'SI', 'Slovenia', 1, NULL, NULL),
       (198, 'SB', 'Solomon Islands', 1, NULL, NULL),
       (199, 'SO', 'Somalia', 1, NULL, NULL),
       (200, 'ZA', 'South Africa', 1, NULL, NULL),
       (201, 'GS', 'South Georgia South Sandwich Islands', 1, NULL, NULL),
       (202, 'SS', 'South Sudan', 1, NULL, NULL),
       (203, 'ES', 'Spain', 1, NULL, NULL),
       (204, 'LK', 'Sri Lanka', 1, NULL, NULL),
       (205, 'SH', 'St. Helena', 1, NULL, NULL),
       (206, 'PM', 'St. Pierre and Miquelon', 1, NULL, NULL),
       (207, 'SD', 'Sudan', 1, NULL, NULL),
       (208, 'SR', 'Suriname', 1, NULL, NULL),
       (209, 'SJ', 'Svalbard and Jan Mayen Islands', 1, NULL, NULL),
       (210, 'SZ', 'Swaziland', 1, NULL, NULL),
       (211, 'SE', 'Sweden', 1, NULL, NULL),
       (212, 'CH', 'Switzerland', 1, NULL, NULL),
       (213, 'SY', 'Syrian Arab Republic', 1, NULL, NULL),
       (214, 'TW', 'Taiwan', 1, NULL, NULL),
       (215, 'TJ', 'Tajikistan', 1, NULL, NULL),
       (216, 'TZ', 'Tanzania, United Republic of', 1, NULL, NULL),
       (217, 'TH', 'Thailand', 1, NULL, NULL),
       (218, 'TG', 'Togo', 1, NULL, NULL),
       (219, 'TK', 'Tokelau', 1, NULL, NULL),
       (220, 'TO', 'Tonga', 1, NULL, NULL),
       (221, 'TT', 'Trinidad and Tobago', 1, NULL, NULL),
       (222, 'TN', 'Tunisia', 1, NULL, NULL),
       (223, 'TR', 'Turkey', 1, NULL, NULL),
       (224, 'TM', 'Turkmenistan', 1, NULL, NULL),
       (225, 'TC', 'Turks and Caicos Islands', 1, NULL, NULL),
       (226, 'TV', 'Tuvalu', 1, NULL, NULL),
       (227, 'UG', 'Uganda', 1, NULL, NULL),
       (228, 'UA', 'Ukraine', 1, NULL, NULL),
       (229, 'AE', 'United Arab Emirates', 1, NULL, NULL),
       (230, 'GB', 'United Kingdom', 1, NULL, NULL),
       (231, 'US', 'United States', 1, NULL, NULL),
       (232, 'UM', 'United States minor outlying islands', 1, NULL, NULL),
       (233, 'UY', 'Uruguay', 1, NULL, NULL),
       (234, 'UZ', 'Uzbekistan', 1, NULL, NULL),
       (235, 'VU', 'Vanuatu', 1, NULL, NULL),
       (236, 'VA', 'Vatican City State', 1, NULL, NULL),
       (237, 'VE', 'Venezuela', 1, NULL, NULL),
       (238, 'VN', 'Vietnam', 1, NULL, NULL),
       (239, 'VG', 'Virgin Islands (British)', 1, NULL, NULL),
       (240, 'VI', 'Virgin Islands (U.S.)', 1, NULL, NULL),
       (241, 'WF', 'Wallis and Futuna Islands', 1, NULL, NULL),
       (242, 'EH', 'Western Sahara', 1, NULL, NULL),
       (243, 'YE', 'Yemen', 1, NULL, NULL),
       (244, 'ZR', 'Zaire', 1, NULL, NULL),
       (245, 'ZM', 'Zambia', 1, NULL, NULL),
       (246, 'ZW', 'Zimbabwe', 1, NULL, NULL),
       (247, 'AF', 'Afghanistan', 1, NULL, NULL),
       (248, 'AL', 'Albania', 1, NULL, NULL),
       (249, 'DZ', 'Algeria', 1, NULL, NULL),
       (250, 'DS', 'American Samoa', 1, NULL, NULL),
       (251, 'AD', 'Andorra', 1, NULL, NULL),
       (252, 'AO', 'Angola', 1, NULL, NULL),
       (253, 'AI', 'Anguilla', 1, NULL, NULL),
       (254, 'AQ', 'Antarctica', 1, NULL, NULL),
       (255, 'AG', 'Antigua and Barbuda', 1, NULL, NULL),
       (256, 'AR', 'Argentina', 1, NULL, NULL),
       (257, 'AM', 'Armenia', 1, NULL, NULL),
       (258, 'AW', 'Aruba', 1, NULL, NULL),
       (259, 'AU', 'Australia', 1, NULL, NULL),
       (260, 'AT', 'Austria', 1, NULL, NULL),
       (261, 'AZ', 'Azerbaijan', 1, NULL, NULL),
       (262, 'BS', 'Bahamas', 1, NULL, NULL),
       (263, 'BH', 'Bahrain', 1, NULL, NULL),
       (264, 'BD', 'Bangladesh', 1, NULL, NULL),
       (265, 'BB', 'Barbados', 1, NULL, NULL),
       (266, 'BY', 'Belarus', 1, NULL, NULL),
       (267, 'BE', 'Belgium', 1, NULL, NULL),
       (268, 'BZ', 'Belize', 1, NULL, NULL),
       (269, 'BJ', 'Benin', 1, NULL, NULL),
       (270, 'BM', 'Bermuda', 1, NULL, NULL),
       (271, 'BT', 'Bhutan', 1, NULL, NULL),
       (272, 'BO', 'Bolivia', 1, NULL, NULL),
       (273, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL),
       (274, 'BW', 'Botswana', 1, NULL, NULL),
       (275, 'BV', 'Bouvet Island', 1, NULL, NULL),
       (276, 'BR', 'Brazil', 1, NULL, NULL),
       (277, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL),
       (278, 'BN', 'Brunei Darussalam', 1, NULL, NULL),
       (279, 'BG', 'Bulgaria', 1, NULL, NULL),
       (280, 'BF', 'Burkina Faso', 1, NULL, NULL),
       (281, 'BI', 'Burundi', 1, NULL, NULL),
       (282, 'KH', 'Cambodia', 1, NULL, NULL),
       (283, 'CM', 'Cameroon', 1, NULL, NULL),
       (284, 'CA', 'Canada', 1, NULL, NULL),
       (285, 'CV', 'Cape Verde', 1, NULL, NULL),
       (286, 'KY', 'Cayman Islands', 1, NULL, NULL),
       (287, 'CF', 'Central African Republic', 1, NULL, NULL),
       (288, 'TD', 'Chad', 1, NULL, NULL),
       (289, 'CL', 'Chile', 1, NULL, NULL),
       (290, 'CN', 'China', 1, NULL, NULL),
       (291, 'CX', 'Christmas Island', 1, NULL, NULL),
       (292, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL),
       (293, 'CO', 'Colombia', 1, NULL, NULL),
       (294, 'KM', 'Comoros', 1, NULL, NULL),
       (295, 'CG', 'Congo', 1, NULL, NULL),
       (296, 'CK', 'Cook Islands', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons`
(
    `id`            int(11)                              NOT NULL AUTO_INCREMENT,
    `type`          varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `code`          varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `details`       longtext COLLATE utf8_unicode_ci     NOT NULL,
    `discount`      double(8, 2)                         NOT NULL,
    `discount_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `start_date`    int(15)                              NOT NULL,
    `end_date`      int(15)                              NOT NULL,
    `created_at`    timestamp                            NOT NULL DEFAULT current_timestamp(),
    `updated_at`    timestamp                            NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
CREATE TABLE IF NOT EXISTS `coupon_usages`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)   NOT NULL,
    `coupon_id`  int(11)   NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies`
(
    `id`            int(11)                              NOT NULL AUTO_INCREMENT,
    `name`          varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `symbol`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `exchange_rate` double(10, 5)                        NOT NULL,
    `status`        int(10)                              NOT NULL DEFAULT 0,
    `code`          varchar(20) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `created_at`    timestamp                            NOT NULL DEFAULT current_timestamp(),
    `updated_at`    timestamp                            NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `exchange_rate`, `status`, `code`, `created_at`, `updated_at`)
VALUES (1, 'تومان', 'تومان', 1.00000, 1, 'IRR', '2020-07-06 02:18:24', '2020-07-06 02:18:52');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)   NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `created_at`, `updated_at`)
VALUES (4, 8, '2019-08-01 10:35:09', '2019-08-01 10:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `customer_packages`
--

DROP TABLE IF EXISTS `customer_packages`;
CREATE TABLE IF NOT EXISTS `customer_packages`
(
    `id`             int(11)   NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `amount`         double(28, 2)                        DEFAULT NULL,
    `product_upload` int(11)                              DEFAULT NULL,
    `logo`           varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at`     timestamp NULL                       DEFAULT NULL,
    `updated_at`     timestamp NULL                       DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_products`
--

DROP TABLE IF EXISTS `customer_products`;
CREATE TABLE IF NOT EXISTS `customer_products`
(
    `id`                int(11)   NOT NULL AUTO_INCREMENT,
    `name`              varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `published`         int(1)    NOT NULL                   DEFAULT 0,
    `status`            int(1)    NOT NULL                   DEFAULT 0,
    `added_by`          varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `user_id`           int(11)                              DEFAULT NULL,
    `category_id`       int(11)                              DEFAULT NULL,
    `subcategory_id`    int(11)                              DEFAULT NULL,
    `subsubcategory_id` int(11)                              DEFAULT NULL,
    `brand_id`          int(11)                              DEFAULT NULL,
    `photos`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `thumbnail_img`     varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
    `conditon`          varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `location`          text COLLATE utf8_unicode_ci         DEFAULT NULL,
    `video_provider`    varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `video_link`        varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `unit`              varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `tags`              varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `description`       mediumtext COLLATE utf8_unicode_ci   DEFAULT NULL,
    `unit_price`        double(28, 2)                        DEFAULT 0.00,
    `meta_title`        varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `meta_description`  varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
    `meta_img`          varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
    `pdf`               varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `slug`              varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at`        timestamp NOT NULL                   DEFAULT current_timestamp(),
    `updated_at`        timestamp NOT NULL                   DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_deals`
--

DROP TABLE IF EXISTS `flash_deals`;
CREATE TABLE IF NOT EXISTS `flash_deals`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `title`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `start_date`       int(20)                              DEFAULT NULL,
    `end_date`         int(20)                              DEFAULT NULL,
    `status`           int(1)    NOT NULL                   DEFAULT 0,
    `featured`         int(1)    NOT NULL                   DEFAULT 0,
    `background_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `text_color`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `banner`           varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `slug`             varchar(255) CHARACTER SET utf8mb4   DEFAULT NULL,
    `created_at`       timestamp NULL                       DEFAULT current_timestamp(),
    `updated_at`       timestamp NOT NULL                   DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 9
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `flash_deals`
--

INSERT INTO `flash_deals` (`id`, `title`, `start_date`, `end_date`, `status`, `featured`, `background_color`,
                           `text_color`, `banner`, `slug`, `created_at`, `updated_at`)
VALUES (7, 'پیشنهاد شگفت انگیز', 1595376000, 1597968000, 1, 1, '#FFFFFF', 'dark',
        'uploads/offers/banner/t4uuiqxx8KUq1bRFZuyXLfQraUdEEpTu5PV97h5V.jpeg', 'پۜشنهاد-شگفت-انگۜز-djzvw',
        '2020-07-22 03:05:16', '2020-08-01 10:02:33'),
       (8, 'تست', 1595236114, 1595322529, 0, 0, '#fff', 'dark',
        'uploads/offers/banner/fwrjKaUqlS7kJlQdxy3gUznU3RIxL51ZcfjqkCB0.jpeg', 'تست-jxqbv', '2020-07-20 00:14:44',
        '2020-07-20 00:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `flash_deal_products`
--

DROP TABLE IF EXISTS `flash_deal_products`;
CREATE TABLE IF NOT EXISTS `flash_deal_products`
(
    `id`            int(11)   NOT NULL AUTO_INCREMENT,
    `flash_deal_id` int(11)   NOT NULL,
    `product_id`    int(11)   NOT NULL,
    `discount`      double(8, 2)                        DEFAULT 0.00,
    `discount_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at`    timestamp NOT NULL                  DEFAULT current_timestamp(),
    `updated_at`    timestamp NOT NULL                  DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 59
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `flash_deal_products`
--

INSERT INTO `flash_deal_products` (`id`, `flash_deal_id`, `product_id`, `discount`, `discount_type`, `created_at`,
                                   `updated_at`)
VALUES (41, 8, 1, 0.00, 'amount', '2020-07-20 00:14:44', '2020-07-20 00:14:44'),
       (42, 8, 2, 0.00, 'amount', '2020-07-20 00:14:44', '2020-07-20 00:14:44'),
       (51, 7, 1, 90000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (52, 7, 6, 6000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (53, 7, 9, 12000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (54, 7, 13, 49000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (55, 7, 15, 300000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (56, 7, 17, 50000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (57, 7, 21, 10000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33'),
       (58, 7, 23, 200000.00, 'amount', '2020-08-01 10:02:33', '2020-08-01 10:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

DROP TABLE IF EXISTS `general_settings`;
CREATE TABLE IF NOT EXISTS `general_settings`
(
    `id`                     int(11)                              NOT NULL AUTO_INCREMENT,
    `frontend_color`         varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
    `logo`                   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `admin_logo`             varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `admin_login_background` varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `admin_login_sidebar`    varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `favicon`                varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `site_name`              varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `address`                varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `description`            mediumtext COLLATE utf8_unicode_ci   NOT NULL,
    `phone`                  varchar(100) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `email`                  varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `facebook`               varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `instagram`              varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `twitter`                varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `youtube`                varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `google_plus`            varchar(1000) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `created_at`             timestamp                            NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at`             timestamp                            NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `frontend_color`, `logo`, `admin_logo`, `admin_login_background`,
                                `admin_login_sidebar`, `favicon`, `site_name`, `address`, `description`, `phone`,
                                `email`, `facebook`, `instagram`, `twitter`, `youtube`, `google_plus`, `created_at`,
                                `updated_at`)
VALUES (1, '2', 'uploads/logo/pfdIuiMeXGkDAIpPEUrvUCbQrOHu484nbGfz77zB.png',
        'uploads/admin_logo/wCgHrz0Q5QoL1yu4vdrNnQIr4uGuNL48CXfcxOuS.png', NULL, NULL,
        'uploads/favicon/uHdGidSaRVzvPgDj6JFtntMqzJkwDk9659233jrb.png', 'فروشگاه اینترنتی اکتیو',
        'تهران خیابان شریعتی پلاک 101', 'کلیه حقوق محفوظ و متعلق به فروشگاه اینترنتی اکتیو می باشد.', '09101111111',
        'shop@aliramezankhani.com', 'https://www.facebook.com', 'https://www.instagram.com', 'https://www.twitter.com',
        'https://www.youtube.com', 'https://www.googleplus.com', '2020-08-05 06:43:15', '2020-08-05 02:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `home_categories`
--

DROP TABLE IF EXISTS `home_categories`;
CREATE TABLE IF NOT EXISTS `home_categories`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `category_id`      int(11)   NOT NULL,
    `subsubcategories` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
    `status`           int(1)    NOT NULL                    DEFAULT 1,
    `created_at`       timestamp NOT NULL                    DEFAULT current_timestamp(),
    `updated_at`       timestamp NOT NULL                    DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `home_categories`
--

INSERT INTO `home_categories` (`id`, `category_id`, `subsubcategories`, `status`, `created_at`, `updated_at`)
VALUES (3, 7, 'null', 1, '2020-08-01 12:59:17', '2020-08-01 12:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages`
(
    `id`         int(11)                              NOT NULL AUTO_INCREMENT,
    `name`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `code`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `rtl`        int(1)                               NOT NULL DEFAULT 0,
    `created_at` timestamp                            NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                            NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `rtl`, `created_at`, `updated_at`)
VALUES (1, 'English', 'en', 0, '2019-01-20 12:13:20', '2019-01-20 12:13:20'),
       (5, 'فارسی', 'ir', 1, '2020-06-27 09:52:41', '2020-07-14 09:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links`
(
    `id`         int(11)                              NOT NULL AUTO_INCREMENT,
    `name`       varchar(50) COLLATE utf8_unicode_ci  NOT NULL,
    `url`        varchar(200) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp                            NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at` timestamp                            NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `name`, `url`, `created_at`, `updated_at`)
VALUES (1, 'لینک اول', '#', '2020-07-19 02:51:35', '2020-07-19 02:51:35'),
       (2, 'لینک دوم', '#', '2020-07-19 02:52:07', '2020-07-19 02:52:07'),
       (3, 'لینک سوم', '#', '2020-07-19 02:52:24', '2020-07-19 02:52:24'),
       (4, 'لینک چهارم', '#', '2020-07-19 02:52:36', '2020-07-19 02:52:36'),
       (5, 'لینک پنجم', '#', '2020-07-19 03:01:11', '2020-07-19 03:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages`
(
    `id`              int(11)   NOT NULL AUTO_INCREMENT,
    `conversation_id` int(11)   NOT NULL,
    `user_id`         int(11)   NOT NULL,
    `message`         text COLLATE utf32_unicode_ci DEFAULT NULL,
    `created_at`      timestamp NOT NULL            DEFAULT current_timestamp(),
    `updated_at`      timestamp NOT NULL            DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf32
  COLLATE = utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations`
(
    `id`        int(10) UNSIGNED                     NOT NULL AUTO_INCREMENT,
    `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
    `batch`     int(11)                              NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES (1, '2014_10_12_000000_create_users_table', 1),
       (2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens`
(
    `id`         varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_id`    int(11)                                      DEFAULT NULL,
    `client_id`  int(10) UNSIGNED                        NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci      DEFAULT NULL,
    `scopes`     text COLLATE utf8mb4_unicode_ci              DEFAULT NULL,
    `revoked`    tinyint(1)                              NOT NULL,
    `created_at` timestamp                               NULL DEFAULT NULL,
    `updated_at` timestamp                               NULL DEFAULT NULL,
    `expires_at` datetime                                     DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`,
                                   `updated_at`, `expires_at`)
VALUES ('125ce8289850f80d9fea100325bf892fbd0deba1f87dbfc2ab81fb43d57377ec24ed65f7dc560e46', 1, 1,
        'Personal Access Token', '[]', 0, '2019-07-30 04:51:13', '2019-07-30 04:51:13', '2020-07-30 10:51:13'),
       ('293d2bb534220c070c4e90d25b5509965d23d3ddbc05b1e29fb4899ae09420ff112dbccab1c6f504', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 06:00:04', '2019-08-04 06:00:04', '2020-08-04 12:00:04'),
       ('5363e91c7892acdd6417aa9c7d4987d83568e229befbd75be64282dbe8a88147c6c705e06c1fb2bf', 1, 1,
        'Personal Access Token', '[]', 0, '2019-07-13 06:44:28', '2019-07-13 06:44:28', '2020-07-13 12:44:28'),
       ('681b4a4099fac5e12517307b4027b54df94cbaf0cbf6b4bf496534c94f0ccd8a79dd6af9742d076b', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:23:06', '2019-08-04 07:23:06', '2020-08-04 13:23:06'),
       ('6d229e3559e568df086c706a1056f760abc1370abe74033c773490581a042442154afa1260c4b6f0', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:32:12', '2019-08-04 07:32:12', '2020-08-04 13:32:12'),
       ('6efc0f1fc3843027ea1ea7cd35acf9c74282f0271c31d45a164e7b27025a315d31022efe7bb94aaa', 1, 1,
        'Personal Access Token', '[]', 0, '2019-08-08 02:35:26', '2019-08-08 02:35:26', '2020-08-08 08:35:26'),
       ('7745b763da15a06eaded371330072361b0524c41651cf48bf76fc1b521a475ece78703646e06d3b0', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:29:44', '2019-08-04 07:29:44', '2020-08-04 13:29:44'),
       ('815b625e239934be293cd34479b0f766bbc1da7cc10d464a2944ddce3a0142e943ae48be018ccbd0', 1, 1,
        'Personal Access Token', '[]', 1, '2019-07-22 02:07:47', '2019-07-22 02:07:47', '2020-07-22 08:07:47'),
       ('8921a4c96a6d674ac002e216f98855c69de2568003f9b4136f6e66f4cb9545442fb3e37e91a27cad', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 06:05:05', '2019-08-04 06:05:05', '2020-08-04 12:05:05'),
       ('8d8b85720304e2f161a66564cec0ecd50d70e611cc0efbf04e409330086e6009f72a39ce2191f33a', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 06:44:35', '2019-08-04 06:44:35', '2020-08-04 12:44:35'),
       ('bcaaebdead4c0ef15f3ea6d196fd80749d309e6db8603b235e818cb626a5cea034ff2a55b66e3e1a', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:14:32', '2019-08-04 07:14:32', '2020-08-04 13:14:32'),
       ('c25417a5c728073ca8ba57058ded43d496a9d2619b434d2a004dd490a64478c08bc3e06ffc1be65d', 1, 1,
        'Personal Access Token', '[]', 1, '2019-07-30 01:45:31', '2019-07-30 01:45:31', '2020-07-30 07:45:31'),
       ('c7423d85b2b5bdc5027cb283be57fa22f5943cae43f60b0ed27e6dd198e46f25e3501b3081ed0777', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-05 05:02:59', '2019-08-05 05:02:59', '2020-08-05 11:02:59'),
       ('e76f19dbd5c2c4060719fb1006ac56116fd86f7838b4bf74e2c0a0ac9696e724df1e517dbdb357f4', 1, 1,
        'Personal Access Token', '[]', 1, '2019-07-15 02:53:40', '2019-07-15 02:53:40', '2020-07-15 08:53:40'),
       ('ed7c269dd6f9a97750a982f62e0de54749be6950e323cdfef892a1ec93f8ddbacf9fe26e6a42180e', 1, 1,
        'Personal Access Token', '[]', 1, '2019-07-13 06:36:45', '2019-07-13 06:36:45', '2020-07-13 12:36:45'),
       ('f6d1475bc17a27e389000d3df4da5c5004ce7610158b0dd414226700c0f6db48914637b4c76e1948', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:22:01', '2019-08-04 07:22:01', '2020-08-04 13:22:01'),
       ('f85e4e444fc954430170c41779a4238f84cd6fed905f682795cd4d7b6a291ec5204a10ac0480eb30', 1, 1,
        'Personal Access Token', '[]', 1, '2019-07-30 06:38:49', '2019-07-30 06:38:49', '2020-07-30 12:38:49'),
       ('f8bf983a42c543b99128296e4bc7c2d17a52b5b9ef69670c629b93a653c6a4af27be452e0c331f79', 1, 1,
        'Personal Access Token', '[]', 1, '2019-08-04 07:28:55', '2019-08-04 07:28:55', '2020-08-04 13:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes`
(
    `id`         varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_id`    int(11)                                 NOT NULL,
    `client_id`  int(10) UNSIGNED                        NOT NULL,
    `scopes`     text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `revoked`    tinyint(1)                              NOT NULL,
    `expires_at` datetime                        DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients`
(
    `id`                     int(10) UNSIGNED                        NOT NULL AUTO_INCREMENT,
    `user_id`                int(11)                                      DEFAULT NULL,
    `name`                   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `secret`                 varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `redirect`               text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `personal_access_client` tinyint(1)                              NOT NULL,
    `password_client`        tinyint(1)                              NOT NULL,
    `revoked`                tinyint(1)                              NOT NULL,
    `created_at`             timestamp                               NULL DEFAULT NULL,
    `updated_at`             timestamp                               NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`,
                             `revoked`, `created_at`, `updated_at`)
VALUES (1, NULL, 'Laravel Personal Access Client', 'eR2y7WUuem28ugHKppFpmss7jPyOHZsMkQwBo1Jj', 'http://localhost', 1, 0,
        0, '2019-07-13 06:17:34', '2019-07-13 06:17:34'),
       (2, NULL, 'Laravel Password Grant Client', 'WLW2Ol0GozbaXEnx1NtXoweYPuKEbjWdviaUgw77', 'http://localhost', 0, 1,
        0, '2019-07-13 06:17:34', '2019-07-13 06:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients`
(
    `id`         int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_id`  int(10) UNSIGNED NOT NULL,
    `created_at` timestamp        NULL DEFAULT NULL,
    `updated_at` timestamp        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`)
VALUES (1, 1, '2019-07-13 06:17:34', '2019-07-13 06:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens`
(
    `id`              varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `revoked`         tinyint(1)                              NOT NULL,
    `expires_at`      datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders`
(
    `id`                    int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`               int(11)                             DEFAULT NULL,
    `guest_id`              int(11)                             DEFAULT NULL,
    `shipping_address`      longtext CHARACTER SET utf8mb4      DEFAULT NULL,
    `payment_type`          varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
    `payment_status`        varchar(20) COLLATE utf8_unicode_ci DEFAULT 'unpaid',
    `payment_details`       longtext COLLATE utf8_unicode_ci    DEFAULT NULL,
    `grand_total`           double(12, 0)                       DEFAULT NULL,
    `coupon_discount`       double(8, 2) NOT NULL               DEFAULT 0.00,
    `code`                  mediumtext COLLATE utf8_unicode_ci  DEFAULT NULL,
    `date`                  int(20)      NOT NULL,
    `viewed`                int(1)       NOT NULL               DEFAULT 0,
    `delivery_viewed`       int(1)       NOT NULL               DEFAULT 1,
    `payment_status_viewed` int(1)                              DEFAULT 1,
    `commission_calculated` int(11)      NOT NULL               DEFAULT 0,
    `created_at`            timestamp    NOT NULL               DEFAULT current_timestamp(),
    `updated_at`            timestamp    NOT NULL               DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details`
(
    `id`                    int(11)                             NOT NULL AUTO_INCREMENT,
    `order_id`              int(11)                             NOT NULL,
    `seller_id`             int(11)                                      DEFAULT NULL,
    `product_id`            int(11)                             NOT NULL,
    `variation`             longtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `price`                 double(12, 0)                                DEFAULT NULL,
    `tax`                   double(12, 2)                       NOT NULL DEFAULT 0.00,
    `shipping_cost`         double(12, 2)                       NOT NULL DEFAULT 0.00,
    `quantity`              int(11)                                      DEFAULT NULL,
    `payment_status`        varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid',
    `delivery_status`       varchar(20) COLLATE utf8_unicode_ci          DEFAULT 'pending',
    `shipping_type`         varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `pickup_point_id`       int(11)                                      DEFAULT NULL,
    `product_referral_code` varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `created_at`            timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at`            timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `title`            varchar(255) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `slug`             varchar(255) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `content`          text COLLATE utf8_unicode_ci          DEFAULT NULL,
    `meta_title`       text COLLATE utf8_unicode_ci          DEFAULT NULL,
    `meta_description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
    `keywords`         varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
    `meta_image`       varchar(255) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `created_at`       timestamp NOT NULL                    DEFAULT current_timestamp(),
    `updated_at`       timestamp NOT NULL                    DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets`
(
    `email`      varchar(191) COLLATE utf8_unicode_ci NOT NULL,
    `token`      varchar(191) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp                            NULL DEFAULT NULL,
    KEY `password_resets_email_index` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments`
(
    `id`              int(11)      NOT NULL AUTO_INCREMENT,
    `seller_id`       int(11)      NOT NULL,
    `amount`          double(8, 2) NOT NULL                DEFAULT 0.00,
    `payment_details` longtext COLLATE utf8_unicode_ci     DEFAULT NULL,
    `payment_method`  varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `txn_code`        varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at`      timestamp    NOT NULL                DEFAULT current_timestamp(),
    `updated_at`      timestamp    NOT NULL                DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `seller_id`, `amount`, `payment_details`, `payment_method`, `txn_code`, `created_at`,
                        `updated_at`)
VALUES (2, 1, 500000.00, NULL, 'cash', NULL, '2020-07-13 03:35:22', '2020-07-13 03:35:22'),
       (3, 3, 56000.00, NULL, 'cash', NULL, '2020-07-25 01:41:07', '2020-07-25 01:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_points`
--

DROP TABLE IF EXISTS `pickup_points`;
CREATE TABLE IF NOT EXISTS `pickup_points`
(
    `id`                    int(11)      NOT NULL AUTO_INCREMENT,
    `staff_id`              int(11)      NOT NULL,
    `name`                  varchar(255) NOT NULL,
    `address`               text         NOT NULL,
    `phone`                 varchar(15)  NOT NULL,
    `pick_up_status`        int(1)                DEFAULT NULL,
    `cash_on_pickup_status` int(1)                DEFAULT NULL,
    `created_at`            timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at`            timestamp    NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4;

--
-- Dumping data for table `pickup_points`
--

INSERT INTO `pickup_points` (`id`, `staff_id`, `name`, `address`, `phone`, `pick_up_status`, `cash_on_pickup_status`,
                             `created_at`, `updated_at`)
VALUES (1, 1, 'مرکز دریافت حضوری شریعتی', 'تهران خیابان شریعتی نرسیده به میدان قدس پلاک 1399', '9121112233', 1, NULL,
        '2020-07-20 11:54:35', '2020-07-20 11:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

DROP TABLE IF EXISTS `policies`;
CREATE TABLE IF NOT EXISTS `policies`
(
    `id`         int(11)                             NOT NULL AUTO_INCREMENT,
    `name`       varchar(35) COLLATE utf8_unicode_ci NOT NULL,
    `content`    longtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `created_at` timestamp                           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at` timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `name`, `content`, `created_at`, `updated_at`)
VALUES (1, 'support_policy', NULL, '2019-10-29 09:24:45', '2019-01-22 01:43:15'),
       (2, 'return_policy', NULL, '2019-10-29 09:24:47', '2019-01-24 02:10:11'),
       (4, 'seller_policy', NULL, '2019-10-29 09:24:49', '2019-02-04 14:20:15'),
       (5, 'terms', NULL, '2019-10-29 09:24:51', '2019-10-28 14:30:00'),
       (6, 'privacy_policy', NULL, '2019-10-29 09:24:54', '2019-10-28 14:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products`
(
    `id`                int(11)                               NOT NULL AUTO_INCREMENT,
    `name`              varchar(200) COLLATE utf8_unicode_ci  NOT NULL,
    `added_by`          varchar(6) COLLATE utf8_unicode_ci    NOT NULL DEFAULT 'admin',
    `user_id`           int(11)                               NOT NULL,
    `category_id`       int(11)                               NOT NULL,
    `subcategory_id`    int(11)                               NOT NULL,
    `subsubcategory_id` int(11)                                        DEFAULT NULL,
    `brand_id`          int(11)                                        DEFAULT NULL,
    `photos`            varchar(2000) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `thumbnail_img`     varchar(100) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `featured_img`      varchar(100) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `flash_deal_img`    varchar(100) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `video_provider`    varchar(20) COLLATE utf8_unicode_ci            DEFAULT NULL,
    `video_link`        varchar(100) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `tags`              mediumtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `description`       longtext COLLATE utf8_unicode_ci               DEFAULT NULL,
    `unit_price`        double(12, 0)                         NOT NULL,
    `purchase_price`    double(12, 0)                         NOT NULL,
    `variant_product`   int(1)                                NOT NULL DEFAULT 0,
    `attributes`        varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[]',
    `choice_options`    mediumtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `colors`            mediumtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `variations`        text COLLATE utf8_unicode_ci                   DEFAULT NULL,
    `todays_deal`       int(11)                               NOT NULL DEFAULT 0,
    `published`         int(11)                               NOT NULL DEFAULT 1,
    `featured`          int(11)                               NOT NULL DEFAULT 0,
    `current_stock`     int(10)                               NOT NULL DEFAULT 0,
    `unit`              varchar(20) COLLATE utf8_unicode_ci            DEFAULT NULL,
    `discount`          double(8, 2)                                   DEFAULT NULL,
    `discount_type`     varchar(10) COLLATE utf8_unicode_ci            DEFAULT NULL,
    `tax`               double(8, 2)                                   DEFAULT NULL,
    `tax_type`          varchar(10) COLLATE utf8_unicode_ci            DEFAULT NULL,
    `shipping_type`     varchar(20) CHARACTER SET latin1               DEFAULT 'flat_rate',
    `shipping_cost`     double(8, 2)                                   DEFAULT 0.00,
    `num_of_sale`       int(11)                               NOT NULL DEFAULT 0,
    `meta_title`        mediumtext COLLATE utf8_unicode_ci             DEFAULT NULL,
    `meta_description`  longtext COLLATE utf8_unicode_ci               DEFAULT NULL,
    `meta_img`          varchar(255) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `pdf`               varchar(255) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `slug`              mediumtext COLLATE utf8_unicode_ci    NOT NULL,
    `rating`            double(8, 2)                          NOT NULL DEFAULT 0.00,
    `barcode`           varchar(255) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `digital`           int(1)                                NOT NULL DEFAULT 0,
    `file_name`         varchar(255) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `file_path`         varchar(255) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `created_at`        timestamp                             NOT NULL DEFAULT current_timestamp(),
    `updated_at`        timestamp                             NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 25
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `added_by`, `user_id`, `category_id`, `subcategory_id`, `subsubcategory_id`,
                        `brand_id`, `photos`, `thumbnail_img`, `featured_img`, `flash_deal_img`, `video_provider`,
                        `video_link`, `tags`, `description`, `unit_price`, `purchase_price`, `variant_product`,
                        `attributes`, `choice_options`, `colors`, `variations`, `todays_deal`, `published`, `featured`,
                        `current_stock`, `unit`, `discount`, `discount_type`, `tax`, `tax_type`, `shipping_type`,
                        `shipping_cost`, `num_of_sale`, `meta_title`, `meta_description`, `meta_img`, `pdf`, `slug`,
                        `rating`, `barcode`, `digital`, `file_name`, `file_path`, `created_at`, `updated_at`)
VALUES (1, 'گوشی موبایل سامسونگ مدل Galaxy A01 SM-A015F/DS دو سیم کارت ظرفیت 16 گیگابایت', 'admin', 12, 4, 10, 18, 6,
        '[\"uploads\\/products\\/photos\\/MHJWDTfGprvRb7AkXBn3mZk5KUtv3cT5hYsdlC2C.jpeg\",\"uploads\\/products\\/photos\\/2g8tGALlKZkZv7qJf7JTnRQJiZWMzw9Ld3ecI2Zd.jpeg\",\"uploads\\/products\\/photos\\/o0WhyrmE6UbAzRtHZ3fqs7TeuEYQE3O7yd3FmzNl.jpeg\"]',
        'uploads/products/thumbnail/jkNAT8RoKuRooELYWG7MKWujk428ggGd65lXQmqW.jpeg',
        'uploads/products/featured/qvLuLcRTD8aYNVI3OS63GHP4pIoY1dWE9baRqQNE.jpeg',
        'uploads/products/flash_deal/tHvztLn7CxVuCsTdc694P5MET4c4vfHaqcAxmBlf.jpeg', 'youtube', NULL, '',
        'گوشی موبایل «Galaxy A01» محصول مقرون به صرفه سامسونگ است که با صفحه‌نمایش تمام‌صفحه Infinity-V روانه بازار شده است. مانند دیگر محصولات سری A، سامسونگ تلاش کرده است حاشیه را در این تولید جدید خود تا حد امکان کم کند. این گوشی قاب پشتی از جنس پلاستیک دارد و قاب جلویی آن را شیشه پوشانده که البته جلوه‌ی زیبایی به گوشی داده است. این محصول سامسونگ با نسخه سیستم‌عامل اندروید Android 10 روانه بازار شده است تا از هر نظر گوشی مدرن به‌حساب بیاید. صفحه‌نمایش استفاده‌شده در این گوشی 5.7 اینچ با رزولوشن 1520 در 720 پیکسل است که با استفاده از فناوری PLS TFT تصاویر شفاف و بی‌نظیری را به نمایش می‌گذارد. این صفحه‌نمایش در هر اینچ 301 پیکسل را نشان می‌دهد . تراشه‌ی این محصول اسنپ‌دراگون 439 از تراشه‌های 12 نانومتری سامسونگ است که به همراه  2 گیگابایت رم عرضه می‌شود تا نشان‌دهنده میان‌رده و مقرون‌به‌صرفه بودن گوشی باشد. تراشه‌ی گرافیکی Adreno 505 هم برای پخش ویدئو و بازی‌های سبک در این محصول درنظر گرفته شده است. این گوشی در ظرفیت 16 گیگابایتی عرضه شده است و با استفاده از یک کارت حافظه‌ی جانبی قادر خواهید بود حافظه داخلی را افزایش دهید. دوربین اصلی  A10 سنسور 13 مگاپیکسلی دارد و از فلش LED برخوردار است. سنسور 2 مگاپیکسلی دیگر  از نوع حسگر عمق است و در کنار این دوربین اصلی مجموعه دوربین‌های قاب پشتی A10 را تشکیل داده‌اند. دوربین سلفی 5مگاپیکسلی هم در قاب جلویی این گوشی به کار گرفته شده است. باتری 3000 میلی‌آمپرساعتی و پشتیبانی و درگاه شارژ microUSB از دیگر ویژگی‌های این تازه‌وارد است. بنابراین، اگر به دنبال گوشی مقرون‌به‌صرفه با طراحی مدرن هستید، این محصول برند سامسونگ را به عنوان یکی انتخابی کاربردی درنظر داشته باشید.\r\n                            <br>',
        2990000, 0, 1, '[]', '[]', '[\"#000000\",\"#0000FF\",\"#00008B\",\"#FF0000\"]', NULL, 0, 1, 1, 0, NULL, 0.00,
        NULL, 0.00, NULL, 'free', 0.00, 1, NULL, NULL, NULL, NULL, '----Galaxy-A01-SM-A015FDS-----16--8DOVq', 0.00,
        NULL, 0, NULL, NULL, '2020-07-07 10:08:21', '2020-07-19 00:59:51'),
       (2, 'گوشی موبایل سامسونگ مدل Galaxy A30s SM-A307FN/DS دو سیم کارت ظرفیت 128 گیگابایت', 'admin', 12, 4, 10, 18, 6,
        '[\"uploads\\/products\\/photos\\/Mu5AsM9ZpVCt3atSQxH2oJTwWxYTI96ahpWM7QvD.jpeg\",\"uploads\\/products\\/photos\\/qLbIddAPmBVykl86IoDZ5aCxJorvkFznDQbQiVad.jpeg\",\"uploads\\/products\\/photos\\/zvE60qsXf8Cy7eGS0jOOKoQgqQgprgCnNqDcj64m.jpeg\"]',
        'uploads/products/thumbnail/hHucEvFVg2yILHCJP9AZ4ZBiMfag2USZ0Fu9H9t8.jpeg',
        'uploads/products/featured/g4fSEP29Ul8mTQgx01pA8lKA2MTt9fdTlnv2KuK7.jpeg',
        'uploads/products/flash_deal/oY9A0WwMp2oxAF5RGQKdu9z2HizRXA86pDL4iDNH.jpeg', 'youtube', NULL, '',
        'سامسونگ سال 2019 را با متنوع کردن هرچند بیشتر سری گوشی‌های A خود آغاز کرد. این سری از تولیدات سامسونگ به داشتن صفحه‌نمایش بسیار با کیفیت AMOLED و دوربین‌هایی با امکانات بالا شهرت دارند. گوشی موبایل Galaxy A30s با صفحه‌نمایش سوپر آمولد طراحی&nbsp; شده است و ظاهر هم زیبایی دارد. سامسونگ تلاش کرده است حاشیه را در این تولید جدید خود تا حد امکان کم کند. این گوشی قاب پشتی از جنس پلاستیک دارد و قاب جلویی آن را شیشه پوشانده که البته جلوه‌ی زیبایی به گوشی داده است. این محصول سامسونگ با جدیدترین نسخه از سیستم‌عامل اندروید (Pie) روانه بازار شده است تا از هر نظر گوشی مدرن به‌حساب بیاید. صفحه‌نمایش استفاده‌شده در این گوشی 6.4&nbsp;اینچ با رزولوشن 1560 در 720&nbsp;پیکسل است که با استفاده از فناوری Super AMOLED &nbsp;و پنل OLED تصاویر شفاف و بی‌نظیری را به نمایش می‌گذارد. این صفحه‌نمایش در هر اینچ 268 پیکسل را نشان می‌دهد . تراشه‌ی این محصول Exynos 7904 &nbsp;از تراشه‌های 14 نانومتری سامسونگ است که به همراه &nbsp;4 گیگابایت رم عرضه می‌شود. این تراشه برای بازکردن چندین برنامه به صورت هم‌زمان و تماشای ویدئو کاملاً مناسب است و کم نمی‌آورد. تراشه‌ی گرافیکی Mali-G71 MP2 هم برای پخش ویدئو و بازی در این محصول درنظر گرفته شده است. این گوشی در ظرفیت 128 گیگابایتی عرضه شده است و با استفاده از یک کارت حافظه‌ی جانبی قادر خواهید بود حافظه داخلی را تا یک ترابایت دیگر هم افزایش دهید. دوربین اصلی &nbsp;A30s سنسور 25 مگاپیکسلی دارد و فلش LED برخوردار است. سنسور 8 مگاپیکسلی دیگر از نوع فوق عریض (Ultrawide) هم در کنار این دوربین اصلی مجموعه دوربین‌های قاب پشتی A30s را تشکیل داده‌اند. علاوه براین یک سنسور عمق 5 مگاپیکسلی هم برای این مدل در نظر گرفت شده است. دوربین سلفی 16مگاپیکسلی هم در قاب جلویی این گوشی به کار گرفته شده است. باتری 4000 میلی‌آمپرساعتی، پشتیبانی از فناوری شارژ سریع 15 واتی، درگاه USB Type-C و حسگر اثرانگشت زیر صفحه‌نمایش هم از دیگر ویژگی‌های این تازه‌وارد است. سامسونگ این گوشی با طراحی کاملاً مطلوب و&nbsp; امکانات فراوان به بازار عرضه کرده است تا در بازار میان‌رده‌ها هم حرفی برای گفتن داشته باشد.',
        4899000, 0, 1, '[]', '[]', '[\"#8FBC8F\",\"#F8F8FF\"]', NULL, 0, 1, 1, 0, NULL, 0.00, NULL, 0.00, NULL, 'free',
        0.00, 1, NULL, NULL, NULL, NULL, '----Galaxy-A30s-SM-A307FNDS-----128--gizhc', 0.00, NULL, 0, NULL, NULL,
        '2020-07-07 10:12:50', '2020-07-20 10:11:38'),
       (3, 'گوشی موبایل سامسونگ مدل Galaxy A31 SM-A315F/DS دو سیم کارت ظرفیت 128 گیگابایت', 'admin', 12, 4, 10, 18, 6,
        '[\"uploads\\/products\\/photos\\/5WjqymRqKjYPsyqvs1sPhIDjXhPE8c84USxeaofF.jpeg\",\"uploads\\/products\\/photos\\/voDGuE4HIPpket97saCE2rEMGgm9h2DVuZByqDVV.jpeg\"]',
        'uploads/products/thumbnail/0EYAOOgTYEqDoPAke1aD5Fsxi1U5dwIBrznNsjqc.jpeg',
        'uploads/products/featured/mn0tUh1xDy301aQ3mdHfL36Pb2RAS2oEKLWRJWo2.jpeg',
        'uploads/products/flash_deal/UQO7Rmzl36VwUBpDT6Nna6nhLBu2WXEJeWfbFSVx.jpeg', 'youtube', NULL, '',
        '<br><span>\r\n                                سامسونگ گوشی جدید سری A خود را با نام «A31» در تاریخ 27 آوریل 2020 معرفی کرد. این سری از تولیدات سامسونگ به داشتن صفحه‌نمایش بسیار با کیفیت AMOLED و دوربین‌هایی با امکانات بالا شهرت دارند. گوشی موبایل Galaxy A31با صفحه‌نمایش سوپر آمولد طراحی  شده است و ظاهر هم زیبایی دارد. سامسونگ تلاش کرده است حاشیه را در این تولید جدید خود تا حد امکان کم کند. قاب جلویی آن را یک نمایشگر تمام صفحه پوشانده که البته جلوه‌ی زیبایی هم به آن داده است. این محصول سامسونگ با جدیدترین نسخه از سیستم‌عامل اندروید 10  و رابط کاربری One UI 2.0 روانه بازار شده است تا از هر نظر گوشی مدرن به‌حساب بیاید. صفحه‌نمایش استفاده‌شده در این گوشی 6.4 اینچ با رزولوشن 2400 در 1080 پیکسل است که با استفاده از فناوری Super AMOLED  تصاویر شفاف و بی‌نظیری را به نمایش می‌گذارد. این صفحه‌نمایش در هر اینچ 411 پیکسل را نشان می‌دهد . تراشه‌ی این محصول MediaTek Helio G70 است که به همراه  4 گیگابایت رم عرضه می‌شود. این گوشی در ظرفیت 128 گیگابایتی عرضه شده است و با استفاده از یک کارت حافظه‌ی جانبی قادر خواهید بود حافظه داخلی را تا یک ترابایت دیگر هم افزایش دهید. شرکت سامسونگ برای این مدل یک دوربین 4 گانه در نظر گرفته است که شامل یک لنز 48 مگاپیکسلی از نوع عریض (Wide)، یک لنز 8 مگاپیکسلی از نوع فوق‌عریض (Ultrawide) و دو لنز 5 مگاپیکسلی از نوع ماکرو سنسورعمق   است. دوربین سلفی 20 مگاپیکسلی هم در قاب جلویی این گوشی به کار گرفته شده است. باتری 5000 میلی‌آمپرساعتی، پشتیبانی از فناوری شارژ سریع 15 واتی، درگاه USB Type-C و حسگر اثرانگشت زیر صفحه‌نمایش هم از دیگر ویژگی‌های این تازه‌وارد است. سامسونگ این گوشی با طراحی کاملاً مطلوب و امکانات فراوان به بازار عرضه کرده است تا در بازار میان‌رده‌ها هم حرفی برای گفتن داشته باشد.\r\n                            </span><br>',
        5874000, 0, 1, '[]', '[]', '[\"#000000\",\"#0000FF\",\"#F8F8FF\"]', NULL, 0, 1, 1, 0, NULL, 0.00, NULL, 0.00,
        NULL, 'free', 0.00, 1, NULL, NULL, NULL, NULL, '----Galaxy-A31-SM-A315FDS-----128--ljPCM', 0.00, NULL, 0, NULL,
        NULL, '2020-07-08 01:20:33', '2020-07-19 00:40:17'),
       (4, 'گوشی موبایل شیائومی مدل Redmi 8A M1908C3KG دو سیم‌ کارت ظرفیت 32 گیگابایت', 'admin', 12, 4, 10, 20, 8,
        '[\"uploads\\/products\\/photos\\/1GGEP2P5SmNpazS2htpSut9CWouWDbuxRn3rFfgD.jpeg\",\"uploads\\/products\\/photos\\/JnpIDsblyG3yXVEGFuZBn6JTs7iSRilPlkHBxVXz.jpeg\",\"uploads\\/products\\/photos\\/7SRZHjZqanHFSK4kEiu7RmrYrca6PzDJSI76HSdH.jpeg\",\"uploads\\/products\\/photos\\/H6ssIy6fwkTI827aUFydBRUKPQvxozQzp442zg4O.jpeg\"]',
        'uploads/products/thumbnail/xsK1sHAHxhKuqo2fnGNqb41EObxVCaqDsVEdOVTQ.jpeg',
        'uploads/products/featured/rNseSI1k2mjUGPQg0KlFmAT9CQFwPWdYPxOIkv2W.jpeg',
        'uploads/products/flash_deal/LpV0RJuO7NoNnXeuE1duKdXNaI0GOnnm3WJGDPNT.jpeg', 'youtube', NULL, '',
        'گوشی مدل «Redmi 8A» از سری محصولات مقرون‌به‌صرفه شرکت مطرح شیائومی است که با پنل­ IPS LCD ساخته‌شده و فاصله لبه صفحه‌نمایش در آن بسیار کم است. این محصول با صفحه‌نمایش بدون حاشیه روانه بازار شده است، نمایشگر آن رزولوشن HD دارد و در اندازه‌ی 6.2 اینچی‌اش، حدود 271 پیکسل را در هر اینچ جا داده است. در گوشی ردمی‌ نوت 8 ای شیائومی نمایشگر تقریباً تمام قاب جلویی گوشی را پر کرده است. این مشخصه در کنار بدنه‌ای جذاب از جنس پلاستیک قرار گرفته است که ظاهر چندرنگی و جلوه­‌ای زیبا به ردمی جدید بخشیده است. این بدنه­‌ی زیبا در کنار نمایشگر این محصول که با استفاده از Corning Gorilla Glass 5 محافظت می‌شود تا حدی گوشی در برابر خط‌وخش ایمن کرده است. ویژگی دیگر Xiaomi Redmi 8A دوربین 12 مگاپیکسلی است که در قسمت پشتی این گوشی جای گرفته‌ است. دوربین‌ سلفی این محصول هم به سنسوری 8 مگاپیکسلی مجهز شده است. قابلیت اتصال به شبکه­‌های 4G &nbsp;با سرعت کاملاً مطلوب، بلوتوث نسخه­‌ی 4.2، نسخه­‌ی 9.0 از اندروید و باتری 5000 میلی آمپرساعتی از دیگر ویژگی­‌های این گوشی&nbsp;جدید هستند. ازنظر سخت­‌افزاری هم این گوشی از تراشه­‌ی Snapdragon 439 کوالکام بهره می‌­برد که در آن پردازنده‌­ای هشت‌هسته‌ا‌ی قرارگرفته تا گوشی بتواند از پس کارهای معمول و روزمره برآید. گوشی Xiaomi Redmi 8A محصولی زیبا و مقرون‌به‌صرفه است که برای انجام کارهای روزمره و استفاده معمول روانه بازار شده است.',
        2895000, 0, 1, '[]', '[]', '[\"#000000\",\"#0000FF\"]', NULL, 0, 1, 0, 0, NULL, 10.00, 'percent', 0.00, NULL,
        'free', 0.00, 0, NULL, NULL, NULL, NULL, '----Redmi-8A-M1908C3KG-----32--6DCDT', 0.00, NULL, 0, NULL, NULL,
        '2020-07-08 01:35:15', '2020-07-19 00:00:17'),
       (5, 'گوشی موبایل هوآوی مدل Y6 Prime 2019 MRD-LX1F دو سیم کارت ظرفیت 32 گیگابایت', 'seller', 3, 4, 10, 19, 7,
        '[\"uploads\\/products\\/photos\\/pNaQQ9GqEOcyOBgLvEpkCcE8SZFEPqjfSh5DmThO.jpeg\"]',
        'uploads/products/thumbnail/c6uq4TV5xmYCCmuL9aeielom6rUbBJITROkjo5mb.jpeg',
        'uploads/products/featured/sLwgfqSd20E7DtvhyAHUSFAK8NaVhXkEGYuBPj8o.jpeg',
        'uploads/products/flash_deal/CdaqyMoNtCyFxA2ORN6ItLf5Ue7ZcPDjVpi5KK93.jpeg', 'youtube', NULL, 'گوشی',
        'این مدل از بدنه پلاستیکی ساخته شده در حالی که پنل جلو آن از جنس شیشه است. ابعاد آن 156.3× 73.5×8 میلی متر و وزن آن 150گرم است. این موبایل در ماه مارس 2019 عرضه شده و بدون شک می تواند با مشخصاتی که دارد به مدل مقرون به صرفه و پرفروش سال تبدیل شود. از نظر آپشن های رنگی هم شامل رنگ مشکی، آبی فیروزه ای و قهوه ای است. رنگ قهوه ای و مشکی این مدل لایه ای از چرم مصنوعی پوشیده شده که جلوه خاصی را به آن بخشیده است . از درگاه اختصاصی کارت حافظه برخوردار بوده از این رو، این امکان به وجود آمده که همزمان از دو سیم کارت به همراه کارت حافظه استفاده کرد. از طرفی دیگر از جک 3.5 میلی متری هدفون نیز پشتیبانی کرده بنابراین بدون نیاز به مبدل می توان به انواع هدفون و هندزفری غیر بلوتوثی متصل شد که از جمله امتیازات فروش آن در نظر گرفته می شود. یکی دیگر از ویژگی های خوب آن برخورداری از یک پنل 6.09 اینچی است که آن را برای تماشای مالتی مدیا ایده ال کرده است. این پنل از جنس IPS LCD بوده و رزولوشن 720×1560 پیکسل و کیفیت تصویر HD+ را ارائه داده است. روشنایی صفحه رضایت بخش بوده و رضایت خاطر کاربران را جلب کرده است. در رابطه با کیفیت صدای آن می بایست خاطر نشان کرد که این مدل از یک میکروفن اختصاصی برای کاهش نویز صدا استفاده کرده بنابراین کیفیت صدای رضایت بخشی را ارائه داده است. این نمایشگر از بریدگی O شکل استفاده کرده که دوربین سلفی را در خود جای داده است. صفحه نمایش بزرگ به نسبت صفحه نمایش به بدنه 87% زاویه دید بسیار خوبی را برای تماشای عکس ها و ویدئوها و کتاب های مورد علاقه کاربران ارائه می دهد. همچنین این پنل دارای فیلتر پرتوهای مضر آبی است. که می تواند ویژگی بسیار کاربردی برای آن دسته افرادی در نظر گرفته شود که استفاده بیشتری در شب از گوشی خود دارند. این موبایل ارزان قیمت در دوربین اصلی تنها از یک لنز استفاده کرده و اندازه ای برابر با 13 مگاپیکسل داشته و دیافراگم آن f/1.8 است. دوربین سلفی در اینجا 8 مگاپیکسلی است. عملکرد آن در نور کافی رضایت بخش بوده اما در تاریکی کیفیت آن تنزل می یابد. همچنین این دوربین از ضبط ویدئو Full HD با سرعت 30 فریم بر ثانیه هم پشتیبانی می کند. دوربین 8 مگاپیکسلی سلفی از فلش برخوردار بوده که کیفیت به سزایی خصوصاً در کیفیت عکس ها دارد. وای 6(2019) برای تامین نیروی لازم جهت اجرای برنامه ها و وظایف مختلف پردازنده Mediatek MT6761 Helio A22 را به خدمت گرفته که بر اساس معماری و یا فرایند ساخت 12 نانومتری ساخته شده در نتیجه نسبت به چیپست های 14 و یا 28 نانومتری این حوزه عملکرد و کارایی بسیار بهتری دارد. پردازنده آن از 4 هسته Cortex-A53 تشکیل شده که با سرعت 2.0 عمل می کنند. پردازنده گرافیکی آن هم PowerVR GE8320 است. از دیگر قابلیت های ارتباطی آن هم می توان به رادیو FM، بلوتوث نسخه 4.2, ، GPS ، Wi-Fi 802.11 b/g/n, hotspot و(Wi-Fi Direct) جهت اتصال به تلویزیون اشاره کرد. از نظر حافظه هم خاطر نشان می شود که وای 6 در یک نسخه 3/ 32 گیگابایتی عرضه شده است. باتری به کار رفته در وای 6 سال 2019 اندازه ای برابر با 3020 میلی آمپر داشته که به نظر می رسد کاملاً با اندازه صفحه نمایش آن تناسب دارد اما خبری از شارژ سریع نیست که با توجه به برچسب قیمتی که برای آن در نظر گرفته شده قابل توجیه است.',
        2539000, 2539000, 1, '[]', '[]', '[\"#000000\",\"#0000FF\"]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00,
        'amount', 'flat_rate', 10000.00, 2,
        'گوشی موبایل هوآوی مدل Y6 Prime 2019 MRD-LX1F دو سیم کارت ظرفیت 32 گیگابایت',
        'گوشی موبایل هوآوی مدل Y6 Prime 2019 MRD-LX1F دو سیم کارت ظرفیت 32 گیگابایت', NULL, NULL,
        '----Y6-Prime-2019-MRD-LX1F-----32--Hj3oZ', 0.00, NULL, 0, NULL, NULL, '2020-07-12 12:20:04',
        '2020-07-13 03:12:18'),
       (6, 'تی شرت مردانه طرح لیورپول', 'seller', 14, 7, 28, 74, 9,
        '[\"uploads\\/products\\/photos\\/mKuJ9nU6gOpRQ7A3ztHcSiMVvRO0CgTHADJxoXWx.jpeg\",\"uploads\\/products\\/photos\\/lvtaGNFJYpJUiCbPve0FtDrWl7D5J80X74IDgZVv.jpeg\",\"uploads\\/products\\/photos\\/waKmR632m1k5U8GyDXumxN65F9mfTlKJlulttiCs.jpeg\"]',
        'uploads/products/thumbnail/Vj1RXuMDd9CNmSIOyVUX7iAmy0vRVZxX4fuyvqt9.jpeg',
        'uploads/products/featured/5vCmn7glgozuujetDAfxxm1tA5LWs4Xl2gJczCys.jpeg',
        'uploads/products/flash_deal/YaCpGdIB5OqpoYXnVlQk6Md2zgQOjsPApcFWQrj1.jpeg', 'youtube', NULL, 'لباس مردانه',
        '<table class=\"table table-bordered\" style=\"width: 100%; text-align: right;\">\r\n<tr>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">جنس</td>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">نخ</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">یقه</td>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">گرد</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">قد آستین</td>\r\n	<td style=\"text-align: right; background-color: rgb(243, 243, 243);\">کوتاه</td></tr></table>',
        26000, 20000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"M\",\"L\",\"XL\",\"XXL\"]}]', '[]', NULL, 0,
        1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 1, NULL, NULL,
        'uploads/products/meta/5sVkA0oIoLv29B728PqXl0lKwo6HjmRJlEMvELhX.jpeg', NULL, '-----WdWTn', 0.00, NULL, 0, NULL,
        NULL, '2020-07-18 02:35:07', '2020-07-20 11:56:54'),
       (7, 'تی شرت ورزشی نخی مردانه فلوریزا طرح بوکس', 'seller', 14, 7, 28, 74, 9,
        '[\"uploads\\/products\\/photos\\/c2xnM4Dg8d7dAOW3mf0RF8M5AUTdaysIHW7U2CjI.jpeg\",\"uploads\\/products\\/photos\\/BjBs1ltgs8VEj3bwINIJjc3LodkXG2MuZzzz24Ch.jpeg\"]',
        'uploads/products/thumbnail/7rUpp8ubN9dQn4EA88UkPv0bOfza1Cb1PUIi8mvX.jpeg',
        'uploads/products/featured/zHFE2j1ZXfpb3DrNI12VuTb6DcY6Yhse5PQoHr2M.jpeg',
        'uploads/products/flash_deal/nMRWy1bgkPrWH3uqfte9TTZH6irGlb0XJ71S3S0Z.jpeg', 'youtube', NULL, 'تی شرت',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">جنس</td>\r\n	<td style=\"text-align: right;\">نخ</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">یقه</td>\r\n	<td style=\"text-align: right;\">گرد</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">قد آستین</td>\r\n	<td style=\"text-align: right;\">کوتاه</td></tr></table>',
        36800, 0, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"M\",\"L\",\"XL\",\"XXL\"]}]', '[]', NULL, 0, 1,
        0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 0, NULL, NULL,
        'uploads/products/meta/rV5qwG6FGomqXX4m32ntI2razgTZCfUu3p17b7We.jpeg', NULL, '--------wNUuD', 0.00, NULL, 0,
        NULL, NULL, '2020-07-18 03:51:16', '2020-07-18 03:51:17'),
       (8, 'تی شرت مردانه طرح شیکاگو بولز', 'seller', 14, 7, 28, 74, 9,
        '[\"uploads\\/products\\/photos\\/3eZPj7fO3mARWYfL8IYpLMLQXjmPsrDMfkrRT6C8.jpeg\",\"uploads\\/products\\/photos\\/WAASW4qUjYW688wIxQDa58nWcViRgJmJNac2riI3.jpeg\"]',
        'uploads/products/thumbnail/0gKUAsodGT33s6w3NSzc4s4UCOLsXQuGk1sUojiG.jpeg',
        'uploads/products/featured/lUgkJoh4IPAPFstXXoRH87yYlqxx9B349K5Id9Pd.jpeg',
        'uploads/products/flash_deal/xKdaGzoPErnUs4KaL0drGOFhZ0bpznLkAgKb2k8V.jpeg', 'youtube', NULL, 'تی شرت',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">جنس</td>\r\n	<td style=\"text-align: right;\">نخ</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">قد</td>\r\n	<td style=\"text-align: right;\">روی باسن</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">طرح پارچه</td>\r\n	<td style=\"text-align: right;\">ساده</td></tr></table>',
        36700, 30000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\",\"XXL\"]}]', '[]', NULL, 0, 1, 0,
        0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 11, NULL, NULL,
        'uploads/products/meta/iRhEFHi9TNklON6vHCy0wjeq8MM2cVtqUX654hU0.jpeg', NULL, '------h6E54', 0.00, NULL, 0, NULL,
        NULL, '2020-07-18 04:21:32', '2020-08-09 08:12:34'),
       (9, 'پیراهن مردانه الیاف طبیعی چترفیروزه مدل چهارگره سفید', 'seller', 14, 7, 28, 75, 9,
        '[\"uploads\\/products\\/photos\\/09WLvziwmWY4louUKS4GqwcAfAR6KylOlJs0oZK5.jpeg\"]',
        'uploads/products/thumbnail/f7tTJJsEaIhtHKgDVuA7CWbHFCfvHRx2ulmg238f.jpeg',
        'uploads/products/featured/uu7YBstswFHqlxteTfpZxSdCAMmOl9zcAnVc0TuC.jpeg',
        'uploads/products/flash_deal/0uK6NZsXFGlkSgAc3URQ0aFa8N1UUxOSbsWBsjVZ.jpeg', 'youtube', NULL, 'پیراهن',
        'پیراهن مردانه الیاف طبیعی برند چترفیروزه مدل چهارگرهرنگبندی : سفید ، مشکی ، کرم ( نخودی ) ، زرشکی ، آبی ، سبز ، سرمه ای ، خردلی ، فیلی ، طوسی ، قهوه ای ، فیروزه ایسایزبندی 1 الی 5 مطابق جدول راهنمای سایزدارای فرم معمولی ، جلوی بسته ،آستین بلند و یقه ایستاده که با چهارگره بسته می شودداری جیب جلوی پیراهنمناسب برای کلیه فصولمناسب برای استفاده روزمرهتهیه شده از 100% الیاف طبیعیقابل شستشو در ماشین لباسشوئی&nbsp; با ماکزیمم دما 30 درجه سانتی گراد ، از مواد سفید کننده استفاده نشود ، اتوکشی با دمای کم انجام گرددتوصیه میگردد همراه با لباسهای همرنگ به صورت پشت و رو شسته شود&nbsp;',
        82000, 70000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"M\",\"L\",\"XL\"]}]', '[]', NULL, 0, 1, 0,
        0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 8000.00, 2, NULL, NULL,
        'uploads/products/meta/P32xJd4LkLDxCY871BeERRjtqM6tRoQZB92COx8u.jpeg', NULL, '--------1aF9U', 0.00, NULL, 0,
        NULL, NULL, '2020-07-18 04:25:15', '2020-07-24 10:08:09'),
       (10, 'پیراهن مردانه کد psh9-5', 'seller', 14, 7, 28, 75, 9,
        '[\"uploads\\/products\\/photos\\/aOmzgpoStd3Fy0NESPEnWc1rSadntX3Bo9TP2JTd.jpeg\",\"uploads\\/products\\/photos\\/SgDDuGkQGIhxrEhrBz6IUgB19FeRbXmELV8zY5qY.jpeg\",\"uploads\\/products\\/photos\\/PzFnYLRGmVRskanhBl9CtgsjPW6GvX9OYWGmTxqL.jpeg\"]',
        'uploads/products/thumbnail/OWShHUKtDAkbu6NwqjWLPktYf7mfLapsdDfTu3xR.jpeg',
        'uploads/products/featured/MpcNxDgtqgark2ndmQwGWDnnwah6KPoTIGd7cKyH.jpeg',
        'uploads/products/flash_deal/8EoCS0u6qpCRHqd8eiVYPGZtqvn79X1Q7eMkGRp9.jpeg', 'youtube', NULL, 'پیراهن',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tbody><tr>\r\n	<td style=\"text-align: right;\">جنس</td>\r\n	<td style=\"text-align: right;\">پنبه , پلی استر , الیاف نخی</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">قد</td>\r\n	<td style=\"text-align: right;\">روی باسن</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">یقه</td>\r\n	<td style=\"text-align: right;\">برگردان</td></tr></tbody></table>',
        48900, 40000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\",\"XXL\"]}]', '[]', NULL, 0, 1, 0,
        0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 4, NULL, NULL,
        'uploads/products/meta/G4YR0Ar7KRiiFvmT9FXYta8Tdb7mUbp4b1u8X2cs.jpeg', NULL, '---psh9-5-T5vRy', 0.00, NULL, 0,
        NULL, NULL, '2020-07-18 06:29:35', '2020-08-09 02:45:50'),
       (11, 'پیراهن مردانه کد psh7-7', 'seller', 14, 7, 28, 75, 9,
        '[\"uploads\\/products\\/photos\\/HankLpoeDXbIVVrbMd8MrF9n9c9HhJzA72BrdyUM.jpeg\",\"uploads\\/products\\/photos\\/qKjYO5MJslHekPgYxYI6fwGPqXSz00CroPRtjk6J.jpeg\",\"uploads\\/products\\/photos\\/IiPGCWIfZAT20MSwoMDg1UnIyllCcGGOHs33XF6b.jpeg\"]',
        'uploads/products/thumbnail/jZGXApZkMLcJegtQR3kVe3FdcjV19yZ6rUwCd3yR.jpeg',
        'uploads/products/featured/HXItgOdxwyqEz66mHoDH0GGCByVt4GfMODQlPK1P.jpeg',
        'uploads/products/flash_deal/0jFLKXdWCPDWiub4jf0dCvOqqD2cbFEGbtuUhZh6.jpeg', 'youtube', NULL, 'پیراهن',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">جنس</td>\r\n	<td style=\"text-align: right;\">پنبه</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">قد</td>\r\n	<td style=\"text-align: right;\">روی باسن</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">طرح پارچه</td>\r\n	<td style=\"text-align: right;\">طرح دار</td></tr></table>',
        49000, 40000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"L\",\"XL\",\"XXL\"]}]', '[]', NULL, 0, 1, 0,
        0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 2, NULL, NULL,
        'uploads/products/meta/yhVmoyDWTq0pILRoYDrH1PXh8ezIDCG6bBYRKCID.jpeg', NULL, '---psh7-7-iV0uO', 0.00, NULL, 0,
        NULL, NULL, '2020-07-18 06:41:40', '2020-08-09 00:32:56'),
       (12, 'کفش مخصوص پیاده روی مردانه کد est', 'seller', 14, 7, 29, 77, 9,
        '[\"uploads\\/products\\/photos\\/CuaISphMA0b42dlr8IRI9uzldPlHDCDHV6DKi3Jo.jpeg\"]',
        'uploads/products/thumbnail/FQIWznHKfmSN401ksAcrVRbTdwVtQIeUE339a01d.jpeg',
        'uploads/products/featured/E0IYg7gZIzwsHHc4U75DAfCB9zYoGgD5UULQNJ0g.jpeg',
        'uploads/products/flash_deal/Vs2UWNRdiC3bkLNZcGo2NFVu7MBxmuEPLMjKq04X.jpeg', 'youtube', NULL, 'کفش',
        'کفش مخصوص پیاده‌روی کد «est» با رویه‌ای از جنس چرم مصنوعی طراحی و روانه بازار شده است؛ این رویه مشکی ساده است و ظاهری براقی به کفش داده است. کفش مخصوص پیاده‌روی est دارای نواری زردرنگ در قسمت آج است که در تناقض با رنگ مشکی، ظاهری اسپرت را ایجاد کرده است. از این محصول می‌توان به عنوان یک کفش روزمره استفاده کرد و در پیاده‌روی‌ها آن را با خود به همراه داشت. کفش est با استفاده از بند در پا محکم می‌شود. زیره به‌کاررفته در این کفش به شکل ضربه‌گیر عمل می‌کند و مانع از انتقال فشارهای وارده به زانو و کمر می‌شود. این زیره در برابر سایش مقاوم است. پا قلب دوم انسان است بنابراین استفاده از کفش مناسب و راحت یکی از فاکتورهای مهم در سلامتی انسان است. این کفش مخصوص پیاده‌روی هم با توجه به این نکته تولید و روانه بازار شده است.',
        99000, 80000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"41\",\"42\",\"43\"]}]', '[]', NULL, 0, 1, 0,
        0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 1, NULL, NULL, NULL, NULL, '------est-nxz4r', 0.00, NULL,
        0, NULL, NULL, '2020-07-18 06:57:30', '2020-08-09 12:21:31'),
       (13, 'کفش کوهنوردی کد B157T', 'seller', 14, 7, 29, 77, 9,
        '[\"uploads\\/products\\/photos\\/bcklkHr3U9s4uOWieVq2IySx3GmOvqGLxYJK9HxZ.jpeg\"]',
        'uploads/products/thumbnail/RebA8Rl4s8sUljLfhvp6Po1vmRhbDIkfS2iQ09qX.jpeg',
        'uploads/products/featured/Wqxa9xwcAB0F5tDPDvNZZNXNMAeNdvcCuvv4jDV0.jpeg',
        'uploads/products/flash_deal/edErxuF6600Lde81mIBs8lLXgY1cVP0h8jRc6BfP.jpeg', 'youtube', NULL, 'کفش',
        'کفش‌های کوهنوردی معمولا یکی‌از مهم‌ترین و گران‌ترین تجهیزات کوهنوردی هستند که در سلامت کوهنورد نقش بسیار زیادی دارند و به‌همین‌دلیل موقع خرید و انتخاب آن‌ها باید دقت زیادی کرد. کفش کوهنوردی پاریس جامه کد B157T ازجمله کفش‌های مخصوص کوهنوردی است که برای کوهپیمایی، کوهنوردی و استفاده شهری در فصول سرد مناسب است. زیره این‌کفش آج‌دار بوده و ازجنس پلی‌اورتان است و مقاومت و انعطاف‌پذیری بالایی دارد. این مشخصه، باعث می‌شود درهنگام کوهپیمایی علاوه‌بر اینکه نگرانی بابت سایش و لغزش نداشته باشید، احساس خستگی نکنید و از راحتی بیشتری برخوردار باشید. کفی این‌کفش طبی بوده و قابل تعویض است و انعطاف‌پذیری بالایی دارد که به راحت‌بودن این‌کفش افزوده. رویه این‌کفش ترکیبی‌از چرم مصنوعی و برزنت است که با‌کیفیت بالایی دوخته شده. کفی و آستر این‌کفش ضد‌تعریق و آنتی‌باکتریال بوده و از ایجاد بوی نامطبوع جلوگیری می‌کند. کفش کوهنوردی پاریس جامه کد B157T دوردوخت و بسیار باکیفیت و زیباست و برای افرادی‌که به‌دنبال یک کفش کوهنوردی هستند توصیه می‌شود.',
        349000, 300000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"40\",\"41\",\"42\",\"43\"]}]', '[]', NULL,
        0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL, NULL,
        'uploads/products/meta/J4PugFjgfML0FUpYhIy4S0lZDPQsubkh9xQFIRmh.jpeg', NULL, '---B157T-YGxSn', 0.00, NULL, 0,
        NULL, NULL, '2020-07-18 07:03:06', '2020-07-18 07:03:07'),
       (14, 'یخچال و فریزر دوقلوی پارس مدل LRDST170WD-FRZNF170', 'seller', 15, 8, 37, 99, 16,
        '[\"uploads\\/products\\/photos\\/IyQMZpHw1VeG75AxqvuLsLQmC9V0H4VeBvX69ps3.jpeg\"]',
        'uploads/products/thumbnail/8pGGBsmDwdwLsHFbNqCwJPNC12Yutp8ZzdFwnQxL.jpeg',
        'uploads/products/featured/Gw8MZNN7j2lUyqrIJHGyk6e1yOu6G10OgU0IS8Vh.jpeg',
        'uploads/products/flash_deal/A83zf0gdyZCSVOxdlG6irxxIWqxZxJK6CQSIR7NP.jpeg', 'youtube', NULL, 'یخچال',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">نمودار مصرف انرژی</td>\r\n	<td style=\"text-align: right;\">B</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">اطلاعات نوع یخچال</td>\r\n	<td style=\"text-align: right;\">یخچال فریزر</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">وزن</td>\r\n	<td style=\"text-align: right;\">1 کیلوگرم</td></tr></table>',
        10500000, 0, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free',
        0.00, 0, NULL, NULL, 'uploads/products/meta/dVrXc2i5jJcA6tV1kvHxQrFARIg0iQWSCdnDptQS.jpeg', NULL,
        '------LRDST170WD-FRZNF170-HTuoi', 0.00, NULL, 0, NULL, NULL, '2020-07-18 08:05:27', '2020-07-18 08:05:27'),
       (15, 'یخچال و فریزر امرسان مدلBFN20D321', 'seller', 15, 8, 37, 99, 11,
        '[\"uploads\\/products\\/photos\\/DPSnxmDpDhsBqThoTzU5mrYlKezTwr3C8qH3uAAH.jpeg\"]',
        'uploads/products/thumbnail/Fw73cmVZe0NzgNWMh6XysPi52PKZY0PESn19M0O1.jpeg',
        'uploads/products/featured/9KU8y8ytJoL8lzkwCskhJliXZyE4bUetD2gRuv0Q.jpeg',
        'uploads/products/flash_deal/11WSiPGyP9IhCiz33QPjlAgvDte8QjmYr2hZuIIT.jpeg', 'youtube', NULL, 'یخچال',
        'خچال فریزر 20 فوت امرسان مدل BFN20D321 دارای طراحی زیبا و ظرفیت مناسب برای خانواده هایی با جمعیت متوسط و زیاد میباشد . رده انرژی این دستگاه A بوده و طراحی آن به صورت فریزر پایین است. از طریق پنل لمسی روی یخچال میتوان میزان دما داخلی یخچال و فریزر و همچنین دیگر مشخصات را تنظیم نمود. وجود سیستم جریان هوای چندگانه موجب خنک شدن یکپارچه تمام طبقه های یخچال و فریزر میشود . سیستم خنک کنندگی فریزر دستگاه به صورت دیفراست بوده و این فریزر دارای 3 کشو برای قرار گیری حجم زیادی از مواد غذایی میباشد. قسمت یخچال نیز دارای 4 طبقه ، 2 کشو برای نگهداری میوه و سبزی و 5 محفظه روی درب میباشد. ابریز دستگاه روی درب یخچال واقع شده و به صورت دستی پر میشود که نیاز به لوله کشی را رفع می نماید. در صورت باز ماندن درب یخچال و فریزر پس از مدتی از طریق بوق ، شما را مطلع میسازد.',
        6900000, 0, 1, '[]', '[]', '[\"#C0C0C0\"]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free',
        0.00, 0, NULL, NULL, NULL, NULL, '----BFN20D321-okbDR', 0.00, NULL, 0, NULL, NULL, '2020-07-18 08:11:13',
        '2020-07-18 09:26:39'),
       (16, 'یخچال فریزر دوو مدل D2BF-0028GW', 'seller', 15, 8, 37, 99, 15,
        '[\"uploads\\/products\\/photos\\/Xk5eOBQ0CF0fKl047cmJmqiGKCoVPLNxsSpDf7sI.jpeg\"]',
        'uploads/products/thumbnail/TbpcEUmF0WBXeTdxokQEDqvRyYiazzlBIHi7hMTu.jpeg',
        'uploads/products/featured/IWQoNf8JJn2IAoTFVIt1O7b6lUevdpTtjBWMshkt.jpeg',
        'uploads/products/flash_deal/rR8PutcqhmM7dtov2NwEnEXQUhSWT4N9SeoBHsN6.jpeg', 'youtube', NULL, 'یخچال',
        'یخچال فریزر پایین دوو مدل D2BF-0028GW، با طراحی لوکس و کلاسیک یکی از یخچال فریزر های که مجهز به نسل جدید کمپرسورهای کم مصرف ، با قابلیت کنترل رطوبت، سیستم انجماد سریع و سیستم قدرتمند گردش هوای سه بعدی و همچنین دارای سامانه مدیریت مصرف هنگام سفر است. جدیدترین یخچال فریزر دوو مجهز به سیستم سرمایش دوگانه است، این سامانه باعث جدایی جریان مستقل سرمایش یخچال از فریزر شده و در نتیجه غذای موجود در هر محفظه در بالاترین کیفیت و تازگی نگهداری می شود. همچنین این سامانه مانع از انتشار بوی غیرمنتظره از فریزر به یخچال و بالعکس می شود.',
        19600000, 0, 1, '[]', '[]', '[\"#FFFAF0\"]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free',
        0.00, 0, NULL, NULL, 'uploads/products/meta/1Uo5wCsXu6pA2THCitIxK1uGCZ4fyM7cnU6YS2Dz.jpeg', NULL,
        '----D2BF-0028GW-yxurm', 0.00, NULL, 0, NULL, NULL, '2020-07-18 08:16:07', '2020-07-18 08:16:08'),
       (17, 'تلویزیون ال ای دی اسنوا مدل SLD-43SA260 سایز 43 اینچ', 'seller', 15, 8, 34, 90, 12,
        '[\"uploads\\/products\\/photos\\/moVMZIkClITosIpO6AzQ0iCnQU4AoiL0x5SWEyOt.jpeg\",\"uploads\\/products\\/photos\\/wOswZzH2IIX7q5WSg5o4ldpJUwlyvpV9D6PKsl0v.jpeg\"]',
        'uploads/products/thumbnail/Zxw5np413t7DIvU16rTtfr8afNgZPjYoapIfiqGH.jpeg',
        'uploads/products/featured/PuEUBHD3XMXYzuvQR8wYuH6UoYmApIdM2YCkhHGk.jpeg',
        'uploads/products/flash_deal/ysM2p9lTXXyesvCbR76SUK6gnBQ1Gk7gQGDfTck6.jpeg', 'youtube', NULL, 'تلویزیون',
        'برند اسنوا متعلق به یک کمپانی ایرانی است که سابقه درخشانی در تولید و عرضه لوازم صوتی و تصویری دارد. تلویزیون اسنوا از مواد با کیفیت و مقاوم ساخته شده است و با مجهز بودن به فناوری و تکنولوژی های روز دنیا، تصویر را خوش رنگ، درخشان و با کیفیت فوق العاده ای پخش می کند. همان قدر که کیفیت تصویر در یک تلویزیون اهمیت بالایی دارد کیفیت و رسا بودن صدا نیز مهم است اسنوا نیز با توجه به این نکته از بلندگوهای حرفه ای که صدا را شفاف و قدرتمند پخش می کنند در این محصولات استفاده کرده است. تلویزیون ال ای دی اسنوا SLD-43SA260 دارای تمامی امکانات اولیه مورد نیاز یک تلویزیون می باشد. این محصول دارای صفحه تخت بوده که از 3 جهت بدون فریم می باشد و امکان پخش تصاویر با کیفیت Full HD را دارد. زاویه دید گسترده این تلویزیون این امکان را فراهم کرده تا تماشای فیلم از زوایای متفاوت نیز فراهم شود. تلویزیون 43SA260 اسنوا مجهز به گیرنده دیجیتال DVBT2 می باشد تا شما دیگر نیاز به خرید یک دستگاه جداگانه نداشته باشد، و به راحتی تمامی شبکه های صدا و سیما را دریافت می کند. همچنین این تلویزیون دارای اسپیکر 20 واتی می باشد که می‌تواند خروجی صدای دالبی را برای شما فراهم ساخته و مشاهده فیلم‌های با کیفیت را دو چندان لذت بخش نماید. این تلویزیون دارای تکنولوژی EPG است که جهت اطلاع کاربر از زمان پخش برنامه های مورد علاقه تعبیه شده است و در صورتیکه این قابلیت از طرف فرستنده در شبکه وجود داشته باشد برنامه ها اعلام میشود. از دیگر ویژگی های تلویزیون ال ای دی اسنوا SLD-43SA260 می توان به قابلیت اتصال به گوشی همراه ، 3 ورودی HDMI و 2 ورودی USB که از طریق آن می توان فلش مموری یا هارد اکسترنال را به آن متصل کنید، قابلیت ضبط تصاویر در حافظه خارجی (PVR) و پنل مقاوم در برابر ضربه IPS (پنل 50 اینچ VA) را نام برد.',
        5250000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 1, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL,
        NULL, 'uploads/products/meta/NndBwycvtFkqK1WLCtsUWwKO7zEXGMbHfgBjCDWm.jpeg', NULL,
        '------SLD-43SA260--43--l23C9', 0.00, NULL, 0, NULL, NULL, '2020-07-18 08:47:03', '2020-07-18 09:25:50'),
       (18, 'تلویزیون ال ای دی ال جی مدل 43LJ52100 سایز 43 اینچ', 'seller', 15, 8, 34, 90, 17,
        '[\"uploads\\/products\\/photos\\/MmKsJNp69ziy89Lc0isoP7kA1xg1E8Egqg2ZQJcq.jpeg\"]',
        'uploads/products/thumbnail/K0J3DxAas9Hdd0OPnA9dfhRajM57Ocaxz9JSdMY7.jpeg',
        'uploads/products/featured/5oqvJepO1mRWGhl7Y5wp42nkUGqPIp228wWJBZPw.jpeg',
        'uploads/products/flash_deal/mjA9BM4saGsVDkn9OxIXRPkl4wuxqHqdRC1K9UKG.jpeg', 'youtube', NULL, 'تلویزیون',
        '43LJ52100GI، تلویزیونی 43 اینچی محصول کمپانی ال‌جی است که با توجه به سایز آن، مناسب فضاهای کوچکتر است. کیفیت تصویر در این دستگاه، Full HD، دارای زاویه‌ی دید گسترده، مجموع قدرت خروجی بلندگوهای آن 10 وات، و دارای سیستم بلندگوی استریو می‌باشد. گیرنده‌ی دیجیتالی داخلی در این تلویزیون شما را از دستگاه جداگانه برای دریافت شبکه‌های دیجیتالی بی‌نیاز می‌کند. اگرچه، درگاه‌های ارتباطی 43LJ52100GI یک عدد پورت USB، پورت‌های HDMI و یک عدد درگاه کامپوننت را نیز شامل می‌شود. درگاه USB قابلیت استفاده از فلش و هارد اکسترنال را به طور مستقیم و وجود پورت HDMI، امکان دریافت تصاویر با کیفیت برایتان فراهم می‌کند. ضمنا این تلویزیون دارای قابلیت اتصال به دیوار است و شما می‌توانید با توجه به شرایط منزل یا محلی که تلویزیون را در آن قرار می‌دهید آن را روی میز تلویزیون قرار داده یا به دیوار نصب کنید.',
        8940000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 1, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL,
        NULL, 'uploads/products/meta/IlM7iGFPTpfDbgZDnHeF2k4blvl1p8Wl5pR9AX9m.jpeg', NULL,
        '-------43LJ52100--43--1EdiF', 0.00, NULL, 0, NULL, NULL, '2020-07-18 09:05:11', '2020-07-18 09:25:43'),
       (19, 'تلویزیون ال ای دی هوشمند خمیده سامسونگ مدل 55NU7950 سایز 55 اینچ', 'seller', 15, 8, 34, 90, 6,
        '[\"uploads\\/products\\/photos\\/HLnwln3hDSYHhMSW6XxAiI5x7Sajre8funOs1JKG.jpeg\"]',
        'uploads/products/thumbnail/3q1wycmN6UdFtFvXaK999piDm1JTRKnEL0ownQkw.jpeg',
        'uploads/products/featured/HNisVrTUZNNVpanMCMKo07Eo5dDXaFKjWnlK8a1f.jpeg',
        'uploads/products/flash_deal/5BrH62clTMQRLHe0GyAFmOdC49WpkMRqo2gFSJZV.jpeg', 'youtube', NULL, 'تلویزیون',
        'تلویزیون هوشمند «سامسونگ» مدل «55NU7950» محصولی از سامسونگ است که برای کاربران معمولی با سالن متوسط و نسبتاً بزرگ گزینه‌ی بسیار خوبی به حساب می‌آید. صفحه‌نمایش تخت 55 اینچی این تلویزیون، تصاویر را با کیفیت 4K پخش می‌کند؛ درنتیجه می‌توانید از تماشای فایل‌های تصویری خود با کیفیت بسیار خوب لذت ببرید؛ البته این کیفیت‌تصویر درصورتی‌که محتوای 4K به تلویزیون منتقل کنید، قابل‌استفاده است. در کنار تصویر خوب، سیستم‌صوتی نیز متناسب با آن طراحی شده است. اسپیکرها درمجموع قادر به تولید 20 وات صدا هستند که این میزان برای تلویزیون‌های بزرگ‌تر هم در نظر گرفته می‌شود و پشتیبانی آن‌ها از فرمت‌های Dolby Digital Plus و DTS باعث می‌شود که بتوانید فیلم‌ها یا فایل‌های دیگری را که به این سیستم‌های صوتی مجهز شده‌اند، در این تلویزیون به اجرا درآورید. پایه‌ی 55NU7950 در قسمت مرکزی بدنه قرار دارد. رنگ تلویزیون، نوک‌مدادی تیره و نزدیک به مشکی است و بدنه تماماً فلزی طراحی شده است. این تلویزیون دو عدد پورت USB و سه عدد پورت HDMI دارد که اتصالات موردنیاز کاربران را ممکن می‌سازد. می‌توانید در فلش‌مموری خود فیلم یا سریال موردعلاقه‌تان را ذخیره کنید و پس از اتصالش به تلویزیون، از آن بهره‌مند شوید؛ همچنین می‌توانید سایر پخش‌کننده‌های دیجیتالی را از پورت HDMI به آن متصل کرده و تصاویر باکیفیتی داشته باشید. برای اتصال به اینترنت هم می‌توانید یکی از راه‌های Wi-Fi یا LAN را انتخاب کنید. برای افرادی که سیستم‌صوتی خانگی دارند، خروجی اپتیکال در نظر گرفته شده است تا بتوانند از صدایی واضح‌تر بهره‌مند شوند. 55NU7950 تلویزیونی هوشمند است که به ‌لطف سیستم‌عامل Smart Hub، امکانات بسیار متنوعی را برای کاربرانش فراهم می‌کند و امکان جست‌وجو در اینترنت، دانلود و نصب اپلیکیشن‌های فروشگاه سامسونگ در آن میسر شده است. این مدل به گیرنده‌ی دیجیتال داخلی مجهز است که شما را از تهیه‌ی یک دستگاه جداگانه بی‌نیاز می‌کند. در کنار امکانات نام‌برده، کاربران می‌توانند تصویر درحال‌پخش از تلویزیون را به‌واسطه‌ی قابلیت‌های DLNA و Mirroring با موبایل یا سایر دستگاه‌های دیجیتالی به‌ اشتراک بگذارند. این تلویزیون با ساندبارها و میزهای منحنی، ترکیب بسیار جالبی ارائه می‌دهد که در دکوراسیون بسیار مفید است.',
        18740000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 1, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL,
        NULL, 'uploads/products/meta/WdCqzNPITB32TXDnhbNn2Hc7vv41w60B4biY5OjQ.jpeg', NULL,
        '--------55NU7950--55--bSf7p', 0.00, NULL, 0, NULL, NULL, '2020-07-18 09:23:21', '2020-07-18 09:25:39'),
       (20, 'کرم روشن کننده قوی درمالیفت مدل Melalift Cream حجم 40 میلی لیتر', 'seller', 16, 5, 17, 41, 9,
        '[\"uploads\\/products\\/photos\\/oFc4LdKLKKcUINScgJ0g9jTfLvasWWhdWds15FYo.jpeg\"]',
        'uploads/products/thumbnail/xdqAmMxHTcqAiV5glumNaLbLAHyDpMMCYNvxRfaE.jpeg',
        'uploads/products/featured/5qd4kSaf5XdQHl8FfhlZ7xuvQMzsvZjsCaHWkuQM.jpeg',
        'uploads/products/flash_deal/9fdm34kwzD7oB4vZW0OX75E8CONhViUWXfmMrwUz.jpeg', 'youtube', NULL, 'بهداشتی',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">حجم</td>\r\n	<td style=\"text-align: right;\">40 میلی لیتر</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">مخصوص</td>\r\n	<td style=\"text-align: right;\">صورت</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">کشور سازنده</td>\r\n	<td style=\"text-align: right;\">ایران</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">راهنمای استفاده</td>\r\n	<td style=\"text-align: right;\">روزانه دو بار کرم را بر روی مناطق تیره پوست مالیده و به خوبی ماساژ دهید. در طی روز حتما از کرم ضد آفتاب با SPF مناسب استفاده نمائید.<br></td></tr></table>',
        40000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 0,
        NULL, NULL, NULL, NULL, '------Melalift-Cream--40---s0iwk', 0.00, NULL, 0, NULL, NULL, '2020-07-19 01:58:09',
        '2020-07-19 01:58:09'),
       (21, 'کرم آبرسان کامان سری واتربمب مدل Q10+E حجم 200 میلی لیتر', 'seller', 16, 5, 17, 41, 9,
        '[\"uploads\\/products\\/photos\\/JOb7FwEkLGUaE4negLO3TbIini0qLhNFveAGlTOW.jpeg\",\"uploads\\/products\\/photos\\/13nlaZ1q0j3Oio4HHw7KUr6oKRTwPf8k50eVpcG5.jpeg\",\"uploads\\/products\\/photos\\/m6G7JqPAFQqUmDMygQTfuBwfrRjlVXEI9u0Jxxjd.jpeg\"]',
        'uploads/products/thumbnail/JvVicSENOAzlI6UUzw1hYVbortflT5j4MzDaXYiH.jpeg',
        'uploads/products/featured/QHRt1WFGwDUiZA9bUqhU2YllEd4zoAmO3uzSusNr.jpeg',
        'uploads/products/flash_deal/Gs4tVzFX22uY2nDRvtPWACEbrxeqbBHiBrZoHtT2.jpeg', 'youtube', NULL, 'بهداشتی',
        'کرم آبرسان صورت کامان با فرمولاسیون جدید بر پایه آب، حاوی هیالورونیک اسید، کوآنــزیم Q10 و ویتــامین E است و همچنین سرشار از آنتی اکسیدان های گروه میوه های Berry میباشد که با آبرسانی فوق العاده پوست از ایجاد علائم پیری و چین و چروکهای پوستی جلوگیری میکند. این کرم با رطوبت رسانی و حفظ رطوبت در پوست به شادابی، جوانسازی و کنترل چربی هرچه بیشتر پوست کمک مینماید.',
        37400, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 0,
        NULL, NULL, NULL, NULL, '------Q10E--200---x1z0v', 0.00, NULL, 0, NULL, NULL, '2020-07-19 02:06:45',
        '2020-07-19 02:08:14'),
       (22, 'گیتار کلاسیک ایران ساز مدل F850D', 'seller', 17, 9, 41, 111, 9,
        '[\"uploads\\/products\\/photos\\/SMecf6hUkGNgrergcU1ObzBIGSSZXSTGZbimMFpe.jpeg\"]',
        'uploads/products/thumbnail/iEGBLmzEAwEDO0knoJgZMWtWfVYlZXhxnACCarZP.jpeg',
        'uploads/products/featured/3hshivxc4D9dZrGTmsIRsrNMhl1M7SmuXsdZcs7B.jpeg',
        'uploads/products/flash_deal/ZWRcIR71aMhSzvFBzndOlCyxVRK5P5hzaE5Z3SNw.jpeg', 'youtube', NULL, 'ساز',
        'اگر به دنبال سازی زیبا با کیفیت خوب و قیمت مناسب هستید گیتار ایران ساز مدل F850D یکی از بهترین گزینه ها می باشد.این ساز کاملا استاندارد است و تا آخرین فرت ساز ژوست و دقیق است و صدایی زیبا و دلنشین دارد.دسته ساز استاندارد و دقیق است و راحتی خاصی در دستان نوازنده دارد.فرت ها با کیفیت است و بدون گز تمام نقاط ساز جواب میدهد. رنگ ساز مات است و زیبایی خاصی دارد.ماشین هد های ساز نرم و راحت بوده و کوک را عالی نگهمیدارد. در مجموع ساز ایران ساز مدل F850D سازی با کیفیت و خوش صداس و مخصوص نوازندگان سطح متوسط می باشد.',
        930000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL,
        NULL, NULL, NULL, '-----F850D-KEmdl', 0.00, NULL, 0, NULL, NULL, '2020-07-19 02:27:25', '2020-07-19 02:28:40'),
       (23, 'گیتار الکتریک شکتر مدل Balsac The Jaws \'o Death - 1555', 'seller', 17, 9, 41, 111, 9,
        '[\"uploads\\/products\\/photos\\/nAm7ib7ggebcFnDdG2rfoEEml4MVPTLezjOjQxeR.jpeg\"]',
        'uploads/products/thumbnail/USuELe2R0gnWjJaXQC5jo7fG5TXuvreHZ8WCGEMI.jpeg',
        'uploads/products/featured/xkErlBR911ZD0fqswYwrftyLmEFvAAAV6dMnrLnj.jpeg',
        'uploads/products/flash_deal/II9PcaPB63BFHxHc8mDWNeUINVWMnLA6EwLr568b.jpeg', 'youtube', NULL, 'گیتار',
        '<table class=\"table table-bordered\" style=\"width: 100%;\">\r\n<tr>\r\n	<td style=\"text-align: right;\">نوع ساز</td>\r\n	<td style=\"text-align: right;\">گیتار الکتریک</td></tr>\r\n<tr>\r\n	<td style=\"text-align: right;\">اندازه</td>\r\n	<td style=\"text-align: right;\">4/4</td></tr></table>',
        22200000, 0, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, NULL,
        NULL, NULL, NULL, '----Balsac-The-Jaws-o-Death---1555-DuGQg', 0.00, NULL, 0, NULL, NULL, '2020-07-19 02:46:33',
        '2020-07-19 02:46:33'),
       (24, 'کیک روکش دار با مغزی توت فرنگی شیرین وطن - 40 گرم', 'seller', 19, 12, 43, 117, 9,
        '[\"uploads\\/products\\/photos\\/j054URBcbJEKtQSm0y6RL4AmBGKP8LAQ8wbrtivy.jpeg\"]',
        'uploads/products/thumbnail/H7xlbfcpmuNi8X5xc1DJVixISHBcgYrmmIleuAUs.jpeg',
        'uploads/products/featured/ZVjd5e5tqUvFmrQzfmeeoOrzLnFYH9m0RTjZKUqQ.jpeg',
        'uploads/products/flash_deal/O1HgeUWpFAKCCjWJxmby3dTx0kkSUmABrbyp5txt.jpeg', 'youtube', NULL, 'کیک',
        'صنایع غذایی شیرین وطن تولید کننده انواع کیک، شیرینی، شکلات، بیسکوئیت، ویفر و... به عنوان صادر کننده نمونه در چند سال متوالی با دغدغه ی سلامت و تندرستی مصرف کنندگان همواره محصولاتی با کیفیت و مرغوب به ارائه نموده است. از مهمترین مزیت های محصولات شیرین وطن برحسب نوع کالا عبارتند از: استفاده از مواد اولیه غیر تراریخته، استفاده از روغن بدون پالم، نسبت شکر و روغن استفاده شده در پایینترین میزان ممکن، عدم استفاده از افزودنی های شیمیایی، عدم استفاده از جوش شیرین در محصولات مرتبط، استفاده از رنگ کاملاً طبیعی، استفاده از دستگاههای تمام اتوماتیک بدون دخالت دست. همچنین از افتخارات این برند داشتن استانداردهای داخلی و بین المللی تشویقی علاوه بر استانداردهای الزامی می باشد.',
        1000, 1000, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 2, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00,
        8, NULL, NULL, 'uploads/products/meta/kJQ6kxLT9hopDo4Uay5zQX8pELzFj9RhkUpukMDr.jpeg', NULL,
        '-----------40--0gwTN', 0.00, NULL, 0, NULL, NULL, '2020-08-09 12:51:17', '2020-08-10 00:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

DROP TABLE IF EXISTS `product_stocks`;
CREATE TABLE IF NOT EXISTS `product_stocks`
(
    `id`         int(11)       NOT NULL AUTO_INCREMENT,
    `product_id` int(11)       NOT NULL,
    `variant`    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `sku`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `price`      double(12, 0) NOT NULL               DEFAULT 0,
    `qty`        int(11)       NOT NULL               DEFAULT 0,
    `created_at` timestamp     NOT NULL               DEFAULT current_timestamp(),
    `updated_at` timestamp     NOT NULL               DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 57
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `product_stocks`
--

INSERT INTO `product_stocks` (`id`, `product_id`, `variant`, `sku`, `price`, `qty`, `created_at`, `updated_at`)
VALUES (12, 5, 'Black', '1698679', 2539000, 8, '2020-07-12 12:20:04', '2020-07-13 03:12:18'),
       (13, 5, 'Blue', '1698679', 2539000, 10, '2020-07-12 12:20:04', '2020-07-12 12:20:04'),
       (14, 6, 'M', '799702', 26800, 9, '2020-07-18 02:35:08', '2020-07-20 11:56:53'),
       (15, 6, 'L', '799702', 26000, 10, '2020-07-18 02:35:08', '2020-07-18 02:35:08'),
       (16, 6, 'XL', '799702', 24500, 10, '2020-07-18 02:35:08', '2020-07-18 02:35:08'),
       (17, 6, 'XXL', '799702', 29000, 10, '2020-07-18 02:35:08', '2020-07-18 02:35:08'),
       (18, 7, 'M', '919013', 36800, 10, '2020-07-18 03:51:16', '2020-07-18 03:51:16'),
       (19, 7, 'L', '919013', 36800, 10, '2020-07-18 03:51:17', '2020-07-18 03:51:17'),
       (20, 7, 'XL', '919013', 36800, 10, '2020-07-18 03:51:17', '2020-07-18 03:51:17'),
       (21, 7, 'XXL', '919013', 36800, 10, '2020-07-18 03:51:17', '2020-07-18 03:51:17'),
       (22, 8, 'L', '2113732', 36700, -1, '2020-07-18 04:21:32', '2020-08-09 08:12:34'),
       (23, 8, 'XL', '2113732', 36700, 10, '2020-07-18 04:21:32', '2020-07-18 04:21:32'),
       (24, 8, 'XXL', '2113732', 36900, 10, '2020-07-18 04:21:32', '2020-07-18 04:21:32'),
       (25, 9, 'M', 'dkp-429276', 82000, 8, '2020-07-18 04:25:15', '2020-07-24 10:08:08'),
       (26, 9, 'L', 'dkp-429276', 82000, 10, '2020-07-18 04:25:16', '2020-07-18 04:25:16'),
       (27, 9, 'XL', 'dkp-429276', 82000, 10, '2020-07-18 04:25:16', '2020-07-18 04:25:16'),
       (28, 10, 'L', 'dkp-2036633', 48900, 6, '2020-07-18 06:29:36', '2020-08-09 02:45:50'),
       (29, 10, 'XL', 'dkp-2036633', 48900, 10, '2020-07-18 06:29:36', '2020-07-18 06:29:36'),
       (30, 10, 'XXL', 'dkp-2036633', 48900, 10, '2020-07-18 06:29:36', '2020-07-18 06:29:36'),
       (31, 11, 'L', 'dkp-1956258', 49000, 8, '2020-07-18 06:41:40', '2020-08-09 00:32:56'),
       (32, 11, 'XL', 'dkp-1956258', 50000, 10, '2020-07-18 06:41:41', '2020-07-18 06:41:41'),
       (33, 11, 'XXL', 'dkp-1956258', 52000, 10, '2020-07-18 06:41:41', '2020-07-18 06:41:41'),
       (34, 12, '41', 'dkp-2560578', 9900, 10, '2020-07-18 06:57:30', '2020-07-18 06:57:30'),
       (35, 12, '42', 'dkp-2560578', 110000, 9, '2020-07-18 06:57:30', '2020-08-09 12:21:31'),
       (36, 12, '43', 'dkp-2560578', 120000, 10, '2020-07-18 06:57:30', '2020-07-18 06:57:30'),
       (37, 13, '40', 'dkp-2220174', 34900, 10, '2020-07-18 07:03:06', '2020-07-18 07:03:06'),
       (38, 13, '41', 'dkp-2220174', 355000, 10, '2020-07-18 07:03:07', '2020-07-18 07:03:07'),
       (39, 13, '42', 'dkp-2220174', 360000, 10, '2020-07-18 07:03:07', '2020-07-18 07:03:07'),
       (40, 13, '43', 'dkp-2220174', 365000, 10, '2020-07-18 07:03:07', '2020-07-18 07:03:07'),
       (41, 14, 'White', 'dkp-211877', 10500000, 10, '2020-07-18 08:05:27', '2020-07-18 08:05:27'),
       (42, 15, 'Silver', 'dkp-3027936', 6900000, 10, '2020-07-18 08:11:13', '2020-07-18 08:11:13'),
       (43, 16, 'FloralWhite', 'dkp-942121', 19600000, 10, '2020-07-18 08:16:08', '2020-07-18 08:16:08'),
       (44, 4, 'Black', NULL, 2940000, 10, '2020-07-19 00:00:17', '2020-07-19 00:00:17'),
       (45, 4, 'Blue', NULL, 2895000, 10, '2020-07-19 00:00:17', '2020-07-19 00:00:17'),
       (46, 3, 'Black', NULL, 5900000, 9, '2020-07-19 00:40:16', '2020-07-19 00:40:16'),
       (47, 3, 'Blue', NULL, 5874000, 10, '2020-07-19 00:40:16', '2020-07-19 00:40:16'),
       (48, 3, 'GhostWhite', NULL, 5874000, 10, '2020-07-19 00:40:17', '2020-07-19 00:40:17'),
       (51, 2, 'DarkSeaGreen', NULL, 4899000, 9, '2020-07-19 00:54:33', '2020-07-20 10:11:37'),
       (52, 2, 'GhostWhite', NULL, 4899000, 10, '2020-07-19 00:54:34', '2020-07-19 00:54:34'),
       (53, 1, 'Black', NULL, 2990000, 9, '2020-07-19 00:59:50', '2020-07-19 00:59:50'),
       (54, 1, 'Blue', NULL, 2990000, 10, '2020-07-19 00:59:50', '2020-07-19 00:59:50'),
       (55, 1, 'DarkBlue', NULL, 2990000, 10, '2020-07-19 00:59:51', '2020-07-19 00:59:51'),
       (56, 1, 'Red', NULL, 2990000, 10, '2020-07-19 00:59:51', '2020-07-19 00:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews`
(
    `id`         int(11)                            NOT NULL AUTO_INCREMENT,
    `product_id` int(11)                            NOT NULL,
    `user_id`    int(11)                            NOT NULL,
    `rating`     int(11)                            NOT NULL DEFAULT 0,
    `comment`    mediumtext COLLATE utf8_unicode_ci NOT NULL,
    `status`     int(1)                             NOT NULL DEFAULT 1,
    `viewed`     int(1)                             NOT NULL DEFAULT 0,
    `created_at` timestamp                          NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                          NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles`
(
    `id`          int(11)                             NOT NULL AUTO_INCREMENT,
    `name`        varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `permissions` mediumtext COLLATE utf8_unicode_ci  NOT NULL,
    `created_at`  timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at`  timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`, `created_at`, `updated_at`)
VALUES (1, 'مدیر', '[\"1\",\"2\",\"4\"]', '2018-10-10 04:39:47', '2020-07-16 06:28:31'),
       (2, 'اپراتور سایت', '[\"2\",\"3\"]', '2018-10-10 04:52:09', '2020-07-16 06:28:55'),
       (3, 'اپراتور سفارشات حضوری', '[\"14\"]', '2020-07-20 11:41:42', '2020-07-20 11:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

DROP TABLE IF EXISTS `searches`;
CREATE TABLE IF NOT EXISTS `searches`
(
    `id`         int(11)                               NOT NULL AUTO_INCREMENT,
    `query`      varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
    `count`      int(11)                               NOT NULL DEFAULT 1,
    `created_at` timestamp                             NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                             NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `searches`
--

INSERT INTO `searches` (`id`, `query`, `count`, `created_at`, `updated_at`)
VALUES (2, 'dcs', 1, '2020-03-08 00:29:09', '2020-03-08 00:29:09'),
       (3, 'das', 3, '2020-03-08 00:29:15', '2020-03-08 00:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
CREATE TABLE IF NOT EXISTS `sellers`
(
    `id`                      int(11)       NOT NULL AUTO_INCREMENT,
    `user_id`                 int(11)       NOT NULL,
    `verification_status`     int(1)        NOT NULL               DEFAULT 0,
    `verification_info`       longtext COLLATE utf8_unicode_ci     DEFAULT NULL,
    `cash_on_delivery_status` int(1)        NOT NULL               DEFAULT 0,
    `admin_to_pay`            double(12, 0) NOT NULL               DEFAULT 0,
    `bank_name`               varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `bank_acc_name`           varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `bank_acc_no`             varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `bank_routing_no`         int(50)                              DEFAULT NULL,
    `bank_payment_status`     int(11)       NOT NULL               DEFAULT 0,
    `created_at`              timestamp     NOT NULL               DEFAULT current_timestamp(),
    `updated_at`              timestamp     NOT NULL               DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `user_id`, `verification_status`, `verification_info`, `cash_on_delivery_status`,
                       `admin_to_pay`, `bank_name`, `bank_acc_name`, `bank_acc_no`, `bank_routing_no`,
                       `bank_payment_status`, `created_at`, `updated_at`)
VALUES (1, 3, 1,
        '[{\"type\":\"text\",\"label\":\"Name\",\"value\":\"Mr. Seller\"},{\"type\":\"select\",\"label\":\"Marital Status\",\"value\":\"Married\"},{\"type\":\"multi_select\",\"label\":\"Company\",\"value\":\"[\\\"Company\\\"]\"},{\"type\":\"select\",\"label\":\"Gender\",\"value\":\"Male\"},{\"type\":\"file\",\"label\":\"Image\",\"value\":\"uploads\\/verification_form\\/CRWqFifcbKqibNzllBhEyUSkV6m1viknGXMEhtiW.png\"}]',
        1, 7800, NULL, NULL, NULL, NULL, 0, '2018-10-07 04:42:57', '2020-07-13 03:35:22'),
       (2, 13, 1,
        '[{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u0648 \\u0646\\u0627\\u0645 \\u062e\\u0627\\u0646\\u0648\\u0627\\u062f\\u06af\\u06cc\",\"value\":\"\\u0645\\u062a\\u06cc\\u0646 \\u062c\\u0648\\u0627\\u0646\"},{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u067e\\u062f\\u0631\",\"value\":\"\\u0645\\u062d\\u0645\\u062f\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u0634\\u0646\\u0627\\u0633\\u0646\\u0627\\u0645\\u0647\",\"value\":\"1234\"},{\"type\":\"text\",\"label\":\"\\u06a9\\u062f\\u0645\\u0644\\u06cc\",\"value\":\"0010010011\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u067e\\u0631\\u0648\\u0627\\u0646\\u0647 \\u06a9\\u0633\\u0628\",\"value\":\"1133889998\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u0647\\u0645\\u0631\\u0627\\u0647\",\"value\":\"0912998877\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u062b\\u0627\\u0628\\u062a\",\"value\":\"02122334455\"},{\"type\":\"text\",\"label\":\"\\u0622\\u062f\\u0631\\u0633\",\"value\":\"\\u062a\\u0647\\u0631\\u0627\\u0646\"}]',
        0, 0, NULL, NULL, NULL, NULL, 0, '2020-07-13 06:38:56', '2020-07-13 07:57:40'),
       (3, 14, 1,
        '[{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u0648 \\u0646\\u0627\\u0645 \\u062e\\u0627\\u0646\\u0648\\u0627\\u062f\\u06af\\u06cc\",\"value\":\"\\u0631\\u0636\\u0627 \\u0631\\u062d\\u0645\\u062a\\u06cc\"},{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u067e\\u062f\\u0631\",\"value\":\"\\u062d\\u0633\\u0646\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u0634\\u0646\\u0627\\u0633\\u0646\\u0627\\u0645\\u0647\",\"value\":\"0010010011\"},{\"type\":\"text\",\"label\":\"\\u06a9\\u062f\\u0645\\u0644\\u06cc\",\"value\":\"0010010011\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u067e\\u0631\\u0648\\u0627\\u0646\\u0647 \\u06a9\\u0633\\u0628\",\"value\":\"1002001002020\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u0647\\u0645\\u0631\\u0627\\u0647\",\"value\":\"09125566778\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u062b\\u0627\\u0628\\u062a\",\"value\":\"02166775588\"},{\"type\":\"text\",\"label\":\"\\u0622\\u062f\\u0631\\u0633\",\"value\":\"\\u062a\\u0647\\u0631\\u0627\\u0646\"}]',
        0, 73340, NULL, NULL, NULL, NULL, 0, '2020-07-18 00:01:11', '2020-08-09 08:14:09'),
       (4, 15, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2020-07-18 07:39:46', '2020-07-18 07:39:59'),
       (5, 16, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2020-07-19 01:48:09', '2020-07-19 01:48:17'),
       (6, 17, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2020-07-19 02:21:12', '2020-07-19 02:22:57'),
       (7, 19, 1, NULL, 0, 400, NULL, NULL, NULL, NULL, 0, '2020-08-09 12:35:08', '2020-08-10 00:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `seller_withdraw_requests`
--

DROP TABLE IF EXISTS `seller_withdraw_requests`;
CREATE TABLE IF NOT EXISTS `seller_withdraw_requests`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)            DEFAULT NULL,
    `amount`     double(8, 2)       DEFAULT NULL,
    `message`    longtext           DEFAULT NULL,
    `status`     int(1)             DEFAULT NULL,
    `viewed`     int(1)             DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = latin1;

--
-- Dumping data for table `seller_withdraw_requests`
--

INSERT INTO `seller_withdraw_requests` (`id`, `user_id`, `amount`, `message`, `status`, `viewed`, `created_at`,
                                        `updated_at`)
VALUES (4, 1, 500000.00, NULL, 1, 1, '2020-07-13 03:27:50', '2020-07-13 03:35:22'),
       (5, 3, 56000.00, NULL, 1, 1, '2020-07-24 11:03:03', '2020-07-25 01:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE IF NOT EXISTS `seo_settings`
(
    `id`           int(11)                              NOT NULL AUTO_INCREMENT,
    `keyword`      varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `author`       varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `revisit`      int(11)                              NOT NULL,
    `sitemap_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `description`  longtext COLLATE utf8_unicode_ci     NOT NULL,
    `created_at`   timestamp                            NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at`   timestamp                            NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `keyword`, `author`, `revisit`, `sitemap_link`, `description`, `created_at`,
                            `updated_at`)
VALUES (1, 'فروشگاه,فروشگاه اینترنتی,لاراول,سایت فروشگاهی', 'علی', 11, 'example.com', 'فروشگاه اینترنتی اکتیو',
        '2020-08-03 06:23:30', '2020-08-03 01:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
CREATE TABLE IF NOT EXISTS `shops`
(
    `id`               int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`          int(11)      NOT NULL,
    `name`             varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
    `logo`             varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `sliders`          longtext COLLATE utf8_unicode_ci     DEFAULT NULL,
    `address`          varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
    `facebook`         varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `google`           varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `twitter`          varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `youtube`          varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `slug`             varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `meta_title`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `meta_description` text COLLATE utf8_unicode_ci         DEFAULT NULL,
    `pick_up_point_id` text COLLATE utf8_unicode_ci         DEFAULT NULL,
    `shipping_cost`    double(8, 2) NOT NULL                DEFAULT 0.00,
    `created_at`       timestamp    NOT NULL                DEFAULT current_timestamp(),
    `updated_at`       timestamp    NULL                    DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `user_id`, `name`, `logo`, `sliders`, `address`, `facebook`, `google`, `twitter`, `youtube`,
                     `slug`, `meta_title`, `meta_description`, `pick_up_point_id`, `shipping_cost`, `created_at`,
                     `updated_at`)
VALUES (1, 3, 'آریا سیستم', 'uploads/shop/logo/th8yKNuqJ3Lkv29veKS6UdTNpmDsOFoeuF1LfLJD.png',
        '[\"uploads\\/shop\\/sliders\\/1fO3T8tckQQfX9D6FtIyMeiebYbKr8S3us1DmfBl.jpeg\",\"uploads\\/shop\\/sliders\\/E9n3Hg9kS3fy1zrYUx9frwk5nr7sGsJJHN2epeph.jpeg\"]',
        'تهران، میدان ولیعصر، پاساژ رضا، پلاک 1298', 'www.facebook.com', 'www.google.com', 'www.twitter.com',
        'www.youtube.com', 'آریا-سیستم-1', 'آریا سیستم', 'فروشگاه لوازم جانبی کامپیوتر آریا سیستم', '[]', 0.00,
        '2018-11-27 10:23:13', '2020-07-17 04:16:38'),
       (2, 13, 'آرشام رایانه', 'uploads/shop/logo/zVRCuPuZTvKXkPv3g8JLaGejd9J1bzBpqyYfGMzB.png',
        '[\"uploads\\/shop\\/sliders\\/UKDqjKtB4VIdWjpzXBq3i7M8D9rkd72lVDRt2el7.jpeg\",\"uploads\\/shop\\/sliders\\/uPXOrTgkSXAvH3RZojMsEON0TlG34UqDz0MGAh9j.jpeg\"]',
        'تهران، میدان ولیعصر، پاساژ رضا، پلاک 12', NULL, NULL, NULL, NULL, 'آرشام-رایانه-2', 'آرشام رایانه',
        'آرشام رایانه', '[\"1\"]', 0.00, '2020-07-13 06:38:57', '2020-08-10 01:49:33'),
       (3, 14, 'تاپ کالا', 'uploads/shop/logo/CvipIUuIZDAso5d39H9uCFuZ36fZYR1v8xNFQeZB.png',
        '[\"uploads\\/shop\\/sliders\\/OMIf8ikvMcyid5tQ9GkiK5vgIwZkICSK88ZQCt40.jpeg\",\"uploads\\/shop\\/sliders\\/paxPfweCBcmtCSMfNY4yJ7dZxRTSAKY33dVCck0A.jpeg\"]',
        'تهران، میدان منیریه، پلاک 1', NULL, NULL, NULL, NULL, 'تاپ-کالا-3', 'تاپ کالا', 'تاپ کالا', '[\"1\"]', 0.00,
        '2020-07-18 00:01:12', '2020-08-10 01:48:58'),
       (4, 15, 'پخش بانه', 'uploads/shop/logo/Tr47b9a4ZbePrymejo7scpQOm7wlGTOLULlbMNpg.png',
        '[\"uploads\\/shop\\/sliders\\/x4lGb74O6tClA3U3tlZwpP5aalBydIUSx1msr3Jk.jpeg\"]',
        'کردستان، بانه، مرکز تجاری گلدیس', NULL, NULL, NULL, NULL, 'پخش-بانه-4', 'پخش بانه', 'پخش بانه', '[\"1\"]',
        0.00, '2020-07-18 07:39:47', '2020-08-10 01:49:59'),
       (5, 16, 'داروکده', 'uploads/shop/logo/mMQEcxd1mRfPGGrc3sRRwfaaQJAoKrJg3z6TIBmx.png',
        '[\"uploads\\/shop\\/sliders\\/KLUWCQSbYnYQoyPLEZuOMcSsUaRXtZZ5qHZaJ8Gm.jpeg\"]',
        'تهران، سیدخندان خیابان کابلی پلاک 1', NULL, NULL, NULL, NULL, 'داروکده-5', 'داروکده', 'داروکده', '[\"1\"]',
        0.00, '2020-07-19 01:48:10', '2020-08-10 01:50:22'),
       (6, 17, 'سازینو', 'uploads/shop/logo/itU1VvL2XiKdK7CxJjHsE6TWs6R20jiAHEamnTkH.png',
        '[\"uploads\\/shop\\/sliders\\/8JjrgXObzNilQqDcU6CLF8yl8Yx5txZwjjywG0Jx.jpeg\"]',
        'تهران، بلوار اندرزگو، پلاک 1', NULL, NULL, NULL, NULL, 'سازینو-6', 'سازینو', 'سازینو', '[\"1\"]', 0.00,
        '2020-07-19 02:21:12', '2020-08-10 01:50:52'),
       (7, 19, 'سوپر مارکت دریانی', NULL, NULL, 'تهران، بلوار اندرزگو، پلاک 1', NULL, NULL, NULL, NULL,
        'سوپر-مارکت-دریانی-7', 'خوردنی و آشامیدنی', 'خوردنی و آشامیدنی', '[\"1\"]', 0.00, '2020-08-09 12:35:08',
        '2020-08-09 23:56:02');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `photo`      varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `published`  int(1)    NOT NULL                   DEFAULT 1,
    `link`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `created_at` timestamp NOT NULL                   DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL                   DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 16
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `photo`, `published`, `link`, `created_at`, `updated_at`)
VALUES (13, 'uploads/sliders/s0tEnSllV2REmAJTgwfULZTuHLBdXmxIvwXKhqvm.jpeg', 1, '#', '2020-07-16 10:01:36',
        '2020-07-16 10:01:36'),
       (14, 'uploads/sliders/gh6CD7oYyM7utU1Pa97uSMVNkRBB969yQx66rCOB.jpeg', 1, '#', '2020-07-16 10:05:02',
        '2020-07-16 10:05:02'),
       (15, 'uploads/sliders/0PqMpW5kr2iV0vjZuQwtCr0vq9f8BKNkIa3wdId9.jpeg', 1, '#', '2020-07-16 14:22:12',
        '2020-07-16 14:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)   NOT NULL,
    `role_id`    int(11)   NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`)
VALUES (1, 18, 3, '2020-07-20 11:42:24', '2020-07-20 11:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE IF NOT EXISTS `subscribers`
(
    `id`         int(11)                             NOT NULL AUTO_INCREMENT,
    `email`      varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp                           NULL     DEFAULT current_timestamp(),
    `updated_at` timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE IF NOT EXISTS `sub_categories`
(
    `id`               int(11)                             NOT NULL AUTO_INCREMENT,
    `name`             varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `category_id`      int(11)                             NOT NULL,
    `slug`             varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_title`       varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_description` text COLLATE utf8_unicode_ci                 DEFAULT NULL,
    `created_at`       timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at`       timestamp                           NULL     DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `fk_category_id` (`category_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 44
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `category_id`, `slug`, `meta_title`, `meta_description`, `created_at`,
                              `updated_at`)
VALUES (10, 'گوشی موبایل', 4, '--zvSo6', 'گوشی موبایل', NULL, '2020-07-04 10:38:39', '2020-07-04 10:38:39'),
       (11, 'لوازم جانبی گوشی', 4, '---jA7nh', 'لوازم جانبی گوشی', NULL, '2020-07-04 10:39:59', '2020-07-04 10:39:59'),
       (12, 'هدفون، هدست، هندزفری', 4, '---qmHDa', 'هدفون، هدست، هندزفری', NULL, '2020-07-04 10:41:32',
        '2020-07-04 10:41:32'),
       (13, 'دوربین', 4, '-hwRMR', 'دوربین', NULL, '2020-07-04 23:17:00', '2020-07-04 23:17:00'),
       (14, 'کامپیوتر و تجهیزات جانبی', 4, '----aJ6Jo', 'کامپیوتر و تجهیزات جانبی', NULL, '2020-07-04 23:24:32',
        '2020-07-04 23:24:32'),
       (15, 'ماشین های اداری', 4, '---cPw2S', 'ماشین های اداری', NULL, '2020-07-04 23:24:58', '2020-07-04 23:24:58'),
       (16, 'لوازم آرایشی', 5, '--uw8YU', 'لوازم آرایشی', NULL, '2020-07-05 02:00:51', '2020-07-05 02:00:51'),
       (17, 'لوازم بهداشتی', 5, '--KJLZN', 'لوازم بهداشتی', NULL, '2020-07-05 02:01:09', '2020-07-05 02:01:24'),
       (18, 'لوازم شخصی برقی', 5, '---gX1D7', 'لوازم شخصی برقی', NULL, '2020-07-05 02:01:47', '2020-07-05 02:02:11'),
       (19, 'عطر، ادکلن، اسپری', 5, '---7FPXx', 'عطر، ادکلن، اسپری', NULL, '2020-07-05 02:02:40',
        '2020-07-05 02:02:40'),
       (20, 'طلا، نقره، زیورآلات رنانه', 5, '----J0byg', 'طلا، نقره، زیورآلات رنانه', NULL, '2020-07-05 02:03:18',
        '2020-07-05 02:03:18'),
       (21, 'ابزار سلامت و طبی', 5, '----Hcvya', 'ابزار سلامت و طبی', NULL, '2020-07-05 02:03:51',
        '2020-07-05 02:03:51'),
       (22, 'لوازم جانبی خودرو', 6, '---OLkiZ', 'لوازم جانبی خودرو', NULL, '2020-07-05 02:05:23',
        '2020-07-05 02:05:23'),
       (23, 'لوازم یدکی خودرو', 6, '---U0NJj', 'لوازم یدکی خودرو', NULL, '2020-07-05 02:05:50', '2020-07-05 02:05:50'),
       (24, 'ابزار برقی', 6, '--jBEq3', 'ابزار برقی', NULL, '2020-07-05 02:06:10', '2020-07-05 02:06:10'),
       (25, 'ابزار غیر برقی', 6, '---evN0J', 'ابزار غیر برقی', NULL, '2020-07-05 02:06:35', '2020-07-05 02:06:35'),
       (26, 'لوازم ساختمانی', 6, '--hyIRZ', 'لوازم ساختمانی', NULL, '2020-07-05 02:06:59', '2020-07-05 02:06:59'),
       (27, 'نور و روشنایی', 6, '---yU7ZK', 'نور و روشنایی', NULL, '2020-07-05 02:07:22', '2020-07-05 02:07:22'),
       (28, 'لباس مردانه', 7, '--Vyv2o', 'لباس مردانه', NULL, '2020-07-05 03:52:07', '2020-07-05 03:52:07'),
       (29, 'کفش مردانه', 7, '--QDUAH', 'کفش مردانه', NULL, '2020-07-05 03:52:48', '2020-07-05 03:52:48'),
       (30, 'اکسسوری مردانه', 7, '--qUeuB', 'اکسسوری مردانه', NULL, '2020-07-05 03:53:09', '2020-07-05 03:53:09'),
       (31, 'لباس زنانه', 7, '--kPOi1', 'لباس زنانه', NULL, '2020-07-05 03:53:31', '2020-07-05 03:53:31'),
       (32, 'کفش زنانه', 7, '--uHfms', 'کفش زنانه', NULL, '2020-07-05 03:53:49', '2020-07-05 03:53:49'),
       (33, 'اکسسوری زنانه', 7, '--wrmOn', 'اکسسوری زنانه', NULL, '2020-07-05 03:54:11', '2020-07-05 03:54:11'),
       (34, 'صوتی و تصویری', 8, '---PQNjn', 'صوتی و تصویری', NULL, '2020-07-05 03:55:27', '2020-07-05 03:55:27'),
       (35, 'دکوراتیو', 8, '-aedo5', 'دکوراتیو', NULL, '2020-07-05 03:55:53', '2020-07-05 03:55:53'),
       (36, 'فرش ماشینی، دستباف', 8, '---2DrNK', 'فرش ماشینی، دستباف', NULL, '2020-07-05 03:56:26',
        '2020-07-05 03:56:26'),
       (37, 'لوازم برقی خانگی', 8, '---i1yHV', 'لوازم برقی خانگی', NULL, '2020-07-05 03:56:49', '2020-07-05 03:56:49'),
       (38, 'سرو و پذیرایی', 8, '---2HZ5U', 'سرو و پذیرایی', NULL, '2020-07-05 03:57:14', '2020-07-05 03:57:14'),
       (39, 'آشپزخانه', 8, '-zVnuw', 'آشپزخانه', NULL, '2020-07-05 03:57:31', '2020-07-05 03:57:31'),
       (40, 'لوازم التحریر', 9, '--zkYvQ', 'لوازم التحریر', NULL, '2020-07-05 03:58:18', '2020-07-05 03:58:18'),
       (41, 'آلات موسیقی', 9, '--3uqsz', 'آلات موسیقی', NULL, '2020-07-05 03:58:43', '2020-07-05 03:58:43'),
       (42, 'صنایع دستی', 9, '--DyQaA', 'صنایع دستی', NULL, '2020-07-05 03:59:03', '2020-07-05 03:59:03'),
       (43, 'تنقلات', 12, '-WoGPP', 'تنقلات', NULL, '2020-08-09 12:47:49', '2020-08-09 12:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_categories`
--

DROP TABLE IF EXISTS `sub_sub_categories`;
CREATE TABLE IF NOT EXISTS `sub_sub_categories`
(
    `id`               int(11)                             NOT NULL AUTO_INCREMENT,
    `sub_category_id`  int(11)                             NOT NULL,
    `name`             varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `slug`             varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_title`       varchar(255) COLLATE utf8_unicode_ci         DEFAULT NULL,
    `meta_description` text COLLATE utf8_unicode_ci                 DEFAULT NULL,
    `created_at`       timestamp                           NOT NULL DEFAULT current_timestamp(),
    `updated_at`       timestamp                           NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `fk_sub_category_id` (`sub_category_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 118
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `sub_sub_categories`
--

INSERT INTO `sub_sub_categories` (`id`, `sub_category_id`, `name`, `slug`, `meta_title`, `meta_description`,
                                  `created_at`, `updated_at`)
VALUES (18, 10, 'سامسونگ', '-0Moq0', 'سامسونگ', NULL, '2020-07-04 10:51:09', '2020-07-04 10:51:09'),
       (19, 10, 'هوآوی', '-AECoS', 'هوآوی', NULL, '2020-07-04 10:56:04', '2020-07-04 10:56:04'),
       (20, 10, 'شیائومی', '-Oqip5', 'شیائومی', NULL, '2020-07-04 23:11:19', '2020-07-04 23:11:19'),
       (23, 11, 'کیف و کاور گوشی', '----RcFeg', 'کیف و کاور گوشی', NULL, '2020-07-04 23:13:08', '2020-07-04 23:13:08'),
       (24, 11, 'پاور بانک', '--lv5rM', 'پاور بانک', NULL, '2020-07-04 23:13:47', '2020-07-04 23:13:47'),
       (25, 11, 'پایه نگهدارنده گوشی', '---rFMxG', 'پایه نگهدارنده گوشی', NULL, '2020-07-04 23:14:27',
        '2020-07-04 23:14:27'),
       (26, 12, 'هدفون', '-NIR0H', 'هدفون', NULL, '2020-07-04 23:15:54', '2020-07-04 23:15:54'),
       (27, 12, 'هدست', '-zKXQY', 'هدست', NULL, '2020-07-04 23:16:13', '2020-07-04 23:16:13'),
       (28, 12, 'هندزفری', '-2UCNc', 'هندزفری', NULL, '2020-07-04 23:16:38', '2020-07-04 23:16:38'),
       (29, 13, 'دوربین عکاسی دیجیتال', '---NEtz9', 'دوربین عکاسی دیجیتال', NULL, '2020-07-04 23:26:34',
        '2020-07-04 23:26:34'),
       (30, 13, 'دوربین ورزشی و فیلم برداری', '-----aCqAD', 'دوربین ورزشی و فیلم برداری', NULL, '2020-07-04 23:27:11',
        '2020-07-04 23:27:11'),
       (31, 13, 'دوربین چاپ سریع', '---iwg8a', 'دوربین چاپ سریع', NULL, '2020-07-04 23:27:42', '2020-07-04 23:27:42'),
       (32, 14, 'مانیتور', '-NVjnd', 'مانیتور', NULL, '2020-07-04 23:28:13', '2020-07-04 23:28:13'),
       (33, 14, 'موس', '-1v5M9', 'موس', NULL, '2020-07-04 23:28:30', '2020-07-04 23:28:30'),
       (34, 14, 'کیبورد', '-m0MZA', 'کیبورد', NULL, '2020-07-04 23:28:52', '2020-07-04 23:28:52'),
       (35, 15, 'تلفن', '-Kq3fc', 'تلفن', NULL, '2020-07-04 23:30:34', '2020-07-04 23:30:34'),
       (36, 15, 'فکس', '-AN5S9', 'فکس', NULL, '2020-07-04 23:30:53', '2020-07-04 23:31:10'),
       (37, 15, 'پرینتر', '-BZ9zF', 'پرینتر', NULL, '2020-07-04 23:31:34', '2020-07-04 23:31:34'),
       (38, 16, 'آرایش چشم و ابرو', '----B5lB4', 'آرایش چشم و ابرو', NULL, '2020-07-05 04:02:01',
        '2020-07-05 04:02:01'),
       (39, 16, 'آرایش لب', '--dT43N', 'آرایش لب', NULL, '2020-07-05 04:02:41', '2020-07-05 04:02:41'),
       (40, 16, 'آرایش صورت', '--QCtXV', 'آرایش صورت', NULL, '2020-07-05 04:03:14', '2020-07-05 04:03:14'),
       (41, 17, 'کرم و مراقبت پوست', '----J9wQB', 'کرم و مراقبت پوست', NULL, '2020-07-05 04:04:14',
        '2020-07-05 04:04:14'),
       (42, 17, 'شامپو و مراقبت مو', '----Jozp6', 'شامپو و مراقبت مو', NULL, '2020-07-05 04:08:33',
        '2020-07-05 04:08:33'),
       (43, 17, 'بهداشت دهان و دندان', '----NDayG', 'بهداشت دهان و دندان', NULL, '2020-07-05 04:09:05',
        '2020-07-05 04:09:05'),
       (44, 18, 'ماشین اصلاح صورت', '---iQIFA', 'ماشین اصلاح صورت', NULL, '2020-07-05 04:09:42', '2020-07-05 04:09:42'),
       (45, 18, 'ماشین اصلاح سر', '---ILmqP', 'ماشین اصلاح سر', NULL, '2020-07-05 04:10:20', '2020-07-05 04:10:20'),
       (46, 18, 'سشوار', '-8IxPr', 'سشوار', NULL, '2020-07-05 04:10:45', '2020-07-05 04:10:45'),
       (47, 19, 'مردانه', '-k5Ner', 'مردانه', NULL, '2020-07-05 04:11:08', '2020-07-05 04:11:08'),
       (48, 19, 'زنانه', '-1Rich', 'زنانه', NULL, '2020-07-05 04:11:31', '2020-07-05 04:11:31'),
       (49, 19, 'اسپری', '-LuSD5', 'اسپری', NULL, '2020-07-05 04:11:59', '2020-07-05 04:11:59'),
       (50, 20, 'زیورآلات طلا', '--TApDO', 'زیورآلات طلا', NULL, '2020-07-05 04:13:07', '2020-07-05 04:13:07'),
       (51, 20, 'زیورآلات نقره', '--w3J4b', 'زیورآلات نقره', NULL, '2020-07-05 04:13:34', '2020-07-05 04:13:34'),
       (52, 20, 'حلقه و انگشتر', '---sLjtx', 'حلقه و انگشتر', NULL, '2020-07-05 04:14:25', '2020-07-05 04:14:25'),
       (53, 21, 'تست قند خون', '---97x9H', 'تست قند خون', NULL, '2020-07-05 04:15:09', '2020-07-05 04:15:09'),
       (54, 21, 'تب سنج', '--mIzY4', 'تب سنج', NULL, '2020-07-05 04:15:32', '2020-07-05 04:15:32'),
       (55, 21, 'فشارسنج', '-bIOTb', 'فشارسنج', NULL, '2020-07-05 04:16:07', '2020-07-05 04:16:07'),
       (56, 22, 'لوازم تزیینی', '--MR9Wb', 'لوازم تزیینی', NULL, '2020-07-05 04:22:29', '2020-07-05 04:22:29'),
       (57, 22, 'سیستم صوتی و تصویری', '----cYzmb', 'سیستم صوتی و تصویری', NULL, '2020-07-05 04:22:51',
        '2020-07-05 04:22:51'),
       (58, 22, 'نظافت و نگهداری خودرو', '----DbINK', 'نظافت و نگهداری خودرو', NULL, '2020-07-05 04:23:28',
        '2020-07-05 04:23:28'),
       (59, 23, 'دیسک و صفحه کلاچ', '----QoGDQ', 'دیسک و صفحه کلاچ', NULL, '2020-07-05 04:24:11',
        '2020-07-05 04:24:11'),
       (60, 23, 'جلوبندی و تعلیق', '---dXBOV', 'جلوبندی و تعلیق', NULL, '2020-07-05 04:24:49', '2020-07-05 04:24:49'),
       (61, 23, 'چراغ خودرو', '--3xTEj', 'چراغ خودرو', NULL, '2020-07-05 04:25:15', '2020-07-05 04:25:36'),
       (62, 24, 'دریل، پیچ گوشتی برقی', '----OPTXr', 'دریل، پیچ گوشتی برقی', NULL, '2020-07-05 04:27:09',
        '2020-07-05 04:27:09'),
       (63, 24, 'فرز و سنگ رومیزی', '----HKc2Y', 'فرز و سنگ رومیزی', NULL, '2020-07-05 04:27:53',
        '2020-07-05 04:27:53'),
       (64, 24, 'موتور برق', '--KUTJ1', 'موتور برق', NULL, '2020-07-05 04:28:26', '2020-07-05 04:28:26'),
       (65, 25, 'ابزار دستی', '--7sF5m', 'ابزار دستی', NULL, '2020-07-05 04:29:06', '2020-07-05 04:29:06'),
       (66, 25, 'مجموعه ابزار', '--YZvFX', 'مجموعه ابزار', NULL, '2020-07-05 04:29:55', '2020-07-05 04:30:14'),
       (67, 25, 'نردبان', '-iDXQ8', 'نردبان', NULL, '2020-07-05 04:30:37', '2020-07-05 04:30:37'),
       (68, 26, 'شیرآلات', '-5nM4Z', 'شیرآلات', NULL, '2020-07-05 04:31:21', '2020-07-05 04:31:21'),
       (69, 26, 'رنگ', '-hUEfx', 'رنگ', NULL, '2020-07-05 04:31:58', '2020-07-05 04:31:58'),
       (70, 26, 'دستگیره در', '--nkJax', 'دستگیره در', NULL, '2020-07-05 04:32:43', '2020-07-05 04:32:43'),
       (71, 27, 'لوستر و آباژور', '---tXQpN', 'لوستر و آباژور', NULL, '2020-07-05 04:33:25', '2020-07-05 04:33:25'),
       (72, 27, 'لامپ', '-EUUGa', 'لامپ', NULL, '2020-07-05 04:33:44', '2020-07-05 04:33:44'),
       (73, 27, 'چندراهی برق و محافظ ولتاژ', '-----kVzzY', 'چندراهی برق و محافظ ولتاژ', NULL, '2020-07-05 04:34:40',
        '2020-07-05 04:34:40'),
       (74, 28, 'تی شرت و پولو شرت', '--ShtUJ', 'تی شرت و پولو شرت', NULL, '2020-07-05 04:40:49',
        '2020-07-05 06:41:47'),
       (75, 28, 'پیراهن', '-hB7SI', 'پیراهن', NULL, '2020-07-05 06:42:08', '2020-07-05 06:42:08'),
       (76, 28, 'شلوار', '-PyvET', 'شلوار', NULL, '2020-07-05 06:42:26', '2020-07-05 06:42:26'),
       (77, 29, 'کفش روزمره', '--JyRl0', 'کفش روزمره', NULL, '2020-07-05 06:43:04', '2020-07-05 06:43:04'),
       (78, 29, 'کفش رسمی', '--AXmpf', 'کفش رسمی', NULL, '2020-07-05 06:43:24', '2020-07-05 06:43:24'),
       (79, 30, 'ساعت', '-knXPn', 'ساعت', NULL, '2020-07-05 06:43:51', '2020-07-05 06:43:51'),
       (80, 30, 'کیف', '-nvm3T', 'کیف', NULL, '2020-07-05 06:44:19', '2020-07-05 06:44:19'),
       (81, 30, 'کمربند', '-PyvMs', 'کمربند', NULL, '2020-07-05 06:44:38', '2020-07-05 06:44:38'),
       (82, 31, 'تی شرت و پولوشرت', '----tCmAg', 'تی شرت و پولوشرت', NULL, '2020-07-05 06:45:13',
        '2020-07-05 06:45:13'),
       (83, 31, 'مانتو', '-Fu1c9', 'مانتو', NULL, '2020-07-05 06:45:36', '2020-07-05 06:45:36'),
       (84, 31, 'شلوار و سرهمی', '---GolyB', 'شلوار و سرهمی', NULL, '2020-07-05 06:46:10', '2020-07-05 06:46:10'),
       (85, 32, 'کفش روزمره', '--ocPZs', 'کفش روزمره', NULL, '2020-07-05 06:46:42', '2020-07-05 06:46:42'),
       (86, 32, 'کفش تخت', '--jk1QC', 'کفش تخت', NULL, '2020-07-05 06:47:02', '2020-07-05 06:47:02'),
       (87, 33, 'ساعت', '-WoFgw', 'ساعت', NULL, '2020-07-05 06:47:19', '2020-07-05 06:47:19'),
       (88, 33, 'کیف', '-haEsv', 'کیف', NULL, '2020-07-05 06:47:35', '2020-07-05 06:47:35'),
       (89, 33, 'شال و روسری', '---SoA5l', 'شال و روسری', NULL, '2020-07-05 06:47:51', '2020-07-05 06:47:51'),
       (90, 34, 'تلویزیون', '-Eipct', 'تلویزیون', NULL, '2020-07-05 06:50:49', '2020-07-05 06:50:49'),
       (91, 34, 'سینمای خانگی و ساندبار', '----cR9rp', 'سینمای خانگی و ساندبار', NULL, '2020-07-05 06:53:34',
        '2020-07-05 06:53:34'),
       (92, 34, 'گیرنده دیجیتال و تلویزیون', '----YTfCC', 'گیرنده دیجیتال و تلویزیون', NULL, '2020-07-05 06:54:47',
        '2020-07-05 06:54:47'),
       (93, 35, 'مبلمان خانگی', '--xzbnn', 'مبلمان خانگی', NULL, '2020-07-05 06:55:36', '2020-07-05 06:55:36'),
       (94, 35, 'دکوراسیون اداری', '--31rMD', 'دکوراسیون اداری', NULL, '2020-07-05 06:56:12', '2020-07-05 06:56:12'),
       (95, 35, 'تابلو', '-gbQcL', 'تابلو', NULL, '2020-07-05 06:56:49', '2020-07-05 06:56:49'),
       (96, 36, 'فرش ماشینی', '--5N1bB', 'فرش ماشینی', NULL, '2020-07-05 06:57:34', '2020-07-05 06:57:34'),
       (97, 36, 'فرش دستباف', '--Sndxd', 'فرش دستباف', NULL, '2020-07-05 06:58:01', '2020-07-05 06:58:01'),
       (98, 36, 'قالیچه', '-7YMzO', 'قالیچه', NULL, '2020-07-05 06:58:30', '2020-07-05 06:58:30'),
       (99, 37, 'یخچال و فریزر', '---n58lB', 'یخچال و فریزر', NULL, '2020-07-05 06:59:14', '2020-07-05 06:59:14'),
       (100, 37, 'ماشین لباسشویی', '--3lMZm', 'ماشین لباسشویی', NULL, '2020-07-05 06:59:45', '2020-07-05 06:59:45'),
       (101, 37, 'جارو برقی', '--9K7Dl', 'جارو برقی', NULL, '2020-07-05 07:00:22', '2020-07-05 07:00:22'),
       (102, 38, 'سرویس غذاخوری', '--WgwCl', 'سرویس غذاخوری', NULL, '2020-07-05 07:01:01', '2020-07-05 07:01:01'),
       (103, 38, 'قاشق، چنگال و کارد', '----mIVP6', 'قاشق، چنگال و کارد', NULL, '2020-07-05 07:01:46',
        '2020-07-05 07:01:46'),
       (104, 38, 'پارچ، بطری، لیوان', '---o3Gwr', 'پارچ، بطری، لیوان', NULL, '2020-07-05 07:02:35',
        '2020-07-05 07:02:35'),
       (105, 39, 'سرویس و ظروف پخت و پز', '------QP67D', 'سرویس و ظروف پخت و پز', NULL, '2020-07-05 07:03:21',
        '2020-07-05 07:03:21'),
       (106, 39, 'فلاسک و کلمن', '---yfFoa', 'فلاسک و کلمن', NULL, '2020-07-05 07:03:51', '2020-07-05 07:03:51'),
       (107, 39, 'کتری، قوری', '--CdxIe', 'کتری، قوری', NULL, '2020-07-05 07:04:30', '2020-07-05 07:04:30'),
       (108, 40, 'نوشت افزار', '--YroVZ', 'نوشت افزار', NULL, '2020-07-05 07:05:11', '2020-07-05 07:05:11'),
       (109, 40, 'دفتر و کاغذ', '---s8ysB', 'دفتر و کاغذ', NULL, '2020-07-05 07:05:40', '2020-07-05 07:05:40'),
       (110, 40, 'کیف، کوله پشتی و جامدادی', '-----7HZQw', 'کیف، کوله پشتی و جامدادی', NULL, '2020-07-05 07:06:10',
        '2020-07-05 07:06:10'),
       (111, 41, 'گیتار', '-UPava', 'گیتار', NULL, '2020-07-05 07:06:48', '2020-07-05 07:06:48'),
       (112, 41, 'سازهای ایرانی', '--7pXYZ', 'سازهای ایرانی', NULL, '2020-07-05 07:07:19', '2020-07-05 07:07:19'),
       (113, 41, 'پیانو و کیبورد و ارگ', '-----BJa1U', 'پیانو و کیبورد و ارگ', NULL, '2020-07-05 07:07:48',
        '2020-07-05 07:07:48'),
       (114, 42, 'کالاهای مسی', '--nirK1', 'کالاهای مسی', NULL, '2020-07-05 07:08:21', '2020-07-05 07:08:21'),
       (115, 42, 'ترمه و قلمکار و دستباف', '-----pChiV', 'ترمه و قلمکار و دستباف', NULL, '2020-07-05 07:09:00',
        '2020-07-05 07:09:00'),
       (116, 42, 'خاتم، منبت، چوبی و حصیری', '-----wpL5R', 'خاتم، منبت، چوبی و حصیری', NULL, '2020-07-05 07:09:39',
        '2020-07-05 07:09:39'),
       (117, 43, 'کیک و کلوچه', '---cGYKl', 'کیک و کلوچه', NULL, '2020-08-09 12:48:18', '2020-08-09 12:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets`
(
    `id`            int(11)                              NOT NULL AUTO_INCREMENT,
    `code`          int(6)                               NOT NULL,
    `user_id`       int(11)                              NOT NULL,
    `subject`       varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `details`       longtext COLLATE utf8_unicode_ci                   DEFAULT NULL,
    `files`         longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    `status`        varchar(10) COLLATE utf8_unicode_ci  NOT NULL      DEFAULT 'pending',
    `viewed`        int(1)                               NOT NULL      DEFAULT 0,
    `client_viewed` int(1)                               NOT NULL      DEFAULT 0,
    `created_at`    timestamp                            NOT NULL      DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at`    timestamp                            NOT NULL      DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 9
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `code`, `user_id`, `subject`, `details`, `files`, `status`, `viewed`, `client_viewed`,
                       `created_at`, `updated_at`)
VALUES (8, 10000054, 8, 'درخواست بازگشت محصول',
        '<p>با سلام</p><p>تقاضا دارم محصول خریداری شده را پس بگیرید</p><p>با تشکر<br></p>', NULL, 'open', 1, 1,
        '2020-07-20 14:56:14', '2020-07-20 10:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

DROP TABLE IF EXISTS `ticket_replies`;
CREATE TABLE IF NOT EXISTS `ticket_replies`
(
    `id`         int(11)                          NOT NULL AUTO_INCREMENT,
    `ticket_id`  int(11)                          NOT NULL,
    `user_id`    int(11)                          NOT NULL,
    `reply`      longtext COLLATE utf8_unicode_ci NOT NULL,
    `files`      longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    `created_at` timestamp                        NOT NULL          DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_at` timestamp                        NOT NULL          DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 9
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `user_id`, `reply`, `files`, `created_at`, `updated_at`)
VALUES (8, 8, 12, '<p>با سلام و احترام</p><p>این مورد در دست پیگیری است.</p><p><br></p>', NULL, '2020-07-20 09:27:52',
        '2020-07-20 09:27:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`
(
    `id`                  int(10) UNSIGNED                     NOT NULL AUTO_INCREMENT,
    `referred_by`         int(11)                                       DEFAULT NULL,
    `provider_id`         varchar(50) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `user_type`           varchar(10) COLLATE utf8_unicode_ci  NOT NULL DEFAULT 'customer',
    `name`                varchar(191) COLLATE utf8_unicode_ci NOT NULL,
    `email`               varchar(191) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `email_verified_at`   timestamp                            NULL     DEFAULT NULL,
    `password`            varchar(191) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `remember_token`      varchar(100) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `avatar`              varchar(256) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `avatar_original`     varchar(256) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `address`             varchar(300) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `country`             varchar(30) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `city`                varchar(30) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `postal_code`         varchar(20) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `phone`               varchar(20) COLLATE utf8_unicode_ci           DEFAULT NULL,
    `phone_verified_at`   timestamp                            NULL     DEFAULT NULL,
    `verification_code`   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `balance`             double(8, 2)                         NOT NULL DEFAULT 0.00,
    `referral_code`       varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
    `customer_package_id` int(11)                                       DEFAULT NULL,
    `remaining_uploads`   int(11)                                       DEFAULT 0,
    `created_at`          timestamp                            NULL     DEFAULT NULL,
    `updated_at`          timestamp                            NULL     DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 20
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `referred_by`, `provider_id`, `user_type`, `name`, `email`, `email_verified_at`, `password`,
                     `remember_token`, `avatar`, `avatar_original`, `address`, `country`, `city`, `postal_code`,
                     `phone`, `balance`, `referral_code`, `customer_package_id`, `remaining_uploads`, `created_at`,
                     `updated_at`)
VALUES (3, NULL, NULL, 'seller', 'آرمین رحیمی', 'seller@example.com', '2018-12-11 18:00:00',
        '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm',
        'ne8qEnuHh0AqRDV297wUyqViP45M1dr5JcEr217O0NL5nqH4vK3bIR4Hz8AO',
        'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50',
        'uploads/9XPIm6rto3nggN0zgBx32dHYQi5DyU1SRrYxOa0s.png', NULL, NULL, NULL, NULL, NULL, 0.00, '3dLUoHsR1l', NULL,
        NULL, '2018-10-07 04:42:57', '2020-07-12 11:55:49'),
       (8, NULL, NULL, 'customer', 'علی احمدی', 'customer@example.com', '2018-12-11 18:00:00',
        '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm',
        'ZgP0DfiFkICMKxHaQJIeg2nvhEv6q9neQkpihP0IJiqDyB6iPlhMNZjFzW7j',
        'https://lh3.googleusercontent.com/-7OnRtLyua5Q/AAAAAAAAAAI/AAAAAAAADRk/VqWKMl4f8CI/photo.jpg?sz=50',
        'uploads/users/jo3EHt8rbcdfprb6sw74GKYK2iQyc5jmxVfd3vEj.jpeg', NULL, NULL, NULL, NULL, NULL, 0.00, '8zJTyXTlTT',
        NULL, NULL, '2018-10-07 04:42:57', '2020-07-24 02:54:26'),
       (12, NULL, NULL, 'admin', 'admin', 'admin@example.com', '2020-06-25 06:36:33',
        '$2y$10$eUKRlkmm2TAug75cfGQ4i.WoUbcJ2uVPqUlVkox.cv4CCyGEIMQEm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-06-25 06:38:33', '2020-06-25 06:38:33'),
       (13, NULL, NULL, 'seller', 'متین جوان', 'seller2@example.com', '2020-07-13 06:37:56',
        '$2y$10$mnzXF29.dVrSzL/zy3dKF.Xt0koaOJ2wQs5APOYfdJkf3ZrEXzAGi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-07-13 06:25:49', '2020-07-13 06:38:56'),
       (14, NULL, NULL, 'seller', 'رضا رحمتی', 'seller3@example.com', '2020-07-17 23:37:11',
        '$2y$10$hA0vvwR/Rx5KSieJ9/xaL.O1GYaIQORu9zScwzrhtIER4ILzXTjHu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-07-17 23:37:32', '2020-07-18 00:01:11'),
       (15, NULL, NULL, 'seller', 'علی همتی', 'seller4@example.com', '2020-07-17 23:37:11',
        '$2y$10$ichl8tRniAkh76QYF5npmOh629z6n/IYrj59zN/lvSE9hxEFBYArK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-07-18 07:39:46', '2020-07-18 07:39:46'),
       (16, NULL, NULL, 'seller', 'الهام جابری', 'seller5@example.com', '2020-07-17 23:37:11',
        '$2y$10$MB6TzZZi8vfBqReeqj8ndemsLeQARD4a3yDA2AvW9IyT4pQjpnj4S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-07-19 01:48:09', '2020-07-19 01:48:09'),
       (17, NULL, NULL, 'seller', 'علی سلطانی', 'seller6@example.com', '2020-07-17 23:37:11',
        '$2y$10$.8SzfNdSupTjXwb/dFGkt.e7MxGp7fIhAQd02t82H60sFRH9Pj2u6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-07-19 02:21:12', '2020-07-19 02:21:12'),
       (18, NULL, NULL, 'staff', 'اپراتور سفارشات حضوری', 'inhouse_orders@example.com', NULL,
        '$2y$10$4kWT7UZSvGLc0b.UT1MS4.rLVk2daB5iUB9wzCX3mMWBbtN3XuxpK', NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        '09121111111', 0.00, NULL, NULL, 0, '2020-07-20 11:42:24', '2020-07-20 11:42:24'),
       (19, NULL, NULL, 'seller', 'یوسف ابراهیمی', 'seller7@example.com', '2020-07-17 23:37:11',
        '$2y$10$boTae38mLpu10h7dVecfjuulM4zbthdFskY9zZ7bb1PxHgNGMfQvy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
        0.00, NULL, NULL, 0, '2020-08-09 12:35:08', '2020-08-09 12:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
CREATE TABLE IF NOT EXISTS `wallets`
(
    `id`              int(11)                              NOT NULL AUTO_INCREMENT,
    `user_id`         int(11)                              NOT NULL,
    `amount`          double(8, 2)                         NOT NULL,
    `payment_method`  varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `payment_details` longtext COLLATE utf8_unicode_ci              DEFAULT NULL,
    `created_at`      timestamp                            NOT NULL DEFAULT current_timestamp(),
    `updated_at`      timestamp                            NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists`
(
    `id`         int(11)   NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)   NOT NULL,
    `product_id` int(11)   NOT NULL,
    `created_at` timestamp NULL     DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`)
VALUES (2, 8, 23, '2020-07-20 02:35:13', '2020-07-20 02:35:13');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
