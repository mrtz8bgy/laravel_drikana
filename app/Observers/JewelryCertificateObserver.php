<?php

namespace App\Observers;

use App\Models\JewelryCertificate;
use App\Models\OwnershipHistory;
use App\Services\Ownership\OwnershipTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ناظر (Observer) مدل شناسنامه جواهرات.
 *
 * وظیفه: هر شناسنامه تازه‌ای که ساخته می‌شود، یک رکورد «مالکیت اولیه»
 * (sequence_no = 1) در دفتر کل مالکیت بگیرد.
 *
 * چرا Observer و نه فراخوانی دستی در کنترلر؟
 *   • شناسنامه‌ها از چند مسیر ساخته می‌شوند (پنل مدیریت، API، ایمپورت SQL).
 *     فراخوانی دستی در کنترلر باعث می‌شد هر مسیر جدیدی که فراموش شود،
 *     یک قطعه بدون سابقه مالکیت در سیستم باقی بگذارد.
 *   • Observer تضمین می‌کند این کار در همه مسیرها به‌طور یکسان انجام شود.
 *
 * نکات امنیتی:
 *   • این ناظر هیچ داده‌ای را حذف یا بازنویسی نمی‌کند؛ فقط رکورد افزودنی
 *     ثبت می‌کند. اگر ثبت سابقه شکست بخورد، خطا لاگ می‌شود تا ایجاد خود
 *     شناسنامه متوقف نشود (شکست بی‌صدا در برابر از دست رفتن داده).
 *   • سابقه مالکیت فقط از طریق سریس رسمی تغییر می‌کند، نه از طریق این ناظر.
 */
class JewelryCertificateObserver
{
    /**
     * @var \App\Services\Ownership\OwnershipTransferService
     */
    protected $transfers;

    public function __construct(OwnershipTransferService $transfers)
    {
        $this->transfers = $transfers;
    }

    /**
     * پس از ایجاد شناسنامه، مالکیت اولیه را در دفتر کل ثبت کن.
     *
     * @param  \App\Models\JewelryCertificate $certificate
     * @return void
     */
    public function created(JewelryCertificate $certificate)
    {
        if ($certificate->owner_id === null) {
            return;
        }

        try {
            $actor = Auth::user();

            $this->transfers->recordInitialOwnership($certificate, $actor);

        } catch (\Throwable $e) {
            // ثبت اولیه مالکیت نباید ایجاد شناسنامه را شکست دهد.
            // اما حتماً لاگ می‌شود تا در بازبینی دستی رسیدگی شود.
            Log::warning('ثبت اولیه سابقه مالکیت ناموفق بود', [
                'certificate_id' => $certificate->getKey(),
                'error'          => $e->getMessage(),
            ]);
        }
    }
}
