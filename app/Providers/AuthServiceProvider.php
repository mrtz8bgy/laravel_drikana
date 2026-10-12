<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\Models\JewelryTransfer;
use App\Models\RepairOrder;
use App\Policies\ArticlePolicy;
use App\Policies\JewelryCertificatePolicy;
use App\Policies\JewelryOwnerPolicy;
use App\Policies\OwnershipTransferPolicy;
use App\Policies\RepairOrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        JewelryOwner::class       => JewelryOwnerPolicy::class,
        JewelryCertificate::class => JewelryCertificatePolicy::class,
        JewelryTransfer::class    => OwnershipTransferPolicy::class,
        RepairOrder::class        => RepairOrderPolicy::class,
        Article::class            => ArticlePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * ── منطق RBAC ────────────────────────────────────────────────────
     *
     * Gate::before — «دروازه سوپرادمین»:
     *   اگر کاربر «مدیر کل سیستم» باشد، همه بررسی‌های مجوز تأیید می‌شوند.
     *   این استثنا با دو معیار تشخیص داده می‌شود:
     *     • user_type = 'admin'  (سازگاری با داده موجود پروژه)
     *     • نقش super_admin      (سیستم RBAC جدید)
     *
     * Gate::define (پویا) — برای هر مجوز موجود در جدول `permissions` یک
     *   Gate با همان نام ساخته می‌شود. نتیجه: می‌توان از
     *   `@can('products.update')` و `$this->authorize('products.update')`
     *   در Blade و کنترلرها استفاده کرد، بدون تعریف دستی هر Gate.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // -------------------------------------------------------
        // دروازه سوپرادمین
        // -------------------------------------------------------
        Gate::before(function ($user, $ability) {
            // پشتیبانی از الگوی «policy:method» — فقط بخش نام policy بررسی می‌شود
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }

            return null;
        });

        // -------------------------------------------------------
        // تعریف پویا Gate برای هر مجوز ثبت‌شده در دیتابیس
        // -------------------------------------------------------
        $this->registerDynamicPermissionGates();

        // -------------------------------------------------------
        // Gateهای ویژه‌کار — برای شرط‌هایی که فراتر از یک مجوز اتمی هستند
        // -------------------------------------------------------

        /**
         * تأییدکننده انتقال مالکیت باید با درخواست‌دهنده متفاوت باشد
         * (تفکیک وظایف — Separation of Duties).
         */
        Gate::define('ownership.transfer.approve-distinct', function ($user, $transfer) {
            if ($user->isSuperAdmin()) {
                // حتی مدیر کل هم نباید درخواست خودش را تأیید کند
                return (int) $transfer->requested_by !== (int) $user->id;
            }

            if (!$user->hasPermissionTo('jewelry.transfers.approve')) {
                return false;
            }

            return (int) $transfer->requested_by !== (int) $user->id;
        });
    }

    /**
     * ساخت Gate برای هر مجوز موجود در جدول permissions.
     *
     * اگر جدول هنوز ساخته نشده باشد (مثلاً در حین اجرای مایگریشن اولیه)
     * خطا نادیده گرفته می‌شود تا اپلیکیشن از کار نیفتد.
     *
     * @return void
     */
    protected function registerDynamicPermissionGates()
    {
        try {
            $slugs = \App\Models\Permission::allSlugs();
        } catch (\Exception $e) {
            // جدول permissions هنوز مهاجرت نکرده است
            return;
        }

        foreach ($slugs as $slug) {
            // نام Gate با نقطه تعریف می‌شود تا خوانا باشد: products.update
            Gate::define($slug, function ($user) use ($slug) {
                return method_exists($user, 'hasPermissionTo')
                    ? $user->hasPermissionTo($slug)
                    : false;
            });
        }
    }
}
