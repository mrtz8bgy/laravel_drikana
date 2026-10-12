<?php

namespace App\Http\Controllers\Concerns;

use Barryvdh\DomPDF\Facade as PDF;

/**
 * ساخت خروجی‌های PDF (دسته‌بندی، زیردسته، برند، کاربران و ...).
 *
 * چرا این trait وجود دارد و چرا گزینه‌ها اینجا متمرکز شده‌اند:
 *
 *  ۱) در بستهٔ barryvdh/laravel-dompdf متد setOptions() شیء Options را
 *     **جایگزین** می‌کند (نه ادغام با پیش‌فرض‌ها). اگر `chroot` داده نشود،
 *     dompdf مسیر پروژه را «بیرون از محدودهٔ مجاز» می‌بیند و تصویرهای
 *     محلی (لوگو) را بی‌صدا حذف می‌کند.
 *  ۲) الیاس سراسری `PDF` توسط بستهٔ niklasravnsborg/laravel-pdf (از راه
 *     bootstrap/cache/packages.php) روی mPDF بازنویسی می‌شود و متد
 *     setOptions ندارد؛ بنابراین کلاس dompdf را صریح import می‌کنیم.
 *  ۳) تمرکز گزینه‌ها از تکرار در چند کنترلر جلوگیری می‌کند.
 */
trait BuildsPdfDocuments
{
    /**
     * گزینه‌های مشترک موتور PDF.
     *
     * @return array
     */
    protected function pdfOptions()
    {
        return [
            // دسترسی dompdf به فایل‌های محلی، محدود به ریشهٔ پروژه می‌شود.
            'chroot'               => base_path(),
            'isHtml5ParserEnabled' => true,
            // خروجی‌ها خودبسنده‌اند (لوگو و CSS محلی)؛ واکشی شبکه‌ای نه لازم
            // است و نه مطلوب — این تنظیم مسیر سوءاستفادهٔ SSRF را می‌بندد.
            'isRemoteEnabled'      => false,
            'logOutputFile'        => storage_path('logs/log.htm'),
            'tempDir'              => storage_path('logs/'),
            // قلم‌ها و کش قلم در storage نگه داشته می‌شوند تا نیازی به
            // نوشتن در پوشهٔ vendor (که می‌تواند فقط‌خواندنی باشد) نباشد.
            'fontDir'              => storage_path('fonts/'),
            'fontCache'            => storage_path('fonts/'),
        ];
    }

    /**
     * ساخت و ارسال PDF از یک ویو.
     *
     * @param  string $view     نام ویو (مثل downloads.category)
     * @param  array  $data     داده‌های ویو
     * @param  string $filename نام فایل خروجی
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function pdfDownload($view, array $data, $filename)
    {
        return PDF::setOptions($this->pdfOptions())
            ->loadView($view, $data)
            ->download($filename);
    }
}
