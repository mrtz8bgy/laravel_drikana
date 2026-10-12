<?php

namespace App\Http\Controllers\Concerns;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ─────────────────────────────────────────────────────────────
 * trait دانلود فایل CSV
 * ─────────────────────────────────────────────────────────────
 *
 * این trait برای خروجی گرفتن «الگوی نمونه CSV» در بخش بارگذاری
 * گروهی محصولات استفاده می‌شود.
 *
 * چرا trait و نه یک کلاس پایه؟
 *   کنترلرهای این پروژه از `Controller` ارث‌بری می‌کنند و ارث‌بری چندگانه
 *   در PHP ممکن نیست. trait راه استاندارد اشتراک کد بدون شکستن سلسله‌مراتب
 *   ارث‌بری است (اصل DRY + اصل مسئولیت واحد).
 *
 * نکات امنیتی:
 *   • BOM یونیکد در ابتدای فایل درج می‌شود تا اکسل فارسی را درست بخواند.
 *   • هیچ داده‌ای از کاربر در نام فایل قرار نمی‌گیرد، بنابراین امکان
 *     Path Traversal یا Header Injection وجود ندارد.
 *   • مقادیر نوشته‌شده در CSV با `fputcsv` تولید می‌شوند که کاراکترهای
 *     خاص را خودش escape می‌کند.
 */
trait HandlesCsvDownload
{
    /**
     * ساخت پاسخ دانلود یک فایل CSV از یک آرایه دو‌بعدی.
     *
     * @param  string $filename نام فایل خروجی (پیشوند ثابت، از سمت سرور)
     * @param  array  $headers  سطر عنوان ستون‌ها
     * @param  array  $rows     سطرهای داده
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamCsv($filename, array $headers, array $rows)
    {
        // نام فایل فقط از حروف امن ساخته می‌شود
        $safeFilename = preg_replace('/[^A-Za-z0-9_\-\.]/', '', (string) $filename);

        if ($safeFilename === '' || substr($safeFilename, -4) !== '.csv') {
            $safeFilename = 'export.csv';
        }

        return new StreamedResponse(function () use ($headers, $rows) {

            $out = fopen('php://output', 'w');

            // BOM یونیکد برای نمایش صحیح فارسی در Excel
            fwrite($out, "\xEF\xBB\xBF");

            if (!empty($headers)) {
                fputcsv($out, $headers);
            }

            foreach ($rows as $row) {
                fputcsv($out, $row);
            }

            fclose($out);

        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $safeFilename . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }
}
