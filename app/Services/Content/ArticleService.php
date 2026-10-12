<?php

namespace App\Services\Content;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleImage;
use App\Models\AuditLog;
use App\Services\AuditLogger;
use App\Services\Media\SecureImageUploader;
use Illuminate\Support\Facades\DB;

/**
 * سرویس مدیریت مقالات.
 *
 * مسئول: ایجاد/ویرایش مقاله، گردش‌کار وضعیت، مدیریت تصاویر.
 *
 * ── مسئولیت‌های امنیتی ──────────────────────────────────────────────
 *  • پاک‌سازی HTML محتوا با HtmlSanitizer (ضد XSS ذخیره‌شده)
 *  • آپلود امن تصاویر با SecureImageUploader
 *  • تولید Slug یکتا و امن
 *  • ثبت Audit Log برای هر تغییر وضعیت
 */
class ArticleService
{
    /**
     * @var \App\Services\AuditLogger
     */
    protected $audit;

    /**
     * @var \App\Services\Media\SecureImageUploader
     */
    protected $uploader;

    public function __construct(AuditLogger $audit, SecureImageUploader $uploader)
    {
        $this->audit = $audit;
        $this->uploader = $uploader;
    }

    // =========================================================
    // ایجاد و ویرایش
    // =========================================================

    /**
     * ایجاد مقاله جدید.
     *
     * @param  array    $data
     * @param  \App\User $author
     * @return \App\Models\Article
     */
    public function create(array $data, $author)
    {
        return DB::transaction(function () use ($data, $author) {

            $article = new Article();

            $article->title              = HtmlSanitizer::cleanText($data['title']);
            // Slug یکتا از عنوان یا مقدار ورودی ساخته می‌شود؛ همیشه امن و یکتا
            $article->slug               = $this->makeUniqueSlug(
                !empty($data['slug']) ? $data['slug'] : $data['title']
            );
            $article->excerpt            = HtmlSanitizer::cleanText(isset($data['excerpt']) ? $data['excerpt'] : null);
            $article->content            = HtmlSanitizer::clean(isset($data['content']) ? $data['content'] : '');
            $article->category_id        = isset($data['category_id']) ? $data['category_id'] : null;
            $article->author_name        = HtmlSanitizer::cleanText(isset($data['author_name']) ? $data['author_name'] : null);
            $article->meta_title         = HtmlSanitizer::cleanText(isset($data['meta_title']) ? $data['meta_title'] : null);
            $article->meta_description   = HtmlSanitizer::cleanText(isset($data['meta_description']) ? $data['meta_description'] : null);
            $article->canonical_url      = $this->safeUrl(isset($data['canonical_url']) ? $data['canonical_url'] : null);
            $article->featured_image_alt = HtmlSanitizer::cleanText(isset($data['featured_image_alt']) ? $data['featured_image_alt'] : null);
            $article->user_id            = $author->getKey();
            $article->status             = Article::STATUS_DRAFT;
            $article->is_featured        = !empty($data['is_featured']);
            $article->sort_order         = isset($data['sort_order']) ? (int) $data['sort_order'] : 0;
            $article->keywords           = $this->encodeKeywords(isset($data['keywords']) ? $data['keywords'] : null);

            // اگر نویسنده نام خود را وارد نکرده باشد، از نام کاربری استفاده کن
            if (empty($article->author_name)) {
                $article->author_name = $author->name;
            }

            $article->save();

            $this->audit->logModelChange(
                AuditLog::EVENT_ARTICLE_CREATED,
                $article,
                [],
                ['title' => $article->title, 'status' => $article->status],
                'ایجاد مقاله «' . $article->title . '»'
            );

            return $article;
        });
    }

    /**
     * ویرایش مقاله.
     *
     * @param  \App\Models\Article $article
     * @param  array               $data
     * @param  \App\User           $actor
     * @return \App\Models\Article
     *
     * @throws \RuntimeException
     */
    public function update(Article $article, array $data, $actor)
    {
        return DB::transaction(function () use ($article, $data, $actor) {

            $original = [
                'title'  => $article->title,
                'slug'   => $article->slug,
                'status' => $article->status,
            ];

            if (isset($data['title'])) {
                $article->title = HtmlSanitizer::cleanText($data['title']);
            }

            // تغییر Slug — تنها اگر صریحاً خواسته شده و مقاله منتشر نشده باشد
            if (!empty($data['slug']) && $article->status !== Article::STATUS_PUBLISHED) {
                $newSlug = $this->makeUniqueSlug($data['slug'], $article->getKey());
                if ($newSlug !== $article->slug) {
                    $article->slug = $newSlug;
                }
            }

            if (array_key_exists('excerpt', $data)) {
                $article->excerpt = HtmlSanitizer::cleanText($data['excerpt']);
            }
            if (array_key_exists('content', $data)) {
                $article->content = HtmlSanitizer::clean($data['content']);
            }
            if (array_key_exists('category_id', $data)) {
                $article->category_id = $data['category_id'] ?: null;
            }
            if (array_key_exists('author_name', $data)) {
                $article->author_name = HtmlSanitizer::cleanText($data['author_name']);
            }
            if (array_key_exists('meta_title', $data)) {
                $article->meta_title = HtmlSanitizer::cleanText($data['meta_title']);
            }
            if (array_key_exists('meta_description', $data)) {
                $article->meta_description = HtmlSanitizer::cleanText($data['meta_description']);
            }
            if (array_key_exists('canonical_url', $data)) {
                $article->canonical_url = $this->safeUrl($data['canonical_url']);
            }
            if (array_key_exists('featured_image_alt', $data)) {
                $article->featured_image_alt = HtmlSanitizer::cleanText($data['featured_image_alt']);
            }
            if (array_key_exists('is_featured', $data)) {
                $article->is_featured = (bool) $data['is_featured'];
            }
            if (array_key_exists('sort_order', $data)) {
                $article->sort_order = (int) $data['sort_order'];
            }
            if (array_key_exists('keywords', $data)) {
                $article->keywords = $this->encodeKeywords($data['keywords']);
            }

            $article->save();

            $this->audit->logModelChange(
                AuditLog::EVENT_ARTICLE_UPDATED,
                $article,
                $original,
                ['title' => $article->title, 'slug' => $article->slug],
                'ویرایش مقاله «' . $article->title . '»'
            );

            return $article;
        });
    }

    // =========================================================
    // گردش‌کار وضعیت
    // =========================================================

    /**
     * ارسال مقاله برای بررسی (draft → pending_review).
     *
     * @param  \App\Models\Article $article
     * @param  \App\User           $actor
     * @return \App\Models\Article
     *
     * @throws \RuntimeException
     */
    public function submitForReview(Article $article, $actor)
    {
        if ($article->status !== Article::STATUS_DRAFT) {
            throw new \RuntimeException('تنها مقاله‌های پیش‌نویس قابل ارسال برای بررسی هستند.');
        }

        if (empty(trim((string) $article->content))) {
            throw new \RuntimeException('مقاله بدون متن کامل قابل ارسال برای بررسی نیست.');
        }

        $article->status       = Article::STATUS_PENDING_REVIEW;
        $article->submitted_by = $actor->getKey();
        $article->submitted_at = now();
        $article->save();

        $this->audit->logModelChange(
            AuditLog::EVENT_ARTICLE_UPDATED,
            $article,
            ['status' => Article::STATUS_DRAFT],
            ['status' => Article::STATUS_PENDING_REVIEW],
            'ارسال مقاله «' . $article->title . '» برای بررسی'
        );

        return $article;
    }

    /**
     * انتشار مقاله (pending_review یا draft → published).
     *
     * @param  \App\Models\Article $article
     * @param  \App\User           $actor
     * @return \App\Models\Article
     *
     * @throws \RuntimeException
     */
    public function publish(Article $article, $actor)
    {
        if ($article->status === Article::STATUS_PUBLISHED) {
            throw new \RuntimeException('این مقاله از قبل منتشر شده است.');
        }

        if ($article->status === Article::STATUS_ARCHIVED) {
            throw new \RuntimeException('مقاله آرشیوشده قابل انتشار نیست. ابتدا آن را بازگردانید.');
        }

        // بررسی حداقل محتوا
        if (empty(trim((string) $article->title))) {
            throw new \RuntimeException('مقاله بدون عنوان قابل انتشار نیست.');
        }
        if (empty(trim((string) $article->content))) {
            throw new \RuntimeException('مقاله بدون متن کامل قابل انتشار نیست.');
        }

        $oldStatus = $article->status;

        $article->status       = Article::STATUS_PUBLISHED;
        $article->published_by = $actor->getKey();
        // حفظ تاریخ انتشار قبلی در صورت انتشار مجدد
        $article->published_at = $article->published_at ?: now();
        $article->save();

        $this->audit->logModelChange(
            AuditLog::EVENT_ARTICLE_PUBLISHED,
            $article,
            ['status' => $oldStatus],
            ['status' => Article::STATUS_PUBLISHED, 'published_at' => (string) $article->published_at],
            'انتشار مقاله «' . $article->title . '»'
        );

        return $article;
    }

    /**
     * لغو انتشار (published → draft).
     *
     * @param  \App\Models\Article $article
     * @param  \App\User           $actor
     * @return \App\Models\Article
     */
    public function unpublish(Article $article, $actor)
    {
        if ($article->status !== Article::STATUS_PUBLISHED) {
            throw new \RuntimeException('این مقاله منتشر نشده است.');
        }

        $article->status = Article::STATUS_DRAFT;
        $article->save();

        $this->audit->logModelChange(
            AuditLog::EVENT_ARTICLE_UPDATED,
            $article,
            ['status' => Article::STATUS_PUBLISHED],
            ['status' => Article::STATUS_DRAFT],
            'لغو انتشار مقاله «' . $article->title . '»'
        );

        return $article;
    }

    /**
     * آرشیو مقاله — به‌جای حذف فیزیکی مقاله‌های منتشرشده.
     *
     * دلیل: حذف فیزیکی مقاله منتشرشده، لینک‌های عمومی و سئو را می‌شکند.
     *
     * @param  \App\Models\Article $article
     * @param  \App\User           $actor
     * @return \App\Models\Article
     */
    public function archive(Article $article, $actor)
    {
        $oldStatus = $article->status;

        $article->status = Article::STATUS_ARCHIVED;
        $article->save();

        $this->audit->logModelChange(
            AuditLog::EVENT_ARTICLE_UPDATED,
            $article,
            ['status' => $oldStatus],
            ['status' => Article::STATUS_ARCHIVED],
            'آرشیو مقاله «' . $article->title . '»'
        );

        return $article;
    }

    /**
     * حذف مقاله.
     *
     * مقاله منتشرشده هرگز حذف فیزیکی نمی‌شود — آرشیو می‌گردد.
     * مقاله پیش‌نویس با Soft Delete حذف می‌شود.
     *
     * @param  \App\Models\Article $article
     * @param  \App\User           $actor
     * @return void
     */
    public function delete(Article $article, $actor)
    {
        DB::transaction(function () use ($article, $actor) {

            $snapshot = ['title' => $article->title, 'slug' => $article->slug, 'status' => $article->status];

            if ($article->status === Article::STATUS_PUBLISHED) {
                throw new \RuntimeException(
                    'مقاله منتشرشده حذف نمی‌شود. برای پنهان‌کردن آن از سایت، '
                    . 'ابتدا «لغو انتشار» و سپس «آرشیو» را انتخاب کنید.'
                );
            }

            // حذف فایل‌های تصویر مربوطه
            foreach ($article->images as $image) {
                $this->uploader->delete($image->path, 'public', ['uploads/articles']);
            }

            if ($article->featured_image) {
                $this->uploader->delete($article->featured_image, 'public', ['uploads/articles']);
            }

            // SoftDelete — قابل بازگشت
            $article->delete();

            $this->audit->log(
                AuditLog::EVENT_ARTICLE_DELETED,
                'حذف مقاله «' . $snapshot['title'] . '»',
                null,
                $snapshot,
                [],
                AuditLog::SEVERITY_NOTICE
            );
        });
    }

    // =========================================================
    // تصاویر
    // =========================================================

    /**
     * آپلود و ثبت تصویر شاخص مقاله.
     *
     * @param  \App\Models\Article            $article
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  \App\User                      $actor
     * @return \App\Models\Article
     *
     * @throws \RuntimeException
     */
    public function uploadFeaturedImage(Article $article, $file, $actor)
    {
        $result = $this->uploader->upload($file, 'uploads/articles', 'public');

        // حذف تصویر شاخص قبلی — جلوگیری از انباشت فایل بی‌استفاده
        if ($article->featured_image && $article->featured_image !== $result['path']) {
            $this->uploader->delete($article->featured_image, 'public', ['uploads/articles']);
        }

        $article->featured_image = $result['path'];
        $article->save();

        $this->audit->log(
            AuditLog::EVENT_ARTICLE_UPDATED,
            'آپلود تصویر شاخص برای مقاله «' . $article->title . '»',
            $article,
            [],
            ['featured_image' => $result['path'], 'mime' => $result['mime'], 'size' => $result['size']],
            AuditLog::SEVERITY_INFO
        );

        return $article;
    }

    /**
     * آپلود تصویر داخل متن مقاله.
     *
     * @param  \App\Models\Article           $article
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\User                     $actor
     * @param  array                         $meta  alt, caption
     * @return \App\Models\ArticleImage
     */
    public function uploadInlineImage(Article $article, $file, $actor, array $meta = [])
    {
        $result = $this->uploader->upload($file, 'uploads/articles', 'public');

        $maxSort = (int) ArticleImage::query()->where('article_id', $article->getKey())->max('sort_order');

        $image = ArticleImage::create([
            'article_id'    => $article->getKey(),
            'path'          => $result['path'],
            'original_name' => $result['original_name'],
            'alt'           => HtmlSanitizer::cleanText(isset($meta['alt']) ? $meta['alt'] : null),
            'caption'       => HtmlSanitizer::cleanText(isset($meta['caption']) ? $meta['caption'] : null),
            'file_size'     => $result['size'],
            'mime_type'     => $result['mime'],
            'sort_order'    => $maxSort + 1,
        ]);

        $this->audit->log(
            AuditLog::EVENT_ARTICLE_UPDATED,
            'آپلود تصویر داخل متن برای مقاله «' . $article->title . '»',
            $article,
            [],
            ['image_id' => $image->getKey(), 'path' => $result['path']],
            AuditLog::SEVERITY_INFO
        );

        return $image;
    }

    /**
     * حذف تصویر داخل متن.
     *
     * @param  \App\Models\ArticleImage $image
     * @param  \App\User                $actor
     * @return bool
     */
    public function deleteInlineImage(ArticleImage $image, $actor)
    {
        $article = $image->article;
        $path = $image->path;

        $deleted = $this->uploader->delete($path, 'public', ['uploads/articles']);

        $image->delete();

        $this->audit->log(
            AuditLog::EVENT_ARTICLE_UPDATED,
            'حذف تصویر داخل متن' . ($article ? ' از مقاله «' . $article->title . '»' : ''),
            null,
            ['path' => $path],
            [],
            AuditLog::SEVERITY_INFO
        );

        return $deleted;
    }

    // =========================================================
    // کمکی
    // =========================================================

    /**
     * تولید Slug یکتا و امن.
     *
     * ── نکته ──────────────────────────────────────────────────────
     * الگوی کاراکترها سخت‌گیرانه است تا امکان تزریق مسیر در URL
     * (مثل `../`) از بین برود. برای عنوان فارسی، بخش‌های غیرلاتین
     * در صورت خالی‌مماندن با یک hash کوتاه جایگزین می‌شوند.
     *
     * @param  string   $value
     * @param  int|null $exceptId
     * @return string
     */
    public function makeUniqueSlug($value, $exceptId = null)
    {
        $value = (string) $value;

        // اگر ورودی فارسی است، به لاتین تبدیل ساده نمی‌کنیم (بدون کتابخانه
        // ترانویسی)؛ در عوض از hash کوتاه + بخش لاتین استفاده می‌کنیم.
        $latin = strtolower(trim($value));
        $latin = preg_replace('/[^a-z0-9\s-]/u', ' ', $latin);
        $latin = trim(preg_replace('/[\s-]+/', '-', $latin), '-');

        if ($latin === '') {
            // عنوان تماماً غیرلاتین — از hash کوتاه استفاده کن
            $latin = 'article-' . substr(sha1($value), 0, 10);
        }

        $slug = mb_substr($latin, 0, 150);
        $base = $slug;
        $counter = 1;

        while (true) {
            $query = Article::withTrashed()->where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $base . '-' . (++$counter);

            // محافظ در برابر حلقه بی‌پایان
            if ($counter > 500) {
                $slug = $base . '-' . substr(sha1($value . microtime()), 0, 8);
                break;
            }
        }

        return $slug;
    }

    /**
     * تبدیل کلمات کلیدی به JSON.
     *
     * @param  mixed $keywords رشته جداشده با کاما یا آرایه
     * @return string|null
     */
    protected function encodeKeywords($keywords)
    {
        if (empty($keywords)) {
            return null;
        }

        if (is_string($keywords)) {
            $keywords = array_map('trim', explode(',', $keywords));
        }

        if (!is_array($keywords)) {
            return null;
        }

        $clean = [];
        foreach ($keywords as $keyword) {
            $keyword = HtmlSanitizer::cleanText($keyword);
            if ($keyword !== '') {
                $clean[] = mb_substr($keyword, 0, 100);
            }
        }

        $clean = array_slice(array_unique($clean), 0, 30);

        return empty($clean) ? null : json_encode(array_values($clean), JSON_UNESCAPED_UNICODE);
    }

    /**
     * اعتبارسنجی URL کانونیکال — فقط http/https.
     *
     * @param  string|null $url
     * @return string|null
     */
    protected function safeUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        $url = trim((string) $url);

        if (!preg_match('#^https?://#i', $url)) {
            return null;
        }

        return mb_substr($url, 0, 500);
    }
}
