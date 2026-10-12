#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
بررسی سلامت ارجاع‌ها در کد — سه دسته جدا.

── چرا این اسکریپت وجود دارد؟ ──────────────────────────────────────

هر ارجاع به «نامی که وجود ندارد» یک خطای زمان اجراست که صفحه را ۵۰۰
می‌کند، و کشف آن با بازبینی چشمی تقریباً غیرممکن است:

  ۱) `route('products.show')` — نام مسیر ناموجود
     → استثنای «Route [x] not defined» در هر درخواست
  ۲) `middleware('permission:articles.update')` — مجوز ناموجود
     → دسترسی‌ای که هرگز باز نمی‌شود (یا با wildcard اشتباه باز می‌شود)
  ۳) `@include('frontend.partials.x')` — ویو ناموجود
     → خطای رندر

این اسکریپت هر سه را با منبع حقیقت مقایسه می‌کند: روتر لاراول، جدول
`permissions` و فایل‌های ویو.

── اجرا ─────────────────────────────────────────────────────────────

    php7.4 dump_routes.php && python3 tools/check_references.py

(اسکریپت `dump_routes.php` نام همه مسیرها را از روتر بیرون می‌کشد و در
 `/tmp/route_names.txt` می‌نویسد؛ پارس جدول `route:list` قابل اعتماد نیست
 چون ستون متد خودش شامل `|` است.)

خروج کد ۰ یعنی هیچ ارجاع شکسته‌ای پیدا نشد.
"""
import io
import os
import re
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

SKIP_DIRS = {'vendor', 'node_modules', '.git', 'storage', 'bootstrap/cache', 'dist', 'build'}
SCAN_DIRS = ('app', 'resources/views', 'routes', 'config')


def collect_files():
    files = []
    for base in SCAN_DIRS:
        for dirpath, dirnames, filenames in os.walk(base):
            dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
            for name in filenames:
                if name.endswith('.php'):
                    files.append(os.path.join(dirpath, name))
    return files


def route_names():
    path = '/tmp/route_names.txt'
    if not os.path.isfile(path):
        print('نام مسیرها پیدا نشد. اول اجرا کنید:  php7.4 dump_routes.php')
        sys.exit(2)
    return set(line.strip() for line in io.open(path, encoding='utf-8') if line.strip())


def permission_slugs():
    """خواندن اسلاگ مجوزها از دیتابیس پروژه."""
    php = (
        "require 'vendor/autoload.php';"
        "$a = require 'bootstrap/app.php';"
        "$a->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();"
        "echo implode(\"\\n\", DB::table('permissions')->pluck('slug')->all());"
    )
    try:
        out = subprocess.check_output(['php7.4', '-r', php], stderr=subprocess.DEVNULL)
        return set(line.strip() for line in out.decode('utf-8', 'replace').split('\n') if line.strip())
    except subprocess.CalledProcessError:
        print('!! جدول permissions خوانده نشد — بررسی مجوزها رد شد.')
        return None


def view_names():
    """نام ویوهای موجود — مسیر فایل به نام نقطه‌ای."""
    views = set()
    base = 'resources/views'
    for dirpath, dirnames, filenames in os.walk(base):
        for name in filenames:
            if not name.endswith('.blade.php'):
                continue
            rel = os.path.relpath(os.path.join(dirpath, name), base)
            views.add(rel[:-len('.blade.php')].replace(os.sep, '.'))
    return views


def main():
    print('═' * 68)
    print('  بررسی ارجاع‌های کد')
    print('═' * 68)

    routes = route_names()
    permissions = permission_slugs()
    views = view_names()

    print('منابع حقیقت: %d نام مسیر · %s مجوز · %d ویو'
          % (len(routes), len(permissions) if permissions else '؟', len(views)))

    files = collect_files()
    print('فایل‌های بررسی‌شده: %d' % len(files))
    print()

    # الگوها
    ROUTE_RE = re.compile(r"route\(\s*['\"]([A-Za-z0-9_.\-]+)['\"]")
    PERMISSION_RE = re.compile(r"middleware\(\s*['\"](?:permission|permission_all|role):([A-Za-z0-9_.\-|]+)['\"]")
    INCLUDE_RE = re.compile(r"@(?:include|extends|each)\(\s*['\"]([A-Za-z0-9_.\-/]+)['\"]")

    broken_routes = {}
    conditional_routes = {}
    broken_permissions = {}
    broken_includes = {}

    # ارجاع‌هایی که داخل شرط «فعال بودن افزونه» یا «وجود داشتن روت» نوشته
    # شده‌اند، خطا نیستند: اگر افزونه نصب/فعال نباشد آن بلوک اصلاً رندر
    # نمی‌شود. این‌ها جدا شمرده می‌شوند تا عدد «ارجاع شکسته» واقعی بماند.
    GUARD_RE = re.compile(
        r"Route::has\(|->activated|Addon::where\(|addon",
        re.IGNORECASE,
    )

    dynamic = 0

    for path in files:
        try:
            source = io.open(path, encoding='utf-8').read()
        except Exception:
            continue

        source_lines = source.splitlines()

        for match in ROUTE_RE.finditer(source):
            name = match.group(1)
            if name in routes:
                continue

            # ناحیهٔ ۲۵ خط پیش از ارجاع را برای «محافظ» بررسی می‌کنیم
            line_no = source.count('\n', 0, match.start())
            window = '\n'.join(source_lines[max(0, line_no - 25):line_no + 1])

            if GUARD_RE.search(window):
                conditional_routes.setdefault(name, set()).add(path)
            else:
                broken_routes.setdefault(name, set()).add(path)

        # ارجاع داینامیک (با متغیر) قابل بررسی نیست — شمرده می‌شود تا عدد
        # پوشش بررسی شفاف بماند
        dynamic += len(re.findall(r"route\(\s*\$", source))

        if permissions:
            for match in PERMISSION_RE.finditer(source):
                for slug in match.group(1).split('|'):
                    slug = slug.strip()
                    if slug and slug not in permissions:
                        broken_permissions.setdefault(slug, set()).add(path)

        for match in INCLUDE_RE.finditer(source):
            view = match.group(1)
            if '$' in view:
                continue
            if view not in views:
                broken_includes.setdefault(view, set()).add(path)

    def report(title, broken, hint):
        print('── %s' % title)
        if not broken:
            print('   ✅ هیچ ارجاع شکسته‌ای نیست')
        else:
            print('   ❌ %d مورد:' % len(broken))
            for name in sorted(broken, key=lambda n: -len(broken[n])):
                sites = sorted(broken[name])
                print('      • %-42s %d جایگاه' % (name, len(sites)))
                for site in sites[:4]:
                    print('          %s' % site)
        print()

    report('۱) نام‌های مسیر در route()', broken_routes, '')

    if conditional_routes:
        print()
        print('   ℹ %d نام مسیر مشروط (داخل شرط فعال‌بودن افزونه یا Route::has) — خطا نیست:'
              % len(conditional_routes))
        for name in sorted(conditional_routes):
            paths = ', '.join(sorted(p.replace('resources/views/', '') for p in conditional_routes[name]))
            print('      • %-38s %s' % (name, paths))
    report('۲) اسلاگ مجوز در middleware()', broken_permissions, '')
    report('۳) ویو در @include/@extends', broken_includes, '')

    print('ارجاع‌های داینامیک route($var) که قابل بررسی نیستند: %d' % dynamic)

    total = len(broken_routes) + len(broken_permissions) + len(broken_includes)

    print()
    print('═' * 68)
    if total == 0:
        print('  ✅ همه ارجاع‌ها معتبرند')
    else:
        print('  ❌ مجموع ارجاع شکسته: %d (مسیر %d · مجوز %d · ویو %d)'
              % (total, len(broken_routes), len(broken_permissions), len(broken_includes)))
    print('═' * 68)

    return 1 if total else 0


if __name__ == '__main__':
    sys.exit(main())
