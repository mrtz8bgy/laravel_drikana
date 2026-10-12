<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * مدل مقاله (Article).
 *
 * گردش‌کار وضعیت:
 *     draft ──submit──> pending_review ──publish──> published
 *       ^                     │                        │
 *       └─────unpublish───────┴────────────────────────┘
 *                             │
 *                          archive ──> archived
 *
 * @property int    $id
 * @property string $title
 * @property string $slug
 * @property string $status
 */
class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    // وضعیت‌های گردش‌کار
    const STATUS_DRAFT          = 'draft';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_PUBLISHED      = 'published';
    const STATUS_ARCHIVED       = 'archived';

    /**
     * مقادیر مجاز برای ستون status — برای اعتبارسنجی ورودی.
     *
     * @return array
     */
    public static function statuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PENDING_REVIEW,
            self::STATUS_PUBLISHED,
            self::STATUS_ARCHIVED,
        ];
    }

    /**
     * برچسب فارسی وضعیت‌ها.
     *
     * @return array
     */
    public static function statusLabels()
    {
        return [
            self::STATUS_DRAFT          => 'پیش‌نویس',
            self::STATUS_PENDING_REVIEW => 'در انتظار بررسی',
            self::STATUS_PUBLISHED      => 'منتشرشده',
            self::STATUS_ARCHIVED       => 'آرشیو شده',
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'category_id',
        'user_id',
        'author_name',
        'keywords',
        'meta_title',
        'meta_description',
        'canonical_url',
        'status',
        'is_featured',
        'sort_order',
        'submitted_by',
        'submitted_at',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'submitted_at' => 'datetime',
        'views_count'  => 'integer',
    ];

    // =========================================================
    // روابط
    // =========================================================

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * نویسنده / ثبت‌کننده مقاله.
     */
    public function author()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * تصاویر داخل متن.
     */
    public function images()
    {
        return $this->hasMany(ArticleImage::class, 'article_id')->orderBy('sort_order');
    }

    public function publisher()
    {
        return $this->belongsTo(\App\User::class, 'published_by');
    }

    // =========================================================
    // Accessors
    // =========================================================

    /**
     * کلمات کلیدی به‌صورت آرایه.
     *
     * @return array
     */
    public function getKeywordsArrayAttribute()
    {
        if (empty($this->keywords)) {
            return [];
        }

        $decoded = json_decode($this->keywords, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        // پشتیبانی از ورودی متنی جداشده با کاما
        return array_values(array_filter(array_map('trim', explode(',', $this->keywords))));
    }

    /**
     * برچسب فارسی وضعیت.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = self::statusLabels();

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * رنگ Bootstrap متناظر با وضعیت.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_DRAFT          => 'secondary',
            self::STATUS_PENDING_REVIEW => 'warning',
            self::STATUS_PUBLISHED      => 'success',
            self::STATUS_ARCHIVED       => 'dark',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * نام نمایشی نویسنده — اولویت با نام ثبت‌شده در مقاله.
     *
     * @return string
     */
    public function getDisplayAuthorAttribute()
    {
        if (!empty($this->author_name)) {
            return $this->author_name;
        }

        return $this->author ? $this->author->name : 'نامشخص';
    }

    /**
     * URL عمومی مقاله.
     *
     * @return string
     */
    public function getPublicUrlAttribute()
    {
        return route('articles.show', $this->slug);
    }

    /**
     * URL کامل تصویر شاخص.
     *
     * مسیر ذخیره‌شده در دیتابیس نسبی است (مثال: articles/2026/10/abc.jpg).
     * این دسترسی‌کننده آن را به آدرس عمومی کامل تبدیل می‌کند تا در
     * قالب‌ها مستقیم قابل استفاده باشد.
     *
     * نکته امنیتی: مسیر هرگز از ورودی کاربر ساخته نمی‌شود و مقدار آن
     * تنها از دیتابیسی می‌آید که با SecureImageUploader پر شده است.
     *
     * @return string
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (empty($this->featured_image)) {
            return '';
        }

        // اگر مقدار از قبل یک URL کامل بود، همان را برگردان
        if (preg_match('#^https?://#i', $this->featured_image)) {
            return $this->featured_image;
        }

        return asset('storage/' . ltrim($this->featured_image, '/'));
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
