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

 Date: 15/05/2026 16:09:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
