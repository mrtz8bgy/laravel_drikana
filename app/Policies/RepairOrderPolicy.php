<?php

namespace App\Policies;

use App\Models\RepairOrder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy سفارش تعمیر (Repair Order).
 *
 * ── رفع H-01 ────────────────────────────────────────────────────────
 * پیش از این، هیچ Policy و هیچ بررسی مجوزی وجود نداشت و هر کاربر
 * لاگین‌شده می‌توانست سفارش‌های تعمیر همه کاربران را ببیند/ویرایش/حذف کند.
 *
 * مدل دسترسی:
 *   • مشتری        : فقط سفارش‌های خودش
 *   • تکنسین       : فقط سفارش‌های تخصیص‌یافته به خودش
 *   • مدیر تعمیرات : همه سفارش‌ها
 */
class RepairOrderPolicy
{
    use HandlesAuthorization;

    /**
     * آیا کاربر تکنسینِ تخصیص‌یافته به این سفارش است؟
     *
     * @return bool
     */
    protected function isAssignedTechnician(User $user, RepairOrder $order)
    {
        return (int) $order->assigned_to === (int) $user->id;
    }

    /**
     * آیا کاربر مشتریِ صاحب این سفارش است؟
     *
     * @return bool
     */
    protected function isOwnerCustomer(User $user, RepairOrder $order)
    {
        return (int) $order->user_id === (int) $user->id;
    }

    /**
     * مشاهده فهرست سفارش‌های تعمیر.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('jewelry.repairs.view');
    }

    /**
     * مشاهده یک سفارش تعمیر خاص.
     *
     * مشتری صاحب سفارش و تکنسین تخصیص‌یافته، حتی بدون مجوز سراسری،
     * اجازه مشاهده دارند — چون این رکورد متعلق به خودشان است.
     */
    public function view(User $user, RepairOrder $order)
    {
        if ($this->isOwnerCustomer($user, $order)) {
            return true;
        }

        if ($this->isAssignedTechnician($user, $order)) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.repairs.view');
    }

    /**
     * ایجاد سفارش تعمیر.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('jewelry.repairs.create');
    }

    /**
     * ویرایش سفارش تعمیر.
     *
     * تکنسین تخصیص‌یافته اجازه دارد شرح تعمیر و هزینه را ثبت کند، اما
     * تغییر مالکیت/حذف سفارش نیازمند مجوز مدیریتی است.
     */
    public function update(User $user, RepairOrder $order)
    {
        if ($this->isAssignedTechnician($user, $order)
            && $user->hasPermissionTo('jewelry.repairs.update_status')) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.repairs.update');
    }

    /**
     * تغییر وضعیت تعمیر.
     */
    public function updateStatus(User $user, RepairOrder $order)
    {
        if ($this->isAssignedTechnician($user, $order)
            && $user->hasPermissionTo('jewelry.repairs.update_status')) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.repairs.update_status');
    }

    /**
     * بارگذاری تصویر/مدرک برای سفارش.
     */
    public function uploadPhoto(User $user, RepairOrder $order)
    {
        if ($this->isAssignedTechnician($user, $order)
            && $user->hasPermissionTo('jewelry.repairs.update_status')) {
            return true;
        }

        return $user->hasPermissionTo('jewelry.repairs.update');
    }

    /**
     * تخصیص تکنسین به سفارش.
     */
    public function assign(User $user, RepairOrder $order)
    {
        return $user->hasPermissionTo('jewelry.repairs.assign');
    }

    /**
     * حذف سفارش تعمیر.
     *
     * سفارش‌های تکمیل‌شده و تحویل‌شده سابقه‌ی خدمت‌اند و حذف نمی‌شوند.
     */
    public function delete(User $user, RepairOrder $order)
    {
        if (in_array($order->status, ['completed', 'delivered'], true)) {
            return false;
        }

        return $user->hasPermissionTo('jewelry.repairs.delete');
    }
}
