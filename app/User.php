<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Models\Cart;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'city', 'postal_code', 
        'phone', 'country', 'provider_id', 'email_verified_at', 
        'verification_code', 'role' // اضافه کردن role
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * =============================================
     * روابط فروشگاه (موجود)
     * =============================================
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function affiliate_user()
    {
        return $this->hasOne(AffiliateUser::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function club_point()
    {
        return $this->hasOne(ClubPoint::class);
    }
    
    public function customer_package()
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function customer_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * =============================================
     * روابط جدید برای ماژول طلا و جواهر
     * =============================================
     */

    /**
     * اطلاعات مالکیت در جدول jewelry_owners
     */
    public function jewelryOwner()
    {
        return $this->hasOne(\App\Models\JewelryOwner::class, 'user_id');
    }

    /**
     * قطعات طلا و جواهر متعلق به کاربر (از طریق جدول jewelry_owners)
     */
    public function ownedJewelries()
    {
        return $this->hasManyThrough(
            \App\Models\JewelryCertificate::class,
            \App\Models\JewelryOwner::class,
            'user_id', // کلید خارجی در jewelry_owners
            'owner_id', // کلید خارجی در jewelry_certificates
            'id', // کلید محلی در users
            'id' // کلید محلی در jewelry_owners
        );
    }

    /**
     * گزارش‌های سرقت و مفقودی ثبت شده توسط کاربر
     */
    public function missingReports()
    {
        return $this->hasMany(\App\Models\MissingReport::class, 'user_id');
    }

    /**
     * سفارشات تعمیری که کاربر (به عنوان مشتری) ثبت کرده
     */
    public function repairOrders()
    {
        return $this->hasMany(\App\Models\RepairOrder::class, 'user_id');
    }

    /**
     * سفارشات تعمیری که به این کاربر (به عنوان تکنسین) اختصاص داده شده
     */
    public function assignedRepairOrders()
    {
        return $this->hasMany(\App\Models\RepairOrder::class, 'assigned_to');
    }

    /**
     * پروفایل تکنسینی (اگر کاربر نقش تکنسین داشته باشد)
     */
    public function technicianProfile()
    {
        return $this->hasOne(\App\Models\RepairTechnician::class, 'user_id');
    }

    /**
     * تاریخچه تغییرات وضعیت ثبت شده توسط این کاربر
     */
    public function statusHistories()
    {
        return $this->hasMany(\App\Models\RepairStatusHistory::class, 'updated_by');
    }

    /**
     * =============================================
     * متدهای کمکی برای بررسی نقش و دسترسی
     * =============================================
     */

    /**
     * بررسی آیا کاربر مدیر است؟
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * بررسی آیا کاربر تکنسین است؟
     */
    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    /**
     * بررسی آیا کاربر مشتری عادی است؟
     */
    public function isCustomer()
    {
        return $this->role === 'customer' || is_null($this->role);
    }

    /**
     * بررسی آیا کاربر فروشنده است؟
     */
    public function isSeller()
    {
        return !is_null($this->seller);
    }

    /**
     * بررسی دسترسی کاربر به یک سفارش تعمیر خاص
     */
    public function canAccessRepairOrder($repairOrder)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isTechnician()) {
            return $repairOrder->assigned_to == $this->id;
        }

        return $repairOrder->user_id == $this->id;
    }

    /**
     * بررسی دسترسی کاربر به یک گزارش سرقت/مفقودی خاص
     */
    public function canAccessMissingReport($missingReport)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $missingReport->user_id == $this->id;
    }

    /**
     * بررسی دسترسی کاربر به یک قطعه خاص
     */
    public function canAccessJewelry($jewelry)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $jewelry->owner && $jewelry->owner->user_id == $this->id;
    }

    /**
     * دریافت لیست قطعات قابل دسترسی برای کاربر
     */
    public function getAccessibleJewelries()
    {
        if ($this->isAdmin()) {
            return \App\Models\JewelryCertificate::all();
        }

        return $this->ownedJewelries;
    }

    /**
     * =============================================
     * Accessors
     * =============================================
     */

    /**
     * دریافت عنوان نقش به فارسی
     */
    public function getRoleLabelAttribute()
    {
        $labels = [
            'admin' => 'مدیر',
            'technician' => 'تکنسین',
            'customer' => 'مشتری'
        ];

        return $labels[$this->role] ?? 'مشتری';
    }

    /**
     * دریافت رنگ نقش برای نمایش
     */
    public function getRoleColorAttribute()
    {
        $colors = [
            'admin' => 'danger',
            'technician' => 'info',
            'customer' => 'success'
        ];

        return $colors[$this->role] ?? 'secondary';
    }

    /**
     * =============================================
     * متدهای فروشگاه (موجود)
     * =============================================
     */

    /**
     * Determine if the user has verified their phone address.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}