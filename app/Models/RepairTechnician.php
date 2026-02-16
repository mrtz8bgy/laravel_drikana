<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairTechnician extends Model
{
    protected $table = 'repair_technicians';
    
    protected $fillable = [
        'user_id', 
        'specialty', 
        'experience_years', 
        'certificate', 
        'is_active'
    ];
    
    protected $casts = [
        'experience_years' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * ارتباط با مدل User
     * هر تکنسین مربوط به یک کاربر است
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * ارتباط با سفارشات تعمیر (از طریق user_id)
     * یک تکنسین می‌تواند چندین سفارش تعمیر داشته باشد
     */
    public function repairOrders()
    {
        return $this->hasMany(RepairOrder::class, 'assigned_to', 'user_id');
    }
    
    /**
     * دریافت نام کامل تکنسین
     */
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : 'نامشخص';
    }
    
    /**
     * دریافت ایمیل تکنسین
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '---';
    }
    
    /**
     * دریافت تخصص به فارسی
     */
    public function getSpecialtyLabelAttribute()
    {
        $specialties = [
            'gold' => 'طلاسازی',
            'silver' => 'نقره‌سازی',
            'platinum' => 'پلاتین',
            'diamond' => 'نشاندن سنگ',
            'repair' => 'تعمیرات عمومی',
            'cleaning' => 'تمیزکاری',
            'restoration' => 'بازسازی',
            'resizing' => 'تغییر اندازه',
            'all' => 'همه موارد'
        ];
        
        return $specialties[$this->specialty] ?? $this->specialty ?? 'تخصص نامشخص';
    }
    
    /**
     * دریافت وضعیت فعال به صورت متنی
     */
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'فعال' : 'غیرفعال';
    }
    
    /**
     * دریافت وضعیت فعال با رنگ
     */
    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }
    
    /**
     * دریافت اطلاعات کامل تکنسین برای نمایش
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->full_name;
        if ($this->specialty) {
            $name .= ' - ' . $this->specialty_label;
        }
        if ($this->experience_years) {
            $name .= ' (' . $this->experience_years . ' سال)';
        }
        return $name;
    }
    
    /**
     * اسکوپ برای تکنسین‌های فعال
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * اسکوپ برای تکنسین‌های غیرفعال
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    
    /**
     * اسکوپ بر اساس تخصص
     */
    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }
    
    /**
     * اسکوپ جستجو
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('specialty', 'LIKE', "%{$search}%");
        }
        return $query;
    }
    
    /**
     * اسکوپ با تجربه بیشتر از حد معین
     */
    public function scopeMinExperience($query, $years)
    {
        return $query->where('experience_years', '>=', $years);
    }
    
    /**
     * بررسی اینکه تکنسین به یک سفارش خاص دسترسی دارد
     */
    public function canAccessRepairOrder($repairOrder)
    {
        return $repairOrder->assigned_to == $this->user_id;
    }
    
    /**
     * دریافت تعداد سفارش‌های فعال
     */
    public function getActiveOrdersCountAttribute()
    {
        return $this->repairOrders()
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count();
    }
    
    /**
     * دریافت تعداد کل سفارش‌ها
     */
    public function getTotalOrdersCountAttribute()
    {
        return $this->repairOrders()->count();
    }
}