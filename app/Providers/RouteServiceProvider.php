<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * This namespace is applied to your controller routes.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'App\Http\Controllers';

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot()
  {
    //

    parent::boot();
  }

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->loadAddonControllerClasses();

    $this->mapApiRoutes();

    $this->mapAdminRoutes();

    $this->mapAffiliateRoutes();

    $this->mapRefundRoutes();

    $this->mapClubPointsRoutes();

    $this->mapOtpRoutes();

    $this->mapOfflinePaymentRoutes();

    $this->mapPaytmRoutes();

    $this->mapPosRoutes();

    $this->mapSellerPackageRoutes();

    $this->mapWebRoutes();

    // Installation and update routes are only available when their controllers
    // are present. Keeping them conditional avoids breaking normal requests
    // while still allowing the installer/update screens to generate URLs.
    if (class_exists('App\\Http\\Controllers\\InstallController')) {
      $this->mapInstallRoutes();
    }

    if (class_exists('App\\Http\\Controllers\\UpdateController')) {
      $this->mapUpdateRoutes();
    }
  }

  /**
   * Define the "affiliate" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function loadAddonControllerClasses()
  {
    $addonControllers = [
      'addons/affiliate_system/controllers/AffiliateController.php',
      'addons/club_point/controllers/ClubPointController.php',
      'addons/offline_payment/controllers/ManualPaymentMethodController.php',
      'addons/seller_subscription/controllers/SellerPackageController.php',
      'addons/seller_subscription/controllers/SellerPackagePaymentController.php',
      'addons/auction/controllers/AuctionProductController.php',
      'addons/auction/controllers/AuctionProductBidController.php',
    ];

    foreach ($addonControllers as $controller) {
      $path = base_path($controller);
      if (file_exists($path)) {
        require_once $path;
      }
    }
  }

  /**
   * آیا افزونهٔ مشخصی در سامانه نصب و فعال شده است؟
   *
   * چرا این بررسی لازم است: کنترلرهای افزونه‌ها از پوشهٔ addons/ بارگذاری
   * می‌شوند، اما **مدل‌ها و ویوهای** آنان تنها هنگام نصب افزونه (از بخش
   * «مدیریت افزونه‌ها») داخل app/ و resources/views/ قرار می‌گیرند. اگر
   * مسیرهای یک افزونهٔ نصب‌نشده ثبت شوند، صفحه با خطای ۵۰۰
   * (Class/View not found) از کار می‌افتد؛ در حالی که رفتار درست، ۴۰۴
   * است. بنابراین ثبت مسیرهای افزونه به نتیجهٔ همین بررسی گره خورده است.
   *
   * @param  string $identifier شناسهٔ یکتای افزونه (مثل club_point)
   * @return bool
   */
  protected function addonInstalled($identifier)
  {
    try {
      if (!\Illuminate\Support\Facades\Schema::hasTable('addons')) {
        return false;
      }

      return \Illuminate\Support\Facades\DB::table('addons')
        ->where('unique_identifier', $identifier)
        ->where('activated', 1)
        ->exists();
    } catch (\Exception $e) {
      // در زمان نصب سامانه (پیش از ساخته‌شدن جدول‌ها) یا هر خطای دیتابیس،
      // امن‌ترین پاسخ «نصب‌نشده» است تا هیچ صفحه‌ای ۵۰۰ ندهد.
      return false;
    }
  }

  protected function mapAffiliateRoutes()
  {
    $path = base_path('routes/affiliate.php');
    if ($this->addonInstalled('affiliate_system')
        && $this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\AffiliateController'])) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "offline payment" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapOfflinePaymentRoutes()
  {
    $path = base_path('routes/offline_payment.php');
    // The addon may contain optional seller/customer package handlers. Register
    // its available routes when the core payment controllers are installed.
    if ($this->addonInstalled('offline_payment')
        && file_exists($path)
        && class_exists('App\\Http\\Controllers\\ManualPaymentMethodController')
        && class_exists('App\\Http\\Controllers\\WalletController')) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }


  /**
   * Define the "offline payment" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapPaytmRoutes()
  {
    $path = base_path('routes/paytm.php');
    if (file_exists($path) && file_exists(app_path('Http/Controllers/PaytmController.php'))) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "refund" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapRefundRoutes()
  {
    $path = base_path('routes/refund_request.php');
    if ($this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\RefundRequestController'])) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "club points" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapClubPointsRoutes()
  {
    $path = base_path('routes/club_points.php');
    if ($this->addonInstalled('club_point')
        && $this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\ClubPointController'])) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "OTP System" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapOtpRoutes()
  {
    $path = base_path('routes/otp.php');
    if (file_exists($path) && file_exists(app_path('Http/Controllers/OTPController.php')) && file_exists(app_path('Http/Controllers/OTPVerificationController.php'))) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "POS System" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapPosRoutes()
  {
    $path = base_path('routes/pos.php');
    if (file_exists($path) && file_exists(app_path('Http/Controllers/PosController.php'))) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the seller subscription routes when the addon is installed.
   *
   * @return void
   */
  protected function mapSellerPackageRoutes()
  {
    $path = base_path('routes/seller_package.php');
    if ($this->addonInstalled('seller_subscription')
        && $this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\SellerPackageController'])) {
      Route::middleware('web')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  /**
   * Define the "updating" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapUpdateRoutes()
  {
    // میدل‌ور not_installed: روی سامانه‌ای که قبلاً نصب شده، مسیر
    // /update کاملاً بسته می‌شود (پاسخ ۴۰۴). بدون این قید، هر فرد
    // بیرونی می‌توانست صفحه به‌روزرسانی را باز کند.
    Route::middleware(['web', 'not_installed'])
       ->namespace($this->namespace)
       ->prefix('update')
       ->group(base_path('routes/update.php'));
  }

  /**
   * Define the "installation" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapInstallRoutes()
  {
    // میدل‌ور not_installed: روی سامانه‌ای که قبلاً نصب شده، مسیر
    // /install کاملاً بسته می‌شود (پاسخ ۴۰۴).
    //
    // ── چرا حیاتی است؟ ─────────────────────────────────────────────
    // نصب‌کننده فایل .env را بازنویسی می‌کند، SQL ایمپورت می‌کند و
    // تنظیمات سیستم را تغییر می‌دهد. اگر این مسیر روی سامانه در حال
    // بهره‌برداری باز بماند، یک فرد بیرونی می‌تواند پیکربندی سایت را
    // بازنویسی کند یا آن را از کار بیندازد.
    Route::middleware(['web', 'not_installed'])
       ->namespace($this->namespace)
       ->prefix('install')
       ->group(base_path('routes/install.php'));
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapWebRoutes()
  {
    $this->registerRouteFileIfAvailable(base_path('routes/web.php'));
  }

  /**
   * Define the "admin" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapAdminRoutes()
  {
    $this->registerRouteFileIfAvailable(base_path('routes/admin.php'));
  }

  /**
   * Define the "api" routes for the application.
   *
   * These routes are typically stateless.
   *
   * @return void
   */
  protected function mapApiRoutes()
  {
    $path = base_path('routes/api.php');
    if ($this->canRegisterRouteGroup($path, [])) {
      Route::prefix('api')
         ->middleware('api')
         ->namespace($this->namespace)
         ->group($path);
    }
  }

  protected function registerRouteFileIfAvailable($path, $middleware = 'web')
  {
    if (!$this->canRegisterRouteGroup($path, [])) {
      return;
    }

    $route = Route::middleware($middleware)->namespace($this->namespace);

    if ($middleware === 'api') {
      $route = Route::prefix('api')->middleware('api')->namespace($this->namespace);
    }

    $route->group($path);
  }

  protected function canRegisterRouteGroup($path, array $controllers)
  {
    if (!file_exists($path)) {
      return false;
    }

    $required = $controllers;
    $content = file_get_contents($path);

    if ($content !== false) {
      preg_match_all('/[\'\"]([A-Za-z0-9_]+Controller)[\'\"]/', $content, $matches);

      foreach ($matches[1] as $controller) {
        $required[] = 'App\\Http\\Controllers\\' . $controller;
      }
    }

    foreach (array_unique($required) as $controller) {
      if ($controller === '') {
        continue;
      }
      if (class_exists($controller)) {
        continue;
      }

      $baseClass = str_replace('App\\Http\\Controllers\\', '', $controller);
      if (file_exists(app_path('Http/Controllers/' . $baseClass . '.php'))) {
        continue;
      }

      if (class_exists('App\\' . $baseClass) || file_exists(app_path($baseClass . '.php'))) {
        continue;
      }

      return false;
    }

    return true;
  }
}
