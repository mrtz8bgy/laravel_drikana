<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * آپلودر امن تصویر.
 *
 * ── کنترل‌های امنیتی پیاده‌شده ──────────────────────────────────────
 *
 * ۱) اعتبارسنجی «نوع واقعی فایل» با MIME sniffing (`getMimeType()` که
 *    محتوای فایل را می‌خواند) — نه فقط پسوند نام فایل. مهاجم می‌تواند
 *    فایل PHP را با پسوند `.jpg` آپلود کند؛ بررسی پسوند کافی نیست.
 *
 * ۲) بررسی تصویر بودن واقعی با `getimagesize()` — محتوای فایل باید
 *    ساختار تصویر معتبر داشته باشد.
 *
 * ۳) محدودیت حجم (پیش‌فرض ۳ مگابایت) و ابعاد (پیش‌فرض ۴۰۰۰×۴۰۰۰).
 *
 * ۴) تولید نام فایل کاملاً تصادفی — هیچ بخشی از نام کاربر در نام فایل
 *    استفاده نمی‌شود، بنابراین امکان Path Traversal و بازنویسی فایل
 *    وجود ندارد.
 *
 * ۵) پسوند فایل بر اساس MIME تأییدشده تعیین می‌شود، نه نام اصلی.
 *
 * ۶) ساخت مسیر با تاریخ (Y/m) — جلوگیری از انباشت هزاران فایل در یک پوشه.
 *
 * ۷) ذخیره در دیسک `public` زیر پوشه `uploads` که فایل‌های اجرایی در آن
 *    اجرا نمی‌شوند (به شرط تنظیم صحیح وب‌سرور — در docs/deployment.md
 *    دستورالعمل آمده است).
 */
class SecureImageUploader
{
    /** حداکثر حجم مجاز فایل (بایت) */
    const MAX_SIZE = 3145728; // ۳ مگابایت

    /** حداکثر ابعاد مجاز (پیکسل) */
    const MAX_WIDTH  = 4000;
    const MAX_HEIGHT = 4000;

    /**
     * نگاشت MIME مجاز به پسوند امن.
     *
     * @var array
     */
    protected $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    /**
     * آپلود امن یک تصویر.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string                        $directory پوشه مقصد در دیسک
     * @param  string                        $disk
     * @return array  ['path' => ..., 'original_name' => ..., 'mime' => ..., 'size' => ...]
     *
     * @throws \RuntimeException در صورت رد شدن فایل
     */
    public function upload(UploadedFile $file, $directory = 'uploads/articles', $disk = 'public')
    {
        // -------------------------------------------------------
        // ۱) بررسی معتبر بودن آپلود
        // -------------------------------------------------------
        if (!$file->isValid()) {
            throw new \RuntimeException('فایل آپلودشده معتبر نیست.');
        }

        // -------------------------------------------------------
        // ۲) بررسی نوع واقعی فایل (MIME sniffing)
        // -------------------------------------------------------
        $realMime = $file->getMimeType();

        if (!isset($this->allowedMimes[$realMime])) {
            throw new \RuntimeException(
                'نوع فایل مجاز نیست. تنها تصویر JPEG، PNG، GIF و WebP پذیرفته می‌شود. '
                . 'نوع تشخیص‌داده‌شده: ' . ($realMime ?: 'ناشناخته')
            );
        }

        // -------------------------------------------------------
        // ۳) بررسی ساختار واقعی تصویر
        //    getimagesize روی محتوا کار می‌کند نه پسوند
        // -------------------------------------------------------
        $imageInfo = @getimagesize($file->getRealPath());

        if ($imageInfo === false) {
            throw new \RuntimeException('فایل ارسالی یک تصویر معتبر نیست.');
        }

        list($width, $height) = $imageInfo;

        // -------------------------------------------------------
        // ۴) محدودیت ابعاد
        // -------------------------------------------------------
        if ($width > self::MAX_WIDTH || $height > self::MAX_HEIGHT) {
            throw new \RuntimeException(
                'ابعاد تصویر بیش از حد مجاز است. حداکثر ' . self::MAX_WIDTH . '×' . self::MAX_HEIGHT . ' پیکسل.'
            );
        }

        // -------------------------------------------------------
        // ۵) محدودیت حجم (بررسی سمت سرور — اعتبارسنجی فرم کافی نیست)
        // -------------------------------------------------------
        if ($file->getSize() > self::MAX_SIZE) {
            throw new \RuntimeException(
                'حجم تصویر نباید بیشتر از ' . round(self::MAX_SIZE / 1048576, 1) . ' مگابایت باشد.'
            );
        }

        // -------------------------------------------------------
        // ۶) تولید نام امن و تصادفی
        // -------------------------------------------------------
        $extension = $this->allowedMimes[$realMime];
        $filename = $this->generateSafeFilename($extension);
        $subDirectory = trim($directory, '/') . '/' . date('Y/m');

        // -------------------------------------------------------
        // ۷) ذخیره روی دیسک
        // -------------------------------------------------------
        $path = $file->storeAs($subDirectory, $filename, $disk);

        if ($path === false || $path === null) {
            throw new \RuntimeException('ذخیره تصویر روی سرور ناموفق بود.');
        }

        return [
            'path'          => $path,
            'original_name' => $this->sanitizeOriginalName($file->getClientOriginalName()),
            'mime'          => $realMime,
            'size'          => (int) $file->getSize(),
            'width'         => $width,
            'height'        => $height,
        ];
    }

    /**
     * تولید نام فایل تصادفی و امن.
     *
     * از `random_bytes` استفاده می‌کند که منبع تصادفی رمزنگاری‌شده است —
     * بنابراین نام فایل قابل پیش‌بینی نیست و امکان حدس‌زدن مسیر یک فایل
     * آپلودشده توسط کاربر دیگر وجود ندارد.
     *
     * @param  string $extension
     * @return string
     */
    protected function generateSafeFilename($extension)
    {
        return date('Ymd') . '_' . bin2hex(random_bytes(16)) . '.' . $extension;
    }

    /**
     * پاک‌سازی نام اصلی فایل برای ذخیره در دیتابیس.
     *
     * نام اصلی فقط برای نمایش به مدیر نگه داشته می‌شود؛ هرگز در مسیر
     * فایل استفاده نمی‌شود. اما پاک‌سازی می‌شود تا در صورت نمایش در HTML
     * امکان XSS ایجاد نکند.
     *
     * @param  string $name
     * @return string
     */
    protected function sanitizeOriginalName($name)
    {
        $name = (string) $name;

        // حذف کاراکترهای کنترل و تگ‌های HTML
        $name = strip_tags($name);
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name);

        // حذف اجزای مسیر (Path Traversal)
        $name = str_replace(['..', '/', '\\'], '', $name);

        return mb_substr(trim($name), 0, 191);
    }

    /**
     * حذف ایمن یک فایل آپلودشده.
     *
     * ── محافظت از Path Traversal ────────────────────────────────────
     * مسیر پیش از حذف عادی‌سازی و بررسی می‌شود که در پوشه مجاز باقی
     * بماند. بدون این بررسی، مقداری مانند `../../.env` در دیتابیس
     * می‌توانست منجر به حذف فایل‌های حساس شود.
     *
     * @param  string|null $path مسیر نسبی فایل
     * @param  string      $disk
     * @param  array       $allowedPrefixes پیشوندهای مجاز نسبت به ریشه دیسک
     * @return bool
     */
    public function delete($path, $disk = 'public', array $allowedPrefixes = ['uploads/'])
    {
        if (empty($path)) {
            return false;
        }

        // حذف اجزای خطرناک مسیر
        $normalized = str_replace('\\', '/', (string) $path);

        if (strpos($normalized, '..') !== false || strpos($normalized, "\0") !== false) {
            return false;
        }

        $normalized = ltrim($normalized, '/');

        // مسیر باید زیر یکی از پیشوندهای مجاز باشد
        $isAllowed = false;
        foreach ((array) $allowedPrefixes as $prefix) {
            if (strpos($normalized, ltrim($prefix, '/')) === 0) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return false;
        }

        try {
            $storage = \Illuminate\Support\Facades\Storage::disk($disk);

            if ($storage->exists($normalized)) {
                return $storage->delete($normalized);
            }
        } catch (\Exception $e) {
            report($e);
        }

        return false;
    }
}
