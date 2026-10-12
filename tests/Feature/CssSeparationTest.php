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
    //  ۵) استایل درون‌خطی (بازبینی L-04)
    // =========================================================

    /**
     * پوشه ویوها.
     *
     * @return string
     */
    protected function viewsPath()
    {
        return base_path('resources/views');
    }

    public function test_utilities_css_exists_and_is_not_empty()
    {
        $path = public_path('css/drikana/utilities.css');

        $this->assertFileExists(
            $path,
            'فایل utilities.css باید وجود داشته باشد — کلاس‌های استخراج‌شده از '
            . 'استایل درون‌خطی ویوها در آن تعریف می‌شوند.'
        );

        $size = filesize($path);

        $this->assertGreaterThan(
            5000,
            $size,
            'utilities.css غیرمنتظره کوچک است (' . $size . ' بایت) — احتمالاً استخراج ناقص بوده.'
        );

        $content = file_get_contents($path);

        $this->assertGreaterThan(
            200,
            substr_count($content, '.u-'),
            'تعداد قواعد کلاس در utilities.css کمتر از انتظار است.'
        );

        // هر قاعده باید !important داشته باشد — وگرنه اولویت استایل
        // درون‌خطی حفظ نمی‌شود و ظاهر صفحه تغییر می‌کند.
        $this->assertStringContainsString(
            '!important',
            $content,
            'قواعد utilities.css باید !important داشته باشند تا اولویت '
            . 'استایل درون‌خطی قبلی حفظ شود.'
        );
    }

    public function test_no_static_inline_style_attributes_remain_in_views()
    {
        $offenders = [];

        foreach ($this->bladeFiles($this->viewsPath()) as $path) {
            $source = file_get_contents($path);

            if (preg_match_all('/style="([^"]*)"/', $source, $matches) === 0) {
                continue;
            }

            foreach ($matches[1] as $declaration) {
                // استایل پویا (وابسته به داده) استثناست؛ آن را نمی‌توان
                // به کلاس ثابت تبدیل کرد.
                if (strpos($declaration, '{{') !== false || strpos($declaration, '@') !== false) {
                    continue;
                }

                $offenders[] = str_replace(base_path(), '', $path) . ' → style="' . $declaration . '"';
            }
        }

        $this->assertSame(
            [],
            array_slice($offenders, 0, 20),
            'استایل درون‌خطی ایستا در ویوها باقی مانده است. این استایل‌ها باید '
            . 'کلاس‌دار شوند تا CSP سخت‌گیرانه (بدون unsafe-inline) کار کند.'
        );
    }

    public function test_every_extracted_class_is_defined_somewhere()
    {
        $utilities = file_get_contents(public_path('css/drikana/utilities.css'));
        $undefined = [];

        foreach ($this->bladeFiles($this->viewsPath()) as $path) {
            $source = file_get_contents($path);

            if (preg_match_all('/(?<![\w-])(u-[a-z0-9-]+)(?![\w-])/', $source, $matches) === 0) {
                continue;
            }

            foreach (array_unique($matches[1]) as $class) {
                if (strpos($utilities, '.' . $class . ' ') !== false
                    || strpos($utilities, '.' . $class . '{') !== false) {
                    continue;
                }

                // ویوهای PDF کلاس خود را درون همان فایل تعریف می‌کنند
                if (preg_match('/\.' . preg_quote($class, '/') . '\s*\{/', $source)) {
                    continue;
                }

                $undefined[] = str_replace(base_path(), '', $path) . ' → .' . $class;
            }
        }

        $this->assertSame(
            [],
            array_slice($undefined, 0, 20),
            'کلاس استخراج‌شده‌ای در ویو استفاده شده اما در هیچ CSS تعریف نشده است — '
            . 'یعنی استایل آن عنصر از دست رفته است.'
        );
    }

    public function test_pdf_views_keep_their_styles_inside_the_file()
    {
        $pdfViews = [
            'invoices/admin_invoice.blade.php',
            'invoices/customer_invoice.blade.php',
            'invoices/seller_invoice.blade.php',
            'downloads/brand.blade.php',
            'downloads/category.blade.php',
            'downloads/sub_category.blade.php',
            'downloads/sub_sub_category.blade.php',
            'downloads/user.blade.php',
        ];

        foreach ($pdfViews as $relative) {
            $path = $this->viewsPath() . '/' . $relative;

            $this->assertFileExists($path);

            $source = file_get_contents($path);

            // dompdf با isRemoteEnabled=false استایل خارجی را بارگذاری
            // نمی‌کند؛ اگر کلاس‌ها به فایل بیرونی منتقل شوند، PDF
            // بی‌استایل می‌شود. پس این ویوها باید تعریف درون‌فایلی داشته باشند.
            $this->assertNotContains(
                'utilities.css',
                $source,
                'ویو PDF «' . $relative . '» نباید به CSS بیرونی تکیه کند.'
            );
        }
    }

    public function test_layouts_link_the_utilities_file()
    {
        $layouts = [
            'layouts/app.blade.php',
            'layouts/blank.blade.php',
            'layouts/login.blade.php',
            'frontend/layouts/app.blade.php',
            'admin/jewelry/layouts/app.blade.php',
            'auth/register.blade.php',
        ];

        foreach ($layouts as $relative) {
            $source = file_get_contents($this->viewsPath() . '/' . $relative);

            $this->assertContains(
                'css/drikana/utilities.css',
                $source,
                'قالب «' . $relative . '» باید utilities.css را بارگذاری کند.'
            );
        }
    }

    public function test_rendered_page_has_no_static_inline_style()
    {
        $user = $this->makeUser(['customer']);
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $body = $response->getContent();

        preg_match_all('/style="([^"]*)"/', $body, $matches);

        // استثنا: هر صفتی که نشانی (URL) دارد، ذاتاً پویاست — مثل
        // `background-image:url('{{ asset(...) }}')`. چنین صفتی را
        // نمی‌توان به کلاس ثابت تبدیل کرد و در ویو باقی می‌ماند.
        $static = [];

        foreach ($matches[1] as $declaration) {
            if (stripos($declaration, 'url(') !== false) {
                continue;
            }

            $static[] = $declaration;
        }

        $this->assertSame(
            [],
            array_slice($static, 0, 10),
            'خروجی HTML صفحه اصلی هنوز صفت style ایستا دارد — یعنی استایل '
            . 'درون‌خطی به کلاس تبدیل نشده است.'
        );

        $this->assertStringContainsString(
            'css/drikana/utilities.css',
            $body,
            'صفحه اصلی باید utilities.css را لینک کند، وگرنه کلاس‌های '
            . 'استخراج‌شده استایل نمی‌گیرند.'
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
