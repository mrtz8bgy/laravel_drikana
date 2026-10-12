# نقش‌ها و مجوزها (RBAC)

> سامانه دسترسی این پروژه «پویا» است: نقش‌ها و مجوزها در دیتابیس ذخیره
> می‌شوند و مدیر کل سیستم می‌تواند بدون تغییر کد، آن‌ها را بسازد یا تغییر دهد.

## فهرست

1. [مفاهیم پایه](#مفاهیم-پایه)
2. [نقش‌های پیش‌فرض](#نقشهای-پیشفرض)
3. [فهرست کامل مجوزها](#فهرست-کامل-مجوزها)
4. [نحوه اعمال در کد](#نحوه-اعمال-در-کد)
5. [پنل مدیریت دسترسی](#پنل-مدیریت-دسترسی)
6. [قواعد امنیتی](#قواعد-امنیتی)
7. [ساخت نخستین مدیر کل سیستم](#ساخت-نخستین-مدیر-کل-سیستم)
8. [افزودن مجوز جدید](#افزودن-مجوز-جدید)

---

## مفاهیم پایه

| مفهوم | توضیح |
|-------|-------|
| **مجوز (Permission)** | یک «توانایی اتمی»، مثل `articles.publish`. ۸۶ مجوز در سیستم تعریف شده است. |
| **نقش (Role)** | مجموعه‌ای از مجوزها، مثل «مدیر محتوا». ۶ نقش پیش‌فرض وجود دارد. |
| **سطح (Level)** | وزن عددی نقش. نقش با سطح بالاتر اولویت دارد. |
| **کاربر چندنقشی** | یک کاربر می‌تواند هم‌زمان چند نقش داشته باشد؛ مجموعه مجوزها، اجتماع آن‌هاست. |

### کجا ذخیره می‌شود؟

| جدول | محتوا |
|------|-------|
| `permissions` | فهرست ۸۶ مجوز |
| `roles` | فهرست نقش‌ها (جدول موجود پروژه که توسعه یافت) |
| `role_user` | کاربر ↔ نقش |
| `permission_role` | نقش ↔ مجوز — **مرجع نهایی** |

> **نکته:** جدول `roles` ستون `permissions` از نوع JSON هم دارد. این ستون
> از نسخه قدیمی پروژه باقی مانده و برای سازگاری حفظ شده است، اما هیچ
> تصمیم دسترسی بر اساس آن گرفته نمی‌شود. مرجع واقعی `permission_role` است.

---

## نقش‌های پیش‌فرض

| نقش | عنوان | سطح | تعداد مجوز |
|-----|-------|-----|-----------|
| `super_admin` | مدیر کل سیستم | ۱۰۰ | ۸۶ (همه) |
| `store_manager` | مدیر فروشگاه | ۶۰ | ۲۲ |
| `ownership_manager` | مدیر مالکیت | ۵۰ | ۱۷ |
| `repair_technician` | تکنسین تعمیرات | ۴۰ | ۷ |
| `content_manager` | مدیر محتوا | ۳۰ | ۱۱ |
| `customer` | کاربر عادی | ۱۰ | ۰ |

### توضیح هر نقش

| نقش | مسئولیت |
|-----|---------|
| **مدیر کل سیستم** | دسترسی کامل. تنها نقشی که می‌تواند نقش بسازد، ویرایش کند و به کاربران بدهد. |
| **مدیر فروشگاه** | محصولات، دسته‌بندی‌ها، برندها، سفارش‌ها، فروشندگان و تنظیمات فروشگاه. |
| **مدیر مالکیت** | شناسنامه‌ها، مالکان، انتقال مالکیت و گزارش‌های مالکیت. |
| **تکنسین تعمیرات** | مشاهده و به‌روزرسانی سفارش‌های تعمیر، ثبت گزارش پیشرفت. |
| **مدیر محتوا** | ایجاد، ویرایش، ارسال برای بازبینی و انتشار مقالات. |
| **کاربر عادی** | بدون مجوز مدیریتی. دسترسی به سفارش‌های خود، جواهرات خود و ثبت درخواست انتقال مالکیت. |

> **نقش «کاربر عادی» عامدانه صفر مجوز دارد.** دسترسی‌های آن (مثل مشاهده
> جواهرات خود) از طریق Policy و بررسی مالکیت رکورد انجام می‌شود، نه از
> طریق مجوز — چون وابسته به «کدام رکورد» است، نه «کدام عملیات».

---

## فهرست کامل مجوزها

عنوان‌گذاری: `<گروه>.<عملیات>`

برای هر عملیات حساس، مجوز «جداگانه» تعریف شده است. مثلاً `articles.update`
و `articles.publish` دو مجوز متفاوت‌اند تا اصل **تفکیک وظایف**
(Separation of Duties) قابل اعمال باشد: یک خبرنگار می‌تواند مقاله بنویسد
اما نتواند منتشر کند.

### `dashboard` — داشبورد
`dashboard.view`

### `products` — محصولات
`products.view` · `products.create` · `products.update` · `products.delete` · `products.publish` · `products.images.manage`

### `categories` — دسته‌بندی‌ها
`categories.view` · `categories.manage`

### `brands` — برندها
`brands.view` · `brands.manage`

### `orders` — سفارش‌ها
`orders.view` · `orders.update` · `orders.update_status` · `orders.delete` · `orders.invoice`

### `stock` — موجودی
`stock.view` · `stock.update`

### `users` — کاربران
`users.view` · `users.create` · `users.update` · `users.delete`

### `roles` — نقش‌ها
`roles.view` · `roles.create` · `roles.update` · `roles.delete` · `roles.assign`

### `permissions` — مجوزها
`permissions.view` · `permissions.create` · `permissions.delete`

### `audit_logs` — گزارش حسابرسی
`audit_logs.view`

### `security` — گزارش‌های امنیتی
`security.reports.view`

### `articles` — مقالات
`articles.view` · `articles.view_own` · `articles.create` · `articles.update` · `articles.update_any` · `articles.delete` · `articles.submit` · `articles.publish` · `articles.categories.manage` · `articles.images.manage`

> تفکیک کلیدی: `articles.view_own` یعنی «تنهامقالات خودم را ببینم» و
> `articles.update` یعنی «تنها مقالات خودم را ویرایش کنم»؛ در برابر آن‌ها
> `articles.update_any` و `articles.view` (بدون `_own`) دسترسی به همه
> مقالات را می‌دهند. بررسی «مالکیت رکورد» در کنترلر انجام می‌شود.

### `jewelry.certificates` — شناسنامه‌ها
`jewelry.certificates.view` · `.create` · `.update` · `.delete` · `.verify` · `.print` · `.download`

### `jewelry.owners` — مالکان
`jewelry.owners.view` · `.create` · `.update` · `.delete`

### `jewelry.transfers` — انتقال مالکیت
`jewelry.transfers.view` · `.create` · `.approve` · `.reject` · `.cancel` · `.delete`

### `jewelry.repairs` — تعمیرات
`jewelry.repairs.view` · `.create` · `.assign` · `.update` · `.update_status` · `.delete`

### `jewelry.missing_reports` — مفقودی و سرقت
`jewelry.missing_reports.view` · `.create` · `.update_status` · `.print`

### `jewelry.reports` — گزارش‌ها
`jewelry.reports.view` · `jewelry.reports.export`

### `jewelry.dashboard` — داشبورد
`jewelry.dashboard.view`

### فروشندگان و مالی
`sellers.view` · `sellers.approve` · `withdraw_requests.view` · `withdraw_requests.approve` · `sales.reports.view` · `coupons.manage`

### محتوا و ظاهر
`pages.manage` · `seo.manage` · `banners.manage` · `sliders.manage` · `languages.manage` · `policies.manage`

### `settings` — تنظیمات
`settings.view` · `settings.update`

> فهرست مرجع در کد: `app/Services/Rbac/PermissionRegistry.php`

---

## نحوه اعمال در کد

### ۱. در سطح مسیر — لایه اصلی

```php
Route::get('admin/articles/{id}/edit', 'Admin\\ArticleController@edit')
    ->name('admin.articles.edit')
    ->middleware('permission:articles.update');
```

چند مجوز با کاما یعنی **یا** (OR):

```php
->middleware('permission:articles.update,articles.create')
```

برای **و** (AND) از میدل‌ور دیگر استفاده کنید:

```php
->middleware('permission_all:articles.update,articles.publish')
```

### ۲. در ویو — پنهان‌سازی رابط کاربری

```blade
@can('articles.publish')
    <button type="submit">انتشار</button>
@endcan
```

> ⚠ **پنهان‌کردن دکمه، امنیت نیست.** کسی که آدرس را بداند می‌تواند درخواست
> را مستقیم بفرستد. لایه مسیر باید مستقل از رابط کاربری، درخواست را رد کند.

### ۳. در کنترلر — بررسی رکورد مشخص

```php
public function update(Request $request, Article $article)
{
    $this->authorize('update', $article);   // Policy
    // ...
}
```

### ۴. Gateهای پویا

برای هر مجوز موجود در دیتابیس، به‌طور خودکار یک Gate با همان نام ساخته می‌شود
(در `AuthServiceProvider`). پس هر جا `@can('jewelry.transfers.approve')`
بنویسید، مجوز از دیتابیس خوانده می‌شود.

### ۵. Gate ویژه‌کار

| Gate | شرط |
|------|-----|
| `ownership.transfer.approve-distinct` | تأییدکننده انتقال باید با درخواست‌دهنده **متفاوت** باشد |

---

## پنل مدیریت دسترسی

| مسیر | کاربرد | مجوز لازم |
|------|--------|-----------|
| `/admin/roles` | فهرست نقش‌ها | `roles.view` |
| `/admin/roles/create` | ساخت نقش جدید | `roles.create` |
| `/admin/roles/{id}/edit` | ویرایش مجوزهای نقش | `roles.update` |
| `/admin/roles/{id}` | جزئیات نقش | `roles.view` |
| `/admin/users` | فهرست کاربران | `users.view` |
| `/admin/users/{id}/roles` | دادن/گرفتن نقش | `roles.assign` |
| `/admin/permissions` | فهرست مجوزها | `permissions.view` |
| `/admin/audit-logs` | گزارش رخدادهای امنیتی | `audit_logs.view` |

در صفحه ویرایش نقش، مجوزها در قالب «ماتریس مجوز» و گروه‌بندی‌شده نمایش
داده می‌شوند. نقش‌های سیستمی (`is_system = 1`) قابل حذف نیستند.

---

## قواعد امنیتی

### Deny by Default

اگر مجوزی مشخص نشده باشد، درخواست **رد** می‌شود — نه اینکه عبور کند.

```php
// اگر لیست مجوزها خالی باشد:
if (empty($required)) {
    return $this->deny(...);   // رد، نه عبور
}
```

### تفکیک وظایف

| قاعده | پیاده‌سازی |
|-------|-----------|
| انتشار مقاله مجوز جدا از ویرایش | `articles.update` در برابر `articles.publish` |
| تأیید انتقال مجوز جدا از درخواست | `jewelry.transfers.request` در برابر `jewelry.transfers.approve` |
| تأییدکننده ≠ درخواست‌دهنده | Gate `ownership.transfer.approve-distinct` |
| تغییر وضعیت تعمیر جدا از اختصاص | `jewelry.repairs.assign` در برابر `jewelry.repairs.update_status` |

### جلوگیری از اعطای دسترسی بالاتر

1. **مدیریت نقش‌ها** فقط با مجوز `roles.*` ممکن است — که تنها
   `super_admin` دارد.
2. **کاربر نمی‌تواند به خودش نقش بدهد.** بررسی در `UserRoleController`.
3. **نقش با سطح بالاتر از سطح خود فرد** قابل اعطا نیست.
4. **نقش «مدیر کل سیستم» از پنل قابل انتساب نیست** — تنها از طریق دستور
   `drikana:make-super-admin`.

### ثبت رخدادهای حساس

هر تغییر نقش، تغییر مجوز نقش، تغییر وضعیت کاربر و هر رد دسترسی در
`audit_logs` ثبت می‌شود:

```
role.assigned              نقش به کاربر داده شد
role.revoked               نقش از کاربر گرفته شد
role.created               نقش جدید ساخته شد
role.permissions.updated   مجوزهای نقش تغییر کرد
role.deleted               نقش حذف شد
security.access.denied     تلاش دسترسی غیرمجاز
```

برای مشاهده: `/admin/audit-logs`

### کش مجوزها

مجوزهای مؤثر هر کاربر برای ۱۸۰۰ ثانیه کش می‌شود. پس از هر تغییر
نقش/مجوز باید کش پاک شود:

```php
$user->flushPermissionCache();        // یک کاربر
User::flushAllPermissionCaches();     // همه کاربران
```

این کار به‌طور خودکار در کنترلرهای RBAC انجام می‌شود، اما اگر از
`artisan tinker` یا ابزار بیرونی تغییر دادید، دستی اجرا کنید.

---

## ساخت نخستین مدیر کل سیستم

سیدر و فایل ارتقای SQL **هیچ کاربری نمی‌سازند** و هیچ رمزی تعیین نمی‌کنند.
این عمدی است تا رمز پیش‌فرض در کد یا دیتابیس نماند.

```bash
php artisan drikana:make-super-admin
```

دستور به‌صورت تعاملی ایمیل و رمز را می‌پرسد. رمز باید:

- دست‌کم ۱۲ کاراکتر باشد؛
- ترکیبی از حروف بزرگ و کوچک، رقم و نشانه باشد؛
- در جای دیگری استفاده نشده باشد.

### اجرای غیرتعاملی

برای اسکریپت‌های استقرار، رمز را در یک **فایل** بگذارید (توصیه‌شده):

```bash
# ساخت فایل رمز با مجوز محدود — هیچ‌کس جز خودتان نتواند بخواند
printf '%s\n' 'یک-رمز-قوی-و-یکتا' > /root/drikana-admin.pw
chmod 600 /root/drikana-admin.pw

php artisan drikana:make-super-admin \
    --email=admin@example.com \
    --name='مدیر کل' \
    --password-file=/root/drikana-admin.pw \
    --force

# پاک‌کردن فایل بلافاصله پس از اجرا
shred -u /root/drikana-admin.pw
```

اگر فایل برای گروه/دیگران خواندنی باشد، دستور هشدار می‌دهد. اگر فایل
وجود نداشته باشد یا خالی باشد، دستور با کد خروج ۱ **متوقف می‌شود** و
به پرسش تعاملی برنمی‌گردد — تا اسکریپت خودکار بی‌صدا روی رمز دیگری
متوقف نشود.

راه دوم (متغیر محیطی) راحت‌تر ولی کم‌امن‌تر است، چون متغیر محیطی در
فهرست فرایندهای سیستم قابل دیدن است:

```bash
DRIKANA_ADMIN_EMAIL=admin@example.com \
DRIKANA_ADMIN_PASSWORD='یک-رمز-قوی' \
php artisan drikana:make-super-admin --force
```

> ⚠ پس از اجرا، مقدار `DRIKANA_ADMIN_PASSWORD` را از تاریخچۀ فرمان‌ها پاک
> کنید (`history -c`) و در فایل `.env` ذخیره نکنید.

### رمزهایی که رد می‌شوند

| قاعده | نمونه رد‌شده |
|-------|-------------|
| کوتاه‌تر از ۱۲ کاراکتر | `Short1!` |
| رمزهای رایج | `password123`, `qwerty`, `admin123` |
| تنوع کاراکتر کمتر از سه نوع | `abcdefgh1234` (تنها حروف کوچک و رقم) |

تنوع یعنی حداقل سه مورد از: حرف کوچک، حرف بزرگ، رقم، نماد.

نقش‌های بعدی را از پنل `/admin/roles` بسازید و از `/admin/users/{id}/roles`
به کاربران بدهید.

---

## افزودن مجوز جدید

### گام ۱ — تعریف در رجیستری

`app/Services/Rbac/PermissionRegistry.php`:

```php
'jewelry.insurance' => [
    'title'  => 'بیمه اشیاء قیمتی',
    'group'  => 'jewelry',
    'actions' => [
        'view'   => 'مشاهده بیمه‌نامه',
        'create' => 'ثبت بیمه‌نامه',
        'update' => 'ویرایش بیمه‌نامه',
    ],
],
```

### گام ۲ — اجرای سیدر

```bash
php artisan db:seed --class=RbacSeeder --force
```

> سیدر اصلی (`php artisan db:seed`) هم این کار را انجام می‌دهد: ابتدا
> `RbacSeeder` و سپس `DrikanaCoreInstallSeeder` را به ترتیب اجرا می‌کند.
> داده‌های نمونه تنها با پرچم `DRIKANA_SEED_DEMO_DATA=true` ساخته
> می‌شوند.

سیدر مجوزهای تازه را می‌سازد **بدون پاک‌کردن** انتساب‌هایی که از پنل
داده‌اید.

### گام ۳ — اعمال روی مسیر

```php
Route::get('insurance/{id}', 'Jewelry\InsuranceController@show')
    ->middleware('permission:jewelry.insurance.view');
```

### گام ۴ — دادن مجوز به نقش

از پنل `/admin/roles/{id}/edit` یا با کد:

```php
$role = Role::where('slug', 'ownership_manager')->first();
$role->permissions()->syncWithoutDetaching([
    Permission::where('slug', 'jewelry.insurance.view')->first()->id,
]);
User::flushAllPermissionCaches();
```

### گام ۵ — به‌روزرسانی فایل ارتقای SQL

مجوز جدید را به بخش ۴-۱ فایل `database/sql/platform_upgrade.sql` هم
بیفزایید تا نصب‌های تازه آن را داشته باشند.

---

## عیب‌یابی

| نشانه | علت محتمل | راه‌حل |
|-------|-----------|--------|
| کاربر با نقش درست، ۴۰۳ می‌گیرد | کش مجوز | `php artisan cache:clear` |
| ۴۰۴ گرفتن در ناحیه /admin | کاربر نقش مدیریتی ندارد | بررسی `roleSlugs()` |
| مجوز در پنل نمایش داده نمی‌شود | مجوز در دیتابیس نیست | اجرای `RbacSeeder` |
| نقش قابل حذف نیست | نقش سیستمی است | `is_system` را از پنل قابل تغییر نیست — عمدی |
| `route('x')` به آدرس اشتباه می‌رود | نام روت تکراری | `phpunit --filter RouteIntegrityTest` |
