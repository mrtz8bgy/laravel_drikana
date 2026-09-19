/*
 Navicat Premium Dump SQL

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100411 (10.4.11-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : drikana-db

 Target Server Type    : MySQL
 Target Server Version : 100411 (10.4.11-MariaDB)
 File Encoding         : 65001

 Date: 09/09/2026 17:51:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for addons
-- ----------------------------
DROP TABLE IF EXISTS `addons`;
CREATE TABLE `addons`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `unique_identifier` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `version` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `activated` int NOT NULL DEFAULT 1,
  `image` varchar(1000) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf32 COLLATE = utf32_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of addons
-- ----------------------------

-- ----------------------------
-- Table structure for addresses
-- ----------------------------
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `set_default` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of addresses
-- ----------------------------
INSERT INTO `addresses` VALUES (1, 8, 'سیدخندان پلاک 40', 'Iran (Islamic Republic of)', 'تهران', '1476822666', '09126769825', 1, '2020-07-11 10:16:53', '2020-07-24 06:39:45');
INSERT INTO `addresses` VALUES (3, 3, 'ولیعصر پاساژ رضا پلاک 1234', 'Iran (Islamic Republic of)', 'تهران', '1476822666', '9121112233', 1, '2020-07-12 07:47:28', '2020-07-12 07:47:36');
INSERT INTO `addresses` VALUES (4, 8, 'ورامین میدان رازی', 'Iran (Islamic Republic of)', 'ورامین', '3371745768', '02136725581', 0, '2025-12-22 12:16:32', '2025-12-22 12:16:32');
INSERT INTO `addresses` VALUES (5, 12, 'ورامین میدان رازی', 'Iran (Islamic Republic of)', 'ورامین', '3371745768', '+989306060331', 0, '2026-01-01 09:45:41', '2026-01-01 09:45:41');
INSERT INTO `addresses` VALUES (6, 29, 'ورامین میدان رازی', 'Iran (Islamic Republic of)', 'ورامین', '3371745768', '+989306060331', 0, '2026-01-01 23:27:24', '2026-01-01 23:27:24');

-- ----------------------------
-- Table structure for app_settings
-- ----------------------------
DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE `app_settings`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `currency_id` int NULL DEFAULT NULL,
  `currency_format` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `facebook` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `twitter` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `instagram` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `youtube` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `google_plus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of app_settings
-- ----------------------------
INSERT INTO `app_settings` VALUES (1, 'Active eCommerce', 'uploads/logo/matggar.png', 1, 'symbol', 'https://facebook.com', 'https://twitter.com', 'https://instagram.com', 'https://youtube.com', 'https://google.com', '2019-08-04 20:09:15', '2019-08-04 20:09:18');

-- ----------------------------
-- Table structure for attributes
-- ----------------------------
DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf32 COLLATE = utf32_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of attributes
-- ----------------------------
INSERT INTO `attributes` VALUES (1, 'کد شناسایی محصول', '2020-02-24 09:25:07', '2025-12-31 14:26:13');
INSERT INTO `attributes` VALUES (2, 'مدل', '2020-02-24 09:25:13', '2025-12-28 18:53:38');
INSERT INTO `attributes` VALUES (3, 'عیار', '2025-12-28 18:51:29', '2025-12-28 18:51:29');
INSERT INTO `attributes` VALUES (4, 'رنگ', '2025-12-28 18:51:52', '2025-12-28 18:51:52');
INSERT INTO `attributes` VALUES (5, 'جنس', '2025-12-28 18:52:12', '2025-12-28 18:52:12');
INSERT INTO `attributes` VALUES (6, 'نوع', '2025-12-28 18:52:44', '2025-12-28 18:52:44');
INSERT INTO `attributes` VALUES (7, 'جنسیت', '2025-12-28 18:53:09', '2025-12-28 18:53:09');
INSERT INTO `attributes` VALUES (8, 'وزن', '2025-12-28 18:53:22', '2025-12-28 18:53:22');
INSERT INTO `attributes` VALUES (9, 'سال ساخت', '2025-12-28 18:53:50', '2025-12-28 18:53:50');
INSERT INTO `attributes` VALUES (10, 'سایز', '2025-12-31 14:25:28', '2025-12-31 14:25:48');

-- ----------------------------
-- Table structure for banners
-- ----------------------------
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `url` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `position` int NOT NULL DEFAULT 1,
  `published` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of banners
-- ----------------------------
INSERT INTO `banners` VALUES (4, 'uploads/banners/gz5xZsK2IcwXKzx8BIpybd0zflho4C1QeMsEiMWr.jpeg', '#', 1, 1, '2019-03-12 09:28:23', '2020-07-22 02:53:33');
INSERT INTO `banners` VALUES (5, 'uploads/banners/dBfaGAeRI34g1Rpb8piCwJSpdeZ9dhrkYEVIkoFe.jpeg', '#', 1, 1, '2019-03-12 09:28:41', '2020-07-22 02:53:52');
INSERT INTO `banners` VALUES (6, 'uploads/banners/u4jYHOJH3h2NgYB4ai4fjKBjINRyccoTvN41FVVi.jpg', '#', 2, 1, '2019-03-12 09:28:52', '2026-01-01 11:12:16');
INSERT INTO `banners` VALUES (7, 'uploads/banners/I9hTaD9y6gXGDzrKX3atAYDlUhV0rPPHONe1OVeY.jpg', '#', 2, 1, '2019-05-26 08:46:38', '2026-01-01 11:13:45');
INSERT INTO `banners` VALUES (9, 'uploads/banners/yLyzoGfMgZHtf8XfQ706SDV7cm420qdv4wRU3DVy.jpeg', '#', 1, 1, '2019-06-11 08:30:15', '2020-07-22 02:54:09');
INSERT INTO `banners` VALUES (10, 'uploads/banners/0WFjYCatbBevDobv52ePJyj5ZkHmnjurMBbrJCIh.jpeg', '#', 1, 1, '2019-06-11 08:30:24', '2020-07-22 02:54:23');

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `top` int NOT NULL DEFAULT 0,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES (5, 'طلای ایران', 'uploads/brands/aAP9dcYBg53UtesQgACZqKYEPbRw9g6Trrq3vJVD.webp', 1, 'tala-iran', 'طلای ایران', NULL, '2020-07-04 13:20:03', '2025-12-31 14:00:47');
INSERT INTO `brands` VALUES (6, 'کیمیا گلد', 'uploads/brands/zDZtQiAKOH3ApOK3DvZHUAXeixHqEIML19U4Ukru.webp', 1, 'kimia-gold', 'کیمیا گلد', NULL, '2020-07-05 11:04:22', '2025-12-31 14:00:33');
INSERT INTO `brands` VALUES (7, 'سکه طلا', 'uploads/brands/NZJLl1tV3PDmlPF94VZQgWTr8KhNtZLXxWOcpf55.webp', 1, 'seke-tala', 'سکه طلا', NULL, '2020-07-05 11:04:35', '2025-12-31 14:00:18');
INSERT INTO `brands` VALUES (8, 'طلا و جواهرات زرین', 'uploads/brands/XgXxQnLxl0TOM3JUsT3iA7hV1dTLt7nzYeykbNDn.webp', 1, 'zarine', 'طلا و جواهرات زرین', NULL, '2020-07-08 05:02:31', '2025-12-31 13:59:57');
INSERT INTO `brands` VALUES (9, 'الماس گستر', 'uploads/brands/tPgqbixPY4qulaRqelfoTqPBBQ9McAzMHHPLSFq9.webp', 0, 'almas-gostar', 'الماس گستر', NULL, '2020-07-18 05:03:58', '2025-12-31 13:59:35');
INSERT INTO `brands` VALUES (10, 'زرین گلد', 'uploads/brands/JgiuSN3fCYzyQcqX30a6ZiZRvbUXgJCJJUEIIIVX.webp', 1, 'zaringold', 'زرین گلد', NULL, '2020-07-18 05:04:35', '2025-12-31 13:58:55');
INSERT INTO `brands` VALUES (11, 'طلای ملی', 'uploads/brands/YhtBVqP88kePXc7ITm0rQCeH3rsSk5pkfX58HQZo.webp', 0, 'tala-melli', 'طلای ملی', NULL, '2020-07-18 05:05:19', '2025-12-31 13:58:42');
INSERT INTO `brands` VALUES (12, 'طلا رویال', 'uploads/brands/NaMDokoaELho0CICIGP5tN56KXALCXp537IZLeDd.webp', 0, 'tala-royal', 'طلا رویال', NULL, '2020-07-18 05:05:54', '2025-12-31 13:58:26');
INSERT INTO `brands` VALUES (13, 'نگین طلایی', 'uploads/brands/xDPFK5kgmdASd1T27ZhoDsXweyImvaD8mGnkHWrI.webp', 1, 'negin-talayi', 'نگین طلایی', NULL, '2020-07-18 05:06:05', '2025-12-31 13:58:16');
INSERT INTO `brands` VALUES (14, 'طلای پارسیان', 'uploads/brands/eQ2uJAQ3ZUJnLuTNigJcq4VENgUdJCAvjEjLJNvY.webp', 0, 'tala-parsian', 'طلای پارسیان', NULL, '2020-07-18 05:06:18', '2025-12-31 13:58:04');
INSERT INTO `brands` VALUES (15, 'جواهرات کیمیا', 'uploads/brands/mlOTBjMaTSqYrOYJqBUbe0PUWQ9PU0IDjhpkPXxJ.webp', 1, 'jewelry-kimia', 'جواهرات کیمیا', NULL, '2020-07-18 10:56:48', '2025-12-31 13:57:52');
INSERT INTO `brands` VALUES (16, 'طلای زهره', 'uploads/brands/MtofmUEKXAtK9y5YFm4OdoyevLpVhe6LoUBIER6s.webp', 0, 'tala-zohre', 'طلای زهره', NULL, '2020-07-18 11:08:47', '2025-12-31 13:57:35');
INSERT INTO `brands` VALUES (17, 'الماس پارس', 'uploads/brands/kfTVKJ6yl7QbUzu0DGpW9LKgQD8gXnvkWcmBsgXB.webp', 1, 'almas-pars', 'الماس پارس', NULL, '2020-07-18 11:52:21', '2025-12-31 13:57:27');
INSERT INTO `brands` VALUES (18, 'زر طلایی', 'uploads/brands/GGx0InN5NBtOn0CyJyoX8pkH9y0HT878snv0VydX.webp', 1, 'zar-talayi', 'زر طلایی', NULL, '2020-07-18 11:52:35', '2025-12-31 13:57:08');
INSERT INTO `brands` VALUES (19, 'طلای نیکان', 'uploads/brands/u6AeF0SqamLaQTG1Uu3aUPXa1UobVN9sKO0LmJxF.webp', 1, 'tala-nikan', 'طلای نیکان', NULL, '2020-07-18 11:52:50', '2025-12-31 13:56:59');

-- ----------------------------
-- Table structure for business_settings
-- ----------------------------
DROP TABLE IF EXISTS `business_settings`;
CREATE TABLE `business_settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of business_settings
-- ----------------------------
INSERT INTO `business_settings` VALUES (1, 'home_default_currency', '1', '2018-10-16 05:05:52', '2019-01-28 04:56:53');
INSERT INTO `business_settings` VALUES (2, 'system_default_currency', '1', '2018-10-16 05:06:58', '2020-01-26 07:52:13');
INSERT INTO `business_settings` VALUES (3, 'currency_format', '1', '2018-10-17 06:31:59', '2018-10-17 06:31:59');
INSERT INTO `business_settings` VALUES (4, 'symbol_format', '2', '2018-10-17 06:31:59', '2020-07-06 07:49:34');
INSERT INTO `business_settings` VALUES (5, 'no_of_decimals', '0', '2018-10-17 06:31:59', '2020-06-30 03:33:23');
INSERT INTO `business_settings` VALUES (6, 'product_activation', '1', '2018-10-28 05:08:37', '2019-02-04 04:41:41');
INSERT INTO `business_settings` VALUES (7, 'vendor_system_activation', '1', '2018-10-28 11:14:16', '2019-02-04 04:41:38');
INSERT INTO `business_settings` VALUES (8, 'show_vendors', '1', '2018-10-28 11:14:47', '2019-02-04 04:41:13');
INSERT INTO `business_settings` VALUES (9, 'paypal_payment', '0', '2018-10-28 11:15:16', '2019-01-31 08:39:10');
INSERT INTO `business_settings` VALUES (10, 'stripe_payment', '0', '2018-10-28 11:15:47', '2018-11-14 05:21:51');
INSERT INTO `business_settings` VALUES (11, 'cash_payment', '1', '2018-10-28 11:16:05', '2019-01-24 07:10:18');
INSERT INTO `business_settings` VALUES (12, 'payumoney_payment', '0', '2018-10-28 11:16:27', '2019-03-05 09:11:36');
INSERT INTO `business_settings` VALUES (13, 'best_selling', '1', '2018-12-24 11:43:44', '2019-02-14 08:59:13');
INSERT INTO `business_settings` VALUES (14, 'paypal_sandbox', '0', '2019-01-16 16:14:18', '2019-01-16 16:14:18');
INSERT INTO `business_settings` VALUES (15, 'sslcommerz_sandbox', '1', '2019-01-16 16:14:18', '2019-03-14 03:37:26');
INSERT INTO `business_settings` VALUES (16, 'sslcommerz_payment', '0', '2019-01-24 13:09:07', '2019-01-29 09:43:46');
INSERT INTO `business_settings` VALUES (17, 'vendor_commission', '80', '2019-01-31 09:48:04', '2020-07-13 06:56:13');
INSERT INTO `business_settings` VALUES (18, 'verification_form', '[{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u0648 \\u0646\\u0627\\u0645 \\u062e\\u0627\\u0646\\u0648\\u0627\\u062f\\u06af\\u06cc\"},{\"type\":\"text\",\"label\":\"\\u0646\\u0627\\u0645 \\u067e\\u062f\\u0631\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u0634\\u0646\\u0627\\u0633\\u0646\\u0627\\u0645\\u0647\"},{\"type\":\"text\",\"label\":\"\\u06a9\\u062f\\u0645\\u0644\\u06cc\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u067e\\u0631\\u0648\\u0627\\u0646\\u0647 \\u06a9\\u0633\\u0628\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u0647\\u0645\\u0631\\u0627\\u0647\"},{\"type\":\"text\",\"label\":\"\\u0634\\u0645\\u0627\\u0631\\u0647 \\u062a\\u0644\\u0641\\u0646 \\u062b\\u0627\\u0628\\u062a\"},{\"type\":\"text\",\"label\":\"\\u0622\\u062f\\u0631\\u0633\"},{\"type\":\"file\",\"label\":\"\\u0645\\u062f\\u0627\\u0631\\u06a9 \\u0634\\u0646\\u0627\\u0633\\u0627\\u06cc\\u06cc\"},{\"type\":\"file\",\"label\":\"\\u0645\\u062f\\u0627\\u0631\\u06a9 \\u06a9\\u0633\\u0628 \\u0648 \\u06a9\\u0627\\u0631\"}]', '2019-02-03 15:06:58', '2025-12-31 14:06:10');
INSERT INTO `business_settings` VALUES (19, 'google_analytics', '0', '2019-02-06 15:52:35', '2019-02-06 15:52:35');
INSERT INTO `business_settings` VALUES (20, 'facebook_login', '0', '2019-02-07 16:21:59', '2019-02-08 23:11:15');
INSERT INTO `business_settings` VALUES (21, 'google_login', '0', '2019-02-07 16:22:10', '2019-02-08 23:11:14');
INSERT INTO `business_settings` VALUES (22, 'twitter_login', '0', '2019-02-07 16:22:20', '2019-02-08 06:02:56');
INSERT INTO `business_settings` VALUES (23, 'payumoney_payment', '1', '2019-03-05 15:08:17', '2019-03-05 15:08:17');
INSERT INTO `business_settings` VALUES (24, 'payumoney_sandbox', '1', '2019-03-05 15:08:17', '2019-03-05 09:09:18');
INSERT INTO `business_settings` VALUES (36, 'facebook_chat', '0', '2019-04-15 15:15:04', '2019-04-15 15:15:04');
INSERT INTO `business_settings` VALUES (37, 'email_verification', '0', '2019-04-30 11:00:07', '2019-04-30 11:00:07');
INSERT INTO `business_settings` VALUES (38, 'wallet_system', '0', '2019-05-19 11:35:44', '2020-08-05 03:51:08');
INSERT INTO `business_settings` VALUES (39, 'coupon_system', '0', '2019-06-11 13:16:18', '2019-06-11 13:16:18');
INSERT INTO `business_settings` VALUES (40, 'current_version', '2.9', '2019-06-11 13:16:18', '2019-06-11 13:16:18');
INSERT INTO `business_settings` VALUES (41, 'instamojo_payment', '0', '2019-07-06 13:28:03', '2019-07-06 13:28:03');
INSERT INTO `business_settings` VALUES (42, 'instamojo_sandbox', '1', '2019-07-06 13:28:43', '2019-07-06 13:28:43');
INSERT INTO `business_settings` VALUES (43, 'razorpay', '0', '2019-07-06 13:28:43', '2019-07-06 13:28:43');
INSERT INTO `business_settings` VALUES (44, 'paystack', '0', '2019-07-21 16:30:38', '2019-07-21 16:30:38');
INSERT INTO `business_settings` VALUES (45, 'pickup_point', '1', '2019-10-17 15:20:39', '2020-07-14 12:04:45');
INSERT INTO `business_settings` VALUES (46, 'maintenance_mode', '0', '2019-10-17 15:21:04', '2020-08-04 05:08:59');
INSERT INTO `business_settings` VALUES (47, 'voguepay', '0', '2019-10-17 15:21:24', '2019-10-17 15:21:24');
INSERT INTO `business_settings` VALUES (48, 'voguepay_sandbox', '0', '2019-10-17 15:21:38', '2019-10-17 15:21:38');
INSERT INTO `business_settings` VALUES (50, 'category_wise_commission', '0', '2020-01-21 10:52:47', '2020-01-21 10:52:47');
INSERT INTO `business_settings` VALUES (51, 'conversation_system', '1', '2020-01-21 10:53:21', '2020-01-21 10:53:21');
INSERT INTO `business_settings` VALUES (52, 'guest_checkout_active', '1', '2020-01-22 11:06:38', '2020-01-22 11:06:38');
INSERT INTO `business_settings` VALUES (53, 'facebook_pixel', '0', '2020-01-22 15:13:58', '2020-01-22 15:13:58');
INSERT INTO `business_settings` VALUES (55, 'classified_product', '0', '2020-05-13 16:31:05', '2020-08-05 03:44:49');
INSERT INTO `business_settings` VALUES (56, 'pos_activation_for_seller', '1', '2020-06-11 13:15:02', '2020-06-11 13:15:02');
INSERT INTO `business_settings` VALUES (57, 'shipping_type', 'product_wise_shipping', '2020-07-04 10:32:57', '2020-07-04 10:32:57');
INSERT INTO `business_settings` VALUES (58, 'flat_rate_shipping_cost', '0', '2020-07-04 10:32:57', '2020-07-04 10:32:57');
INSERT INTO `business_settings` VALUES (59, 'shipping_cost_admin', '0', '2020-07-04 10:32:57', '2020-07-04 10:32:57');

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `product_id` int NULL DEFAULT NULL,
  `variation` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `price` double(8, 2) NULL DEFAULT NULL,
  `tax` double(8, 2) NULL DEFAULT NULL,
  `shipping_cost` double(8, 2) NULL DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of carts
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commision_rate` double(8, 2) NOT NULL DEFAULT 0.00,
  `banner` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `featured` int NOT NULL DEFAULT 0,
  `top` int NOT NULL DEFAULT 0,
  `digital` int NOT NULL DEFAULT 0,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (4, 'طلا و سکه', 0.00, 'uploads/categories/banner/j9RdT1fB4vz6WgefxVOfalAor63Nf6qOlzhQH9Fv.webp', 'uploads/categories/icon/4k2o8u6W0VuotGl9iqdI6vHEmojWHD7yNzDO7eLa.webp', 0, 1, 0, '--2EJpD', 'طلا و سکه', 'دسته مربوط به طلا و سکه ها', '2025-12-31 17:18:02', '2025-12-31 13:48:02');
INSERT INTO `categories` VALUES (5, 'ساعت‌های لوکس', 0.00, 'uploads/categories/banner/8OWqjFjhNSfclJHcpk3DM2r2rSk964wsZrB2fxkv.webp', 'uploads/categories/icon/O256zt8iQfQRHVedr6hvwldkGx90hloRWzcJDbNA.webp', 0, 1, 0, '----FoS0v', 'ساعت‌های لوکس', 'دسته ساعت های برند', '2025-12-31 17:19:55', '2025-12-31 13:49:55');
INSERT INTO `categories` VALUES (6, 'جواهرات زنانه', 0.00, 'uploads/categories/banner/Kj87XQDQBpqRMW8j6eR029Jtc4BwZtToHbnPU4rs.webp', 'uploads/categories/icon/RmXJwd8lDFazKcApTSIddvEDiZezkKTmRmMuscZk.webp', 0, 1, 0, '----N4OaQ', 'جواهرات زنانه', NULL, '2025-12-31 17:20:16', '2025-12-31 13:50:16');
INSERT INTO `categories` VALUES (7, 'جواهرات مردانه', 0.00, 'uploads/categories/banner/evc67s4Xs3TqQBAJ9MYnxNIVHFNcMKvmF2a9VHFG.webp', 'uploads/categories/icon/VBlwqLhsF5lCIodcjc1Fo9glmQULz1UkKwwPifRe.webp', 0, 1, 0, '---WKBAy', 'جواهرات مردانه', NULL, '2025-12-31 17:21:26', '2025-12-31 13:51:26');
INSERT INTO `categories` VALUES (8, 'انگشتر و دستبند', 0.00, 'uploads/categories/banner/9BLM6RBT6GZ4pbq0GUdtUBUMtk2IVhQiJOtYMRwU.webp', 'uploads/categories/icon/jTzzToVNHTQlbflr4hSR2JgN0ZMdAXRivGTPIowp.webp', 0, 1, 0, '---uQqCB', 'انگشتر و دستبند', NULL, '2025-12-31 17:21:55', '2025-12-31 13:51:55');
INSERT INTO `categories` VALUES (9, 'گردنبند و پابند', 0.00, 'uploads/categories/banner/Lb7SOk7DjI3xk2EbyD0WPhCovb71Ya8XyKV9v1T4.webp', 'uploads/categories/icon/dhmTU6zNBeHhJvFufIa5MzT3OPFOL1JMKXw8BE7a.webp', 0, 1, 0, '-----MB8NO', 'گردنبند و پابند', NULL, '2025-12-31 17:22:51', '2025-12-31 13:52:51');
INSERT INTO `categories` VALUES (12, 'سنگ‌های قیمتی', 0.00, 'uploads/categories/banner/NAQ6xfeWcwg9gnc0su0Vew3wGGZ7lb1VgWj7YhV3.webp', 'uploads/categories/icon/xv1R8QEGckhy6QcwzUlvOs4QDAp7Dk0c64iZwUOn.webp', 0, 1, 0, '---iLNhI', 'سنگ‌های قیمتی', NULL, '2025-12-31 17:23:23', '2025-12-31 13:53:23');
INSERT INTO `categories` VALUES (13, 'اکسسوری‌های طلا', 0.00, 'uploads/categories/banner/aAez1ZdsnsiHFRSS7GthaiqUyq9X5ABRYDIuliYI.webp', 'uploads/categories/icon/JSCDczuWKJNOci9TqVedirSeFMzNFfnzRKYKKvBA.webp', 0, 1, 0, '---qY463', 'اکسسوری‌های طلا', 'اکسسوری‌های طلا', '2025-12-31 17:24:00', '2025-12-31 13:54:00');
INSERT INTO `categories` VALUES (14, 'هدیه و اقلام لوکس', 0.00, 'uploads/categories/banner/2IGukVvYsDBhnkgwoxEKPn7ETmhlmdyUMx8McWII.webp', 'uploads/categories/icon/kQXDntLy2tQOVBdnIDPpK49MlRzLwNbKdFKBHfSl.webp', 0, 1, 0, '-----H1Zlr', 'هدیه و اقلام لوکس', 'هدیه و اقلام لوکس', '2025-12-31 17:24:26', '2025-12-31 13:54:26');
INSERT INTO `categories` VALUES (15, 'لوازم جانبی ساعت و جواهر', 0.00, 'uploads/categories/banner/RshhhluhMFk5vPFAqTrjgyl3o5RRmjmuq5wMrWHo.webp', 'uploads/categories/icon/h5ZlxaLSRWMRhGqVSMddb7sgZVMUUgMwBp69BhJ3.webp', 0, 1, 0, '--o65zD', 'لوازم جانبی ساعت و جواهر', 'لوازم جانبی ساعت و جواهر', '2025-12-31 17:25:36', '2025-12-31 13:55:36');

-- ----------------------------
-- Table structure for colors
-- ----------------------------
DROP TABLE IF EXISTS `colors`;
CREATE TABLE `colors`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 144 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of colors
-- ----------------------------
INSERT INTO `colors` VALUES (1, 'IndianRed', '#CD5C5C', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (2, 'LightCoral', '#F08080', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (3, 'Salmon', '#FA8072', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (4, 'DarkSalmon', '#E9967A', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (5, 'LightSalmon', '#FFA07A', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (6, 'Crimson', '#DC143C', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (7, 'Red', '#FF0000', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (8, 'FireBrick', '#B22222', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (9, 'DarkRed', '#8B0000', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (10, 'Pink', '#FFC0CB', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (11, 'LightPink', '#FFB6C1', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (12, 'HotPink', '#FF69B4', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (13, 'DeepPink', '#FF1493', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (14, 'MediumVioletRed', '#C71585', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (15, 'PaleVioletRed', '#DB7093', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (16, 'LightSalmon', '#FFA07A', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (17, 'Coral', '#FF7F50', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (18, 'Tomato', '#FF6347', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (19, 'OrangeRed', '#FF4500', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (20, 'DarkOrange', '#FF8C00', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (21, 'Orange', '#FFA500', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (22, 'Gold', '#FFD700', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (23, 'Yellow', '#FFFF00', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (24, 'LightYellow', '#FFFFE0', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (25, 'LemonChiffon', '#FFFACD', '2018-11-05 05:42:26', '2018-11-05 05:42:26');
INSERT INTO `colors` VALUES (26, 'LightGoldenrodYellow', '#FAFAD2', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (27, 'PapayaWhip', '#FFEFD5', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (28, 'Moccasin', '#FFE4B5', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (29, 'PeachPuff', '#FFDAB9', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (30, 'PaleGoldenrod', '#EEE8AA', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (31, 'Khaki', '#F0E68C', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (32, 'DarkKhaki', '#BDB76B', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (33, 'Lavender', '#E6E6FA', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (34, 'Thistle', '#D8BFD8', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (35, 'Plum', '#DDA0DD', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (36, 'Violet', '#EE82EE', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (37, 'Orchid', '#DA70D6', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (38, 'Fuchsia', '#FF00FF', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (39, 'Magenta', '#FF00FF', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (40, 'MediumOrchid', '#BA55D3', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (41, 'MediumPurple', '#9370DB', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (42, 'Amethyst', '#9966CC', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (43, 'BlueViolet', '#8A2BE2', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (44, 'DarkViolet', '#9400D3', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (45, 'DarkOrchid', '#9932CC', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (46, 'DarkMagenta', '#8B008B', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (47, 'Purple', '#800080', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (48, 'Indigo', '#4B0082', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (49, 'SlateBlue', '#6A5ACD', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (50, 'DarkSlateBlue', '#483D8B', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (51, 'MediumSlateBlue', '#7B68EE', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (52, 'GreenYellow', '#ADFF2F', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (53, 'Chartreuse', '#7FFF00', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (54, 'LawnGreen', '#7CFC00', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (55, 'Lime', '#00FF00', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (56, 'LimeGreen', '#32CD32', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (57, 'PaleGreen', '#98FB98', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (58, 'LightGreen', '#90EE90', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (59, 'MediumSpringGreen', '#00FA9A', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (60, 'SpringGreen', '#00FF7F', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (61, 'MediumSeaGreen', '#3CB371', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (62, 'SeaGreen', '#2E8B57', '2018-11-05 05:42:27', '2018-11-05 05:42:27');
INSERT INTO `colors` VALUES (63, 'ForestGreen', '#228B22', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (64, 'Green', '#008000', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (65, 'DarkGreen', '#006400', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (66, 'YellowGreen', '#9ACD32', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (67, 'OliveDrab', '#6B8E23', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (68, 'Olive', '#808000', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (69, 'DarkOliveGreen', '#556B2F', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (70, 'MediumAquamarine', '#66CDAA', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (71, 'DarkSeaGreen', '#8FBC8F', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (72, 'LightSeaGreen', '#20B2AA', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (73, 'DarkCyan', '#008B8B', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (74, 'Teal', '#008080', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (75, 'Aqua', '#00FFFF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (76, 'Cyan', '#00FFFF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (77, 'LightCyan', '#E0FFFF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (78, 'PaleTurquoise', '#AFEEEE', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (79, 'Aquamarine', '#7FFFD4', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (80, 'Turquoise', '#40E0D0', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (81, 'MediumTurquoise', '#48D1CC', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (82, 'DarkTurquoise', '#00CED1', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (83, 'CadetBlue', '#5F9EA0', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (84, 'SteelBlue', '#4682B4', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (85, 'LightSteelBlue', '#B0C4DE', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (86, 'PowderBlue', '#B0E0E6', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (87, 'LightBlue', '#ADD8E6', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (88, 'SkyBlue', '#87CEEB', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (89, 'LightSkyBlue', '#87CEFA', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (90, 'DeepSkyBlue', '#00BFFF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (91, 'DodgerBlue', '#1E90FF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (92, 'CornflowerBlue', '#6495ED', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (93, 'MediumSlateBlue', '#7B68EE', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (94, 'RoyalBlue', '#4169E1', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (95, 'Blue', '#0000FF', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (96, 'MediumBlue', '#0000CD', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (97, 'DarkBlue', '#00008B', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (98, 'Navy', '#000080', '2018-11-05 05:42:28', '2018-11-05 05:42:28');
INSERT INTO `colors` VALUES (99, 'MidnightBlue', '#191970', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (100, 'Cornsilk', '#FFF8DC', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (101, 'BlanchedAlmond', '#FFEBCD', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (102, 'Bisque', '#FFE4C4', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (103, 'NavajoWhite', '#FFDEAD', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (104, 'Wheat', '#F5DEB3', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (105, 'BurlyWood', '#DEB887', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (106, 'Tan', '#D2B48C', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (107, 'RosyBrown', '#BC8F8F', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (108, 'SandyBrown', '#F4A460', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (109, 'Goldenrod', '#DAA520', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (110, 'DarkGoldenrod', '#B8860B', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (111, 'Peru', '#CD853F', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (112, 'Chocolate', '#D2691E', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (113, 'SaddleBrown', '#8B4513', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (114, 'Sienna', '#A0522D', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (115, 'Brown', '#A52A2A', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (116, 'Maroon', '#800000', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (117, 'White', '#FFFFFF', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (118, 'Snow', '#FFFAFA', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (119, 'Honeydew', '#F0FFF0', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (120, 'MintCream', '#F5FFFA', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (121, 'Azure', '#F0FFFF', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (122, 'AliceBlue', '#F0F8FF', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (123, 'GhostWhite', '#F8F8FF', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (124, 'WhiteSmoke', '#F5F5F5', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (125, 'Seashell', '#FFF5EE', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (126, 'Beige', '#F5F5DC', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (127, 'OldLace', '#FDF5E6', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (128, 'FloralWhite', '#FFFAF0', '2018-11-05 05:42:29', '2018-11-05 05:42:29');
INSERT INTO `colors` VALUES (129, 'Ivory', '#FFFFF0', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (130, 'AntiqueWhite', '#FAEBD7', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (131, 'Linen', '#FAF0E6', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (132, 'LavenderBlush', '#FFF0F5', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (133, 'MistyRose', '#FFE4E1', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (134, 'Gainsboro', '#DCDCDC', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (135, 'LightGrey', '#D3D3D3', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (136, 'Silver', '#C0C0C0', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (137, 'DarkGray', '#A9A9A9', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (138, 'Gray', '#808080', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (139, 'DimGray', '#696969', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (140, 'LightSlateGray', '#778899', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (141, 'SlateGray', '#708090', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (142, 'DarkSlateGray', '#2F4F4F', '2018-11-05 05:42:30', '2018-11-05 05:42:30');
INSERT INTO `colors` VALUES (143, 'Black', '#000000', '2018-11-05 05:42:30', '2018-11-05 05:42:30');

-- ----------------------------
-- Table structure for conversations
-- ----------------------------
DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `title` varchar(1000) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL,
  `sender_viewed` int NOT NULL DEFAULT 1,
  `receiver_viewed` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf32 COLLATE = utf32_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of conversations
-- ----------------------------

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 297 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of countries
-- ----------------------------
INSERT INTO `countries` VALUES (1, 'AF', 'Afghanistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (2, 'AL', 'Albania', 1, NULL, NULL);
INSERT INTO `countries` VALUES (3, 'DZ', 'Algeria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (4, 'DS', 'American Samoa', 1, NULL, NULL);
INSERT INTO `countries` VALUES (5, 'AD', 'Andorra', 1, NULL, NULL);
INSERT INTO `countries` VALUES (6, 'AO', 'Angola', 1, NULL, NULL);
INSERT INTO `countries` VALUES (7, 'AI', 'Anguilla', 1, NULL, NULL);
INSERT INTO `countries` VALUES (8, 'AQ', 'Antarctica', 1, NULL, NULL);
INSERT INTO `countries` VALUES (9, 'AG', 'Antigua and Barbuda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (10, 'AR', 'Argentina', 1, NULL, NULL);
INSERT INTO `countries` VALUES (11, 'AM', 'Armenia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (12, 'AW', 'Aruba', 1, NULL, NULL);
INSERT INTO `countries` VALUES (13, 'AU', 'Australia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (14, 'AT', 'Austria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (15, 'AZ', 'Azerbaijan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (16, 'BS', 'Bahamas', 1, NULL, NULL);
INSERT INTO `countries` VALUES (17, 'BH', 'Bahrain', 1, NULL, NULL);
INSERT INTO `countries` VALUES (18, 'BD', 'Bangladesh', 1, NULL, NULL);
INSERT INTO `countries` VALUES (19, 'BB', 'Barbados', 1, NULL, NULL);
INSERT INTO `countries` VALUES (20, 'BY', 'Belarus', 1, NULL, NULL);
INSERT INTO `countries` VALUES (21, 'BE', 'Belgium', 1, NULL, NULL);
INSERT INTO `countries` VALUES (22, 'BZ', 'Belize', 1, NULL, NULL);
INSERT INTO `countries` VALUES (23, 'BJ', 'Benin', 1, NULL, NULL);
INSERT INTO `countries` VALUES (24, 'BM', 'Bermuda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (25, 'BT', 'Bhutan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (26, 'BO', 'Bolivia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (27, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL);
INSERT INTO `countries` VALUES (28, 'BW', 'Botswana', 1, NULL, NULL);
INSERT INTO `countries` VALUES (29, 'BV', 'Bouvet Island', 1, NULL, NULL);
INSERT INTO `countries` VALUES (30, 'BR', 'Brazil', 1, NULL, NULL);
INSERT INTO `countries` VALUES (31, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL);
INSERT INTO `countries` VALUES (32, 'BN', 'Brunei Darussalam', 1, NULL, NULL);
INSERT INTO `countries` VALUES (33, 'BG', 'Bulgaria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (34, 'BF', 'Burkina Faso', 1, NULL, NULL);
INSERT INTO `countries` VALUES (35, 'BI', 'Burundi', 1, NULL, NULL);
INSERT INTO `countries` VALUES (36, 'KH', 'Cambodia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (37, 'CM', 'Cameroon', 1, NULL, NULL);
INSERT INTO `countries` VALUES (38, 'CA', 'Canada', 1, NULL, NULL);
INSERT INTO `countries` VALUES (39, 'CV', 'Cape Verde', 1, NULL, NULL);
INSERT INTO `countries` VALUES (40, 'KY', 'Cayman Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (41, 'CF', 'Central African Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (42, 'TD', 'Chad', 1, NULL, NULL);
INSERT INTO `countries` VALUES (43, 'CL', 'Chile', 1, NULL, NULL);
INSERT INTO `countries` VALUES (44, 'CN', 'China', 1, NULL, NULL);
INSERT INTO `countries` VALUES (45, 'CX', 'Christmas Island', 1, NULL, NULL);
INSERT INTO `countries` VALUES (46, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (47, 'CO', 'Colombia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (48, 'KM', 'Comoros', 1, NULL, NULL);
INSERT INTO `countries` VALUES (49, 'CG', 'Congo', 1, NULL, NULL);
INSERT INTO `countries` VALUES (50, 'CK', 'Cook Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (51, 'CR', 'Costa Rica', 1, NULL, NULL);
INSERT INTO `countries` VALUES (52, 'HR', 'Croatia (Hrvatska)', 1, NULL, NULL);
INSERT INTO `countries` VALUES (53, 'CU', 'Cuba', 1, NULL, NULL);
INSERT INTO `countries` VALUES (54, 'CY', 'Cyprus', 1, NULL, NULL);
INSERT INTO `countries` VALUES (55, 'CZ', 'Czech Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (56, 'DK', 'Denmark', 1, NULL, NULL);
INSERT INTO `countries` VALUES (57, 'DJ', 'Djibouti', 1, NULL, NULL);
INSERT INTO `countries` VALUES (58, 'DM', 'Dominica', 1, NULL, NULL);
INSERT INTO `countries` VALUES (59, 'DO', 'Dominican Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (60, 'TP', 'East Timor', 1, NULL, NULL);
INSERT INTO `countries` VALUES (61, 'EC', 'Ecuador', 1, NULL, NULL);
INSERT INTO `countries` VALUES (62, 'EG', 'Egypt', 1, NULL, NULL);
INSERT INTO `countries` VALUES (63, 'SV', 'El Salvador', 1, NULL, NULL);
INSERT INTO `countries` VALUES (64, 'GQ', 'Equatorial Guinea', 1, NULL, NULL);
INSERT INTO `countries` VALUES (65, 'ER', 'Eritrea', 1, NULL, NULL);
INSERT INTO `countries` VALUES (66, 'EE', 'Estonia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (67, 'ET', 'Ethiopia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (68, 'FK', 'Falkland Islands (Malvinas)', 1, NULL, NULL);
INSERT INTO `countries` VALUES (69, 'FO', 'Faroe Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (70, 'FJ', 'Fiji', 1, NULL, NULL);
INSERT INTO `countries` VALUES (71, 'FI', 'Finland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (72, 'FR', 'France', 1, NULL, NULL);
INSERT INTO `countries` VALUES (73, 'FX', 'France, Metropolitan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (74, 'GF', 'French Guiana', 1, NULL, NULL);
INSERT INTO `countries` VALUES (75, 'PF', 'French Polynesia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (76, 'TF', 'French Southern Territories', 1, NULL, NULL);
INSERT INTO `countries` VALUES (77, 'GA', 'Gabon', 1, NULL, NULL);
INSERT INTO `countries` VALUES (78, 'GM', 'Gambia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (79, 'GE', 'Georgia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (80, 'DE', 'Germany', 1, NULL, NULL);
INSERT INTO `countries` VALUES (81, 'GH', 'Ghana', 1, NULL, NULL);
INSERT INTO `countries` VALUES (82, 'GI', 'Gibraltar', 1, NULL, NULL);
INSERT INTO `countries` VALUES (83, 'GK', 'Guernsey', 1, NULL, NULL);
INSERT INTO `countries` VALUES (84, 'GR', 'Greece', 1, NULL, NULL);
INSERT INTO `countries` VALUES (85, 'GL', 'Greenland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (86, 'GD', 'Grenada', 1, NULL, NULL);
INSERT INTO `countries` VALUES (87, 'GP', 'Guadeloupe', 1, NULL, NULL);
INSERT INTO `countries` VALUES (88, 'GU', 'Guam', 1, NULL, NULL);
INSERT INTO `countries` VALUES (89, 'GT', 'Guatemala', 1, NULL, NULL);
INSERT INTO `countries` VALUES (90, 'GN', 'Guinea', 1, NULL, NULL);
INSERT INTO `countries` VALUES (91, 'GW', 'Guinea-Bissau', 1, NULL, NULL);
INSERT INTO `countries` VALUES (92, 'GY', 'Guyana', 1, NULL, NULL);
INSERT INTO `countries` VALUES (93, 'HT', 'Haiti', 1, NULL, NULL);
INSERT INTO `countries` VALUES (94, 'HM', 'Heard and Mc Donald Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (95, 'HN', 'Honduras', 1, NULL, NULL);
INSERT INTO `countries` VALUES (96, 'HK', 'Hong Kong', 1, NULL, NULL);
INSERT INTO `countries` VALUES (97, 'HU', 'Hungary', 1, NULL, NULL);
INSERT INTO `countries` VALUES (98, 'IS', 'Iceland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (99, 'IN', 'India', 1, NULL, NULL);
INSERT INTO `countries` VALUES (100, 'IM', 'Isle of Man', 1, NULL, NULL);
INSERT INTO `countries` VALUES (101, 'ID', 'Indonesia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (102, 'IR', 'Iran (Islamic Republic of)', 1, NULL, NULL);
INSERT INTO `countries` VALUES (103, 'IQ', 'Iraq', 1, NULL, NULL);
INSERT INTO `countries` VALUES (104, 'IE', 'Ireland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (105, 'IL', 'Israel', 1, NULL, NULL);
INSERT INTO `countries` VALUES (106, 'IT', 'Italy', 1, NULL, NULL);
INSERT INTO `countries` VALUES (107, 'CI', 'Ivory Coast', 1, NULL, NULL);
INSERT INTO `countries` VALUES (108, 'JE', 'Jersey', 1, NULL, NULL);
INSERT INTO `countries` VALUES (109, 'JM', 'Jamaica', 1, NULL, NULL);
INSERT INTO `countries` VALUES (110, 'JP', 'Japan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (111, 'JO', 'Jordan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (112, 'KZ', 'Kazakhstan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (113, 'KE', 'Kenya', 1, NULL, NULL);
INSERT INTO `countries` VALUES (114, 'KI', 'Kiribati', 1, NULL, NULL);
INSERT INTO `countries` VALUES (115, 'KP', 'Korea, Democratic People\'s Republic of', 1, NULL, NULL);
INSERT INTO `countries` VALUES (116, 'KR', 'Korea, Republic of', 1, NULL, NULL);
INSERT INTO `countries` VALUES (117, 'XK', 'Kosovo', 1, NULL, NULL);
INSERT INTO `countries` VALUES (118, 'KW', 'Kuwait', 1, NULL, NULL);
INSERT INTO `countries` VALUES (119, 'KG', 'Kyrgyzstan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (120, 'LA', 'Lao People\'s Democratic Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (121, 'LV', 'Latvia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (122, 'LB', 'Lebanon', 1, NULL, NULL);
INSERT INTO `countries` VALUES (123, 'LS', 'Lesotho', 1, NULL, NULL);
INSERT INTO `countries` VALUES (124, 'LR', 'Liberia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (125, 'LY', 'Libyan Arab Jamahiriya', 1, NULL, NULL);
INSERT INTO `countries` VALUES (126, 'LI', 'Liechtenstein', 1, NULL, NULL);
INSERT INTO `countries` VALUES (127, 'LT', 'Lithuania', 1, NULL, NULL);
INSERT INTO `countries` VALUES (128, 'LU', 'Luxembourg', 1, NULL, NULL);
INSERT INTO `countries` VALUES (129, 'MO', 'Macau', 1, NULL, NULL);
INSERT INTO `countries` VALUES (130, 'MK', 'Macedonia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (131, 'MG', 'Madagascar', 1, NULL, NULL);
INSERT INTO `countries` VALUES (132, 'MW', 'Malawi', 1, NULL, NULL);
INSERT INTO `countries` VALUES (133, 'MY', 'Malaysia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (134, 'MV', 'Maldives', 1, NULL, NULL);
INSERT INTO `countries` VALUES (135, 'ML', 'Mali', 1, NULL, NULL);
INSERT INTO `countries` VALUES (136, 'MT', 'Malta', 1, NULL, NULL);
INSERT INTO `countries` VALUES (137, 'MH', 'Marshall Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (138, 'MQ', 'Martinique', 1, NULL, NULL);
INSERT INTO `countries` VALUES (139, 'MR', 'Mauritania', 1, NULL, NULL);
INSERT INTO `countries` VALUES (140, 'MU', 'Mauritius', 1, NULL, NULL);
INSERT INTO `countries` VALUES (141, 'TY', 'Mayotte', 1, NULL, NULL);
INSERT INTO `countries` VALUES (142, 'MX', 'Mexico', 1, NULL, NULL);
INSERT INTO `countries` VALUES (143, 'FM', 'Micronesia, Federated States of', 1, NULL, NULL);
INSERT INTO `countries` VALUES (144, 'MD', 'Moldova, Republic of', 1, NULL, NULL);
INSERT INTO `countries` VALUES (145, 'MC', 'Monaco', 1, NULL, NULL);
INSERT INTO `countries` VALUES (146, 'MN', 'Mongolia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (147, 'ME', 'Montenegro', 1, NULL, NULL);
INSERT INTO `countries` VALUES (148, 'MS', 'Montserrat', 1, NULL, NULL);
INSERT INTO `countries` VALUES (149, 'MA', 'Morocco', 1, NULL, NULL);
INSERT INTO `countries` VALUES (150, 'MZ', 'Mozambique', 1, NULL, NULL);
INSERT INTO `countries` VALUES (151, 'MM', 'Myanmar', 1, NULL, NULL);
INSERT INTO `countries` VALUES (152, 'NA', 'Namibia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (153, 'NR', 'Nauru', 1, NULL, NULL);
INSERT INTO `countries` VALUES (154, 'NP', 'Nepal', 1, NULL, NULL);
INSERT INTO `countries` VALUES (155, 'NL', 'Netherlands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (156, 'AN', 'Netherlands Antilles', 1, NULL, NULL);
INSERT INTO `countries` VALUES (157, 'NC', 'New Caledonia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (158, 'NZ', 'New Zealand', 1, NULL, NULL);
INSERT INTO `countries` VALUES (159, 'NI', 'Nicaragua', 1, NULL, NULL);
INSERT INTO `countries` VALUES (160, 'NE', 'Niger', 1, NULL, NULL);
INSERT INTO `countries` VALUES (161, 'NG', 'Nigeria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (162, 'NU', 'Niue', 1, NULL, NULL);
INSERT INTO `countries` VALUES (163, 'NF', 'Norfolk Island', 1, NULL, NULL);
INSERT INTO `countries` VALUES (164, 'MP', 'Northern Mariana Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (165, 'NO', 'Norway', 1, NULL, NULL);
INSERT INTO `countries` VALUES (166, 'OM', 'Oman', 1, NULL, NULL);
INSERT INTO `countries` VALUES (167, 'PK', 'Pakistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (168, 'PW', 'Palau', 1, NULL, NULL);
INSERT INTO `countries` VALUES (169, 'PS', 'Palestine', 1, NULL, NULL);
INSERT INTO `countries` VALUES (170, 'PA', 'Panama', 1, NULL, NULL);
INSERT INTO `countries` VALUES (171, 'PG', 'Papua New Guinea', 1, NULL, NULL);
INSERT INTO `countries` VALUES (172, 'PY', 'Paraguay', 1, NULL, NULL);
INSERT INTO `countries` VALUES (173, 'PE', 'Peru', 1, NULL, NULL);
INSERT INTO `countries` VALUES (174, 'PH', 'Philippines', 1, NULL, NULL);
INSERT INTO `countries` VALUES (175, 'PN', 'Pitcairn', 1, NULL, NULL);
INSERT INTO `countries` VALUES (176, 'PL', 'Poland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (177, 'PT', 'Portugal', 1, NULL, NULL);
INSERT INTO `countries` VALUES (178, 'PR', 'Puerto Rico', 1, NULL, NULL);
INSERT INTO `countries` VALUES (179, 'QA', 'Qatar', 1, NULL, NULL);
INSERT INTO `countries` VALUES (180, 'RE', 'Reunion', 1, NULL, NULL);
INSERT INTO `countries` VALUES (181, 'RO', 'Romania', 1, NULL, NULL);
INSERT INTO `countries` VALUES (182, 'RU', 'Russian Federation', 1, NULL, NULL);
INSERT INTO `countries` VALUES (183, 'RW', 'Rwanda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (184, 'KN', 'Saint Kitts and Nevis', 1, NULL, NULL);
INSERT INTO `countries` VALUES (185, 'LC', 'Saint Lucia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (186, 'VC', 'Saint Vincent and the Grenadines', 1, NULL, NULL);
INSERT INTO `countries` VALUES (187, 'WS', 'Samoa', 1, NULL, NULL);
INSERT INTO `countries` VALUES (188, 'SM', 'San Marino', 1, NULL, NULL);
INSERT INTO `countries` VALUES (189, 'ST', 'Sao Tome and Principe', 1, NULL, NULL);
INSERT INTO `countries` VALUES (190, 'SA', 'Saudi Arabia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (191, 'SN', 'Senegal', 1, NULL, NULL);
INSERT INTO `countries` VALUES (192, 'RS', 'Serbia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (193, 'SC', 'Seychelles', 1, NULL, NULL);
INSERT INTO `countries` VALUES (194, 'SL', 'Sierra Leone', 1, NULL, NULL);
INSERT INTO `countries` VALUES (195, 'SG', 'Singapore', 1, NULL, NULL);
INSERT INTO `countries` VALUES (196, 'SK', 'Slovakia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (197, 'SI', 'Slovenia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (198, 'SB', 'Solomon Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (199, 'SO', 'Somalia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (200, 'ZA', 'South Africa', 1, NULL, NULL);
INSERT INTO `countries` VALUES (201, 'GS', 'South Georgia South Sandwich Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (202, 'SS', 'South Sudan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (203, 'ES', 'Spain', 1, NULL, NULL);
INSERT INTO `countries` VALUES (204, 'LK', 'Sri Lanka', 1, NULL, NULL);
INSERT INTO `countries` VALUES (205, 'SH', 'St. Helena', 1, NULL, NULL);
INSERT INTO `countries` VALUES (206, 'PM', 'St. Pierre and Miquelon', 1, NULL, NULL);
INSERT INTO `countries` VALUES (207, 'SD', 'Sudan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (208, 'SR', 'Suriname', 1, NULL, NULL);
INSERT INTO `countries` VALUES (209, 'SJ', 'Svalbard and Jan Mayen Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (210, 'SZ', 'Swaziland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (211, 'SE', 'Sweden', 1, NULL, NULL);
INSERT INTO `countries` VALUES (212, 'CH', 'Switzerland', 1, NULL, NULL);
INSERT INTO `countries` VALUES (213, 'SY', 'Syrian Arab Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (214, 'TW', 'Taiwan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (215, 'TJ', 'Tajikistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (216, 'TZ', 'Tanzania, United Republic of', 1, NULL, NULL);
INSERT INTO `countries` VALUES (217, 'TH', 'Thailand', 1, NULL, NULL);
INSERT INTO `countries` VALUES (218, 'TG', 'Togo', 1, NULL, NULL);
INSERT INTO `countries` VALUES (219, 'TK', 'Tokelau', 1, NULL, NULL);
INSERT INTO `countries` VALUES (220, 'TO', 'Tonga', 1, NULL, NULL);
INSERT INTO `countries` VALUES (221, 'TT', 'Trinidad and Tobago', 1, NULL, NULL);
INSERT INTO `countries` VALUES (222, 'TN', 'Tunisia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (223, 'TR', 'Turkey', 1, NULL, NULL);
INSERT INTO `countries` VALUES (224, 'TM', 'Turkmenistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (225, 'TC', 'Turks and Caicos Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (226, 'TV', 'Tuvalu', 1, NULL, NULL);
INSERT INTO `countries` VALUES (227, 'UG', 'Uganda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (228, 'UA', 'Ukraine', 1, NULL, NULL);
INSERT INTO `countries` VALUES (229, 'AE', 'United Arab Emirates', 1, NULL, NULL);
INSERT INTO `countries` VALUES (230, 'GB', 'United Kingdom', 1, NULL, NULL);
INSERT INTO `countries` VALUES (231, 'US', 'United States', 1, NULL, NULL);
INSERT INTO `countries` VALUES (232, 'UM', 'United States minor outlying islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (233, 'UY', 'Uruguay', 1, NULL, NULL);
INSERT INTO `countries` VALUES (234, 'UZ', 'Uzbekistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (235, 'VU', 'Vanuatu', 1, NULL, NULL);
INSERT INTO `countries` VALUES (236, 'VA', 'Vatican City State', 1, NULL, NULL);
INSERT INTO `countries` VALUES (237, 'VE', 'Venezuela', 1, NULL, NULL);
INSERT INTO `countries` VALUES (238, 'VN', 'Vietnam', 1, NULL, NULL);
INSERT INTO `countries` VALUES (239, 'VG', 'Virgin Islands (British)', 1, NULL, NULL);
INSERT INTO `countries` VALUES (240, 'VI', 'Virgin Islands (U.S.)', 1, NULL, NULL);
INSERT INTO `countries` VALUES (241, 'WF', 'Wallis and Futuna Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (242, 'EH', 'Western Sahara', 1, NULL, NULL);
INSERT INTO `countries` VALUES (243, 'YE', 'Yemen', 1, NULL, NULL);
INSERT INTO `countries` VALUES (244, 'ZR', 'Zaire', 1, NULL, NULL);
INSERT INTO `countries` VALUES (245, 'ZM', 'Zambia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (246, 'ZW', 'Zimbabwe', 1, NULL, NULL);
INSERT INTO `countries` VALUES (247, 'AF', 'Afghanistan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (248, 'AL', 'Albania', 1, NULL, NULL);
INSERT INTO `countries` VALUES (249, 'DZ', 'Algeria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (250, 'DS', 'American Samoa', 1, NULL, NULL);
INSERT INTO `countries` VALUES (251, 'AD', 'Andorra', 1, NULL, NULL);
INSERT INTO `countries` VALUES (252, 'AO', 'Angola', 1, NULL, NULL);
INSERT INTO `countries` VALUES (253, 'AI', 'Anguilla', 1, NULL, NULL);
INSERT INTO `countries` VALUES (254, 'AQ', 'Antarctica', 1, NULL, NULL);
INSERT INTO `countries` VALUES (255, 'AG', 'Antigua and Barbuda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (256, 'AR', 'Argentina', 1, NULL, NULL);
INSERT INTO `countries` VALUES (257, 'AM', 'Armenia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (258, 'AW', 'Aruba', 1, NULL, NULL);
INSERT INTO `countries` VALUES (259, 'AU', 'Australia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (260, 'AT', 'Austria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (261, 'AZ', 'Azerbaijan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (262, 'BS', 'Bahamas', 1, NULL, NULL);
INSERT INTO `countries` VALUES (263, 'BH', 'Bahrain', 1, NULL, NULL);
INSERT INTO `countries` VALUES (264, 'BD', 'Bangladesh', 1, NULL, NULL);
INSERT INTO `countries` VALUES (265, 'BB', 'Barbados', 1, NULL, NULL);
INSERT INTO `countries` VALUES (266, 'BY', 'Belarus', 1, NULL, NULL);
INSERT INTO `countries` VALUES (267, 'BE', 'Belgium', 1, NULL, NULL);
INSERT INTO `countries` VALUES (268, 'BZ', 'Belize', 1, NULL, NULL);
INSERT INTO `countries` VALUES (269, 'BJ', 'Benin', 1, NULL, NULL);
INSERT INTO `countries` VALUES (270, 'BM', 'Bermuda', 1, NULL, NULL);
INSERT INTO `countries` VALUES (271, 'BT', 'Bhutan', 1, NULL, NULL);
INSERT INTO `countries` VALUES (272, 'BO', 'Bolivia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (273, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL);
INSERT INTO `countries` VALUES (274, 'BW', 'Botswana', 1, NULL, NULL);
INSERT INTO `countries` VALUES (275, 'BV', 'Bouvet Island', 1, NULL, NULL);
INSERT INTO `countries` VALUES (276, 'BR', 'Brazil', 1, NULL, NULL);
INSERT INTO `countries` VALUES (277, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL);
INSERT INTO `countries` VALUES (278, 'BN', 'Brunei Darussalam', 1, NULL, NULL);
INSERT INTO `countries` VALUES (279, 'BG', 'Bulgaria', 1, NULL, NULL);
INSERT INTO `countries` VALUES (280, 'BF', 'Burkina Faso', 1, NULL, NULL);
INSERT INTO `countries` VALUES (281, 'BI', 'Burundi', 1, NULL, NULL);
INSERT INTO `countries` VALUES (282, 'KH', 'Cambodia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (283, 'CM', 'Cameroon', 1, NULL, NULL);
INSERT INTO `countries` VALUES (284, 'CA', 'Canada', 1, NULL, NULL);
INSERT INTO `countries` VALUES (285, 'CV', 'Cape Verde', 1, NULL, NULL);
INSERT INTO `countries` VALUES (286, 'KY', 'Cayman Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (287, 'CF', 'Central African Republic', 1, NULL, NULL);
INSERT INTO `countries` VALUES (288, 'TD', 'Chad', 1, NULL, NULL);
INSERT INTO `countries` VALUES (289, 'CL', 'Chile', 1, NULL, NULL);
INSERT INTO `countries` VALUES (290, 'CN', 'China', 1, NULL, NULL);
INSERT INTO `countries` VALUES (291, 'CX', 'Christmas Island', 1, NULL, NULL);
INSERT INTO `countries` VALUES (292, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL);
INSERT INTO `countries` VALUES (293, 'CO', 'Colombia', 1, NULL, NULL);
INSERT INTO `countries` VALUES (294, 'KM', 'Comoros', 1, NULL, NULL);
INSERT INTO `countries` VALUES (295, 'CG', 'Congo', 1, NULL, NULL);
INSERT INTO `countries` VALUES (296, 'CK', 'Cook Islands', 1, NULL, NULL);

-- ----------------------------
-- Table structure for coupon_usages
-- ----------------------------
DROP TABLE IF EXISTS `coupon_usages`;
CREATE TABLE `coupon_usages`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `coupon_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of coupon_usages
-- ----------------------------

-- ----------------------------
-- Table structure for coupons
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `discount` double(8, 2) NOT NULL,
  `discount_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_date` int NOT NULL,
  `end_date` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of coupons
-- ----------------------------

-- ----------------------------
-- Table structure for currencies
-- ----------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `exchange_rate` double(10, 5) NOT NULL,
  `status` int NOT NULL DEFAULT 0,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of currencies
-- ----------------------------
INSERT INTO `currencies` VALUES (1, 'تومان', 'تومان', 1.00000, 1, 'IRR', '2020-07-06 05:48:24', '2020-07-06 05:48:52');

-- ----------------------------
-- Table structure for customer_packages
-- ----------------------------
DROP TABLE IF EXISTS `customer_packages`;
CREATE TABLE `customer_packages`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `amount` double(28, 2) NULL DEFAULT NULL,
  `product_upload` int NULL DEFAULT NULL,
  `logo` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customer_packages
-- ----------------------------

-- ----------------------------
-- Table structure for customer_products
-- ----------------------------
DROP TABLE IF EXISTS `customer_products`;
CREATE TABLE `customer_products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `published` int NOT NULL DEFAULT 0,
  `status` int NOT NULL DEFAULT 0,
  `added_by` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `category_id` int NULL DEFAULT NULL,
  `subcategory_id` int NULL DEFAULT NULL,
  `subsubcategory_id` int NULL DEFAULT NULL,
  `brand_id` int NULL DEFAULT NULL,
  `photos` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `thumbnail_img` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `conditon` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `location` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `video_provider` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `video_link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `unit` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `unit_price` double(28, 2) NULL DEFAULT 0.00,
  `meta_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_img` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `pdf` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customer_products
-- ----------------------------

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (4, 8, '2019-08-01 14:05:09', '2019-08-01 14:05:09');
INSERT INTO `customers` VALUES (10, 23, '2026-01-01 21:52:16', '2026-01-01 21:52:16');
INSERT INTO `customers` VALUES (17, 30, '2026-01-01 23:35:30', '2026-01-01 23:35:30');
INSERT INTO `customers` VALUES (18, 31, '2026-01-06 19:02:06', '2026-01-06 19:02:06');

-- ----------------------------
-- Table structure for flash_deal_products
-- ----------------------------
DROP TABLE IF EXISTS `flash_deal_products`;
CREATE TABLE `flash_deal_products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `flash_deal_id` int NOT NULL,
  `product_id` int NOT NULL,
  `discount` double(8, 2) NULL DEFAULT 0.00,
  `discount_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of flash_deal_products
-- ----------------------------
INSERT INTO `flash_deal_products` VALUES (41, 8, 1, 0.00, 'amount', '2020-07-20 03:44:44', '2020-07-20 03:44:44');
INSERT INTO `flash_deal_products` VALUES (42, 8, 2, 0.00, 'amount', '2020-07-20 03:44:44', '2020-07-20 03:44:44');
INSERT INTO `flash_deal_products` VALUES (51, 7, 1, 90000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (52, 7, 6, 6000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (53, 7, 9, 12000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (54, 7, 13, 49000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (55, 7, 15, 300000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (56, 7, 17, 50000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (57, 7, 21, 10000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');
INSERT INTO `flash_deal_products` VALUES (58, 7, 23, 200000.00, 'amount', '2020-08-01 13:32:33', '2020-08-01 13:32:33');

-- ----------------------------
-- Table structure for flash_deals
-- ----------------------------
DROP TABLE IF EXISTS `flash_deals`;
CREATE TABLE `flash_deals`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `start_date` int NULL DEFAULT NULL,
  `end_date` int NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT 0,
  `featured` int NOT NULL DEFAULT 0,
  `background_color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `text_color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `banner` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of flash_deals
-- ----------------------------
INSERT INTO `flash_deals` VALUES (7, 'پیشنهاد شگفت انگیز', 1595376000, 1597968000, 1, 1, '#FFFFFF', 'dark', 'uploads/offers/banner/t4uuiqxx8KUq1bRFZuyXLfQraUdEEpTu5PV97h5V.jpeg', 'پۜشنهاد-شگفت-انگۜز-djzvw', '2020-07-22 06:35:16', '2020-08-01 13:32:33');
INSERT INTO `flash_deals` VALUES (8, 'تست', 1595236114, 1595322529, 0, 0, '#fff', 'dark', 'uploads/offers/banner/fwrjKaUqlS7kJlQdxy3gUznU3RIxL51ZcfjqkCB0.jpeg', 'تست-jxqbv', '2020-07-20 03:44:44', '2020-07-20 03:44:44');

-- ----------------------------
-- Table structure for general_settings
-- ----------------------------
DROP TABLE IF EXISTS `general_settings`;
CREATE TABLE `general_settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `frontend_color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_login_background` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_login_sidebar` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `favicon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `site_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `facebook` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `instagram` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `twitter` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `youtube` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `google_plus` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of general_settings
-- ----------------------------
INSERT INTO `general_settings` VALUES (1, '2', 'uploads/logo/pfdIuiMeXGkDAIpPEUrvUCbQrOHu484nbGfz77zB.png', 'uploads/admin_logo/wCgHrz0Q5QoL1yu4vdrNnQIr4uGuNL48CXfcxOuS.png', NULL, NULL, 'uploads/favicon/uHdGidSaRVzvPgDj6JFtntMqzJkwDk9659233jrb.png', 'پلتفرم اختصاصی طلا و جواهرات', 'تهران خیابان جمهوری', 'کلیه حقوق محفوظ و متعلق به سامانه دریک سرویس میباشد', '09104927131', 'edeban.almas@gmail.com', 'https://www.facebook.com', 'https://www.instagram.com', 'https://www.twitter.com', 'https://www.youtube.com', 'https://www.googleplus.com', '2025-12-22 15:33:35', '2025-12-22 12:03:35');

-- ----------------------------
-- Table structure for home_categories
-- ----------------------------
DROP TABLE IF EXISTS `home_categories`;
CREATE TABLE `home_categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `subsubcategories` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of home_categories
-- ----------------------------
INSERT INTO `home_categories` VALUES (3, 7, 'null', 1, '2020-08-01 16:29:17', '2020-08-01 16:29:17');

-- ----------------------------
-- Table structure for jewelry_certificates
-- ----------------------------
DROP TABLE IF EXISTS `jewelry_certificates`;
CREATE TABLE `jewelry_certificates`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` int UNSIGNED NOT NULL,
  `product_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metal_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purity` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `weight` decimal(10, 2) NULL DEFAULT NULL,
  `stone_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `serial_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` date NULL DEFAULT NULL,
  `certificate_file` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_verified` tinyint(1) NULL DEFAULT 0,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `serial_number`(`serial_number` ASC) USING BTREE,
  INDEX `owner_id`(`owner_id` ASC) USING BTREE,
  CONSTRAINT `jewelry_certificates_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `jewelry_owners` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jewelry_certificates
-- ----------------------------
INSERT INTO `jewelry_certificates` VALUES (1, 4, 'انگشتر طلای مصری', 'طلا', '18', 100.00, NULL, '202020', '2026-02-02', 'jewelry-certificates/hX14111HOQ24K6MF3q0p4DGCRjHg8qnBD5v7SXCp.jpg', NULL, 0, NULL, '2026-02-02 19:45:33', '2026-02-04 23:12:28');
INSERT INTO `jewelry_certificates` VALUES (2, 5, 'دستبند پلاتین', 'پلاتین', '26', 40.00, 'سایر', '202210', '2026-02-02', 'jewelry-certificates/pCZppsVdq5XoLlqe18qNFtadREogR3uN7CQHvI5B.jpg', 'این یک دستبند عتیقه هست', 0, NULL, '2026-02-02 20:11:22', '2026-02-04 23:36:38');
INSERT INTO `jewelry_certificates` VALUES (3, 3, 'گردنبند پلاتین نقره ای زنانه', 'پلاتین', '26', 10.00, 'الماس', '042101010', '2026-02-04', 'jewelry-certificates/hTiFsHoN8yhoSB7vB7CRYHvMPDobLeRcuz8gZ1TK.jpg', 'این گردنبند عتیقه هست', 0, NULL, '2026-02-04 23:17:10', '2026-02-04 23:17:10');

-- ----------------------------
-- Table structure for jewelry_owners
-- ----------------------------
DROP TABLE IF EXISTS `jewelry_owners`;
CREATE TABLE `jewelry_owners`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `full_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `jewelry_owners_user_id_national_id_unique`(`user_id` ASC, `national_id` ASC) USING BTREE,
  INDEX `jewelry_owners_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jewelry_owners
-- ----------------------------

-- ----------------------------
-- Table structure for jewelry_transfers
-- ----------------------------
DROP TABLE IF EXISTS `jewelry_transfers`;
CREATE TABLE `jewelry_transfers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `certificate_id` int UNSIGNED NOT NULL,
  `from_owner_id` int UNSIGNED NOT NULL,
  `to_owner_id` int UNSIGNED NOT NULL,
  `transfer_date` date NULL DEFAULT NULL,
  `transfer_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jewelry_transfers
-- ----------------------------

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rtl` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES (1, 'فارسی', 'ir', 1, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `languages` VALUES (2, 'English', 'en', 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');

-- ----------------------------
-- Table structure for links
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of links
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026_09_07_100001_create_drikana_core_ecommerce_tables', 1);
INSERT INTO `migrations` VALUES (2, '2026_09_07_100002_align_seo_settings_columns', 2);
INSERT INTO `migrations` VALUES (3, '2014_10_12_000000_create_users_table', 3);
INSERT INTO `migrations` VALUES (4, '2026_02_16_151319_create_missing_reports_table', 3);
INSERT INTO `migrations` VALUES (5, '2026_02_16_165127_create_repair_status_histories_table', 3);
INSERT INTO `migrations` VALUES (6, '2026_02_16_165329_create_repair_orders_table', 3);
INSERT INTO `migrations` VALUES (7, '2026_02_16_171033_add_role_to_users_table', 3);
INSERT INTO `migrations` VALUES (8, '2026_02_16_183253_fix_repair_status_histories_foreign_keys', 3);
INSERT INTO `migrations` VALUES (9, '2026_02_16_202821_create_repair_technicians_table', 3);
INSERT INTO `migrations` VALUES (10, '2026_09_07_100000_add_ecommerce_fields_to_users_table', 3);
INSERT INTO `migrations` VALUES (11, '2014_10_12_100000_create_password_resets_table', 4);
INSERT INTO `migrations` VALUES (12, '2016_06_01_000001_create_oauth_auth_codes_table', 4);
INSERT INTO `migrations` VALUES (13, '2016_06_01_000002_create_oauth_access_tokens_table', 4);
INSERT INTO `migrations` VALUES (14, '2016_06_01_000003_create_oauth_refresh_tokens_table', 4);
INSERT INTO `migrations` VALUES (15, '2016_06_01_000004_create_oauth_clients_table', 4);
INSERT INTO `migrations` VALUES (16, '2016_06_01_000005_create_oauth_personal_access_clients_table', 4);
INSERT INTO `migrations` VALUES (17, '2026_09_07_100003_create_jewelry_owners_table', 5);

-- ----------------------------
-- Table structure for missing_reports
-- ----------------------------
DROP TABLE IF EXISTS `missing_reports`;
CREATE TABLE `missing_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `jewelry_id` bigint UNSIGNED NOT NULL,
  `type` enum('theft','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'سرقت یا مفقودی',
  `location` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','found') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of missing_reports
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NULL DEFAULT NULL,
  `client_id` int UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_access_tokens_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_auth_codes
-- ----------------------------
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `client_id` int UNSIGNED NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_auth_codes
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint NULL DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_clients_user_id_index`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_clients
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_personal_access_clients
-- ----------------------------
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_personal_access_clients_client_id_index`(`client_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_personal_access_clients
-- ----------------------------

-- ----------------------------
-- Table structure for oauth_refresh_tokens
-- ----------------------------
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `oauth_refresh_tokens_access_token_id_index`(`access_token_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of oauth_refresh_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `seller_id` int UNSIGNED NULL DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `variation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `price` double(20, 2) NOT NULL DEFAULT 0.00,
  `tax` double(20, 2) NOT NULL DEFAULT 0.00,
  `shipping_cost` double(20, 2) NOT NULL DEFAULT 0.00,
  `quantity` int NOT NULL DEFAULT 1,
  `delivery_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of order_details
-- ----------------------------

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NULL DEFAULT NULL,
  `seller_id` int UNSIGNED NULL DEFAULT NULL,
  `payment_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_status` tinyint NOT NULL DEFAULT 0,
  `payment_status_string` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `delivery_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home_delivery',
  `grand_total` double(20, 2) NOT NULL DEFAULT 0.00,
  `coupon_discount` double(20, 2) NOT NULL DEFAULT 0.00,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `pickup_point_id` int NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `viewed` int NOT NULL DEFAULT 0,
  `delivery_viewed` int NOT NULL DEFAULT 0,
  `payment_status_viewed` int NOT NULL DEFAULT 0,
  `commission_calculated` tinyint NOT NULL DEFAULT 0,
  `trx_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` int NULL DEFAULT NULL,
  `amount` double(20, 2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'due',
  `payment_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `txn_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payments
-- ----------------------------

-- ----------------------------
-- Table structure for policies
-- ----------------------------
DROP TABLE IF EXISTS `policies`;
CREATE TABLE `policies`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of policies
-- ----------------------------

-- ----------------------------
-- Table structure for product_stocks
-- ----------------------------
DROP TABLE IF EXISTS `product_stocks`;
CREATE TABLE `product_stocks`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `variant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `price` double(20, 2) NOT NULL DEFAULT 0.00,
  `qty` int NOT NULL DEFAULT 0,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_stocks
-- ----------------------------
INSERT INTO `product_stocks` VALUES (2, 61, 'LightPink', NULL, 850000000.00, 10, NULL, '2026-09-09 13:29:35', '2026-09-09 13:29:35');
INSERT INTO `product_stocks` VALUES (4, 63, 'LightPink', NULL, 450000000.00, 10, NULL, '2026-09-09 13:36:34', '2026-09-09 13:36:34');
INSERT INTO `product_stocks` VALUES (5, 63, 'Silver', NULL, 450000000.00, 10, NULL, '2026-09-09 13:36:34', '2026-09-09 13:36:34');
INSERT INTO `product_stocks` VALUES (6, 64, 'Blue', NULL, 580000000.00, 10, NULL, '2026-09-09 13:37:41', '2026-09-09 13:37:41');
INSERT INTO `product_stocks` VALUES (7, 64, 'Silver', NULL, 580000000.00, 10, NULL, '2026-09-09 13:37:41', '2026-09-09 13:37:41');
INSERT INTO `product_stocks` VALUES (8, 65, 'Blue', NULL, 590000000.00, 10, NULL, '2026-09-09 13:40:16', '2026-09-09 13:40:16');
INSERT INTO `product_stocks` VALUES (9, 65, 'Silver', NULL, 590000000.00, 10, NULL, '2026-09-09 13:40:16', '2026-09-09 13:40:16');
INSERT INTO `product_stocks` VALUES (10, 66, 'Blue', NULL, 980000000.00, 10, NULL, '2026-09-09 13:46:04', '2026-09-09 13:46:04');
INSERT INTO `product_stocks` VALUES (11, 66, 'Gold', NULL, 980000000.00, 10, NULL, '2026-09-09 13:46:04', '2026-09-09 13:46:04');
INSERT INTO `product_stocks` VALUES (12, 67, 'Green', NULL, 750000000.00, 10, NULL, '2026-09-09 13:47:05', '2026-09-09 13:47:05');
INSERT INTO `product_stocks` VALUES (13, 67, 'Silver', NULL, 750000000.00, 10, NULL, '2026-09-09 13:47:05', '2026-09-09 13:47:05');
INSERT INTO `product_stocks` VALUES (14, 68, 'Gold', NULL, 820000000.00, 10, NULL, '2026-09-09 13:47:54', '2026-09-09 13:47:54');
INSERT INTO `product_stocks` VALUES (15, 68, 'Silver', NULL, 820000000.00, 10, NULL, '2026-09-09 13:47:54', '2026-09-09 13:47:54');
INSERT INTO `product_stocks` VALUES (16, 69, 'Chocolate', NULL, 880000000.00, 10, NULL, '2026-09-09 13:48:53', '2026-09-09 13:48:53');
INSERT INTO `product_stocks` VALUES (17, 69, 'LightPink', NULL, 880000000.00, 10, NULL, '2026-09-09 13:48:53', '2026-09-09 13:48:53');
INSERT INTO `product_stocks` VALUES (18, 70, 'Silver', NULL, 65000000.00, 10, NULL, '2026-09-09 13:50:03', '2026-09-09 13:50:03');
INSERT INTO `product_stocks` VALUES (19, 72, 'Black', NULL, 55000000.00, 10, NULL, '2026-09-09 13:50:46', '2026-09-09 13:50:46');
INSERT INTO `product_stocks` VALUES (20, 72, 'Gold', NULL, 55000000.00, 10, NULL, '2026-09-09 13:50:46', '2026-09-09 13:50:46');
INSERT INTO `product_stocks` VALUES (21, 73, 'Silver', NULL, 38000000.00, 10, NULL, '2026-09-09 13:52:38', '2026-09-09 13:52:38');
INSERT INTO `product_stocks` VALUES (22, 73, 'White', NULL, 38000000.00, 10, NULL, '2026-09-09 13:52:38', '2026-09-09 13:52:38');
INSERT INTO `product_stocks` VALUES (24, 75, 'LightPink', NULL, 35000000.00, 10, NULL, '2026-09-09 13:54:14', '2026-09-09 13:54:14');
INSERT INTO `product_stocks` VALUES (25, 75, 'Silver', NULL, 35000000.00, 10, NULL, '2026-09-09 13:54:14', '2026-09-09 13:54:14');
INSERT INTO `product_stocks` VALUES (26, 76, 'Black', NULL, 62000000.00, 10, NULL, '2026-09-09 13:55:41', '2026-09-09 13:55:41');
INSERT INTO `product_stocks` VALUES (27, 77, 'Black', NULL, 72000000.00, 10, NULL, '2026-09-09 13:56:33', '2026-09-09 13:56:33');
INSERT INTO `product_stocks` VALUES (28, 77, 'Gold', NULL, 72000000.00, 10, NULL, '2026-09-09 13:56:33', '2026-09-09 13:56:33');
INSERT INTO `product_stocks` VALUES (29, 78, 'Black', NULL, 60000000.00, 10, NULL, '2026-09-09 13:57:30', '2026-09-09 13:57:30');
INSERT INTO `product_stocks` VALUES (30, 79, 'Black', NULL, 85000000.00, 10, NULL, '2026-09-09 13:58:22', '2026-09-09 13:58:22');
INSERT INTO `product_stocks` VALUES (31, 79, 'Gold', NULL, 85000000.00, 10, NULL, '2026-09-09 13:58:22', '2026-09-09 13:58:22');
INSERT INTO `product_stocks` VALUES (32, 81, 'Silver', NULL, 28000000.00, 10, NULL, '2026-09-09 13:59:24', '2026-09-09 13:59:24');
INSERT INTO `product_stocks` VALUES (33, 81, 'White', NULL, 28000000.00, 10, NULL, '2026-09-09 13:59:24', '2026-09-09 13:59:24');
INSERT INTO `product_stocks` VALUES (34, 80, 'Gold', NULL, 95000000.00, 10, NULL, '2026-09-09 14:00:14', '2026-09-09 14:00:14');
INSERT INTO `product_stocks` VALUES (35, 80, 'SaddleBrown', NULL, 95000000.00, 10, NULL, '2026-09-09 14:00:14', '2026-09-09 14:00:14');
INSERT INTO `product_stocks` VALUES (36, 82, 'White', NULL, 48000000.00, 10, NULL, '2026-09-09 14:01:35', '2026-09-09 14:01:35');
INSERT INTO `product_stocks` VALUES (37, 8, 'White', NULL, 19500000.00, 10, NULL, '2026-09-09 14:02:58', '2026-09-09 14:02:58');
INSERT INTO `product_stocks` VALUES (38, 28, 'White', NULL, 16500000.00, 10, NULL, '2026-09-09 14:04:13', '2026-09-09 14:04:13');
INSERT INTO `product_stocks` VALUES (40, 62, 'Silver', NULL, 120000000.00, 10, NULL, '2026-09-09 14:10:03', '2026-09-09 14:10:03');
INSERT INTO `product_stocks` VALUES (41, 74, 'White', NULL, 42000000.00, 10, NULL, '2026-09-09 14:11:50', '2026-09-09 14:11:50');
INSERT INTO `product_stocks` VALUES (42, 83, 'White', NULL, 38000000.00, 10, NULL, '2026-09-09 14:14:49', '2026-09-09 14:14:49');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `added_by` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin',
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `subsubcategory_id` int NULL DEFAULT NULL,
  `brand_id` int NULL DEFAULT NULL,
  `photos` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `thumbnail_img` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `featured_img` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `flash_deal_img` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `video_provider` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `video_link` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `tags` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `unit_price` double(12, 0) NOT NULL,
  `purchase_price` double(12, 0) NOT NULL,
  `variant_product` int NOT NULL DEFAULT 0,
  `attributes` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '[]',
  `choice_options` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `colors` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `variations` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `todays_deal` int NOT NULL DEFAULT 0,
  `published` int NOT NULL DEFAULT 1,
  `featured` int NOT NULL DEFAULT 0,
  `current_stock` int NOT NULL DEFAULT 0,
  `unit` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `discount` double(8, 2) NULL DEFAULT NULL,
  `discount_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `tax` double(8, 2) NULL DEFAULT NULL,
  `tax_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `shipping_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'flat_rate',
  `shipping_cost` double(8, 2) NULL DEFAULT 0.00,
  `num_of_sale` int NOT NULL DEFAULT 0,
  `meta_title` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `meta_description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `meta_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `pdf` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `slug` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rating` double(8, 2) NOT NULL DEFAULT 0.00,
  `barcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `digital` int NOT NULL DEFAULT 0,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 84 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'انگشتر نامزدی آکوامارین هاله الماس | مدل 17830', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/aquamarine-diamond-halo-engagement-floral-ring__17830.jpg\"]', 'uploads/products/thumbnail/aquamarine-diamond-halo-engagement-floral-ring__17830.jpg', 'uploads/products/featured/aquamarine-diamond-halo-engagement-floral-ring__17830.jpg', 'uploads/products/flash_deal/aquamarine-diamond-halo-engagement-floral-ring__17830.jpg', 'youtube', NULL, 'آکوامارین, هاله الماس, انگشتر نامزدی, مدل 17830', 'انگشتر نامزدی با سنگ مرکزی آکوامارین بیضی شکل 1.5 قیراطی و هاله‌ای از الماس‌های برلیان دور آن. طراحی گل‌مانند با ظرافت کامل. قاب از طلای سفید 18 عیار.', 12500000, 9800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'عدد', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین هاله الماس مدل 17830', 'خرید انگشتر نامزدی آکوامارین 1.5 قیراطی با هاله الماس و قاب طلای سفید 18 عیار', 'uploads/products/meta/aquamarine-diamond-halo-engagement-floral-ring__17830.jpg', NULL, 'angoshtar-akvamarin-hale-almas-17830', 0.00, '17830', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (2, 'انگشتر نامزدی آکوامارین وینتیج | مدل 82099', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/aquamarine-diamond-vintage-engagement-ring__82099.jpg\"]', 'uploads/products/thumbnail/aquamarine-diamond-vintage-engagement-ring__82099.jpg', 'uploads/products/featured/aquamarine-diamond-vintage-engagement-ring__82099.jpg', 'uploads/products/flash_deal/aquamarine-diamond-vintage-engagement-ring__82099.jpg', 'youtube', NULL, 'آکوامارین, وینتیج, انگشتر نامزدی, مدل 82099', 'انگشتر نامزدی با سنگ آکوامارین کوسنی 2 قیراطی در قاب طلای رزگلد 18 عیار با حکاکی‌های وینتیج و الماس‌های کوچک در حاشیه.', 18500000, 14200000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 3, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر وینتیج آکوامارین مدل 82099', 'خرید انگشتر نامزدی آکوامارین کوسنی 2 قیراطی با طراحی وینتیج و قاب رزگلد', 'uploads/products/meta/aquamarine-diamond-vintage-engagement-ring__82099.jpg', NULL, 'angoshtar-vintij-akvamarin-82099', 0.00, '82099', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (3, 'انگشتر آکوامارین هاله اسپلیت | مدل 69931', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/aquamarine_diamond_cocktail_halo_split_ring__69931.jpg\"]', 'uploads/products/thumbnail/aquamarine_diamond_cocktail_halo_split_ring__69931.jpg', 'uploads/products/featured/aquamarine_diamond_cocktail_halo_split_ring__69931.jpg', 'uploads/products/flash_deal/aquamarine_diamond_cocktail_halo_split_ring__69931.jpg', 'youtube', NULL, 'آکوامارین, هاله اسپلیت, انگشتر کاکتلی, مدل 69931', 'انگشتر کاکتلی با سنگ آکوامارین گرد 1.8 قیراطی و هاله اسپلیت (شکسته) از الماس‌های ریز. قاب طلای سفید 18 عیار با طراحی مدرن.', 9800000, 7500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 7, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین هاله اسپلیت مدل 69931', 'خرید انگشتر کاکتلی آکوامارین با طراحی مدرن هاله اسپلیت و قاب طلای سفید', 'uploads/products/meta/aquamarine_diamond_cocktail_halo_split_ring__69931.jpg', NULL, 'angoshtar-akvamarin-hale-split-69931', 0.00, '69931', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (4, 'ست انگشتر نامزدی آکوامارین هاله | مدل 61174', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/aquamarine_halo_matching_engagement_ring_set-2__61174.jpg\"]', 'uploads/products/thumbnail/aquamarine_halo_matching_engagement_ring_set-2__61174.jpg', 'uploads/products/featured/aquamarine_halo_matching_engagement_ring_set-2__61174.jpg', 'uploads/products/flash_deal/aquamarine_halo_matching_engagement_ring_set-2__61174.jpg', 'youtube', NULL, 'آکوامارین, ست نامزدی, هاله الماس, مدل 61174', 'ست کامل انگشتر نامزدی و حلقه ازدواج با سنگ آکوامارین بیضی 1.2 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی هماهنگ.', 22000000, 17000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 4, 'ست', 12.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر آکوامارین هاله مدل 61174', 'خرید ست انگشتر نامزدی و حلقه ازدواج آکوامارین با هاله الماس', 'uploads/products/meta/aquamarine_halo_matching_engagement_ring_set-2__61174.jpg', NULL, 'set-angoshtar-akvamarin-hale-61174', 0.00, '61174', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (5, 'انگشتر آکوامارین هاله برجسته کوسنی | مدل 75986', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/blue-cushion-aquamarine-raised-diamond-halo-engagement-ring__75986.jpg\"]', 'uploads/products/thumbnail/blue-cushion-aquamarine-raised-diamond-halo-engagement-ring__75986.jpg', 'uploads/products/featured/blue-cushion-aquamarine-raised-diamond-halo-engagement-ring__75986.jpg', 'uploads/products/flash_deal/blue-cushion-aquamarine-raised-diamond-halo-engagement-ring__75986.jpg', 'youtube', NULL, 'آکوامارین کوسنی, هاله برجسته, انگشتر نامزدی, مدل 75986', 'انگشتر نامزدی با سنگ آکوامارین کوسنی 2.2 قیراطی و هاله الماس برجسته (raised halo) که سنگ را بالاتر از قاب نشان می‌دهد. قاب طلای رزگلد 18 عیار.', 24000000, 18500000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 3, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین کوسنی هاله برجسته مدل 75986', 'خرید انگشتر آکوامارین کوسنی 2.2 قیراطی با هاله الماس برجسته', 'uploads/products/meta/blue-cushion-aquamarine-raised-diamond-halo-engagement-ring__75986.jpg', NULL, 'angoshtar-akvamarin-kosni-hale-barjaste-75986', 0.00, '75986', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (6, 'انگشتر آکوامارین طناب تابیده کوسنی | مدل 28756', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/cushion-aquamarine-twisted-rope-halo-engagement-promise-ring__28756.jpg\"]', 'uploads/products/thumbnail/cushion-aquamarine-twisted-rope-halo-engagement-promise-ring__28756.jpg', 'uploads/products/featured/cushion-aquamarine-twisted-rope-halo-engagement-promise-ring__28756.jpg', 'uploads/products/flash_deal/cushion-aquamarine-twisted-rope-halo-engagement-promise-ring__28756.jpg', 'youtube', NULL, 'آکوامارین کوسنی, طناب تابیده, انگشتر, مدل 28756', 'انگشتر با سنگ آکوامارین کوسنی 1.8 قیراطی و هاله الماس با قاب طناب تابیده (twisted rope) از طلای زرد 18 عیار. طراحی رمانتیک و کلاسیک.', 16500000, 12500000, 0, '[]', '[]', '[\"#FFD700\"]', NULL, 1, 1, 0, 6, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین طناب تابیده مدل 28756', 'خرید انگشتر آکوامارین کوسنی با قاب طناب تابیده و هاله الماس', 'uploads/products/meta/cushion-aquamarine-twisted-rope-halo-engagement-promise-ring__28756.jpg', NULL, 'angoshtar-akvamarin-tanab-tabide-28756', 0.00, '28756', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (7, 'انگشتر آکوامارین هاله دبل کوسنی | مدل 59717', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/cushion-cut-aquamarine-and-diamond-double-halo-statement-ring__59717.jpg\"]', 'uploads/products/thumbnail/cushion-cut-aquamarine-and-diamond-double-halo-statement-ring__59717.jpg', 'uploads/products/featured/cushion-cut-aquamarine-and-diamond-double-halo-statement-ring__59717.jpg', 'uploads/products/flash_deal/cushion-cut-aquamarine-and-diamond-double-halo-statement-ring__59717.jpg', 'youtube', NULL, 'آکوامارین کوسنی, دبل هاله, انگشتر استیتمنت, مدل 59717', 'انگشتر استیتمنت با سنگ آکوامارین کوسنی 3 قیراطی و دو هاله الماس (دبل هاله) دور آن. قاب پلاتین با طراحی چشمگیر و لوکس.', 35000000, 27000000, 0, '[]', '[]', '[\"#E5E4E2\"]', NULL, 1, 1, 1, 2, 'عدد', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین دبل هاله مدل 59717', 'خرید انگشتر استیتمنت آکوامارین 3 قیراطی با دو هاله الماس', 'uploads/products/meta/cushion-cut-aquamarine-and-diamond-double-halo-statement-ring__59717.jpg', NULL, 'angoshtar-akvamarin-doble-hale-59717', 0.00, '59717', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (8, 'انگشتر آکوامارین وینتیج کوسنی | مدل 88335', 'admin', 12, 6, 110, 141, 19, '[\"uploads\\/products\\/photos\\/cushion-cut-aquamarine-diamond-halo-engagement-ring-set-vintage-style__88335.jpg\"]', 'uploads/products/thumbnail/ysqh5ydGFouSwQkovDDXE413NCnPXrUMO3oeBLlt.jpg', 'uploads/products/featured/VrqYOn0fkU2fL7haTW3SwNSRhKtO2ezTkTpeWfOS.jpg', 'uploads/products/flash_deal/BzvBXWzeUHPgVeYsjgN7ut2JNtgzYxO7Yynhat51.jpg', 'youtube', NULL, 'آکوامارین کوسنی, وینتیج, ست نامزدی, مدل 88335', 'ست انگشتر نامزدی و حلقه ازدواج با سنگ آکوامارین کوسنی 1.5 قیراطی و هاله الماس در قاب وینتیج طلای سفید 18 عیار با حکاکی‌های ظریف.', 19500000, 15000000, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 4, 'ست', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر وینتیج آکوامارین مدل 88335', 'خرید ست انگشتر نامزدی آکوامارین کوسنی با طراحی وینتیج', 'uploads/products/meta/cushion-cut-aquamarine-diamond-halo-engagement-ring-set-vintage-style__88335.jpg', NULL, 'انگشتر-آکوامارین-وینتیج-کوسنی-|-مدل-88335-28DnY', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 14:02:58');
INSERT INTO `products` VALUES (9, 'انگشتر آکوامارین کوسنی سولیتر | مدل 67105', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/cushion-cut-blue-aquamarine-diamond-statetemt-ring__67105.jpg\"]', 'uploads/products/thumbnail/cushion-cut-blue-aquamarine-diamond-statetemt-ring__67105.jpg', 'uploads/products/featured/cushion-cut-blue-aquamarine-diamond-statetemt-ring__67105.jpg', 'uploads/products/flash_deal/cushion-cut-blue-aquamarine-diamond-statetemt-ring__67105.jpg', 'youtube', NULL, 'آکوامارین کوسنی, سولیتر, انگشتر استیتمنت, مدل 67105', 'انگشتر سولیتر با سنگ آکوامارین کوسنی 2.5 قیراطی و الماس‌های کوچک در چهار گوشه. قاب طلای زرد 18 عیار با طراحی مینیمال و مدرن.', 28000000, 21500000, 0, '[]', '[]', '[\"#FFD700\"]', NULL, 1, 1, 1, 3, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین کوسنی سولیتر مدل 67105', 'خرید انگشتر سولیتر آکوامارین کوسنی 2.5 قیراطی با الماس', 'uploads/products/meta/cushion-cut-blue-aquamarine-diamond-statetemt-ring__67105.jpg', NULL, 'angoshtar-akvamarin-kosni-soliter-67105', 0.00, '67105', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (10, 'انگشتر آکوامارین رزگلد سولیتر | مدل 39745', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/cushion-cut-blue-aquamarine-rose-gold-solitaire-ring__39745.jpg\"]', 'uploads/products/thumbnail/cushion-cut-blue-aquamarine-rose-gold-solitaire-ring__39745.jpg', 'uploads/products/featured/cushion-cut-blue-aquamarine-rose-gold-solitaire-ring__39745.jpg', 'uploads/products/flash_deal/cushion-cut-blue-aquamarine-rose-gold-solitaire-ring__39745.jpg', 'youtube', NULL, 'آکوامارین کوسنی, رزگلد, سولیتر, مدل 39745', 'انگشتر سولیتر با سنگ آکوامارین کوسنی 1.8 قیراطی در قاب طلای رزگلد 18 عیار. طراحی ساده و شیک با تمرکز بر زیبایی سنگ.', 15500000, 12000000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 0, 5, 'عدد', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین رزگلد سولیتر مدل 39745', 'خرید انگشتر سولیتر آکوامارین کوسنی با قاب رزگلد', 'uploads/products/meta/cushion-cut-blue-aquamarine-rose-gold-solitaire-ring__39745.jpg', NULL, 'angoshtar-akvamarin-rozgold-soliter-39745', 0.00, '39745', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (11, 'انگشتر آکوامارین کوسنی سولیتر | مدل 62830', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/cushion-cut-blue-aquamarine-solitaire-engagement-ring__62830.jpg\"]', 'uploads/products/thumbnail/cushion-cut-blue-aquamarine-solitaire-engagement-ring__62830.jpg', 'uploads/products/featured/cushion-cut-blue-aquamarine-solitaire-engagement-ring__62830.jpg', 'uploads/products/flash_deal/cushion-cut-blue-aquamarine-solitaire-engagement-ring__62830.jpg', 'youtube', NULL, 'آکوامارین کوسنی, سولیتر, انگشتر نامزدی, مدل 62830', 'انگشتر نامزدی سولیتر با سنگ آکوامارین کوسنی 1.2 قیراطی. قاب طلای سفید 18 عیار با چهار چنگک ساده و طراحی کلاسیک.', 12500000, 9500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 8, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین کوسنی سولیتر مدل 62830', 'خرید انگشتر نامزدی سولیتر آکوامارین کوسنی 1.2 قیراطی', 'uploads/products/meta/cushion-cut-blue-aquamarine-solitaire-engagement-ring__62830.jpg', NULL, 'angoshtar-akvamarin-kosni-soliter-62830', 0.00, '62830', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (12, 'ست انگشتر نامزدی آکوامارین کوسنی | مدل 45670', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/cushion_cut_blue_aquamarine_engagement_wedding_matching_ring_set__45670.jpg\"]', 'uploads/products/thumbnail/cushion_cut_blue_aquamarine_engagement_wedding_matching_ring_set__45670.j', 'uploads/products/featured/cushion_cut_blue_aquamarine_engagement_wedding_matching_ring_set__45670.jp', 'uploads/products/flash_deal/cushion_cut_blue_aquamarine_engagement_wedding_matching_ring_set__45670.', 'youtube', NULL, 'آکوامارین کوسنی, ست نامزدی, حلقه ازدواج, مدل 45670', 'ست کامل انگشتر نامزدی و حلقه ازدواج با سنگ آکوامارین کوسنی 1 قیراطی. قاب طلای سفید 18 عیار با طراحی هماهنگ و مینیمال.', 18500000, 14000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'ست', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر آکوامارین کوسنی مدل 45670', 'خرید ست انگشتر نامزدی و حلقه ازدواج آکوامارین کوسنی', 'uploads/products/meta/cushion_cut_blue_aquamarine_engagement_wedding_matching_ring_set__45670.jpg', NULL, 'set-angoshtar-akvamarin-kosni-45670', 0.00, '45670', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (13, 'انگشتر آکوامارین هاله استیتمنت | مدل 09292', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/blue-aquamarine-and-diamond-halo-statement-ring__09292.jpg\"]', 'uploads/products/thumbnail/blue-aquamarine-and-diamond-halo-statement-ring__09292.jpg', 'uploads/products/featured/blue-aquamarine-and-diamond-halo-statement-ring__09292.jpg', 'uploads/products/flash_deal/blue-aquamarine-and-diamond-halo-statement-ring__09292.jpg', 'youtube', NULL, 'آکوامارین, هاله الماس, استیتمنت, مدل 09292', 'انگشتر استیتمنت با سنگ آکوامارین بیضی 2 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی مدرن و چشمگیر.', 21000000, 16000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 3, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین هاله استیتمنت مدل 09292', 'خرید انگشتر استیتمنت آکوامارین 2 قیراطی با هاله الماس', 'uploads/products/meta/blue-aquamarine-and-diamond-halo-statement-ring__09292.jpg', NULL, 'angoshtar-akvamarin-hale-09292', 0.00, '09292', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (14, 'انگشتر آکوامارین و الماس ۳ سنگه | مدل 63219', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/blue-aquamarine-and-diamonds-engagement-ring__63219.jpg\"]', 'uploads/products/thumbnail/blue-aquamarine-and-diamonds-engagement-ring__63219.jpg', 'uploads/products/featured/blue-aquamarine-and-diamonds-engagement-ring__63219.jpg', 'uploads/products/flash_deal/blue-aquamarine-and-diamonds-engagement-ring__63219.jpg', 'youtube', NULL, 'آکوامارین, ۳ سنگه, انگشتر نامزدی, مدل 63219', 'انگشتر نامزدی با سه سنگ شامل آکوامارین بیضی 1.8 قیراطی در مرکز و دو الماس مثلثی در دو طرف. قاب طلای رزگلد 18 عیار.', 23000000, 17500000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 4, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین ۳ سنگه مدل 63219', 'خرید انگشتر نامزدی آکوامارین ۳ سنگه با الماس', 'uploads/products/meta/blue-aquamarine-and-diamonds-engagement-ring__63219.jpg', NULL, 'angoshtar-akvamarin-3-sange-63219', 0.00, '63219', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (15, 'انگشتر آکوامارین ۳ سنگه کلاسیک | مدل 66964', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/blue-aquamarine-diamond-3-stone-engagement-ring__66964.jpg\"]', 'uploads/products/thumbnail/blue-aquamarine-diamond-3-stone-engagement-ring__66964.jpg', 'uploads/products/featured/blue-aquamarine-diamond-3-stone-engagement-ring__66964.jpg', 'uploads/products/flash_deal/blue-aquamarine-diamond-3-stone-engagement-ring__66964.jpg', 'youtube', NULL, 'آکوامارین, ۳ سنگه کلاسیک, انگشتر نامزدی, مدل 66964', 'انگشتر نامزدی ۳ سنگه با سنگ مرکزی آکوامارین گرد 1.5 قیراطی و دو الماس گرد کوچکتر در دو طرف. قاب طلای سفید 18 عیار.', 19500000, 15000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 5, 'عدد', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین ۳ سنگه کلاسیک مدل 66964', 'خرید انگشتر نامزدی ۳ سنگه آکوامارین کلاسیک', 'uploads/products/meta/blue-aquamarine-diamond-3-stone-engagement-ring__66964.jpg', NULL, 'angoshtar-akvamarin-3-sange-klasik-66964', 0.00, '66964', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (16, 'انگشتر آکوامارین سولیتر | مدل 89396', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/blue-aquamarine-diamond-solitaire-engagement-ring__89396.jpg\"]', 'uploads/products/thumbnail/blue-aquamarine-diamond-solitaire-engagement-ring__89396.jpg', 'uploads/products/featured/blue-aquamarine-diamond-solitaire-engagement-ring__89396.jpg', 'uploads/products/flash_deal/blue-aquamarine-diamond-solitaire-engagement-ring__89396.jpg', 'youtube', NULL, 'آکوامارین, سولیتر, انگشتر نامزدی, مدل 89396', 'انگشتر سولیتر با سنگ آکوامارین بیضی 1.3 قیراطی و چهار چنگک الماس‌نشان. قاب طلای سفید 18 عیار با طراحی مینیمال.', 14500000, 11000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 6, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین سولیتر مدل 89396', 'خرید انگشتر سولیتر آکوامارین 1.3 قیراطی با الماس', 'uploads/products/meta/blue-aquamarine-diamond-solitaire-engagement-ring__89396.jpg', NULL, 'angoshtar-akvamarin-soliter-89396', 0.00, '89396', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (17, 'انگشتر مارکیز آکوامارین وینتیج | مدل 75038', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/marquise-cut-aquamarine-vintage-halo-engagement-ring__75038.jpg\"]', 'uploads/products/thumbnail/marquise-cut-aquamarine-vintage-halo-engagement-ring__75038.jpg', 'uploads/products/featured/marquise-cut-aquamarine-vintage-halo-engagement-ring__75038.jpg', 'uploads/products/flash_deal/marquise-cut-aquamarine-vintage-halo-engagement-ring__75038.jpg', 'youtube', NULL, 'آکوامارین مارکیز, وینتیج, انگشتر نامزدی, مدل 75038', 'انگشتر نامزدی با سنگ آکوامارین مارکیز (نوک تیز) 1.8 قیراطی و هاله الماس وینتیج. قاب طلای زرد 18 عیار با حکاکی‌های ظریف.', 17500000, 13500000, 0, '[]', '[]', '[\"#FFD700\"]', NULL, 1, 1, 1, 4, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین مارکیز وینتیج مدل 75038', 'خرید انگشتر نامزدی آکوامارین مارکیز با هاله وینتیج', 'uploads/products/meta/marquise-cut-aquamarine-vintage-halo-engagement-ring__75038.jpg', NULL, 'angoshtar-akvamarin-markiz-vintij-75038', 0.00, '75038', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (18, 'انگشتر آکوامارین بیضی وینتیج | مدل 56938', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/oval-aquamarine-and-diamond-vintage-engagement-ring__56938.jpg\"]', 'uploads/products/thumbnail/oval-aquamarine-and-diamond-vintage-engagement-ring__56938.jpg', 'uploads/products/featured/oval-aquamarine-and-diamond-vintage-engagement-ring__56938.jpg', 'uploads/products/flash_deal/oval-aquamarine-and-diamond-vintage-engagement-ring__56938.jpg', 'youtube', NULL, 'آکوامارین بیضی, وینتیج, انگشتر نامزدی, مدل 56938', 'انگشتر نامزدی با سنگ آکوامارین بیضی 1.6 قیراطی و الماس‌های ریز در حاشیه با طراحی وینتیج. قاب طلای سفید 18 عیار.', 16500000, 12500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 5, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین بیضی وینتیج مدل 56938', 'خرید انگشتر نامزدی آکوامارین بیضی با طراحی وینتیج', 'uploads/products/meta/oval-aquamarine-and-diamond-vintage-engagement-ring__56938.jpg', NULL, 'angoshtar-akvamarin-beizi-vintij-56938', 0.00, '56938', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (19, 'انگشتر آکوامارین بزل ست طلای زرد | مدل 14918', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/oval-aquamarine-bezel-set-ring-14k-yellow-gold__14918.jpg\"]', 'uploads/products/thumbnail/oval-aquamarine-bezel-set-ring-14k-yellow-gold__14918.jpg', 'uploads/products/featured/oval-aquamarine-bezel-set-ring-14k-yellow-gold__14918.jpg', 'uploads/products/flash_deal/oval-aquamarine-bezel-set-ring-14k-yellow-gold__14918.jpg', 'youtube', NULL, 'آکوامارین بیضی, بزل ست, طلای زرد, مدل 14918', 'انگشتر با سنگ آکوامارین بیضی 1.2 قیراطی در قاب بزل (bezelset) طلای زرد ۱۴ عیار. طراحی مدرن و مینیمال با محافظت کامل از سنگ.', 12000000, 9000000, 0, '[]', '[]', '[\"#FFD700\"]', NULL, 1, 1, 0, 7, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین بزل ست طلای زرد مدل 14918', 'خرید انگشتر آکوامارین بیضی با قاب بزل طلای زرد', 'uploads/products/meta/oval-aquamarine-bezel-set-ring-14k-yellow-gold__14918.jpg', NULL, 'angoshtar-akvamarin-bezel-set-14918', 0.00, '14918', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (20, 'انگشتر آکوامارین بیضی وینتیج | مدل 13008', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/oval-aquamarine-diamond-vintage-engagement-ring__13008.jpg\"]', 'uploads/products/thumbnail/oval-aquamarine-diamond-vintage-engagement-ring__13008.jpg', 'uploads/products/featured/oval-aquamarine-diamond-vintage-engagement-ring__13008.jpg', 'uploads/products/flash_deal/oval-aquamarine-diamond-vintage-engagement-ring__13008.jpg', 'youtube', NULL, 'آکوامارین بیضی, وینتیج, انگشتر نامزدی, مدل 13008', 'انگشتر نامزدی با سنگ آکوامارین بیضی 1.4 قیراطی و الماس‌های ریز در قاب وینتیج طلای سفید 18 عیار. طراحی ظریف و کلاسیک.', 15500000, 11800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 4, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین بیضی وینتیج مدل 13008', 'خرید انگشتر نامزدی آکوامارین بیضی با طراحی وینتیج', 'uploads/products/meta/oval-aquamarine-diamond-vintage-engagement-ring__13008.jpg', NULL, 'angoshtar-akvamarin-beizi-vintij-13008', 0.00, '13008', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (21, 'انگشتر آکوامارین ۳ سنگه بیضی | مدل 86596', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/oval-blue-aquamarine-and-diamonds-3-stone-engagement-ring__86596.jpg\"]', 'uploads/products/thumbnail/oval-blue-aquamarine-and-diamonds-3-stone-engagement-ring__86596.jpg', 'uploads/products/featured/oval-blue-aquamarine-and-diamonds-3-stone-engagement-ring__86596.jpg', 'uploads/products/flash_deal/oval-blue-aquamarine-and-diamonds-3-stone-engagement-ring__86596.jpg', 'youtube', NULL, 'آکوامارین بیضی, ۳ سنگه, انگشتر نامزدی, مدل 86596', 'انگشتر ۳ سنگه با سنگ مرکزی آکوامارین بیضی 1.2 قیراطی و دو الماس گرد در دو طرف. قاب طلای رزگلد 18 عیار.', 18500000, 14000000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 5, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین ۳ سنگه بیضی مدل 86596', 'خرید انگشتر ۳ سنگه آکوامارین بیضی با الماس', 'uploads/products/meta/oval-blue-aquamarine-and-diamonds-3-stone-engagement-ring__86596.jpg', NULL, 'angoshtar-akvamarin-3-sange-beizi-86596', 0.00, '86596', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (22, 'انگشتر آکوامارین هاله بیضی | مدل 55983', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/oval-blue-aquamarine-diamond-halo-statement-ring__55983.jpg\"]', 'uploads/products/thumbnail/oval-blue-aquamarine-diamond-halo-statement-ring__55983.jpg', 'uploads/products/featured/oval-blue-aquamarine-diamond-halo-statement-ring__55983.jpg', 'uploads/products/flash_deal/oval-blue-aquamarine-diamond-halo-statement-ring__55983.jpg', 'youtube', NULL, 'آکوامارین بیضی, هاله الماس, استیتمنت, مدل 55983', 'انگشتر استیتمنت با سنگ آکوامارین بیضی 2.2 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی چشمگیر.', 25000000, 19000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 3, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین هاله بیضی مدل 55983', 'خرید انگشتر استیتمنت آکوامارین بیضی 2.2 قیراطی با هاله الماس', 'uploads/products/meta/oval-blue-aquamarine-diamond-halo-statement-ring__55983.jpg', NULL, 'angoshtar-akvamarin-hale-beizi-55983', 0.00, '55983', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (23, 'ست انگشتر نامزدی آکوامارین بیضی | مدل 78933', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/oval-blue-aquamarine-engagement-wedding-ring-set__78933.jpg\"]', 'uploads/products/thumbnail/oval-blue-aquamarine-engagement-wedding-ring-set__78933.jpg', 'uploads/products/featured/oval-blue-aquamarine-engagement-wedding-ring-set__78933.jpg', 'uploads/products/flash_deal/oval-blue-aquamarine-engagement-wedding-ring-set__78933.jpg', 'youtube', NULL, 'آکوامارین بیضی, ست نامزدی, حلقه ازدواج, مدل 78933', 'ست کامل انگشتر نامزدی با سنگ آکوامارین بیضی 1 قیراطی و حلقه ازدواج هماهنگ با الماس‌های ریز. قاب طلای سفید 18 عیار.', 19500000, 14800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 4, 'ست', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر آکوامارین بیضی مدل 78933', 'خرید ست انگشتر نامزدی و حلقه ازدواج آکوامارین بیضی', 'uploads/products/meta/oval-blue-aquamarine-engagement-wedding-ring-set__78933.jpg', NULL, 'set-angoshtar-akvamarin-beizi-78933', 0.00, '78933', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (24, 'انگشتر گلابی آکوامارین | مدل 49721', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/pear-shape-aquamarine-diamond-engagement-ring__49721.jpg\"]', 'uploads/products/thumbnail/pear-shape-aquamarine-diamond-engagement-ring__49721.jpg', 'uploads/products/featured/pear-shape-aquamarine-diamond-engagement-ring__49721.jpg', 'uploads/products/flash_deal/pear-shape-aquamarine-diamond-engagement-ring__49721.jpg', 'youtube', NULL, 'آکوامارین گلابی, انگشتر نامزدی, مدل 49721', 'انگشتر نامزدی با سنگ آکوامارین گلابی (نوک تیز) 1.7 قیراطی و الماس‌های کوچک در امتداد قاب. قاب طلای سفید 18 عیار.', 17500000, 13200000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 5, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین گلابی مدل 49721', 'خرید انگشتر نامزدی آکوامارین گلابی با الماس', 'uploads/products/meta/pear-shape-aquamarine-diamond-engagement-ring__49721.jpg', NULL, 'angoshtar-akvamarin-golabi-49721', 0.00, '49721', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (25, 'انگشتر آکوامارین گلابی هاله | مدل 70693', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/pear-shaped-aquamarone-and-diamonds-halo-statement-ring__70693.jpg\"]', 'uploads/products/thumbnail/pear-shaped-aquamarone-and-diamonds-halo-statement-ring__70693.jpg', 'uploads/products/featured/pear-shaped-aquamarone-and-diamonds-halo-statement-ring__70693.jpg', 'uploads/products/flash_deal/pear-shaped-aquamarone-and-diamonds-halo-statement-ring__70693.jpg', 'youtube', NULL, 'آکوامارین گلابی, هاله الماس, استیتمنت, مدل 70693', 'انگشتر استیتمنت با سنگ آکوامارین گلابی 2 قیراطی و هاله الماس دور آن. قاب طلای رزگلد 18 عیار با طراحی دراماتیک.', 23000000, 17500000, 0, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 3, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین گلابی هاله مدل 70693', 'خرید انگشتر استیتمنت آکوامارین گلابی 2 قیراطی با هاله الماس', 'uploads/products/meta/pear-shaped-aquamarone-and-diamonds-halo-statement-ring__70693.jpg', NULL, 'angoshtar-akvamarin-golabi-hale-70693', 0.00, '70693', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (26, 'انگشتر آکوامارین هاله مینیاتوری | مدل 81035', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/petite-blue-aquamarine-and-diamond-halo-engagement-ring__81035.jpg\"]', 'uploads/products/thumbnail/petite-blue-aquamarine-and-diamond-halo-engagement-ring__81035.jpg', 'uploads/products/featured/petite-blue-aquamarine-and-diamond-halo-engagement-ring__81035.jpg', 'uploads/products/flash_deal/petite-blue-aquamarine-and-diamond-halo-engagement-ring__81035.jpg', 'youtube', NULL, 'آکوامارین کوچک, هاله الماس, انگشتر نامزدی, مدل 81035', 'انگشتر نامزدی ظریف با سنگ آکوامارین بیضی 0.8 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار. مناسب استفاده روزمره.', 10500000, 7800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 8, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین هاله مینیاتوری مدل 81035', 'خرید انگشتر نامزدی آکوامارین ظریف 0.8 قیراطی با هاله الماس', 'uploads/products/meta/petite-blue-aquamarine-and-diamond-halo-engagement-ring__81035.jpg', NULL, 'angoshtar-akvamarin-hale-miniatur-81035', 0.00, '81035', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (27, 'انگشتر پرنسس آکوامارین ۳ سنگه | مدل 49705', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/princess-aquamarine-diamond-engagement-ring-set__49705.jpg\"]', 'uploads/products/thumbnail/princess-aquamarine-diamond-engagement-ring-set__49705.jpg', 'uploads/products/featured/princess-aquamarine-diamond-engagement-ring-set__49705.jpg', 'uploads/products/flash_deal/princess-aquamarine-diamond-engagement-ring-set__49705.jpg', 'youtube', NULL, 'آکوامارین پرنسس, ۳ سنگه, ست نامزدی, مدل 49705', 'ست انگشتر نامزدی با سنگ آکوامارین پرنسس 1.5 قیراطی و دو الماس مثلثی در دو طرف. قاب طلای سفید 18 عیار.', 21000000, 16000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 4, 'ست', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر آکوامارین پرنسس مدل 49705', 'خرید ست انگشتر نامزدی آکوامارین پرنسس با الماس', 'uploads/products/meta/princess-aquamarine-diamond-engagement-ring-set__49705.jpg', NULL, 'set-angoshtar-akvamarin-princes-49705', 0.00, '49705', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (28, 'ست انگشتر پرنسس آکوامارین | مدل 40939', 'admin', 12, 6, 110, 141, 16, '[\"uploads\\/products\\/photos\\/princess-cut-aquamarine-and-diamond-engagement-wedding-ring-set__40939.jpg\"]', 'uploads/products/thumbnail/7SDAAsLy6HJJXiWeZN604jaJ035FZTd6KTjD8ke8.jpg', 'uploads/products/featured/princess-cut-aquamarine-and-diamond-engagement-wedding-ring-set__40939.jpg', 'uploads/products/flash_deal/F9J7X2eyAMGbvaJjIjEEtfI7u9Lkj8usH4aKbIyF.jpg', 'youtube', NULL, 'آکوامارین پرنسس, ست نامزدی, حلقه ازدواج, مدل 40939', 'ست کامل انگشتر نامزدی با سنگ آکوامارین پرنسس 1 قیراطی و حلقه ازدواج هماهنگ با الماس. قاب طلای سفید 18 عیار.', 16500000, 12500000, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 5, 'ست', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'ست انگشتر پرنسس آکوامارین مدل 40939', 'خرید ست انگشتر نامزدی و حلقه ازدواج آکوامارین پرنسس', 'uploads/products/meta/princess-cut-aquamarine-and-diamond-engagement-wedding-ring-set__40939.jpg', NULL, 'ست-انگشتر-پرنسس-آکوامارین-|-مدل-40939-k4TJe', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 14:04:13');
INSERT INTO `products` VALUES (29, 'انگشتر پرنسس آکوامارین وینتیج | مدل 99744', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/princess-cut-aquamarine-diamond-vintage-engagement-ring__99744.jpg\"]', 'uploads/products/thumbnail/princess-cut-aquamarine-diamond-vintage-engagement-ring__99744.jpg', 'uploads/products/featured/princess-cut-aquamarine-diamond-vintage-engagement-ring__99744.jpg', 'uploads/products/flash_deal/princess-cut-aquamarine-diamond-vintage-engagement-ring__99744.jpg', 'youtube', NULL, 'آکوامارین پرنسس, وینتیج, انگشتر نامزدی, مدل 99744', 'انگشتر نامزدی با سنگ آکوامارین پرنسس 1.6 قیراطی و الماس‌های ریز در قاب وینتیج طلای سفید 18 عیار با حکاکی‌های ظریف.', 17500000, 13500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 4, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر پرنسس آکوامارین وینتیج مدل 99744', 'خرید انگشتر نامزدی آکوامارین پرنسس با طراحی وینتیج', 'uploads/products/meta/princess-cut-aquamarine-diamond-vintage-engagement-ring__99744.jpg', NULL, 'angoshtar-akvamarin-princes-vintij-99744', 0.00, '99744', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (30, 'انگشتر پرنسس آکوامارین سولیتر | مدل 61759', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/princess-cut-aquamarine-solitaire-ring__61759.jpg\"]', 'uploads/products/thumbnail/princess-cut-aquamarine-solitaire-ring__61759.jpg', 'uploads/products/featured/princess-cut-aquamarine-solitaire-ring__61759.jpg', 'uploads/products/flash_deal/princess-cut-aquamarine-solitaire-ring__61759.jpg', 'youtube', NULL, 'آکوامارین پرنسس, سولیتر, انگشتر نامزدی, مدل 61759', 'انگشتر سولیتر با سنگ آکوامارین پرنسس 1.2 قیراطی در قاب طلای سفید 18 عیار با چهار چنگک ساده. طراحی مینیمال و کلاسیک.', 13500000, 10000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 6, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر پرنسس آکوامارین سولیتر مدل 61759', 'خرید انگشتر سولیتر آکوامارین پرنسس 1.2 قیراطی', 'uploads/products/meta/princess-cut-aquamarine-solitaire-ring__61759.jpg', NULL, 'angoshtar-akvamarin-princes-soliter-61759', 0.00, '61759', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (31, 'انگشتر پرنسس آکوامارین هاله | مدل 48861', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/princess-cut-blue-aquamarine-diamond-engagement-ring__48861.jpg\"]', 'uploads/products/thumbnail/princess-cut-blue-aquamarine-diamond-engagement-ring__48861.jpg', 'uploads/products/featured/princess-cut-blue-aquamarine-diamond-engagement-ring__48861.jpg', 'uploads/products/flash_deal/princess-cut-blue-aquamarine-diamond-engagement-ring__48861.jpg', 'youtube', NULL, 'آکوامارین پرنسس, هاله الماس, انگشتر نامزدی, مدل 48861', 'انگشتر نامزدی با سنگ آکوامارین پرنسس 1.4 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی مدرن.', 16500000, 12500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر پرنسس آکوامارین هاله مدل 48861', 'خرید انگشتر نامزدی آکوامارین پرنسس با هاله الماس', 'uploads/products/meta/princess-cut-blue-aquamarine-diamond-engagement-ring__48861.jpg', NULL, 'angoshtar-akvamarin-princes-hale-48861', 0.00, '48861', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (32, 'انگشتر گرد آکوامارین اسپلیت بند | مدل 54144', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/round-blue-aquamarine-solitaire-engagement-ring-split-band__54144.jpg\"]', 'uploads/products/thumbnail/round-blue-aquamarine-solitaire-engagement-ring-split-band__54144.jpg', 'uploads/products/featured/round-blue-aquamarine-solitaire-engagement-ring-split-band__54144.jpg', 'uploads/products/flash_deal/round-blue-aquamarine-solitaire-engagement-ring-split-band__54144.jpg', 'youtube', NULL, 'آکوامارین گرد, اسپلیت بند, سولیتر, مدل 54144', 'انگشتر سولیتر با سنگ آکوامارین گرد 1.3 قیراطی و بند اسپلیت (شکسته) طلای سفید 18 عیار. طراحی مدرن و منحصر به فرد.', 14500000, 11000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 5, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر گرد آکوامارین اسپلیت بند مدل 54144', 'خرید انگشتر سولیتر آکوامارین گرد با بند اسپلیت', 'uploads/products/meta/round-blue-aquamarine-solitaire-engagement-ring-split-band__54144.jpg', NULL, 'angoshtar-akvamarin-gard-split-band-54144', 0.00, '54144', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (33, 'انگشتر گرد آکوامارین هاله | مدل 70793', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/round_blue_aquamarine_diamond_engagement_ring__70793.jpg\"]', 'uploads/products/thumbnail/round_blue_aquamarine_diamond_engagement_ring__70793.jpg', 'uploads/products/featured/round_blue_aquamarine_diamond_engagement_ring__70793.jpg', 'uploads/products/flash_deal/round_blue_aquamarine_diamond_engagement_ring__70793.jpg', 'youtube', NULL, 'آکوامارین گرد, هاله الماس, انگشتر نامزدی, مدل 70793', 'انگشتر نامزدی با سنگ آکوامارین گرد 1.6 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی کلاسیک و همیشه شیک.', 17500000, 13200000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر گرد آکوامارین هاله مدل 70793', 'خرید انگشتر نامزدی آکوامارین گرد با هاله الماس', 'uploads/products/meta/round_blue_aquamarine_diamond_engagement_ring__70793.jpg', NULL, 'angoshtar-akvamarin-gard-hale-70793', 0.00, '70793', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (34, 'انگشتر مربع آکوامارین هاله | مدل 93695', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/square-blue-aquamarine-and-diamond-halo-statement-ring__93695.jpg\"]', 'uploads/products/thumbnail/square-blue-aquamarine-and-diamond-halo-statement-ring__93695.jpg', 'uploads/products/featured/square-blue-aquamarine-and-diamond-halo-statement-ring__93695.jpg', 'uploads/products/flash_deal/square-blue-aquamarine-and-diamond-halo-statement-ring__93695.jpg', 'youtube', NULL, 'آکوامارین مربع, هاله الماس, استیتمنت, مدل 93695', 'انگشتر استیتمنت با سنگ آکوامارین مربع (شکل خاص) 2.5 قیراطی و هاله الماس دور آن. قاب طلای سفید 18 عیار با طراحی منحصر به فرد.', 28000000, 21500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 3, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین مربع هاله مدل 93695', 'خرید انگشتر استیتمنت آکوامارین مربع 2.5 قیراطی با هاله الماس', 'uploads/products/meta/square-blue-aquamarine-and-diamond-halo-statement-ring__93695.jpg', NULL, 'angoshtar-akvamarin-moraba-hale-93695', 0.00, '93695', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (35, 'انگشتر مثلث آکوامارین سولیتر | مدل 95642', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/trilliant-cut-aquamarine-solitaire-ring-white-gold__95642.jpg\"]', 'uploads/products/thumbnail/trilliant-cut-aquamarine-solitaire-ring-white-gold__95642.jpg', 'uploads/products/featured/trilliant-cut-aquamarine-solitaire-ring-white-gold__95642.jpg', 'uploads/products/flash_deal/trilliant-cut-aquamarine-solitaire-ring-white-gold__95642.jpg', 'youtube', NULL, 'آکوامارین مثلث, سولیتر, انگشتر نامزدی, مدل 95642', 'انگشتر سولیتر با سنگ آکوامارین مثلث (trilliant) 1.8 قیراطی در قاب طلای سفید 18 عیار. طراحی مدرن و خاص.', 19500000, 14800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 4, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر مثلث آکوامارین سولیتر مدل 95642', 'خرید انگشتر سولیتر آکوامارین مثلث با قاب طلای سفید', 'uploads/products/meta/trilliant-cut-aquamarine-solitaire-ring-white-gold__95642.jpg', NULL, 'angoshtar-akvamarin-mosalas-soliter-95642', 0.00, '95642', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (36, 'انگشتر مثلث آکوامارین قول | مدل 66764', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/trillion-aquamarine-diamond-promise-engagement-ring__66764.jpg\"]', 'uploads/products/thumbnail/trillion-aquamarine-diamond-promise-engagement-ring__66764.jpg', 'uploads/products/featured/trillion-aquamarine-diamond-promise-engagement-ring__66764.jpg', 'uploads/products/flash_deal/trillion-aquamarine-diamond-promise-engagement-ring__66764.jpg', 'youtube', NULL, 'آکوامارین مثلث, حلقه قول, انگشتر نامزدی, مدل 66764', 'انگشتر قول (Promise Ring) با سنگ آکوامارین مثلث 1 قیراطی و الماس‌های ریز در قاب طلای سفید 18 عیار. هدیه‌ای برای آغاز یک رابطه.', 12500000, 9500000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 8, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر مثلث آکوامارین قول مدل 66764', 'خرید انگشتر قول آکوامارین مثلث با الماس', 'uploads/products/meta/trillion-aquamarine-diamond-promise-engagement-ring__66764.jpg', NULL, 'angoshtar-akvamarin-mosalas-ghol-66764', 0.00, '66764', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (37, 'انگشتر آکوامارین وینتیج هاله | مدل 28934', 'admin', 12, 104, 1043, 132, 1, '[\"uploads/products/photos/vintage-blue-aquamarine-diamond-halo-engagement-ring__28934.jpg\"]', 'uploads/products/thumbnail/vintage-blue-aquamarine-diamond-halo-engagement-ring__28934.jpg', 'uploads/products/featured/vintage-blue-aquamarine-diamond-halo-engagement-ring__28934.jpg', 'uploads/products/flash_deal/vintage-blue-aquamarine-diamond-halo-engagement-ring__28934.jpg', 'youtube', NULL, 'آکوامارین, وینتیج, هاله الماس, انگشتر نامزدی, مدل 28934', 'انگشتر نامزدی با سنگ آکوامارین بیضی 1.5 قیراطی و هاله الماس وینتیج در قاب طلای سفید 18 عیار با جزئیات ظریف.', 18500000, 14000000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 4, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین وینتیج هاله مدل 28934', 'خرید انگشتر نامزدی آکوامارین با هاله وینتیج', 'uploads/products/meta/vintage-blue-aquamarine-diamond-halo-engagement-ring__28934.jpg', NULL, 'angoshtar-akvamarin-vintij-hale-28934', 0.00, '28934', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (38, 'انگشتر آکوامارین سولیتر وینتیج | مدل 45823', 'admin', 12, 104, 1043, 132, 2, '[\"uploads/products/photos/vintage-blue-aquamarine-solitaire-engagement-ring__45823.jpg\"]', 'uploads/products/thumbnail/vintage-blue-aquamarine-solitaire-engagement-ring__45823.jpg', 'uploads/products/featured/vintage-blue-aquamarine-solitaire-engagement-ring__45823.jpg', 'uploads/products/flash_deal/vintage-blue-aquamarine-solitaire-engagement-ring__45823.jpg', 'youtube', NULL, 'آکوامارین, سولیتر وینتیج, انگشتر نامزدی, مدل 45823', 'انگشتر سولیتر با سنگ آکوامارین گرد 1.2 قیراطی در قاب وینتیج طلای زرد 18 عیار با حکاکی‌های ظریف دور تا دور بند.', 13500000, 10000000, 0, '[]', '[]', '[\"#FFD700\"]', NULL, 1, 1, 0, 5, 'عدد', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین سولیتر وینتیج مدل 45823', 'خرید انگشتر سولیتر آکوامارین با طراحی وینتیج', 'uploads/products/meta/vintage-blue-aquamarine-solitaire-engagement-ring__45823.jpg', NULL, 'angoshtar-akvamarin-soliter-vintij-45823', 0.00, '45823', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (39, 'انگشتر آکوامارین سولیتر وینتیج سفید | مدل 38970', 'admin', 12, 104, 1043, 132, 3, '[\"uploads/products/photos/vintage-style-aquamarine-solitaire-ring-white-gold__38970.jpg\"]', 'uploads/products/thumbnail/vintage-style-aquamarine-solitaire-ring-white-gold__38970.jpg', 'uploads/products/featured/vintage-style-aquamarine-solitaire-ring-white-gold__38970.jpg', 'uploads/products/flash_deal/vintage-style-aquamarine-solitaire-ring-white-gold__38970.jpg', 'youtube', NULL, 'آکوامارین, سولیتر وینتیج, طلای سفید, مدل 38970', 'انگشتر سولیتر با سنگ آکوامارین بیضی 1.4 قیراطی در قاب وینتیج طلای سفید 18 عیار با جزئیات ظریف و الماس‌های کوچک.', 15500000, 11800000, 0, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 6, 'عدد', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 150000.00, 0, 'انگشتر آکوامارین سولیتر وینتیج سفید مدل 38970', 'خرید انگشتر سولیتر آکوامارین با طراحی وینتیج طلای سفید', 'uploads/products/meta/vintage-style-aquamarine-solitaire-ring-white-gold__38970.jpg', NULL, 'angoshtar-akvamarin-soliter-vintij-sefid-38970', 0.00, '38970', 0, NULL, NULL, '2026-09-09 16:43:58', '2026-09-09 16:43:58');
INSERT INTO `products` VALUES (61, 'ساعت مچی رولکس اسکای‌دولر رزگلد | مدل 326935', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-rolex-326935-sky-dweller-rose-gold-white-index-dial-watch-rsky07162-32696863719483_520x.png\"]', 'uploads/products/thumbnail/8OiWkM7ewSLxGoO2Bb9TJCpkJS6ZH5B49uOshpfW.png', 'uploads/products/featured/ga8CJ5LHUNgC6DgtU3OgVKMdx1hhps2R5zLLKVRP.png', 'uploads/products/flash_deal/yVo18tEtaFFDyXIlrlZnvAHeOV7cc0Ew6l4c7Lsr.png', 'youtube', NULL, 'رولکس, اسکای‌دولر, ساعت مچی, رزگلد', 'ساعت مچی رولکس اسکای‌دولر با قاب طلای رزگلد 18 عیار و صفحه سفید با نشان‌های شاخص. مجهز به کالیبر 9001 با نمایش دو منطقه زمانی و تاریخ. ضد آب تا 100 متر. بند طلای رزگلد با قفل ایمن.', 850000000, 750000000, 1, '[]', '[]', '[\"#FFB6C1\"]', NULL, 1, 1, 1, 2, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس اسکای‌دولر رزگلد مدل 326935', 'خرید ساعت مچی رولکس اسکای‌دولر با قاب رزگلد 18 عیار و صفحه سفید', 'uploads/products/meta/watch-rolex-326935-sky-dweller-rose-gold-white-index-dial-watch-rsky07162-32696863719483_520x.png', NULL, 'ساعت-مچی-رولکس-اسکای‌دولر-رزگلد-|-مدل-326935-lVIKZ', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:29:35');
INSERT INTO `products` VALUES (62, 'ساعت مچی رولکس دی‌جاست 36 استیل | مدل 116234', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/FBfg6KFb94ty6xTxjgDG1J4diI0FLWkIDHUt1Prj.png\"]', 'uploads/products/thumbnail/TyxbTlVYYVWs4bH3FnCAaNMEc81F3GlJZkQxfmDB.png', 'uploads/products/featured/MxCw4yr9YfapHKQJtxRJc6bIZyzANQhMkQL28Nlv.png', 'uploads/products/flash_deal/yCThGUxzqoy2CjrmAA8PnQdDGa9QYOvHUgJxIKKD.png', 'youtube', NULL, 'رولکس, دی‌جاست, ساعت مچی, استیل', 'ساعت مچی رولکس دی‌جاست 36 میلی‌متری با قاب استیل ضدزنگ 904L و بند جوبیلی. صفحه سفید با نشان‌های طلایی و کریستال یاقوت. مجهز به کالیبر 3235. مناسب استفاده روزمره و رسمی.', 120000000, 95000000, 1, '[]', '[]', '[\"#C0C0C0\"]', NULL, 1, 1, 1, 5, 'دستگاه', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس دی‌جاست 36 استیل مدل 116234', 'خرید ساعت مچی رولکس دی‌جاست 36 میلی‌متری با قاب استیل و بند جوبیلی', 'uploads/products/meta/watch-rolex-datejust-36-stainless-steel-jubilee-white-dial-watch-116234-116234-32693971943483_520x.png', NULL, 'ساعت-مچی-رولکس-دی‌جاست-36-استیل-|-مدل-116234-SjgT7', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 14:10:04');
INSERT INTO `products` VALUES (63, 'ساعت مچی رولکس دی‌جاست 41 استیل و رزگلد | مدل 126331', 'admin', 12, 5, 107, 134, 8, '[\"uploads\\/products\\/photos\\/watch-rolex-datejust-41-stainless-steel-and-rose-gold-jubilee-sundust-index-dial-watch-126331-rj4107259-1184525113_520x.png\"]', 'uploads/products/thumbnail/JQBukVUN7roELZPUNJMnUPYo66UGzezdFcxfZj52.png', 'uploads/products/featured/GAx8WfokjSlVyaTwqdIPymoNCKepIpvXQHCcsTnC.png', 'uploads/products/flash_deal/3TSn6RC4Zyb5cmERz4l7gO1mL1Ee8GQVHqMleNP2.png', 'youtube', NULL, 'رولکس, دی‌جاست 41, استیل, رزگلد', 'ساعت مچی رولکس دی‌جاست 41 میلی‌متری با قاب ترکیبی استیل ضدزنگ 904L و طلای رزگلد 18 عیار. بند جوبیلی رزگلد و استیل. صفحه رنگ سانداست با نشان‌های طلایی و کریستال یاقوت.', 450000000, 380000000, 1, '[]', '[]', '[\"#FFB6C1\",\"#C0C0C0\"]', NULL, 1, 1, 1, 4, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس دی‌جاست 41 استیل و رزگلد', 'خرید ساعت مچی رولکس دی‌جاست 41 با قاب استیل و رزگلد و صفحه سانداست', 'uploads/products/meta/watch-rolex-datejust-41-stainless-steel-and-rose-gold-jubilee-sundust-index-dial-watch-126331-rj4107259-1184525113_520x.png', NULL, 'ساعت-مچی-رولکس-دی‌جاست-41-استیل-و-رزگلد-|-مدل-126331-omKYt', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:36:34');
INSERT INTO `products` VALUES (64, 'ساعت مچی رولکس دی‌جاست 41 الماس آبی | مدل 126334', 'admin', 12, 5, 107, 134, 7, '[\"uploads\\/products\\/photos\\/watch-rolex-datejust-41mm-blue-diamond-steel-and-white-gold-jubilee-watch-126334-rj4107065-32699219869755_520x.png\"]', 'uploads/products/thumbnail/hzc5wBQ2Mh5WN7GmLNhhZqW3WZOU2xzUbiHis4cU.png', 'uploads/products/featured/13O6f8Muayrlj8ekSbEbAVc1htYoMm9W4foovf7o.png', 'uploads/products/flash_deal/Oh98qv69vk4I3zUij6NyivjjVvo6tw5fuSqiEiW0.png', 'youtube', NULL, 'رولکس, دی‌جاست 41, الماس, آبی', 'ساعت مچی رولکس دی‌جاست 41 میلی‌متری با قاب استیل ضدزنگ 904L و طلای سفید 18 عیار. صفحه آبی با نشان‌های الماس. بند جوبیلی استیل و طلای سفید. مجهز به کالیبر 3235.', 580000000, 500000000, 1, '[]', '[]', '[\"#0000FF\",\"#C0C0C0\"]', NULL, 1, 1, 1, 3, 'دستگاه', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس دی‌جاست 41 الماس آبی', 'خرید ساعت مچی رولکس دی‌جاست 41 با صفحه آبی و نشان‌های الماس', 'uploads/products/meta/watch-rolex-datejust-41mm-blue-diamond-steel-and-white-gold-jubilee-watch-126334-rj4107065-32699219869755_520x.png', NULL, 'ساعت-مچی-رولکس-دی‌جاست-41-الماس-آبی-|-مدل-126334-qRkmh', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:37:41');
INSERT INTO `products` VALUES (65, 'ساعت مچی رولکس دی‌جاست 41 الماس آبی ۲ | مدل 126334', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-rolex-datejust-41mm-blue-diamond-steel-and-white-gold-jubilee-watch-126334-rj4107065-32699223015483_520x.png\"]', 'uploads/products/thumbnail/Cjl8xKCCAYMV35e1gqaxd49aBoXq8sqmdN9eCX0P.png', 'uploads/products/featured/GEMMJtbi9hMmhDM2nF2UZKei5yS5TJxBEFNNYoVs.png', 'uploads/products/flash_deal/hBDNv4OS8dqNSessdIQzk0FQ5Ps2byZrfwTl01lM.png', 'youtube', NULL, 'رولکس, دی‌جاست 41, الماس, آبی', 'ساعت مچی رولکس دی‌جاست 41 میلی‌متری با قاب استیل ضدزنگ 904L و طلای سفید 18 عیار. صفحه آبی با نشان‌های الماس. بند جوبیلی استیل و طلای سفید. طراحی لوکس و چشمگیر.', 590000000, 510000000, 1, '[]', '[]', '[\"#0000FF\",\"#C0C0C0\"]', NULL, 1, 1, 0, 3, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس دی‌جاست 41 الماس آبی ۲', 'خرید ساعت مچی رولکس دی‌جاست 41 با صفحه آبی الماس و قاب دو رنگ', 'uploads/products/meta/watch-rolex-datejust-41mm-blue-diamond-steel-and-white-gold-jubilee-watch-126334-rj4107065-32699223015483_520x.png', NULL, 'ساعت-مچی-رولکس-دی‌جاست-41-الماس-آبی-۲-|-مدل-126334-JDiyg', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:40:16');
INSERT INTO `products` VALUES (66, 'ساعت مچی رولکس ساب‌مارینر استیل و طلا | مدل 126613lb', 'admin', 12, 5, 107, 134, 9, '[\"uploads\\/products\\/photos\\/watch-rolex-submariner-stainless-steel-and-yellow-gold-blue-index-dial-watch-126613lb-rsub07180-32702127210555_520x.png\"]', 'uploads/products/thumbnail/6ymYhxV0eRdN5BERBQrS7LHmBpNaZYLPwiSUjw15.png', 'uploads/products/featured/egkNsHPWnrG7ELnoqH7EAyddVAFONxATJMvZiicC.png', 'uploads/products/flash_deal/1oDe2WThxxX7PFnsIjT8aPUPITG8KzQzhoSkcol3.png', 'youtube', NULL, 'رولکس, ساب‌مارینر, استیل, طلا', 'ساعت مچی رولکس ساب‌مارینر با قاب ترکیبی استیل ضدزنگ 904L و طلای زرد 18 عیار. صفحه آبی با نشان‌های لومینسنت. ضد آب تا 300 متر. بند استیل با قفل ایمن. طراحی کلاسیک غواصی.', 980000000, 850000000, 1, '[]', '[]', '[\"#0000FF\",\"#FFD700\"]', NULL, 1, 1, 1, 3, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس ساب‌مارینر استیل و طلا', 'خرید ساعت مچی رولکس ساب‌مارینر با قاب استیل و طلای زرد و صفحه آبی', 'uploads/products/meta/watch-rolex-submariner-stainless-steel-and-yellow-gold-blue-index-dial-watch-126613lb-rsub07180-32702127210555_520x.png', NULL, 'ساعت-مچی-رولکس-ساب‌مارینر-استیل-و-طلا-|-مدل-126613lb-cXzh5', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:46:04');
INSERT INTO `products` VALUES (67, 'ساعت مچی رولکس ساب‌مارینر هالک سبز | مدل 116610lv', 'admin', 12, 5, 107, 134, 10, '[\"uploads\\/products\\/photos\\/watch-rolex-submariner-stainless-steel-hulk-green-dial-watch-116610lv-rsub07157-32665478037563_520x.png\"]', 'uploads/products/thumbnail/hYVxu3uR6wn9Ngc3EAc1Jr67lAy6YBHynCASodKU.png', 'uploads/products/featured/3NPpkdZZOL13rajrWZhchCAkRXB2CLAKl6RcERIT.png', 'uploads/products/flash_deal/SvhoUbPKBlW0zJkCB0wwg5ROAAyONBnN5mvQNhUH.png', 'youtube', NULL, 'رولکس, ساب‌مارینر, هالک, صفحه سبز', 'ساعت مچی رولکس ساب‌مارینر هالک با قاب استیل ضدزنگ 904L و صفحه سبز نمادین. بند استیل با قفل ایمنی. ضد آب تا 300 متر. مدل کمیاب و بسیار محبوب کلکسیونرها.', 750000000, 650000000, 1, '[]', '[]', '[\"#008000\",\"#C0C0C0\"]', NULL, 1, 1, 1, 2, 'دستگاه', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس ساب‌مارینر هالک سبز', 'خرید ساعت مچی رولکس ساب‌مارینر با صفحه سبز و قاب استیل', 'uploads/products/meta/watch-rolex-submariner-stainless-steel-hulk-green-dial-watch-116610lv-rsub07157-32665478037563_520x.png', NULL, 'ساعت-مچی-رولکس-ساب‌مارینر-هالک-سبز-|-مدل-116610lv-kITHW', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:47:05');
INSERT INTO `products` VALUES (68, 'ساعت مچی رولکس سی‌دولر استیل و طلا | مدل 126603', 'admin', 12, 5, 107, 134, 16, '[\"uploads\\/products\\/photos\\/watch-rolex-sea-dweller-stainless-steel-and-yellow-gold-black-dial-watch-126603-rsea07034-32702418255931_520x.png\"]', 'uploads/products/thumbnail/Ix1n1lNuetqNKCZrUKjDQj29hjlrDA471qZNgTmX.png', 'uploads/products/featured/CIPLOpU5unncmn0igzAyZ454HXdaekA686CpsGgY.png', 'uploads/products/flash_deal/OsQpjCfc5EQcZUHRZtcdpPcQb2Vhi5fss6lwmWhs.png', 'youtube', NULL, 'رولکس, سی‌دولر, استیل, طلا', 'ساعت مچی رولکس سی‌دولر با قاب ترکیبی استیل ضدزنگ 904L و طلای زرد 18 عیار. صفحه سیاه با نشان‌های لومینسنت. ضد آب تا 1220 متر. مجهز به دریچه هلیوم. مناسب غواصی حرفه‌ای.', 820000000, 720000000, 1, '[]', '[]', '[\"#FFD700\",\"#C0C0C0\"]', NULL, 1, 1, 0, 2, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس سی‌دولر استیل و طلا', 'خرید ساعت مچی رولکس سی‌دولر با قاب استیل و طلا و صفحه سیاه', 'uploads/products/meta/watch-rolex-sea-dweller-stainless-steel-and-yellow-gold-black-dial-watch-126603-rsea07034-32702418255931_520x.png', NULL, 'ساعت-مچی-رولکس-سی‌دولر-استیل-و-طلا-|-مدل-126603-S9L2C', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:47:54');
INSERT INTO `products` VALUES (69, 'ساعت مچی رولکس اسکای‌دولر رزگلد شکلاتی | مدل 326935', 'admin', 12, 5, 107, 134, 17, '[\"uploads\\/products\\/photos\\/watch-rolex-sky-dweller-rose-gold-chocolate-dial-dual-time-zone-watch-326935-326936-32694014804027_520x.png\"]', 'uploads/products/thumbnail/ZbCVsAwWjzfFpBmqVplKgSCwCGacDlHnMgk3Zx8o.png', 'uploads/products/featured/qhOYKucDm6vKa2mqNkWjTK8SUS0XtMOx2rAV3aWD.png', 'uploads/products/flash_deal/a6bxd3CccTW2GbX1bNAwOZmBdYDNYjAzOA9vlGWD.png', 'youtube', NULL, 'رولکس, اسکای‌دولر, رزگلد, شکلاتی', 'ساعت مچی رولکس اسکای‌دولر با قاب طلای رزگلد 18 عیار و صفحه شکلاتی رنگ با نشان‌های طلایی. مجهز به کالیبر 9001 با نمایش دو منطقه زمانی، تاریخ و ماه. ضد آب تا 100 متر.', 880000000, 780000000, 1, '[]', '[]', '[\"#D2691E\",\"#FFB6C1\"]', NULL, 1, 1, 1, 2, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت رولکس اسکای‌دولر رزگلد شکلاتی', 'خرید ساعت مچی رولکس اسکای‌دولر با قاب رزگلد و صفحه شکلاتی', 'uploads/products/meta/watch-rolex-sky-dweller-rose-gold-chocolate-dial-dual-time-zone-watch-326935-326936-32694014804027_520x.png', NULL, 'ساعت-مچی-رولکس-اسکای‌دولر-رزگلد-شکلاتی-|-مدل-326935-Ycdo9', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:48:53');
INSERT INTO `products` VALUES (70, 'ساعت مچی اُمگا اسپیدمستر کرونو | مدل 1160095730', 'admin', 12, 5, 107, 134, 5, '[\"uploads\\/products\\/photos\\/watch-omega-speedmaster-day-date-chrono-silver-dial-watch-1160095730_520x.png\"]', 'uploads/products/thumbnail/NVLrOS6bDJ5FeGZKkNj8TquA521edVLG1N5r0WBq.png', 'uploads/products/featured/QIy5JIUmudosOIUCLK7NqvUZl4aWqnMeOGZ3bqjx.png', 'uploads/products/flash_deal/NkUe2WRWwbfCGyxa7LF43ruhxNWRtfkcLawy3p4n.png', 'youtube', NULL, 'اُمگا, اسپیدمستر, کرنوگراف, ساعت مچی', 'ساعت مچی اُمگا اسپیدمستر با کرنوگراف و نمایش روز/تاریخ. صفحه نقره‌ای با سه زیرصفحه (ثانیه، دقیقه، ساعت). موتور کوارتز دقیق و بند استیل ضدزنگ. طراحی اسپرت و کلاسیک.', 65000000, 50000000, 1, '[]', '[]', '[\"#C0C0C0\"]', NULL, 1, 1, 0, 4, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت اُمگا اسپیدمستر کرنوگراف', 'خرید ساعت مچی اُمگا اسپیدمستر با کرنوگراف و موتور دقیق کوارتز', 'uploads/products/meta/watch-omega-speedmaster-day-date-chrono-silver-dial-watch-1160095730_520x.png', NULL, 'ساعت-مچی-اُمگا-اسپیدمستر-کرونو-|-مدل-1160095730-TwG58', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:50:03');
INSERT INTO `products` VALUES (71, 'ساعت مچی آویان کو جیمسون مردانه | مدل 151652', 'admin', 12, 104, 1043, 134, 6, '[\"uploads/products/photos/avianne-co-men-s-jamison-collection-watch-151652-31739148107835_520x.jpg\"]', 'uploads/products/thumbnail/avianne-co-men-s-jamison-collection-watch-151652-31739148107835_520x.jpg', 'uploads/products/featured/avianne-co-men-s-jamison-collection-watch-151652-31739148107835_520x.jpg', 'uploads/products/flash_deal/avianne-co-men-s-jamison-collection-watch-151652-31739148107835_520x.jpg', 'youtube', NULL, 'آویان کو, جیمسون, ساعت مردانه, کلکسیون', 'ساعت مچی مردانه از کلکسیون جیمسون برند آویان کو. قاب استیل ضدزنگ با روکش مشکی. صفحه مشکی با کرنوگراف و نمایش تاریخ. بند چرم اصل مشکی. طراحی مدرن و اسپرت با جزئیات نقره‌ای.', 45000000, 35000000, 0, '[]', '[]', '[\"#000000\",\"#C0C0C0\"]', NULL, 1, 1, 0, 5, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو جیمسون مردانه', 'خرید ساعت مچی مردانه آویان کو کلکسیون جیمسون با کرنوگراف', 'uploads/products/meta/avianne-co-men-s-jamison-collection-watch-151652-31739148107835_520x.jpg', NULL, 'avianne-co-jamison-mens-watch', 0.00, '151652', 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 16:49:41');
INSERT INTO `products` VALUES (72, 'ساعت مچی آویان کو اکتاویان PVD طلایی | مدل 216852', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/avianne-co-octavian-collection-men-s-pvd-plated-steel-watch-216852-31739157807163_520x.jpg\"]', 'uploads/products/thumbnail/AMhHoOKhgP7WMgMeotOKinpK1klYSpaYS8JPF7Jw.jpg', 'uploads/products/featured/xJot6x3QxhIV6neqz3dV0aJ2j9gA9QWkSeKc9CqO.jpg', 'uploads/products/flash_deal/4DVnKNnyxZC02k01E8jjWMd0KdSe1Nib1Du8npq1.jpg', 'youtube', NULL, 'آویان کو, اکتاویان, PVD, ساعت مردانه', 'ساعت مچی مردانه از کلکسیون اکتاویان با قاب استیل PVD طلایی. صفحه مشکی با جزئیات طلایی و کرنوگراف. بند استیل طلایی با قفل ایمن. طراحی لوکس و مجلل مناسب مهمانی‌های رسمی.', 55000000, 42000000, 1, '[]', '[]', '[\"#000000\",\"#FFD700\"]', NULL, 1, 1, 1, 4, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو اکتاویان PVD طلایی', 'خرید ساعت مچی مردانه آویان کو اکتاویان با قاب PVD طلایی', 'uploads/products/meta/avianne-co-octavian-collection-men-s-pvd-plated-steel-watch-216852-31739157807163_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-اکتاویان-PVD-طلایی-|-مدل-216852-2oYy8', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:50:46');
INSERT INTO `products` VALUES (73, 'ساعت مچی آویان کو اسنس زنانه الماس | مدل 1183276565', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/diamond-watches-avianne-co-essence-collection-womens-diamond-watch-16-63-ctw-1183276565_520x.jpg\"]', 'uploads/products/thumbnail/VjWKTC3ADM2LZcWpq5BVLSdK9ofk7wEVTkk49Ri9.jpg', 'uploads/products/featured/ugrQLNcRtiRLTwXmmnpNrey9Pi5mpV8ZvvxwTehI.jpg', 'uploads/products/flash_deal/c1CeptjA53yeWAy7eOqv9djGY94dAthmbChECkJi.jpg', 'youtube', NULL, 'آویان کو, اسنس, ساعت زنانه, الماس', 'ساعت مچی زنانه از کلکسیون اسنس با قاب استیل ضدزنگ و ۱۶.۶۳ قیراط الماس برلیان دور صفحه. صفحه مرواریدی با جزئیات ظریف و نشان‌های طلایی. موتور کوارتز دقیق. بند استیل. طراحی شیک و زنان‌ه.', 38000000, 29000000, 1, '[]', '[]', '[\"#C0C0C0\",\"#FFFFFF\"]', NULL, 1, 1, 1, 6, 'دستگاه', 12.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو اسنس زنانه الماس', 'خرید ساعت مچی زنانه آویان کو اسنس با الماس و صفحه مرواریدی', 'uploads/products/meta/diamond-watches-avianne-co-essence-collection-womens-diamond-watch-16-63-ctw-1183276565_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-اسنس-زنانه-الماس-|-مدل-1183276565-t7eJ8', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:52:38');
INSERT INTO `products` VALUES (74, 'ساعت مچی آویان کو سرامیک سفید الماس | مدل W108D', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-ceramic-white-diamond-watch-w108d-29067487019067_520x.jpg\"]', 'uploads/products/thumbnail/dHY3UrYkrhOv10iHQeZYugVKRwGYEwjJDnNbaOB3.jpg', 'uploads/products/featured/watch-avianne-co-ceramic-white-diamond-watch-w108d-29067487019067_520x.jpg', 'uploads/products/flash_deal/rf2LAP11FlEaVKQfrZfeqMVI7gQRyL4xiQBiVcds.jpg', 'youtube', NULL, 'آویان کو, سرامیک, سفید, الماس', 'ساعت مچی با قاب سرامیک سفید و الماس‌های دور صفحه. صفحه سفید با نشان‌های طلایی. بند سرامیک سفید با قفل ایمن. موتور کوارتز سوئیسی. طراحی مدرن و مینیمال مناسب استفاده روزمره.', 42000000, 32000000, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک سفید الماس', 'خرید ساعت مچی آویان کو با قاب سرامیک سفید و الماس', 'uploads/products/meta/DXc1p2fKCad0HAL1MTaWesda3aT27WcVOMgLn9IO.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-سفید-الماس-|-مدل-W108D-CvbVw', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 14:11:50');
INSERT INTO `products` VALUES (75, 'ساعت مچی آویان کو اسنس ۲ قیراط | مدل 8639699615803', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-essence-collection-womens-diamond-watch-2-00-ctw-8639699615803_520x.jpg\"]', 'uploads/products/thumbnail/qfJdd4MYvQg91cVVB4xIj7is37I5wIAfJ0vbNNi9.jpg', 'uploads/products/featured/LtJS9c5euRj97rHh4crQRYMNjvzGLKBdBp60IQZm.jpg', 'uploads/products/flash_deal/Sa3KOGSGJHUprlcl91NCNt3CYhD76Z7GMR1ctOqg.jpg', 'youtube', NULL, 'آویان کو, اسنس, ساعت زنانه, ۲ قیراط', 'ساعت مچی زنانه اسنس با ۲ قیراط الماس برلیان در قاب استیل ضدزنگ. صفحه صورتی با نشان‌های الماس. بند استیل. طراحی ظریف و مجلل مناسب هدیه.', 35000000, 26000000, 1, '[]', '[]', '[\"#FFB6C1\",\"#C0C0C0\"]', NULL, 1, 1, 0, 4, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو اسنس ۲ قیراط الماس', 'خرید ساعت مچی زنانه آویان کو اسنس با ۲ قیراط الماس', 'uploads/products/meta/watch-avianne-co-essence-collection-womens-diamond-watch-2-00-ctw-8639699615803_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-اسنس-۲-قیراط-|-مدل-8639699615803-CeyNh', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:54:14');
INSERT INTO `products` VALUES (76, 'ساعت مچی آویان کو سرامیک مشکی کرنو ۱.۳۲ قیراط | مدل W108BKB', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-black-diamond-watch-1-32-ctw-w108bkb-8639692734523_520x.jpg\"]', 'uploads/products/thumbnail/bXcSViwSOjSa7eHOBVuFyquWdcqb54vUbjUPc3RB.jpg', 'uploads/products/featured/0RameZvA8QUbbPZMtwWWaibfyvC03kn8poPiYaFp.jpg', 'uploads/products/flash_deal/qEoKlAiLbo29ys6amk6ym9DS98J7qEmh9O2ZpWgF.jpg', 'youtube', NULL, 'آویان کو, سرامیک مشکی, کرنوگراف, الماس سیاه', 'ساعت مچی مردانه با قاب سرامیک مشکی و استیل ضدزنگ. کرنوگراف با نمایش روز/تاریخ. ۱.۳۲ قیراط الماس سیاه روی صفحه. بند استیل مشکی با قفل ایمن. طراحی اسپرت و لوکس.', 62000000, 48000000, 1, '[]', '[]', '[\"#000000\"]', NULL, 1, 1, 1, 4, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک مشکی کرنو', 'خرید ساعت مچی مردانه آویان کو با سرامیک مشکی و کرنوگراف', 'uploads/products/meta/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-black-diamond-watch-1-32-ctw-w108bkb-8639692734523_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-مشکی-کرنو-۱.۳۲-قیراط-|-مدل-W108BKB-OwZzL', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:55:41');
INSERT INTO `products` VALUES (77, 'ساعت مچی آویان کو سرامیک مشکی کرنو ۳.۸۳ قیراط | مدل W108BKA', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-black-diamond-watch-3-83-ctw-w108bka-8639692570683_520x.jpg\"]', 'uploads/products/thumbnail/ZWRQdMRPwCjCpeaE9QAYtIhaGWlZba5EjGWIp3uv.jpg', 'uploads/products/featured/pughMY6z4EF7W3tpB9b8gPFS913eOq8mWHGGTblt.jpg', 'uploads/products/flash_deal/axaMIRk46GsqSMMq6Hkbk6IWjOaKqzEdlYW0zpKO.jpg', 'youtube', NULL, 'آویان کو, سرامیک مشکی, کرنوگراف, ۳.۸۳ قیراط', 'ساعت مچی مردانه با قاب سرامیک مشکی و ۳.۸۳ قیراط الماس سیاه برلیان. کرنوگراف پیشرفته با نمایش روز و تاریخ. صفحه مشکی با جزئیات طلایی. بند استیل مشکی با قفل ایمن.', 72000000, 55000000, 1, '[]', '[]', '[\"#000000\",\"#FFD700\"]', NULL, 1, 1, 1, 3, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک مشکی ۳.۸۳ قیراط', 'خرید ساعت مچی آویان کو با سرامیک مشکی و ۳.۸۳ قیراط الماس', 'uploads/products/meta/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-black-diamond-watch-3-83-ctw-w108bka-8639692570683_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-مشکی-کرنو-۳.۸۳-قیراط-|-مدل-W108BKA-5lb6O', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:56:33');
INSERT INTO `products` VALUES (78, 'ساعت مچی آویان کو سرامیک مشکی کرنو ۱.۳۲ قیراط | مدل W108BKD', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-diamond-watch-1-32-ctw-w108bkd-8639692800059_520x.jpg\"]', 'uploads/products/thumbnail/NunBqy66zZ91d1agmt4ejlFL1Lq2XH86CzntFz1c.jpg', 'uploads/products/featured/lHwRD7mGaLQjgxIKMBZmhU2k64XGjK0Cpbph1ETA.jpg', 'uploads/products/flash_deal/c9CT88oRk9EWWrGIo5bynHff3GMfSpAo8q4Wl9JP.jpg', 'youtube', NULL, 'آویان کو, سرامیک مشکی, کرنوگراف, الماس', 'ساعت مچی مردانه با قاب سرامیک مشکی و استیل ضدزنگ. کرنوگراف با نمایش روز/تاریخ. ۱.۳۲ قیراط الماس سفید برلیان روی صفحه. بند استیل مشکی. طراحی اسپرت و لوکس.', 60000000, 46000000, 1, '[]', '[]', '[\"#000000\"]', NULL, 1, 1, 0, 4, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک مشکی کرنو الماس', 'خرید ساعت مچی آویان کو با سرامیک مشکی و الماس سفید', 'uploads/products/meta/watch-avianne-co-mens-ceramic-stainless-steel-black-chrono-diamond-watch-1-32-ctw-w108bkd-8639692800059_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-مشکی-کرنو-۱.۳۲-قیراط-|-مدل-W108BKD-Fiq71', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:57:30');
INSERT INTO `products` VALUES (79, 'ساعت مچی آویان کو پرنس PVD الماس ۵.۶۵ قیراط | مدل 8639695355963', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-mens-prince-collection-pvd-diamond-watch-5-65-ctw-8639695355963_520x.jpg\"]', 'uploads/products/thumbnail/Nj8JgBo8upboSxZazms6czUFfQm9SPU3LyK9rhsc.jpg', 'uploads/products/featured/ckdxsWCIiLhZOo4QKRmUshSD1Ddu3XigEgz9DI5j.jpg', 'uploads/products/flash_deal/lBM15aEnWTq9W0Frotur08VRLz0a7cyT71WiqsZx.jpg', 'youtube', NULL, 'آویان کو, پرنس, PVD, الماس ۵.۶۵ قیراط', 'ساعت مچی مردانه از کلکسیون پرنس با قاب استیل PVD طلایی و ۵.۶۵ قیراط الماس برلیان. صفحه مشکی با جزئیات طلایی و کرنوگراف. بند استیل طلایی با قفل ایمن. طراحی سلطنتی و مجلل.', 85000000, 65000000, 1, '[]', '[]', '[\"#000000\",\"#FFD700\"]', NULL, 1, 1, 1, 3, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو پرنس PVD الماس ۵.۶۵ قیراط', 'خرید ساعت مچی مردانه آویان کو پرنس با PVD طلایی و ۵.۶۵ قیراط الماس', 'uploads/products/meta/watch-avianne-co-mens-prince-collection-pvd-diamond-watch-5-65-ctw-8639695355963_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-پرنس-PVD-الماس-۵.۶۵-قیراط-|-مدل-8639695355963-ZWcMS', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:58:22');
INSERT INTO `products` VALUES (80, 'ساعت مچی آویان کو توربیلون PVD طلایی | مدل 8638678532155', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-mens-steel-tourbillon-pvd-plated-diamond-watch-8638678532155_520x.jpg\"]', 'uploads/products/thumbnail/Fgb5pne8gQBG12k6tpyxNrl1LVmI1fR2TSJQRoGe.jpg', 'uploads/products/featured/d2406LmKyvn7ruuksHIOaIG8FDi5E7z0VOcAicwm.jpg', 'uploads/products/flash_deal/6VnoxycvZmg9Vrb5Dijh3iKNRufMLfNyuwOuxDXN.jpg', 'youtube', NULL, 'آویان کو, توربیلون, PVD, الماس', 'ساعت مچی مردانه با توربیلون نمایان و قاب استیل PVD طلایی. صفحه مشکی با الماس‌های دور صفحه و نشان‌های طلایی. بند چرم تمساح اصل قهوه‌ای. طراحی مکانیکی لوکس با موتور اتوماتیک.', 95000000, 72000000, 1, '[]', '[]', '[\"#FFD700\",\"#8B4513\"]', NULL, 1, 1, 1, 2, 'دستگاه', 5.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو توربیلون PVD طلایی', 'خرید ساعت مچی آویان کو با توربیلون و قاب PVD طلایی', 'uploads/products/meta/watch-avianne-co-mens-steel-tourbillon-pvd-plated-diamond-watch-8638678532155_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-توربیلون-PVD-طلایی-|-مدل-8638678532155-QG78p', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 14:00:14');
INSERT INTO `products` VALUES (81, 'ساعت مچی آویان کو اسولت زنانه ۱.۸۵ قیراط | مدل W15625L', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-svelte-womens-diamond-watch-1-85-ctw-w15625l-8639699124283_520x.jpg\"]', 'uploads/products/thumbnail/50dm8Q6x1NJPodXI3QCWC6dB3qQ8XxBBrfHFRxDj.jpg', 'uploads/products/featured/278DZ2loXNWBxDmcG5sipm20NBNM0RiJ0f1sgh2S.jpg', 'uploads/products/flash_deal/aHlVFaOvhwJ1II53TAqNxJnCXnofuTfFtblSS273.jpg', 'youtube', NULL, 'آویان کو, اسولت, ساعت زنانه, ۱.۸۵ قیراط', 'ساعت مچی زنانه اسولت با قاب استیل ضدزنگ و ۱.۸۵ قیراط الماس برلیان. صفحه مرواریدی با جزئیات ظریف و نشان‌های طلایی. بند استیل. طراحی باریک و مینیمال مناسب مچ‌های ظریف.', 28000000, 21000000, 1, '[]', '[]', '[\"#C0C0C0\",\"#FFFFFF\"]', NULL, 1, 1, 0, 6, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو اسولت زنانه ۱.۸۵ قیراط', 'خرید ساعت مچی زنانه آویان کو اسولت با ۱.۸۵ قیراط الماس', 'uploads/products/meta/watch-avianne-co-svelte-womens-diamond-watch-1-85-ctw-w15625l-8639699124283_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-اسولت-زنانه-۱.۸۵-قیراط-|-مدل-W15625L-YhrTS', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 13:59:24');
INSERT INTO `products` VALUES (82, 'ساعت مچی آویان کو سرامیک سفید کرنو | مدل W108B', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-unisex-ceramic-stainless-steel-white-chrono-diamond-watch-1-60-ctw-w108b-31562902929467_520x.jpg\"]', 'uploads/products/thumbnail/aGQMUsb57U1VNNcIHd9G5aILmcAt0pp1xnPLc8VG.jpg', 'uploads/products/featured/O9apb4ZgovbFVC9XlFyTZC3X2XCnnBksqDhtqwc3.jpg', 'uploads/products/flash_deal/tr5Lp1lblkfhZAt1T9htOrlZfNIIm58sg1iL76kH.jpg', 'youtube', NULL, 'آویان کو, سرامیک سفید, کرنوگراف, یونیسکس', 'ساعت مچی یونیسکس با قاب سرامیک سفید و استیل ضدزنگ. کرنوگراف با نمایش روز/تاریخ. ۱.۶۰ قیراط الماس برلیان دور صفحه. بند استیل سفید با قفل ایمن. طراحی مدرن و شیک.', 48000000, 36000000, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 1, 5, 'دستگاه', 8.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک سفید کرنو', 'خرید ساعت مچی یونیسکس آویان کو با سرامیک سفید و کرنوگراف', 'uploads/products/meta/watch-avianne-co-unisex-ceramic-stainless-steel-white-chrono-diamond-watch-1-60-ctw-w108b-31562902929467_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-سفید-کرنو-|-مدل-W108B-wTmLm', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 14:01:35');
INSERT INTO `products` VALUES (83, 'ساعت مچی آویان کو سرامیک سفید مینیمال | مدل W108A', 'admin', 12, 5, 107, 134, 6, '[\"uploads\\/products\\/photos\\/watch-avianne-co-white-ceramic-diamond-watch-w108a-28506452852795_520x.jpg\"]', 'uploads/products/thumbnail/cyJbC4OtPHyLwV3O1sTbujk4zhn6DntgDAKaB9Aq.jpg', 'uploads/products/featured/watch-avianne-co-white-ceramic-diamond-watch-w108a-28506452852795_520x.jpg', 'uploads/products/flash_deal/J4BvXICmKDwRyTQCyxWBnpneST5Zb8nisXB3c4DO.jpg', 'youtube', NULL, 'آویان کو, سرامیک سفید, الماس, مینیمال', 'ساعت مچی با قاب سرامیک سفید و الماس‌های دور صفحه. صفحه سفید با نشان‌های طلایی. بند سرامیک سفید با قفل ایمن. طراحی مینیمال و شیک مناسب استفاده روزمره و مهمانی.', 38000000, 28000000, 1, '[]', '[]', '[\"#FFFFFF\"]', NULL, 1, 1, 0, 6, 'دستگاه', 10.00, 'percent', 0.00, 'amount', 'flat_rate', 500000.00, 0, 'ساعت آویان کو سرامیک سفید مینیمال', 'خرید ساعت مچی آویان کو با سرامیک سفید و طراحی مینیمال', 'uploads/products/meta/watch-avianne-co-white-ceramic-diamond-watch-w108a-28506452852795_520x.jpg', NULL, 'ساعت-مچی-آویان-کو-سرامیک-سفید-مینیمال-|-مدل-W108A-vccaO', 0.00, NULL, 0, NULL, NULL, '2026-09-09 16:49:41', '2026-09-09 14:14:49');

-- ----------------------------
-- Table structure for repair_orders
-- ----------------------------
DROP TABLE IF EXISTS `repair_orders`;
CREATE TABLE `repair_orders`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `jewelry_certificate_id` int UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `repair_type` enum('cleaning','restoration','resizing','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `location` enum('workshop','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'workshop',
  `scheduled_date` date NULL DEFAULT NULL,
  `estimated_delivery_date` date NULL DEFAULT NULL,
  `estimated_cost` decimal(15, 2) NULL DEFAULT NULL,
  `final_cost` decimal(15, 2) NULL DEFAULT NULL,
  `assigned_to` int UNSIGNED NULL DEFAULT NULL,
  `priority` enum('normal','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'normal',
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','in_progress','completed','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `completion_date` date NULL DEFAULT NULL,
  `delivery_date` date NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `repair_orders_order_number_unique`(`order_number` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of repair_orders
-- ----------------------------

-- ----------------------------
-- Table structure for repair_status_histories
-- ----------------------------
DROP TABLE IF EXISTS `repair_status_histories`;
CREATE TABLE `repair_status_histories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `repair_order_id` bigint UNSIGNED NOT NULL,
  `status` enum('pending','in_progress','completed','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of repair_status_histories
-- ----------------------------

-- ----------------------------
-- Table structure for repair_technicians
-- ----------------------------
DROP TABLE IF EXISTS `repair_technicians`;
CREATE TABLE `repair_technicians`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `specialty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `experience_years` int NULL DEFAULT NULL,
  `certificate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `repair_technicians_user_id_unique`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of repair_technicians
-- ----------------------------
INSERT INTO `repair_technicians` VALUES (1, 2, 'طلاسازی', 5, NULL, 1, '2026-08-22 21:38:12', '2026-08-22 21:38:12');

-- ----------------------------
-- Table structure for reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `rating` int NOT NULL DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` int NOT NULL DEFAULT 1,
  `viewed` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of reviews
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------

-- ----------------------------
-- Table structure for searches
-- ----------------------------
DROP TABLE IF EXISTS `searches`;
CREATE TABLE `searches`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `query` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of searches
-- ----------------------------

-- ----------------------------
-- Table structure for sellers
-- ----------------------------
DROP TABLE IF EXISTS `sellers`;
CREATE TABLE `sellers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `verification_status` tinyint(1) NOT NULL DEFAULT 0,
  `verification_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cash_on_delivery_status` tinyint(1) NOT NULL DEFAULT 0,
  `sslcommerz_status` tinyint(1) NOT NULL DEFAULT 0,
  `stripe_status` tinyint(1) NOT NULL DEFAULT 0,
  `paypal_client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paypal_client_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ssl_store_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ssl_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stripe_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stripe_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `instamojo_status` tinyint(1) NOT NULL DEFAULT 0,
  `instamojo_api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `instamojo_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `razorpay_status` tinyint(1) NOT NULL DEFAULT 0,
  `razorpay_api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `razorpay_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paypal_status` tinyint(1) NOT NULL DEFAULT 0,
  `admin_to_pay` double(20, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sellers
-- ----------------------------

-- ----------------------------
-- Table structure for seo_settings
-- ----------------------------
DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE `seo_settings`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `twitter_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `keyword` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `author` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `revisit` int UNSIGNED NOT NULL DEFAULT 7,
  `sitemap_link` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_settings
-- ----------------------------
INSERT INTO `seo_settings` VALUES (1, 'پلتفرم اختصاصی طلا و جواهرات', 'پلتفرم اختصاصی طلا و جواهرات', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-07 09:06:26', '2026-09-07 09:06:26', '', 'پلتفرم اختصاصی طلا و جواهرات', 7, NULL, 'پلتفرم اختصاصی طلا و جواهرات');

-- ----------------------------
-- Table structure for shops
-- ----------------------------
DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sliders` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `google` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `youtube` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `delivery_pickup_latitude` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `delivery_pickup_longitude` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `shops_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of shops
-- ----------------------------

-- ----------------------------
-- Table structure for sliders
-- ----------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `published` int NOT NULL DEFAULT 1,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sliders
-- ----------------------------
INSERT INTO `sliders` VALUES (1, 'uploads/sliders/slider-1.jpg', 1, '#', '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `sliders` VALUES (2, 'uploads/sliders/slider-2.jpg', 1, '#', '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `sliders` VALUES (3, 'uploads/sliders/slider-3.jpg', 1, '#', '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `sliders` VALUES (4, 'uploads/sliders/slider-4.jpg', 1, '#', '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `sliders` VALUES (5, 'uploads/sliders/slider-5.jpg', 1, '#', '2026-08-22 21:38:12', '2026-08-22 21:38:12');

-- ----------------------------
-- Table structure for staff
-- ----------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of staff
-- ----------------------------

-- ----------------------------
-- Table structure for sub_categories
-- ----------------------------
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE `sub_categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_category_id`(`category_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1083 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sub_categories
-- ----------------------------
INSERT INTO `sub_categories` VALUES (105, 'طلا و سکه مردانه', 4, 'gold-male', 'طلا و سکه مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (106, 'طلا و سکه زنانه', 4, 'gold-female', 'طلا و سکه زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (107, 'ساعت مردانه', 5, 'watch-male', 'ساعت مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (108, 'ساعت زنانه', 5, 'watch-female', 'ساعت زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (109, 'جواهرات مردانه', 6, 'jewelry-male', 'جواهرات مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (110, 'جواهرات زنانه', 6, 'jewelry-female', 'جواهرات زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (111, 'جواهرات مردانه', 7, 'jewelry-male', 'جواهرات مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (112, 'جواهرات زنانه', 7, 'jewelry-female', 'جواهرات زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (113, 'انگشتر و دستبند مردانه', 8, 'ring-bracelet-male', 'انگشتر و دستبند مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (114, 'انگشتر و دستبند زنانه', 8, 'ring-bracelet-female', 'انگشتر و دستبند زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (115, 'گردنبند و پابند مردانه', 9, 'necklace-anklet-male', 'گردنبند و پابند مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (116, 'گردنبند و پابند زنانه', 9, 'necklace-anklet-female', 'گردنبند و پابند زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (117, 'سنگ‌های قیمتی مردانه', 12, 'gemstone-male', 'سنگ‌های قیمتی مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (118, 'سنگ‌های قیمتی زنانه', 12, 'gemstone-female', 'سنگ‌های قیمتی زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (119, 'اکسسوری طلا مردانه', 13, 'accessory-gold-male', 'اکسسوری طلا مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (120, 'اکسسوری طلا زنانه', 13, 'accessory-gold-female', 'اکسسوری طلا زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (121, 'هدیه و اقلام لوکس مردانه', 14, 'gift-luxury-male', 'هدیه و اقلام لوکس مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (122, 'هدیه و اقلام لوکس زنانه', 14, 'gift-luxury-female', 'هدیه و اقلام لوکس زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (123, 'لوازم جانبی ساعت و جواهر مردانه', 15, 'accessory-watch-jewelry-male', 'لوازم جانبی ساعت و جواهر مردانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (124, 'لوازم جانبی ساعت و جواهر زنانه', 15, 'accessory-watch-jewelry-female', 'لوازم جانبی ساعت و جواهر زنانه', '', '2025-12-31 16:05:17', '2025-12-31 16:05:17');
INSERT INTO `sub_categories` VALUES (1000, 'آرایشی صورت', 100, 'face-makeup', 'آرایشی صورت', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1001, 'آرایشی چشم', 100, 'eye-makeup', 'آرایشی چشم', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1002, 'آرایشی لب', 100, 'lip-makeup', 'آرایشی لب', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1003, 'بهداشت پوست', 100, 'skin-care', 'بهداشت پوست', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1004, 'عطر و ادکلن', 100, 'perfume-cologne', 'عطر و ادکلن', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1005, 'لوازم جانبی آرایش', 100, 'makeup-tools', 'لوازم جانبی آرایش', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1010, 'ابزار برقی', 101, 'power-tools', 'ابزار برقی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1011, 'ابزار دستی', 101, 'hand-tools', 'ابزار دستی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1012, 'یراق آلات ساختمانی', 101, 'building-hardware', 'یراق آلات ساختمانی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1013, 'ابزار باغبانی', 101, 'gardening-tools', 'ابزار باغبانی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1014, 'تجهیزات ایمنی', 101, 'safety-equipment', 'تجهیزات ایمنی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1020, 'لوازم آشپزخانه', 102, 'kitchen-appliances', 'لوازم آشپزخانه', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1021, 'لوازم برقی خانگی', 102, 'home-electrical', 'لوازم برقی خانگی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1022, 'دکوراسیون منزل', 102, 'home-decor', 'دکوراسیون منزل', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1023, 'مبلمان و سرویس خواب', 102, 'furniture-bedroom', 'مبلمان و سرویس خواب', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1024, 'نورپردازی', 102, 'lighting', 'نورپردازی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1030, 'گوشی موبایل', 103, 'mobile-phones', 'گوشی موبایل', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1031, 'تبلت', 103, 'tablets', 'تبلت', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1032, 'لپ تاپ و کامپیوتر', 103, 'laptop-computer', 'لپ تاپ و کامپیوتر', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1033, 'لوازم جانبی موبایل', 103, 'mobile-accessories', 'لوازم جانبی موبایل', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1034, 'هدفون و هدست', 103, 'headphones-headsets', 'هدفون و هدست', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1035, 'ساعت هوشمند', 103, 'smart-watches', 'ساعت هوشمند', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1040, 'پوشاک مردانه', 104, 'mens-clothing', 'پوشاک مردانه', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1041, 'پوشاک زنانه', 104, 'womens-clothing', 'پوشاک زنانه', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1042, 'کفش و کیف', 104, 'shoes-bags', 'کفش و کیف', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1043, 'اکسسوری', 104, 'accessories', 'اکسسوری', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1050, 'تجهیزات ورزشی', 105, 'sports-equipment', 'تجهیزات ورزشی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1051, 'لباس ورزشی', 105, 'sportswear', 'لباس ورزشی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1052, 'لوازم سفر', 105, 'travel-gear', 'لوازم سفر', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1053, 'کوله پشتی و چمدان', 105, 'backpacks-luggage', 'کوله پشتی و چمدان', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1060, 'کتاب فارسی', 106, 'persian-books', 'کتاب فارسی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1061, 'کتاب خارجی', 106, 'foreign-books', 'کتاب خارجی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1062, 'لوازم تحریر', 106, 'stationery', 'لوازم تحریر', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1063, 'نوشت افزار', 106, 'writing-tools', 'نوشت افزار', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1070, 'اسباب بازی فکری', 107, 'educational-toys', 'اسباب بازی فکری', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1071, 'عروسک و حیوانات', 107, 'dolls-animals', 'عروسک و حیوانات', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1072, 'بازی های رومیزی', 107, 'board-games', 'بازی های رومیزی', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1080, 'لوازم بارداری', 108, 'pregnancy-supplies', 'لوازم بارداری', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1081, 'لوازم نوزاد', 108, 'baby-supplies', 'لوازم نوزاد', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');
INSERT INTO `sub_categories` VALUES (1082, 'پوشاک کودک', 108, 'kids-clothing', 'پوشاک کودک', NULL, '2026-05-15 13:33:17', '2026-05-15 13:33:17');

-- ----------------------------
-- Table structure for sub_sub_categories
-- ----------------------------
DROP TABLE IF EXISTS `sub_sub_categories`;
CREATE TABLE `sub_sub_categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub_category_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_sub_category_id`(`sub_category_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 284 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sub_sub_categories
-- ----------------------------
INSERT INTO `sub_sub_categories` VALUES (130, 105, 'سکه امامی', 'gold-coin-imami', 'سکه امامی', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (131, 105, 'شمش طلا', 'gold-bar', 'شمش طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (132, 106, 'انگشتر طلا', 'gold-ring', 'انگشتر طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (133, 106, 'دستبند طلا', 'gold-bracelet', 'دستبند طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (134, 107, 'ساعت کلاسیک مردانه', 'classic-watch-male', 'ساعت کلاسیک مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (135, 107, 'ساعت اسپرت مردانه', 'sport-watch-male', 'ساعت اسپرت مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (136, 108, 'ساعت کلاسیک زنانه', 'classic-watch-female', 'ساعت کلاسیک زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (137, 108, 'ساعت مجلسی زنانه', 'luxury-watch-female', 'ساعت مجلسی زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (138, 109, 'انگشتر جواهر مردانه', 'ring-jewelry-male', 'انگشتر جواهر مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (139, 109, 'دستبند جواهر مردانه', 'bracelet-jewelry-male', 'دستبند جواهر مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (140, 110, 'گردنبند جواهر زنانه', 'necklace-jewelry-female', 'گردنبند جواهر زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (141, 110, 'گوشواره جواهر زنانه', 'earring-jewelry-female', 'گوشواره جواهر زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (142, 111, 'انگشتر طلا مردانه', 'gold-ring-male', 'انگشتر طلا مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (143, 111, 'دستبند طلا مردانه', 'gold-bracelet-male', 'دستبند طلا مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (144, 112, 'انگشتر طلا زنانه', 'gold-ring-female', 'انگشتر طلا زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (145, 112, 'دستبند طلا زنانه', 'gold-bracelet-female', 'دستبند طلا زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (146, 113, 'گردنبند طلا مردانه', 'gold-necklace-male', 'گردنبند طلا مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (147, 113, 'پابند طلا مردانه', 'gold-anklet-male', 'پابند طلا مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (148, 114, 'گردنبند طلا زنانه', 'gold-necklace-female', 'گردنبند طلا زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (149, 114, 'پابند طلا زنانه', 'gold-anklet-female', 'پابند طلا زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (150, 115, 'یاقوت سرخ', 'ruby-male', 'یاقوت سرخ', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (151, 115, 'زمرد', 'emerald-male', 'زمرد', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (152, 116, 'یاقوت سرخ زنانه', 'ruby-female', 'یاقوت سرخ زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (153, 116, 'زمرد زنانه', 'emerald-female', 'زمرد زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (154, 117, 'دکمه سردست طلا', 'cufflink-gold-male', 'دکمه سردست طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (155, 117, 'سنجاق یقه طلا', 'tiepin-gold-male', 'سنجاق یقه طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (156, 118, 'سنجاق سر طلا', 'hairpin-gold-female', 'سنجاق سر طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (157, 118, 'گیره لباس طلا', 'clothing-pin-gold-female', 'گیره لباس طلا', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (158, 119, 'ست هدیه مردانه', 'gift-set-male', 'ست هدیه مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (159, 119, 'جعبه هدیه مردانه', 'gift-box-male', 'جعبه هدیه مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (160, 120, 'ست هدیه زنانه', 'gift-set-female', 'ست هدیه زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (161, 120, 'جعبه هدیه زنانه', 'gift-box-female', 'جعبه هدیه زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (162, 121, 'بند ساعت مردانه', 'watch-strap-male', 'بند ساعت مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (163, 121, 'جعبه ساعت مردانه', 'watch-box-male', 'جعبه ساعت مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (164, 122, 'بند ساعت زنانه', 'watch-strap-female', 'بند ساعت زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (165, 122, 'جعبه ساعت زنانه', 'watch-box-female', 'جعبه ساعت زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (166, 123, 'گردنبند مردانه', 'necklace-jewelry-male', 'گردنبند مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (167, 123, 'گوشواره مردانه', 'earring-jewelry-male', 'گوشواره مردانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (168, 124, 'گردنبند زنانه', 'necklace-jewelry-female', 'گردنبند زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (169, 124, 'گوشواره زنانه', 'earring-jewelry-female', 'گوشواره زنانه', '', '2025-12-31 16:13:55', '2025-12-31 16:13:55');
INSERT INTO `sub_sub_categories` VALUES (170, 1000, 'کرم پودر', 'foundation', 'کرم پودر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (171, 1000, 'کانسیلر', 'concealer', 'کانسیلر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (172, 1000, 'پنکیک', 'pancake', 'پنکیک', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (173, 1000, 'پودر فیکس', 'setting-powder', 'پودر فیکس', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (174, 1001, 'سایه چشم', 'eyeshadow', 'سایه چشم', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (175, 1001, 'خط چشم', 'eyeliner', 'خط چشم', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (176, 1001, 'مژه مصنوعی', 'false-lashes', 'مژه مصنوعی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (177, 1001, 'ریمل', 'mascara', 'ریمل', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (178, 1001, 'مداد چشم', 'eyebrow-pencil', 'مداد چشم', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (179, 1002, 'رژ لب', 'lipstick', 'رژ لب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (180, 1002, 'گلاس لب', 'lip-gloss', 'گلاس لب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (181, 1002, 'خط لب', 'lip-liner', 'خط لب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (182, 1002, 'بالم لب', 'lip-balm', 'بالم لب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (183, 1003, 'ضد آفتاب', 'sunscreen', 'ضد آفتاب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (184, 1003, 'مرطوب کننده', 'moisturizer', 'مرطوب کننده', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (185, 1003, 'تونر', 'toner', 'تونر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (186, 1003, 'ماسک صورت', 'face-mask', 'ماسک صورت', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (187, 1003, 'پاک کننده صورت', 'facial-cleanser', 'پاک کننده صورت', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (188, 1004, 'عطر مردانه', 'mens-perfume', 'عطر مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (189, 1004, 'عطر زنانه', 'womens-perfume', 'عطر زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (190, 1004, 'ادکلن', 'cologne', 'ادکلن', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (191, 1004, 'اسپری بدن', 'body-spray', 'اسپری بدن', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (192, 1005, 'براش آرایشی', 'makeup-brush', 'براش آرایشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (193, 1005, 'اسفنج آرایشی', 'makeup-sponge', 'اسفنج آرایشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (194, 1005, 'کیف آرایش', 'makeup-bag', 'کیف آرایش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (195, 1005, 'دستگاه بخور صورت', 'facial-steamer', 'دستگاه بخور صورت', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (196, 1010, 'دریل و پیچ گوشتی', 'drill-screwdriver', 'دریل و پیچ گوشتی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (197, 1010, 'اره برقی', 'electric-saw', 'اره برقی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (198, 1010, 'سنگ سنباده', 'sander', 'سنگ سنباده', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (199, 1010, 'فرز نجاری', 'router', 'فرز نجاری', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (200, 1011, 'آچار و انبردست', 'wrench-pliers', 'آچار و انبردست', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (201, 1011, 'پیچ گوشتی', 'screwdriver', 'پیچ گوشتی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (202, 1011, 'چکش', 'hammer', 'چکش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (203, 1011, 'آچار آلن', 'allen-key', 'آچار آلن', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (204, 1012, 'دستگیره درب', 'door-handle', 'دستگیره درب', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (205, 1012, 'قفل و کلید', 'lock-key', 'قفل و کلید', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (206, 1012, 'لولا', 'hinge', 'لولا', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (207, 1012, 'پیچ و رولپلاک', 'screw-anchor', 'پیچ و رولپلاک', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (208, 1020, 'قابلمه و تابه', 'pots-pans', 'قابلمه و تابه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (209, 1020, 'ظروف سرویس', 'dinnerware', 'ظروف سرویس', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (210, 1020, 'چاقو و وسایل برش', 'knives-cutlery', 'چاقو و وسایل برش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (211, 1020, 'لوازم شیرینی پزی', 'baking-supplies', 'لوازم شیرینی پزی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (212, 1020, 'سرویس قاشق و چنگال', 'cutlery-set', 'سرویس قاشق و چنگال', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (213, 1021, 'یخچال و فریزر', 'refrigerator', 'یخچال و فریزر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (214, 1021, 'ماشین لباسشویی', 'washing-machine', 'ماشین لباسشویی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (215, 1021, 'جاروبرقی', 'vacuum-cleaner', 'جاروبرقی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (216, 1021, 'مایکروویو', 'microwave', 'مایکروویو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (217, 1021, 'مخلوط کن و غذاساز', 'blender-food-processor', 'مخلوط کن و غذاساز', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (218, 1022, 'تابلو و قاب عکس', 'painting-photoframe', 'تابلو و قاب عکس', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (219, 1022, 'گلدان و گل مصنوعی', 'vase-artificial-flower', 'گلدان و گل مصنوعی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (220, 1022, 'ساعت دیواری', 'wall-clock', 'ساعت دیواری', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (221, 1022, 'شمع و لوازم تزئینی', 'candle-decoration', 'شمع و لوازم تزئینی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (222, 1030, 'گوشی سامسونگ', 'samsung-phone', 'گوشی سامسونگ', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (223, 1030, 'گوشی اپل', 'apple-iphone', 'گوشی اپل', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (224, 1030, 'گوشی شیائومی', 'xiaomi-phone', 'گوشی شیائومی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (225, 1030, 'گوشی هوآوی', 'huawei-phone', 'گوشی هوآوی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (226, 1030, 'گوشی نوکیا', 'nokia-phone', 'گوشی نوکیا', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (227, 1031, 'تبلت سامسونگ', 'samsung-tablet', 'تبلت سامسونگ', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (228, 1031, 'تبلت اپل آیپد', 'apple-ipad', 'تبلت اپل آیپد', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (229, 1031, 'تبلت لنوو', 'lenovo-tablet', 'تبلت لنوو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (230, 1031, 'تبلت هواوی', 'huawei-tablet', 'تبلت هواوی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (231, 1032, 'لپ تاپ ایسوس', 'asus-laptop', 'لپ تاپ ایسوس', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (232, 1032, 'لپ تاپ لنوو', 'lenovo-laptop', 'لپ تاپ لنوو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (233, 1032, 'لپ تاپ اچ پی', 'hp-laptop', 'لپ تاپ اچ پی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (234, 1032, 'لپ تاپ دل', 'dell-laptop', 'لپ تاپ دل', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (235, 1032, 'کیس و قطعات کامپیوتر', 'computer-pc-parts', 'کیس و قطعات کامپیوتر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (236, 1033, 'قاب و کاور موبایل', 'phone-case', 'قاب و کاور موبایل', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (237, 1033, 'محافظ صفحه نمایش', 'screen-protector', 'محافظ صفحه نمایش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (238, 1033, 'پاوربانک', 'powerbank', 'پاوربانک', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (239, 1033, 'شارژر و کابل', 'charger-cable', 'شارژر و کابل', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (240, 1033, 'هولدر و پایه خودرو', 'car-holder', 'هولدر و پایه خودرو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (241, 1034, 'هدفون بی سیم', 'wireless-headphone', 'هدفون بی سیم', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (242, 1034, 'هدفون سیمی', 'wired-headphone', 'هدفون سیمی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (243, 1034, 'هدست گیمینگ', 'gaming-headset', 'هدست گیمینگ', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (244, 1034, 'هدفون True Wireless', 'tw-headphone', 'هدفون True Wireless', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (245, 1040, 'تیشرت و پولوشرت مردانه', 'mens-tshirt-polo', 'تیشرت و پولوشرت مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (246, 1040, 'پیراهن مردانه', 'mens-shirt', 'پیراهن مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (247, 1040, 'شلوار مردانه', 'mens-pants', 'شلوار مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (248, 1040, 'کت و شلوار', 'suit-blazer', 'کت و شلوار', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (249, 1040, 'ژاکت و هودی', 'hoodie-sweater', 'ژاکت و هودی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (250, 1040, 'کت و پالتو', 'coat-jacket', 'کت و پالتو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (251, 1041, 'بلوز و تاپ زنانه', 'womens-blouse-top', 'بلوز و تاپ زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (252, 1041, 'شلوار زنانه', 'womens-pants', 'شلوار زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (253, 1041, 'لباس و مانتو', 'dress-manteau', 'لباس و مانتو', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (254, 1041, 'دامن', 'skirt', 'دامن', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (255, 1041, 'کت و جلیقه', 'jacket-vest', 'کت و جلیقه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (256, 1041, 'شومیز', 'shirt-blouse', 'شومیز', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (257, 1042, 'کفش مردانه', 'mens-shoes', 'کفش مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (258, 1042, 'کفش زنانه', 'womens-shoes', 'کفش زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (259, 1042, 'کفش ورزشی', 'sports-shoes', 'کفش ورزشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (260, 1042, 'کیف مردانه', 'mens-bag', 'کیف مردانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (261, 1042, 'کیف زنانه', 'womens-bag', 'کیف زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (262, 1042, 'کوله پشتی', 'backpack', 'کوله پشتی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (263, 1043, 'عینک آفتابی', 'sunglasses', 'عینک آفتابی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (264, 1043, 'کلاه', 'hat-cap', 'کلاه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (265, 1043, 'شال و روسری', 'scarf-shawl', 'شال و روسری', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (266, 1043, 'کمربند', 'belt', 'کمربند', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (267, 1050, 'لوازم بدنسازی', 'fitness-equipment', 'لوازم بدنسازی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (268, 1050, 'لوازم فوتبال', 'football-gear', 'لوازم فوتبال', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (269, 1050, 'لوازم شنا', 'swimming-gear', 'لوازم شنا', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (270, 1050, 'لوازم کوهنوردی', 'mountaineering-gear', 'لوازم کوهنوردی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (271, 1051, 'تیشرت ورزشی', 'sports-tshirt', 'تیشرت ورزشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (272, 1051, 'شلوار ورزشی', 'sports-pants', 'شلوار ورزشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (273, 1051, 'کفش ورزشی', 'sports-shoes', 'کفش ورزشی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (274, 1051, 'لباس ورزشی زنانه', 'womens-sportswear', 'لباس ورزشی زنانه', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (275, 1052, 'چمدان و ساک', 'suitcase-bag', 'چمدان و ساک', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (276, 1052, 'کیف مسافرتی', 'travel-bag', 'کیف مسافرتی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (277, 1052, 'لوازم بهداشتی سفر', 'travel-toiletries', 'لوازم بهداشتی سفر', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (278, 1052, 'بالش و پتو مسافرتی', 'travel-pillow-blanket', 'بالش و پتو مسافرتی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (279, 1062, 'خودکار و روان نویس', 'pen-rollerball', 'خودکار و روان نویس', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (280, 1062, 'مداد و تراش', 'pencil-sharpener', 'مداد و تراش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (281, 1062, 'پاک کن و خط کش', 'eraser-ruler', 'پاک کن و خط کش', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (282, 1062, 'دفتر و یادداشت', 'notebook-notepad', 'دفتر و یادداشت', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');
INSERT INTO `sub_sub_categories` VALUES (283, 1062, 'کیف و جامدادی', 'pencil-case-bag', 'کیف و جامدادی', NULL, '2026-05-15 13:38:06', '2026-05-15 13:38:06');

-- ----------------------------
-- Table structure for subscribers
-- ----------------------------
DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE `subscribers`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `subscribers_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subscribers
-- ----------------------------

-- ----------------------------
-- Table structure for ticket_replies
-- ----------------------------
DROP TABLE IF EXISTS `ticket_replies`;
CREATE TABLE `ticket_replies`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `viewed` tinyint NOT NULL DEFAULT 0,
  `client_viewed` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ticket_replies
-- ----------------------------

-- ----------------------------
-- Table structure for tickets
-- ----------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_id` int NULL DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `viewed` tinyint NOT NULL DEFAULT 0,
  `status` tinyint NOT NULL DEFAULT 0,
  `client_viewed` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tickets_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tickets
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `referred_by` int NULL DEFAULT NULL,
  `provider_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `user_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'customer',
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `role` enum('admin','technician','customer') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `avatar` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `avatar_original` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `address` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `country` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `balance` double(8, 2) NOT NULL DEFAULT 0.00,
  `referral_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `customer_package_id` int NULL DEFAULT NULL,
  `remaining_uploads` int NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, NULL, NULL, 'admin', 'مدیر سیستم', 'admin@example.com', 'admin', NULL, '$2y$10$vyiEytr3RPcwh.VE/DrqQOcHwi9lJr/0wc0eoIowXfDgPxkMZdYCG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `users` VALUES (2, NULL, NULL, 'customer', 'تکنسین نمونه', 'tech@example.com', 'technician', NULL, '$2y$10$vyiEytr3RPcwh.VE/DrqQOcHwi9lJr/0wc0eoIowXfDgPxkMZdYCG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `users` VALUES (3, NULL, NULL, 'customer', 'مشتری نمونه', 'customer@example.com', 'customer', NULL, '$2y$10$vyiEytr3RPcwh.VE/DrqQOcHwi9lJr/0wc0eoIowXfDgPxkMZdYCG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `users` VALUES (4, NULL, NULL, 'admin', 'مدیریت دریکانا', 'admin@drikana.com', 'admin', '2026-09-07 03:55:52', '$2y$10$vyiEytr3RPcwh.VE/DrqQOcHwi9lJr/0wc0eoIowXfDgPxkMZdYCG', NULL, NULL, NULL, 'تهران، بازار بزرگ طلا و جواهر', NULL, 'تهران', NULL, '09120000000', NULL, NULL, 0.00, NULL, NULL, 0, '2026-09-07 03:55:52', '2026-09-07 03:55:52');

-- ----------------------------
-- Table structure for wallets
-- ----------------------------
DROP TABLE IF EXISTS `wallets`;
CREATE TABLE `wallets`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `amount` double(20, 2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_details` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wallets
-- ----------------------------

-- ----------------------------
-- Table structure for wishlists
-- ----------------------------
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wishlists
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
