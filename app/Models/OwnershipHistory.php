<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * دفتر کل تاریخچه مالکیت (Ownership Ledger).
 *
 * این جدول «فقط افزودنی» است: هیچ متد یا مسیری برای ویرایش/حذف رکوردهای
 * آن در لایه اپلیکیشن وجود ندارد. هر تغییر مالکیت، یک رکورد جدید اضافه
 * می‌کند و رکورد قبلی دست‌نخورده می‌ماند.
 *
 * @property int $id
 * @property int $certificate_id
 * @property int $from_owner_id
 * @property int $to_owner_id
 * @property int $sequence_no
 * @property string $event_type
 */
class OwnershipHistory extends Model
{
    protected $table = 'ownership_history';

    protected $fillable = [
        'certificate_id',
        'from_owner_id',
        'to_owner_id',
        'transfer_id',
        'sequence_no',
        'event_type',
        'notes',
        'declared_value',
        'recorded_by',
        'effective_at',
    ];

    protected $casts = [
        'effective_at'   => 'datetime',
        'declared_value' => 'float',
        'sequence_no'    => 'integer',
    ];

    // انواع رخداد مالکیت
    const EVENT_INITIAL     = 'initial';      // ثبت اولیه مالکیت
    const EVENT_SALE        = 'sale';         // فروش
    const EVENT_GIFT        = 'gift';         // هدیه
    const EVENT_INHERITANCE = 'inheritance';  // ارث
    const EVENT_CORRECTION  = 'correction';   // اصلاح ثبت
    const EVENT_OTHER       = 'other';        // سایر

    /**
     * فهرست انواع رخداد با برچسب فارسی.
     *
     * @return array
     */
    public static function eventTypes()
    {
        return [
            self::EVENT_INITIAL     => 'ثبت اولیه',
            self::EVENT_SALE        => 'فروش',
            self::EVENT_GIFT        => 'هدیه',
            self::EVENT_INHERITANCE => 'ارث',
            self::EVENT_CORRECTION  => 'اصلاح ثبت',
            self::EVENT_OTHER       => 'سایر',
        ];
    }

    // =========================================================
    // روابط
    // =========================================================

    /**
     * قطعه/شناسنامه مربوطه.
     */
    public function certificate()
    {
        return $this->belongsTo(JewelryCertificate::class, 'certificate_id');
    }

    /**
     * مالک قبلی (در ثبت اولیه NULL است).
     */
    public function fromOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'from_owner_id');
    }

    /**
     * مالک جدید.
     */
    public function toOwner()
    {
        return $this->belongsTo(JewelryOwner::class, 'to_owner_id');
    }

    /**
     * درخواست انتقال مرجع.
     */
    public function transfer()
    {
        return $this->belongsTo(JewelryTransfer::class, 'transfer_id');
    }

    /**
     * کاربری که رکورد را ثبت کرده است.
     */
    public function recorder()
    {
        return $this->belongsTo(\App\User::class, 'recorded_by');
    }

    // =========================================================
    // متدهای دامنه
    // =========================================================

    /**
     * برچسب فارسی نوع رخداد.
     *
     * @return string
     */
    public function getEventLabelAttribute()
    {
        $types = self::eventTypes();

        return $types[$this->event_type] ?? $this->event_type;
    }

    /**
     * آیا این رکورد، «ثبت اولیه» مالکیت است؟
     *
     * @return bool
     */
    public function isInitial()
    {
        return $this->event_type === self::EVENT_INITIAL;
    }

    // =========================================================
    // جلوگیری از تغییر سوابق
    // =========================================================

    /**
     * مسدودسازی ویرایش رکوردهای تاریخی.
     *
     * این محافظ سطح مدل است: حتی اگر کدی به‌اشتباه بخواهد یک رکورد مالکیت
     * را ویرایش کند، همان ابتدا استثنا می‌گیرد. دفتر کل مالکیت باید
     * تغییرناپذیر (immutable) بماند.
     *
     * @param  array $options
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new \RuntimeException(
                'رکورد سابقه مالکیت قابل ویرایش نیست. برای اصلاح، یک رکورد جدید ثبت کنید.'
            );
        }

        return parent::save($options);
    }

    /**
     * مسدودسازی حذف رکوردهای تاریخی.
     *
     * @return bool|null
     *
     * @throws \RuntimeException
     */
    public function delete()
    {
        throw new \RuntimeException('حذف سابقه مالکیت مجاز نیست.');
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeForCertificate($query, $certificateId)
    {
        return $query->where('certificate_id', $certificateId);
    }

    public function scopeChronological($query)
    {
        return $query->orderBy('certificate_id')->orderBy('sequence_no');
    }
}
