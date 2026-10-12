<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * دسته‌بندی مقاله (Article Category).
 *
 * از ساختار درختی ساده (parent_id) پشتیبانی می‌کند اما در UI فعلی تنها
 * یک سطح نمایش داده می‌شود.
 */
class ArticleCategory extends Model
{
    protected $table = 'article_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * مقاله‌های این دسته.
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * مقاله‌های منتشرشده این دسته.
     */
    public function publishedArticles()
    {
        return $this->hasMany(Article::class, 'category_id')->published();
    }

    /**
     * دسته والد.
     */
    public function parent()
    {
        return $this->belongsTo(ArticleCategory::class, 'parent_id');
    }

    /**
     * دسته‌های فرزند.
     */
    public function children()
    {
        return $this->hasMany(ArticleCategory::class, 'parent_id');
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // =========================================================
    // متدهای دامنه
    // =========================================================

    /**
     * ساخت Slug یکتا از نام.
     *
     * ── نکته امنیتی ───────────────────────────────────────────────
     * Slug همیشه با الگوی کاراکترهای مجاز پاک می‌شود تا امکان تزریق
     * مسیر (Path Traversal) یا XSS از طریق URL از بین برود.
     * برای نام‌های فارسی که پس از پاک‌سازی خالی می‌مانند، از hash
     * استفاده می‌شود تا slug معتبر و یکتا باقی بماند.
     *
     * @param  string   $name
     * @param  int|null $exceptId
     * @return string
     */
    public static function makeUniqueSlug($name, $exceptId = null)
    {
        $slug = self::slugify($name);

        // اگر عنوان فقط فارسی/عربی باشد، slug لاتین خالی می‌ماند. در این حالت
        // به‌جای یک نام عمومی تکراری مانند «category»، از هش یکتا استفاده
        // می‌کنیم تا هر دسته آدرس یکتای خودش را داشته باشد و آدرس‌ها حدس‌زدنی
        // نباشند (کاهش سطح حمله enumerate).
        if ($slug === '') {
            $slug = 'category-' . substr(sha1((string) $name . microtime(true)), 0, 8);
        }

        $base = $slug;
        $counter = 1;

        while (true) {
            $query = static::query()->where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $base . '-' . (++$counter);
        }

        return $slug;
    }

    /**
     * تبدیل رشته به slug امن.
     *
     * @param  string $value
     * @return string
     */
    public static function slugify($value)
    {
        // حذف کاراکترهای غیرمجاز — فقط حروف لاتین، رقم و خط تیره
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/u', '-', $value);
        $value = trim(preg_replace('/-+/', '-', $value), '-');

        return $value;
    }
}
