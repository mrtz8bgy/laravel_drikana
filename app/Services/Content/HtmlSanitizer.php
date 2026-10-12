<?php

namespace App\Services\Content;

/**
 * پاک‌سازی HTML مقاله (XSS Sanitization).
 *
 * ── رویکرد ───────────────────────────────────────────────────────
 * محتوای غنی مقاله (متن کامل) اجازه HTML دارد تا نویسنده بتواند از
 * تیتر، لیست و تصویر استفاده کند. اما مستقیم ذخیره‌کردن HTML ورودی
 * کاربر = XSS ذخیره‌شده.
 *
 * دو لایه دفاع:
 *
 *   لایه ۱ — در زمان ذخیره (این کلاس):
 *     پاک‌سازی با whitelist سخت‌گیرانه. هر تگ یا ویژگی خارج از فهرست
 *     حذف می‌شود. تگ‌های خطرناک (script, iframe, object, embed, form)
 *     به‌طور کامل حذف می‌شوند.
 *
 *   لایه ۲ — در زمان نمایش:
 *     خروجی HTML مقاله با `{!! !!}` نمایش داده می‌شود، اما همین حالا
 *     پاک‌شده است. برای فیلدهای متنی (عنوان، خلاصه، Alt) از
 *     `{{ }}` استفاده می‌شود که escape می‌کند.
 *
 * ── چرا از کتابخانه خارجی استفاده نشد؟ ──────────────────────────────
 * پروژه روی Laravel 5.8 و PHP 7.1+ قفل است و وابستگی جدید (مانند
 * ezyang/htmlpurifier) نصب نشده. برای پرهیز از افزودن وابستگی در
 * این مرحله، پیاده‌سازی داخلی با whitelist محافظه‌کارانه انجام شد.
 * **توصیه برای فاز بعد:** جایگزینی با HTMLPurifier برای پوشش کامل‌تر.
 */
class HtmlSanitizer
{
    /**
     * تگ‌های مجاز در محتوای مقاله.
     *
     * @var array
     */
    protected static $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup',
        'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'blockquote', 'pre', 'code',
        'a', 'img',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'figure', 'figcaption', 'hr', 'span', 'div',
    ];

    /**
     * تگ‌هایی که همراه با محتوایشان کاملاً حذف می‌شوند.
     * (نه فقط تگ — کل محتوا، چون ممکن است اسکریپت درون آن باشد)
     *
     * @var array
     */
    protected static $dangerousTags = [
        'script', 'style', 'iframe', 'object', 'embed', 'applet',
        'form', 'input', 'button', 'select', 'textarea', 'option',
        'link', 'meta', 'base', 'frame', 'frameset', 'noscript',
        'svg', 'math', 'template',
    ];

    /**
     * ویژگی‌های مجاز به تفکیک تگ.
     *
     * @var array
     */
    protected static $allowedAttributes = [
        'a'   => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading'],
        '*'   => ['class', 'id', 'dir', 'lang'],
    ];

    /**
     * پروتکل‌های مجاز برای href و src.
     *
     * @var array
     */
    protected static $allowedProtocols = ['http', 'https', 'mailto', 'tel'];

    /**
     * پاک‌سازی HTML مقاله.
     *
     * @param  string|null $html
     * @return string
     */
    public static function clean($html)
    {
        if (empty($html)) {
            return '';
        }

        $html = (string) $html;

        // -------------------------------------------------------
        // ۱) حذف کامل تگ‌های خطرناک و محتوای درونشان
        // -------------------------------------------------------
        foreach (static::$dangerousTags as $tag) {
            // الگوی مقاوم به تغییر فاصله و بزرگ/کوچک بودن حروف
            $html = preg_replace(
                '#<' . $tag . '\b[^>]*>.*?</' . $tag . '\s*>#is',
                '',
                $html
            );
            // تگ‌های خودبسته یا بدون محتوا
            $html = preg_replace('#<' . $tag . '\b[^>]*/?>#is', '', $html);
        }

        // -------------------------------------------------------
        // ۲) حذف کامنت‌های HTML
        //    (شرطی‌سازی مرورگر و تزریق از این مسیر ممکن است)
        // -------------------------------------------------------
        $html = preg_replace('/<!--.*?-->/s', '', $html);

        // -------------------------------------------------------
        // ۳) حذف پردازنده‌های شرطی IE
        // -------------------------------------------------------
        $html = preg_replace('#<!\[if.*?<!\[endif\]\s*>#is', '', $html);

        // -------------------------------------------------------
        // ۴) حذف ویژگی‌های رویدادمحور (onclick, onerror, onload, ...)
        // -------------------------------------------------------
        $html = preg_replace(
            '/\s+on[a-zA-Z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i',
            '',
            $html
        );

        // -------------------------------------------------------
        // ۵) حذف ویژگی‌های حاوی پروتکل خطرناک
        //    javascript:, vbscript:, data:text/html
        // -------------------------------------------------------
        $html = preg_replace(
            '/\s+(href|src|xlink:href|action|formaction|style)\s*=\s*("|\')\s*(javascript|vbscript|data\s*:\s*text\/html)[^"\']*\2/i',
            '',
            $html
        );

        // -------------------------------------------------------
        // ۶) حذف ویژگی style (CSS درون‌خطی می‌تواند حامل expression
        //    یا url(javascript:) باشد)
        // -------------------------------------------------------
        $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $html);

        // -------------------------------------------------------
        // ۷) فیلتر تگ‌ها بر اساس whitelist
        //    قطعه‌به‌قطعه پردازش می‌شود تا کنترل کامل روی ویژگی‌ها باشد
        // -------------------------------------------------------
        $html = static::filterTags($html);

        return trim($html);
    }

    /**
     * فیلتر تگ‌ها و ویژگی‌ها بر اساس whitelist.
     *
     * @param  string $html
     * @return string
     */
    protected static function filterTags($html)
    {
        $allowedTagsPattern = '<(?:/)?(?:' . implode('|', static::$allowedTags) . ')(?:\s[^>]*)?/?>';

        return preg_replace_callback(
            '/<[^>]+>/',
            function ($matches) use ($allowedTagsPattern) {
                $tag = $matches[0];

                // اگر تگ در whitelist نیست، آن را حذف کن
                if (!preg_match('#^' . $allowedTagsPattern . '$#i', $tag)) {
                    return '';
                }

                return static::filterAttributes($tag);
            },
            $html
        );
    }

    /**
     * فیلتر ویژگی‌های یک تگ.
     *
     * @param  string $tag
     * @return string
     */
    protected static function filterAttributes($tag)
    {
        // تشخیص نام تگ
        if (!preg_match('#^</?\s*([a-zA-Z0-9]+)#', $tag, $m)) {
            return $tag;
        }

        $tagName = strtolower($m[1]);
        $isClosing = (strpos($tag, '</') === 0);

        if ($isClosing) {
            return '</' . $tagName . '>';
        }

        $isSelfClosing = (substr(rtrim($tag), -2) === '/>');

        // جمع‌آوری ویژگی‌های مجاز برای این تگ
        $allowed = isset(static::$allowedAttributes[$tagName])
            ? static::$allowedAttributes[$tagName]
            : [];
        $allowed = array_unique(array_merge($allowed, static::$allowedAttributes['*']));

        $attributes = [];

        // استخراج ویژگی‌ها
        if (preg_match_all(
            '/([a-zA-Z_:][a-zA-Z0-9_:.-]*)\s*(?:=\s*("([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+)))?/',
            substr($tag, strlen($m[0])),
            $attrMatches,
            PREG_SET_ORDER
        )) {
            foreach ($attrMatches as $attr) {
                $name  = strtolower($attr[1]);
                $value = isset($attr[3]) && $attr[3] !== ''
                    ? $attr[3]
                    : (isset($attr[4]) && $attr[4] !== ''
                        ? $attr[4]
                        : (isset($attr[5]) ? $attr[5] : ''));

                // ویژگی باید در whitelist باشد
                if (!in_array($name, $allowed, true)) {
                    continue;
                }

                // ویژگی رویدادمحور ممنوع
                if (strpos($name, 'on') === 0) {
                    continue;
                }

                // بررسی پروتکل برای href و src
                if (in_array($name, ['href', 'src'], true) && $value !== '') {
                    if (!static::isSafeUrl($value)) {
                        continue;
                    }
                }

                // مقادیر width/height باید عددی باشند
                if (in_array($name, ['width', 'height'], true) && $value !== '' && !ctype_digit($value)) {
                    continue;
                }

                $attributes[] = $name . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        // لینک‌های خارجی باید rel="noopener noreferrer" داشته باشند
        if ($tagName === 'a' && !empty($attributes)) {
            $hasTarget = false;
            foreach ($attributes as $a) {
                if (strpos($a, 'target=') === 0) {
                    $hasTarget = true;
                    break;
                }
            }
            if ($hasTarget) {
                $hasRel = false;
                foreach ($attributes as $a) {
                    if (strpos($a, 'rel=') === 0) {
                        $hasRel = true;
                        break;
                    }
                }
                if (!$hasRel) {
                    $attributes[] = 'rel="noopener noreferrer"';
                }
            }
        }

        $attrString = empty($attributes) ? '' : ' ' . implode(' ', $attributes);

        return '<' . $tagName . $attrString . ($isSelfClosing ? ' />' : '>');
    }

    /**
     * بررسی امن بودن URL.
     *
     * @param  string $url
     * @return bool
     */
    protected static function isSafeUrl($url)
    {
        $url = trim($url);

        if ($url === '') {
            return false;
        }

        // لینک داخلی نسبی مجاز است
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }

        // لنگر صفحه
        if (strpos($url, '#') === 0) {
            return true;
        }

        // لینک داده‌ای فقط برای تصویر و فقط image/*
        if (stripos($url, 'data:') === 0) {
            return (bool) preg_match('#^data:image/(png|jpe?g|gif|webp);base64,#i', $url);
        }

        // بررسی پروتکل
        if (preg_match('#^([a-zA-Z][a-zA-Z0-9+.-]*):#', $url, $m)) {
            return in_array(strtolower($m[1]), static::$allowedProtocols, true);
        }

        // بدون پروتکل — نسبی است
        return true;
    }

    /**
     * پاک‌سازی متن ساده (عنوان، خلاصه، Alt).
     *
     * فیلدهای متنی نباید HTML داشته باشند. این متد تمام تگ‌ها را حذف
     * می‌کند.
     *
     * @param  string|null $text
     * @return string
     */
    public static function cleanText($text)
    {
        if ($text === null) {
            return '';
        }

        return trim(strip_tags((string) $text));
    }

    /**
     * پاک‌سازی raw HTML برای ذخیره در دیتابیس.
     *
     * @param  string|null $html
     * @return string
     */
    public static function cleanForStorage($html)
    {
        return static::clean($html);
    }
}
