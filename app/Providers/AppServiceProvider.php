<?php

namespace App\Providers;

use App\Models\JewelryCertificate;
use App\Observers\JewelryCertificateObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // طول پیش‌فرض رشته‌ها برای سازگاری با MySQL/MariaDB نسخه‌های قدیمی
        // (محدودیت ایندکس یونیکد ۷۶۷ بایت)
        Schema::defaultStringLength(191);

        // -------------------------------------------------------------
        // ثبت ناظرها
        // -------------------------------------------------------------
        // هر شناسنامه جدید باید یک رکورد «مالکیت اولیه» در دفتر کل
        // مالکیت دریافت کند تا زنجیره مالکان از ابتدا کامل باشد.
        JewelryCertificate::observe(JewelryCertificateObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
