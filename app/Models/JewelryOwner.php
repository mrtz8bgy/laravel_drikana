<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelryOwner extends Model
{
    protected $table = 'jewelry_owners';
    
    protected $fillable = [
        'user_id', 'full_name', 'national_id', 
        'phone', 'address', 'email'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function certificates()
    {
        return $this->hasMany(JewelryCertificate::class, 'owner_id');
    }
    
    public function transfersFrom()
    {
        return $this->hasMany(JewelryTransfer::class, 'from_owner_id');
    }
    
    public function transfersTo()
    {
        return $this->hasMany(JewelryTransfer::class, 'to_owner_id');
    }
}
