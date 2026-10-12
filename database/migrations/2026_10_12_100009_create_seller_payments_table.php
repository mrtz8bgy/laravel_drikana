<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ساخت جدول `seller_payments` — تاریخچهٔ پرداخت‌های مدیریت به فروشندگان.
 *
 * ── چرا این مایگریشن لازم است؟ ──────────────────────────────────────
 * کنترلر `PaymentController` دو مسیر زندهٔ پروژه را مدیریت می‌کند:
 *
 *   ۱) `/seller/payments`       → متد index             (داشبورد فروشنده)
 *   ۲) `/admin/seller/payments` → متد payment_histories (پنل مدیریت)
 *
 * هر دو مسیر پیش‌تر از مدل `App\Payment` (جدول `payments`، مربوط به
 * پرداخت سفارش‌ها) استفاده می‌کردند؛ اما ویوهای این دو صفحه ستون‌هایی
 * مانند `payment_method` و `seller_id` را می‌خوانند که در جدول `payments`
 * وجود ندارند. نتیجه: صفحهٔ تاریخچهٔ پرداخت فروشنده با خطای ۵۰۰
 * «Unknown column 'seller_id'» از کار می‌افتاد.
 *
 * راهکار (سازگار با معماری پروژه، بدون حذف هیچ جدول یا ستونی):
 *   • ساخت جدول اختصاصی `seller_payments`
 *   • مدل `App\Models\SellerPayment`
 *   • اصلاح کنترلر برای استفاده از مدل درست در هر دو مسیر
 *
 * ── معنای ستون‌ها ───────────────────────────────────────────────────
 *   • `seller_id`  شناسهٔ «کاربری» فروشنده است (`users.id`)، نه شناسهٔ
 *     پروفایل فروشنده در جدول `sellers`. این انتخاب باعث می‌شود کوئری
 *     داشبورد فروشنده به رابطهٔ `seller` وابسته نباشد و اگر پروفایل
 *     فروشنده حذف شد، تاریخچهٔ مالی سالم بماند.
 *   • `amount` مبلغ پرداخت، `payment_method` روش پرداخت و `txn_code`
 *     کد پیگیری تراکنش است.
 *
 * ── نکته عملیاتی ────────────────────────────────────────────────────
 * این داده مالی است؛ پیش از اجرای `migrate:rollback` پشتیبان بگیرید.
 */
class CreateSellerPaymentsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('seller_payments')) {
            return;
        }

        // جدول والد باید موجود باشد؛ در غیر این صورت کلید خارجی ساخته نمی‌شود
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::create('seller_payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // شناسهٔ کاربری فروشنده (users.id)
            $table->unsignedInteger('seller_id')->nullable();

            $table->double('amount', 8, 2)->default(0);

            // شرح پرداخت (مثلاً «تسویهٔ سفارش‌های مهر ۱۴۰۵»)
            $table->text('payment_details')->nullable();

            $table->string('payment_method', 255)->nullable();
            $table->string('txn_code', 255)->nullable();

            $table->timestamps();

            $table->index('seller_id', 'seller_payments_seller_id_index');
            $table->index('created_at', 'seller_payments_created_at_index');

            // حذف کاربر نباید تاریخچهٔ مالی را از بین ببرد → set null
            $table->foreign('seller_id', 'seller_payments_seller_id_foreign')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_payments');
    }
}
