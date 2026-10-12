<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * دفتر کل تاریخچه مالکیت (Ownership Ledger).
 *
 * ── تصمیم معماری مهم ──────────────────────────────────────────────
 * در سند درخواست، جدول‌های `ownership_transfer_requests` و
 * `ownership_history` پیشنهاد شده بود. بررسی دیتابیس نشان داد جدول
 * `jewelry_transfers` از قبل وجود دارد و همان معنای «انتقال مالکیت» را
 * دارد. طبق دستور «از ایجاد ساختار تکراری جلوگیری کن»، به‌جای ساخت جدول
 * جدیدِ درخواست، همان `jewelry_transfers` را با ستون‌های گردش‌کار
 * (status / requested_by / approved_by / approval_note / approved_at)
 * توسعه دادیم — این کار در مایگریشن 2026_10_12_100004 انجام شده است.
 *
 * تنها جدولی که واقعاً وجود نداشت و لازم است، «دفتر کل» است:
 *
 *   ownership_history — زنجیره فقط‌افزودنی مالکیت هر قطعه.
 *
 * چرا دفتر کل جدا از `jewelry_transfers`؟
 *   • `jewelry_transfers` یک «درخواست» است که می‌تواند رد یا لغو شود.
 *   • `ownership_history` یک «رکورد حقوقی ثبت‌شده» است که هرگز نباید
 *     بازنویسی یا حذف شود؛ حتی اگر درخواست مرجع آن بعداً تغییر کند.
 *   • هر قطعه یک ستون `sequence_no` دارد که ترتیب مالکیت را تضمین می‌کند
 *     و از ثبت دو مالک «فعلی» هم‌زمان جلوگیری می‌کند.
 */
class CreateOwnershipHistoryTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ownership_history')) {
            Schema::create('ownership_history', function (Blueprint $table) {
                $table->bigIncrements('id');

                // قطعه/شناسنامه‌ای که مالکیت آن تغییر کرده
                $table->unsignedInteger('certificate_id');

                // مالک قبلی — برای «ثبت اولیه» می‌تواند NULL باشد
                $table->unsignedInteger('from_owner_id')->nullable();

                // مالک جدید — در رکورد «ثبت اولیه» همان مالک اولیه است
                $table->unsignedInteger('to_owner_id');

                // درخواست مرجع در جدول jewelry_transfers (در ثبت اولیه NULL)
                $table->unsignedInteger('transfer_id')->nullable();

                // ترتیب مالکیت: ۱ = مالک اول، ۲ = مالک دوم، ...
                // UNIQUE(certificate_id, sequence_no) از دو «مالک فعلی» جلوگیری می‌کند
                $table->unsignedInteger('sequence_no')->default(1);

                // نوع رخداد: initial | sale | gift | inheritance | correction | other
                $table->string('event_type', 30)->default('sale');

                $table->text('notes')->nullable();
                $table->decimal('declared_value', 15, 2)->nullable();

                // پاسخ به «چه کسی و چه زمانی» — الزامی طبق نیازمندی
                $table->unsignedInteger('recorded_by')->nullable();
                $table->timestamp('effective_at')->nullable();   // زمان اعمال مالکیت
                $table->timestamps();

                $table->unique(['certificate_id', 'sequence_no'], 'ownership_history_certificate_sequence_unique');
                $table->index('certificate_id', 'ownership_history_certificate_id_index');
                $table->index('from_owner_id', 'ownership_history_from_owner_id_index');
                $table->index('to_owner_id', 'ownership_history_to_owner_id_index');
                $table->index('effective_at', 'ownership_history_effective_at_index');
                $table->index('event_type', 'ownership_history_event_type_index');

                $table->foreign('certificate_id')
                    ->references('id')->on('jewelry_certificates')
                    ->onDelete('restrict')->onUpdate('cascade');
            });
        }

        // ---------------------------------------------------------
        // ایندکس و کلید خارجی برای جدول انتقال‌ها (نبودِ آن‌ها در تحلیل اولیه)
        // فقط زمانی FK اضافه می‌شود که جدول خالی باشد تا روی داده موجود
        // محدودیت ناسازگار ایجاد نشود.
        // ---------------------------------------------------------
        if (Schema::hasTable('jewelry_transfers')) {
            $this->addForeignIfSafe('jewelry_transfers', 'certificate_id', 'jewelry_certificates', 'jewelry_transfers_certificate_id_foreign');
            $this->addForeignIfSafe('jewelry_transfers', 'from_owner_id', 'jewelry_owners', 'jewelry_transfers_from_owner_id_foreign');
            $this->addForeignIfSafe('jewelry_transfers', 'to_owner_id', 'jewelry_owners', 'jewelry_transfers_to_owner_id_foreign');
        }
    }

    /**
     * افزودن کلید خارجی تنها در صورت نبودن و خالی بودن جدول.
     */
    protected function addForeignIfSafe($table, $column, $refTable, $fkName)
    {
        try {
            $exists = collect(\Illuminate\Support\Facades\DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
                [$table, $fkName]
            ))->count() > 0;

            if ($exists) {
                return;
            }

            $hasRows = \Illuminate\Support\Facades\DB::table($table)->exists();
            if ($hasRows) {
                // داده موجود است — برای پرهیز از شکست روی رکوردهای یتیم، FK اضافه نمی‌کنیم
                return;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($column, $refTable, $fkName) {
                $blueprint->foreign($column, $fkName)
                    ->references('id')->on($refTable)
                    ->onDelete('restrict')->onUpdate('cascade');
            });
        } catch (\Exception $e) {
            // اگر افزودن FK ممکن نبود، ایندکس موجود کافی است
        }
    }

    public function down()
    {
        Schema::dropIfExists('ownership_history');
    }
}
