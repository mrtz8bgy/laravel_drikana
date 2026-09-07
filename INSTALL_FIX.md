# راهنمای رفع خطای `Table 'drikana-db.sellers' doesn't exist`

## ⚠️ دلیل خطا
پروژه Active eCommerce (که دریکانا روی آن ساخته شده) انتظار دارد ده‌ها جدول پایه در دیتابیس موجود باشند، اما در نصب شما فقط جدول‌های جواهرسازی ایجاد شده و جدول‌های هسته فروشگاه (`sellers`, `shops`, `products`, `categories`, ...) ایجاد نشده‌اند.

## ✅ راه‌حل کامل
در شاخه اصلی پروژه (همین پوشه) دستورات زیر را به ترتیب اجرا کنید:

### مرحله ۱: مایگریشن‌ها (ایجاد جدول‌ها)
```bash
php artisan migrate
```
> این دستور **۳۱ جدول** هسته فروشگاه را ایجاد می‌کند که در فایل `database/migrations/2026_09_07_100001_create_drikana_core_ecommerce_tables.php` تعریف شده‌اند، همچنین ستون‌های کم‌شده کاربران را اضافه می‌کند.

### مرحله ۲: سیدرها (داده‌های پیش‌فرض)
```bash
php artisan db:seed --class=DrikanaCoreInstallSeeder
php artisan db:seed --class=DrikanaSampleDataSeeder
php artisan db:seed --class=DrikanaSampleProductsSeeder
```

هر سیدر چه کاری انجام می‌دهد:
| Seeder | محتوا |
|---|---|
| `DrikanaCoreInstallSeeder` | مدیر پیش‌فرض (`admin@drikana.com` / `123456`)، ارز (تومان)، زبان فارسی، تنظیمات اولیه سایت، گالری رسمی، اسلایدرها، لینک‌های فوتر، سیاست‌ها |
| `DrikanaSampleDataSeeder` | ۸ دسته‌بندی طلا/جواهر، ۸ برند لوکس، ۴ مالک نمونه، ۵ شناسنامه طلا |
| `DrikanaSampleProductsSeeder` | ۶ محصول نمونه طلا (انگشتر، گردنبند، ساعت رولکس، دستبند زمرد، نیم‌ست، حلقه کارتیه) |

### مرحله ۳: کش لاراول را پاک کنید
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

### مرحله ۴ (اختیاری): ذخیره لینک
```bash
php artisan storage:link
```

---

## 🔐 اطلاعات ورود پیش‌فرض پنل مدیریت
- **ایمیل**: `admin@drikana.com`
- **رمز عبور**: `123456`
- **آدرس پنل**: `/admin`

---

## 🛡️ حفاظت‌های اضافی که انجام شد
1. در `best_sellers_section.blade.php` چک وجود جدول `sellers` اضافه شد تا حتی اگر سیستم فروشنده غیرفعال بود، خطا ندهد.
2. در `nav.blade.php` و `index.blade.php` کوئری‌های دسته‌بندی با `Schema::hasTable` محافظ شدند.
3. به مدل‌های `Category` و `SubCategory` رابطه‌های alias با حروف کوچک اضافه شد تا با قالب‌های قدیمی هم سازگار باشند.
4. تنظیم `vendor_system_activation = 0` در سیدر پیش‌فرض (یعنی سیستم چند فروشنده غیرفعال است تا فروشنده‌ای اضافه کنید). اگر می‌خواهید سیستم فروشنده را فعال کنید، از پنل تنظیمات کسب‌وکار آن را روشن کنید.

---

## 📌 بعد از نصب موفق
سایت با تم لوکس طلایی/سرمه‌ای دریکانا، با لوگوی SVG اختصاصی، منوی بزرگ دسته‌بندی‌ها، هیرو، اسلایدر، بخش محصولات، استعلام شناسنامه، گزارش سرقت و رهگیری در دسترس خواهد بود.

اگر با خطای دیگری روبرو شدید، متن دقیق خطا را بدهید تا همان لحظه رفع کنم. 💎
