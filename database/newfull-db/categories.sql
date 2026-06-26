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

 Date: 15/05/2026 16:08:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `categories` VALUES (100, 'لوازم آرایشی و بهداشتی', 0.00, NULL, NULL, 1, 1, 0, 'beauty-cosmetics', 'لوازم آرایشی و بهداشتی', 'خرید لوازم آرایشی و بهداشتی اصل', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (101, 'ابزارآلات و یراق آلات', 0.00, NULL, NULL, 1, 1, 0, 'tools-hardware', 'ابزارآلات و یراق آلات', 'خرید انواع ابزارآلات صنعتی و یراق آلات', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (102, 'لوازم خانگی', 0.00, NULL, NULL, 1, 1, 0, 'home-appliances', 'لوازم خانگی', 'خرید لوازم خانگی برند با گارانتی', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (103, 'لوازم دیجیتال', 0.00, NULL, NULL, 1, 1, 0, 'digital-electronics', 'لوازم دیجیتال', 'خرید انواع گوشی موبایل، تبلت، لپ تاپ', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (104, 'مد و پوشاک', 0.00, NULL, NULL, 1, 1, 0, 'fashion-clothing', 'مد و پوشاک', 'خرید انواع پوشاک مردانه و زنانه با کیفیت', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (105, 'ورزش و سفر', 0.00, NULL, NULL, 1, 1, 0, 'sports-travel', 'ورزش و سفر', 'لوازم ورزشی و سفر با بهترین قیمت', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (106, 'کتاب و لوازم تحریر', 0.00, NULL, NULL, 1, 1, 0, 'books-stationery', 'کتاب و لوازم تحریر', 'خرید کتاب و لوازم تحریر ارزان', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (107, 'اسباب بازی و کودک', 0.00, NULL, NULL, 1, 1, 0, 'toys-kids', 'اسباب بازی و کودک', 'خرید اسباب بازی و لوازم کودک', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (108, 'مادر و کودک', 0.00, NULL, NULL, 1, 1, 0, 'mother-baby', 'مادر و کودک', 'لوازم بارداری و کودک', '2026-05-15 13:31:39', '2026-05-15 13:31:39');
INSERT INTO `categories` VALUES (109, 'صنایع دستی و هنری', 0.00, NULL, NULL, 1, 1, 0, 'handicrafts-art', 'صنایع دستی و هنری', 'خرید صنایع دستی و محصولات هنری', '2026-05-15 13:31:39', '2026-05-15 13:31:39');

SET FOREIGN_KEY_CHECKS = 1;
