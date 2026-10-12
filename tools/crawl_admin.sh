#!/usr/bin/env bash
# ══════════════════════════════════════════════════════════════════════
#  خزش همه صفحات پنل مدیریت با ورود واقعی
#  هر صفحه GET با کد ۵۰۰ = خطای واقعی (صفحه‌ای که مدیر نمی‌تواند ببیند)
#  استفاده: bash tools/crawl_admin.sh <USERNAME-OR-EMAIL> <PASSWORD> [BASE_URL]
#
#  توجه: فرم ورود این پروژه فیلد `username` می‌گیرد (نه `email`) و مقدار
#  آن می‌تواند ایمیل یا نام کاربری باشد — LoginController::username()
#  بر پایه همین ورودی، ستون درست را انتخاب می‌کند.
# ══════════════════════════════════════════════════════════════════════
set -u

EMAIL="${1:?نام کاربری یا ایمیل لازم است}"
PASSWORD="${2:?رمز لازم است}"
BASE="${3:-http://127.0.0.1:8000}"
JAR=/tmp/crawl_admin_cookies.txt
rm -f "$JAR"

G=$'\033[32m'; R=$'\033[31m'; Y=$'\033[33m'; N=$'\033[0m'

# ── ۱) گرفتن توکن CSRF از صفحه ورود
LOGIN_HTML=$(curl -s -c "$JAR" --max-time 20 "$BASE/login")
TOKEN=$(printf '%s' "$LOGIN_HTML" | grep -oE 'name="_token" value="[^"]+"' | head -1 | sed 's/.*value="//;s/"//')
if [[ -z "$TOKEN" ]]; then
    echo "${R}توکن CSRF پیدا نشد — صفحه ورود رندر نمی‌شود.${N}"
    exit 2
fi

# ── ۲) ورود
CODE=$(curl -s -o /dev/null -w '%{http_code}' -b "$JAR" -c "$JAR" --max-time 20 \
       -X POST "$BASE/login" \
       --data-urlencode "_token=$TOKEN" \
       --data-urlencode "username=$EMAIL" \
       --data-urlencode "password=$PASSWORD")

echo "ورود: کد $CODE"
if [[ "$CODE" != "302" ]]; then
    echo "${R}ورود ناموفق — آزمون پنل بی‌معنا است.${N}"
    exit 2
fi

# آیا واقعاً وارد شده‌ایم؟
AFTER=$(curl -s -o /dev/null -w '%{http_code}' -b "$JAR" -c "$JAR" --max-time 20 "$BASE/admin")
echo "بررسی دسترسی به /admin: کد $AFTER"
if [[ "$AFTER" != "200" ]]; then
    echo "${Y}هشدار: /admin کد $AFTER داد — ادامه با احتیاط${N}"
fi

# ── ۳) فهرست مسیرهای GET پنل
ROUTES_FILE=/tmp/admin_get_routes.txt
: > "$ROUTES_FILE"

php7.4 -r '
require "vendor/autoload.php";
$a = require "bootstrap/app.php";
$a->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (app("router")->getRoutes() as $r) {
    $uri = $r->uri();
    if (strpos($uri, "admin") !== 0 && strpos($uri, "seller") !== 0) { continue; }
    if (!in_array("GET", $r->methods(), true)) { continue; }
    if (strpos($uri, "{") !== false) { continue; }   // مسیرهای پارامتری رد می‌شوند
    echo "/" . $uri . "\n";
}
' 2>/dev/null | sort -u > "$ROUTES_FILE"

TOTAL=$(wc -l < "$ROUTES_FILE")
echo "مسیرهای بدون پارامتر پنل: $TOTAL"
echo "══════════════════════════════════════════════════════════════════"

PASS=0; FAIL=0; REDIRECT=0
FAILED=()

while read -r path; do
    [[ -z "$path" ]] && continue
    code=$(curl -s -o /tmp/crawl_body.html -w '%{http_code}' -b "$JAR" -c "$JAR" \
                --max-time 25 "$BASE$path" 2>/dev/null)
    size=$(stat -c%s /tmp/crawl_body.html 2>/dev/null || echo 0)

    case "$code" in
        200)
            if grep -qiE "Fatal error|Parse error|Uncaught (Exception|Error)|Whoops" /tmp/crawl_body.html; then
                printf "  ${R}✗ 200 ولی استثنا${N}  %s\n" "$path"
                FAIL=$((FAIL+1)); FAILED+=("$path [استثنا در بدنه]")
            elif [[ "$size" -lt 500 ]]; then
                printf "  ${Y}⚠ 200 بدنه کوچک${N}  %s (%s بایت)\n" "$path" "$size"
            else
                PASS=$((PASS+1))
            fi
            ;;
        500)
            printf "  ${R}✗ ۵۰۰${N}  %s\n" "$path"
            FAIL=$((FAIL+1)); FAILED+=("$path [500]")
            ;;
        302|301|403|404)
            REDIRECT=$((REDIRECT+1))
            printf "  ${Y}• %s${N}  %s\n" "$code" "$path"
            ;;
        *)
            printf "  ${Y}• %s${N}  %s\n" "$code" "$path"
            ;;
    esac
done < "$ROUTES_FILE"

echo
echo "══════════════════════════════════════════════════════════════════"
echo "  ✓ ۲۰۰ سالم: $PASS   ✗ خطا: $FAIL   • سایر: $REDIRECT"
echo "══════════════════════════════════════════════════════════════════"

if [[ ${#FAILED[@]} -gt 0 ]]; then
    echo "  صفحات خطادار:"
    for item in "${FAILED[@]}"; do echo "    • $item"; done
fi

exit $(( FAIL > 0 ? 1 : 0 ))
