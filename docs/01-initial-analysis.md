# گزارش تحلیل اولیه پروژه دریکانا (Drikana)

**تاریخ تحلیل:** ۱۴۰۵/۰۷/۲۰ (2026-10-12)  
**مخزن:** `https://github.com/mrtz8bgy/laravel_drikana.git`  
**شاخه اصلی:** `main` — آخرین کامیت: `bcacdc4e` (last-update-slider)  
**شاخه کاری جدید:** `develop/refactor-rbac`  
**نسخه پشتیبان:** تگ `backup/pre-refactor-2026-10-12` + شاخه `backup/pre-refactor`

---

## ۱. وضعیت مخزن و نسخه‌گذاری

| مورد | مقدار |
|---|---|
| تعداد کامیت | ۵۱ |
| تعداد آبجکت در تاریخچه | ۱۷,۵۳۶ |
| حجم مخزن | ۴۰۰ MB |
| تعداد فایل | ۲,۳۶۲ |
| وضعیت Working Tree | Clean |
| شاخه‌ها | `main` فقط (+ شاخه‌های پشتیبان جدید) |

### ۱-۱. یافته بحرانی: افشای فایل `.env` در تاریخچه Git

فایل `.env` **در ۷ کامیت** در مخزن ثبت شده است:

```
f261b2ef update-env-licence
35bde1f9 Update .env
e73d9297 update-project
903f03f2 update_suport-controller
ea672555 update-env
0d3f5b44 Update .env
6ba26d81 Initial commit
```

همچنین `.env.gobox` نیز در مخزن ردیابی می‌شود.

**نتیجه:** تمام مقادیر حساس زیر در تاریخچه Git (و هر کلونی از آن) افشا شده‌اند و **باید تعویض شوند**:
- `APP_KEY` (سرقت آن → جعل نشست، رمزگشایی کوکی)
- `DB_USERNAME` / `DB_PASSWORD`
- `MELIPAYAMAK_PASSWORD`, `FARAZSMS_PASSWORD`, `MELIPAYAMAK_USERNAME`, `FARAZSMS_USERNAME`
- `SSLCZ_STORE_PASSWD`, `IM_AUTH_TOKEN`, `IM_API_KEY`
- `RAZOR_SECRET`, `PAYSTACK_SECRET_KEY`
- `ZARINPAL_MERCHANT_CODE`
- `GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_SECRET`
- `MAIL_PASSWORD`, `MAILGUN_SECRET`
- `PUSHER_APP_SECRET`
- `PAYTM_MERCHANT_KEY`

> **هشدار:** حذف فایل از آخرین کامیت کافی نیست. تا زمانی که تاریخچه بازنویسی (force-push + rotate) نشود، این داده‌ها قابل استخراج هستند. **اقدام فوری: تعویض تمام کلیدها و رمزها.**

`public/.htaccess` تلاش کرده `.env` را مسدود کند، اما این حفاظت در سطح وب‌سرور است و افشای تاریخچه Git را جبران نمی‌کند. `.gitignore` فعلی شامل `/.env` است، پس فایل از این پس ردیابی نمی‌شود، اما سابقه باقی است.

---

## ۲. شناسایی فناوری و نسخه‌ها

| مورد | نسخه | ملاحظه |
|---|---|---|
| Framework | **Laravel 5.8** | ⚠️ EOL — پشتیبانی امنیتی از ۲۰۱۹ قطع شده |
| PHP موردنیاز | `^7.1.3` | ⚠️ PHP 7.1 نیز EOL است |
| Composer | `minimum-stability: dev` | ⚠️ خطر نصب نسخه‌های ناپایدار |
| Database | MySQL/MariaDB | Schema از dump استخراج شد |
| Frontend | Bootstrap 4 + jQuery + Laravel Mix 2 (Webpack) | `public/css`, `public/js` |
| Assets | Vite **ندارد** — از Mix استفاده می‌کند | مطابق ساختار موجود پیش می‌رویم |
| Language | فارسی (RTL) + چندزبانه | `resources/lang`, جدول `languages` |

### ۲-۱. وابستگی‌های پرخطر

```json
"mehedi-iitdu/core-component-repository": "dev-master"
"mehedi-iitdu/laravel-paystack": "dev-master"
"netshell/paypal": "dev-master"
```

سه پکیج روی `dev-master` پین شده‌اند → **عدم تکرارپذیری بیلد + خطر supply-chain**.  
همچنین ده‌ها SDK درگاه پرداخت خارجی (PayTM, Razorpay, Stripe, Instamojo, Paystack, SSLCommerz, VoguePay, PayPal) برای یک پلتفرم ایرانی نصب شده که عمدتاً **بلااستفاده** هستند — کاهش سطح حمله با حذف محتاطانه.

---

## ۳. ساختار پروژه

```
app/               Http/Controllers (116 فایل), Models (50), Middleware (14), Policies (1), Helpers
routes/            web.php (29KB), admin.php (15KB), api.php, + 12 فایل روت افزودنی
resources/views/   294 فایل Blade — admin/, frontend/, auth/, layouts/, jewelry/
public/            css/, js/, plugins/, uploads/, frontend/, uploads
database/          migrations (19), seeds (4), drikana-db.sql (276K), drikana-db222.sql (252K)
sqlupdates/        15 فایل SQL نسخه‌ای (v15 → v30)
addons/            5 افزودنی (affiliate_system, auction, club_point, offline_payment, seller_subscription)
```

### ۳-۱. قابلیت‌های موجود شناسایی‌شده

| قابلیت | وضعیت | محل |
|---|---|---|
| مدیریت محصولات (e-commerce) | ✅ کامل | `ProductController`, `products`, `product_stocks` |
| مارکت‌پلیس چندفروشندگی | ✅ | `SellerController`, `sellers`, `shops` |
| سفارش و پرداخت | ✅ (چند درگاه) | `OrderController`, `PaymentController`, درگاه‌ها |
| مدیریت دسته‌بندی (۳ سطحی) | ✅ | `categories`, `sub_categories`, `sub_sub_categories` |
| تخفیف/فلش‌دی�%al | ✅ | `FlashDealController` |
| کوپن، کیف پول، امتیاز باشگاه | ✅ | `coupons`, `wallets`, `club_points` |
| تیکت پشتیبانی و گفتگو | ✅ | `SupportTicketController`, `ConversationController` |
| OTP / تأیید موبایل | ✅ | `OTPController`, `OTPVerificationController` |
| **گواهی اصالت طلا** | ⚠️ ناقص | `JewelryCertificate`, `jewelry_certificates` |
| **مالکیت اشیا** | ⚠️ ناقص | `JewelryOwner`, `JewelryTransfer` |
| **تعمیرات** | ⚠️ ناقص | `RepairOrder`, `repair_orders`, بدون مجوزدهی |
| **گزارش سرقت/مفقودی** | ⚠️ ناقص | `MissingReportController` |
| **گزارش اخبار/مقالات** | ❌ وجود ندارد | باید از صفر ساخته شود |
| **RBAC واقعی** | ❌ شبه‌سیستم | جدول `roles` بدون هیچ کاربردی |
| **Audit Log** | ❌ وجود ندارد | باید ساخته شود |

---

## ۴. یافته‌های امنیتی — طبقه‌بندی‌شده

### 🔴 CRITICAL

#### C-01 — حذف کل دیتابیس با یک درخواست GET بدون احراز هویت

```php
// routes/web.php:22-23
Route::get('/demo/cron_1', 'DemoController@cron_1');
Route::get('/demo/cron_2', 'DemoController@cron_2');
```

```php
// app/Http/Controllers/DemoController.php
public function __construct() {
    if (env('DEMO_MODE') != 'On') {
        return false;   // ← این return فقط سازنده را ترک می‌کند!
    }                   //   هیچ‌وقت جلوی اجرای اکشن را نمی‌گیرد
}

public function cron_1() {
    $this->drop_all_tables();     // Schema::drop برای همه جداول
    $this->import_demo_sql();     // base_path('demo.sql') → فایل وجود ندارد
}
```

**تأثیر:** `GET /demo/cron_1` بدون لاگین → `Schema::disableForeignKeyConstraints()` و دراپ **تمام جداول**، سپس خطا در خواندن `demo.sql`.  
**بحران:** دراپ **قبل از** خطا اجرا می‌شود → **نابودی کامل داده‌ها**.  
**تست شد؟** تحلیل ایستا — بله. اجرای واقعی — خیر (محیط دیتابیس در دسترس نیست).

#### C-02 — افشای `.env` در تاریخچه Git (بخش ۱-۱)

### 🟠 HIGH

#### H-01 — کنترل دسترسی شکسته (BAC) در ماژول تعمیرات

مسیرهای مدیریتی جواهرات به‌صورت تکراری و **بدون میدل‌ور `admin`** تعریف شده‌اند:

```php
// routes/web.php:412 — فقط auth، بدون admin!
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    Route::prefix('jewelry')->name('jewelry.')->group(function() {
        Route::resource('repair-orders', 'Jewelry\Admin\RepairOrderController');
        // ...
    });
});
```

و `Jewelry\Admin\RepairOrderController` **هیچ بررسی مجوزی** ندارد (بدون `authorize`، بدون `Gate`، بدون `middleware` در سازنده).  
**تأثیر:** هر کاربر لاگین‌شده (از جمله یک مشتری عادی) می‌تواند تمام سفارش‌های تعمیر همه کاربران را ببیند، تغییر وضعیت دهد، هزینه تعیین کند و **حذف** کند.

#### H-02 — عدم اعمال مالکیت در تغییر مالکیت جواهرات

`JewelryTransferController::store()`:
```php
$request->validate([
    'certificate_id' => 'required|exists:jewelry_certificates,id',
    'to_owner_id'    => 'required|exists:jewelry_owners,id',
]);
$this->authorize('view', $certificate);   // ← فقط «مشاهده» را چک می‌کند
$toOwner = JewelryOwner::findOrFail($validated['to_owner_id']);
if ($toOwner->user_id !== Auth::id()) { /* ... */ }
```

مشکلات:
1. فقط مجوز `view` بررسی می‌شود، نه مجوز «ایجاد انتقال».
2. **بدون Database Transaction** — اگر `$certificate->update()` شکست بخورد، رکورد `jewelry_transfers` یتیم می‌ماند و مالکیت نیمه‌کاره تغییر کرده.
3. **بدون قفل‌گذاری (lockForUpdate)** — امکان **دو انتقال هم‌زمان** روی یک قطعه (race condition).
4. **بدون گردش تأیید** (Pending/Approved/Rejected/Cancelled) — انتقال **بی‌درنگ نهایی می‌شود** بدون تأیید.
5. **بدون Audit Log**.
6. `JewelryTransfer` model: فیلدهای `price` و `contract_file` در `$fillable` **نیستند** → به‌صورت خاموش دور ریخته می‌شوند (Mass Assignment silent drop). همچنین ستون‌های `price`/`contract_file` در جدول DB **وجود ندارند**.

### 🟡 MEDIUM

#### M-01 — میدل‌ورهای امنیتی غیرفعال‌شده

```php
// app/Http/Kernel.php
'web' => [
    //\App\Http\Middleware\EncryptCookies::class,   ← غیرفعال!
    \Illuminate\Session\Middleware\StartSession::class,
    // \Illuminate\Session\Middleware\AuthenticateSession::class,  ← غیرفعال → Session Fixation
    ...
    // \App\Http\Middleware\HttpsProtocol::class     ← غیرفعال
],
```
- نبود `EncryptCookies` → کوکی نشست **رمزنگاری نمی‌شود**.
- نبود `AuthenticateSession` → تغییر رمز عبور، نشست‌های فعال دیگر را invalidate نمی‌کند (Session Hijacking پایدار).
- نبود `HttpsProtocol` → بدون اجبار HTTPS.

#### M-02 — تنظیمات ناامن نشست

```php
// config/session.php
'encrypt'   => false,     // داده نشست رمزنگاری نمی‌شود
'secure'    => env('SESSION_SECURE_COOKIE', false),   // پیش‌فرض false
'same_site' => null,      // بدون محافظت CSRF سطح کوکی
```

#### M-03 — عدم تطابق Schema مدل با دیتابیس

`App\Models\JewelryCertificate::$fillable`:
```php
'is_active',    // ← این ستون در jewelry_certificates وجود ندارد
'created_by',   // ← وجود ندارد
```
ستون‌های واقعی جدول: `is_verified`, `rejected_at`.  
**تأثیر:** هر `JewelryCertificate::create()` با این فیلدها → خطای `Unknown column`. همچنین `->update(['is_active' => ...])` شکست می‌خورد. ستون‌های `product_name`, `metal_type`, `serial_number` در DB **NOT NULL** هستند اما در migration ای برای این جدول وجود ندارد.

#### M-04 — شکاف Migration

جدول‌های `jewelry_certificates` و `jewelry_transfers` **هیچ Migration ندارند** — فقط در `database/drikana-db.sql` وجود دارند.  
همچنین `repair_orders` migration از `jewelry_id` استفاده می‌کند اما دیتابیس واقعی ستون `jewelry_certificate_id` دارد:

```php
// migration: 2026_02_16_165329_create_repair_orders_table.php
$table->foreign('jewelry_id')->references('id')->on('jewelry_certificates');
// DB واقعی: jewelry_certificate_id
```
**تأثیر:** `php artisan migrate` روی دیتابیس تازه → شکست یا ناسازگاری.

#### M-05 — خطای نحوی در تعریف روت (Escape دوگانه)

```php
// routes/web.php:468
Route::get('/jewelry/certificate-verify', 'Jewelry\\\\JewelryCertificateController@verifyPage')
```
در رشته تک‌کوتیشن، `\\\\` → `\\` (دو بک‌اسلش واقعی). مقدار نهایی اکشن: `Jewelry\\JewelryCertificateController` که **قابل resolve نیست** → خطای زمان اجرا در این صفحه عمومی.

#### M-06 — APP_DEBUG فعال در محیط Production

هر دو فایل `.env` و `.env.gobox` مقدار `APP_DEBUG=true` دارند، در حالی که `.env.gobox` مقدار `APP_ENV=production` دارد.  
**تأثیر:** نمایش Stack Trace، مسیر فایل‌ها، کوئری‌ها و متغیرهای محیطی به کاربر.

#### M-07 — روت‌های تکراری و سایه‌افتاده

| URI | تعداد تعریف | مشکل |
|---|---|---|
| `admin/jewelry/repair-orders` | ۲ | تعریف دوم برنده است (بدون `admin` middleware) |
| `admin/jewelry/reports/certificates` | ۲ | سایه‌افتادگی |
| `/dashboard` | ۲ | — |
| `/products` | ۲ | — |
| `/checkout/payment_select` | ۲ | — |
| `support_ticket/reply` | ۲ | — |
| `/jewelry/report-stolen` | ۲ (GET+POST) | صحیح |

#### M-08 — Redirection باز (Open Redirect)

`IsUser` middleware: `session(['link' => url()->current()])` و LoginController احتمالاً آن را بدون اعتبارسنجی host redirect می‌کند. نیاز به بررسی و اعتبارسنجی مسیر داخلی.

### 🔵 LOW / INFO

- **L-01** — `InstallController` و `UpdateController` در دسترس باقی مانده‌اند (`/install/*`). سازنده `step0` فایل `.env` را می‌نویسد → خطر بازنویسی تنظیمات.
- **L-02** — رمز عبور پیش‌فرض ادمین در Seeder: `admin@drikana.com` / `123456`.
- **L-03** — کوئری‌های خام: ۲۶ مورد `DB::raw`/`whereRaw`/`selectRaw` — عمدتاً در `Api/OrderController`, `Api/CartController`, `Jewelry/ReportController`. ورودی کاربر مستقیم در SQL دیده نشد، اما نیاز به بازبینی موردی.
- **L-04** — ۳۸۶ ویژگی `style="..."` درون‌خطی + ۳۳ بلوک `<style>` (بخش ۵).
- **L-05** — `composer.json`: `minimum-stability: dev`.
- **L-06** — نبود Security Headers (CSP, X-Frame-Options, HSTS, X-Content-Type-Options).
- **L-07** — `VerifyCsrfToken::$except` شامل `/config_content` (نیاز به بررسی).
- **L-08** — ۱۲ فایل روت افزودنی با بارگذاری شرطی ولی بدون تست — پیچیدگی بالا در `RouteServiceProvider`.

---

## ۵. وضعیت CSS — مبنای مرحله ۳

### توزیع فعلی

| مورد | مقدار |
|---|---|
| فایل‌های Blade دارای بلوک `<style>` | ۳۳ |
| حجم کل CSS درون‌خطی | ~۱۹۰ KB |
| ویژگی‌های `style="..."` | ۳۸۶ |
| فایل‌های CSS مستقل | `public/css/` (theme-based, active-shop) |

### بزرگ‌ترین بلوک‌های CSS درون‌خطی (اولویت جداسازی)

| حجم (بایت) | فایل | مقصد پیشنهادی |
|---|---|---|
| ۳۶,۳۵۸ | `frontend/index.blade.php` | `store.css` |
| ۲۶,۹۹۱ | `admin/jewelry/layouts/app.blade.php` | `jewelry-admin.css` (Bootstrap 5 RTL کامل) |
| ۲۲,۸۵۳ | `layouts/login.blade.php` | `auth.css` |
| ۲۲,۴۴۳ | `auth/register.blade.php` | `auth.css` |
| ۱۱,۸۲۸ | `admin/dashboard.blade.php` | `admin.css` |
| ۸,۸۵۳ | `frontend/all_category.blade.php` | `store.css` |
| ۷,۴۵۴ | `admin/jewelry/certificates/print.blade.php` | `print.css` (باید درون‌خطی بماند — چاپ) |
| ۶,۵۸۶ | `frontend/jewelry/certificate_verify.blade.php` | `ownership.css` |
| ۴,۹۹۰ | `frontend/jewelry/report_stolen.blade.php` | `ownership.css` |
| ۳,۵۷۰ | `admin/jewelry/certificates/index.blade.php` | `jewelry-admin.css` |

> **نکته مهم:** بلوک CSS در `admin/jewelry/layouts/app.blade.php` یک **کپی کامل Bootstrap 5 RTL** است (~۲۷KB) که همزمان با **Bootstrap 4** در لایه ادمین اصلی بارگذاری می‌شود. این دو با هم تعارض دارند. جداسازی باید با احتیاط انجام شود تا ظاهر صفحات ادمین اصلی حفظ شود.

ساختار CSS پیشنهادی (مطابق درخواست):
```
public/css/
├── app.css          (عمومی — موجود، گسترش می‌یابد)
├── admin.css        (پنل ادمین)
├── store.css        (فروشگاه/فرانت)
├── repair.css       (تعمیرات)
├── ownership.css    (مالکیت و گواهی)
├── articles.css     (مقالات — جدید)
├── auth.css         (ورود/ثبت‌نام)
└── jewelry-admin.css (پوسته اختصاصی ماژول جواهرات)
```

---

## ۶. مشکلات معماری و کیفیت کد

1. **دو سیستم نقش موازی و ناسازگار** — مدل `User` هم `user_type` (admin/seller/staff/customer) دارد و هم `role` (admin/technician/customer). میدل‌ورها فقط `user_type` را چک می‌کنند؛ کنترلرهای جواهرات فقط `role` را. **هیچ‌کدام مرجع واحد نیست.** جدول `roles` (با ستون JSON `permissions`) و جدول `staff` (با `role_id`) وجود دارند اما **کاملاً بلااستفاده‌اند**.
2. **مسئولیت‌های درهم** — `ProductController` بسیار حجیم (نیاز به بررسی و شکست به سرویس).
3. **کنترلرهای موازی/تکراری** — `Jewelry/RepairOrderController` و `Jewelry/Admin/RepairOrderController`؛ `RepairOrderController` سطح ریشه. سه نسخه از یک قابلیت.
4. **بدون لایه Service/Repository** — منطق کسب‌وکار مستقیم در کنترلر.
5. **بدون تست** — پوشه `tests/` فقط اسکلت پیش‌فرض دارد.
6. **بدون Audit Log و بدون Soft Delete در جداول حساس** — تاریخچه حذف‌پذیر است.
7. **مدل‌های دوپاره** — `App\User` و `App\Models\User` (adapter)، `App\Role` و `App\Models\Role`، `App\Policy` و `App\Models\Policy`. کنترلرها هرکدام به یکی ارجاع می‌دهند.
8. **Policy ناقص** — فقط `JewelryOwnerPolicy` ثبت شده. `authorize('view', $certificate)` روی `JewelryCertificate` **Policy ندارد** → در Laravel 5.8 رفتار پیش‌فرض «رد» یا استثنا. نیاز به بررسی دقیق.
9. **فایل‌های زائد در ریشه** — `route_list.txt` (448KB!)، `preview.html`، `temp_url_check.py`، `temp-mega-mobile-changes.txt`، `solve-utf8-powershell.txt`، `sitemap.xml` خالی.

---

## ۷. جمع‌بندی ریسک

| شدت | تعداد | موارد کلیدی |
|---|---|---|
| 🔴 Critical | ۲ | `C-01` نابودی دیتابیس، `C-02` افشای `.env` |
| 🟠 High | ۲ | `H-01` BAC در تعمیرات، `H-02` انتقال مالکیت غیرامن |
| 🟡 Medium | ۸ | میدل‌ورهای غیرفعال، Schema drift، روت‌های تکراری، Open Redirect |
| 🔵 Low | ۸ | نصب‌کننده باز، رمز پیش‌فرض، نبود headers |

**ارزیابی کلی:** پروژه پایه مناسبی دارد (e-commerce کامل + ماژول جواهرات موجود)، اما:
- ماژول‌های «جواهرات» **نیمه‌کاره و ناامن** هستند و نباید روی داده واقعی اجرا شوند تا اصلاح شوند.
- سیستم دسترسی **عملاً وجود ندارد** — فقط `user_type` سطحی.
- نبود مقالات و نبود Audit Log.

**قابلیت‌های سالم که حفظ می‌شوند (طبق قانون اصلی):** کل e-commerce، مارکت‌پلیس، درگاه‌های پرداخت فعال، OTP، تیکت، گفتگو، کوپن، کیف پول، دسته‌بندی‌ها، اسلایدر/بنر، تنظیمات، SeoSetting، چندزبانه، پوسته ادمین active-shop.

---

## ۸. اقدامات فوری پیشنهادی (قبل از هر توسعه)

| # | اقدام | وابستگی |
|---|---|---|
| 1 | **تعویض تمام Secretهای افشاشده** + تولید `APP_KEY` جدید | خارج از کد — توسط شما |
| 2 | مسدودسازی `DemoController` (حذف روت یا افزودن میدل‌ور مسدودکننده) | فوری |
| 3 | افزودن میدل‌ور `admin` به روت‌های تکراری `admin/jewelry/*` | فوری |
| 4 | فعال‌سازی `EncryptCookies` + `AuthenticateSession` + تنظیم `secure`/`same_site` | فوری |
| 5 | اصلاح `APP_DEBUG=true` در محیط production | فوری |
| 6 | اصلاح خطای `Jewelry\\\\` در روت `certificate-verify` | فوری |
| 7 | پشتیبان‌گیری کامل از دیتابیس واقعی **قبل از** هر Migration/SQL | توسط شما |

---

## ۹. محدودیت‌های محیط بررسی

موارد زیر در این محیط قابل انجام **نبود** و صریحاً اعلام می‌شود:

| مورد | وضعیت | دلیل |
|---|---|---|
| اجرای `composer install` | ❌ | نیاز به شبکه/زمان طولانی؛ `vendor/` در مخزن نیست |
| اجرای `php artisan migrate` | ❌ | PHP و MySQL در محیط سندباکس نصب نیستند |
| اجرای تست‌های PHPUnit | ❌ | همان دلیل |
| `php -l` روی فایل‌ها | ❌ | PHP نصب نیست |
| بررسی Runtime پروژه | ❌ | همان دلیل |
| اسکن خودکار آسیب‌پذیری | ❌ | ابزار (composer audit, npm audit) نیاز به نصب وابستگی‌ها دارد |
| **تحلیل ایستا و بازبینی کد** | ✅ | **انجام شد** |
| **استخراج Schema از dump** | ✅ | **انجام شد** |

**اقدام:** در گام بعد تلاش می‌کنم PHP و MariaDB را در سندباکس نصب کنم تا تست‌های واقعی (مرحله ۹) قابل اجرا شوند. اگر نصب ممکن نشد، این موضوع در گزارش تست صریحاً ثبت می‌شود و هیچ تستی «موفق» اعلام نخواهد شد.

---

## ۱۰. نقشه راه اصلاح (مراحل بعدی)

| مرحله | عنوان | وضعیت |
|---|---|---|
| A | دریافت و تحلیل ساختار | ✅ **تکمیل** |
| B | نسخه پشتیبان و شناسایی ریسک | ✅ **تکمیل** (تگ + شاخه پشتیبان) |
| C | اصلاح خطاها و ریفکتور | ⏳ در انتظار تأیید تصمیمات معماری |
| D | جداسازی CSS | ⏳ |
| E | سیستم نقش‌ها و مجوزها (RBAC) | ⏳ |
| F | ماژول مالکیت و تاریخچه انتقال | ⏳ |
| G | مقالات و مدیریت تصاویر | ⏳ |
| H | بررسی و اصلاح امنیت | ⏳ |
| I | دیتابیس و SQL | ⏳ |
| J | تست و مستندات | ⏳ |

---

## ۱۱. به‌روزرسانی وضعیت — پایان کار

> این بخش در **مرحله ۱۰** به سند افزوده شد. بخش‌های ۱ تا ۱۰ سند، تصویر
> لحظه‌ای «وضعیت ورودی» را نگه می‌دارند و **بازنویسی نشده‌اند**؛ چون
> ارزش این سند در ثبت نقطه شروع است. آنچه در ادامه می‌آید، تفاوت
> «آن‌روز» با «امروز» است.

### ۱۱-۱. محدودیت‌های محیط بررسی — برطرف شد

جدول بخش ۹ (نبود PHP، MySQL، Composer و ناتوانی در اجرای آزمون) دیگر
معتبر نیست. در جریان کار:

| مورد | وضعیت تازه |
|------|-----------|
| PHP ۷.۴.۳۳ | ✅ نصب شد (مخزن `packages.sury.org`) |
| Composer ۲.x | ✅ نصب شد |
| MariaDB | ✅ نصب و راه‌اندازی شد |
| `composer install` | ✅ انجام شد |
| `php artisan migrate` | ✅ ۱۹ مهاجرت روی دیتابیس آزمایشی اجرا شد |
| `php -l` روی همه فایل‌های تغییریافته | ✅ |
| PHPUnit | ✅ **۱۸۵ آزمون / ۵۸۸ assertion / ۱ رد‌شده — سبز** |
| `composer audit` | ✅ ۷۰ هشدار در ۱۸ بسته شناسایی شد |

**نتیجه:** هیچ آزمونی در این پروژه «اجرا‌نشده اما موفق» علامت نخورده
است. آنچه آزمون خودکار ندارد، در `docs/testing-report.md` فهرست شده
است.

### ۱۱-۲. یافته‌های امنیتی — از ۲۰ به ۳۶ رسید

جدول ریسک بخش ۷، بر پایه بازبینی *ایستا* بود. با اجرای آزمون پویا و
بازبینی عمیق‌تر، سه مورد **بحرانی تازه** پیدا شد:

| شناسه | مورد | چرا در تحلیل نخست دیده نشد |
|-------|------|----------------------------|
| `C-03` | افشای اعتبارنامه ورود مدیر در منبع صفحه ورود | در بخش JS یک فایل ویو پنهان بود |
| `C-04` | نوشتن فایل روی مسیر دلخواه، بدون احراز هویت | نام توابع و رشته‌ها عامدانه مبهم‌سازی شده بود |
| `C-05` | ارتباط پنهان: ارسال نشانی صفحه‌ها به یک نشانی بیرونی | برای دیدنش باید رشته‌های مبهم‌شده را بازگشایی می‌کردی |

**درس عملی:** بازبینی ایستای نخست، «کد مبهم‌سازی‌شده» را جدی نگرفت.
دو مورد از سه مورد بالا فقط با *بازگشایی الگوی مبهم‌سازی* پیدا شد —
کاربردی‌ترین سرنخ در این پروژه، خودِ وجود کد مبهم بود، نه محتوایش.

### ۱۱-۳. وضعیت نهایی یافته‌ها (۳۶ یافته — ۳۴ بسته، ۲ باز)

| شدت | شناسایی‌شده | اصلاح‌شده | باز |
|-----|------------|-----------|-----|
| 🔴 بحرانی | ۵ | ۴ | ۱ |
| 🟠 شدید | ۵ | ۵ | ۰ |
| 🟡 متوسط | ۱۲ | ۱۲ | ۰ |
| 🔵 کم | ۱۴ | ۱۳ | ۱ |
| **جمع** | **۳۶** | **۳۴** | **۲** |

فهرست کامل با محل، شدت، علت، اصلاح و نتیجه آزمون:
`docs/security-audit.md`.

### ۱۱-۴. نقشه راه A تا J — همه تکمیل شد

| مرحله | عنوان | وضعیت | شاهد |
|-------|-------|-------|------|
| A | دریافت و تحلیل ساختار | ✅ | همین سند |
| B | نسخه پشتیبان و شناسایی ریسک | ✅ | شاخه `backup/pre-refactor` + تگ |
| C | اصلاح خطاها و ریفکتور | ✅ | حذف روت‌های نابودکننده، رفع ۴۱ نام روت تکراری، `RouteIntegrityTest` |
| D | جداسازی CSS | ✅ | ۱۴ فایل در `public/css/drikana/` + `CssSeparationTest` |
| E | سیستم نقش‌ها و مجوزها (RBAC) | ✅ | ۸۶ مجوز، ۶ نقش، `docs/roles-and-permissions.md` |
| F | ماژول مالکیت و تاریخچه انتقال | ✅ | دفتر کل فقط‌افزودنی + گردش تأیید، `docs/ownership-module.md` |
| G | مقالات و مدیریت تصاویر | ✅ | ۴ جدول، سامانه تصویر امن، `docs/articles-module.md` |
| H | بررسی و اصلاح امنیت | ✅ | ۳۶ یافته، `docs/security-audit.md` |
| I | دیتابیس و SQL | ✅ | `database/sql/platform_upgrade.sql` + ۸ مهاجرت، آزموده روی کپی |
| J | تست و مستندات | ✅ | ۱۸۵ آزمون سبز، `docs/testing-report.md` |

### ۱۱-۵. قابلیت‌هایی که دست‌نخورده ماندند

طبق قانون اصلی پروژه («قابلیت سالم را حذف نکن»)، این بخش‌ها **بدون
حذف قابلیت** حفظ شدند: فروشگاه و سبد خرید، مارکت‌پلیس و فروشندگان،
درگاه‌های پرداخت، ورود با OTP، تیکت و گفتگو، کوپن و کیف پول،
دسته‌بندی‌ها، اسلایدر و بنر، تنظیمات، چندزبانه، پوسته مدیریت.

تنها چهار فایل حذف شد که هیچ‌کدام «قابلیت» نبودند:

| فایل | دلیل |
|------|------|
| `.env` | افشای اعتبارنامه در Git |
| `.env.gobox` | همان |
| `DemoController.php` | روت عمومی که کل دیتابیس را دراپ می‌کرد |
| `JewelryTransferController.php` | انتقال مالکیت بدون تراکنش/قفل/تأیید — با نسخه کامل‌تر جایگزین شد |

به‌علاوه در همین مرحله دو «در پشتی» از کد حذف شد (`C-04` و `C-05`) که
آن‌ها هم قابلیت نبودند، بلکه مسیر نفوذ بودند.
