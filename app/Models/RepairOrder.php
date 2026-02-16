<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $table = 'repair_orders';
    
    protected $fillable = [
        'order_number',          // اضافه شد
        'user_id', 
        'jewelry_certificate_id', 
        'description',
        'repair_type', 
        'location',              // اضافه شد
        'scheduled_date',        // اضافه شد
        'estimated_delivery_date', // اضافه شد
        'estimated_cost',         // اضافه شد
        'final_cost',             // اضافه شد
        'assigned_to',            // اضافه شد
        'priority',               // اضافه شد
        'photo', 
        'notes',                  // اضافه شد
        'status',
        'completion_date',        // اضافه شد
        'delivery_date',          // اضافه شد
        'cancelled_at',           // اضافه شد
        'cancelled_by'            // اضافه شد
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'estimated_delivery_date' => 'date',
        'completion_date' => 'date',
        'delivery_date' => 'date',
        'cancelled_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'photo' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // روابط
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function certificate()
    {
        return $this->belongsTo(JewelryCertificate::class, 'jewelry_certificate_id');
    }
    
    public function jewelry()
    {
        return $this->belongsTo(JewelryCertificate::class, 'jewelry_certificate_id');
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function statusHistory()
    {
        return $this->hasMany(RepairStatusHistory::class, 'repair_order_id');
    }
    
    // متدهای کمکی
    public static function generateOrderNumber()
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $lastOrder = self::whereYear('created_at', $year)
                         ->whereMonth('created_at', $month)
                         ->count();
        
        return "REP-{$year}{$month}-" . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);
    }
    
    public function updateStatus($newStatus, $userId, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        
        // تنظیم تاریخ‌ها بر اساس وضعیت
        if ($newStatus == 'completed' && !$this->completion_date) {
            $this->completion_date = now();
        }
        
        if ($newStatus == 'delivered' && !$this->delivery_date) {
            $this->delivery_date = now();
        }
        
        if ($newStatus == 'cancelled' && !$this->cancelled_at) {
            $this->cancelled_at = now();
            $this->cancelled_by = $userId;
        }
        
        $this->save();
        
        // ثبت در تاریخچه
        $this->statusHistory()->create([
            'status' => $newStatus,
            'updated_by' => $userId,
            'notes' => $notes
        ]);
        
        return true;
    }
    
    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'در انتظار',
            'in_progress' => 'در حال انجام',
            'completed' => 'تکمیل شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }
    
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'delivered' => 'secondary',
            'cancelled' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }
    
    public function getPriorityLabelAttribute()
    {
        return $this->priority == 'urgent' ? 'فوری' : 'عادی';
    }
    
    public function getLocationLabelAttribute()
    {
        $labels = [
            'workshop' => 'کارگاه',
            'customer' => 'محل مشتری'
        ];
        
        return $labels[$this->location] ?? 'تعیین نشده';
    }
    
    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeByTechnician($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }
    
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
    
    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        
        return $query;
    }
}