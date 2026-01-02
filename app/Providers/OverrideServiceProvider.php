
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OverrideServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Override کلاس CoreComponentRepository
        $this->app->bind(
            \MehediIitdu\CoreComponentRepository\CoreComponentRepository::class,
            function () {
                return new class {
                    public static function instantiateShopRepository() { return true; }
                    public static function initializeCache() { return true; }
                    protected static function finalizeRepository($rn) { return true; }
                };
            }
        );
    }
}