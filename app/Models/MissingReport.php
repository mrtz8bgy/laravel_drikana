<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissingReport extends Model
{
    protected $table = 'missing_reports';
    
    protected $fillable = [
        'user_id', 
        'jewelry_id', 
        'type', 
        'location', 
        'description', 
        'status'
    ];
    
    // رابطه با مدل JewelryCertificate
    public function jewelry()
    {
        return $this->belongsTo('App\Models\JewelryCertificate', 'jewelry_id');
    }
    
    // رابطه با کاربر
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}