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

 Date: 15/05/2026 16:09:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
