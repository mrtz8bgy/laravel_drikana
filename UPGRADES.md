# 📜 گزارش ارتقاء پروژه Drikana (نسخه 2.0 لوکس)

تاریخ ارتقاء: ۱۴۰۴/۰۶/۱۷ — توسط Arena.ai

---

## ✨ خلاصه تغییرات

پروژه از حالت پایه‌ی Active eCommerce به یک **مارکت‌پلیس لوکس و تخصصی طلا و جواهر** با هویت بصری منحصربه‌فرد (تم سرمه‌ای-طلایی) تبدیل شد. طراحی جدید شامل هدر حرفه‌ای، هیرو جدید، سرویس‌های طلایی، بخش شناسنامه، استعلام، اعلام سرقت، سوالات متداول، و فوتر بازطراحی شده است.

---

## 🎨 طراحی و استایل (UI/UX)

### فایل جدید: `public/frontend/css/drikana-luxury.css`
- **پالت رنگی لوکس**: سرمه‌ای تیره (`#07091a`) + طلایی (`#d4af37`) + کرم و سفید
- **فونت فارسی حرفه‌ای**: Vazirmatn از CDN رسمی jsDelivr (جایگزین فونت سیستمی قبلی)
- **کامپوننت‌های لوکس**:
  - دکمه‌های گرادیانت طلایی با shimmer افکت
  - کارت‌ها با پس‌زمینه‌ی شیشه‌ای (glass-morphism) و حاشیه طلایی
  - جعبه‌های آمار و Show-case با سایه‌های طلایی
  - انیمیشن‌های ظریف: float، pulse، rotate، fadeIn، shimmer
  - اسکرول‌بار سفارشی طلایی
  - Back-to-Top دکمه طلایی
  - ریسپانسیو کامل برای موبایل/تبلت/دسکتاپ
- **اورراید استایل‌های پیش‌فرض Active eCommerce** برای هماهنگی با تم تیره
- **بخش‌های جدید استایل‌دهی شده**:
  - `.d-header` (هدر دو سطحی لوکس با جستجو، آیکون‌ها و منو)
  - `.d-hero` (هیرو با الماس متحرک، حلقه‌های چرخشی و کارت‌های شناور)
  - `.d-services` (۶ ویژگی کلیدی فروشگاه)
  - `.d-categories` (گرید دسته‌بندی‌ها با اموجی و انیمیشن)
  - `.d-trust` (بخش شناسنامه و امنیت با موکاپ گواهی)
  - `.d-verify-cta` (فرم استعلام سریع شناسنامه)
  - `.d-faq` (آکاردئون سوالات متداول)
  - `.d-footer` (فوتر چهار ستونه با خبرنامه و شبکه‌های اجتماعی)
  - `.d-cert-display` (گواهی‌نامه با تم کاغذ کهنه و مهر طلایی)
  - `.status-badge`, `.d-steps` (وضعیت‌ها و مراحل پیگیری)

---

## 🏗️ بهبودهای ساختاری / روت‌ها (routes/web.php)

روت‌های عمومی جدید (بدون نیاز به لاگین):

| روش | آدرس | نام | توضیح |
|---|---|---|---|
| GET | `/jewelry/certificate-verify` | `jewelry.certificates.verify_page` | صفحه اصلی استعلام شناسنامه |
| GET | `/jewelry/report-stolen` | `jewelry.missing_reports.create_public` | فرم عمومی اعلام سرقت |
| POST | `/jewelry/report-stolen` | `jewelry.missing_reports.store_public` | ذخیره گزارش |
| GET | `/jewelry/track/{serial?}` | `jewelry.track` | رهگیری عمومی قطعه |
| GET | `/jewelry/certificate-service` | `jewelry.service` | صفحه معرفی سرویس |

---

## 🧠 کنترلرهای جدید و بهبودیافته

### 🆕 `app/Http/Controllers/FrontendJewelryController.php`
کنترلر جدید برای صفحات عمومی جواهرات:
- `reportStolenForm()` — نمایش فرم گزارش سرقت (با لیست قطعات مالک اگر لاگین باشد)
- `reportStolenStore()` — ذخیره گزارش با اعتبارسنجی (چک مالکیت + جلوگیری از گزارش تکراری)
- `track()` — رهگیری قطعه با سریال

### ✏️ `app/Http/Controllers/Jewelry/JewelryCertificateController.php`
- افزودن متد `verifyPage()` برای صفحه استعلام عمومی (بدون نیاز به auth)
- اصلاح middleware برای عمومی کردن `publicVerify` و `verifyPage`
- بررسی وضعیت سرقت/مفقودی در هنگام استعلام

---

## 🖼️ ویوهای جدید (resources/views/frontend/...)

### `inc/nav.blade.php` — هدر کاملاً جدید
- نوار بالایی با اطلاعات تماس و لینک‌های کاربردی
- لوگو لوکس با مارک دایره‌ای طلایی
- جستجوی بزرگ با استایل دارک
- آیکون‌های تعاملی (شناسنامه، شیلد، مقایسه، علاقه‌مندی، سبد خرید) با بَج
- منوی پایین با لینک‌های اصلی + دکمه CTA «فروشنده شوید»
- دکمه منوی موبایل

### `partials/mobile_menu_lux.blade.php` — منوی موبایل دارک
- طراحی کاملاً هماهنگ با تم تیره
- انیمیشن slide از راست با overlay

### `inc/footer.blade.php` — فوتر جدید
- چهار ستون: برند، دسترسی سریع، خدمات مشتریان، تماس + خبرنامه
- شبکه‌های اجتماعی با دکمه‌های دایره‌ای طلایی
- بخش پایینی با کپی‌رایت و روش‌های پرداخت (SVG inline)

### `jewelry/certificate_verify.blade.php` — صفحه استعلام شناسنامه
- Hero مخصوص صفحه
- فرم جستجو با ورودی استایل طلایی
- نمایش نتیجه در قالب «گواهی کاغذی کهنه» با مهر، QR و رنگ‌های کرم-طلایی
- نمایش هشدار در صورت سرقتی بودن قطعه
- بخش تاریخچه انتقال مالکیت به‌صورت تایم‌لاین
- چهار ویژگی سرویس در پایین صفحه

### `jewelry/report_stolen.blade.php` — اعلام سرقت/مفقودی
- Hero قرمز-طلایی برای جلب توجه
- فرم ثبت گزارش با رادیو کارتی انتخاب نوع (سرقت/مفقودی)
- لیست دراپ‌داون قطعات کاربر (فقط قطعاتی که مالک آن‌هاست)
- پنل راهنمای مراحل رهگیری (۴ مرحله)
- بخش آماد سامانه (تعداد قطعات/بازیابی/گزارش‌های فعال)
- باکس تماس اضطراری (پلیس ۱۱۰ + پشتیبانی)
- کنترل guest: در صورت عدم ورود، پیام راهنما + دکمه ورود

### `jewelry/track.blade.php` — رهگیری عمومی
- فرم ورود سریال
- نمایش اطلاعات قطعه + وضعیت به صورت کارت
- تایم‌لاین انتقالات مالکیت با استپ‌های done/active

### `jewelry/service.blade.php` — صفحه معرفی سرویس

### `index.blade.php` — بهبود صفحه اصلی
- جایگزینی اسلایدر قدیمی با هیروی جدید لوکس (champagne gold)
- افزودن بخش خدمات (۶ کارت)
- افزودن بخش دسته‌بندی‌ها با کارت‌های جدید و اموجی
- افزودن بخش اعتماد/شناسنامه با موکاپ گواهی متحرک
- افزودن CTA استعلام شناسنامه
- افزودن سوالات متداول (FAQ آکاردئونی)
- حفظ بخش‌های اصلی قبلی (تخفیف امروز، دسته‌بندی‌ها، محصولات ویژه/پرفروش/جدید، بنرها)

### `admin/jewelry/certificates/public-verify.blade.php` — بهبود ظاهر استعلام از لینک سریال
- تم لوکس به جای طرح Bootstrap خام
- نمایش وضعیت با رنگ‌بندی مشخص

---

## 📁 فایل‌های تغییر یافته

| فایل | وضعیت |
|---|---|
| `public/frontend/css/drikana-luxury.css` | ✅ جدید |
| `resources/views/frontend/layouts/app.blade.php` | ✏️ ویرایش (لود فونت و CSS + back-to-top) |
| `resources/views/frontend/inc/nav.blade.php` | ♻️ بازنویسی کامل |
| `resources/views/frontend/partials/mobile_menu_lux.blade.php` | 🆕 جدید |
| `resources/views/frontend/inc/footer.blade.php` | ♻️ بازنویسی کامل |
| `resources/views/frontend/index.blade.php` | ✏️ افزودن بخش‌های جدید |
| `resources/views/frontend/jewelry/*.blade.php` | 🆕 ۴ صفحه جدید |
| `resources/views/admin/jewelry/certificates/public-verify.blade.php` | ✏️ بهبود ظاهر |
| `app/Http/Controllers/FrontendJewelryController.php` | 🆕 کنترلر جدید |
| `app/Http/Controllers/Jewelry/JewelryCertificateController.php` | ✏️ افزودن verifyPage |
| `routes/web.php` | ✏️ افزودن روت‌های عمومی |
| `preview.html` | 🆕 پیش‌نمایش استاتیک |

---

## 🚀 راه‌اندازی

بعد از کشیدن پروژه، مراحل زیر را اجرا کنید:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

پیش‌نمایش استاتیک طراحی در آدرس `/preview.html` موجود است (در سرور فعال روی پورت ۸۰۸۰).

---

## 💡 پیشنهادات برای گام‌های بعدی

1. **پنل ادمین جواهرات** نیز با تم طلایی-سرمه‌ای هماهنگ شود.
2. **صفحه لیست محصولات** (listing) و **جزئیات محصول** بهبود ظاهری بگیرند.
3. **سیستم امتیازدهی و نظرات** برای قطعات طلا طراحی شود.
4. **چت آنلاین کارشناسان** برای مشاوره خرید اضافه شود.
5. **API موبایل** برای اپلیکیشن Flutter (که در پوشه addons موجود است) تکمیل گردد.
6. **بلاگ تخصصی طلا و جواهر** (نکات نگهداری، راهنمای خرید، شناخت الماس و...) راه‌اندازی شود.
7. **سیستم اعلان (notification)** در زمان ثبت گزارش سرقت برای ادمین و همکاران تعریف شود.


---

## 🆕 ارتقای مرحله دوم (مهر ۱۴۰۴)

### 🎨 لوگو و هویت بصری
- **لوگوی اختصاصی SVG** دریکانا در `public/frontend/images/logo/drikana-logo.svg` با گرادیان طلایی و مارک الماس
- **فاوایکون SVG** جدید هماهنگ با برند
- **آیکون‌های SVG اختصاصی** برای دسته‌بندی‌ها: حلقه، گردنبند، ساعت، الماس، تاج، سکه، هدیه، عتیقه
- **پترن پس‌زمینه Hero** به صورت SVG (`hero-bg.svg`)

### 📂 مگامنو (منوی بزرگ دسته‌بندی‌ها)
- **فایل جدید**: `public/frontend/css/megamenu-lux.css` با طراحی کاملاً جدید
- پشتیبانی از **تصویر/آیکون دسته‌بندی‌ها** (با تطبیق هوشمند روی نام دسته)
- زیرمنوی پانل‌مانند با نمایش **زیردسته‌ها + زیرزیردسته‌ها + بنر تبلیغاتی**
- **ریسپانسیو کامل موبایل**: اکاردئونی با انیمیشن نرم
- دکمه «همه دسته‌بندی‌ها» در پایین
- بستن خودکار با کلیک بیرون

### 📱 بهبود موبایل
- دکمه جستجوی موبایل با نمایش/مخفی کردن باکس جستجو
- بهبودهای ریسپانسیو: هدر، هیرو، کارت‌ها، گریدها، فوتر
- تنظیمات فاصله و فونت در صفحات کوچک
- بهبود تاچ و اندازه دکمه‌ها

### 🔐 صفحات ورود/ثبت‌نام
- هر دو صفحه از قبل **تم لوکس طلایی-تیره** با Glassmorphism دارا بودند
- انیمیشن‌های چشمک‌زن، دکمه‌های شیمر، سایدبار معرفی
- قفل پیش‌فرض روی همان قالب باقی ماند

### 🖥️ یکپارچه‌سازی پنل ادمین جواهرات
- تم طلایی-سرمه‌ای برای پنل جواهرسازی (هماهنگ با سایت اصلی)
- اضافه شدن **لینک پنل اصلی فروشگاه** و **مشاهده سایت** در سایدبار
- بَج (نشان) تعداد گزارش‌های سرقت/تعمیرات در منو
- بهبود کنتراست و خوانایی
- ایمپورت فونت Vazirmatn در پنل

### 🗃️ داده‌های نمونه (Seeder)
- **فایل جدید**: `database/seeds/DrikanaSampleDataSeeder.php`
- ۸ دسته‌بندی اصلی طلا و جواهر با آیکون SVG
- ۸ برند لوکس (Rolex, Cartier, Tiffany, Bvlgari, ...)
- ۴ مالک نمونه + ۵ شناسنامه طلا با جزئیات کامل (انگشتر الماس، گردنبند مروارید، رولکس، دستبند زمرد، نیم‌ست یاقوت)
- **نحوه اجرا**:
  ```bash
  php artisan db:seed --class=DrikanaSampleDataSeeder
  ```

### 📁 فایل‌های جدید این مرحله
| فایل | وضعیت |
|---|---|
| `public/frontend/images/logo/drikana-logo.svg` | 🆕 لوگو |
| `public/frontend/images/logo/favicon.svg` | 🆕 فاوایکون |
| `public/frontend/images/drikana/ring.svg` | 🆕 |
| `public/frontend/images/drikana/necklace.svg` | 🆕 |
| `public/frontend/images/drikana/watch.svg` | 🆕 |
| `public/frontend/images/drikana/diamond.svg` | 🆕 |
| `public/frontend/images/drikana/crown.svg` | 🆕 |
| `public/frontend/images/drikana/coin.svg` | 🆕 |
| `public/frontend/images/drikana/gift.svg` | 🆕 |
| `public/frontend/images/drikana/antique.svg` | 🆕 |
| `public/frontend/images/drikana/hero-bg.svg` | 🆕 |
| `public/frontend/css/megamenu-lux.css` | 🆕 |
| `database/seeds/DrikanaSampleDataSeeder.php` | 🆕 |
| `resources/views/frontend/partials/mobile_menu_lux.blade.php` | 🆕 |
