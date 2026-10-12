<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جداول Audit Log و تاریخچه حساس.
 *
 * این جداول «فقط افزودنی» (append-only) هستند: در لایه اپلیکیشن هیچ مسیری
 * برای ویرایش یا حذف رکورد آن‌ها وجود ندارد. برای پاک‌سازی قدیمی‌تر از دوره
 * نگهداری، باید از یک Artisan Command با دسترسی سرور استفاده شود.
 *
 *   audit_logs        — ثبت رخدادهای حساس (تغییر نقش، مجوز، مالکیت، ورود، ...)
 *   login_attempts    — شمارش تلاش‌های ناموفق ورود (مقابله با Brute Force)
 */
class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        // ---------------------------------------------------------
        // audit_logs
        // ---------------------------------------------------------
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->bigIncrements('id');

                // کاربر عامل — nullable چون ممکن است عملیات سیستمی یا ورود ناموفق باشد
                $table->unsignedInteger('user_id')->nullable();

                // نام رخداد: role.assigned, permission.updated, ownership.transfer.approved, ...
                $table->string('event', 100);

                // موجودیت هدف (polymorphic)
                $table->string('auditable_type', 191)->nullable();
                $table->unsignedBigInteger('auditable_id')->nullable();

                // توضیح خوانا
                $table->string('description', 500)->nullable();

                // مقادیر قبل/بعد به شکل JSON
                $table->text('old_values')->nullable();
                $table->text('new_values')->nullable();

                // اطلاعات درخواست
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent', 500)->nullable();
                $table->string('url', 500)->nullable();
                $table->string('method', 10)->nullable();

                // سطح اهمیت: info | notice | warning | critical
                $table->string('severity', 20)->default('info');

                $table->timestamp('created_at')->nullable();

                $table->index(['auditable_type', 'auditable_id'], 'audit_logs_auditable_index');
                $table->index('user_id', 'audit_logs_user_id_index');
                $table->index('event', 'audit_logs_event_index');
                $table->index('created_at', 'audit_logs_created_at_index');
                $table->index('severity', 'audit_logs_severity_index');
            });
        }

        // ---------------------------------------------------------
        // login_attempts — مقابله با Brute Force
        // ---------------------------------------------------------
        if (!Schema::hasTable('login_attempts')) {
            Schema::create('login_attempts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('identifier', 191);          // ایمیل یا موبایل یا IP
                $table->string('ip_address', 45)->nullable();
                $table->boolean('successful')->default(false);
                $table->timestamp('created_at')->nullable();

                $table->index(['identifier', 'created_at'], 'login_attempts_identifier_index');
                $table->index(['ip_address', 'created_at'], 'login_attempts_ip_index');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('audit_logs');
    }
}
