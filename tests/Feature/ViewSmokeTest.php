<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\Role;
use App\Services\Content\ArticleService;
use App\Services\Ownership\OwnershipTransferService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * تست دودی (Smoke Test) تمام صفحات جدید.
 *
 * هدف: اطمینان از اینکه هر صفحه واقعاً «رندر» می‌شود و خطای Blade
 * (متغیر تعریف‌نشده، سینتکس نادرست، ویوی گمشده) ندارد.
 *
 * چرا لازم است؟
 *   در این پروژه چند ویو وجود نداشت یا سینتکس Blade آن‌ها نادرست بود
 *   (مثلاً `@section('name', $عبارت_پیچیده)` که باعث بازماندن output
 *   buffer می‌شود). این نوع خطاها فقط با رندر واقعی صفحه آشکار می‌شوند،
 *   نه با بررسی وجود فایل.
 *
 * نکته: این تست‌ها وضعیت HTTP را «غیر از ۵۰۰» می‌سنجند. کد ۴۰۳ برای
 * صفحات غیرمجاز رفتار صحیح است و نباید باعث شکست تست شود.
 */
class ViewSmokeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ساخت کاربر با نقش مشخص.
     *
     * @param  array $roleSlugs
     * @return \App\User
     */
    protected function makeUser(array $roleSlugs = [])
    {
        $user = new User();
        $user->name      = 'کاربر آزمایشی';
        $user->email     = 'smoke' . uniqid() . '@example.com';
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

    /**
     * بررسی اینکه صفحه بدون خطای سرور رندر می‌شود.
     *
     * @param  \App\User $user
     * @param  string    $uri
     * @param  string    $label
     * @return void
     */
    protected function assertPageRenders(User $user, $uri, $label)
    {
        $response = $this->actingAs($user)->get($uri);

        $status = $response->getStatusCode();

        // ۵۰۰ یعنی خطای واقعی در رندر ویو
        $this->assertNotEquals(
            500,
            $status,
            'صفحه «' . $label . '» (' . $uri . ') با خطای ۵۰۰ رندر نشد.'
        );

        // ۴۰۴ نباید برای صفحات موجود رخ دهد
        $this->assertNotEquals(
            404,
            $status,
            'صفحه «' . $label . '» (' . $uri . ') یافت نشد (۴۰۴) — احتمالاً ویو یا روت اشتباه است.'
        );
    }

    // =========================================================
    // صفحات مدیریتی RBAC
    // =========================================================

    /** @test */
    public function all_rbac_admin_pages_render()
    {
        $admin = $this->makeUser(['super_admin']);

        $pages = [
            '/admin/roles'                => 'فهرست نقش‌ها',
            '/admin/roles/create'         => 'ایجاد نقش',
            '/admin/users'                => 'فهرست کاربران',
            '/admin/permissions'          => 'فهرست مجوزها',
            '/admin/audit-logs'           => 'گزارش‌های امنیتی',
        ];

        foreach ($pages as $uri => $label) {
            $this->assertPageRenders($admin, $uri, $label);
        }
    }

    /** @test */
    public function role_detail_pages_render()
    {
        $admin = $this->makeUser(['super_admin']);
        $role  = Role::query()->where('slug', 'content_manager')->first();

        $this->assertPageRenders($admin, '/admin/roles/' . $role->id, 'جزئیات نقش');
        $this->assertPageRenders($admin, '/admin/roles/' . $role->id . '/edit', 'ویرایش نقش');
    }

    /** @test */
    public function user_permission_pages_render()
    {
        $admin  = $this->makeUser(['super_admin']);
        $target = $this->makeUser(['customer']);

        $this->assertPageRenders($admin, '/admin/users/' . $target->id, 'جزئیات کاربر');
        $this->assertPageRenders($admin, '/admin/users/' . $target->id . '/roles', 'نمایش نقش‌های کاربر');
    }

    // =========================================================
    // صفحات مقالات (ادمین)
    // =========================================================

    /** @test */
    public function all_admin_article_pages_render()
    {
        $manager = $this->makeUser(['content_manager']);

        $pages = [
            '/admin/articles'            => 'فهرست مقالات',
            '/admin/articles/create'     => 'ایجاد مقاله',
            '/admin/articles/categories' => 'دسته‌بندی مقالات',
        ];

        foreach ($pages as $uri => $label) {
            $this->assertPageRenders($manager, $uri, $label);
        }
    }

    /** @test */
    public function admin_article_edit_and_show_pages_render()
    {
        $manager = $this->makeUser(['content_manager']);

        $article = app(ArticleService::class)->create([
            'title'   => 'مقاله آزمایشی برای رندر',
            'content' => '<p>متن آزمایشی</p>',
        ], $manager);

        $this->assertPageRenders($manager, '/admin/articles/' . $article->id . '/edit', 'ویرایش مقاله');
        $this->assertPageRenders($manager, '/admin/articles/' . $article->id, 'پیش‌نمایش مقاله');
    }

    // =========================================================
    // صفحات عمومی مقالات
    // =========================================================

    /** @test */
    public function public_article_pages_render()
    {
        $manager = $this->makeUser(['content_manager']);
        $service = app(ArticleService::class);

        $category = ArticleCategory::create([
            'name'      => 'سنگ‌های قیمتی',
            'slug'      => ArticleCategory::makeUniqueSlug('سنگ‌های قیمتی'),
            'is_active' => true,
        ]);

        $article = $service->create([
            'title'       => 'راهنمای نگهداری طلا',
            'excerpt'     => 'خلاصه مقاله',
            'content'     => '<p>متن کامل مقاله آزمایشی</p>',
            'category_id' => $category->id,
            'keywords'    => 'طلا, جواهر',
        ], $manager);

        $service->submitForReview($article, $manager);
        $service->publish($article->fresh(), $manager);

        $article = $article->fresh();

        $this->assertPageRenders($manager, '/articles', 'فهرست مقالات');
        $this->assertPageRenders($manager, '/articles?q=طلا', 'جست‌وجوی مقالات');
        $this->assertPageRenders($manager, '/articles/category/' . $category->slug, 'مقالات یک دسته');
        $this->assertPageRenders($manager, '/articles/' . $article->slug, 'نمایش مقاله');
    }

    // =========================================================
    // صفحات مالکیت
    // =========================================================

    /** @test */
    public function admin_transfer_pages_render()
    {
        $admin = $this->makeUser(['super_admin']);

        // ساخت داده آزمایشی
        $ownerA = new JewelryOwner();
        $ownerA->user_id   = $admin->id;
        $ownerA->full_name = 'مالک مبدأ';
        $ownerA->phone     = '09120000001';
        $ownerA->save();

        $ownerB = new JewelryOwner();
        $ownerB->user_id   = $admin->id;
        $ownerB->full_name = 'مالک مقصد';
        $ownerB->phone     = '09120000002';
        $ownerB->save();

        $certificate = new JewelryCertificate();
        $certificate->owner_id      = $ownerA->id;
        $certificate->product_name  = 'گردنبند آزمایشی';
        $certificate->metal_type    = 'طلا';
        $certificate->purity        = 750;
        $certificate->weight        = 10;
        $certificate->serial_number = 'SMOKE-' . strtoupper(uniqid());
        $certificate->save();

        $transfer = app(OwnershipTransferService::class)->requestTransfer([
            'certificate_id' => $certificate->id,
            'to_owner_id'    => $ownerB->id,
            'transfer_date'  => now()->format('Y-m-d'),
            'transfer_type'  => 'فروش',
            'notes'          => 'توضیح تست',
        ], $admin);

        $this->assertPageRenders($admin, '/admin/jewelry/transfers', 'فهرست انتقالات');
        $this->assertPageRenders($admin, '/admin/jewelry/transfers/create', 'ثبت انتقال');
        $this->assertPageRenders($admin, '/admin/jewelry/transfers/' . $transfer->id, 'جزئیات انتقال');
    }

    /** @test */
    public function owner_jewelry_pages_render()
    {
        $ownerUser = $this->makeUser();

        $owner = new JewelryOwner();
        $owner->user_id   = $ownerUser->id;
        $owner->full_name = 'مالک آزمایشی';
        $owner->phone     = '09120000003';
        $owner->save();

        $certificate = new JewelryCertificate();
        $certificate->owner_id      = $owner->id;
        $certificate->product_name  = 'انگشتر آزمایشی';
        $certificate->metal_type    = 'طلا';
        $certificate->purity        = 750;
        $certificate->weight        = 5;
        $certificate->serial_number = 'SMOKE-OWN-' . strtoupper(uniqid());
        $certificate->save();

        $this->assertPageRenders($ownerUser, '/my-jewelry', 'داشبورد جواهرات من');
        $this->assertPageRenders($ownerUser, '/my-jewelry/transfers', 'انتقالات من');
        $this->assertPageRenders($ownerUser, '/my-jewelry/transfers/create/' . $certificate->id, 'درخواست انتقال');
        $this->assertPageRenders($ownerUser, '/my-jewelry/certificates/' . $certificate->id . '/history', 'سابقه مالکیت');
    }

    // =========================================================
    // صفحاتی که در پروژه اصلی ویو نداشتند
    // =========================================================

    /** @test */
    public function previously_broken_admin_pages_now_render()
    {
        $admin = $this->makeUser(['super_admin']);

        $pages = [
            '/admin/frontend_settings/home_categories' => 'دسته‌بندی‌های صفحه اصلی',
            '/admin/frontend_settings/home_banners'    => 'بنرها',
            '/admin/frontend_settings/sliders'         => 'اسلایدرها',
            '/admin/customer-bulk-upload/index' => 'بارگذاری گروهی مشتریان',
        ];

        foreach ($pages as $uri => $label) {
            $this->assertPageRenders($admin, $uri, $label);
        }
    }

    /** @test */
    public function admin_jewelry_report_pages_render()
    {
        $admin = $this->makeUser(['super_admin']);

        $pages = [
            '/admin/jewelry/reports'            => 'گزارش جامع',
            '/admin/jewelry/reports/certificates' => 'گزارش شناسنامه‌ها',
            '/admin/jewelry/reports/repairs'    => 'گزارش تعمیرات',
            '/admin/jewelry/reports/transfers'  => 'گزارش انتقالات',
        ];

        foreach ($pages as $uri => $label) {
            $this->assertPageRenders($admin, $uri, $label);
        }
    }

    // =========================================================
    // صفحات عمومی بدون ورود
    // =========================================================

    /** @test */
    public function public_pages_render_without_authentication()
    {
        $pages = [
            '/articles'                        => 'فهرست مقالات',
            '/jewelry/certificate-verify'      => 'استعلام شناسنامه',
            '/jewelry/report-stolen'           => 'اعلام سرقت',
        ];

        foreach ($pages as $uri => $label) {
            $response = $this->get($uri);

            $this->assertNotEquals(
                500,
                $response->getStatusCode(),
                'صفحه عمومی «' . $label . '» (' . $uri . ') خطای ۵۰۰ داد.'
            );
        }
    }
}
