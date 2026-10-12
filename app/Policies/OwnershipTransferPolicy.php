<?php

namespace App\Policies;

use App\Models\JewelryTransfer;
use App\Models\OwnershipHistory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy انتقال مالکیت (Ownership Transfer).
 *
 * قواعد کلیدی:
 *   • مشاهده    : مالک فعلی، مالک مقصد، درخواست‌دهنده، یا دارنده مجوز مشاهده
 *   • ایجاد      : دارنده مجوز ایجاد + مالک فعلی بودن (برای مسیر کاربری)
 *   • تأیید      : دارنده مجوز تأیید و **متفاوت از درخواست‌دهنده** (تفکیک وظایف)
 *   • رد         : دارنده مجوز رد
 *   • لغو        : درخواست‌دهنده تا پیش از نهایی‌شدن، یا دارنده مجوز لغو
 */
class OwnershipTransferPolicy
{
    use HandlesAuthorization;

    /**
     * آیا کاربر به این درخواست انتقال مرتبط است (به‌عنوان یکی از طرفین)؟
     *
     * @return bool
     */
    protected function isPartyTo(User $user, JewelryTransfer $transfer)
    {
        $userId = (int) $user->id;

        if ((int) $transfer->requested_by === $userId) {
            return true;
        }

        // مالک قبلی
        if ($transfer->fromOwner && (int) $transfer->fromOwner->user_id === $userId) {
            return true;
        }

        // مالک جدید
        if ($transfer->toOwner && (int) $transfer->toOwner->user_id === $userId) {
            return true;
        }

        return false;
    }

    /**
     * مشاهده درخواست انتقال.
     */
    public function view(User $user, JewelryTransfer $transfer)
    {
        if ($this->isPartyTo($user, $transfer)) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.transfers.view');
    }

    /**
     * مشاهده فهرست انتقال‌ها (بدون رکورد خاص).
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('jewelry.transfers.view');
    }

    /**
     * ثبت درخواست انتقال.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('jewelry.transfers.create');
    }

    /**
     * تأیید انتقال.
     *
     * شرط تفکیک وظایف: تأییدکننده نباید خودِ درخواست‌دهنده باشد.
     * این قاعده حتی برای مدیر کل سیستم هم اعمال می‌شود.
     */
    public function approve(User $user, JewelryTransfer $transfer)
    {
        if (!$user->hasPermissionTo('jewelry.transfers.approve')) {
            return false;
        }

        // درخواست باید در وضعیت pending باشد
        if ($transfer->status !== JewelryTransfer::STATUS_PENDING) {
            return false;
        }

        // تأییدکننده ≠ درخواست‌دهنده
        return (int) $transfer->requested_by !== (int) $user->id;
    }

    /**
     * رد انتقال.
     */
    public function reject(User $user, JewelryTransfer $transfer)
    {
        if (!$user->hasPermissionTo('jewelry.transfers.reject')) {
            return false;
        }

        if ($transfer->status !== JewelryTransfer::STATUS_PENDING) {
            return false;
        }

        return (int) $transfer->requested_by !== (int) $user->id;
    }

    /**
     * لغو انتقال.
     *
     * درخواست‌دهنده می‌تواند تا پیش از تصمیم‌گیری، درخواست خود را لغو کند.
     */
    public function cancel(User $user, JewelryTransfer $transfer)
    {
        if ($transfer->status !== JewelryTransfer::STATUS_PENDING) {
            return false;
        }

        // درخواست‌دهنده همیشه می‌تواند درخواست خودش را لغو کند
        if ((int) $transfer->requested_by === (int) $user->id) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.transfers.cancel');
    }

    /**
     * حذف رکورد انتقال.
     *
     * ── قاعده مهم ─────────────────────────────────────────────────
     * انتقال‌های نهایی‌شده (approved) «سابقه» هستند و هرگز نباید حذف
     * شوند. تنها انتقال‌های رد یا لغو شده و آن هم با مجوز صریح حذف
     * قابل پاک‌سازی‌اند.
     */
    public function delete(User $user, JewelryTransfer $transfer)
    {
        if (in_array($transfer->status, [JewelryTransfer::STATUS_APPROVED], true)) {
            return false;
        }

        return $user->hasPermissionTo('jewelry.transfers.delete');
    }

    /**
     * مشاهده تاریخچه مالکیت یک قطعه.
     */
    public function viewHistory(User $user)
    {
        return $user->hasAnyPermissionTo([
            'jewelry.transfers.view',
            'jewelry.certificates.view',
        ]);
    }
}
