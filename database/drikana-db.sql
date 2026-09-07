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

 Date: 07/09/2026 13:16:15
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_stocks
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'گردنبند طلای 24 عیار | طرح ابدی سلطنتی', 'admin', 12, 104, 1043, NULL, 6, '[\"uploads\\/products\\/photos\\/Z4E6qsPrDR545IKbRYG0qUsxrDAILjXSxIy4gyvv.jpg\",\"uploads\\/products\\/photos\\/aHJcGqGInVJYjtiMLm3qunZoqnrAJQupn2Lcqo4W.jpg\"]', 'uploads/products/thumbnail/jUANPNxmrIQyD5fOnHP6V3Ce3IROYdhu7urmKTNN.jpg', 'uploads/products/featured/hEh2nBd1iw4caSZqEwIReFOFb7lgYTKklmtRYbJF.jpg', 'uploads/products/flash_deal/7cTZHv7B529r7xKKWNczxTRV6Mykelg7ipkDAjIu.jpg', 'youtube', NULL, 'گردنبند', 'گردنبندی از طلای خالص 24 عیار با طراحی کلاسیک سلطنتی. هر گلبرگ دست‌ساز این قطعه، نور را به شیوه‌ای منحصر به فرد بازمی‌تاباند. مناسب مراسم عروسی، مهمانی‌های لوکس و به عنوان سرمایه‌ای ماندگار.', 9500000, 7500000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0421088\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]}]', '[\"#000000\",\"#FF8C00\"]', NULL, 0, 1, 1, 0, 'انس', 0.00, NULL, 0.00, NULL, 'free', 0.00, 3, 'گردنبند طلای 24 عیار سلطنتی | سرمایه ماندگار', 'خرید گردنبند طلای خالص 24 عیار با طراحی دست‌ساز سلطنتی. مناسب سرمایه‌گذاری و مراسم خاص.', 'uploads/products/meta/K0j3z2jbqBsPEP8iqkWPP1IVTkdpZCMMxZLEiHWA.jpg', NULL, 'گردنبند-طلای-24-عیار-|-طرح-ابدی-سلطنتی-HjVn1', 0.00, NULL, 0, NULL, NULL, '2020-07-07 13:38:21', '2026-01-01 10:07:08');
INSERT INTO `products` VALUES (5, 'حلقه نامزدی الماس | وعده ابدی', 'seller', 3, 104, 1043, 132, 7, '[\"uploads\\/products\\/photos\\/Wbyo7C7FOOLev0NYmWKsu9rm1WUikg6kZGcBeomf.jpg\"]', 'uploads/products/thumbnail/lyyPJrjtLP1n9c1vlMlXfaFBkyoJUbZgfTjSG8Kp.jpg', 'uploads/products/featured/Q1zLSzK1Upa2Zrc7N8apmemaTSJRbEwsO2up9bct.jpg', 'uploads/products/flash_deal/xozcjkNOK763TZenugvOnl5a7h3grtA5sDM6kgF7.jpg', 'youtube', NULL, 'حلقه', 'حلقه‌ای از الماس تک‌قطعه 2 قیراطی با تراش پرنسس، بر بستری از طلای سفید 18 عیار. طراحی سه‌نگین (Three-stone) نماد گذشته، حال و آینده. همراه با گواهی GIA.', 28000000, 22000000, 1, '[]', '[]', '[\"#F0FFFF\",\"#F5F5DC\"]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 2, 'حلقه نامزدی الماس 2 قیراط | تراش پرنسس', 'خرید حلقه نامزدی با الماس 2 قیراطی و گواهی GIA. طراحی سه‌نگین نمادین.', 'uploads/products/meta/6UKdzChPM5B9XNeHEJnPOddhuCCzPxm6y7TW6qR5.jpg', NULL, '------?اط', 0.00, NULL, 0, NULL, NULL, '2020-07-12 15:50:04', '2026-01-01 09:15:59');
INSERT INTO `products` VALUES (6, 'ساعت  طلای سوئیسی | کلاسیک همیشگی', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/UOmrmUMlwwa40da1kLm9gtuijQrBpOtuifiZTMzw.png\"]', 'uploads/products/thumbnail/jXd2695MMTH6P9wR95jPwkgdCVQ2V2r0uqVLIo9t.png', 'uploads/products/featured/aPbRhjRDjnQCF8zn8hMwAfQAekiXubYwRoDsArLz.png', 'uploads/products/flash_deal/cfkVAJSs9GLf7DRsuKEow6lScr9M4hCAGJTI3Cv1.png', 'youtube', NULL, 'ساعت  طلای سوئیس', 'ساعت مچی مردانه از طلای 18 عیار با موتور سوئیسی اتوماتیک. صفحه میناکاری شده با اعداد رومی، شمارشگر تاریخ و ضد آب تا 50 متر. ترکیبی از هنر جواهرسازی و ساعت‌سازی اصیل.', 18500000, 14500000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"56468\"]}]', '[]', NULL, 0, 1, 0, 0, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 1, 'ساعت مچی طلای سوئیسی | موتور اتوماتیک', 'خرید ساعت مچی طلای 18 عیار با موتور سوئیسی. طراحی کلاسیک و ارزشمند.', 'uploads/products/meta/lnBz0lfpaCCAoAkrGgLMhs9VoQLqr7dodPYO4fNL.png', NULL, 'ساعت--طلای-سوئیسی-|-کلاسیک-همیشگی-kQyp8', 0.00, NULL, 0, NULL, NULL, '2020-07-18 06:05:07', '2026-01-01 12:26:38');
INSERT INTO `products` VALUES (7, 'النگو مروارید تاهیتی | اشک ماه', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/OFBMUpQr1Yadx0Cy3sqgq9O52FdU8UBAfxceiK6U.jpg\"]', 'uploads/products/thumbnail/htElFRruPNVNhYW2spJnBVXfX60rGxEPppdkWtE8.jpg', 'uploads/products/featured/1v33QXNnwjuEGNSMphYQ7KlpfFUnbge8vuwlArXQ.jpg', 'uploads/products/flash_deal/PkyMpIkWR99qC787zG0MSCISfWIX5u7dIQqOMFUW.jpg', 'youtube', NULL, 'النگو مروارید', 'مرواریدهای سیاه تاهیتی طبیعی با درخشش آینه‌ای، بر نخ ابریشمی و با فاصله‌اندازهای طلای 14 عیار. قطر مرواریدها: 10-12 میلی‌متر. هدیه‌ای اشرافی برای بانوان خوش‌سلیقه.', 9500000, 7200000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"36565\"]}]', '[\"#F0F8FF\"]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 1, 'النگو مروارید تاهیتی طبیعی | مروارید سیاه', 'خرید النگوی مروارید تاهیتی طبیعی با قطر 10-12 میلی‌متر. هدیه‌ای لوکس و ماندگار.', 'uploads/products/meta/vxiaripoywZuDahhuXRyAV1TazfxTt5b9Y1mUlVP.jpg', NULL, 'النگو-مروارید-تاهیتی-|-اشک-ماه-NPiq4', 0.00, NULL, 0, NULL, NULL, '2020-07-18 07:21:16', '2026-02-21 14:01:02');
INSERT INTO `products` VALUES (8, 'ساعت الماس', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/TgwPvK9CAHCIgHwzjrhloCjdquLae6k6CDVxLi0F.jpg\"]', 'uploads/products/thumbnail/PSBD8NaoN7RqA0U5eJyUD2orZ9edcjHX9ENGIUug.jpg', 'uploads/products/featured/iJWm8DsYQKu9GDbd8iqHcJP3yuosarM5gRY2TGCt.jpg', 'uploads/products/flash_deal/BZyIdv0n03ta61UbYANhOeYROsub8iKp0v6qBYkx.jpg', 'youtube', NULL, '', 'سنجاق سینه با طراحی پروانه، ترکیبی از الماس‌های ریز و یاقوت‌های صورتی. بال‌های متحرک این قطعه، با هر حرکت نور را به بازی می‌گیرند. مناسب کراوات، شال یا یقه لباس شب.', 7800000, 5800000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"25477\"]}]', '[]', NULL, 0, 1, 0, 0, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 11, 'سنجاق سینه الماس و یاقوت | طراحی پروانه', 'خرید سنجاق سینه الماس و یاقوت صورتی با طراحی متحرک. اکسسوری لوکس برای لباس شب.', 'uploads/products/meta/5VRgJThfheZFKtVJ7uIzKrijGeOdiuv8OQ108XVB.jpg', NULL, 'ساعت-الماس-SjlXT', 0.00, NULL, 0, NULL, NULL, '2020-07-18 07:51:32', '2026-01-01 12:18:27');
INSERT INTO `products` VALUES (9, 'تاج عروس سواروسکی | ملکه شب', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/IDOMboDCBvCJ7qjV6KyjRDmAmGhBXJyrGnL13rlU.jpg\",\"uploads\\/products\\/photos\\/US23Ew2qgBDt1LDHLIOLx6M6R5m8kD6e0dhRan8C.jpg\"]', 'uploads/products/thumbnail/Xbk49h9W9Y3pi9YU327Pl51ubHWiujnMB9X9HsGz.jpg', 'uploads/products/featured/nAUki2FA80FKjia8BkMO6GBJRaKQW6jlXfTrlgyT.jpg', 'uploads/products/flash_deal/avyf0ohOISTKnR8C2hxQeoiVZZyNMbthEDbHijwY.jpg', 'youtube', NULL, 'پیراهن', 'تاج عروس از کریستال‌های سواروسکی درجه یک با تراش چندوجهی. طراحی الهام‌گرفته از تاج‌های اروپایی قرن نوزدهم. قاب از نقره استرلینگ روکش‌شده با رودیوم ضد حساسیت.', 4200000, 3100000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0421055885\"]}]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 8000.00, 4, 'تاج عروس سواروسکی | کریستال درجه یک', 'خرید تاج عروس با کریستال سواروسکی. طراحی شیک و ضد حساسیت.', 'uploads/products/meta/4tb7pvPLfd1rjz4ZCg3OywP2rQTAWryBAviixzls.jpg', NULL, 'تاج-عروس-سواروسکی-|-ملکه-شب-0bzt4', 0.00, NULL, 0, NULL, NULL, '2020-07-18 07:55:15', '2026-01-01 09:50:04');
INSERT INTO `products` VALUES (10, 'پابند طلای 22 عیار | صدای آرامش', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/FOhyP15WjpYxJjKgyGm6bm0DyASXonmV6iQ4Fayt.jpg\"]', 'uploads/products/thumbnail/hODYP9iepe70mppazNfyTHQy1fHfXclUep39EryS.jpg', 'uploads/products/featured/FIm308s0hHFQqTuY5QZ2uZ5edAru9Lq7HPPck41G.jpg', 'uploads/products/flash_deal/UJ92MLmc255wdNd1hUaO7IepD2UtBOfH1Fe0Y1RU.jpg', 'youtube', NULL, 'پابند', 'پابندی از طلای 22 عیار با زنجیر ظریف و آویز ستاره‌ای کوچک. زنگوله‌های نقره‌ای ریز صدایی آرامش‌بخش تولید می‌کنند. نمادی از آزادی و زیبایی در فرهنگ‌های شرقی.', 5200000, 3900000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"5003365\"]}]', '[]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 4, 'پابند طلای 22 عیار | با آویز ستاره و زنگوله', 'خرید پابند طلای 22 عیار با آویزهای ظریف. طراحی الهام‌گرفته از زیورآلات شرقی.', 'uploads/products/meta/OZCH9m5tcoyRceFWFxAco4bN0TbXFqO2MnCQmBdd.jpg', NULL, 'پابند-طلای-22-عیار-|-صدای-آرامش-OdnTH', 0.00, NULL, 0, NULL, NULL, '2020-07-18 09:59:35', '2026-01-01 12:14:18');
INSERT INTO `products` VALUES (11, 'گوشواره مروارید جنوبی | قطره اشک', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/sqvAQmJYaPiipmi3XDzjChFj3L88tcM1BMNhhZ3U.jpg\"]', 'uploads/products/thumbnail/YqOspqhQ2V10SOypKD0Im3xPwKesMtPDR1wWVrIo.jpg', 'uploads/products/featured/g98Itm57rQYDSjMBKnySGW8vQ5dnD36OJ0omws3E.jpg', 'uploads/products/flash_deal/o0yI8MwlYuTOuv8xHGgFbAbL9WfW9LnwhOJsiPDr.jpg', 'youtube', NULL, 'گوشواره', 'مرواریدهای طبیعی جنوبی با رنگ کرم و درخشش مخملی، در قابی از طلای رزگلد 18 عیار. قطر مروارید: 9 میلی‌متر. طراحی کلاسیک و مناسب تمامی سنین.', 6800000, 5200000, 1, '[\"3\",\"8\"]', '[{\"attribute_id\":\"3\",\"values\":[\"18\"]},{\"attribute_id\":\"8\",\"values\":[\"4\"]}]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 2, 'گوشواره مروارید جنوبی طبیعی | رنگ کرم', 'خرید گوشواره مروارید جنوبی طبیعی با قطر 9mm. طراحی کلاسیک و همیشه شیک.', 'uploads/products/meta/MTNqIgEJvzR5Ck5AtxQOavIbUlLA2l70m9BOvFH3.jpg', NULL, 'گوشواره-مروارید-جنوبی-|-قطره-اشک-9aAS4', 0.00, NULL, 0, NULL, NULL, '2020-07-18 10:11:40', '2026-01-01 12:12:10');
INSERT INTO `products` VALUES (12, 'ساعت زنانه الماس‌نشان | درخشش زمان', 'seller', 14, 104, 1043, 136, 9, '[\"uploads\\/products\\/photos\\/dgQ3xPwccYsIyK9J17XrVJYudqFmAolThbTZBZyP.jpg\",\"uploads\\/products\\/photos\\/9i5Nc8okBA9ZHCWTMWoOpfUlUL0NcTGPEcoI1QVp.jpg\",\"uploads\\/products\\/photos\\/DejEubnkAJGi04Ak56Yensi5b1C0XukR9MVhbCkc.jpg\",\"uploads\\/products\\/photos\\/zcj8Bs394JbMrjwWixns5olWTgWPLWX6nwO2xnHP.jpg\"]', 'uploads/products/thumbnail/4gQWk563lNXrSEPogY70ozeiA5cvfq3Q9x7zLzLg.jpg', 'uploads/products/featured/n07iFVWMlvIBiAIMQyV2soCbdzORaq0tPUkikBVC.jpg', 'uploads/products/flash_deal/Kx3DH49RRs18KZN7oH8zOAlOCCrYIrPAZQIcur9I.jpg', 'youtube', NULL, 'ساعت زنانه', 'ساعت مچی زنانه با قاب طلای سفید 18 عیار و 56 قطعه الماس برلیان دور صفحه کوارتز سوئیسی. بند چرم تمساح اصل. طراحی ظریف و لوکس برای بانوان موفق.', 15500000, 12000000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"04585258\"]}]', '[]', NULL, 0, 1, 0, 0, 'دستگاه', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 3, 'ساعت زنانه الماس نشان | با بند چرم تمساح', 'خرید ساعت زنانه طلای سفید با الماس و موتور سوئیسی. ترکیب جواهر و ساعت.', 'uploads/products/meta/4nlNrqmIhwdYoUc47JM1Bz5HGdBQ7WAUUgppyZhm.jpg', NULL, 'ساعت-زنانه-الماس‌نشان-|-درخشش-زمان-mjSOY', 0.00, NULL, 0, NULL, NULL, '2020-07-18 10:27:30', '2026-01-01 12:08:18');
INSERT INTO `products` VALUES (13, 'دستبند فیروزه نیشابور | آسمان در مچ', 'seller', 14, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/GfI7nESrda5lvJuFis1t5TDCPyUGtpwamvGh5Quf.jpg\",\"uploads\\/products\\/photos\\/h9sxgVJCK6fRtcasO3HD4vLisnRUv047wg2ChgsT.jpg\"]', 'uploads/products/thumbnail/53hoPzWS2LM8ZUHEiofm2JVpbSOvdFBxEbX5CvPn.jpg', 'uploads/products/featured/au4j5G2V1chW3yvd8sHH9iy7scxY1gB2jTtXbbbu.jpg', 'uploads/products/flash_deal/SEL4womyyJ3OEZtBYp1l6RC3HxbcgAkxcRqBkZ8i.jpg', 'youtube', NULL, 'دستبند', 'مهره‌های فیروزه نیشابور اصل با رگه‌های طبیعی، همراه با مهره‌های نقره استرلینگ دست‌ساز. فیروزه سنگ محافظ در فرهنگ ایرانی و نماد خوش‌یمنی.', 3200000, 2400000, 1, '[\"1\",\"3\",\"7\"]', '[{\"attribute_id\":\"1\",\"values\":[\"425558555\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]},{\"attribute_id\":\"7\",\"values\":[\"\\u0637\\u0644\\u0627\"]}]', '[]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'دستبند فیروزه نیشابور اصل | سنگ محافظ', 'خرید دستبند فیروزه نیشابور اصل با مهره‌های نقره. هدیه‌ای خوش‌یمن و سنتی.', 'uploads/products/meta/4eJsT60VwxvtisAVrXavKx8KLbfDancoPgfABNul.jpg', NULL, 'دستبند-فیروزه-نیشابور-|-آسمان-در-مچ-FU6ak', 0.00, NULL, 0, NULL, NULL, '2020-07-18 10:33:06', '2026-01-01 10:54:25');
INSERT INTO `products` VALUES (14, 'ست ساقط طلا و الماس | هارمونی نور', 'seller', 15, 104, 1043, NULL, 16, '[\"uploads\\/products\\/photos\\/qKurtZ7HlbGTmOkndT0CU9E8blDsbrGuXSw4UliZ.jpg\",\"uploads\\/products\\/photos\\/CYcAL3cn33dnIvyNjNfQyzyByPx2R5y9q73t6IhZ.jpg\"]', 'uploads/products/thumbnail/d7aV6NPoMf1uEq1AwTiX5wxCX7zC7NxFiAXQYjhi.jpg', 'uploads/products/featured/4Ii0msM8BdySKz7BPy7Nq7UEYx2nWWl6T7LZcCds.jpg', 'uploads/products/flash_deal/iMRQ4H5BJh8RFKWRhvEo2jZELm0zxfxiIcfcUSDh.jpg', 'youtube', NULL, 'ست', 'ست کامل ساقط (شامل گوشواره، گردنبند، دستبند) از طلای سفید 18 عیار با الماس‌های ریز. طراحی هماهنگ و مینیمال که با هم یا جداگانه قابل استفاده است.', 28500000, 22000000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"5678\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]}]', '[\"#FFFFFF\"]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 1, 'ست ساقط طلا و الماس | ست کامل جواهرات', 'خرید ست کامل ساقط طلای سفید با الماس. طراحی هماهنگ برای مهمانی‌های رسمی.', 'uploads/products/meta/oHvQucdpwHaceCJquFX3jneLK8t8qmGJV62DDr2v.jpg', NULL, 'ست-ساقط-طلا-و-الماس-|-هارمونی-نور-iQ0mb', 0.00, NULL, 0, NULL, NULL, '2020-07-18 11:35:27', '2026-01-01 10:51:40');
INSERT INTO `products` VALUES (15, 'انگشتر مهر طلا | امضاى اشرافی', 'seller', 15, 104, 1043, NULL, 11, '[\"uploads\\/products\\/photos\\/XJhlvMJrFBwwzbeoXOr8fltnEarghApnzM4MCNbK.jpg\"]', 'uploads/products/thumbnail/t33ZtITepLqwJT5rgQmgzsGJKAf67xCjNkohDQae.jpg', 'uploads/products/featured/65vVfahBoV48n2qX1B3tss1qqdOXn3nBJ0B29p6Q.jpg', 'uploads/products/flash_deal/vkmbfPpypenGyiS8mv8DgTETR70NeeGeMt9fyNhU.jpg', 'youtube', NULL, 'انگشتر', 'انگشتر مهر مردانه از طلای 21 عیار با سطح حکاکی‌شده برای مهر کردن نامه‌ها. طرح شیر و خورشید به سبک قاجاری. مناسب مجموعه‌داران و علاقه‌مندان به اشیای تاریخی.', 7500000, 5800000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"2567\"]},{\"attribute_id\":\"3\",\"values\":[\"26\"]}]', '[\"#F0E68C\"]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'انگشتر مهر طلا | طرح شیر و خورشید قاجاری', 'خرید انگشتر مهر طلای 21 عیار با طرح تاریخی. مناسب مجموعه‌داران جواهرات.', 'uploads/products/meta/gu5QOL7j1JUbSr8QNey1jh05mu6l75nMkyU7sCR9.jpg', NULL, 'انگشتر-مهر-طلا-|-امضاى-اشرافی-roXMK', 0.00, NULL, 0, NULL, NULL, '2020-07-18 11:41:13', '2026-01-01 10:48:32');
INSERT INTO `products` VALUES (16, 'گل‌سینه یاقوت کبود | ستاره دریایی', 'seller', 15, 104, 1043, NULL, 15, '[\"uploads\\/products\\/photos\\/gLH0JJNn4MZGM03GVQJjO48n5NhF8XqGMfYjhCXB.jpg\"]', 'uploads/products/thumbnail/Xc7O5ofywbS99lh7pe91vpqh1eXYqSjFG7o6Lvvs.jpg', 'uploads/products/featured/9QXMOnLNumtrgMmJVgZ7SkgLQpi6NHqYLbTzHGvz.jpg', 'uploads/products/flash_deal/apHYItNtdpphnjkKdxGngQtIWDRmeAtZvi2hl15G.jpg', 'youtube', NULL, 'گل‌سینه یاقوت کبود', 'گل‌سینه با طراحی ستاره دریایی، مرکزیت یاقوت کبود 5 قیراطی اصل سیلان، احاطه‌شده توسط الماس‌های ریز. سنجاق ایمن برای استفاده روی لباس شب.', 16500000, 13000000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"200439\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]}]', '[\"#1E90FF\",\"#FFFAF0\"]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 1, 'گل‌سینه یاقوت کبود سیلان | 5 قیراط', 'خرید گل‌سینه یاقوت کبود اصل سیلان با طراحی ستاره دریایی. جواهری چشمگیر.', 'uploads/products/meta/2Sovak5Qewek1tUpD4HhKs6k8VO5ld309ShTj21e.jpg', NULL, 'گل‌سینه-یاقوت-کبود-|-ستاره-دریایی-qVcbY', 0.00, NULL, 0, NULL, NULL, '2020-07-18 11:46:07', '2026-01-01 10:42:54');
INSERT INTO `products` VALUES (17, 'گوشواره آویز زمرد و الماس | آونگ سبز', 'seller', 15, 104, 1043, NULL, 12, '[\"uploads\\/products\\/photos\\/jUk70Ce5HaD2JW4X1Um9rdbQchSWU0lzGTp4wAMg.png\"]', 'uploads/products/thumbnail/SjnLXRnujbK1q0m0MRgfHj00AdSMobKhuklEBVdz.png', 'uploads/products/featured/hpS4wt4rRBeerz6gFVakTWYVyNUgVuQSEpBSEWDz.png', 'uploads/products/flash_deal/LIXfaVH4OHH8kLpQGZvzYprtcsLk4trxfMG9wP8a.png', 'youtube', NULL, 'گوشواره', 'آویزهای دراماتیک با زمرد کلمبیایی بیضی‌شکل (هرکدام 1.2 قیراط) و الماس‌های ریز پیرامون. طول آویز: 6 سانتیمتر. طراحی مناسب مهمانی‌های گالا.', 18500000, 14500000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"55617\"]},{\"attribute_id\":\"3\",\"values\":[\"24\"]}]', '[]', NULL, 0, 1, 1, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'گوشواره آویز زمرد و الماس | طراحی دراماتیک', 'خرید گوشواره آویز زمرد کلمبیایی 1.2 قیراطی با الماس. مناسب مراسم بسیار رسمی.', 'uploads/products/meta/VQvHEsuMGf1YGTp6NbVA1pjLqK8IaUXtsxpy1cu0.png', NULL, 'گوشواره-آویز-زمرد-و-الماس-|-آونگ-سبز-7xQdk', 0.00, NULL, 0, NULL, NULL, '2020-07-18 12:17:03', '2026-01-01 10:35:39');
INSERT INTO `products` VALUES (18, 'دستبند پاور الماس | خط قدرت', 'seller', 15, 104, 1043, NULL, 17, '[\"uploads\\/products\\/photos\\/jV0lx2aah3hTBzjOY6Tn6U4xMM3IXzqUjWCbt8Tj.jpg\"]', 'uploads/products/thumbnail/j4L2QQbf4BXPiggIGPH2in9JdHDzMEWOxVywvZP6.jpg', 'uploads/products/featured/e1UDfdXD1rB2R7iANTtzliDmX4RLa18N9nRsOQqN.jpg', 'uploads/products/flash_deal/Oa0hd9oHQInfYAoU9nj2MLRetcY9WG1DWfJ6PMVh.jpg', 'youtube', NULL, 'دستبند پاور الماس', 'دستبند پاور (Power) از طلای زرد 18 عیار با 5 ردیف الماس‌های برلیان به وزن کل 8 قیراط. طراحی برجسته و جسورانه برای بانوان قدرتمند.', 42000000, 32000000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"58622434\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]}]', '[]', NULL, 0, 1, 1, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'دستبند پاور الماس 8 قیراط | جواهر قدرت', 'خرید دستبند پاور طلا با 8 قیراط الماس. طراحی جسورانه و مدرن.', 'uploads/products/meta/24PrbCMyd7yh9eoL5nAXlpR9KC7YCFGQdmUm2iC5.jpg', NULL, 'دستبند-پاور-الماس-|-خط-قدرت-txFeP', 0.00, NULL, 0, NULL, NULL, '2020-07-18 12:35:11', '2026-01-01 10:26:36');
INSERT INTO `products` VALUES (19, 'حلقه سنگ ماه (مون استون) | راز ماه', 'seller', 15, 104, 1043, NULL, 6, '[\"uploads\\/products\\/photos\\/f3ioFgy2nEH5VMQwhwma5Ei6Z2HK03x46ISo0HA5.jpg\",\"uploads\\/products\\/photos\\/3nXAT6GauNc8pnnVkHYPEhoFheSRIg7EFhqneHFc.jpg\"]', 'uploads/products/thumbnail/9bSzzzLDfiKP7mB8c6TrH27MHqpiVzqxqBFDrYn6.jpg', 'uploads/products/featured/6cnW2MAIdrD9B21HiD1Punox9CXbkCzXaLmZO25p.jpg', 'uploads/products/flash_deal/zPS2Ymb3qFAVEsFmlrdgSg3qkIG9OzIs7jQWgwWl.jpg', 'youtube', NULL, 'حلقه', 'حلقه با سنگ ماه (مون استون) اصل با بازی نور آبی-سفید (ادولارسنس). قاب نقره استرلینگ 925. سنگ نماد ماه و معنویت در باورهای کهن.', 2800000, 1900000, 1, '[\"1\",\"3\",\"9\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0421802222\"]},{\"attribute_id\":\"3\",\"values\":[\"18\"]},{\"attribute_id\":\"9\",\"values\":[\"1404\"]}]', '[]', NULL, 0, 1, 1, 0, 'انس', 2.00, 'percent', 10.00, 'percent', 'free', 0.00, 1, 'حلقه سنگ ماه (مون استون) | بازی نور آبی', 'خرید حلقه با سنگ ماه اصل و نقره استرلینگ. نماد معنویت و آرامش.', 'uploads/products/meta/Ahf2TBA7lTHgsboIfTynm7tpzmA2Bwn6yebCwyRr.jpg', NULL, 'حلقه-سنگ-ماه-(مون-استون)-|-راز-ماه-45lKi', 0.00, NULL, 0, NULL, NULL, '2020-07-18 12:53:21', '2026-01-01 10:12:50');
INSERT INTO `products` VALUES (20, 'گوشواره توپاز آبی لندن | آسمان آبی', 'seller', 16, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/vrqcJa6w66EhO9Z7M2Kxn1iRYfjwsMkkEDcBfqmW.png\"]', 'uploads/products/thumbnail/etc4zj9lDHPKZHmKZoNQ7sfZaIHPVQS87isYtVqR.png', 'uploads/products/featured/gv4IubKDtuAoSPKrMsKhquii8Vha6mRTjZwU7354.png', 'uploads/products/flash_deal/dSiZqpy9nA5bvS4C3NU1IPAeaRwEReXT49tm0XVn.png', 'youtube', NULL, 'گوشواره', 'توپازهای آبی لندن (London Blue Topaz) با رنگ آبی عمیق و درخشان، در تراش اشرافی. وزن هر سنگ: 3.5 قیراط. قاب طلای سفید 14 عیار.', 8500000, 6500000, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 0, 'گوشواره توپاز آبی لندن | رنگ آبی عمیق', 'خرید گوشواره توپاز آبی لندن 3.5 قیراطی. رنگ اشرافی و درخشان.', 'uploads/products/meta/QmRT2UDqNhoSwbEiakSqyGMzBgPUmQQPEuAYpfRJ.png', NULL, 'گوشواره-توپاز-آبی-لندن-|-آسمان-آبی-GT1X3', 0.00, NULL, 0, NULL, NULL, '2020-07-19 05:28:09', '2026-01-01 10:23:35');
INSERT INTO `products` VALUES (21, 'النگو الماس چپقی | دایره کامل نور', 'seller', 16, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/Fv1C83WQCRLhrhrC1O3ivYHEv9zGxQPttkVChr9X.jpg\",\"uploads\\/products\\/photos\\/KS8bWhLNO7Q4eXTogZwUUckybKTPqpa33syFj8z9.jpg\"]', 'uploads/products/thumbnail/HKqNyGH57lyZnAN7940Npe00Gi0fDtMfAUVl3rRg.jpg', 'uploads/products/featured/TDRA53JOP8tEo4wlev30f35dz6g4ONXnfS4L9Qzr.jpg', 'uploads/products/flash_deal/SmrwsD0C5bg28ZQ4CMy0VIfbkP5Rerz6DUWi0KJu.jpg', 'youtube', NULL, 'النگو الماس', 'النگی از طلای سفید 18 عیار با 22 قطعه الماس برلیان (مجموع 5 قیراط) در حالت چپقی (channel setting). طراحی بدون درز و بسیار راحت.', 19500000, 15000000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0425885255\"]}]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 0, 'النگو الماس چپقی | 5 قیراط الماس', 'خرید النگو الماس با تراش چپقی. طراحی راحت و پر زرق و برق.', 'uploads/products/meta/AMQKtecyw3ahS0QwuINU81miXL0adqKc4oKcAihs.jpg', NULL, 'النگو-الماس-چپقی-|-دایره-کامل-نور-M0qZP', 0.00, NULL, 0, NULL, NULL, '2020-07-19 05:36:45', '2026-01-01 10:21:30');
INSERT INTO `products` VALUES (22, 'سینه‌ریز یاقوت زرد | خورشید تابان', 'seller', 17, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/oGX5Xxos9rC1tT68LtNnRvj8qqRwssac4nFFE6l0.jpg\"]', 'uploads/products/thumbnail/LP9ZFrsuWYXXEAxMGDwAn7fnrkv3wbQoCT15ivzL.jpg', 'uploads/products/featured/S3kxtKpEQmkbk7HLVu9GccQpvSaHrIfRvRcUDh4r.jpg', 'uploads/products/flash_deal/NtjqaJsqkWJLPlGycmkJgpBodFJWGIc16GZ6F4cw.jpg', 'youtube', NULL, 'سینه‌ریز', 'سینه‌ریز با یاقوت زرد 7 قیراطی اصل ماداگاسکار، احاطه‌شده با الماس‌های ریز. زنجیر طلای زرد 18 عیار با قابلیت تنظیم طول.', 12500000, 9500000, 1, '[\"1\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0421088269\"]}]', '[]', NULL, 0, 1, 0, 0, 'انس', 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'سینه‌ریز یاقوت زرد ماداگاسکار | 7 قیراط', 'خرید سینه‌ریز یاقوت زرد اصل با الماس. رنگ گرم و خورشیدی.', 'uploads/products/meta/VMZPknxP5qE4X1lZny2sQHkikFyn99E4v6gfeVDT.jpg', NULL, 'سینه‌ریز-یاقوت-زرد-|-خورشید-تابان-vQrsb', 0.00, NULL, 0, NULL, NULL, '2020-07-19 05:57:25', '2026-01-01 09:44:15');
INSERT INTO `products` VALUES (23, 'گوشواره حریر طلا | ریزش باران', 'seller', 17, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/hkXm4MuzoxaJ1KFUSJlV3mvucndibi6gKlmYPRVU.png\"]', 'uploads/products/thumbnail/sR7RUilVdSu2ZRdzfbHyJCyy0QCF1Xfuxxl2hIDo.png', 'uploads/products/featured/hH5Qd6sonQywClYRBnDIPacRiwBgkyNj2RH8TBhD.png', 'uploads/products/flash_deal/Z6xdQJhcZf5klsGtSwUF7G2wKud1CZsqh7wuc17w.png', 'youtube', NULL, 'گوشواره', 'گوشواره‌های حریر با زنجیرهای نازک طلای 18 عیار و آویزهای الماس ریز. طراحی ظریف و زنگوله‌ای که با حرکت سر نوازشگر صورت می‌شود.', 7200000, 5500000, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 0, NULL, 0.00, 'amount', 0.00, 'amount', 'free', 0.00, 0, 'گوشواره حریر طلا و الماس | طراحی ظریف', 'خرید گوشواره حریر طلای 18 عیار با آویزهای الماس. مناسب استفاده روزمره.', 'uploads/products/meta/4PoOv7AaljHocX0C6uArWFuVjReaRQ2erCSzIhpF.png', NULL, '------?اس', 0.00, NULL, 0, NULL, NULL, '2020-07-19 06:16:33', '2026-01-01 09:14:22');
INSERT INTO `products` VALUES (24, 'دستبند یشم سبز (جید) | صلح سبز', 'seller', 19, 104, 1043, NULL, 9, '[\"uploads\\/products\\/photos\\/ATRlBqEEkuePtzrD4uZVE2KJZ8Ij5XweE5L6EUSy.png\"]', 'uploads/products/thumbnail/swqAKgPJvosR2xW1QyPEisNio6Cb4fpxQ4dzP7D2.png', 'uploads/products/featured/wLhV4N5cE9EXDWIY6c5px8l84jMyoz0t6U2U3u8T.png', 'uploads/products/flash_deal/4UJ5BImOHCBx2VlVhnTLLl5tTK9ePFxjcFD2ce90.png', 'youtube', NULL, 'دستبند', 'مهره‌های یشم سبز برمه با کیفیت Type A (بدون رنگ‌آمیزی)، در کنار مهره‌های طلای 24 عیار. یشم در فرهنگ‌های شرقی نماد طول عمر و آرامش است.', 18500000, 14000000, 0, '[]', '[]', '[]', NULL, 0, 1, 0, 2, 'انس', 0.00, 'amount', 0.00, 'amount', 'flat_rate', 10000.00, 8, 'دستبند یشم سبز برمه | سنگ Type A', 'خرید دستبند یشم سبز برمه اصل با مهره‌های طلای 24 عیار. سرمایه‌گذاری در سنگ‌های قیمتی.', 'uploads/products/meta/DTth6fOSN6FNJ9J4zwwEQhsDio4mCDYppkmqk2fe.png', NULL, 'دستبند-یشم-سبز-(جید)-|-صلح-سبز-ENhFQ', 0.00, NULL, 0, NULL, NULL, '2020-08-09 16:21:17', '2026-01-01 12:30:07');
INSERT INTO `products` VALUES (25, 'گردنبند ستاره داود مردانه', 'admin', 12, 104, 1043, NULL, 6, '[\"uploads\\/products\\/photos\\/rUuRt52ABf5zbtNFn96IqeXPDCV3gAach26RLp9P.png\"]', 'uploads/products/thumbnail/xpddSlQ8i9IHjo3uOF3mBoSiJKCxOXljnMWzIWQG.png', 'uploads/products/featured/1EVg2MRftQ35VOV1giTLZDUezIbwgai0x7I71pxM.png', 'uploads/products/flash_deal/ccc2wAgOZ0bdDoL2okLPUSm9bAal5zkhKS7jqWab.png', 'youtube', NULL, 'گردنبند مردانه', 'گردن بند طرح ستاره داود مردانه با بهترین تراش و ساخت . زیبا و شکیل', 333000000, 330000000, 1, '[\"1\",\"3\"]', '[{\"attribute_id\":\"1\",\"values\":[\"0421500255\"]},{\"attribute_id\":\"3\",\"values\":[\"26\"]}]', '[]', NULL, 0, 1, 0, 0, 'انس', 0.00, NULL, 0.00, NULL, 'flat_rate', 0.00, 0, 'طلا و سکه', 'طلا مردانه', 'uploads/products/meta/boVqYIVILqPalhTwGzSt0Q3MNSqLiUKbBCctlXo4.png', NULL, '----BVrOp', 0.00, NULL, 0, NULL, NULL, '2026-01-01 09:11:20', '2026-01-01 09:11:20');
INSERT INTO `products` VALUES (26, 'ساعت مردانه کلاسیک تمام استیل', 'admin', 12, 104, 1043, 134, 8, '[\"uploads\\/products\\/photos\\/Dry82chTBgVtwOrQd9IgDnWUGLs2peFM52AkwX8Y.jpg\",\"uploads\\/products\\/photos\\/7lcX3OHGZYSWI2Au3S9UAVbWqiymtfuVwBuDHdF7.jpg\"]', 'uploads/products/thumbnail/TTzSfxMYiP8eChVYoVPek1yQFfZETuUAKef0L80l.jpg', 'uploads/products/featured/xfxPxVWHTXr0yuDlyaN3oltgfoFf6PnMnYL16eRF.jpg', 'uploads/products/flash_deal/kYOCXRFLT8b0yxETBd6qSs1s4waQkA1GSLEA3F7O.jpg', 'youtube', NULL, 'ساعت', 'ساعت مچی کلاسیک مردانه با طرح مدرن و شیک برای اقایان شیک پوش&nbsp;', 200000000, 19000000, 1, '[\"1\",\"2\",\"9\"]', '[{\"attribute_id\":\"1\",\"values\":[\"25525522\"]},{\"attribute_id\":\"2\",\"values\":[\"20251500\"]},{\"attribute_id\":\"9\",\"values\":[\"2025\"]}]', '[\"#C0C0C0\"]', NULL, 0, 1, 0, 0, 'دستگاه', 3.00, 'percent', 9.00, 'percent', 'flat_rate', 200000.00, 0, 'ساعت‌های لوکس', 'ندارد', 'uploads/products/meta/oEEZ9m78qKF7lH8bG4EvQUBJfJ0LOdLeDWNe91z2.jpg', NULL, 'ساعت-مردانه-کلاسیک-تمام-استیل-o8lwP', 0.00, NULL, 0, NULL, NULL, '2026-01-01 11:05:14', '2026-01-01 11:05:14');
INSERT INTO `products` VALUES (27, 'کرم پودر مت ماتیف مناسب پوست چرب', 'admin', 12, 100, 1000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کرم پودر,مات,آرایش صورت', 'کرم پودر با پوشش کامل و مات، مناسب پوست چرب و مختلط، ماندگاری 12 ساعت', 450000, 350000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 50, 'عدد', 10.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'matte-foundation-oily-skin', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (28, 'کرم پودر دراپ کاور کامل', 'admin', 12, 100, 1000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کرم پودر مایع,پوشش بالا', 'کرم پودر مایع با پوشش کامل و بافت سبک، مناسب پوست خشک و معمولی', 680000, 520000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 40, 'عدد', 5.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'drop-foundation-full-coverage', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (29, 'پالت 12 رنگ سایه چشم حرفه‌ای', 'admin', 12, 100, 1001, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'سایه چشم,پالت آرایشی', 'پالت سایه چشم با 12 رنگ مات و براق، ماندگاری بالا و پیگمنت قوی', 890000, 650000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 30, 'عدد', 15.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '12-colors-eye-shadow-palette', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (30, 'ریمل حجم دهنده ضد آب', 'admin', 12, 100, 1001, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ریمل,مژه,حجم دهنده', 'ریمل ضد آب با برس سیلیکونی، افزایش حجم و بلندی مژه ها', 320000, 250000, 0, '[]', NULL, NULL, NULL, 0, 1, 0, 80, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'waterproof-volumizing-mascara', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (31, 'عطر مردانه وانیلی گرم 100ml', 'admin', 12, 100, 1004, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'عطر مردانه,وانیل,ادکلن', 'عطر مردانه با نت‌های وانیل، چوب صندل و کهربا، رایحه گرم و ماندگار', 1250000, 980000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 25, 'عدد', 8.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'mens-warm-vanilla-perfume', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (32, 'ادکلن زنانه گلی رز 75ml', 'admin', 12, 100, 1004, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ادکلن زنانه,رز,گلی', 'ادکلن زنانه با رایحه گل رز، یاس و مشک سفید، ملایم و شیک', 980000, 750000, 0, '[]', NULL, NULL, NULL, 0, 1, 0, 35, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'womens-rose-floral-cologne', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (33, 'گوشی موبایل سامسونگ با 256GB رم 8GB', 'admin', 12, 103, 1030, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'سامسونگ,موبایل,گوشی هوشمند', 'گوشی هوشمند با صفحه نمایش 6.7 اینچ، دوربین 108 مگاپیکسل، باتری 5000 میلی آمپر', 25000000, 22500000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 15, 'عدد', 5.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'samsung-256gb-8gb-phone', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (34, 'گوشی اپل آیفون جدید 128GB', 'admin', 12, 103, 1030, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'اپل,آیفون,موبایل', 'گوشی اپل با تراشه A16، دوربین دوگانه 48 مگاپیکسل، صفحه نمایش Super Retina', 45000000, 42000000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 10, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'apple-iphone-128gb', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (35, 'لپ تاپ ایسوس گیمینگ با 16GB رم و 512GB SSD', 'admin', 12, 103, 1032, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'لپ تاپ,گیمینگ,ایسوس', 'لپ تاپ گیمینگ با پردازنده Intel Core i7، کارت گرافیک RTX 3060، صفحه نمایش 144Hz', 32000000, 29000000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 8, 'عدد', 7.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'asus-gaming-laptop', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (36, 'لپ تاپ لنوو مناسب کار و تحصیل 8GB رم', 'admin', 12, 103, 1032, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'لنوو,لپ تاپ دانشجویی', 'لپ تاپ سبک و باریک با پردازنده Intel Core i5، باتری 8 ساعت، مناسب دانشجویی', 18000000, 16000000, 0, '[]', NULL, NULL, NULL, 0, 1, 0, 20, 'عدد', 5.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'lenovo-laptop-8gb', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (37, 'پاوربانک 20000 میلی آمپر با شارژ سریع', 'admin', 12, 103, 1033, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'پاوربانک,شارژر همراه', 'پاوربانک با ظرفیت بالا، دو پورت خروجی، شارژ سریع 18 وات', 850000, 650000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 100, 'عدد', 10.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '20000mah-powerbank', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (38, 'هندزفری بیسیم بلوتوثی با قاب شارژ', 'admin', 12, 103, 1033, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'هندزفری,بلوتوث,بیسیم', 'هندزفری بیسیم با کیفیت صدای عالی، ضد آب، عمر باتری 24 ساعت', 1200000, 900000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 60, 'عدد', 15.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'wireless-bluetooth-earbuds', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (39, 'تیشرت مردانه طرح دار کتانی', 'admin', 12, 104, 1040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'تیشرت مردانه,کتانی,طرح دار', 'تیشرت مردانه با جنس کتان مرغوب و طرح شیک، مناسب استفاده روزمره', 350000, 250000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 45, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'mens-cotton-printed-tshirt', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (40, 'شلوار جین مردانه اسلیم فیت', 'admin', 12, 104, 1040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'شلوار جین,مردانه,اسلیم فیت', 'شلوار جین مردانه با برش اسلیم، جنس با کیفیت و ضد چروک', 890000, 650000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 30, 'عدد', 10.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'mens-slim-fit-jeans', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (41, 'مانتو زنانه بلند و شیک پاییزه', 'admin', 12, 104, 1041, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'مانتو زنانه,پاییزه,بلند', 'مانتو زنانه بلند با طراحی خاص، مناسب فصل پاییز، جنس گرم و نرم', 1250000, 950000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 25, 'عدد', 15.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'womens-long-autumn-manteau', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (42, 'بلوز زنانه حریر با طرح گلدار', 'admin', 12, 104, 1041, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'بلوز زنانه,حریر,گلدار', 'بلوز زنانه با جنس حریر مرغوب و طرح گلدار، مناسب مهمانی و دورهمی', 580000, 420000, 0, '[]', NULL, NULL, NULL, 0, 1, 0, 40, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'womens-floral-chiffon-blouse', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (43, 'کفش اسپرت مردانه مدل جدید', 'admin', 12, 104, 1042, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کفش اسپرت,مردانه,مدل جدید', 'کفش اسپرت مردانه با طراحی مدرن، زیره نرم و قابل تنفس', 1500000, 1100000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 35, 'عدد', 8.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'mens-new-sport-shoes', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (44, 'کیف زنانه چرم طبیعی', 'admin', 12, 104, 1042, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کیف زنانه,چرم طبیعی,شیک', 'کیف زنانه با جنس چرم اصل و طراحی شیک، مناسب استفاده روزمره', 2200000, 1700000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 20, 'عدد', 12.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'genuine-leather-womens-bag', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (45, 'یخچال ساید بای ساید اینورتر 600 لیتر', 'admin', 12, 102, 1021, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'یخچال,ساید بای ساید,لوازم خانگی', 'یخچال ساید بای ساید با تکنولوژی اینورتر، یخساز، درب آب سرد', 35000000, 31000000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 5, 'عدد', 5.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'side-by-side-refrigerator-600l', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (46, 'ماشین لباسشویی 10 کیلویی دیجیتال', 'admin', 12, 102, 1021, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ماشین لباسشویی,دیجیتال,10 کیلو', 'ماشین لباسشویی با موتور دیجیتال، شستشوی بخار، 10 کیلوگرم ظرفیت', 18000000, 15500000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 12, 'عدد', 7.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'digital-washing-machine-10kg', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (47, 'دریل برقی دو کاره 18 ولت شارژی', 'admin', 12, 101, 1010, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'دریل برقی,شارژی,ابزارآلات', 'دریل برقی دو کاره با دو باطری، قابلیت دریل و پیچ گوشتی، سبک و قدرتمند', 4500000, 3800000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 20, 'عدد', 10.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '18v-cordless-hammer-drill', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (48, 'اره گرد برقی 1500 وات', 'admin', 12, 101, 1010, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'اره برقی,ابزار برقی', 'اره گرد برقی با صفحه برش قابل تنظیم، قدرت بالا و ایمنی', 3200000, 2700000, 0, '[]', NULL, NULL, NULL, 0, 1, 0, 12, 'عدد', 5.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '1500w-circular-saw', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (49, 'تشک یوگا ضد لغزش 8 میلی متر', 'admin', 12, 105, 1050, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'تشک یوگا,ورزشی', 'تشک یوگا با ضخامت 8 میلی متر، ضد لغزش، نرم و قابل شستشو', 850000, 650000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 50, 'عدد', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '8mm-non-slip-yoga-mat', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (50, 'دستگاه ورزش کراس فیت خانگی', 'admin', 12, 105, 1050, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کراس فیت,دستگاه ورزشی خانگی', 'دستگاه ورزشی کراس فیت با قابلیت انجام تمرینات مختلف، جمع شونده و کم جا', 12500000, 9800000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 8, 'عدد', 8.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'home-crossfit-station', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (51, 'کتاب رمان پرفروش خارجی ترجمه شده', 'admin', 12, 106, 1060, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'کتاب,رمان,پرفروش', 'رمان پرفروش خارجی با ترجمه عالی، مناسب علاقه‌مندان به داستان', 185000, 125000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 200, 'عدد', 10.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, 'translated-bestseller-novel', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');
INSERT INTO `products` VALUES (52, 'ست 20 عددی خودکار طرح دار', 'admin', 12, 106, 1062, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'خودکار,ست خودکار,لوازم تحریر', 'ست 20 عددی خودکار با طرح‌های متنوع و شیک، مناسب هدیه', 95000, 65000, 0, '[]', NULL, NULL, NULL, 0, 1, 1, 500, 'ست', 0.00, 'percent', NULL, NULL, 'flat_rate', 0.00, 0, NULL, NULL, NULL, NULL, '20-piece-pen-set', 0.00, NULL, 0, NULL, NULL, '2026-05-15 13:44:33', '2026-05-15 13:44:33');

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, NULL, NULL, 'admin', 'مدیر سیستم', 'admin@example.com', 'admin', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `users` VALUES (2, NULL, NULL, 'customer', 'تکنسین نمونه', 'tech@example.com', 'technician', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
INSERT INTO `users` VALUES (3, NULL, NULL, 'customer', 'مشتری نمونه', 'customer@example.com', 'customer', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, 0, '2026-08-22 21:38:12', '2026-08-22 21:38:12');
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wishlists
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
