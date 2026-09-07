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

    $this->mapWebRoutes();

    //$this->mapInstallRoutes();

    //$this->mapUpdateRoutes();
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

  protected function mapAffiliateRoutes()
  {
    $path = base_path('routes/affiliate.php');
    if ($this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\AffiliateController'])) {
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
    if ($this->canRegisterRouteGroup($path, [
      'App\\Http\\Controllers\\ManualPaymentMethodController',
      'App\\Http\\Controllers\\SellerPackagePaymentController',
      'App\\Http\\Controllers\\CustomerPackagePaymentController',
      'App\\Http\\Controllers\\WalletController',
    ])) {
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
    if ($this->canRegisterRouteGroup($path, ['App\\Http\\Controllers\\ClubPointController'])) {
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
   * Define the "updating" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapUpdateRoutes()
  {
    Route::middleware('web')
       ->namespace($this->namespace)
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
    Route::middleware('web')
       ->namespace($this->namespace)
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
