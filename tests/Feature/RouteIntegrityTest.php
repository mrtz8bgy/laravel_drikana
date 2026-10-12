<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * آزمون یکپارچگی روت‌ها.
 *
 * ── چرا لازم است؟ ─────────────────────────────────────────────────
 * در این پروژه سه نوع نقص در روت‌ها شناسایی شد که هیچ‌کدام با اجرای
 * معمولی برنامه آشکار نمی‌شدند:
 *
 *   ۱) «نام روت تکراری»: دو روت با یک نام. نتیجه‌اش این است که تابع
 *      route('x') به یکی از آن دو می‌رود و رفتار برنامه به ترتیب
 *      ثبت روت‌ها وابسته می‌شود — یعنی باگ‌های تصادفی.
 *   ۲) «کنترلر یا متد ناموجود»: روت به متدی اشاره می‌کند که وجود ندارد
 *      و کاربر خطای 500 می‌گیرد.
 *   ۳) «حذف از طریق GET»: روت‌های حذف با متد GET. این الگو امکان حمله
 *      CSRF را باز می‌کند، چون توکن CSRF در درخواست GET بررسی نمی‌شود.
 *
 * این آزمون هر سه مورد را به‌صورت خودکار بررسی می‌کند.
 */
class RouteIntegrityTest extends TestCase
{
    /**
     * فهرست نام روت‌هایی که عمداً با متدهای GET و POST هر دو ثبت شده‌اند.
     * این‌ها استثنا هستند و بررسی «بدون نام تکراری» برایشان اعمال نمی‌شود.
     *
     * @var array<int,string>
     */
    protected static $allowedSameNameDifferentVerb = [
        // روت «نمایش محصولات ویژه» (GET، عمومی) در برابر روت «نشان‌کردن
        // محصول به‌عنوان ویژه» (POST، ادمین) — نام‌ها جدا شده‌اند، اما
        // این فهرست برای مستندسازی باقی می‌ماند.
    ];

    /** @test */
    public function route_names_are_unique()
    {
        $names = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if (!$name) {
                continue;
            }

            $names[$name][] = strtoupper(implode('|', $route->methods())) . ' ' . $route->uri();
        }

        $duplicates = [];

        foreach ($names as $name => $occurrences) {
            if (count($occurrences) > 1 && !in_array($name, static::$allowedSameNameDifferentVerb, true)) {
                $duplicates[] = $name . "\n      - " . implode("\n      - ", $occurrences);
            }
        }

        $this->assertEmpty(
            $duplicates,
            "نام روت تکراری یافت شد (باید یکتا باشد تا route() رفتار قطعی داشته باشد):\n\n      "
            . implode("\n\n      ", $duplicates)
        );
    }

    /** @test */
    public function every_route_points_to_an_existing_controller_method()
    {
        $broken = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();

            if (!isset($action['controller'])) {
                continue; // روت‌های Closure
            }

            $controller = $action['controller'];

            if (strpos($controller, '@') === false) {
                continue;
            }

            list($class, $method) = explode('@', $controller, 2);

            if (!class_exists($class)) {
                $broken[] = $route->uri() . ' → کلاس یافت نشد: ' . $class;
                continue;
            }

            if (!method_exists($class, $method)) {
                $broken[] = $route->uri() . ' → متد یافت نشد: ' . $class . '::' . $method;
            }
        }

        $this->assertEmpty(
            $broken,
            "روت‌هایی که به کنترلر/متد ناموجود اشاره می‌کنند:\n  - " . implode("\n  - ", $broken)
        );
    }

    /** @test */
    public function no_mutating_action_is_reachable_through_a_get_route()
    {
        // مسیرهایی که «حذف» یا «تأیید» انجام می‌دهند نباید با GET قابل فراخوانی باشند
        $suspiciousPatterns = [
            '/destroy/',
            '/delete/',
        ];

        $offenders = [];

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }

            $action = $route->getAction();
            $controller = isset($action['controller']) ? $action['controller'] : '';

            foreach ($suspiciousPatterns as $pattern) {
                if (strpos($route->uri(), $pattern) !== false) {
                    $offenders[] = $route->uri() . '  (' . $controller . ')';
                    break;
                }
            }
        }

        $this->assertEmpty(
            $offenders,
            "این روت‌ها عملیات تغییردهنده را با متد GET انجام می‌دهند و در برابر CSRF آسیب‌پذیرند:\n  - "
            . implode("\n  - ", $offenders)
        );
    }

    /** @test */
    public function api_routes_are_namespaced_with_the_api_prefix()
    {
        // ── چرا این بررسی مهم است؟ ─────────────────────────────────────
        // روت‌های API و روت‌های وب هر دو در یک «فضای نام» قرار دارند. اگر
        // یک روت API نامی مثل `shops.index` بگیرد و روت وب هم همان نام را
        // داشته باشد، تابع route('shops.index') به یکی از آن دو می‌رود و
        // نتیجه به ترتیب ثبت روت‌ها وابسته می‌شود؛ یعنی لینک‌های پنل ممکن
        // است به آدرس API اشاره کنند (باگ واقعی که در این پروژه وجود داشت).
        //
        // راه‌حل: هر روت API نامش با پیشوند `api.` شروع شود.
        $offenders = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();

            // روت‌های واقع در مسیر api/ را بررسی کن
            if (strpos($uri, 'api/') !== 0) {
                continue;
            }

            $name = $route->getName();

            // روت‌های بی‌نام مشکلی ندارند
            if (!$name) {
                continue;
            }

            if (strpos($name, 'api.') !== 0) {
                $offenders[] = $uri . '  →  نام: ' . $name;
            }
        }

        $this->assertEmpty(
            $offenders,
            "روت‌های API باید نامشان با پیشوند «api.» شروع شود تا با نام روت‌های وب تداخل نکنند:\n  - "
            . implode("\n  - ", $offenders)
        );
    }

    /** @test */
    public function critical_named_routes_resolve()
    {
        // روت‌های کلیدی که صفحات به آن‌ها لینک می‌دهند
        $mustExist = [
            'user.login',
            'roles.index',
            'users.roles.edit',
            'users.roles.update',
            'permissions.index',
            'audit_logs.index',
            'brands.index',
            'categories.index',
            'products.category',
            'products.featured',
            'contact',
            'articles.index',
            'admin.products.featured',
            'admin.orders.show',
            'admin.reviews.index',
            'verification.phone.verify',
            'verification.phone.code_resend',
            'languages.edit',
            'languages.update',
            'home_banners.create',
            'home_banners.create.position',
            'categories.destroy',
            'brands.destroy',
        ];

        foreach ($mustExist as $name) {
            $this->assertNotNull(
                Route::getRoutes()->getByName($name),
                'روت «' . $name . '» وجود ندارد در حالی که کد به آن ارجاع می‌دهد.'
            );
        }
    }

    /** @test */
    public function public_category_links_use_existing_category_slugs()
    {
        $categorySlugs = [
            '--2EJpD',
            '----FoS0v',
            '---iLNhI',
            '---uQqCB',
            '-----MB8NO',
            '-----H1Zlr',
        ];

        foreach ($categorySlugs as $slug) {
            $this->assertStringContainsString(
                'category=' . $slug,
                route('products.category', ['category_slug' => $slug])
            );
        }
    }
}
