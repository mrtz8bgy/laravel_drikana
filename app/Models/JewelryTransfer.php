<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelryTransfer extends Model
{
    protected $table = 'jewelry_transfers';
    
    protected $fillable = [
        'certificate_id', 'from_owner_id', 'to_owner_id',
        'transfer_date', 'transfer_type', 'notes'
    ];
    
    protected $dates = ['transfer_date'];
    
    public function certificate()
    {
        return $this->belongsTo(JewelryCertificate::class, 'certificate_id');
    }
    
    public function fromOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'from_owner_id');
    }
    
    public function toOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'to_owner_id');
    }
}
