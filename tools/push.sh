#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────
# پوش امن به GitHub
#
#   GITHUB_TOKEN=ghp_xxx bash tools/push.sh
#
# • توکن فقط از متغیر محیطی خوانده می‌شود و در هیچ فایلی نوشته نمی‌شود.
# • به هر دو شاخهٔ main و develop/refactor-rbac پوش می‌کند و در پایان
#   نتیجه را از راه دور تأیید می‌کند.
# ──────────────────────────────────────────────────────────────────────────
set -euo pipefail
: "${GITHUB_TOKEN:?متغیر GITHUB_TOKEN تعیین نشده است}"

cd "$(dirname "$0")/.."

AUTH="AUTHORIZATION: basic $(printf 'x-access-token:%s' "$GITHUB_TOKEN" | base64 -w0)"
PUSH=(git -c credential.helper= -c "http.extraheader=$AUTH" push origin)

echo "── پوش به main:"
"${PUSH[@]}" HEAD:refs/heads/main

echo "── پوش به develop/refactor-rbac:"
"${PUSH[@]}" HEAD:refs/heads/develop/refactor-rbac

echo "── تأیید از راه دور:"
git ls-remote origin refs/heads/main refs/heads/develop/refactor-rbac
echo "✅ پوش انجام شد."
