<?php

namespace App\Console\Commands;

use App\Models\JewelryCertificate;
use App\Models\OwnershipHistory;
use App\Services\Ownership\OwnershipTransferService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * دستور پرکردن سابقه مالکیت برای قطعات قدیمی.
 *
 * ────────────────────────────────────────────────────────────────
 * چرا لازم است؟
 * ────────────────────────────────────────────────────────────────
 * ماژول «سابقه مالکیت» بعد از راه‌اندازی سیستم اضافه شده است. بنابراین
 * شناسنامه‌هایی که پیش‌تر صادر شده‌اند، در دفتر کل مالکیت هیچ رکوردی
 * ندارند و مالک اول آن‌ها در تاریخچه ثبت نشده است.
 *
 * این دستور برای هر شناسنامه‌ای که سابقه ندارد، یک رکورد «مالکیت اولیه»
 * با شماره ترتیب ۱ می‌سازد که مالک فعلی را به‌عنوان مالک اول ثبت می‌کند.
 *
 * ────────────────────────────────────────────────────────────────
 * تضمین‌های ایمنی
 * ────────────────────────────────────────────────────────────────
 *   • هیچ داده‌ای حذف یا بازنویسی نمی‌شود — فقط درج رکورد افزودنی.
 *   • شناسنامه‌هایی که سابقه دارند، دست‌نخورده می‌مانند.
 *   • پیش از اجرا، تعداد رکوردهای ایجادشدنی گزارش می‌شود.
 *   • گزینه --dry-run اجرای واقعی را انجام نمی‌دهد.
 *   • در محیط production نیاز به تأیید صریح دارد.
 *
 * نمونه اجرا:
 *     php artisan drikana:backfill-ownership-history --dry-run
 *     php artisan drikana:backfill-ownership-history
 */
class BackfillOwnershipHistory extends Command
{
    /**
     * نام و امضای دستور.
     *
     * @var string
     */
    protected $signature = 'drikana:backfill-ownership-history
                            {--dry-run : فقط گزارش بده، چیزی ثبت نکن}
                            {--chunk=200 : تعداد رکورد در هر دسته}';

    /**
     * توضیح دستور.
     *
     * @var string
     */
    protected $description = 'ثبت رکورد مالکیت اولیه برای شناسنامه‌های قدیمی که سابقه مالکیت ندارند';

    /**
     * @var \App\Services\Ownership\OwnershipTransferService
     */
    protected $transfers;

    /**
     * @param \App\Services\Ownership\OwnershipTransferService $transfers
     */
    public function __construct(OwnershipTransferService $transfers)
    {
        parent::__construct();

        $this->transfers = $transfers;
    }

    /**
     * اجرای دستور.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $chunk  = max(1, (int) $this->option('chunk'));

        // شناسنامه‌هایی که مالک دارند اما در دفتر کل سابقه‌ای ندارند
        $query = JewelryCertificate::query()
            ->whereNotNull('owner_id')
            ->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('ownership_history')
                    ->whereRaw('ownership_history.certificate_id = jewelry_certificates.id');
            });

        $total = (clone $query)->count();

        $this->info('شناسنامه‌های بدون سابقه مالکیت: ' . $total);

        if ($total === 0) {
            $this->info('هیچ رکوردی برای تکمیل وجود ندارد.');

            return 0;
        }

        if ($dryRun) {
            $this->warn('حالت dry-run فعال است — هیچ تغییری در دیتابیس ثبت نشد.');

            $sample = (clone $query)->limit(10)->pluck('serial_number', 'id');

            foreach ($sample as $id => $serial) {
                $this->line('  شناسنامه #' . $id . ' — شماره سری ' . ($serial ?: '—'));
            }

            return 0;
        }

        // تأیید در محیط production
        if (app()->environment('production') && !$this->option('no-interaction')) {
            if (!$this->confirm('در محیط production هستید. ادامه می‌دهید؟', false)) {
                $this->warn('عملیات لغو شد.');

                return 1;
            }
        }

        $created = 0;
        $failed  = 0;

        $query->chunkById($chunk, function ($certificates) use (&$created, &$failed) {

            foreach ($certificates as $certificate) {

                try {
                    $result = $this->transfers->recordInitialOwnership($certificate, null);

                    if ($result !== null) {
                        $created++;
                    }

                } catch (\Throwable $e) {
                    $failed++;

                    // هیچ اطلاعات حساسی لاگ نمی‌شود
                    $this->error('خطا در شناسنامه #' . $certificate->getKey() . ': ' . $e->getMessage());
                }
            }
        });

        $this->info('رکورد مالکیت اولیه ثبت‌شده: ' . $created);

        if ($failed > 0) {
            $this->warn('رکوردهای ناموفق: ' . $failed);
        }

        // بررسی نهایی
        $remaining = JewelryCertificate::query()
            ->whereNotNull('owner_id')
            ->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('ownership_history')
                    ->whereRaw('ownership_history.certificate_id = jewelry_certificates.id');
            })
            ->count();

        $this->info('شناسنامه‌های باقی‌مانده بدون سابقه: ' . $remaining);

        return $failed > 0 ? 1 : 0;
    }
}
