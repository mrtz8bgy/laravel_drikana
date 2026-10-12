<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * تصویر داخل متن مقاله.
 *
 * مسیر فایل نسبت به دیسک ذخیره‌سازی ذخیره می‌شود (نه URL کامل) تا
 * انتقال دامنه یا تغییر ساختار پوشه‌ها، رکوردها را نشکند.
 */
class ArticleImage extends Model
{
    protected $table = 'article_images';

    protected $fillable = [
        'article_id',
        'path',
        'original_name',
        'alt',
        'caption',
        'file_size',
        'mime_type',
        'sort_order',
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * مقاله مربوطه.
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * URL عمومی تصویر.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if (empty($this->path)) {
            return '';
        }

        return asset('storage/' . ltrim($this->path, '/'));
    }

    /**
     * حجم فایل به شکل خوانا.
     *
     * @return string
     */
    public function getHumanSizeAttribute()
    {
        $bytes = (int) $this->file_size;

        if ($bytes <= 0) {
            return '—';
        }

        $units = ['بایت', 'کیلوبایت', 'مگابایت'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
