<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelryCertificate extends Model
{
    protected $table = 'jewelry_certificates';
    
    protected $fillable = [
        'owner_id', 'product_name', 'metal_type', 'purity',
        'weight', 'stone_type', 'serial_number', 'issued_at',
        'certificate_file'
    ];
    
    protected $dates = ['issued_at'];
    
    public function owner()
    {
        return $this->belongsTo(JewelryOwner::class, 'owner_id');
    }
    
    public function transfers()
    {
        return $this->hasMany(JewelryTransfer::class, 'certificate_id');
    }
    
    public function repairOrders()
    {
        return $this->hasMany(RepairOrder::class);
    }
    // دسترسی به آخرین مالک
    public function currentOwner()
    {
        $lastTransfer = $this->transfers()->latest()->first();
        return $lastTransfer ? $lastTransfer->toOwner : $this->owner;
    }
}
