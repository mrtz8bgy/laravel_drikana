<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairStatusHistory extends Model
{
    protected $table = 'repair_status_histories';
    
    protected $fillable = [
        'repair_order_id', 'status', 'updated_by', 'notes'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
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
}