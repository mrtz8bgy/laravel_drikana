<?php

namespace Tests\Feature;

use App\Models\ArticleCategory;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\Role;
use App\Services\Content\ArticleService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * آزمون جداسازی CSS (مرحله ۳ بازسازی).
 *
 * هدف: اطمینان از اینکه:
 *   ۱) فایل‌های CSS استخراج‌شده واقعاً وجود دارند؛
 *   ۲) در خروجی HTML صفحه‌ها با تگ <link> بارگذاری می‌شوند؛
 *   ۳) هیچ بلوک <style> سنگینی در صفحه‌های اصلی باقی نمانده است؛
 *   ۴) استایل‌ها پس از فایل‌های پایه بارگذاری می‌شوند (ترتیب Cascade).
 *
 * چرا لازم است؟ انتقال CSS از Blade به فایل مستقل اگر اشتباه انجام شود،
 * «بی‌صدا» ظاهر را خراب می‌کند: صفحه ۲۰۰ برمی‌گرداند، تست‌ها پاس می‌شوند،
 * اما استایل بارگذاری نمی‌شود. این تست همان شکست بی‌صدا را می‌گیرد.
 */
class CssSeparationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * فایل‌های CSS که باید وجود داشته باشند.
     *
     * @var array<int,string>
     */
    protected static $expectedFiles = [
        'css/drikana/store.css',
        'css/drikana/store-category.css',
        'css/drikana/store-menu.css',
        'css/drikana/jewelry-admin.css',
        'css/drikana/jewelry-certificates.css',
        'css/drikana/jewelry-owners.css',
        'css/drikana/auth.css',
        'css/drikana/auth-register.css',
        'css/drikana/admin.css',
        'css/drikana/ownership.css',
        'css/drikana/blank.css',
        'css/drikana/print.css',
        'css/drikana/articles.css',
        'css/drikana/repair.css',
    ];

    /**
     * @param  array $roleSlugs
     * @return \App\User
     */
    protected function makeUser(array $roleSlugs = [])
    {
        $user = new User();
        $user->name      = 'کاربر آزمون CSS';
        $user->email     = 'css' . uniqid() . '@example.com';
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
    // ۱) وجود فایل‌ها
    // =========================================================

    /** @test */
    public function all_extracted_css_files_exist_and_are_not_empty()
    {
        foreach (static::$expectedFiles as $relative) {
            $path = public_path($relative);

            $this->assertFileExists($path, 'فایل CSS یافت نشد: ' . $relative);

            $size = filesize($path);
            $this->assertGreaterThan(
                200,
                $size,
                'فایل CSS «' . $relative . '» بسیار کوچک است (' . $size . ' بایت) — احتمالاً خالی منتقل شده.'
            );
        }
    }

    /** @test */
    public function no_blade_view_still_contains_a_large_inline_style_block()
    {
        $viewsRoot = resource_path('views');

        $offenders = [];

        foreach ($this->bladeFiles($viewsRoot) as $file) {
            $content = file_get_contents($file);

            if (preg_match_all('/<style[^>]*>(.*?)<\/style>/s', $content, $matches)) {
                foreach ($matches[1] as $block) {
                    if (strlen($block) > 3000) {
                        $offenders[] = str_replace($viewsRoot . '/', '', $file)
                            . ' (' . strlen($block) . ' بایت)';
                    }
                }
            }
        }

        $this->assertEmpty(
            $offenders,
            "بلوک CSS درون‌خطی سنگین باقی مانده است:\n  - " . implode("\n  - ", $offenders)
        );
    }

    // =========================================================
    // ۲) بارگذاری واقعی در صفحه‌ها
    // =========================================================

    /** @test */
    public function admin_dashboard_loads_extracted_css()
    {
        $admin = $this->makeUser(['super_admin']);

        $html = $this->actingAs($admin)->get('/admin')->getContent();

        $this->assertContains('css/drikana/admin.css', $html,
            'صفحه داشبورد مدیریت (/admin) فایل CSS استخراج‌شده خود را بارگذاری نکرده است.');
    }

    /** @test */
    public function jewelry_admin_pages_load_extracted_css()
    {
        $admin = $this->makeUser(['super_admin']);

        $html = $this->actingAs($admin)
            ->get('/admin/jewelry/certificates')
            ->getContent();

        // قالب پنل جواهرات باید فایل پایه خود را بارگذاری کند
        $this->assertContains('css/drikana/jewelry-admin.css', $html,
            'قالب پنل جواهرات فایل CSS پایه خود را بارگذاری نکرده است.');
    }

    /** @test */
    public function frontend_pages_load_their_extracted_css()
    {
        $manager = $this->makeUser(['content_manager']);
        $service = app(ArticleService::class);

        $category = ArticleCategory::create([
            'name'      => 'دسته آزمون CSS',
            'slug'      => ArticleCategory::makeUniqueSlug('دسته آزمون CSS'),
            'is_active' => true,
        ]);

        $article = $service->create([
            'title'       => 'مقاله آزمون CSS',
            'content'     => '<p>متن</p>',
            'category_id' => $category->id,
        ], $manager);
        $service->submitForReview($article, $manager);
        $service->publish($article->fresh(), $manager);

        // صفحه مقالات
        $html = $this->get('/articles')->getContent();
        $this->assertNotFalse(strpos($html, 'drikana'), 'صفحه مقالات رندر نشد.');

        // صفحه استعلام اصالت شناسنامه — CSS آن به ownership.css منتقل شده است
        $html = $this->get('/jewelry/certificate-verify')->getContent();
        $this->assertContains('css/drikana/ownership.css', $html,
            'صفحه استعلام اصالت، فایل CSS خود را بارگذاری نکرده است.');
    }

    /** @test */
    public function ownership_and_report_pages_load_ownership_css()
    {
        $html = $this->get('/jewelry/report-stolen')->getContent();

        $this->assertContains('css/drikana/ownership.css', $html,
            'صفحه اعلام سرقت، فایل CSS خود را بارگذاری نکرده است.');
    }

    // =========================================================
    // ۳) ترتیب بارگذاری (Cascade)
    // =========================================================

    /** @test */
    public function page_styles_load_after_base_stylesheets()
    {
        $admin = $this->makeUser(['super_admin']);

        $html = $this->actingAs($admin)->get('/admin')->getContent();

        $basePos = strpos($html, 'css/bootstrap.min.css');
        $pagePos = strpos($html, 'css/drikana/admin.css');

        $this->assertNotFalse($basePos, 'استایل پایه bootstrap در صفحه یافت نشد.');
        $this->assertNotFalse($pagePos, 'استایل اختصاصی صفحه در صفحه یافت نشد.');

        $this->assertGreaterThan(
            $basePos,
            $pagePos,
            'استایل اختصاصی صفحه باید «پس از» استایل‌های پایه بارگذاری شود؛ '
            . 'در غیر این صورت قواعد پایه آن را بازنویسی می‌کنند و ظاهر صفحه تغییر می‌کند.'
        );
    }

    /** @test */
    public function jewelry_layout_loads_base_css_before_page_css()
    {
        $admin = $this->makeUser(['super_admin']);

        $html = $this->actingAs($admin)
            ->get('/admin/jewelry/certificates')
            ->getContent();

        $layoutPos = strpos($html, 'css/drikana/jewelry-admin.css');
        $pagePos   = strpos($html, 'css/drikana/jewelry-certificates.css');

        $this->assertNotFalse($layoutPos, 'CSS قالب پنل جواهرات بارگذاری نشده است.');
        $this->assertNotFalse($pagePos, 'CSS صفحه فهرست شناسنامه‌ها بارگذاری نشده است.');

        $this->assertLessThan(
            $pagePos,
            $layoutPos,
            'استایل قالب پنل جواهرات باید پیش از استایل صفحه بارگذاری شود تا '
            . 'استایل صفحه بر آن اولویت داشته باشد (همان ترتیب قبلی).'
        );
    }

    // =========================================================
    // ۴) ابزار کمکی
    // =========================================================

    /**
     * فهرست بازگشتی فایل‌های Blade.
     *
     * @param  string $dir
     * @return array<int,string>
     */
    protected function bladeFiles($dir)
    {
        $out = [];

        foreach (scandir($dir) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $dir . '/' . $entry;

            if (is_dir($path)) {
                $out = array_merge($out, $this->bladeFiles($path));
            } elseif (substr($entry, -10) === '.blade.php') {
                $out[] = $path;
            }
        }

        return $out;
    }
}
