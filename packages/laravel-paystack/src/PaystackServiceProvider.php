<?php

namespace Mehedi\Paystack;

use Illuminate\Support\ServiceProvider;

/**
 * ثبت سرویس Paystack در ظرف سرویس‌های لاراول.
 *
 * Config با کلید `paystack` ادغام می‌شود و برای انتشار در
 * `config/paystack.php` برنامه در دسترس است.
 */
class PaystackServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/paystack.php' => config_path('paystack.php'),
        ], 'paystack-config');
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paystack.php', 'paystack');

        $this->app->singleton('paystack', function ($app) {
            return new Paystack();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['paystack'];
    }
}
