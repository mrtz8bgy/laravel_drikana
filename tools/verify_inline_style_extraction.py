#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
تأیید «تغییر صفر در ظاهر» برای انتقال استایل درون‌خطی — مورد L-04.

── این اسکریپت چه می‌کند؟ ──────────────────────────────────────────────

انتقال استایل درون‌خطی به کلاس CSS، اگر اشتباه انجام شود، ظاهر را
«بی‌صدا» خراب می‌کند: صفحه ۲۰۰ برمی‌گرداند و آزمون‌ها پاس می‌شوند، اما
رنگ یا چیدمان عنصر عوض شده است. این اسکریپت همان خرابی بی‌صدا را
می‌گیرد.

روش:
  ۱) نسخه **پیش از تغییر** هر ویو از Git خوانده می‌شود
     (`git show <rev>:<path>`).
  ۲) برای هر `style="X"` ایستا در نسخه پیشین، نام کلاس بر پایه همان
     قاعده نام‌گذاری ابزار انتقال محاسبه می‌شود.
  ۳) بررسی می‌شود قاعده‌ای با **همان اعلان‌ها** و با `!important` در فایل
     CSS مقصد وجود دارد و کلاس روی همان تعداد عنصر اعمال شده است.
  ۴) تعداد استایل‌های **پویا** (وابسته به داده) قبل و بعد باید برابر
     بماند — چون آن‌ها نباید دست بخورند.

مقصد: `public/css/drikana/utilities.css` برای صفحه‌های HTML، و
درون خود ویو برای ویوهای PDF (`invoices/*`، `downloads/*` — چون dompdf
با isRemoteEnabled=false استایل بیرونی را بارگذاری نمی‌کند).

── اجرا ───────────────────────────────────────────────────────────────

    python3 tools/verify_inline_style_extraction.py            # پیش از کامیت
    python3 tools/verify_inline_style_extraction.py --rev <sha> # بعد از کامیت

`--rev` باید کامیتی باشد که ویوها **پیش از** انتقال در آن هستند.
خروج کد ۰ یعنی تأیید شد.
"""
import argparse
import collections
import hashlib
import io
import os
import re
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
VIEWS = os.path.join(ROOT, 'resources/views')
CSS_FILE = os.path.join(ROOT, 'public/css/drikana/utilities.css')
STYLE_RE = re.compile(r'style="([^"]*)"')
SELF_CONTAINED_PREFIXES = ('invoices/', 'downloads/')


def is_static(decl):
    """آیا اعلان ثابت است (هیچ ورودی Blade ندارد)؟"""
    return ('{{' not in decl and '}}' not in decl
            and '@' not in decl and '</' not in decl)


def class_name(decl):
    """نام کلاس از رشته اعلان — همان الگوریتم ابزار انتقال."""
    parts = []
    for piece in decl.split(';'):
        piece = piece.strip()
        if not piece or ':' not in piece:
            continue
        prop, _, value = piece.partition(':')
        token = re.sub(r'[^a-z0-9]+', '-', (prop.strip() + '-' + value.strip()).lower()).strip('-')
        if token:
            parts.append(token)
    name = 'u-' + '-'.join(parts)
    if len(name) > 64:
        name = name[:57] + '-' + hashlib.md5(decl.encode('utf-8')).hexdigest()[:6]
    return name


def css_rule(name, decl):
    declarations = [p.strip() + ' !important' for p in decl.split(';') if p.strip()]
    return '.%s { %s; }' % (name, '; '.join(declarations))


def assign_names(decls):
    """نام‌گذاری یکتا — پرکاربردترین اعلان، نام پایه را می‌گیرد."""
    by_name = collections.defaultdict(list)
    for decl in decls:
        by_name[class_name(decl)].append(decl)

    name_of = {}
    for name, group in by_name.items():
        group.sort(key=lambda d: (-decls[d], d))
        for index, decl in enumerate(group):
            name_of[decl] = name if index == 0 else '%s-%d' % (name, index + 1)
    return name_of


def git_show(rev, path):
    try:
        return subprocess.check_output(
            ['git', 'show', '%s:%s' % (rev, path)],
            cwd=ROOT, stderr=subprocess.DEVNULL
        ).decode('utf-8')
    except subprocess.CalledProcessError:
        return None


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--rev', default='HEAD',
                        help='کامیتی که ویوهای پیش از انتقال در آن هستند')
    args = parser.parse_args()

    if not os.path.isfile(CSS_FILE):
        print('فایل %s پیدا نشد.' % CSS_FILE)
        return 2

    utilities = io.open(CSS_FILE, encoding='utf-8').read()

    decls = collections.Counter()
    old_files = {}

    for dirpath, _, filenames in os.walk(VIEWS):
        for filename in filenames:
            if not filename.endswith('.blade.php'):
                continue
            full = os.path.join(dirpath, filename)
            rel = os.path.relpath(full, ROOT)
            old = git_show(args.rev, rel)
            if old is None:
                continue
            static = [m.group(1) for m in STYLE_RE.finditer(old) if is_static(m.group(1))]
            if static:
                old_files[rel] = (old, static)
                decls.update(static)

    name_of = assign_names(decls)

    total = 0
    missing_rule = []
    not_applied = []

    for rel, (old, static) in old_files.items():
        new = io.open(os.path.join(ROOT, rel), encoding='utf-8').read()
        is_pdf = rel.replace('resources/views/', '').startswith(SELF_CONTAINED_PREFIXES)
        haystack = new if is_pdf else utilities

        for decl in static:
            total += 1
            name = name_of[decl]

            if css_rule(name, decl) not in haystack:
                missing_rule.append((rel, decl, name))
                continue

            if new.count(name) < old.count('style="%s"' % decl):
                not_applied.append((rel, decl, name))

    dynamic_old = dynamic_new = 0
    for rel, (old, _) in old_files.items():
        new = io.open(os.path.join(ROOT, rel), encoding='utf-8').read()
        dynamic_old += len([m for m in STYLE_RE.finditer(old) if not is_static(m.group(1))])
        dynamic_new += len([m for m in STYLE_RE.finditer(new) if not is_static(m.group(1))])

    print('صفت‌های style ایستا در نسخه %s : %d' % (args.rev, total))
    print('قاعده CSS متناظر پیدا شد      : %d' % (total - len(missing_rule)))
    print('قاعده گم‌شده                   : %d' % len(missing_rule))
    print('کلاس روی عنصر اعمال نشده      : %d' % len(not_applied))
    print('استایل پویا — قبل: %d ، بعد: %d (باید برابر بماند)' % (dynamic_old, dynamic_new))

    for row in missing_rule[:10]:
        print('   گم‌شده: %s ← %s (%s)' % row)
    for row in not_applied[:10]:
        print('   اعمال‌نشده: %s ← %s (%s)' % row)

    ok = (not missing_rule and not not_applied and dynamic_old == dynamic_new)

    print('\nنتیجه: %s' % ('✅ تغییر صفر در ظاهر — همه اعلان‌ها منتقل شدند'
                           if ok else '❌ بازبینی لازم است'))
    return 0 if ok else 1


if __name__ == '__main__':
    sys.exit(main())
