-- ============================================================================
--  DRIKANA — فایل ارتقای دیتابیس (Platform Upgrade)
-- ============================================================================
--
--  پروژه      : سامانه طلا و جواهر دُریکانا (Laravel 5.8 / MariaDB / MySQL)
--  نسخه فایل  : 1.0
--  تاریخ      : 2026-10-12
--
--  ── این فایل چه کاری انجام می‌دهد؟ ────────────────────────────────────────
--  این فایل، دیتابیس نسخه قبلی پروژه را به نسخه بازسازی‌شده ارتقا می‌دهد.
--  شامل سه بخش است:
--     ۱) ساخت جداول جدید (RBAC، مالکیت، مقالات، حسابرسی امنیتی، ...)
--     ۲) افزودن ستون‌ها و ایندکس‌های تازه به جداول موجود
--     ۳) درج داده اولیه نقش‌ها و مجوزها
--
--  ── نکات بسیار مهم پیش از اجرا ───────────────────────────────────────────
--  • پیش از اجرا «حتماً» از دیتابیس خود نسخه پشتیبان (backup) بگیرید:
--
--        mysqldump -u USER -p DBNAME > backup_before_upgrade.sql
--
--  • این فایل «تکرارپذیر» (idempotent) است؛ یعنی اجرای چندباره آن بی‌خطر
--    است و خطای تکراری‌سازی نمی‌دهد. اگر جداول قبلاً ساخته شده باشند،
--    دست‌نخورده باقی می‌مانند.
--
--  • این فایل «هیچ داده‌ای را حذف نمی‌کند». تنها عملیات ممکن روی داده،
--    درج رکوردهای جدید (نقش/مجوز) و پرکردن ستون‌های تازه است.
--
--  • رمز عبور هیچ کاربری در این فایل تغییر نمی‌کند و هیچ کاربری ساخته
--    نمی‌شود. ساخت نخستین «مدیر کل سیستم» با دستور زیر انجام می‌شود:
--
--        php artisan drikana:make-super-admin
--
--  ── نحوه اجرا ────────────────────────────────────────────────────────────
--
--        mysql -u USER -p DBNAME < database/sql/platform_upgrade.sql
--
--    یا داخل mysql:
--
--        USE DBNAME;
--        SOURCE /path/to/database/sql/platform_upgrade.sql;
--
--  ── جایگزین (توصیه‌شده) ──────────────────────────────────────────────────
--  اگر کد پروژه را هم به‌روز کرده‌اید، ساده‌تر آن است که همین تغییرات را
--  از طریق مایگریشن‌های Laravel اعمال کنید:
--
--        php artisan migrate --force
--        php artisan db:seed --class=RbacSeeder --force
--
--  خروجی هر دو مسیر یکسان است؛ تفاوت فقط در ابزار اجراست.
-- ============================================================================


SET NAMES utf8mb4;
SET SQL_MODE = '';
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;
SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS;
SET UNIQUE_CHECKS = 0;


-- ============================================================================
--  بخش ۰ — ابزارهای کمکی (Stored Procedures)
-- ============================================================================
--
--  چرا لازم است؟
--  دستور `ALTER TABLE ... ADD COLUMN IF NOT EXISTS` فقط در MariaDB پشتیبانی
--  می‌شود و در MySQL خطا می‌دهد. برای سازگاری با «هر دو» موتور، از
--  رویه‌های زیر استفاده می‌کنیم که ابتدا وجود ستون/ایندکس را از
--  information_schema بررسی می‌کنند و سپس در صورت نیاز تغییر را اعمال
--  می‌کنند. این رویه‌ها در پایان فایل حذف می‌شوند.
-- ============================================================================

DROP PROCEDURE IF EXISTS drikana_add_column;
DROP PROCEDURE IF EXISTS drikana_add_index;
DROP PROCEDURE IF EXISTS drikana_add_unique;
DROP PROCEDURE IF EXISTS drikana_drop_procedures;

DELIMITER $$

CREATE PROCEDURE drikana_add_column(
    IN p_table  VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_ddl    TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = p_table AND column_name = p_column
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', p_table, '` ADD COLUMN `', p_column, '` ', p_ddl);
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE drikana_add_index(
    IN p_table VARCHAR(64),
    IN p_index VARCHAR(64),
    IN p_cols  TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.statistics
        WHERE table_schema = DATABASE() AND table_name = p_table AND index_name = p_index
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', p_table, '` ADD INDEX `', p_index, '` (', p_cols, ')');
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE drikana_add_unique(
    IN p_table VARCHAR(64),
    IN p_index VARCHAR(64),
    IN p_cols  TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.statistics
        WHERE table_schema = DATABASE() AND table_name = p_table AND index_name = p_index
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', p_table, '` ADD UNIQUE `', p_index, '` (', p_cols, ')');
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;


-- ============================================================================
--  بخش ۱ — جداول جدید
-- ============================================================================
--  ترتیب ساخت رعایت شده است: ابتدا جداول پایه (نقش/مجوز)، سپس جداولی که
--  به آن‌ها ارجاع می‌دهند. هیچ جدول موجودی بازسازی یا حذف نمی‌شود.
-- ============================================================================

-- ---------------------------------------------------------------------------
-- ۱-۱) جدول `permissions` — فهرست مجوزهای اتمی سیستم
--      هر رکورد یک «توانایی» مشخص است: مثلاً articles.publish
-- ---------------------------------------------------------------------------


-- --------------------------------------------------------------------------
-- ۱-1) جدول `permissions` (مجوزها)
-- فهرست مجوزهای اتمی سیستم. هر رکورد یک «توانایی» مشخص است،
--      مثلاً articles.publish یعنی «انتشار مقاله».
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `name` varchar(191) NOT NULL,
  `group` varchar(100) NOT NULL DEFAULT 'general',
  `description` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`),
  KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-2) جدول `permission_role` (نگاشت نقش به مجوز)
-- جدول واسط نقش↔مجوز. مجوزهای هر نقش از این جدول خوانده
--      می‌شود؛ نه از ستون JSON قدیمی `roles.permissions`.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `permission_role` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-3) جدول `role_user` (نگاشت کاربر به نقش)
-- جدول واسط کاربر↔نقش. هر کاربر می‌تواند چند نقش داشته باشد.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `assigned_by` int(10) unsigned DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_user_id_index` (`user_id`),
  KEY `role_user_role_id_index` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-4) جدول `audit_logs` (گزارش حسابرسی)
-- گزارش حسابرسی امنیتی. همه رخدادهای حساس (ورود، تغییر نقش،
--      رد دسترسی، تأیید انتقال مالکیت، ...) اینجا ثبت می‌شود.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `event` varchar(100) NOT NULL,
  `auditable_type` varchar(191) DEFAULT NULL,
  `auditable_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `severity` varchar(20) NOT NULL DEFAULT 'info',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_auditable_index` (`auditable_type`,`auditable_id`),
  KEY `audit_logs_user_id_index` (`user_id`),
  KEY `audit_logs_event_index` (`event`),
  KEY `audit_logs_created_at_index` (`created_at`),
  KEY `audit_logs_severity_index` (`severity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-5) جدول `ownership_history` (دفتر کل مالکیت)
-- دفتر کل «فقط-افزودنی» سابقه مالکیت هر شناسنامه.
--      هر رکورد یک رویداد مالکیت را با شماره ترتیبی یکتا ثبت می‌کند.
--      محدودیت UNIQUE روی (certificate_id, sequence_no) تضمین می‌کند
--      دو رویداد هم‌شماره برای یک شناسنامه ثبت نشود (ضد شرایط رقابتی).
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `ownership_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `certificate_id` int(10) unsigned NOT NULL,
  `from_owner_id` int(10) unsigned DEFAULT NULL,
  `to_owner_id` int(10) unsigned NOT NULL,
  `transfer_id` int(10) unsigned DEFAULT NULL,
  `sequence_no` int(10) unsigned NOT NULL DEFAULT 1,
  `event_type` varchar(30) NOT NULL DEFAULT 'sale',
  `notes` text DEFAULT NULL,
  `declared_value` decimal(15,2) DEFAULT NULL,
  `recorded_by` int(10) unsigned DEFAULT NULL,
  `effective_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ownership_history_certificate_sequence_unique` (`certificate_id`,`sequence_no`),
  KEY `ownership_history_certificate_id_index` (`certificate_id`),
  KEY `ownership_history_from_owner_id_index` (`from_owner_id`),
  KEY `ownership_history_to_owner_id_index` (`to_owner_id`),
  KEY `ownership_history_effective_at_index` (`effective_at`),
  KEY `ownership_history_event_type_index` (`event_type`),
  CONSTRAINT `ownership_history_certificate_id_foreign` FOREIGN KEY (`certificate_id`) REFERENCES `jewelry_certificates` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-6) جدول `article_categories` (دسته‌بندی مقالات)
-- دسته‌بندی موضوعی مقالات (طلا، جواهر، سنگ قیمتی، شناسنامه،
--      نگهداری اشیاء قیمتی، ...). با پشتیبانی از زیردسته.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `article_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_categories_slug_unique` (`slug`),
  KEY `article_categories_parent_id_index` (`parent_id`),
  KEY `article_categories_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-7) جدول `articles` (مقالات)
-- مقالات آموزشی. ستون `status` چرخه انتشار را نگه می‌دارد
--      (draft / pending_review / published / archived).
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(191) DEFAULT NULL,
  `featured_image_alt` varchar(191) DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `author_name` varchar(191) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `submitted_by` int(10) unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `published_by` int(10) unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_category_id_index` (`category_id`),
  KEY `articles_status_index` (`status`),
  KEY `articles_published_at_index` (`published_at`),
  KEY `articles_user_id_index` (`user_id`),
  KEY `articles_is_featured_index` (`is_featured`),
  CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `article_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-8) جدول `article_images` (تصاویر مقالات)
-- تصاویر درون‌متنی مقالات. مسیر فایل‌ها در `path` ذخیره می‌شود
--      و تنها فایل‌های اعتبارسنجی‌شده توسط Media\SecureImageUploader
--      اجازه ورود به این جدول را دارند.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `article_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `path` varchar(191) NOT NULL,
  `original_name` varchar(191) DEFAULT NULL,
  `alt` varchar(191) DEFAULT NULL,
  `caption` varchar(500) DEFAULT NULL,
  `file_size` int(10) unsigned DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_images_article_id_index` (`article_id`),
  CONSTRAINT `article_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ---------------------------------------------------------------------------
-- ۱-۱۰) جدول `repair_order_status_logs` (تاریخچه تغییر وضعیت تعمیرات)
--      کنترلر `Jewelry\Admin\RepairOrderController` هر تغییر وضعیت را در این
--      جدول ثبت می‌کند، اما این جدول هرگز ساخته نشده بود؛ نتیجه آن بود که
--      تاریخچه تعمیرات هیچ‌وقت ذخیره نمی‌شد و صفحه تاریخچه همیشه خالی بود
--      بدون اینکه خطایی دیده شود (خطا در try/catch بی‌صدا بلعیده می‌شد).
--
--      نکته مهم: نوع ستون `repair_order_id` باید دقیقاً با `repair_orders.id`
--      یکسان باشد (int unsigned). اگر bigint انتخاب شود، ساخت کلید خارجی با
--      خطای errno 150 «Foreign key constraint is incorrectly formed» شکست
--      می‌خورد.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `repair_order_status_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `repair_order_id` int(10) unsigned NOT NULL,
  `old_status` varchar(100) DEFAULT NULL,
  `new_status` varchar(100) NOT NULL,
  `changed_by` int(10) unsigned DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repair_order_status_logs_order_index` (`repair_order_id`),
  KEY `repair_order_status_logs_user_index` (`changed_by`),
  KEY `repair_order_status_logs_created_index` (`created_at`),
  CONSTRAINT `repair_order_status_logs_order_foreign` FOREIGN KEY (`repair_order_id`) REFERENCES `repair_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `repair_order_status_logs_user_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ۱-9) جدول `login_attempts` (تلاش‌های ورود)
-- ثبت تلاش‌های ورود برای مقابله با حمله Brute Force.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(191) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `successful` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_attempts_identifier_index` (`identifier`,`created_at`),
  KEY `login_attempts_ip_index` (`ip_address`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------------
-- ۱-10) جدول `pages` (صفحات ثابت)
-- صفحات محتوایی ثابت (درباره ما، قوانین، ...).
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` varchar(1000) DEFAULT NULL,
  `keywords` varchar(1000) DEFAULT NULL,
  `meta_image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------------------------
-- ۱-11) جدول `pickup_points` (نقاط تحویل)
-- نقاط تحویل حضوری سفارش‌ها.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pickup_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `pick_up_status` tinyint(4) DEFAULT NULL,
  `cash_on_pickup_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------------------------
-- ۱-12) جدول `otp_configurations` (تنظیمات OTP)
-- تنظیمات ارسال کد یک‌بارمصرف (OTP).
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `otp_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `otp_configurations_type_unique` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------------------------
-- ۱-13) جدول `seller_withdraw_requests` (درخواست تسویه فروشنده)
-- درخواست‌های تسویه حساب فروشندگان.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `seller_withdraw_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `viewed` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `seller_withdraw_requests_user_id_index` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------------------------
-- ۱-14) جدول `seller_payments` (پرداخت مدیریت به فروشنده)
-- تاریخچهٔ پرداخت‌های مدیریت به فروشندگان.
--
-- چرا اضافه شد: صفحهٔ «تاریخچهٔ پرداخت» در داشبورد فروشنده
-- (/seller/payments) با خطای ۵۰۰ «Unknown column 'seller_id'» از کار
-- افتاده بود، چون کنترلر از جدول `payments` (پرداخت سفارش‌ها) استفاده
-- می‌کرد در حالی که ویوها ستون‌های payment_method و seller_id را
-- می‌خوانند. این جدول، محل درست نگهداری این داده است.
--
-- نکته: `seller_id` به جدول `users` اشاره می‌کند (شناسهٔ کاربری فروشنده)
-- و با ON DELETE SET NULL تعریف شده تا حذف کاربر، تاریخچهٔ مالی را
-- از بین نبرد. این جدول هیچ رابطه‌ای با جدول `payments` ندارد و به آن
-- دست نمی‌زند.
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `seller_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` int(10) unsigned DEFAULT NULL,
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `payment_details` text DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `txn_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `seller_payments_seller_id_index` (`seller_id`) USING BTREE,
  KEY `seller_payments_created_at_index` (`created_at`) USING BTREE,
  CONSTRAINT `seller_payments_seller_id_foreign` FOREIGN KEY (`seller_id`)
      REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ============================================================================
--  بخش ۲ — تغییرات جداول موجود
-- ============================================================================
--  هیچ ستونی حذف نمی‌شود و هیچ داده‌ای بازنویسی نمی‌گردد. فقط ستون‌های
--  تازه، ایندکس‌ها و محدودیت‌های یکتایی اضافه می‌شوند.
-- ============================================================================

-- ---------------------------------------------------------------------------
-- ۲-۱) جدول `roles` — گسترش برای RBAC پویا
-- ---------------------------------------------------------------------------
--  جدول `roles` از قبل در پروژه وجود داشته و تنها ستون‌های id, name و
--  permissions (JSON) را داشته است. طبق اصل «ساختار تکراری نساز»، همان
--  جدول توسعه داده می‌شود:
--
--      slug        : شناسه یکتا و لاتین نقش، برای ارجاع در کد و میدل‌ور
--      description : توضیح نقش برای نمایش در پنل مدیریت
--      level       : وزن نقش؛ نقش با level بالاتر اولویت دارد
--                    (100 = مدیر کل سیستم … 10 = کاربر عادی)
--      is_system   : نقش سیستمی را نمی‌توان از پنل حذف کرد
--      is_active   : امکان غیرفعال‌سازی موقت نقش، بدون حذف آن
--
--  ستون قدیمی `permissions` برای سازگاری حذف نمی‌شود؛ اما مرجع نهایی
--  مجوزها جدول `permission_role` است.
-- ---------------------------------------------------------------------------

CALL drikana_add_column('roles', 'slug',        'VARCHAR(100) NULL AFTER `name`');
CALL drikana_add_column('roles', 'description', 'VARCHAR(500) NULL AFTER `permissions`');
CALL drikana_add_column('roles', 'level',       'SMALLINT UNSIGNED NOT NULL DEFAULT 0 AFTER `description`');
CALL drikana_add_column('roles', 'is_system',   'TINYINT(1) NOT NULL DEFAULT 0 AFTER `level`');
CALL drikana_add_column('roles', 'is_active',   'TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_system`');

-- ---------------------------------------------------------------------------
-- ۲-۲) جدول `jewelry_certificates` — رفع اختلاف مدل و دیتابیس
-- ---------------------------------------------------------------------------
--  مدل Eloquent از این ستون‌ها استفاده می‌کرد اما در دیتابیس وجود نداشتند
--  (Schema Drift). این ناهماهنگی باعث خطای «Unknown column» می‌شد.
-- ---------------------------------------------------------------------------

CALL drikana_add_column('jewelry_certificates', 'is_active',        'TINYINT(1) NOT NULL DEFAULT 1');
CALL drikana_add_column('jewelry_certificates', 'created_by',       'INT UNSIGNED NULL');
CALL drikana_add_column('jewelry_certificates', 'approved_by',      'INT UNSIGNED NULL');
CALL drikana_add_column('jewelry_certificates', 'approved_at',      'TIMESTAMP NULL');
CALL drikana_add_column('jewelry_certificates', 'rejected_at',      'TIMESTAMP NULL');
CALL drikana_add_column('jewelry_certificates', 'rejection_reason', 'VARCHAR(500) NULL');
-- یادداشت تأیید/رد شناسنامه (ستون‌های verified_at/verified_by عامدانه ساخته
-- نمی‌شوند؛ approved_at/approved_by همین نقش را دارند)
CALL drikana_add_column('jewelry_certificates', 'verification_notes', 'VARCHAR(500) NULL');

CALL drikana_add_index('jewelry_certificates', 'jewelry_certificates_created_by_index', '`created_by`');
CALL drikana_add_index('jewelry_certificates', 'jewelry_certificates_is_verified_index', '`is_verified`');
CALL drikana_add_index('jewelry_certificates', 'jewelry_certificates_is_active_index', '`is_active`');
CALL drikana_add_index('jewelry_certificates', 'jewelry_certificates_issued_at_index', '`issued_at`');

-- ---------------------------------------------------------------------------
-- ۲-۳) جدول `jewelry_transfers` — ستون‌های گردش تأیید انتقال
-- ---------------------------------------------------------------------------
--  ستون `status` با مقدار پیش‌فرض 'approved' اضافه می‌شود تا رکوردهای
--  تاریخی موجود پروژه (که همگی انتقال انجام‌شده هستند) بی‌آنکه بازنویسی
--  شوند، معنای درست خود را حفظ کنند.
-- ---------------------------------------------------------------------------

CALL drikana_add_column('jewelry_transfers', 'price',         'DECIMAL(15,2) NULL');
CALL drikana_add_column('jewelry_transfers', 'contract_file', 'VARCHAR(191) NULL');
CALL drikana_add_column('jewelry_transfers', 'requested_by',  'INT UNSIGNED NULL');
CALL drikana_add_column('jewelry_transfers', 'approved_by',   'INT UNSIGNED NULL');
CALL drikana_add_column('jewelry_transfers', 'status',        "VARCHAR(20) NOT NULL DEFAULT 'approved'");
CALL drikana_add_column('jewelry_transfers', 'approval_note', 'VARCHAR(500) NULL');
CALL drikana_add_column('jewelry_transfers', 'approved_at',   'TIMESTAMP NULL');

CALL drikana_add_index('jewelry_transfers', 'jewelry_transfers_certificate_id_index', '`certificate_id`');
CALL drikana_add_index('jewelry_transfers', 'jewelry_transfers_from_owner_id_index',  '`from_owner_id`');
CALL drikana_add_index('jewelry_transfers', 'jewelry_transfers_to_owner_id_index',    '`to_owner_id`');
CALL drikana_add_index('jewelry_transfers', 'jewelry_transfers_status_index',         '`status`');

-- ---------------------------------------------------------------------------
-- ۲-۴) جدول `jewelry_owners` — ایندکس جست‌وجو بر اساس کد ملی
-- ---------------------------------------------------------------------------
CALL drikana_add_index('jewelry_owners', 'jewelry_owners_national_id_index', '`national_id`');

-- ---------------------------------------------------------------------------
-- ۲-۵) جدول `missing_reports` — پیوست گزارش پلیس
-- ---------------------------------------------------------------------------
CALL drikana_add_column('missing_reports', 'police_report_path', 'VARCHAR(191) NULL');
CALL drikana_add_column('missing_reports', 'police_report_name', 'VARCHAR(191) NULL');

-- ---------------------------------------------------------------------------
-- ۲-۶) جدول `order_details` — تحویل حضوری
-- ---------------------------------------------------------------------------
CALL drikana_add_column('order_details', 'shipping_type',   'VARCHAR(255) NULL');
CALL drikana_add_column('order_details', 'pickup_point_id', 'INT UNSIGNED NULL');
CALL drikana_add_index('order_details', 'order_details_pickup_point_id_index', '`pickup_point_id`');


-- ============================================================================
--  بخش ۳ — یکپارچه‌سازی نقش‌های قدیمی
-- ============================================================================
--  اگر در دیتابیس شما از قبل رکوردهایی در جدول `roles` وجود داشته باشد
--  (سیستم قدیمی پروژه)، این بخش برای آن‌ها `slug` تولید می‌کند تا با
--  سیستم جدید سازگار شوند. هیچ رکوردی حذف یا تغییر نام داده نمی‌شود.
-- ============================================================================

UPDATE `roles`
SET `slug` = CASE `name`
        WHEN 'مدیر کل سیستم'  THEN 'super_admin'
        WHEN 'مدیر فروشگاه'   THEN 'store_manager'
        WHEN 'تکنسین تعمیرات' THEN 'repair_technician'
        WHEN 'مدیر مالکیت'    THEN 'ownership_manager'
        WHEN 'مدیر محتوا'     THEN 'content_manager'
        WHEN 'کاربر عادی'     THEN 'customer'
        WHEN 'مدیر'           THEN 'admin'
        ELSE CONCAT('legacy_role_', `id`)
    END
WHERE `slug` IS NULL OR `slug` = '';

CALL drikana_add_unique('roles', 'roles_slug_unique', '`slug`');


-- ============================================================================
--  بخش ۴ — داده اولیه: نقش‌ها و مجوزها
-- ============================================================================
--  شش نقش پایه سیستم مطابق خواسته کارفرما:
--      مدیر کل سیستم | مدیر فروشگاه | تکنسین تعمیرات
--      مدیر مالکیت   | مدیر محتوا   | کاربر عادی
--
--  همه درج‌ها با ON DUPLICATE KEY UPDATE انجام می‌شود؛ بنابراین اجرای
--  دوباره فایل، رکورد جدیدی نمی‌سازد و مجوزهای دستی‌ای که مدیر سیستم
--  بعداً به نقش‌ها داده است پاک نمی‌شود.
--
--  ⚠ نکته امنیتی: در این فایل هیچ رمز عبوری ساخته یا بازنویسی نمی‌شود
--  و هیچ کاربری به هیچ نقشی انتساب داده نمی‌شود. نخستین «مدیر کل سیستم»
--  باید از طریق دستور زیر ساخته شود:
--
--      php artisan drikana:make-super-admin
-- ============================================================================

INSERT INTO `roles` (`slug`, `name`, `description`, `level`, `is_system`, `is_active`, `permissions`, `created_at`, `updated_at`)
VALUES
    ('super_admin',       'مدیر کل سیستم',  'دسترسی کامل به تمام بخش‌های سیستم. تنها نقش مجاز به مدیریت نقش‌ها و مجوزها.', 100, 1, 1, '[]', NOW(), NOW()),
    ('store_manager',     'مدیر فروشگاه',   'مدیریت محصولات، دسته‌بندی‌ها، سفارش‌ها، فروشندگان و تنظیمات فروشگاه.',          60, 1, 1, '[]', NOW(), NOW()),
    ('ownership_manager', 'مدیر مالکیت',    'مدیریت شناسنامه‌ها، مالکان، انتقال مالکیت و گزارش‌های مالکیت.',                 50, 1, 1, '[]', NOW(), NOW()),
    ('repair_technician', 'تکنسین تعمیرات', 'مشاهده و به‌روزرسانی سفارش‌های تعمیر و ثبتگزارش پیشرفت کار.',                    40, 1, 1, '[]', NOW(), NOW()),
    ('content_manager',   'مدیر محتوا',     'ایجاد، ویرایش و انتشار مقالات و محتوای آموزشی.',                                  30, 1, 1, '[]', NOW(), NOW()),
    ('customer',          'کاربر عادی',     'کاربر فروشگاه. دسترسی به سفارش‌های خود، جواهرات خود و درخواست انتقال مالکیت.',      10, 1, 1, '[]', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name`        = VALUES(`name`),
    `description` = VALUES(`description`),
    `level`       = VALUES(`level`),
    `is_system`   = VALUES(`is_system`);


-- ---------------------------------------------------------------------------
-- ۴-۱) مجوزهای سیستم (86 مجوز) — گروه‌بندی‌شده
-- ---------------------------------------------------------------------------
--  نام‌گذاری: <گروه>.<عملیات>. برای هر عملیات حساس، مجوز جداگانه تعریف
--  شده است (نمایش / ایجاد / ویرایش / حذف / تأیید / انتشار) تا اصل
--  «تفکیک وظایف» (Separation of Duties) قابل اعمال باشد.
-- ---------------------------------------------------------------------------

INSERT INTO `permissions` (`slug`, `name`, `group`, `description`, `created_at`, `updated_at`)
VALUES
    ('jewelry.repairs.view', 'مشاهده سفارش‌های تعمیر مجاز', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.repairs.create', 'ثبت سفارش تعمیر', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.repairs.update', 'ویرایش سفارش تعمیر', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.repairs.update_status', 'ثبت عیب، شرح تعمیر، هزینه و تغییر وضعیت', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.repairs.assign', 'تخصیص تکنسین به سفارش', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.repairs.delete', 'حذف سفارش تعمیر', 'جواهرات — تعمیرات', '', NOW(), NOW()),
    ('jewelry.missing_reports.view', 'مشاهده گزارش‌های سرقت و مفقودی', 'جواهرات — سرقت و مفقودی', '', NOW(), NOW()),
    ('jewelry.missing_reports.create', 'ثبت گزارش سرقت و مفقودی', 'جواهرات — سرقت و مفقودی', '', NOW(), NOW()),
    ('jewelry.missing_reports.update_status', 'تغییر وضعیت گزارش', 'جواهرات — سرقت و مفقودی', '', NOW(), NOW()),
    ('jewelry.missing_reports.print', 'چاپ گزارش', 'جواهرات — سرقت و مفقودی', '', NOW(), NOW()),
    ('jewelry.certificates.view', 'مشاهده شناسنامه‌ها', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.create', 'صدور شناسنامه جدید', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.update', 'ویرایش شناسنامه', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.delete', 'حذف شناسنامه', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.verify', 'تأیید اصالت شناسنامه', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.download', 'دانلود فایل شناسنامه', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.certificates.print', 'چاپ شناسنامه', 'جواهرات — شناسنامه', '', NOW(), NOW()),
    ('jewelry.reports.view', 'مشاهده گزارش‌های ماژول جواهرات', 'جواهرات — گزارش‌ها', '', NOW(), NOW()),
    ('jewelry.reports.export', 'خروجی گرفتن از گزارش‌ها', 'جواهرات — گزارش‌ها', '', NOW(), NOW()),
    ('jewelry.dashboard.view', 'مشاهده داشبورد اشیاء قیمتی', 'جواهرات — مالکان', '', NOW(), NOW()),
    ('jewelry.owners.view', 'مشاهده مالکان اشیاء', 'جواهرات — مالکان', '', NOW(), NOW()),
    ('jewelry.owners.create', 'ثبت مالک جدید', 'جواهرات — مالکان', '', NOW(), NOW()),
    ('jewelry.owners.update', 'ویرایش اطلاعات مالک', 'جواهرات — مالکان', '', NOW(), NOW()),
    ('jewelry.owners.delete', 'حذف مالک', 'جواهرات — مالکان', '', NOW(), NOW()),
    ('jewelry.transfers.view', 'مشاهده درخواست‌های انتقال مالکیت', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('jewelry.transfers.create', 'ثبت درخواست انتقال مالکیت', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('jewelry.transfers.approve', 'تأیید انتقال مالکیت', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('jewelry.transfers.reject', 'رد انتقال مالکیت', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('jewelry.transfers.cancel', 'لغو انتقال مالکیت', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('jewelry.transfers.delete', 'حذف درخواست انتقال (فقط رد/لغو شده)', 'جواهرات — مالکیت', '', NOW(), NOW()),
    ('dashboard.view', 'مشاهده داشبورد مدیریت', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('settings.view', 'مشاهده تنظیمات', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('settings.update', 'ویرایش تنظیمات اصلی', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('users.view', 'مشاهده کاربران', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('users.create', 'ایجاد کاربر', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('users.update', 'ویرایش کاربران', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('users.delete', 'حذف کاربران', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('roles.view', 'مشاهده نقش‌ها', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('roles.create', 'ایجاد نقش', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('roles.update', 'ویرایش نقش', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('roles.delete', 'حذف نقش', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('roles.assign', 'انتساب نقش به کاربران', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('permissions.view', 'مشاهده فهرست مجوزها', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('permissions.create', 'ایجاد مجوز جدید', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('permissions.delete', 'حذف مجوز', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('audit_logs.view', 'مشاهده گزارش‌های امنیتی و Audit Log', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('security.reports.view', 'مشاهده گزارش‌های مدیریتی و امنیتی', 'سیستم و دسترسی‌ها', '', NOW(), NOW()),
    ('orders.view', 'مشاهده سفارش‌ها', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('orders.update', 'ویرایش سفارش‌ها', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('orders.update_status', 'تغییر وضعیت سفارش', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('orders.delete', 'حذف سفارش', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('orders.invoice', 'دانلود فاکتور', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('sales.reports.view', 'مشاهده گزارش‌های فروش', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('sellers.view', 'مشاهده فروشندگان', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('sellers.approve', 'تأیید / رد فروشندگان', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('coupons.manage', 'مدیریت کدهای تخفیف', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('withdraw_requests.view', 'مشاهده درخواست‌های تسویه', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('withdraw_requests.approve', 'تأیید درخواست تسویه', 'فروشگاه — سفارش‌ها', '', NOW(), NOW()),
    ('products.view', 'مشاهده محصولات', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('products.create', 'ایجاد محصول', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('products.update', 'ویرایش محصول', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('products.delete', 'حذف محصول', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('products.publish', 'انتشار / غیرفعال‌سازی محصول', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('categories.view', 'مشاهده دسته‌بندی‌ها', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('categories.manage', 'مدیریت دسته‌بندی‌ها', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('brands.view', 'مشاهده برندها', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('brands.manage', 'مدیریت برندها', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('stock.view', 'مشاهده موجودی', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('stock.update', 'به‌روزرسانی موجودی', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('products.images.manage', 'مدیریت تصاویر محصولات', 'فروشگاه — محصولات', '', NOW(), NOW()),
    ('pages.manage', 'مدیریت صفحات', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('sliders.manage', 'مدیریت اسلایدرها', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('banners.manage', 'مدیریت بنرها', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('policies.manage', 'مدیریت قوانین و مقررات', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('seo.manage', 'مدیریت تنظیمات SEO', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('languages.manage', 'مدیریت زبان‌ها', 'محتوا و ظاهر سایت', '', NOW(), NOW()),
    ('articles.view', 'مشاهده همه مقالات', 'مقالات', '', NOW(), NOW()),
    ('articles.view_own', 'مشاهده مقالات خود', 'مقالات', '', NOW(), NOW()),
    ('articles.create', 'ایجاد مقاله', 'مقالات', '', NOW(), NOW()),
    ('articles.update', 'ویرایش مقاله', 'مقالات', '', NOW(), NOW()),
    ('articles.update_any', 'ویرایش مقاله‌های سایر نویسندگان', 'مقالات', '', NOW(), NOW()),
    ('articles.submit', 'ارسال مقاله برای بررسی', 'مقالات', '', NOW(), NOW()),
    ('articles.publish', 'انتشار / لغو انتشار مقاله', 'مقالات', '', NOW(), NOW()),
    ('articles.delete', 'حذف مقاله', 'مقالات', '', NOW(), NOW()),
    ('articles.categories.manage', 'مدیریت دسته‌بندی مقاله‌ها', 'مقالات', '', NOW(), NOW()),
    ('articles.images.manage', 'مدیریت تصاویر مقاله', 'مقالات', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name`        = VALUES(`name`),
    `group`       = VALUES(`group`),
    `description` = VALUES(`description`);

-- ---------------------------------------------------------------------------
-- ۴-۲) انتساب مجوزها به نقش‌ها (143 انتساب)
-- ---------------------------------------------------------------------------
--  این انتساب‌ها فقط «مجوزهای پیش‌فرض» هستند. مدیر کل سیستم می‌تواند از
--  پنل مدیریت (مسیر /admin/roles) آن‌ها را تغییر دهد. INSERT IGNORE
--  تضمین می‌کند اجرای دوباره فایل، انتساب‌های موجود را دست‌نخورده بگذارد
--  و انتخاب‌های بعدی مدیر را بازنویسی نکند.
-- ---------------------------------------------------------------------------

-- مدیر کل سیستم — تمام ۸۶ مجوز (86 مجوز)
INSERT IGNORE INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT r.id, p.id, NOW(), NOW()
FROM `roles` r JOIN `permissions` p
WHERE r.`slug` = 'super_admin'
  AND p.`slug` IN (
      'dashboard.view',
      'settings.view',
      'settings.update',
      'users.view',
      'users.create',
      'users.update',
      'users.delete',
      'roles.view',
      'roles.create',
      'roles.update',
      'roles.delete',
      'roles.assign',
      'permissions.view',
      'permissions.create',
      'permissions.delete',
      'audit_logs.view',
      'security.reports.view',
      'products.view',
      'products.create',
      'products.update',
      'products.delete',
      'products.publish',
      'categories.view',
      'categories.manage',
      'brands.view',
      'brands.manage',
      'stock.view',
      'stock.update',
      'products.images.manage',
      'orders.view',
      'orders.update',
      'orders.update_status',
      'orders.delete',
      'orders.invoice',
      'sales.reports.view',
      'sellers.view',
      'sellers.approve',
      'coupons.manage',
      'withdraw_requests.view',
      'withdraw_requests.approve',
      'jewelry.dashboard.view',
      'jewelry.owners.view',
      'jewelry.owners.create',
      'jewelry.owners.update',
      'jewelry.owners.delete',
      'jewelry.certificates.view',
      'jewelry.certificates.create',
      'jewelry.certificates.update',
      'jewelry.certificates.delete',
      'jewelry.certificates.verify',
      'jewelry.certificates.download',
      'jewelry.certificates.print',
      'jewelry.repairs.view',
      'jewelry.repairs.create',
      'jewelry.repairs.update',
      'jewelry.repairs.update_status',
      'jewelry.repairs.assign',
      'jewelry.repairs.delete',
      'jewelry.transfers.view',
      'jewelry.transfers.create',
      'jewelry.transfers.approve',
      'jewelry.transfers.reject',
      'jewelry.transfers.cancel',
      'jewelry.transfers.delete',
      'jewelry.missing_reports.view',
      'jewelry.missing_reports.create',
      'jewelry.missing_reports.update_status',
      'jewelry.missing_reports.print',
      'jewelry.reports.view',
      'jewelry.reports.export',
      'articles.view',
      'articles.view_own',
      'articles.create',
      'articles.update',
      'articles.update_any',
      'articles.submit',
      'articles.publish',
      'articles.delete',
      'articles.categories.manage',
      'articles.images.manage',
      'pages.manage',
      'sliders.manage',
      'banners.manage',
      'policies.manage',
      'seo.manage',
      'languages.manage'
  );

-- مدیر فروشگاه (22 مجوز)
INSERT IGNORE INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT r.id, p.id, NOW(), NOW()
FROM `roles` r JOIN `permissions` p
WHERE r.`slug` = 'store_manager'
  AND p.`slug` IN (
      'dashboard.view',
      'products.view',
      'products.create',
      'products.update',
      'products.delete',
      'products.publish',
      'categories.view',
      'categories.manage',
      'brands.view',
      'brands.manage',
      'stock.view',
      'stock.update',
      'products.images.manage',
      'orders.view',
      'orders.update',
      'orders.update_status',
      'orders.invoice',
      'sales.reports.view',
      'coupons.manage',
      'jewelry.dashboard.view',
      'jewelry.certificates.view',
      'jewelry.certificates.print'
  );

-- تکنسین تعمیرات (7 مجوز)
INSERT IGNORE INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT r.id, p.id, NOW(), NOW()
FROM `roles` r JOIN `permissions` p
WHERE r.`slug` = 'repair_technician'
  AND p.`slug` IN (
      'dashboard.view',
      'jewelry.dashboard.view',
      'jewelry.certificates.view',
      'jewelry.repairs.view',
      'jewelry.repairs.create',
      'jewelry.repairs.update',
      'jewelry.repairs.update_status'
  );

-- مدیر مالکیت (17 مجوز)
INSERT IGNORE INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT r.id, p.id, NOW(), NOW()
FROM `roles` r JOIN `permissions` p
WHERE r.`slug` = 'ownership_manager'
  AND p.`slug` IN (
      'dashboard.view',
      'jewelry.dashboard.view',
      'jewelry.owners.view',
      'jewelry.owners.create',
      'jewelry.owners.update',
      'jewelry.certificates.view',
      'jewelry.certificates.verify',
      'jewelry.certificates.download',
      'jewelry.certificates.print',
      'jewelry.transfers.view',
      'jewelry.transfers.create',
      'jewelry.transfers.approve',
      'jewelry.transfers.reject',
      'jewelry.transfers.cancel',
      'jewelry.missing_reports.view',
      'jewelry.missing_reports.update_status',
      'jewelry.reports.view'
  );

-- مدیر محتوا (11 مجوز)
INSERT IGNORE INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT r.id, p.id, NOW(), NOW()
FROM `roles` r JOIN `permissions` p
WHERE r.`slug` = 'content_manager'
  AND p.`slug` IN (
      'dashboard.view',
      'articles.view',
      'articles.view_own',
      'articles.create',
      'articles.update',
      'articles.submit',
      'articles.publish',
      'articles.delete',
      'articles.categories.manage',
      'articles.images.manage',
      'pages.manage'
  );

-- نکته: نقش «کاربر عادی» (customer) عامدانه هیچ مجوز مدیریتی ندارد؛
-- کاربر عادی فقط به بخش‌های عمومی سایت و دارایی‌های خودش دسترسی دارد.


-- ============================================================================
--  بخش ۵ — پاک‌سازی رویه‌های کمکی
-- ============================================================================
--  رویه‌های موقت بخش ۰ دیگر لازم نیستند و حذف می‌شوند تا در دیتابیس
--  باقی نمانند.
-- ============================================================================

DROP PROCEDURE IF EXISTS drikana_add_column;
DROP PROCEDURE IF EXISTS drikana_add_index;
DROP PROCEDURE IF EXISTS drikana_add_unique;
DROP PROCEDURE IF EXISTS drikana_drop_procedures;

SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;


-- ============================================================================
--  بخش ۶ — بررسی نتیجه (Verification)
-- ============================================================================
--  پس از اجرای این کوئری‌ها باید مقادیر زیر را ببینید:
--
--      جدول‌های جدید      : ۱۴
--      نقش‌های سیستمی     : ۶
--      مجوزها             : ۸۶
--      انتساب نقش-مجوز    : ۱۴۳
--
-- ============================================================================

-- تعداد مجوزها (انتظار: ۸۶)
SELECT COUNT(*) AS `تعداد مجوزها` FROM `permissions`;

-- نقش‌ها و تعداد مجوز هر نقش
SELECT
    r.`slug`                      AS `نقش`,
    r.`name`                      AS `عنوان`,
    r.`level`                     AS `سطح`,
    COUNT(pr.`permission_id`)     AS `تعداد مجوز`
FROM `roles` r
LEFT JOIN `permission_role` pr ON pr.`role_id` = r.`id`
GROUP BY r.`id`, r.`slug`, r.`name`, r.`level`
ORDER BY r.`level` DESC;

-- وجود جدول پرداخت فروشندگان (باید ۱ برگرداند)
SELECT COUNT(*) AS `جدول seller_payments`
FROM information_schema.tables
WHERE table_schema = DATABASE() AND table_name = 'seller_payments';

-- وجود ستون‌های تازه (باید ۲۳ سطر باشد)
SELECT COUNT(*) AS `ستون‌های افزوده‌شده`
FROM information_schema.columns
WHERE table_schema = DATABASE()
  AND (
       (table_name = 'roles'                 AND column_name IN ('slug','description','level','is_system','is_active'))
    OR (table_name = 'jewelry_certificates'  AND column_name IN ('is_active','created_by','approved_by','approved_at','rejected_at','rejection_reason','verification_notes'))
    OR (table_name = 'jewelry_transfers'     AND column_name IN ('price','contract_file','requested_by','approved_by','status','approval_note','approved_at'))
    OR (table_name = 'missing_reports'       AND column_name IN ('police_report_path','police_report_name'))
    OR (table_name = 'order_details'         AND column_name IN ('shipping_type','pickup_point_id'))
  );

-- ============================================================================
--  پایان فایل ارتقا
--  گام بعدی: ساخت نخستین مدیر کل سیستم
--      php artisan drikana:make-super-admin
-- ============================================================================
