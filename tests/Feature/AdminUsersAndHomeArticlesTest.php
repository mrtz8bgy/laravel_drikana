<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * تست‌های «فهرست کاربران/نقش‌ها در پنل اشیاء قیمتی» و «بخش مقالات صفحه اصلی».
 *
 * ── چرا این دو در یک فایل؟ ─────────────────────────────────────────
 * هر دو یک ویژگی مشترک را می‌سنجند: «قابلیتی که تازه به رابط کاربری
 * اضافه شده، هم برای کاربر مجاز کار می‌کند و هم برای کاربر غیرمجاز
 * بسته می‌ماند». جداکردن آن‌ها به دو فایل، هم‌پوشانی منطقی می‌ساخت.
 */
class AdminUsersAndHomeArticlesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ساخت کاربر با نقش‌های دلخواه (هم‌سبک با سایر تست‌های پروژه).
     *
     * @param  array $roleSlugs
     * @param  array $attributes
     * @return \App\User
     */
    protected function makeUser(array $roleSlugs = [], array $attributes = [])
    {
        $user = new User();
        $user->name      = isset($attributes['name']) ? $attributes['name'] : 'کاربر آزمون';
        $user->email     = 'u' . uniqid() . '@example.com';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = isset($attributes['user_type']) ? $attributes['user_type'] : 'customer';
        $user->role      = 'customer';
        $user->save();

        $payload = [];

        foreach ($roleSlugs as $slug) {
            $role = Role::query()->where('slug', $slug)->first();

            if ($role) {
                $payload[$role->getKey()] = [
                    'assigned_by' => null,
                    'assigned_at' => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        if (!empty($payload)) {
            $user->roles()->sync($payload);
        }

        $user->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * ساخت دسته‌بندی مقاله برای تست.
     *
     * @return \App\Models\ArticleCategory
     */
    protected function makeArticleCategory()
    {
        $category = new ArticleCategory();
        $category->name      = 'نگهداری طلا ' . uniqid();
        $category->slug      = 'care-' . uniqid();
        $category->is_active = true;
        $category->sort_order = 1;
        $category->save();

        return $category;
    }

    /**
     * ساخت مقاله منتشرشده.
     *
     * @param  array $overrides
     * @return \App\Models\Article
     */
    protected function makePublishedArticle(array $overrides = [])
    {
        $category = $this->makeArticleCategory();

        $article = new Article();
        $article->title        = isset($overrides['title']) ? $overrides['title'] : 'راهنمای نگهداری طلا ' . uniqid();
        $article->slug         = 'gold-care-' . uniqid();
        $article->excerpt      = 'چند نکته کاربردی برای اینکه درخشش طلا و جواهر شما سال‌ها بماند.';
        $article->content      = '<p>متن کامل مقاله برای آزمون.</p>';
        $article->category_id  = $category->getKey();
        $article->status       = Article::STATUS_PUBLISHED;
        $article->published_at = now()->subDay();
        $article->is_featured  = false;
        $article->views_count  = 12;

        if (isset($overrides['status'])) {
            $article->status = $overrides['status'];
        }

        if (isset($overrides['published_at'])) {
            $article->published_at = $overrides['published_at'];
        }

        $article->save();

        return $article;
    }

    // =================================================================
    // ۱) بخش مقالات در صفحه اصلی سایت
    // =================================================================

    /**
     * صفحه اصلی باید برای کاربر مهمان (کاربر عادی سایت) باز شود.
     */
    public function test_home_page_is_public_and_renders()
    {
        $response = $this->get('/');

        $this->assertNotEquals(
            500,
            $response->getStatusCode(),
            'صفحه اصلی خطای ۵۰۰ داد.'
        );
    }

    /**
     * مقاله منتشرشده باید در صفحه اصلی دیده شود، همراه با لینک
     * «مشاهده همه مقالات» به صفحه عمومی مقالات.
     */
    public function test_home_page_shows_published_articles_with_links()
    {
        $article = $this->makePublishedArticle([
            'title' => 'راهنمای تشخیص طلای اصل ' . uniqid(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $html = $response->getContent();

        // عنوان مقاله باید در صفحه اصلی باشد — بدون نیاز به ورود
        $this->assertContains(
            $article->title,
            $html,
            'عنوان مقاله منتشرشده در صفحه اصلی نمایش داده نشد.'
        );

        // لینک صفحه عمومی مقالات
        $this->assertContains(
            route('articles.index'),
            $html,
            'لینک «مشاهده همه مقالات» در صفحه اصلی وجود ندارد.'
        );

        // لینک مستقیم به خود مقاله
        $this->assertContains(
            route('articles.show', $article->slug),
            $html,
            'لینک مستقیم به مقاله در صفحه اصلی وجود ندارد.'
        );

        // لینک موضوعی دسته‌بندی
        $this->assertContains(
            route('articles.category', $article->category->slug),
            $html,
            'لینک دسته‌بندی مقالات در صفحه اصلی وجود ندارد.'
        );
    }

    /**
     * مقاله پیش‌نویس و بایگانی نباید در صفحه اصلی دیده شود.
     *
     * این تست مهم است چون صفحه اصلی به‌روش «حلقه روی مقالات» نوشته شده؛
     * اگر اسکوپ `published` برداشته شود، محتوای منتشرنشده به‌طور تصادفی
     * روی سایت ظاهر می‌شود.
     */
    public function test_home_page_does_not_show_draft_or_archived_articles()
    {
        $draft = $this->makePublishedArticle([
            'title'  => 'پیش‌نویس محرمانه ' . uniqid(),
            'status' => Article::STATUS_DRAFT,
        ]);

        $archived = $this->makePublishedArticle([
            'title'  => 'مقاله بایگانی‌شده ' . uniqid(),
            'status' => Article::STATUS_ARCHIVED,
        ]);

        // مقاله‌ای که تاریخ انتشارش در آینده است هم نباید دیده شود
        $future = $this->makePublishedArticle([
            'title'        => 'مقاله زمان‌بندی‌شده ' . uniqid(),
            'published_at' => now()->addWeek(),
        ]);

        $html = $this->get('/')->getContent();

        $this->assertFalse(
            strpos($html, $draft->title) !== false,
            'مقاله پیش‌نویس در صفحه اصلی منتشر شده است.'
        );

        $this->assertFalse(
            strpos($html, $archived->title) !== false,
            'مقاله بایگانی‌شده در صفحه اصلی منتشر شده است.'
        );

        $this->assertFalse(
            strpos($html, $future->title) !== false,
            'مقاله‌ای که تاریخ انتشارش نرسیده در صفحه اصلی منتشر شده است.'
        );
    }

    /**
     * وقتی هیچ مقاله منتشرشده‌ای وجود ندارد، بخش مقالات نباید با
     * ظاهری خالی نمایش داده شود.
     */
    public function test_home_page_hides_articles_section_when_nothing_is_published()
    {
        // در محدوده تراکنش، همه مقالات را از حالت انتشار خارج می‌کنیم
        DB::table('articles')->update(['status' => Article::STATUS_DRAFT]);

        $html = $this->get('/')->getContent();

        $this->assertFalse(
            strpos($html, 'd-articles') !== false,
            'بخش مقالات صفحه اصلی با نبود مقاله منتشرشده هم نمایش داده شد.'
        );
    }

    /**
     * لینک مقالات باید در منوی اصلی و فوتر سایت باشد تا کاربر عادی
     * بتواند آن را پیدا کند.
     */
    public function test_articles_link_is_in_navigation_and_footer()
    {
        $html = $this->get('/')->getContent();

        $count = substr_count($html, route('articles.index'));

        // در این تست مقاله‌ای منتشر نشده، پس بخش مقالات صفحه اصلی رندر
        // نمی‌شود و انتظار داریم لینک در «منوی اصلی» و «فوتر» باشد.
        // (لینک سوم، درون بخش مقالات، در تست جداگانه سنجیده می‌شود.)
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'لینک صفحه مقالات باید حداقل در منوی اصلی و فوتر باشد. '
            . 'تعداد یافته‌شده: ' . $count
        );
    }

    // =================================================================
    // ۲) کاربران و نقش‌ها در پنل اشیاء قیمتی
    // =================================================================

    /**
     * مدیر کل سیستم باید در پنل اشیاء قیمتی، لینک ساخت کاربر ادمین و
     * مدیریت نقش‌ها را ببیند.
     */
    public function test_jewelry_panel_shows_user_and_role_links_for_super_admin()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/jewelry/dashboard');

        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertContains(
            route('users.create'),
            $html,
            'لینک «کاربر ادمین جدید» در پنل اشیاء قیمتی دیده نمی‌شود.'
        );

        $this->assertContains(
            route('roles.index'),
            $html,
            'لینک «نقش‌ها و سطح دسترسی» در پنل اشیاء قیمتی دیده نمی‌شود.'
        );
    }

    /**
     * کاربر با نقش محدود (تکنسین تعمیرات) نباید لینک ساخت کاربر ادمین
     * را ببیند — نه به‌خاطر امنیت (که در میدل‌ور تضمین می‌شود)، بلکه
     * چون نمایش گزینه‌ای که کار نمی‌کند تجربه کاربری بدی می‌سازد.
     */
    public function test_jewelry_panel_hides_user_links_from_low_privilege_staff()
    {
        $technician = $this->makeUser(['repair_technician'], ['user_type' => 'staff']);

        $response = $this->actingAs($technician)->get('/admin/jewelry/dashboard');

        // تکنسین به این صفحه دسترسی دارد
        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertFalse(
            strpos($html, route('users.create')) !== false,
            'لینک ساخت کاربر ادمین برای کاربر بدون مجوز نمایش داده شده است.'
        );

        $this->assertFalse(
            strpos($html, route('roles.index')) !== false,
            'لینک مدیریت نقش‌ها برای کاربر بدون مجوز نمایش داده شده است.'
        );
    }

    /**
     * مسیرهای ساخت کاربر باید واقعاً محافظت‌شده باشند — نمایش‌ندادن لینک
     * کافی نیست.
     *
     * این تست همان چیزی است که «پنهان‌سازی رابط کاربری، امنیت نیست» را
     * اثبات می‌کند: درخواست مستقیم به آدرس، از کاربر بدون مجوز رد می‌شود.
     */
    public function test_user_creation_routes_reject_unauthorized_direct_access()
    {
        $technician = $this->makeUser(['repair_technician'], ['user_type' => 'staff']);

        $form = $this->actingAs($technician)->get('/admin/users/create');
        $store = $this->actingAs($technician)->post('/admin/users', [
            'name'          => 'کاربر نفوذی',
            'email'         => 'intruder-' . uniqid() . '@example.com',
            'account_type'  => 'staff',
            'password_mode' => 'auto',
        ]);

        $this->assertNotEquals(
            200,
            $form->getStatusCode(),
            'فرم ساخت کاربر برای کاربر بدون مجوز باز شد.'
        );

        $this->assertNotEquals(
            302,
            $store->getStatusCode(),
            'درخواست ساخت کاربر از سوی کاربر بدون مجوز پذیرفته شد.'
        );
    }

    /**
     * مدیر کل سیستم باید بتواند از طریق فرم، یک کاربر ادمین بسازد.
     *
     * مسیر «تولید خودکار رمز» آزموده می‌شود: کاربر باید ساخته شود،
     * رمز هش‌شده ذخیره شود و رمز خام تنها یک بار در فلش‌سشن بیاید.
     */
    public function test_super_admin_can_create_staff_user_with_generated_password()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $email = 'newstaff-' . uniqid() . '@example.com';

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'          => 'مدیر محتوای جدید',
            'email'         => $email,
            'account_type'  => 'staff',
            'password_mode' => 'auto',
            'roles'         => ['content_manager'],
        ]);

        $response->assertStatus(302);

        $created = User::query()->where('email', $email)->first();

        $this->assertNotNull($created, 'کاربر ادمین جدید ساخته نشد.');
        $this->assertNotEquals('admin', $created->user_type, 'نوع حساب باید staff باشد، نه admin.');
        $this->assertTrue(
            $created->roles()->where('slug', 'content_manager')->exists(),
            'نقش انتخابی به کاربر داده نشد.'
        );
        $this->assertNotEmpty(
            $response->getSession()->get('generated_password'),
            'رمز تولیدشده باید یک بار به مدیر نمایش داده شود.'
        );
    }

    /**
     * کاربری که `users.create` دارد اما مدیر کل نیست، نباید بتواند
     * حساب «مدیر کل سیستم» بسازد.
     *
     * ── چرا حیاتی است؟ ──────────────────────────────────────────────
     * `User::isSuperAdmin()` مقدار `user_type === 'admin'` را «مدیر کل»
     * می‌داند. پس اگر این محافظ نبود، هر کاربری با مجوز ساخت کاربر
     * می‌توانست یک مدیر کل بسازد و از آن برای افزایش سطح دسترسی خودش
     * استفاده کند.
     */
    public function test_non_super_admin_cannot_create_another_super_admin()
    {
        // کاربر با مجوز ساخت کاربر، اما بدون نقش مدیر کل
        $storeManager = $this->makeUser(['store_manager'], ['user_type' => 'staff']);

        // مجوز users.create را دستی می‌دهیم تا صرفاً «مجوز» سنجیده شود،
        // نه کمبود آن.
        $permission = DB::table('permissions')->where('slug', 'users.create')->first();

        if ($permission) {
            DB::table('permission_role')->insertOrIgnore([
                'role_id'       => Role::query()->where('slug', 'store_manager')->value('id'),
                'permission_id' => $permission->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        User::flushAllPermissionCaches();
        $storeManager = $storeManager->fresh();

        $email = 'eviladmin-' . uniqid() . '@example.com';

        $response = $this->actingAs($storeManager)->post('/admin/users', [
            'name'          => 'مدیر کل جعلی',
            'email'         => $email,
            'account_type'  => 'admin',
            'password_mode' => 'auto',
        ]);

        $this->assertNotEquals(
            302,
            $response->getStatusCode(),
            'کاربر غیرمدیرکل توانست حساب «مدیر کل سیستم» بسازد!'
        );

        $this->assertNull(
            User::query()->where('email', $email)->first(),
            'حساب مدیر کل جعلی ساخته شد.'
        );
    }

    /**
     * رمز دستی ضعیف در فرم ساخت کاربر باید رد شود.
     */
    public function test_weak_manual_password_is_rejected()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $email = 'weakpw-' . uniqid() . '@example.com';

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'کاربر رمز ضعیف',
            'email'                 => $email,
            'account_type'          => 'staff',
            'password_mode'         => 'manual',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ]);

        // نکته: هم «خطای اعتبارسنجی» و هم «بازگشت با پیام خطا» کد ۳۰۲
        // می‌دهند؛ پس مبنای سنجش، «ساخته‌نشدن کاربر» و «وجود پیام خطا»
        // است، نه کد وضعیت.
        $this->assertNull(
            User::query()->where('email', $email)->first(),
            'کاربر با رمز ضعیف ساخته شد.'
        );

        $session = $response->getSession();
        $this->assertTrue(
            $session->has('error') || $session->has('errors'),
            'رد رمز ضعیف باید پیام خطا به همراه داشته باشد.'
        );
    }

    /**
     * نبود ایمیل و موبایل باید رد شود — حساب بدون راه ورود بی‌معناست.
     */
    public function test_user_without_email_and_phone_is_rejected()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $email = 'no-login-way-' . uniqid() . '@example.com';

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'          => 'بدون راه ورود',
            'account_type'  => 'staff',
            'password_mode' => 'auto',
        ]);

        // کاربر ساخته نشده باشد — ملاک اصلی همین است
        $this->assertEquals(
            0,
            User::query()
                ->where('name', 'بدون راه ورود')
                ->whereNull('email')
                ->where(function ($q) {
                    $q->whereNull('phone')->orWhere('phone', '');
                })
                ->count(),
            'کاربری بدون ایمیل و موبایل ساخته شد.'
        );

        $this->assertTrue(
            $response->getSession()->has('errors') || $response->getSession()->has('error'),
            'نبود ایمیل و موبایل باید پیام خطا بدهد.'
        );
    }

    /**
     * فرم ساخت کاربر باید برای مدیر کل رندر شود و دکمه‌های لازم را داشته باشد.
     */
    public function test_super_admin_can_open_the_create_user_form()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users/create');

        $response->assertStatus(200);

        $html = $response->getContent();

        // مقصد فرم باید مسیر ساخت کاربر باشد
        $this->assertContains(
            route('users.store'),
            $html,
            'فرم ساخت کاربر به مسیر درست ارسال نمی‌شود.'
        );

        // گزینه ساخت مدیر کل برای مدیر کل نمایش داده می‌شود
        $this->assertContains(
            'value="admin"',
            $html,
            'گزینه «مدیر کل سیستم» برای مدیر کل نمایش داده نشده است.'
        );

        // فهرست نقش‌ها باید آمده باشد
        $this->assertContains(
            'content_manager',
            $html,
            'فهرست نقش‌ها در فرم ساخت کاربر دیده نمی‌شود.'
        );
    }

    /**
     * فرم ساخت کاربر باید توکن CSRF داشته باشد.
     *
     * بدون آن، ارسال فرم رد می‌شود و مدیر فکر می‌کند «دکمه کار نمی‌کند».
     */
    public function test_create_user_form_has_csrf_token()
    {
        $admin = $this->makeUser(['super_admin'], ['user_type' => 'admin']);

        $html = $this->actingAs($admin)->get('/admin/users/create')->getContent();

        $this->assertContains('name="_token"', $html);
    }
}
