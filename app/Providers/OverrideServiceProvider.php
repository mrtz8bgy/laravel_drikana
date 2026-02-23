<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OverrideServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Override کلاس CoreComponentRepository برای جلوگیری از چک فعال‌سازی
        $this->app->singleton('core-component-repository', function () {
            return new class {
                public static function instantiateShopRepository() { 
                    return true; 
                }
                public static function initializeCache() { 
                    return true; 
                }
                protected static function finalizeRepository($rn) { 
                    return true; 
                }
                protected static function serializeObjectResponse($zn, $request_data_json) {
                    return 'ok';
                }
                public static function finalizeCache($addon){
                    return true;
                }
            };
        });
    }
}