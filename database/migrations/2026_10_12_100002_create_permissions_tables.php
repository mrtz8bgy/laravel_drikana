<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ایجاد جداول هسته RBAC:
 *
 *   permissions    — فهرست مجوزهای اتمی (view / create / update / delete / approve / publish ...)
 *   permission_role — جدول واسط نقش ↔ مجوز
 *   role_user       — جدول واسط کاربر ↔ نقش (پشتیبانی از چند نقش برای یک کاربر)
 *
 * پیش‌نیاز: جدول `roles` از قبل وجود دارد (توسعه‌یافته در مایگریشن 100001)
 *           و جدول `users` از قبل وجود دارد.
 */
class CreatePermissionsTables extends Migration
{
    public function up()
    {
        // ---------------------------------------------------------
        // 1) permissions
        // ---------------------------------------------------------
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->increments('id');
                // شناسه اتمی به شکل گروه.عملیات — مثلاً jewelry.transfers.approve
                $table->string('slug', 150)->unique();
                // عنوان فارسی برای نمایش در پنل
                $table->string('name', 191);
                // گروه‌بندی برای مرتب‌سازی در فرم تخصیص
                $table->string('group', 100)->default('general');
                $table->string('description', 500)->nullable();
                $table->timestamps();

                $table->index('group', 'permissions_group_index');
            });
        }

        // ---------------------------------------------------------
        // 2) permission_role (نقش ↔ مجوز)
        // ---------------------------------------------------------
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->unsignedInteger('role_id');
                $table->unsignedInteger('permission_id');

                // زمان انتساب مجوز به نقش — برای رهگیری تغییرات
                $table->timestamps();

                // جلوگیری از ثبت مجوز تکراری برای یک نقش
                $table->primary(['role_id', 'permission_id'], 'permission_role_primary');

                $table->foreign('role_id')
                    ->references('id')->on('roles')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->foreign('permission_id')
                    ->references('id')->on('permissions')
                    ->onDelete('cascade')->onUpdate('cascade');
            });
        }

        // ---------------------------------------------------------
        // 3) role_user (کاربر ↔ نقش) — چند نقش برای یک کاربر
        // ---------------------------------------------------------
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('role_id');

                // ثبت‌کننده انتساب — برای رهگیری در Audit Log
                $table->unsignedInteger('assigned_by')->nullable();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamps();

                $table->primary(['user_id', 'role_id'], 'role_user_primary');
                $table->index('user_id', 'role_user_user_id_index');
                $table->index('role_id', 'role_user_role_id_index');

                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->foreign('role_id')
                    ->references('id')->on('roles')
                    ->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
}
