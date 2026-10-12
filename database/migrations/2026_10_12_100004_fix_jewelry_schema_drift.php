<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * اصلاح «انحراف Schema» (Schema Drift) در جداول ماژول طلا و جواهر.
 *
 * مشکلات شناسایی‌شده در تحلیل اولیه:
 *
 *  1) مدل App\Models\JewelryCertificate شامل فیلدهای `is_active` و `created_by`
 *     در $fillable بود، در حالی که هیچ‌کدام در جدول `jewelry_certificates`
 *     وجود ندارند → هر فراخوانی create()/update() با خطای
 *     "Unknown column" شکست می‌خورد.
 *
 *  2) جدول `jewelry_certificates` در دیتابیس دارای `owner_id` با
 *     FOREIGN KEY به `jewelry_owners(id)` و ON DELETE CASCADE است.
 *     این یعنی حذف یک مالک، تمام شناسنامه‌های او را نیز حذف می‌کند که
 *     برای «سوابق ارزشمند» نامطلوب است.
 *
 *  3) جدول `jewelry_transfers` هیچ ستون `price` و `contract_file` ندارد،
 *     اما کنترلر انتقال مالکیت آن‌ها را می‌فرستد → داده بی‌سروصدا دور ریخته
 *     می‌شود.
 *
 *  4) جدول `jewelry_transfers` هیچ FOREIGN KEY و هیچ Index ندارد.
 *
 * این مایگریشن صرفاً **افزودنی** است: ستون اضافه می‌کند و ایندکس می‌سازد؛
 * هیچ ستون یا رکوردی حذف نمی‌شود. بنابراین قابل بازگشت است.
 */
class FixJewelrySchemaDrift extends Migration
{
    public function up()
    {
        // =========================================================
        // 1) jewelry_certificates — افزودن ستون‌های جاافتاده
        // =========================================================
        if (Schema::hasTable('jewelry_certificates')) {
            Schema::table('jewelry_certificates', function (Blueprint $table) {
                if (!Schema::hasColumn('jewelry_certificates', 'is_active')) {
                    // ستون `is_verified` از قبل وجود دارد؛ is_active وضعیت
                    // فعال/غیرفعال بودن رکورد است (متفاوت از تأیید اصالت)
                    $table->boolean('is_active')->default(true)->after('is_verified');
                }
                if (!Schema::hasColumn('jewelry_certificates', 'created_by')) {
                    // ثبت‌کننده شناسنامه — برای رهگیری و Audit
                    $table->unsignedInteger('created_by')->nullable()->after('is_active');
                }
                if (!Schema::hasColumn('jewelry_certificates', 'approved_by')) {
                    $table->unsignedInteger('approved_by')->nullable()->after('created_by');
                }
                if (!Schema::hasColumn('jewelry_certificates', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('jewelry_certificates', 'rejection_reason')) {
                    $table->string('rejection_reason', 500)->nullable()->after('rejected_at');
                }
            });

            // ایندکس روی created_by و is_active (فیلدهای پرجست‌وجو)
            $this->addIndexIfMissing('jewelry_certificates', 'created_by', 'jewelry_certificates_created_by_index');
            $this->addIndexIfMissing('jewelry_certificates', 'is_active', 'jewelry_certificates_is_active_index');
            $this->addIndexIfMissing('jewelry_certificates', 'is_verified', 'jewelry_certificates_is_verified_index');
            $this->addIndexIfMissing('jewelry_certificates', 'issued_at', 'jewelry_certificates_issued_at_index');
        }

        // =========================================================
        // 2) jewelry_transfers — افزودن ستون‌های جاافتاده + گردش‌کار
        // =========================================================
        if (Schema::hasTable('jewelry_transfers')) {
            Schema::table('jewelry_transfers', function (Blueprint $table) {
                if (!Schema::hasColumn('jewelry_transfers', 'price')) {
                    $table->decimal('price', 15, 2)->nullable()->after('transfer_type');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'contract_file')) {
                    $table->string('contract_file', 191)->nullable()->after('price');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'requested_by')) {
                    $table->unsignedInteger('requested_by')->nullable()->after('contract_file');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'approved_by')) {
                    $table->unsignedInteger('approved_by')->nullable()->after('requested_by');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'status')) {
                    // pending | approved | rejected | cancelled
                    $table->string('status', 20)->default('approved')->after('approved_by');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'approval_note')) {
                    $table->string('approval_note', 500)->nullable()->after('status');
                }
                if (!Schema::hasColumn('jewelry_transfers', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approval_note');
                }
            });

            // ایندکس روی کلیدهای خارجی منطقی (جدول فعلاً هیچ FK ندارد)
            $this->addIndexIfMissing('jewelry_transfers', 'certificate_id', 'jewelry_transfers_certificate_id_index');
            $this->addIndexIfMissing('jewelry_transfers', 'from_owner_id', 'jewelry_transfers_from_owner_id_index');
            $this->addIndexIfMissing('jewelry_transfers', 'to_owner_id', 'jewelry_transfers_to_owner_id_index');
            $this->addIndexIfMissing('jewelry_transfers', 'status', 'jewelry_transfers_status_index');
        }

        // =========================================================
        // 3) jewelry_owners — ایندکس ملی و ایمیل برای جست‌وجو
        // =========================================================
        if (Schema::hasTable('jewelry_owners')) {
            $this->addIndexIfMissing('jewelry_owners', 'national_id', 'jewelry_owners_national_id_index');
        }
    }

    /**
     * افزودن ایندکس تنها در صورتی که از قبل وجود نداشته باشد.
     *
     * @param string $table
     * @param string $column
     * @param string $indexName
     */
    protected function addIndexIfMissing($table, $column, $indexName)
    {
        $exists = collect(\Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]
        ))->count() > 0;

        if (!$exists) {
            try {
                Schema::table($table, function (Blueprint $blueprint) use ($column, $indexName) {
                    $blueprint->index($column, $indexName);
                });
            } catch (\Exception $e) {
                // اگر ستون نوع TEXT/BLOB باشد یا ایندکس هم‌نام موجود باشد، نادیده بگیر
            }
        }
    }

    public function down()
    {
        // حذف ستون‌هایی که این مایگریشن اضافه کرده است
        if (Schema::hasTable('jewelry_certificates')) {
            Schema::table('jewelry_certificates', function (Blueprint $table) {
                foreach (['is_active', 'created_by', 'approved_by', 'approved_at', 'rejection_reason'] as $col) {
                    if (Schema::hasColumn('jewelry_certificates', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('jewelry_transfers')) {
            Schema::table('jewelry_transfers', function (Blueprint $table) {
                foreach (['price', 'contract_file', 'requested_by', 'approved_by', 'status', 'approval_note', 'approved_at'] as $col) {
                    if (Schema::hasColumn('jewelry_transfers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
}
