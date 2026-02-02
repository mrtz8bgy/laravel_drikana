<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelryCertificate extends Model
{
    protected $table = 'jewelry_certificates';
    
    protected $fillable = [
        'owner_id', 
        'product_name', 
        'metal_type', 
        'purity',
        'weight', 
        'stone_type', 
        'serial_number', 
        'issued_at',
        'certificate_file',
        'description',
        'is_active',
        'created_by',
    ];
    
    protected $casts = [
        'issued_at' => 'datetime',
        'weight' => 'float',
        'purity' => 'integer',
        'is_active' => 'boolean',
    ];
    
    protected $appends = ['formatted_serial_number'];
    
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
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeBySerialNumber($query, $serialNumber)
    {
        return $query->where('serial_number', $serialNumber);
    }
    
    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }
    
    // دسترسی به آخرین مالک
    public function currentOwner()
    {
        $lastTransfer = $this->transfers()->latest()->first();
        return $lastTransfer ? $lastTransfer->toOwner : $this->owner;
    }
    
    // Accessors
    public function getFormattedSerialNumberAttribute()
    {
        return strtoupper($this->serial_number);
    }
    
    public function getEstimatedValueAttribute()
    {
        if (!$this->weight || !$this->purity) {
            return null;
        }
        
        // اینجا منطق کسب‌وکار شما رو اضافه کنید
        $goldPricePerGram = 2500000;
        $purityFactor = $this->purity / 1000;
        
        return $this->weight * $purityFactor * $goldPricePerGram;
    }
    
    // Validation rules
    public static function rules($id = null)
    {
        return [
            'owner_id' => 'required|exists:jewelry_owners,id',
            'product_name' => 'required|string|max:255',
            'serial_number' => [
                'required',
                'string',
                'max:100',
                'unique:jewelry_certificates,serial_number' . ($id ? ",$id" : ''),
            ],
            'metal_type' => 'required|in:طلا,نقره,پلاتین,سایر',
            'purity' => 'nullable|integer|min:0|max:1000',
            'weight' => 'nullable|numeric|min:0',
            'stone_type' => 'nullable|string|max:100',
            'issued_at' => 'nullable|date',
            'description' => 'nullable|string',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'is_active' => 'boolean',
        ];
    }
    
    public static function messages()
    {
        return [
            'owner_id.required' => 'انتخاب مالک الزامی است.',
            'serial_number.unique' => 'این شماره سریال قبلا ثبت شده است.',
            'certificate_file.max' => 'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
        ];
    }
    
    // بررسی تکراری نبودن شماره سریال
    public static function isSerialNumberUnique($serialNumber, $exceptId = null)
    {
        $query = self::where('serial_number', $serialNumber);
        
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        
        return !$query->exists();
    }
}