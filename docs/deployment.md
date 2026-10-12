# راهنمای نصب و استقرار

> این سند مسیر کامل «از مخزن خالی تا سامانه فعال» را می‌پیماید: پیش‌نیازها،
> نصب، دو مسیر ارتقای دیتابیس، ساخت نخستین مدیر کل، پیکربندی وب‌سرور و
> اقدام‌های امنیتی الزامی پس از استقرار.

## فهرست

1. [پیش‌نیازها](#پیشنیازها)
2. [نصب تازه](#نصب-تازه)
3. [تنظیم فایل محیطی](#تنظیم-فایل-محیطی)
4. [دو مسیر آماده‌سازی دیتابیس](#دو-مسیر-آمادهسازی-دیتابیس)
5. [ساخت نخستین مدیر کل سیستم](#ساخت-نخستین-مدیر-کل-سیستم)
6. [بازیابی سابقه مالکیت قطعات قدیمی](#بازیابی-سابقه-مالکیت-قطعات-قدیمی)
7. [پیکربندی وب‌سرور](#پیکربندی-وبسرور)
8. [کارایی و کش](#کارایی-و-کش)
9. [اقدام‌های امنیتی الزامی پس از استقرار](#اقدامهای-امنیتی-الزامی-پس-از-استقرار)
10. [پاک‌سازی تاریخچه Git](#پاکسازی-تاریخچه-git)
11. [پشتیبان‌گیری و بازگردانی](#پشتیبانگیری-و-بازگردانی)
12. [عیب‌یابی](#عیبیابی)

---

## پیش‌نیازها

| مورد | نسخه | توضیح |
|------|-------|-------|
| PHP | ۷.۱ تا ۷.۴ | پروژه روی Laravel 5.8 است؛ PHP 8 پشتیبانی نمی‌شود |
| افزونه‌های PHP | `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `curl`, `gd` | |
| MariaDB / MySQL | MariaDB ۱۰.۴+ یا MySQL ۵.۷+ | کد و مهاجرت‌ها برای هر دو سازگارند |
| Composer | ۲.x | |
| وب‌سرور | Nginx یا Apache | با ریشه در پوشه `public/` |
| Node.js | فقط اگر می‌خواهید دارایی‌های ظاهری را دوباره بسازید | فایل‌های تولیدشده در مخزن موجودند |

بررسی افزونه‌های PHP:

```bash
php -m | grep -E 'bcmath|ctype|fileinfo|mbstring|openssl|pdo_mysql|tokenizer|xml|curl|gd'
```

---

## نصب تازه

```bash
# ۱) دریافت کد
git clone https://github.com/mrtz8bgy/laravel_drikana.git drikana
cd drikana
git checkout develop/refactor-rbac

# ۲) نصب وابستگی‌های PHP
#    نکته: `composer install` همیشه از فایل قفل (`composer.lock`) می‌خواند،
#    پس دقیقاً همان نسخه‌های آزموده‌شده نصب می‌شوند. هرگز روی سرور
#    `composer update` اجرا نکنید؛ سه بسته پرداخت روی شاخه `dev-master`
#    قفل شده‌اند و به‌روزرسانی می‌تواند آن‌ها را به کامیت ناخواسته ببرد.
composer install --no-dev --optimize-autoloader

# ۳) فایل محیطی
cp .env.example .env
php artisan key:generate

# ۴) پوشه‌های قابل نوشتن
chmod -R 775 storage bootstrap/cache
# روی سرور اشتراکی، مالک را به کاربر وب‌سرور بدهید:
# chown -R www-data:www-data storage bootstrap/cache

# ۵) پیوند ذخیره‌سازی (برای تصاویر مقالات و پیوست‌ها)
php artisan storage:link

# ۶) ساختار دیتابیس — یکی از دو مسیر بخش بعد
php artisan migrate --force

# ۷) داده‌های پایه (نقش‌ها، مجوزها، تنظیمات)
php artisan db:seed --force
```

> ⚠ هیچ‌کدام از این گام‌ها کاربر یا رمز عبوری نمی‌سازد. ساخت نخستین
> مدیر در [بخش ۵](#ساخت-نخستین-مدیر-کل-سیستم) آمده است.

---

## تنظیم فایل محیطی

`.env.example` تنها مقادیر **نمونه و غیرحساس** دارد. هیچ رمز واقعی،
کلید درگاه یا توکنی در آن نیست. مقادیری که باید پر شوند:

### الزامی

| کلید | مقدار | توضیح |
|------|-------|-------|
| `APP_ENV` | `production` | روی سرور واقعی |
| `APP_DEBUG` | `false` | **حتماً `false`** — در غیر این صورت خطاها با جزئیات کامل نمایش داده می‌شوند |
| `APP_URL` | `https://example.com` | بدون `/` انتهایی |
| `APP_KEY` | خروجی `key:generate` | |
| `DB_*` | اطلاعات دیتابیس | کاربر دیتابیس را به این دیتابیس محدود کنید، نه `root` |
| `SESSION_SECURE_COOKIE` | `true` | روی HTTPS |
| `SESSION_ENCRYPT` | `true` | پیش‌فرض همین است |

### اختیاری (بر اساس قابلیت‌های فعال)

| کلید | کاربرد |
|------|--------|
| `DEMO_MODE` | `Off` روی سرور واقعی — `On` پنل اطلاعات ورود نمونه را نشان می‌دهد |
| `DRIKANA_SEED_DEMO_DATA` | فقط برای محیط نمایشی — داده نمونه محصول/مقاله می‌سازد |
| `DRIKANA_ALLOW_INSTALLER` | **هرگز روی سرور واقعی تنظیم نکنید** |
| `MAIL_*` | ارسال ایمیل |
| کلیدهای درگاه پرداخت | زرین‌پال، SSLCommerz و ... |
| کلیدهای پنل پیامک | FarazSMS، Melipayamak، Twilio |

مجوز فایل محیطی:

```bash
chmod 600 .env
chown www-data:www-data .env
```

> ✅ **`php artisan config:cache` اکنون بی‌خطر است.**
> پیش‌تر ۷۵ فراخوانی `env()` در کد برنامه وجود داشت و با کش‌شدن
> تنظیمات، همه `null` برمی‌گشتند (شکست بی‌صدا در پیامک OTP و
> درگاه‌های پرداخت). اکنون تمام این مقادیر در `config/drikana.php`
> (و `config/services.php`، `config/mail.php`) تعریف شده‌اند.
> جزئیات: مورد M-09 در `docs/security-audit.md`.
>
> **تنها نکته:** اگر مقداری را از **پنل مدیریت** تغییر دهید (کد درگاه،
> SMTP، پیکسل رهگیری و ...)، در حالت کش‌شده یک بار کش را تازه کنید:
> `php artisan config:clear && php artisan config:cache`

---

## دو مسیر آماده‌سازی دیتابیس

### مسیر الف — نصب تازه با مهاجرت‌ها (توصیه‌شده)

اگر دیتابیس خالی است:

```bash
php artisan migrate --force
php artisan db:seed --force
```

نتیجه: ۷۴ جدول، ۶ نقش، ۸۶ مجوز، ۱۴۳ انتساب مجوز به نقش.

### مسیر ب — ارتقای دیتابیس موجود (سامانه در حال کار)

اگر دیتابیس قدیمی (قبل از این بازسازی) دارید و نمی‌خواهید داده‌ها از
دست برود:

```bash
# ۰) پشتیبان‌گیری اجباری — پیش از هر چیز
mysqldump -u USER -p DBNAME > backup-$(date +%F-%H%M).sql

# ۱) اجرای فایل ارتقا
mysql -u USER -p DBNAME < database/sql/platform_upgrade.sql

# ۲) تکمیل با مهاجرت‌های لاراول (روی همان دیتابیس)
php artisan migrate --force

# ۳) سیدر نقش‌ها و مجوزها (تکرارپذیر — داده موجود را خراب نمی‌کند)
php artisan db:seed --force
```

**فایل `database/sql/platform_upgrade.sql` چه می‌کند؟**

| مورد | تعداد |
|------|-------|
| جدول تازه | ۱۴ (`roles` توسعه‌یافته، `permissions`, `permission_role`, `role_user`, `audit_logs`, `ownership_history`, `articles`, `article_categories`, `article_images`, `repair_order_status_logs` و ...) |
| ستون افزوده‌شده | ۲۳ |
| ایندکس افزوده‌شده | ۱۰ |
| نقش اولیه | ۶ |
| مجوز اولیه | ۸۶ |
| انتساب نقش↔مجوز | ۱۴۳ |

**تضمین‌های ایمنی فایل ارتقا:**

- **هیچ داده‌ای حذف نمی‌شود.** تنها `CREATE TABLE IF NOT EXISTS`،
  `ALTER TABLE ... ADD COLUMN` و `INSERT` برای داده اولیه.
- **ترتیب ساخت رعایت شده** تا کلیدهای خارجی خطا ندهند.
- **تکرارپذیر است:** اجرای دوباره آن خطای «جدول موجود است» نمی‌دهد و
  داده تکراری نمی‌سازد.
- **انتساب‌های دستی مدیر حفظ می‌شوند:** سیدر مجوزها تنها موارد تازه را
  اضافه می‌کند و مجوزهایی که خودتان به نقش‌ها داده‌اید دست‌نخورده
  می‌مانند.
- **پیش از انتشار آزموده شد:** روی یک دیتابیس ۶۰ جدولی قدیمی اجرا و
  به ۷۴ جدول رسید؛ مسیر جایگزین (`artisan migrate` روی همان دیتابیس)
  هم همان نتیجه را داد.

> **نکته درباره اندازه بسته:** اگر دامپ بزرگ است و با خطای
> `max_allowed_packet` روبه‌رو شدید، ابتدا دامپ پایه را وارد کنید و
> بعد فایل ارتقا را جداگانه اجرا کنید (یا در `my.cnf` مقدار
> `max_allowed_packet=64M` را تنظیم کنید).

---

## ساخت نخستین مدیر کل سیستم

سیدر و فایل SQL **هیچ کاربری نمی‌سازند** و هیچ رمزی تعیین نمی‌کنند —
تا رمز پیش‌فرض در کد یا Git باقی نماند.

### روش تعاملی (توصیه‌شده)

```bash
php artisan drikana:make-super-admin
```

ایمیل، نام نمایشی و رمز را می‌پرسد. رمز باید:
- حداقل ۱۲ کاراکتر باشد،
- در فهرست رمزهای رایج نباشد،
- حداقل سه نوع از چهار نوع کاراکتر داشته باشد (حروف کوچک، بزرگ، رقم، نماد).

### روش غیرتعاملی (اسکریپت استقرار)

امن‌ترین شکل — رمز در یک فایل با مجوز محدود:

```bash
printf '%s\n' 'یک-رمز-قوی-و-یکتا' > /root/drikana-admin.pw
chmod 600 /root/drikana-admin.pw

php artisan drikana:make-super-admin \
    --email=admin@example.com \
    --name='مدیر کل' \
    --password-file=/root/drikana-admin.pw \
    --force

shred -u /root/drikana-admin.pw
```

اگر فایل وجود نداشته باشد یا خالی باشد، دستور با کد خروج ۱ متوقف
می‌شود و به پرسش تعاملی **برنمی‌گردد** — تا اسکریپت خودکار بی‌صدا
متوقف نشود.

راه جایگزین (کم‌امن‌تر، چون در فهرست فرایندها دیده می‌شود):

```bash
DRIKANA_ADMIN_EMAIL=admin@example.com \
DRIKANA_ADMIN_PASSWORD='یک-رمز-قوی' \
php artisan drikana:make-super-admin --force
```

پس از ساخت، از پنل `/admin/roles` نقش بسازید و از
`/admin/users/{id}/roles` به کاربران بدهید. راهنمای کامل:
`docs/roles-and-permissions.md`.

---

## بازیابی سابقه مالکیت قطعات قدیمی

قطعاتی که **پیش از** راه‌اندازی دفتر کل مالکیت ثبت شده بودند، رکورد
مالکیت اولیه ندارند. بدون آن، صفحه «سابقه مالکیت» این قطعات خالی است.

```bash
# ۱) اول به‌صورت آزمایشی — هیچ تغییری در دیتابیس انجام نمی‌شود
php artisan drikana:backfill-ownership-history --dry-run

# ۲) اجرای واقعی
php artisan drikana:backfill-ownership-history

# برای دیتابیس‌های بزرگ، اندازه دسته را کنترل کنید
php artisan drikana:backfill-ownership-history --chunk=200
```

دستور برای هر شناسنامه بدون سابقه، یک ردیف `sequence_no = 1` با
`event_type = 'initial'` می‌سازد. شناسنامه‌های دارای سابقه دست‌نخورده
می‌مانند، بنابراین **اجرای دوباره بی‌خطر است**.

> قطعات تازه به این دستور نیازی ندارند: `JewelryCertificateObserver`
> هنگام ساخت شناسنامه، رکورد مالکیت اولیه را خودکار ثبت می‌کند.

---

## پیکربندی وب‌سرور

### Nginx

```nginx
server {
    listen 80;
    server_name example.com;
    # در محیط عملیاتی، همه ترافیک را به HTTPS هدایت کنید
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name example.com;

    ssl_certificate     /etc/letsencrypt/live/example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;

    root /var/www/drikana/public;   # ⚠ ریشه وب باید پوشه public باشد
    index index.php;

    # فشرده‌سازی
    gzip on;
    gzip_types text/css application/javascript image/svg+xml;

    # اندازه آپلود — باید با محدودیت اعتبارسنجی اپلیکیشن هم‌خوان باشد
    client_max_body_size 8M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include        fastcgi_params;
        fastcgi_pass   unix:/run/php/php7.4-fpm.sock;
        fastcgi_param  SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param  DOCUMENT_ROOT   $realpath_root;
    }

    # ⚠ فایل‌های حساس هرگز نباید از وب قابل خواندن باشند
    location ~ /\.(?!well-known).* { deny all; }
    location ~* \.(env|log|sql|md|json|lock|ini)$ { deny all; }

    # جلوگیری از اجرای اسکریپت در پوشه آپلود
    # (لایه سوم دفاع؛ لایه‌های اول و دوم اعتبارسنجی و نام‌گذاری در کد است)
    location ^~ /storage/ {
        location ~ \.php$ { deny all; }
    }

    location ~* \.(jpg|jpeg|png|gif|webp|svg|ico|css|js|woff2?|ttf)$ {
        expires 30d;
        access_log off;
        add_header Cache-Control "public, immutable";
    }
}
```

### Apache

سند موجود در `public/.htaccess` بازنویسی مسیر را انجام می‌دهد. نکات:

```apache
# ریشه سند را به public/ تنظیم کنید
DocumentRoot /var/www/drikana/public

<Directory /var/www/drikana>
    AllowOverride None
</Directory>

<Directory /var/www/drikana/public>
    AllowOverride All
    Require all granted
</Directory>
```

افزودن به `.htaccess` ریشه پروژه (خارج از `public`) برای مسدودکردن
دسترسی مستقیم:

```apache
# /var/www/drikana/.htaccess
Require all denied
```

### مجوز پوشه‌ها

```bash
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## کارایی و کش

| دستور | اجرا شود؟ | توضیح |
|-------|-----------|-------|
| `php artisan config:cache` | ✅ | اکنون بی‌خطر است (مورد M-09 رفع شد) |
| `php artisan route:cache` | ✅ | روت‌ها ثابت هستند |
| `php artisan view:cache` | ✅ | ویوها را پیش‌کامپایل می‌کند |
| `php artisan cache:clear` | در صورت نیاز | پس از تغییر مجوزها/نقش‌ها از پنل لازم نیست (کش خودکار پاک می‌شود) |

**ترتیب پیشنهادی روی سرور عملیاتی:**

```bash
php artisan config:clear          # پاک‌سازی کش قبلی، پیش از هر چیز
php artisan config:cache          # پیکربندی
php artisan route:cache           # مسیرها
php artisan view:cache            # ویوها
```

> هر بار که `.env` یا فایل‌های `config/` را دستی تغییر دادید، همین
> سه دستور را تکرار کنید. اگر مقداری را از **پنل مدیریت** تغییر دادید
> (پنل، مقدار را در `.env` می‌نویسد)، تنها `config:cache` را دوباره
> اجرا کنید تا مقدار تازه خوانده شود.

> اگر پس از `route:cache` تغییری در `routes/` دادید، باید
> `php artisan route:clear` و سپس `route:cache` را اجرا کنید.

در صورت تنظیم `QUEUE_CONNECTION=database` یک صف کار لازم است:

```bash
* * * * * cd /var/www/drikana && php artisan schedule:run >> /dev/null 2>&1
```

---

## اقدام‌های امنیتی الزامی پس از استقرار

این موارد **اختیاری نیستند**. فهرست کامل در
`docs/security-audit.md` آمده است.

1. **`APP_DEBUG=false`** — بزرگ‌ترین افشای اطلاعات از یک تنظیم ساده.
2. **`APP_KEY` تازه** — با `php artisan key:generate`.
3. **چرخش تمام اعتبارنامه‌های افشاشده در Git** (بخش بعدی).
4. **اجبار HTTPS** — در Nginx یا Apache.
5. **پشتیبان‌گیری خودکار روزانه** از دیتابیس و پوشه `storage/`.
6. **مجوز `600` برای `.env`.**
7. **`DRIKANA_ALLOW_INSTALLER` تنظیم نشده باشد** — میدل‌ور
   `not_installed` روی ۸ مسیر `/install/*` و `/update/*` پاسخ ۴۰۴
   می‌دهد.
8. **نخستین مدیر کل را با دستور بسازید**، نه با رمز پیش‌فرض.
8.5. **`config:cache`، `route:cache` و `view:cache` را اجرا کنید** (سود کارایی).
9. **جایگاه مدیر کل را محدود کنید** — هر حساب `user_type = admin`
   دسترسی کامل دارد.
10. **گزارش حسابرسی را بررسی کنید** — `/admin/audit-logs`.

---

### پوشه بارگذاری: اجرای PHP را ببندید

فایل‌های بارگذاری‌شده زیر `public/uploads/` ذخیره می‌شوند (ریشه دیسک
`local` در `config/filesystems.php` برابر `public_path()` است). کد
برنامه اعتبارسنجی نوع فایل را انجام می‌دهد (مورد M-12 در گزارش امنیت:
`mimes` + `dimensions` + سقف حجم)، اما «لایه دوم» را در وب‌سرور هم
فعال کنید تا فایلی که از فیلتر رد شده، هرگز اجرا نشود:

**Apache** — فایل `public/uploads/.htaccess`:

```apache
# هیچ فایلی در این پوشه اجرا نشود — فقط سرو شود
<FilesMatch "\.(php|phtml|phar|php[0-9]|cgi|pl|py|sh)$">
    Require all denied
</FilesMatch>
php_flag engine off
```

**Nginx** — در بلوک `server`:

```nginx
location ^~ /uploads/ {
    location ~ \.(php|phtml|phar|cgi|pl|py|sh)$ { deny all; }
    try_files $uri =404;
}
```

نکته: پیش از افزودن این قواعد، مطمئن شوید هیچ قابلیت پروژه به اجرای
اسکریپت داخل `uploads/` وابسته نیست — هیچ‌کدام از قابلیت‌ها چنین
وابستگی‌ای ندارند (فقط تصویر و فایل بارگذاری‌شده).

---

## پاک‌سازی تاریخچه Git

فایل‌های `.env` در گذشته در مخزن ثبت شده بودند. خروج آن‌ها از ردیابی
کافی نیست؛ مقادیر در تاریخچه باقی می‌مانند.

### روش ۱ — پاک‌سازی با `git filter-repo` (توصیه‌شده)

```bash
# نصب
pip install git-filter-repo

# پشتیبان کامل پیش از هر کار
git clone --mirror https://github.com/mrtz8bgy/laravel_drikana.git drikana-backup.git

cd drikana
git filter-repo --invert-paths \
    --path .env \
    --path .env.gobox \
    --force

# بازگرداندن ریموت (filter-repo آن را حذف می‌کند)
git remote add origin https://github.com/mrtz8bgy/laravel_drikana.git
git push --force --all
git push --force --tags
```

### روش ۲ — ساخت مخزن تازه (ساده‌ترین و مطمئن‌ترین)

اگر تاریخچه ارزش نگه‌داشتن ندارد:

```bash
git checkout --orphan clean-main
git add -A
git commit -m "نسخه پاک‌سازی‌شده"
git branch -M clean-main main
git push --force origin main
```

### دستور اندازه‌گیری اعتبارنامه‌های افشاشده

```bash
# چه کامیت‌هایی فایل محیطی را لمس کرده‌اند؟
git log --all --oneline -- .env .env.gobox

# جست‌وجوی الگوهای حساس در کل تاریخچه
git grep -I -n -E '(ghp_|github_pat_|sk_live_|AKIA[0-9A-Z]{16})' $(git rev-list --all) || echo "یافت نشد"
```

> ⚠ **ترتیب مهم است:** ابتدا اعتبارنامه‌ها را **چرخش** دهید، بعد
> تاریخچه را پاک کنید. اگر برعکس عمل کنید، مقادیر قدیمی همچنان معتبر
> می‌مانند و کسی که کپی مخزن را دارد می‌تواند از آن‌ها استفاده کند.
>
> **همچنین:** همه همکاران باید پس از پاک‌سازی، مخزن را دوباره کلون
> کنند؛ `git pull` روی مخزن بازنویسی‌شده نتیجه درستی نمی‌دهد.

---

## پشتیبان‌گیری و بازگردانی

### پشتیبان‌گیری

```bash
#!/bin/bash
# backup.sh — با cron روزانه اجرا کنید
STAMP=$(date +%F-%H%M)
DIR=/var/backups/drikana
mkdir -p "$DIR"

mysqldump -u USER -pPASS --single-transaction --routines DBNAME | gzip > "$DIR/db-$STAMP.sql.gz"
tar -czf "$DIR/storage-$STAMP.tar.gz" -C /var/www/drikana storage/app

# نگه‌داشتن ۳۰ روز اخیر
find "$DIR" -type f -mtime +30 -delete
```

### بازگردانی

```bash
gunzip < db-2026-10-12-0300.sql.gz | mysql -u USER -pPASS DBNAME
tar -xzf storage-2026-10-12-0300.tar.gz -C /var/www/drikana
php artisan cache:clear
```

---

## عیب‌یابی

| نشانه | علت محتمل | راه‌حل |
|-------|-----------|--------|
| صفحه سفید یا ۵۰۰ بدون پیام | خطای PHP با `APP_DEBUG=false` | `tail -f storage/logs/laravel.log` |
| «No application encryption key» | `APP_KEY` خالی | `php artisan key:generate` |
| تصاویر آپلودشده ۴۰۴ می‌دهند | پیوند ذخیره‌سازی ساخته نشده | `php artisan storage:link` |
| خطای «could not find driver» | افزونه `pdo_mysql` نصب نیست | نصب افزونه PHP |
| پیامک OTP ارسال نمی‌شود | اعتبارنامه پنل پیامک در `.env` خالی است | کلیدهای `MELIPAYAMAK_*` / `FARAZSMS_*` را پر کنید |
| مقدار تازه پنل اثر نمی‌کند | کش پیکربندی قدیمی | `php artisan config:clear && php artisan config:cache` |
| مسیرها ۴۰۴ می‌دهند | کش روت قدیمی | `php artisan route:clear && php artisan route:cache` |
| «Permission denied» در `storage` | مجوز پوشه | `chmod -R 775 storage bootstrap/cache` |
| جدول‌ها ساخته نشده‌اند | مهاجرت اجرا نشده | `php artisan migrate --force` |
| نقش‌ها در پنل نیستند | سیدر اجرا نشده | `php artisan db:seed --class=RbacSeeder --force` |
| تغییر مجوز نقش اعمال نمی‌شود | کش مجوز | خودکار پاک می‌شود؛ در غیر این صورت `php artisan cache:clear` |
| خطای ۴۰۴ در `/install` | محافظ `not_installed` فعال است (درست است) | برای نصب موقت: `DRIKANA_ALLOW_INSTALLER=true` |
| `max_allowed_packet` هنگام import | بسته SQL بزرگ | افزایش مقدار در `my.cnf` یا import مرحله‌ای |
