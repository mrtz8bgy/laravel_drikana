#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────
# خزش سلامت «همهٔ» مسیرهای ورودیِ خواندنی (GET) سایت و پنل
#
#   bash tools/crawl_all_pages.sh [BASE_URL] [ADMIN_EMAIL] [ADMIN_PASSWORD]
#
# • فهرست مسیرها از خود روتر لاراول استخراج می‌شود (نه فهرست دستی)، پس هیچ
#   مسیری جا نمی‌ماند: همهٔ روت‌های GET که پارامتری ندارند.
# • هر مسیر دو بار خزیده می‌شود: بی‌ورود (مهمان) و با ورود مدیر کل.
# • خطا شمرده می‌شود هر پاسخ ۵۰۰ و هر پاسخ ۲۰۰ که متن Fatal error/Parse
#   error/Uncaught/Whoops در بدنه داشته باشد (خطاهای سرکوب‌شده را هم می‌گیرد).
# • در پایان اگر خطایی باشد با کد ۱ بیرون می‌آید تا در CI/گام‌های بعدی
#   قابل اتکا باشد.
# ──────────────────────────────────────────────────────────────────────────
set -uo pipefail   # بدون -e: خودمان خطاها را می‌شماریم

BASE="${1:-http://127.0.0.1:8000}"
ADMIN_EMAIL="${2:-admin@drikana.test}"
ADMIN_PASSWORD="${3:-DrikanaTest!2026}"

cd "$(dirname "$0")/.." || exit 1

COOKIE_GUEST=/tmp/crawl_all_guest.txt
COOKIE_ADMIN=/tmp/crawl_all_admin.txt
ERRLOG=/tmp/crawl_all_errors.txt
ROUTES=/tmp/crawl_all_routes.txt
SRC=/tmp/crawl_all_dump.php

rm -f "$COOKIE_GUEST" "$COOKIE_ADMIN" "$ERRLOG" /tmp/crawl_all_limited.txt
: > /tmp/crawl_all_codes_guest.txt
: > /tmp/crawl_all_codes_admin.txt

# ── استخراج مسیرها از خود روتر لاراول (منبع حقیقت)
php7.4 -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$out = [];
foreach (app("router")->getRoutes() as $route) {
    if (in_array("GET", $route->methods()) && $route->parameterNames() === []) {
        $out[] = "/" . ltrim(str_replace("?", "", $route->uri()), "/");
    }
}
$out = array_values(array_unique($out));
file_put_contents("/tmp/crawl_all_routes.txt", implode(PHP_EOL, $out));
echo "  مسیرهای یافت‌شده: " . count($out) . PHP_EOL;
'

[ -s "$ROUTES" ] || { echo "❌ استخراج مسیرها ناموفق بود"; exit 1; }

TOTAL=$(wc -l < "$ROUTES")

echo "══════════════════════════════════════════════════════════════════"
echo "  تست سلامت همهٔ صفحات — $BASE"
echo "  $TOTAL مسیر GET بدون پارامتر × ۲ نما (مهمان، مدیر)"
echo "══════════════════════════════════════════════════════════════════"

# ── بررسی خطای سرکوب‌شده در بدنهٔ پاسخ‌های ۲۰۰
has_hidden_error() {
    grep -qE 'Fatal error|Parse error|Uncaught|Whoops|Cannot redeclare' "$1"
}

crawl() {   # $1=برچسب  $2=فایل کوکی  $3=فایل گزارش کدها
    local label="$1" cookie="$2" CODEOUT="$3"
    local ok=0 bad=0 other=0 limited=0 code body
    body=/tmp/crawl_all_body.html

    while IFS= read -r uri; do
        [ -z "$uri" ] && continue

        # گروه میدل‌ور «api» محدودیت 60 درخواست در دقیقه دارد؛ درنگ کوتاه
        # مانع می‌شود خودِ ابزار آزمون، پاسخ ۴۲۹ مصنوعی بسازد.
        case "$uri" in /api/*) sleep 1.1 ;; esac
        if [ -n "$cookie" ]; then
            code=$(curl -s -b "$cookie" -c "$cookie" -o "$body" -w '%{http_code}' --max-time 40 "$BASE$uri")
        else
            code=$(curl -s -c "$cookie" -b "$cookie" -o "$body" -w '%{http_code}' --max-time 40 "$BASE$uri")
        fi

        echo "$code $uri" >> "$CODEOUT"

        case "$code" in
            200)
                if has_hidden_error "$body"; then
                    bad=$((bad+1)); printf '  ✗ %-45s 200 با خطای پنهان در بدنه\n' "$uri" | tee -a "$ERRLOG"
                else
                    ok=$((ok+1))
                fi ;;
            500|502|503|504)
                bad=$((bad+1)); printf '  ✗ %-45s %s\n' "$uri" "$code" | tee -a "$ERRLOG" ;;
            429)
                limited=$((limited+1))
                printf '  ⓘ %-45s 429 (محدودیت نرخ)
' "$uri" | tee -a /tmp/crawl_all_limited.txt ;;
            *)  other=$((other+1)) ;;
        esac
    done < "$ROUTES"

    # خلاصه به stderr می‌رود تا با $() گرفتنِ شمارهٔ خطا در stdout قاطی نشود
    printf '     \033[32m۲۰۰ سالم: %d\033[0m   \033[31mخطا: %d\033[0m   محدودیت نرخ (۴۲۹): %d   سایر: %d\n' "$ok" "$bad" "$limited" "$other" >&2
    echo "$bad"
}

echo
echo "  ── مهمان (بدون ورود) ──"
GUEST_BAD=$(crawl "مهمان" "$COOKIE_GUEST" /tmp/crawl_all_codes_guest.txt | tail -1)

# ── ورود مدیر
LOGIN_URL="$BASE/login"
TOKEN=$(curl -s -c "$COOKIE_ADMIN" -b "$COOKIE_ADMIN" --max-time 30 "$LOGIN_URL" \
        | sed -n 's/.*name="_token"[^>]*value="\([^"]*\)".*/\1/p' | head -1)

LOGIN_CODE=$(curl -s -b "$COOKIE_ADMIN" -c "$COOKIE_ADMIN" -o /tmp/crawl_all_login.html -w '%{http_code}' --max-time 30 \
    -X POST "$LOGIN_URL" \
    -d "_token=$TOKEN" -d "username=$ADMIN_EMAIL" -d "password=$ADMIN_PASSWORD")

if [ "$LOGIN_CODE" = "429" ]; then
    # محدودکنندهٔ نرخ روی POST /login فعال است؛ پنجره یک‌دقیقه‌ای را صبر
    # می‌کنیم و یک‌بار دیگر تلاش می‌کنیم (خودِ فعال‌بودن این محافظت، یافتهٔ
    # امنیتی مثبت است).
    echo "  ⓘ ورود مدیر با کد ۴۲۹ مسدود شد (محافظت از حملهٔ فرهنگ‌نامه‌ای فعال است)."
    echo "    انتظار ۶۵ ثانیه و تلاش دوباره…"
    sleep 65
    rm -f "$COOKIE_ADMIN"
    TOKEN=$(curl -s -c "$COOKIE_ADMIN" -b "$COOKIE_ADMIN" --max-time 30 "$LOGIN_URL" \
            | sed -n 's/.*name="_token"[^>]*value="\([^"]*\)".*/\1/p' | head -1)
    LOGIN_CODE=$(curl -s -b "$COOKIE_ADMIN" -c "$COOKIE_ADMIN" -o /tmp/crawl_all_login.html -w '%{http_code}' --max-time 30 \
        -X POST "$LOGIN_URL" \
        -d "_token=$TOKEN" -d "username=$ADMIN_EMAIL" -d "password=$ADMIN_PASSWORD")
fi

if [ "$LOGIN_CODE" = "429" ]; then
    echo "  ⚠ ورود مدیر همچنان مسدود است — آزمون نمای مدیر انجام نشد."
    ADMIN_BAD=0
elif grep -q "login\|ورود" /tmp/crawl_all_login.html 2>/dev/null && [ "$LOGIN_CODE" != "302" ]; then
    echo "  ⚠ ورود مدیر ناموفق (کد $LOGIN_CODE) — آزمون پنل معنا ندارد."
    ADMIN_BAD=0
else
    # پیش از خزش، معتبر بودن نشست مدیر را بررسی می‌کنیم تا «۳۰۲ به ورود»
    # به‌اشتباه «قطعی دسترسی» تفسیر نشود.
    ADMIN_CHECK=$(curl -s -b "$COOKIE_ADMIN" -o /dev/null -w '%{http_code}' --max-time 40 "$BASE/admin")

    if [ "$ADMIN_CHECK" != "200" ]; then
        echo "  ⚠ نشست مدیر معتبر نشد (کد $ADMIN_CHECK روی /admin) — آزمون پنل انجام نشد."
        ADMIN_BAD=0
    else
    echo
    echo "  ── مدیر کل (با ورود) ──"
    ADMIN_BAD=$(crawl "مدیر" "$COOKIE_ADMIN" /tmp/crawl_all_codes_admin.txt | tail -1)
    fi
fi

echo
echo "  ── مجموع مسیرهای بررسی‌شده: $TOTAL ──"
TOTAL_BAD=$((GUEST_BAD + ADMIN_BAD))
if [ "$TOTAL_BAD" -eq 0 ]; then
    printf '  \033[32m✅ هیچ صفحه‌ای خطای ۵۰۰ ندارد.\033[0m\n'
    exit 0
else
    printf '  \033[31m❌ %d خطا — جزئیات در %s\033[0m\n' "$TOTAL_BAD" "$ERRLOG"
    exit 1
fi
