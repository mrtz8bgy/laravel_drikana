<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $table = 'repair_orders';
    
    protected $fillable = [
        'user_id', 'jewelry_certificate_id', 'description',
        'repair_type', 'photo', 'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function certificate()
    {
        return $this->belongsTo(JewelryCertificate::class, 'jewelry_certificate_id');
    }
}