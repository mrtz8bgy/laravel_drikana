<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * افزودن ستون `verification_notes` به جدول `jewelry_certificates`.
 *
 * ── چرا لازم است؟ ───────────────────────────────────────────────────
 * کنترلر مدیریتی `Jewelry\Admin\JewelryCertificateController` در متدهای
 * `verify` و `quickVerify` این ستون را می‌نوشت، اما در جدول وجود نداشت؛
 * نتیجه آن خطای «Unknown column» در زمان تأیید شناسنامه بود.
 *
 * ── چرا فقط یک ستون؟ ───────────────────────────────────────────────
 * همان متدها ستون‌های `verified_at` و `verified_by` را هم می‌نوشتند.
 * اما جدول از قبل ستون‌های `approved_at` و `approved_by` را دارد که
 * دقیقاً همان معنی «زمان و کاربر تأییدکننده» را می‌رسانند. ساخت ستون
 * تکراری اصل «از ایجاد ساختار تکراری جلوگیری کن» را نقض می‌کرد؛
 * بنابراین آن دو ستون عامدانه ساخته نشدند و کنترلر برای استفاده از
 * ستون‌های موجود بازنویسی شد. تنها ستون واقعاً تازه `verification_notes`
 * است که اطلاعات جدیدی (یادداشت تأیید) نگه می‌دارد.
 */
class AddVerificationNotesToJewelryCertificates extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('jewelry_certificates')) {
            return;
        }

        if (!Schema::hasColumn('jewelry_certificates', 'verification_notes')) {
            Schema::table('jewelry_certificates', function (Blueprint $table) {
                $table->string('verification_notes', 500)->nullable()->after('rejection_reason');
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('jewelry_certificates')
            && Schema::hasColumn('jewelry_certificates', 'verification_notes')) {
            Schema::table('jewelry_certificates', function (Blueprint $table) {
                $table->dropColumn('verification_notes');
            });
        }
    }
}
