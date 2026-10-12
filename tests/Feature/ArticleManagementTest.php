<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\AuditLog;
use App\Models\Role;
use App\Services\Content\ArticleService;
use App\Services\Content\HtmlSanitizer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * تست‌های ماژول مقالات و مدیریت تصاویر.
 *
 * پوشش:
 *   • گردش‌کار draft → pending_review → published
 *   • پاک‌سازی XSS در محتوا، عنوان و توضیحات
 *   • اعتبارسنجی نوع واقعی فایل آپلودی
 *   • Slug یکتا و امن
 *   • دسترسی فقط به مقاله‌های منتشرشده در سایت عمومی
 *   • ثبت Audit Log
 */
class ArticleManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \App\Services\Content\ArticleService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ArticleService::class);
    }

    /**
     * ساخت کاربر با نقش مشخص.
     */
    protected function makeUser(array $roleSlugs = [])
    {
        $user = new User();
        $user->name      = 'کاربر محتوا';
        $user->email     = 'c' . uniqid() . '@example.com';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = 'customer';
        $user->role      = 'customer';
        $user->save();

        if (!empty($roleSlugs)) {
            $roleIds = Role::query()->whereIn('slug', $roleSlugs)->pluck('id')->all();
            $payload = [];
            foreach ($roleIds as $roleId) {
                $payload[$roleId] = [
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            $user->roles()->sync($payload);
        }

        $user->flushPermissionCache();

        return $user->fresh();
    }

    // =========================================================
    // ۱) ایجاد مقاله
    // =========================================================

    /** @test */
    public function article_is_created_as_draft_by_default()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'راهنمای خرید طلا',
            'content' => '<p>متن مقاله درباره <strong>طلا</strong></p>',
        ], $author);

        $this->assertEquals(Article::STATUS_DRAFT, $article->status);
        $this->assertEquals($author->getKey(), $article->user_id);
        $this->assertNotEmpty($article->slug);
    }

    /** @test */
    public function article_slug_is_unique()
    {
        $author = $this->makeUser(['content_manager']);

        $first = $this->service->create([
            'title'   => 'نگهداری جواهرات',
            'content' => '<p>متن اول</p>',
        ], $author);

        $second = $this->service->create([
            'title'   => 'نگهداری جواهرات',
            'content' => '<p>متن دوم</p>',
        ], $author);

        $this->assertNotEquals($first->slug, $second->slug,
            'Slug مقاله‌ها باید یکتا باشد.');
    }

    /** @test */
    public function slug_contains_only_safe_characters()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'      => 'راهنمای خرید طلا و جواهر',
            'slug'       => '../../../etc/passwd--evil<script>',
            'content'    => '<p>متن</p>',
        ], $author);

        // Slug نباید شامل کاراکترهای خطرناک مسیر یا HTML باشد
        $this->assertRegExp('/^[a-z0-9\-]+$/', $article->slug,
            'Slug باید فقط شامل حروف کوچک لاتین، رقم و خط تیره باشد.');
        $this->assertStringNotContainsString('..', $article->slug);
        $this->assertStringNotContainsString('<', $article->slug);
    }

    // =========================================================
    // ۲) پاک‌سازی XSS
    // =========================================================

    /** @test */
    public function script_tags_are_stripped_from_content()
    {
        $author = $this->makeUser(['content_manager']);

        $malicious = '<p>متن سالم</p><script>alert(document.cookie)</script><p>ادامه</p>';

        $article = $this->service->create([
            'title'   => 'مقاله تست XSS',
            'content' => $malicious,
        ], $author);

        $this->assertStringNotContainsString('<script', $article->content);
        $this->assertStringNotContainsString('alert(', $article->content);
        $this->assertStringContainsString('متن سالم', $article->content);
    }

    /** @test */
    public function event_handler_attributes_are_removed()
    {
        $author = $this->makeUser(['content_manager']);

        $malicious = '<p onclick="stealCookies()">کلیک کنید</p><img src="/x.jpg" onerror="alert(1)">';

        $article = $this->service->create([
            'title'   => 'مقاله تست رویداد',
            'content' => $malicious,
        ], $author);

        $this->assertStringNotContainsString('onclick', $article->content);
        $this->assertStringNotContainsString('onerror', $article->content);
    }

    /** @test */
    public function javascript_urls_are_removed()
    {
        $author = $this->makeUser(['content_manager']);

        $malicious = '<a href="javascript:alert(1)">لینک خطرناک</a><a href="https://example.com">لینک سالم</a>';

        $article = $this->service->create([
            'title'   => 'مقاله تست لینک',
            'content' => $malicious,
        ], $author);

        $this->assertStringNotContainsString('javascript:', $article->content);
        $this->assertStringContainsString('https://example.com', $article->content);
    }

    /** @test */
    public function iframe_and_object_tags_are_removed()
    {
        $author = $this->makeUser(['content_manager']);

        $malicious = '<iframe src="https://evil.com"></iframe><object data="x"></object><p>متن</p>';

        $article = $this->service->create([
            'title'   => 'مقاله تست iframe',
            'content' => $malicious,
        ], $author);

        $this->assertStringNotContainsString('<iframe', $article->content);
        $this->assertStringNotContainsString('<object', $article->content);
        $this->assertStringContainsString('متن', $article->content);
    }

    /** @test */
    public function title_is_stripped_of_html()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => '<script>alert(1)</script>عنوان واقعی',
            'content' => '<p>متن</p>',
        ], $author);

        $this->assertStringNotContainsString('<script', $article->title);
        $this->assertStringContainsString('عنوان واقعی', $article->title);
    }

    /** @test */
    public function inline_style_attributes_are_removed()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'تست استایل',
            'content' => '<p style="background:url(javascript:alert(1))">متن</p>',
        ], $author);

        $this->assertStringNotContainsString('style=', $article->content);
    }

    // =========================================================
    // ۳) گردش‌کار وضعیت
    // =========================================================

    /** @test */
    public function draft_can_be_submitted_for_review()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله برای بررسی',
            'content' => '<p>متن کامل</p>',
        ], $author);

        $this->service->submitForReview($article, $author);

        $this->assertEquals(Article::STATUS_PENDING_REVIEW, $article->fresh()->status);
        $this->assertNotNull($article->fresh()->submitted_at);
    }

    /** @test */
    public function empty_article_cannot_be_submitted_for_review()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله خالی',
            'content' => '',
        ], $author);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('بدون متن کامل');

        $this->service->submitForReview($article, $author);
    }

    /** @test */
    public function article_can_be_published_and_unpublished()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله انتشار',
            'content' => '<p>متن</p>',
        ], $author);

        $this->service->submitForReview($article, $author);
        $this->service->publish($article->fresh(), $author);

        $fresh = $article->fresh();
        $this->assertEquals(Article::STATUS_PUBLISHED, $fresh->status);
        $this->assertNotNull($fresh->published_at);
        $this->assertEquals($author->getKey(), $fresh->published_by);

        $this->service->unpublish($fresh, $author);
        $this->assertEquals(Article::STATUS_DRAFT, $article->fresh()->status);
    }

    /** @test */
    public function published_article_cannot_be_deleted()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله منتشرشده',
            'content' => '<p>متن</p>',
        ], $author);

        $this->service->submitForReview($article, $author);
        $this->service->publish($article->fresh(), $author);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('مقاله منتشرشده حذف نمی‌شود');

        $this->service->delete($article->fresh(), $author);
    }

    /** @test */
    public function publishing_requires_a_title()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'عنوان موقت',
            'content' => '<p>متن</p>',
        ], $author);

        // عنوان را به‌صورت مستقیم خالی می‌کنیم
        $article->title = '';
        $article->save();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('بدون عنوان');

        $this->service->publish($article->fresh(), $author);
    }

    // =========================================================
    // ۴) آپلود تصویر
    // =========================================================

    /** @test */
    public function valid_image_can_be_uploaded_as_featured()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله با تصویر',
            'content' => '<p>متن</p>',
        ], $author);

        // ساخت یک تصویر PNG واقعی و معتبر
        $file = UploadedFile::fake()->image('gold.jpg', 300, 200);

        $this->service->uploadFeaturedImage($article, $file, $author);

        $this->assertNotEmpty($article->fresh()->featured_image);
    }

    /** @test */
    public function php_file_disguised_as_image_is_rejected()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله تست فایل مخرب',
            'content' => '<p>متن</p>',
        ], $author);

        // فایل PHP با پسوند jpg — بررسی پسوند کافی نیست
        $fakeImage = UploadedFile::fake()->create('shell.jpg', 100, 'image/jpeg');

        // چون محتوای واقعی تصویر نیست، getimagesize هم رد می‌کند
        $this->expectException(\RuntimeException::class);

        $this->service->uploadFeaturedImage($article, $fakeImage, $author);
    }

    /** @test */
    public function oversized_image_is_rejected()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله تست حجم',
            'content' => '<p>متن</p>',
        ], $author);

        // تصویر بزرگ‌تر از ۳ مگابایت
        $file = UploadedFile::fake()->image('huge.jpg', 500, 500)->size(4096); // ۴ مگابایت

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('حجم تصویر');

        $this->service->uploadFeaturedImage($article, $file, $author);
    }

    // =========================================================
    // ۵) دسترسی عمومی
    // =========================================================

    /** @test */
    public function published_article_is_visible_on_public_page()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله عمومی قابل مشاهده',
            'content' => '<p>محتوای عمومی</p>',
        ], $author);

        $this->service->submitForReview($article, $author);
        $this->service->publish($article->fresh(), $author);
        $article = $article->fresh();

        $response = $this->get('/articles/' . $article->slug);

        $response->assertStatus(200);
        $response->assertSee('مقاله عمومی قابل مشاهده');
    }

    /** @test */
    public function draft_article_is_not_accessible_publicly()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'پیش‌نویس محرمانه',
            'content' => '<p>محتوای منتشرنشده</p>',
        ], $author);

        $response = $this->get('/articles/' . $article->slug);

        $response->assertStatus(404);
    }

    /** @test */
    public function guest_cannot_access_admin_articles_panel()
    {
        $response = $this->get('/admin/articles');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_without_permission_cannot_access_admin_articles_panel()
    {
        $user = $this->makeUser(['customer']);

        $response = $this->actingAs($user)->get('/admin/articles');

        $response->assertStatus(403);
    }

    /** @test */
    public function content_manager_can_access_admin_articles_panel()
    {
        $user = $this->makeUser(['content_manager']);

        $response = $this->actingAs($user)->get('/admin/articles');

        $this->assertNotEquals(403, $response->getStatusCode());
    }

    // =========================================================
    // ۶) دسته‌بندی
    // =========================================================

    /** @test */
    public function category_slug_is_unique_and_safe()
    {
        $slug1 = ArticleCategory::makeUniqueSlug('سنگ‌های قیمتی');
        $slug2 = ArticleCategory::makeUniqueSlug('سنگ‌های قیمتی');

        $this->assertNotEquals($slug1, $slug2);

        $category = ArticleCategory::create([
            'name'       => 'تست امنیت',
            'slug'       => ArticleCategory::makeUniqueSlug('../../etc/passwd'),
            'is_active'  => true,
        ]);

        $this->assertRegExp('/^[a-z0-9\-]+$/', $category->slug);
    }

    // =========================================================
    // ۷) Audit Log
    // =========================================================

    /** @test */
    public function article_creation_is_recorded_in_audit_log()
    {
        $author = $this->makeUser(['content_manager']);

        $this->service->create([
            'title'   => 'مقاله با لاگ',
            'content' => '<p>متن</p>',
        ], $author);

        $this->assertDatabaseHas('audit_logs', ['event' => AuditLog::EVENT_ARTICLE_CREATED]);
    }

    /** @test */
    public function article_publication_is_recorded_in_audit_log()
    {
        $author = $this->makeUser(['content_manager']);

        $article = $this->service->create([
            'title'   => 'مقاله انتشار با لاگ',
            'content' => '<p>متن</p>',
        ], $author);

        $this->service->submitForReview($article, $author);
        $this->service->publish($article->fresh(), $author);

        $this->assertDatabaseHas('audit_logs', ['event' => AuditLog::EVENT_ARTICLE_PUBLISHED]);
    }

    // =========================================================
    // ۸) فهرست عمومی
    // =========================================================

    /** @test */
    public function articles_index_page_loads()
    {
        $response = $this->get('/articles');

        $response->assertStatus(200);
    }

    /** @test */
    public function article_search_does_not_crash_on_special_characters()
    {
        $author = $this->makeUser(['content_manager']);

        // یک مقاله منتشرشده با عنوان مشخص می‌سازیم
        $target = $this->service->create([
            'title'   => 'مقاله هدف برای تست تزریق',
            'content' => '<p>متن</p>',
        ], $author);
        $this->service->submitForReview($target, $author);
        $this->service->publish($target->fresh(), $author);

        // یک مقاله منتشرشده دیگر با عنوان کاملاً متفاوت
        $other = $this->service->create([
            'title'   => 'مقاله بی‌ربط دیگر',
            'content' => '<p>متن</p>',
        ], $author);
        $this->service->submitForReview($other, $author);
        $this->service->publish($other->fresh(), $author);

        // تلاش برای تزریق SQL از طریق پارامتر جست‌وجو
        $response = $this->get('/articles?q=' . urlencode("' OR 1=1 --"));

        // نباید خطای ۵۰۰ بدهد
        $response->assertStatus(200);

        // اگر تزریق موفق شده بود، شرط جست‌وجو حذف می‌شد و همه مقالات
        // بازگردانده می‌شدند. در حالی که پارامتر باید به‌عنوان یک رشته
        // متنی جست‌وجو شود و مقاله‌ای را پیدا نکند.
        $response->assertDontSee('مقاله هدف برای تست تزریق');
        $response->assertDontSee('مقاله بی‌ربط دیگر');
    }

    /** @test */
    public function article_sort_parameter_is_whitelisted()
    {
        // تلاش برای تزریق از طریق پارامتر مرتب‌سازی
        $response = $this->get('/articles?sort=id;DROP TABLE articles--');

        $response->assertStatus(200);

        // جدول مقالات باید هنوز وجود داشته باشد
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('articles'),
            'پارامتر مرتب‌سازی باید در فهرست سفید بررسی شود تا SQL Injection رخ ندهد.'
        );
    }
}
