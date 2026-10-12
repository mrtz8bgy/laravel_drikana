<?php

namespace App\Policies;

use App\Models\JewelryCertificate;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy شناسنامه طلا و جواهر (Jewelry Certificate).
 *
 * ── چرا این فایل لازم بود؟ ─────────────────────────────────────────
 * کنترلر `Jewelry\JewelryCertificateController` از `$this->authorize('view', $certificate)`
 * استفاده می‌کرد، اما هیچ Policy برای مدل `JewelryCertificate` ثبت نشده بود.
 * نتیجه: بررسی مجوز همیشه با خطا رد می‌شد و صفحات شناسنامه برای کاربران
 * عادی از کار می‌افتاد. این Policy آن شکاف را پر می‌کند.
 *
 * مدل دسترسی: مالک قطعه به رکورد خودش دسترسی دارد؛ دارنده مجوز سراسری
 * (مدیر مالکیت / مدیر فروشگاه) به همه رکوردها.
 */
class JewelryCertificatePolicy
{
    use HandlesAuthorization;

    /**
     * آیا کاربر مالک این شناسنامه است؟
     *
     * نکته مهم (جلوگیری از IDOR): مالکیت از دو مسیر بررسی می‌شود:
     *   ۱) رابطه مستقیم jewelry_owners.user_id
     *   ۲) آخرین رکورد دفتر کل مالکیت (ownership_history)
     * این کار مانع آن می‌شود که پس از انتقال مالکیت، مالک قبلی همچنان
     * دسترسی داشته باشد.
     *
     * @param  \App\User                     $user
     * @param  \App\Models\JewelryCertificate $certificate
     * @return bool
     */
    protected function ownsCertificate(User $user, JewelryCertificate $certificate)
    {
        if ($certificate->owner && (int) $certificate->owner->user_id === (int) $user->id) {
            return true;
        }

        // بررسی از طریق تاریخچه مالکیت (پس از انتقال‌های نهایی‌شده)
        $latest = \App\Models\OwnershipHistory::query()
            ->where('certificate_id', $certificate->getKey())
            ->orderByDesc('sequence_no')
            ->first();

        if ($latest && $latest->toOwner && (int) $latest->toOwner->user_id === (int) $user->id) {
            return true;
        }

        return false;
    }

    /**
     * مشاهده شناسنامه.
     *
     * @return bool
     */
    public function view(User $user, JewelryCertificate $certificate)
    {
        if ($this->ownsCertificate($user, $certificate)) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.certificates.view');
    }

    /**
     * ایجاد شناسنامه جدید.
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('jewelry.certificates.create');
    }

    /**
     * ویرایش شناسنامه.
     *
     * ویرایش مشخصات فنی قطعه یک عملیات سطح مدیریتی است و به مالک عادی
     * داده نمی‌شود (مالک نباید بتواند عیار یا وزن ثبت‌شده را تغییر دهد).
     *
     * @return bool
     */
    public function update(User $user, JewelryCertificate $certificate)
    {
        return $user->hasPermissionTo('jewelry.certificates.update');
    }

    /**
     * حذف شناسنامه.
     *
     * حذف سابقه‌ی ثبت‌شده مجاز نیست مگر با مجوز صریح حذف. حتی در آن صورت
     * کنترلر باید به‌جای حذف فیزیکی، `is_active = false` کند.
     *
     * @return bool
     */
    public function delete(User $user, JewelryCertificate $certificate)
    {
        return $user->hasPermissionTo('jewelry.certificates.delete');
    }

    /**
     * تأیید اصالت شناسنامه.
     *
     * @return bool
     */
    public function verify(User $user, JewelryCertificate $certificate)
    {
        return $user->hasPermissionTo('jewelry.certificates.verify');
    }

    /**
     * دانلود فایل شناسنامه.
     *
     * @return bool
     */
    public function download(User $user, JewelryCertificate $certificate)
    {
        if ($this->ownsCertificate($user, $certificate)) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.certificates.download');
    }

    /**
     * چاپ شناسنامه.
     *
     * @return bool
     */
    public function print(User $user, JewelryCertificate $certificate)
    {
        if ($this->ownsCertificate($user, $certificate)) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.certificates.print');
    }

    /**
     * مشاهده تاریخچه مالکیت.
     *
     * @return bool
     */
    public function viewHistory(User $user, JewelryCertificate $certificate)
    {
        if ($this->ownsCertificate($user, $certificate)) {
            return true;
        }

        return $user->hasAnyPermissionTo([
            'jewelry.certificates.view',
            'jewelry.transfers.view',
        ]);
    }
}
