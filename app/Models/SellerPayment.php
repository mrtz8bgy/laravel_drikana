<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * پرداخت مدیریت به فروشنده (تاریخچهٔ مالی فروشنده).
 *
 * جدول: `seller_payments`
 *
 * از این مدل در دو جا استفاده می‌شود:
 *   • داشبورد فروشنده  → `PaymentController@index` (فقط پرداخت‌های خودش)
 *   • پنل مدیریت       → `PaymentController@payment_histories` (همه فروشندگان)
 *
 * نکته: ستون `seller_id` به `users.id` اشاره می‌کند (نه `sellers.id`).
 *
 * @property int         $id
 * @property int|null    $seller_id
 * @property float       $amount
 * @property string|null $payment_method
 * @property string|null $txn_code
 */
class SellerPayment extends Model
{
    /**
     * @var string
     */
    protected $table = 'seller_payments';

    /**
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'amount',
        'payment_details',
        'payment_method',
        'txn_code',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'seller_id' => 'integer',
        'amount'    => 'float',
    ];

    /**
     * فروشنده‌ای که این پرداخت به او انجام شده است.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
