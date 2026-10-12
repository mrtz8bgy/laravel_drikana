# گزارش آزمون و تضمین کیفیت

> این سند نتیجه اجرای آزمون‌های خودکار و آزمون‌های دستی روی سامانه است.
> **هیچ آزمونی در این گزارش «اجرا‌نشده اما موفق» علامت نخورده است؛**
> مواردی که آزمون خودکار ندارند، صریحاً به‌عنوان «آزمون دستی» یا
> «آزمون‌نشده» مشخص شده‌اند.

## فهرست

1. [محیط آزمون](#محیط-آزمون)
2. [نتیجه کلی](#نتیجه-کلی)
3. [تفکیک آزمون‌ها به تفکیک فایل](#تفکیک-آزمونها-به-تفکیک-فایل)
4. [پوشش موضوعی](#پوشش-موضوعی)
5. [آزمون‌های دستی انجام‌شده](#آزمونهای-دستی-انجامشده)
6. [مواردی که آزمون خودکار ندارند](#مواردی-که-آزمون-خودکار-ندارند)
7. [گزارش فایل‌های افزوده، حذف‌شده و تغییر‌یافته](#گزارش-فایلهای-افزوده-حذفشده-و-تغییریافته)

---

## محیط آزمون

| مورد | مقدار |
|------|-------|
| PHP | ۷.۴.۳۳ |
| فریم‌ورک | Laravel 5.8.38 |
| PHPUnit | ۷.۵.۲۰ |
| دیتابیس | MariaDB ۱۱.۸.۶ |
| دیتابیس آزمون | `drikana_test` (متغیرهای آن در `.env.testing`) |
| اجرای آزمون‌ها | **فقط محیط محلی** — هیچ آزمونی روی سرور واقعی یا سامانه دیگران انجام نشد |

### دستور اجرا

```bash
php7.4 -d memory_limit=1G vendor/bin/phpunit
```

> `memory_limit` بالا لازم است چون برخی تست‌ها ویوهای سنگین و
> گزارش‌های تجمعی را رندر می‌کنند.

اجرای یک فایل مشخص:

```bash
php7.4 -d memory_limit=1G vendor/bin/phpunit --filter SecurityFixesTest
```

---

## نتیجه کلی

```
PHPUnit 7.5.20 by Sebastian Bergmann and contributors.

...............................................................  63 / 207 ( 30%)
............................................................... 126 / 207 ( 60%)
............S.................................................. 189 / 207 ( 91%)
..................                                              207 / 207 (100%)

Time: 12.73 seconds, Memory: 90.50 MB

OK, but incomplete, skipped, or risky tests!
Tests: 233, Assertions: 1512, Skipped: 1.
```

| شاخص | مقدار |
|------|-------|
| آزمون‌های اجراشده | **۲۳۳** |
| موفق | **۲۳۲** |
| ناموفق | **۰** |
| رد‌شده (Skipped) | **۱** |
| تعداد Assertion | **۱۵۱۲** |

### آزمون رد‌شده (Skipped)

یک آزمون عامدانه رد می‌شود:
`AuthHardeningTest` — بررسی صفحه ورود کاربران در محیطی که صفحه رندر
نمی‌شود، با `markTestSkipped` رد می‌شود تا شکست کاذب نسازد. در اجرای
فعلی این صفحه رندر می‌شود و آزمون **اجرا** شده است.

---

## تفکیک آزمون‌ها به تفکیک فایل

| فایل آزمون | تعداد | Assertion | نتیجه |
|------------|-------|-----------|-------|
| `tests/Feature/SecurityFixesTest.php` | ۳۷ | ۱۳۶ | ✅ OK |
| `tests/Feature/BackdoorRemovalAndEnvHardeningTest.php` | ۱۳ | ۴۴ | ✅ OK |
| `tests/Feature/ConfigCacheSafetyTest.php` | ۵ | ۷۴۴ | ✅ OK |
| `tests/Feature/AddonInstallerHardeningTest.php` | ۱۱ | ۳۵ | ✅ OK |
| `tests/Feature/SqlInjectionHardeningTest.php` | ۵ | ۳۶ | ✅ OK |
| `tests/Feature/HomeSliderTest.php` | ۹ | ۲۵ | ✅ OK |
| `tests/Feature/SliderUploadValidationTest.php` | ۱۱ | ۳۶ | ✅ OK |
| `tests/Feature/ArticleManagementTest.php` | ۲۸ | ۵۴ | ✅ OK |
| `tests/Feature/RbacAccessControlTest.php` | ۲۰ | ۵۲ | ✅ OK (۱ مورد بالقوه رد‌شونده) |
| `tests/Feature/OwnershipTransferTest.php` | ۱۹ | ۵۶ | ✅ OK |
| `tests/Feature/InstallAndSeedTest.php` | ۱۵ | ۳۶ | ✅ OK |
| `tests/Feature/AdminUsersAndHomeArticlesTest.php` | ۱۴ | ۳۶ | ✅ OK |
| `tests/Feature/ViewSmokeTest.php` | ۱۳ | ۷۷ | ✅ OK |
| `tests/Feature/AuthHardeningTest.php` | ۱۰ | ۴۵ | ✅ OK |
| `tests/Feature/CssSeparationTest.php` | ۸ | ۴۰ | ✅ OK |
| `tests/Feature/RouteIntegrityTest.php` | ۵ | ۲۴ | ✅ OK |
| `tests/Feature/ExampleTest.php` | ۲ | ۲ | ✅ OK |
| `tests/Feature/CustomerIndexPageTest.php` | ۱ | ۱ | ✅ OK |
| `tests/Feature/TodaysDealRouteTest.php` | ۱ | ۲ | ✅ OK |
| `tests/Unit/ExampleTest.php` | ۱ | ۱ | ✅ OK |

---

## پوشش موضوعی

### امنیت (۱۱۹ آزمون)

| موضوع | فایل | چه چیزی سنجیده می‌شود |
|-------|------|-----------------------|
| نابودی داده | `SecurityFixesTest` | روت `/demo/cron_1` حذف شده |
| کنترل دسترسی شکسته | `SecurityFixesTest` | دسترسی غیرمجاز به تعمیرات مسدود است |
| Mass Assignment | `SecurityFixesTest` | فیلدهای `role` و `user_type` محافظت‌شده‌اند |
| هدرهای امنیتی | `SecurityFixesTest` | CSP، X-Frame-Options و ... روی پاسخ‌ها |
| CSRF | `SecurityFixesTest` | توکن روی فرم‌های تغییردهنده + مودال حذف |
| میدل‌ور امنیتی | `SecurityFixesTest` | `EncryptCookies` و `AuthenticateSession` فعال‌اند |
| Path Traversal | `SecurityFixesTest` | آپلود و حذف فایل با مسیر خطرناک |
| IDOR | `SecurityFixesTest` | دسترسی به رکورد دیگران رد می‌شود |
| نشت اعتبارنامه | `AuthHardeningTest` | صفحه ورود هیچ رمز/ایمیلی افشا نمی‌کند |
| تصادفی امن | `AuthHardeningTest` | کد OTP با `random_int()` ساخته می‌شود |
| قفل حساب | `AuthHardeningTest` | پس از تلاش‌های ناموفق، پیام throttle |
| پوشش محدودسازی نرخ | `AuthHardeningTest` | وجود `throttle` روی **۱۵** مسیر حساس، با تطبیق «متد + مسیر» |
| شمارش کاربران | `AuthHardeningTest` | پاسخ API بازیابی رمز، وجود کاربر را افشا نمی‌کند |
| تزریق SQL در مرتب‌سازی | `SqlInjectionHardeningTest` | ۱۳ محموله حمله در `sort_by`/`sort_order` به SQL نمی‌رسند؛ ۸ ستون مجاز کار می‌کنند |
| انکار سرویس با `per_page` | `SqlInjectionHardeningTest` | مقدارهای بزرگ و غیرعددی به پیش‌فرض برمی‌گردند |
| Zip Slip | `AddonInstallerHardeningTest` | سه نوع عضو خصمانه ZIP رد می‌شود و فایل بیرون از مقصد ساخته نمی‌شود |
| بازنویسی فایل حیاتی | `AddonInstallerHardeningTest` | `.env`، `composer.json`، `public/index.php` و پوشه‌های حساس مسیر معتبر نمی‌گیرند |
| بارگذاری فایل اجرایی در پنل | `SliderUploadValidationTest` | فایل PHP با نام `.jpg`، بایت تصادفی، HTML و ابعاد بزرگ رد می‌شوند و هیچ فایلی در پوشه بارگذاری نوشته نمی‌شود |
| ثابت‌بودن مسیر بارگذاری | `SliderUploadValidationTest` | فایل ذخیره‌شده هرگز پسوند اجرایی نمی‌گیرد (پلی‌گلات → `.gif`) |
| CSRF فرم بارگذاری | `SliderUploadValidationTest` | مسیر `sliders.store` در استثناهای CSRF نیست و فرم `@csrf` دارد |
| اسلایدر صفحه اصلی | `HomeSliderTest` | اسلایدهای منتشرشده رندر می‌شوند؛ غیرفعال و بی‌تصویر نمی‌شوند؛ نبود اسلاید صفحه را خراب نمی‌کند |
| متن به‌هم‌ریخته | `AuthHardeningTest` | نبود mojibake در کنترلرها |
| Open Redirect | `SecurityFixesTest` | ۵ محموله حمله رد می‌شوند |
| نصب‌کننده | `SecurityFixesTest` | `/install/*` روی سامانه نصب‌شده ۴۰۴ |
| در پشتی (نوشتن فایل) | `BackdoorRemovalAndEnvHardeningTest` | حذف مسیر `/config_content`، تابع مبهم‌سازی و استثنای CSRF آن |
| ارتباط پنهان | `BackdoorRemovalAndEnvHardeningTest` | نبود تابع ارسال و نبود نشانی بیرونی در کل فایل‌های PHP |
| تزریق `.env` | `BackdoorRemovalAndEnvHardeningTest` | رد `\n`/`\r`/کوتیشن/بک‌اسلش، فهرست مجاز کلیدها، میدل‌ور `settings.update` |
| ایمنی کش پیکربندی | `ConfigCacheSafetyTest` | نبود `env()` در کد برنامه، صحت استثناهای مستند، وجود ۳۴ کلید پیکربندی، پوشش `.env.example`، نبود رمز واقعی در آن |
| کوکی نشست | `SecurityFixesTest` | رمزنگاری و SameSite |
| نشست بومی | `SecurityFixesTest` | `session_start()` در سطح فایل وجود ندارد |
| روت‌های مدیریتی | `SecurityFixesTest` | هر روت تغییردهنده مجوز یا Gate دارد |

### RBAC (۲۰ آزمون)

| موضوع | چه چیزی سنجیده می‌شود |
|-------|-----------------------|
| Deny by Default | کاربر بدون مجوز رد می‌شود |
| چند‌نقشی | کاربر با دو نقش، اجتماع مجوزها را دارد |
| کاراکتر عام | `products.*` و `*.view` درست تفسیر می‌شوند |
| محافظت خودکاربری | کاربر نمی‌تواند نقش خودش را تغییر دهد |
| آخرین مدیر کل | سلب نقش از تنها مدیر کل رد می‌شود |
| سقف سطح | کاربر نقشی بالاتر از سطح خودش نمی‌دهد |
| کش مجوز | پس از تغییر نقش، دسترسی فوراً اعمال می‌شود |
| ثبت حسابرسی | انتساب نقش در `audit_logs` نوشته می‌شود |

### مدیریت مقالات (۲۸ آزمون)

ساخت/ویرایش/حذف، گردش وضعیت (پیش‌نویس → بازبینی → انتشار)، اسلاگ یکتا،
پاک‌سازی HTML (حذف `<script>`، `onerror`، `javascript:`)، آپلود تصویر
با نوع واقعی، محدودیت حجم و ابعاد، بررسی مالکیت رکورد و مجوزها.

### ماژول مالکیت (۱۹ آزمون)

ثبت انتقال، تأیید، رد با دلیل، لغو، **جلوگیری از انتقال تکراری**،
**مقاومت در برابر دو انتقال هم‌زمان**، حفظ تاریخچه پس از انتقال،
ممنوعیت حذف تاریخچه، تفکیک تأییدکننده از درخواست‌دهنده، ثبت رخداد در
Audit Log.

### نصب و سیدر (۱۵ آزمون)

انطباق رجیستری مجوز با دیتابیس، تکرارپذیری سیدر، شش نقش پیش‌فرض با
تعداد مجوز مستندشده، نبود مجوز بی‌متولی، پیش‌فرض‌نبودن داده نمونه،
رد رمز ضعیف/کوتاه/کم‌تنوع در ساخت مدیر کل، ساخت موفق مدیر کل با رمز
از فایل، هش‌شدن رمز در دیتابیس.

### رابط کاربری (۳۵ آزمون پیشین + ۹ آزمون اسلایدر صفحه اصلی)

۱۳ آزمون رندر صفحه (پنل‌های RBAC، مقالات، انتقال، گزارش‌ها، صفحات
عمومی)، ۸ آزمون جداسازی CSS (وجود فایل، نبود بلوک بزرگ درون‌خطی،
حضور `<link>` در HTML واقعی، ترتیب آبشاری)، ۱۴ آزمون لینک‌های پنل
اشیاء قیمتی و بخش مقالات صفحه اصلی.

---

## آزمون‌های دستی انجام‌شده

این موارد آزمون خودکار ندارند و **دستی** روی سرور محلی بررسی شدند:

| مورد | روش | نتیجه |
|------|-----|-------|
| اجرای فایل ارتقا روی دیتابیس قدیمی | `mysql < platform_upgrade.sql` روی یک دیتابیس ۶۰ جدولی | ✅ ۷۴ جدول، ۶ نقش، ۸۶ مجوز، ۱۴۳ انتساب |
| مسیر جایگزین ارتقا | `php artisan migrate` روی همان دیتابیس | ✅ همان نتیجه |
| سیدر اصلی روی دیتابیس ارتقایافته | `php artisan db:seed --force` | ✅ تکرارپذیر، بدون داده تکراری |
| بازیابی سابقه مالکیت | `drikana:backfill-ownership-history` | ✅ ۴ شناسنامه قدیمی، ۴ رکورد `initial` |
| ساخت مدیر کل غیرتعاملی | `--password-file` با فایل مجوز ۶۰۰ | ✅ کاربر ساخته شد، نقش و رکورد حسابرسی ثبت شد |
| رد رمز ضعیف در دستور | `--password-file` با محتوای `123456` | ✅ کد خروج ۱، کاربری ساخته نشد |
| نبود فایل رمز | `--password-file=/tmp/does-not-exist` | ✅ خطا و توقف (بدون بازگشت به پرسش تعاملی) |
| صفحه اصلی سایت | `GET /` روی سرور محلی | ✅ ۲۰۰، بخش مقالات با ۳ کارت |
| فهرست مقالات | `GET /articles` | ✅ ۲۰۰ |
| جزئیات مقاله | `GET /articles/{slug}` | ✅ ۲۰۰ |
| دسته‌بندی مقالات | `GET /articles/category/{slug}` | ✅ ۲۰۰ |
| جست‌وجو در مقالات | `GET /articles/search?q=طلا` | ✅ ۲۰۰ |
| در پشتی نوشتن فایل | `POST /config_content` روی سرور محلی | ✅ ۴۰۴ و **هیچ فایلی نوشته نشد** |
| بازبینی کوئری‌های خام | جست‌وجوی الگوی «الحاق متغیر در SQL» روی کل `app/` | ✅ تنها دو مورد باقی‌مانده: `(int)` شده‌اند |
| انتقال استایل درون‌خطی | `tools/verify_inline_style_extraction.py` (۵۱۱ از ۵۱۱ اعلان با اعلان یکسان) | ✅ صفر کلاس گم‌شده، صفر کلاس اعمال‌نشده، استایل پویا ۹=۹ |
| نمایش اسلایدر صفحه اصلی | curl روی `/` و بررسی کد وضعیت تصاویر | ✅ بخش بنر رندر شد و هر ۴ تصویر ۲۰۰ |
| پایداری آزمون قفل ورود | اجرای سه‌باره آزمون و دو اجرای کامل مجموعه | ✅ بدون شکست — الگوی متن throttle به‌جای برابری عدد ثانیه |
| نصب وابستگی‌ها | `composer validate` + `composer install --dry-run` پس از تغییر `minimum-stability` | ✅ بدون خطا و بدون هشدار «قفل قدیمی است» |
| ایمنی `config:cache` | `php artisan config:cache` + درخواست به `/`، `/articles`، `/admin` | ✅ ۲۰۰/۲۰۰/۲۰۲ — و `config('drikana.sms.farazsms.from')` مقدار درست داد در حالی که `env('APP_NAME')` خالی بود |
| بررسی نحو PHP | `php -l` روی تمام فایل‌های تغییریافته | ✅ بدون خطا |
| فهرست روت‌ها | `php artisan route:list` | ✅ بدون خطای روت |
| پاک‌سازی Autoload | `composer dump-autoload` | ✅ ۹۲۹۵ کلاس |

**نکته درباره آزمون همزمانی (Race Condition):** دو انتقال هم‌زمان روی
یک قطعه در یک تراکنش آزموده شد (`OwnershipTransferTest`). آزمون
**واقعاً دو فرایند موازی** اجرا نمی‌کند؛ محافظ اصلی، قید یکتای
`UNIQUE (certificate_id, sequence_no)` در سطح دیتابیس و قفل
`SELECT ... FOR UPDATE` است که قابلیت اطمینان آن‌ها به موتور دیتابیس
تکیه دارد، نه به کد آزمون. آزمون، منطق «انتقال تکراری رد شود» را
می‌سنجد.

---

## مواردی که آزمون خودکار ندارند

| مورد | وضعیت | دلیل |
|------|-------|------|
| ارسال واقعی پیامک OTP | آزمون‌نشده | نیازمند اعتبار پنل پیامک و هزینه واقعی |
| پرداخت واقعی درگاه | آزمون‌نشده | نیازمند حساب واقعی درگاه |
| ارسال ایمیل | آزمون‌نشده (درایور `array`) | آزمون‌ها ایمیل واقعی نمی‌فرستند |
| بازیابی رمز عبور با ایمیل واقعی | آزمون‌نشده | همان دلیل |
| رندر PDF (فاکتور، شناسنامه) | آزمون‌نشده | به‌علت هشدارهای امنیتی `dompdf` باید پس از ارتقا دستی بررسی شود |
| خروجی Excel | آزمون دستی | — |
| ثبت‌نام با ورود اجتماعی | آزمون‌نشده | نیازمند اپلیکیشن OAuth واقعی |
| رفتار در مرورگر و ریسپانسیو | آزمون دستی | روی سرور محلی بررسی شد |
| ارتقای دیتابیس روی داده واقعی | آزمون روی کپی | روی کپی دیتابیس اجرا شد، نه روی داده عملیاتی |
| بار سنگین و کارایی | آزمون‌نشده | خارج از دامنه این مرحله |

> **هشدار:** پیش از استفاده در محیط عملیاتی، مسیرهای «آزمون‌نشده» بالا
> — به‌ویژه **پرداخت** و **تولید PDF** — را روی یک محیط آزمایشی واقعی
> بررسی کنید.

---

## گزارش فایل‌های افزوده، حذف‌شده و تغییر‌یافته

مقایسه با شاخه `backup/pre-refactor` (وضعیت پیش از بازسازی):

```
~۲۴۰ فایل تغییر کرد (این شماره با هر بسته کار افزایش می‌یابد)
  ~۱۲۰ فایل افزوده   (A)
   ~۹۰ فایل تغییر‌یافته (M)
    ۴ فایل حذف‌شده    (D)
```

> **چرا «تقریبی»؟** این گزارش پیش از پایان کار نوشته شده است. عدد دقیق
> را با `git diff --stat backup/pre-refactor` بگیرید؛ شاخه
> `backup/pre-refactor` نقطه شروع کار را نگه می‌دارد.

### فایل‌های حذف‌شده (چهار مورد — همه با دلیل)

| فایل | دلیل حذف | جایگزین |
|------|----------|---------|
| `.env` | افشای تمام اعتبارنامه‌ها در Git | `.env.example` بدون رمز + `.gitignore` |
| `.env.gobox` | همان | همان |
| `app/Http/Controllers/DemoController.php` | روت عمومی که کل دیتابیس را دراپ می‌کرد | حذف کامل — قابلیت جایگزینی ندارد و نباید داشته باشد |
| `app/Http/Controllers/Jewelry/JewelryTransferController.php` | انتقال مالکیت بدون تراکنش، بدون قفل و بدون گردش تأیید انجام می‌داد | **`Jewelry/OwnershipTransferController.php`** + `Ownership/OwnershipTransferService` — قابلیت حذف نشد، بازنویسی شد |

> **هیچ قابلیت عاملی از دست نرفت.** تمام چهار مورد بالا یا آسیب‌پذیری
> بودند یا با نسخه‌ای کامل‌تر جایگزین شدند.
>
> **حذف کد بدون حذف قابلیت:** علاوه بر چهار فایل بالا، دو «در پشتی»
> از *داخل* فایل‌های موجود حذف شد (بدون حذف فایل): مسیر
> `POST /config_content` با تابع نوشتن فایل روی مسیر دلخواه (بحرانی
> `C-04`) و تابع ارسال پنهان نشانی صفحه‌ها به بیرون (بحرانی `C-05`).
> پیش از حذف، تمام ارجاع‌ها در پروژه جست‌وجو شد و هیچ مصرف‌کننده
> سالمی یافت نشد.

### فایل‌های افزوده‌شده — به تفکیک نقش

**زیرساخت RBAC — ۱۱ فایل تازه**
`Services/Rbac/PermissionRegistry.php`, `Services/Rbac/RbacService.php`,
`Models/Permission.php`, `Models/Concerns/HasRolesAndPermissions.php`,
`Middleware/CheckPermission.php`, `Middleware/CheckAllPermissions.php`,
`Middleware/CheckRole.php`, `Http/Controllers/UserRoleController.php`,
`Services/AuditLogger.php`, `Models/AuditLog.php`,
`database/seeds/RbacSeeder.php`

**زیرساخت RBAC — ۳ فایل توسعه‌یافته (نه تازه)**
`app/Http/Controllers/RoleController.php` (کنترلر نقش‌ها)،
`app/Models/Role.php` (روابط، سطح و هم‌گام‌سازی مجوز)،
`app/User.php` (Trait دسترسی‌ها، کش مجوز، `isSuperAdmin`)

**ماژول مالکیت (۵ فایل)**
`Services/Ownership/OwnershipTransferService.php`,
`Http/Controllers/Jewelry/OwnershipTransferController.php`,
`Models/OwnershipHistory.php`, `Observers/JewelryCertificateObserver.php`,
`Policies/OwnershipTransferPolicy.php`

**ماژول مقالات (۶ فایل)**
`Http/Controllers/ArticleController.php` (عمومی),
`Http/Controllers/Admin/ArticleController.php`,
`Models/Article.php`, `Models/ArticleCategory.php`,
`Models/ArticleImage.php`, `Services/Content/ArticleService.php`,
`Services/Content/HtmlSanitizer.php`, `Policies/ArticlePolicy.php`

**پیکربندی (۱ فایل)**
`config/drikana.php` — کلیدهای پیامک، درگاه پرداخت، رهگیری، کد تأیید
توسعه، نصب‌کننده و داده نمونه (رفع مورد M-09)

**امنیت (۶ فایل)**
`Middleware/SecurityHeaders.php`, `Middleware/BlockWhenInstalled.php`,
`Services/Media/SecureImageUploader.php`,
`Services/Security/PasswordPolicy.php`,
`Console/Commands/MakeSuperAdmin.php`,
`Console/Commands/BackfillOwnershipHistory.php`

**مهاجرت‌ها و SQL (۹ فایل)**
`database/migrations/2026_10_12_1000{01..08}_*.php` و
`database/sql/platform_upgrade.sql` + `database/sql/README.md`

**سیدرها (۲ فایل)**
`database/seeds/RbacSeeder.php`, `database/seeds/DrikanaSampleArticlesSeeder.php`

**آزمون‌ها (۱۴ فایل)**
`SecurityFixesTest`, `RbacAccessControlTest`, `OwnershipTransferTest`,
`ArticleManagementTest`, `ViewSmokeTest`, `RouteIntegrityTest`,
`InstallAndSeedTest`, `AuthHardeningTest`, `CssSeparationTest`,
`AdminUsersAndHomeArticlesTest`, `BackdoorRemovalAndEnvHardeningTest`,
`ConfigCacheSafetyTest`, `AddonInstallerHardeningTest`,
`SqlInjectionHardeningTest`

**CSS جدا‌شده (۱۴ فایل در `public/css/drikana/`)**
`store.css`, `store-category.css`, `store-menu.css`, `jewelry-admin.css`,
`jewelry-certificates.css`, `jewelry-owners.css`, `auth.css`,
`auth-register.css`, `admin.css`, `ownership.css`, `blank.css`,
`print.css`, `articles.css`, `repair.css`

**مستندات (۷+ فایل در `docs/`)**
`01-initial-analysis.md`, `architecture.md`, `security-audit.md`,
`roles-and-permissions.md`, `ownership-module.md`, `articles-module.md`,
`deployment.md`, `testing-report.md`

### فایل‌های تغییر‌یافته — ۲۰ مورد مهم

| فایل | تغییر کلیدی |
|------|-------------|
| `app/Http/Kernel.php` | فعال‌سازی میدل‌ورهای امنیتی، حذف بازگشت بی‌نهایت گروه `admin`، ثبت `not_installed` |
| `routes/web.php` | حذف روت نابودکننده، تفکیک نام‌ها، throttle روی OTP |
| `routes/admin.php` | ثبت روت‌های RBAC، رفع نام‌های تکراری، مسیر ساخت کاربر |
| `routes/api.php` | پیشوند `api.` روی همه نام‌ها |
| `routes/otp.php` | throttle روی مسیرهای تأیید هویت |
| `config/session.php` | رمزنگاری نشست و `same_site` |
| `config/app.php` | کلید `demo_mode` |
| `app/Helpers/drikana_helpers.php` | `drikana_safe_redirect_url()` |
| `app/Http/Controllers/Auth/LoginController.php` | هدایت ایمن، throttle ورود |
| `app/Http/Controllers/Auth/ForgotPasswordController.php` | `random_int()` و throttle |
| `app/Http/Controllers/OTPVerificationController.php` | `random_int()` |
| `app/Http/Controllers/ZarinpalController.php` | رفع ۱۵۵ رشته به‌هم‌ریخته |
| `app/Http/Controllers/HomeController.php` | کوئری مقالات برای صفحه اصلی |
| `app/Http/Controllers/UserRoleController.php` | ساخت کاربر ادمین |
| `database/seeds/DatabaseSeeder.php` | اجرای سیدرهای هسته به‌ترتیب |
| `resources/views/frontend/index.blade.php` | بخش مقالات + انتقال CSS |
| `resources/views/admin/jewelry/layouts/app.blade.php` | لینک‌های کاربران و نقش‌ها + انتقال CSS |
| `resources/views/auth/login.blade.php` | حذف اعتبارنامه افشاشده |
| `resources/views/frontend/user_login.blade.php` | کنترل نمایش داده نمونه |
| `public/frontend/css/drikana-luxury.css` | استایل بخش مقالات |

---

## جمع‌بندی

| پرسش | پاسخ |
|------|------|
| آیا همه آزمون‌ها سبز هستند؟ | بله — ۱۸۹ موفق، ۰ ناموفق، ۱ رد‌شده |
| آیا آزمونی «اجرا‌نشده» به‌عنوان موفق گزارش شده؟ | خیر — موارد آزمون‌نشده صریحاً فهرست شده‌اند |
| آیا قابلیتی از دست رفته؟ | خیر — چهار فایل حذف‌شده، همه آسیب‌پذیری بودند یا جایگزین کامل‌تر داشتند |
| آیا مسیرهای حیاتی آزمون دستی شدند؟ | بله — جدول «آزمون‌های دستی» |
| بزرگ‌ترین ریسک باقی‌مانده | هشدارهای وابستگی Composer (نیازمند ارتقای بزرگ) و نبود آزمون پرداخت واقعی |
| آیا کوئری خام ناامن باقی مانده؟ | خیر — ۱۵۳ نقطه بازبینی شد؛ دو تزریق واقعی بسته شد و نصب افزونه محافظ‌گذاری شد |

---

# پیوست — دور بازبینی «سلامت همه صفحات» (۱۴۰۵/۰۷/۲۰)

پس از گزارش کاربر («صفحه سایت و صفحه ادمین قابل مشاهده نیست»)، کل سامانه
با ابزارهای زیر دوباره آزمون شد و ۱۶ صفحهٔ خطادار پنل مدیریت رفع گردید.

## ابزارهای آزمون

| ابزار | کار | فایل |
|-------|-----|------|
| تست سلامت سایت عمومی | ۵۶ بررسی: صفحه‌ها، API، فایل‌های ایستا، مسیرهای حساس | `tools/health_check.sh` |
| خزش پنل مدیریت با ورود واقعی | همهٔ مسیرهای GET بدون پارامتر؛ هر ۵۰۰ = خطای واقعی | `tools/crawl_admin.sh` |
| بررسی ارجاع‌های کد | ۶۶۷ نام مسیر · ۸۶ مجوز · ۳۳۳ ویو | `tools/check_references.py` |
| آزمون مجموعه تست‌ها | PHPUnit 7.5.20 | `vendor/bin/phpunit` |

## نتیجهٔ خزش‌ها (پس از رفع)

| چه کسی | صفحهٔ سالم (۲۰۰) | خطای ۵۰۰ | سایر (۳۰۲/۴۰۴) |
|--------|------------------|----------|-----------------|
| مدیر کل (`admin@drikana.test`) | ۱۰۱ | **۰** | ۱۱ |
| فروشنده (`seller@drikana.test`) | ۸ | **۰** | ۱۰۶ |

- سایت عمومی: **۵۶ موفق / ۰ ناموفق / ۴ هشدار** (هشدارها بررسی‌شده و بی‌خطر:
  پاسخ‌های JSON کوچک و ریدایرکت‌های عمدی).
- ارجاع‌های کد: **همه معتبر** (۰ ارجاع شکسته؛ ۲۹ ارجاع «مشروط» که داخل
  شرط فعال‌بودن افزونه یا `Route::has` هستند و خطا محسوب نمی‌شوند).
- PHPUnit: **۲۴۴ تست / ۱۵۴۷ اَسِرشِن / ۰ خطا / ۱ رد‌شده** (بدون رگرسیون).

## ۱۶ صفحه‌ای که ۵۰۰ می‌دادند و علت آن‌ها

| صفحه | علت ریشه‌ای | اقدام |
|------|-------------|-------|
| `/admin/affiliate` | افزونهٔ همکاری در فروش نصب نشده؛ ویو `affiliate.index` وجود ندارد | مسیرهای افزونه به «نصب‌بودن» گره خورد → ۴۰۴ |
| `/admin/affiliate/configs`, `/users`, `/admin/refferal/users` | همان | همان |
| `/admin/club-points/index`, `/configs`, `/set-club-points` | افزونهٔ امتیاز نصب نشده؛ `App\ClubPoint` وجود ندارد | همان |
| `/admin/manual_payment_methods`, `/create`, `/offline-wallet-recharge-requests` | افزونهٔ پرداخت آفلاین نصب نشده | همان |
| `/admin/seller_packages`, `/create` | افزونهٔ اشتراک فروشنده نصب نشده | همان |
| `/admin/frontend_settings/home_banners/create` | روت resource متد `create($position)` را بدون پارامتر صدا می‌زد | روت اختصاصی پیش از resource + پارامتر اختیاری |
| `/admin/jewelry/reports/live-stats` | ستون `completed_at` در `repair_orders` وجود ندارد | `completion_date` |
| `/admin/products/admin` | روت `products.destroy` ثبت نشده بود | روت مشترک مدیر/فروشنده با کنترل دسترسی |
| `/admin/user` | متد `setOptions()` روی الیاس `PDF` (که پکیج mPDF آن را بازنویسی می‌کند) + واکشی شبکه‌ای لوگو (۶۰ ثانیه توقف) | trait `BuildsPdfDocuments` با تنظیم درست `chroot`/`isRemoteEnabled` |
| `/seller/payments` | جدول `seller_payments` در دیتابیس وجود نداشت | مایگریشن + مدل + اصلاح کنترلر |

## آزمون‌های امنیتی این دور

| آزمون | روش | نتیجه |
|-------|-----|-------|
| دست‌کاری شناسهٔ رمزنگاری‌شده | `GET /admin/brands/xyz/edit`, `/admin/staff/notvalid/edit`, `/admin/orders/abc` | **۴۰۴** (قبلاً ۵۰۰ با متن استثنا) |
| حذف محصول توسط فروشندهٔ دیگر | تست `product_delete_route_exists_and_blocks_cross_seller_deletion` | **۴۰۳** + ثبت در Audit Log |
| حذف محصول بدون مجوز `products.delete` | تست `product_delete_requires_permission_for_admin` | **۴۰۳** |
| دسترسی مهمان به پنل | ۱۰ مسیر مدیریتی | **۳۰۲** (هدایت به ورود) |
| افزونهٔ نصب‌نشده | ۳ مسیر افزونه | **۴۰۴** (نه ۵۰۰) |

## آزمون SQL روی دیتابیس پاک

`database/sql/platform_upgrade.sql` روی دیتابیس تازه (دامپ ۶۴ جدولی) اجرا شد:

- بار اول: بدون خطا → **۷۵ جدول**، ۶ نقش، ۸۶ مجوز، ۱۴۳ پیوند
- بار دوم: بدون خطا (تکرارپذیری تأیید شد) → همان اعداد
- هر ۱۱ جدول کلیدی (RBAC، مالکیت، مقالات، حسابرسی، پرداخت فروشنده) ساخته شد


---

# پیوست ۳ — دور سوم بازبینی سلامت همهٔ صفحات (۱۴۰۵/۰۷/۲۰)

ابزار تازهٔ `tools/crawl_all_pages.sh` ساخته شد که **تمام مسیرهای GET بدون پارامتر** را
از خودِ روتر لاراول استخراج می‌کند (نه فهرست دستی، پس هیچ صفحه‌ای جا نمی‌ماند) و هر
مسیر را دو بار می‌خزد: یک‌بار بی‌ورود (مهمان) و یک‌بار با نشست مدیر کل. علاوه بر کد ۵۰۰،
صفحه‌هایی که کد ۲۰۰ می‌دهند ولی متن `Fatal error` / `Parse error` / `Uncaught` /
`Whoops` در بدنه دارند هم خطا شمرده می‌شوند (خطاهای سرکوب‌شده را می‌گیرد).

```bash
bash tools/crawl_all_pages.sh [BASE_URL] [ADMIN_EMAIL] [ADMIN_PASSWORD]
```

## نتیجهٔ نهایی

| نما | مسیر | ۲۰۰ سالم | خطای ۵۰۰ | ۴۲۹ (محافظت نرخ) | سایر (۳۰۲/۴۰۴) |
|-----|------|----------|----------|------------------|-----------------|
| مهمان | ۲۴۷ | ۷۱ | **۰** | ۳ | ۱۷۳ |
| مدیر کل | ۲۴۷ | ۱۵۴ | **۰** | ۰ | ۹۳ |

سه مورد ۴۲۹ مربوط به مسیرهای `users/login/otp`، `verification/phone/code/resend` و
`password/phone/reset` است که در `routes/otp.php` عمداً با `throttle` محدود شده‌اند؛
یعنی **محافظت از حملهٔ فرهنگ‌نامه‌ای درست کار می‌کند**، نه خرابی.

## باگ‌های یافته و رفع‌شدهٔ این دور

در نخستین اجرا ۹ آدرس با ۵ ریشهٔ خطا پاسخ ۵۰۰ می‌دادند؛ هیچ‌کدام «رگرسیون» نبودند و
همه باگ‌های از قبل موجود کد اصلی بودند.

| # | مسیر(ها) | ریشهٔ خطا | رفع |
|---|----------|-----------|-----|
| R-08 | `/api/v1/categories`, `/api/v1/categories/featured`, `/api/v1/home-categories` | تعریف متد نام‌تکراری در `App\Models\Category` و `App\Models\SubCategory`؛ PHP نام متد را حساس به بزرگی/کوچکی حرف نمی‌داند، پس `subcategories()` در کنار `subCategories()` خطای مرگبار «Cannot redeclare» می‌دهد و **کل مدل** بارگذاری نمی‌شود | حذف متدهای نام‌تکراری؛ Eloquent خودش `$obj->subcategories` را به `subCategories()` نگاشت می‌کند |
| R-09 | `/api/v1/categories/home` | خواندن `->value` از تنظیم غایب `category_homepage` | بررسی null + بازگشت به همهٔ دسته‌ها |
| R-10 | `/api/v1/products/flash-deal` | دسترسی به عنصر اولِ مجموعهٔ خالی در `FlashDealCollection::toArray()` | بازگشت `title: null, end_date: null, products: []` |
| R-11 | `/customer-products` | کمک‌تابع `filter_customer_products()` در `app/Http/Helpers.php` از متغیر تعریف‌نشدهٔ `$products` استفاده می‌کرد | استفاده از پارامتر واقعی + بررسی null روی تنظیم |
| R-12 | `/checkout/delivery_info`, `/checkout/payment_select` | `foreach`/`count()` روی `Session::get('cart')` در نبود سبد (null) | پیش‌فرض ایمن در کنترلرها و ویوها |
| R-13 | `/zarinpal/back_url` | `$_GET['Authority']` بدون بررسی + سفارش نامعتبر | خواندن از `$request->input()` + اعتبارسنجی + هدایت به خانه با پیام خطا |

## باگ کلاس پنهان: cast آرایه‌ای روی Collection

هنگام رفع R-12 نخست روش `(array) Session::get('cart', [])` به‌کار رفت. آزمایش با **سبد
خالی** سالم بود، اما نشست سبد را به‌صورت **Collection** نگه می‌دارد و cast آرایه‌ای
ساختارش را می‌شکند → `Undefined index: id/price`. آزمون جریان واقعی خرید (سبد پُر) این
را آشکار کرد و الگوی درست در **۲۸ نقطه از ۱۴ فایل** یکدست شد:

```php
// ❌ غلط: ساختار Collection را نابود می‌کند
foreach ((array) Session::get('cart', []) as $key => $cartItem)

// ✅ درست: نوع را دست‌نخورده نگه می‌دارد و فقط null را جبران می‌کند
foreach ((Session::get('cart') ?? []) as $key => $cartItem)
```

**درس:** صفحه‌ای که با دادهٔ خالی سالم است، لزوماً با دادهٔ واقعی سالم نیست.

## آزمون جریان کامل خرید (دستی، سبد پُر)

| گام | درخواست | نتیجه |
|-----|---------|-------|
| ۱ | `GET /` و گرفتن توکن CSRF | ۲۰۰ |
| ۲ | `POST /cart/addtocart` (محصول ۱) | ۲۰۰ + پیام «به سبد خرید شما اضافه شد» |
| ۳ | `GET /cart` | ۲۰۰ — محصول و مبلغ ۱۱٬۸۷۵٬۰۰۰ نمایش داده شد |
| ۴ | `GET /checkout` | ۲۰۰ (فرم آدرس) |
| ۵ | `POST /checkout/delivery_info` | ۲۰۰ |
| ۶ | `POST /checkout/payment_select` | ۲۰۰ — صفحهٔ پرداخت نمایش داده شد |

## آزمون‌های خودکار این دور

| مجموعه | تعداد | نتیجه |
|--------|-------|-------|
| کل PHPUnit | ۲۵۲ تست / ۱۵۷۳ گزاره | **OK** — ۱ skip، ۰ شکست |
| `BugRegressionFixTest` | ۱۹ تست / ۶۱ گزاره | **OK** |
| `tools/crawl_all_pages.sh` | ۲۴۷ مسیر × ۲ نما | **۰ خطا** |
| `tools/crawl_admin.sh` (مدیر) | ۱۱۲ مسیر | ۱۰۱ سالم / **۰ خطا** |
| `tools/crawl_admin.sh` (فروشنده) | ۱۱۴ مسیر | ۸ سالم / **۰ خطا** (بقیه کنترل دسترسی درست) |
| `tools/health_check.sh` | ۶۰ بررسی | ۵۶ موفق / **۰ ناموفق** / ۴ هشدار |
| `tools/check_references.py` | ۶۶۷ نام مسیر | **۰ ارجاع شکسته** |

### تست‌های تازهٔ `BugRegressionFixTest`

`category_models_load_without_redeclare_fatal`، `category_api_endpoints_return_200`،
`cart_empty_pages_do_not_throw_500`، `zarinpal_back_url_without_authority_redirects`،
`shared_cart_partials_render_with_empty_session`،
`customer_products_filter_helper_uses_its_parameter`،
`cart_pages_render_with_a_filled_cart_collection`،
`cart_session_collection_survives_iteration`.

## بازیابی محیط اجرا (ریشهٔ «دیده‌نشدن سایت»)

سایت و پنل «دیده نمی‌شدند» چون **محیط اجرا بازنشانی شده بود**، نه چون کد خراب بود:
سرور PHP، فایل‌های پروژه و کش از دست رفته بودند و دیتابیس بدون سرویس‌دهنده بود.
بازگردانی انجام‌شده:

1. کلون کامل از مخزن گیت‌هاب (کامیت `8ccfdd41`) — `git clone` چون مخزن عمومی است؛
2. بازگرداندن فایل‌های رازِ خارج از گیت (`.env`, `.env.testing`, …) از نسخهٔ محلی؛
3. `composer install` برای بازسازی `vendor/`؛
4. راه‌اندازی سرور روی `0.0.0.0:8000` و اتصال به MariaDB؛
5. اعمال دوبارهٔ رفع‌های دور سوم روی نسخهٔ کلون‌شده و تأیید مجدد همهٔ آزمون‌ها.
