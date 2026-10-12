#!/usr/bin/env bash
# ══════════════════════════════════════════════════════════════════════
#  تست سلامت همه صفحات — سایت عمومی و پنل مدیریت
#  استفاده: bash tools/health_check.sh [BASE_URL]
# ══════════════════════════════════════════════════════════════════════
set -u

BASE="${1:-http://127.0.0.1:8000}"
COOKIE_PUBLIC=/tmp/hc_public.txt
COOKIE_ADMIN=/tmp/hc_admin.txt
rm -f "$COOKIE_PUBLIC" "$COOKIE_ADMIN"

PASS=0; FAIL=0; WARN=0
FAILED_LIST=()

# ── رنگ‌ها
G=$'\033[32m'; R=$'\033[31m'; Y=$'\033[33m'; N=$'\033[0m'

# check <مسیر> <کدهای مجاز> <برچسب>
check() {
    local path="$1" allowed="$2" label="$3" jar="${4:-$COOKIE_PUBLIC}"
    local code
    code=$(curl -s -o /tmp/hc_body.html -w '%{http_code}' -b "$jar" -c "$jar" \
                --max-time 25 "$BASE$path" 2>/dev/null)
    local size
    size=$(stat -c%s /tmp/hc_body.html 2>/dev/null || echo 0)

    if [[ ",$allowed," == *",$code,"* ]]; then
        # بررسی خطای PHP در بدنه (۵۰۰ پنهان با کد ۲۰۰ رخ نمی‌دهد، اما استثناها بله)
        if grep -qiE "Fatal error|Parse error|Uncaught (Exception|Error)" /tmp/hc_body.html 2>/dev/null; then
            printf "  ${R}✗ %-46s %s${N}  خطای PHP در بدنه  (size=%s)\n" "$label" "$code" "$size"
            FAIL=$((FAIL+1)); FAILED_LIST+=("$label [$code] خطای PHP")
        elif [[ "$size" -lt 200 ]]; then
            printf "  ${Y}⚠ %-46s %s${N}  بدنه خیلی کوچک (size=%s)\n" "$label" "$code" "$size"
            WARN=$((WARN+1))
        else
            printf "  ${G}✓ %-46s %s${N}  (%s بایت)\n" "$label" "$code" "$size"
            PASS=$((PASS+1))
        fi
    else
        printf "  ${R}✗ %-46s %s${N}  انتظار: %s\n" "$label" "$code" "$allowed"
        FAIL=$((FAIL+1)); FAILED_LIST+=("$label [$code]")
    fi
}

# expect_redirect <مسیر> <برچسب>  — بازدیدکننده مهمان باید به ورود هدایت شود
expect_redirect() {
    local path="$1" label="$2"
    local code
    code=$(curl -s -o /dev/null -w '%{http_code}' --max-time 25 "$BASE$path" 2>/dev/null)
    if [[ "$code" == "302" || "$code" == "301" || "$code" == "404" ]]; then
        printf "  ${G}✓ %-46s %s${N}  (مهمان → محافظت‌شده)\n" "$label" "$code"
        PASS=$((PASS+1))
    else
        printf "  ${R}✗ %-46s %s${N}  باید مهمان را رد کند\n" "$label" "$code"
        FAIL=$((FAIL+1)); FAILED_LIST+=("$label [$code] محافظت‌نشده")
    fi
}

section() { echo; echo "══ $1 ══"; }

echo "══════════════════════════════════════════════════════════════════"
echo "  تست سلامت — $BASE"
echo "  زمان: $(date '+%Y-%m-%d %H:%M:%S')"
echo "══════════════════════════════════════════════════════════════════"

# ══════════════════════════════════════════════════════════════════
section "۱) سایت عمومی"
# ══════════════════════════════════════════════════════════════════
# مسیرهای زیر از فهرست واقعی روت‌ها گرفته شده‌اند
# (tools/health_check.sh پیش‌تر چند مسیر حدسی داشت: /brands/all،
#  /categories/all، /flash-deals، /sellers، /privacy-policy،
#  /return-policy و /support-policy که در این پروژه وجود ندارند.)
check "/"                     "200" "صفحه اصلی"
check "/articles"             "200" "فهرست مقالات"
check "/articles/search?q=%D8%B7%D9%84%D8%A7" "200" "جست‌وجوی مقالات (فارسی)"
check "/login"                "200" "ورود کاربران"
check "/register"             "200" "ثبت‌نام"
check "/cart"                 "200" "سبد خرید"
check "/products"             "200" "فهرست محصولات"
check "/products/featured"    "200" "محصولات ویژه"
check "/products/new"         "200" "جدیدترین محصولات"
check "/products/best-selling" "200" "پرفروش‌ترین‌ها"
check "/products/discounted"  "200" "تخفیف‌دارها"
check "/products/todays-deal" "200" "پیشنهاد امروز"
check "/brands"               "200" "همه برندها"
check "/categories"           "200" "همه دسته‌بندی‌ها"
check "/search?q=gold"        "200" "جست‌وجو (انگلیسی)"
check "/search?q=%D8%B7%D9%84%D8%A7" "200" "جست‌وجو (فارسی)"
check "/privacypolicy"        "200" "حریم خصوصی"
check "/terms"                "200" "شرایط استفاده"
check "/returnpolicy"         "200" "سیاست بازگشت"
check "/supportpolicy"        "200" "سیاست پشتیبانی"
check "/sellerpolicy"         "200" "سیاست فروشندگان"
check "/track_your_order"     "200,302" "پیگیری سفارش"
check "/support_ticket/create" "200,302" "ثبت تیکت پشتیبانی"
check "/sitemap.xml"          "200" "نقشه سایت"
check "/robots.txt"           "200" "robots.txt"
check "/favicon.ico"          "200" "favicon"
check "/nonexistent-page-xyz" "404" "صفحه ناموجود (باید ۴۰۴)"
check "/vendor"               "404" "فایل حساس vendor (باید ۴۰۴)"
check "/.env"                 "403,404" "دسترسی .env (باید بسته)"

# ══════════════════════════════════════════════════════════════════
section "۲) ماژول جواهرات (عمومی)"
# ══════════════════════════════════════════════════════════════════
for p in /jewelry/certificate-service /jewelry/certificate-verify \
         /jewelry/report-stolen /jewelry/owners /jewelry/track; do
    check "$p" "200,302,404" "jewelry: $p"
done

# ══════════════════════════════════════════════════════════════════
section "۳) احراز هویت"
# ══════════════════════════════════════════════════════════════════
check "/password/reset"       "200" "فرم بازیابی رمز"

# ══════════════════════════════════════════════════════════════════
section "۴) پنل مدیریت — دسترسی مهمان باید بسته باشد"
# ══════════════════════════════════════════════════════════════════
for p in /admin /admin/dashboard /admin/users /admin/products /admin/orders \
         /admin/roles /admin/permissions /admin/articles /admin/audit-logs \
         /admin/jewelry/certificates /admin/settings; do
    expect_redirect "$p" "admin: $p (مهمان)"
done

# ══════════════════════════════════════════════════════════════════
section "۵) API"
# ══════════════════════════════════════════════════════════════════
check "/api/v1/products"           "200,401" "API: فهرست محصولات"
check "/api/v1/products/featured" "200,401" "API: محصولات ویژه"
check "/api/v1/banners"           "200"     "API: بنرها"
check "/api/v1/policies/return"   "200"     "API: سیاست بازگشت"
check "/api/v1/auth/login"        "200,405" "API: ورود (GET → 405)"

# ══════════════════════════════════════════════════════════════════
section "۶) فایل‌های ایستا (CSS/JS)"
# ══════════════════════════════════════════════════════════════════
for f in /css/drikana/utilities.css /css/drikana/store.css \
         /css/drikana/admin.css /css/drikana/articles.css \
         /css/drikana/ownership.css /css/drikana/repair.css \
         /frontend/css/slick.css /frontend/js/main.js /css/bootstrap.min.css; do
    check "$f" "200" "static: $f"
done

# ══════════════════════════════════════════════════════════════════
echo
echo "══════════════════════════════════════════════════════════════════"
echo "  ✓ موفق: ${PASS}   ✗ ناموفق: ${FAIL}   ⚠ هشدار: ${WARN}"
echo "══════════════════════════════════════════════════════════════════"

if [[ ${#FAILED_LIST[@]} -gt 0 ]]; then
    echo
    echo "  موارد ناموفق:"
    for item in "${FAILED_LIST[@]}"; do echo "    • $item"; done
fi

exit $(( FAIL > 0 ? 1 : 0 ))
