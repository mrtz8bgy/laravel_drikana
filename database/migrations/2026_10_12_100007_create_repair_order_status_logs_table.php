<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ساخت جدول `repair_order_status_logs` — تاریخچه تغییر وضعیت سفارش‌های تعمیر.
 *
 * ── چرا این مایگریشن لازم است؟ ──────────────────────────────────────
 * کنترلر `Jewelry\Admin\RepairOrderController` دو جا به این جدول کار دارد:
 *
 *   ۱) در متد `updateStatus` هر تغییر وضعیت را در آن ثبت می‌کند
 *   ۲) در متد `statusHistory` تاریخچه را از آن می‌خواند
 *
 * اما جدول در هیچ مایگریشن یا فایل SQL پروژه ساخته نشده بود. به همین
 * دلیل هر دو مسیر در `try/catch` بی‌صدا شکست می‌خوردند و نتیجه این بود
 * که «تاریخچه وضعیت تعمیرات» عملاً هیچ‌وقت ثبت نمی‌شد؛ صفحه تاریخچه هم
 * همیشه خالی نمایش داده می‌شد بدون اینکه خطایی دیده شود.
 *
 * ── نکته درباره حذف رکوردها ────────────────────────────────────────
 * هنگام حذف یک سفارش تعمیر، رکوردهای این جدول هم پاک می‌شوند. این کار
 * عامدانه است: تاریخچه یک سفارش حذف‌شده باقی نمی‌ماند. اما توجه کنید که
 * حذف سفارش و تاریخچه‌اش هر دو در یک تراکنش دیتابیس انجام می‌شود و خودِ
 * رخداد «حذف سفارش» در `audit_logs` جداگانه ثبت می‌گردد.
 *
 * کلید خارجی با `cascadeOnDelete` تعریف شده تا اگر روزی رکوردی از مسیر
 * دیگری حذف شد، رکوردهای یتیم در این جدول باقی نمانند.
 */
class CreateRepairOrderStatusLogsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('repair_order_status_logs')) {
            return;
        }

        // جدول والد باید موجود باشد؛ در غیر این صورت کلید خارجی ساخته نمی‌شود
        if (!Schema::hasTable('repair_orders')) {
            return;
        }

        Schema::create('repair_order_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // نوع ستون باید دقیقاً با `repair_orders.id` یکسان باشد
            // (int unsigned، نه bigint)؛ در غیر این صورت ساخت کلید خارجی
            // با خطای errno 150 «Foreign key constraint is incorrectly
            // formed» شکست می‌خورد. این نکته روی MariaDB/MySQL هر دو صادق است.
            $table->unsignedInteger('repair_order_id');

            // وضعیت پیشین ممکن است برای نخستین رکورد خالی باشد
            $table->string('old_status', 100)->nullable();
            $table->string('new_status', 100);

            // کاربری که تغییر را انجام داده؛ با حذف کاربر، رکورد تاریخچه
            // حفظ می‌شود و این ستون خالی می‌ماند (حفظ ردپای تاریخی)
            $table->unsignedInteger('changed_by')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('repair_order_id', 'repair_order_status_logs_order_index');
            $table->index('changed_by', 'repair_order_status_logs_user_index');
            $table->index('created_at', 'repair_order_status_logs_created_index');

            $table->foreign('repair_order_id', 'repair_order_status_logs_order_foreign')
                ->references('id')->on('repair_orders')
                ->onDelete('cascade');

            $table->foreign('changed_by', 'repair_order_status_logs_user_foreign')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_order_status_logs');
    }
}
