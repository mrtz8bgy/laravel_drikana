<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * گزارش مفقودی یا سرقت یک قطعه طلا و جواهر.
 *
 * ── نکته مهم درباره نام ستون `jewelry_id` (رفع ابهام M-04) ───────────
 *
 * در این ماژول دو اصطلاح در کنار هم به‌کار می‌روند و ممکن است ایجاد
 * ابهام کنند:
 *
 *   • `jewelry`        — به مدل `JewelryCertificate` اشاره دارد؛
 *   • `jewelry_id`     — کلید خارجی به **`jewelry_certificates.id`**،
 *                        نه به جدول دیگری به نام ``jewelry``.
 *
 * یعنی هر «قطعه جواهر» در این سامانه از طریق «شناسنامه» آن شناسایی
 * می‌شود و `jewelry_id` عملاً همان شناسه شناسنامه است.
 *
 * نام ستون در دیتابیس `jewelry_id` است و تغییر نام آن به
 * `jewelry_certificate_id` نیازمند مهاجرت داده و بازنویسی گسترده کد
 * موجود است؛ بنابراین طبق اصل «تغییر گام‌به‌گام و برگشت‌پذیر»، نام
 * فعلی حفظ شده و اینجا مستند شده است.
 *
 * برای خوانایی بیشتر، نام رابطه `jewelryCertificate()` نیز اضافه شده
 * تا در کد جدید بتوان از نام گویا استفاده کرد. هر دو رابطه به یک
 * کلید خارجی اشاره می‌کنند.
 */
class MissingReport extends Model
{
    protected $table = 'missing_reports';

    protected $fillable = [
        'user_id',
        'jewelry_id',   // → jewelry_certificates.id
        'type',
        'location',
        'description',
        'police_report_path',
        'police_report_name',
        'status'
    ];

    /**
     * قطعه جواهر مرتبط با این گزارش.
     *
     * توجه: مقدار کلید خارجی، شناسه جدول `jewelry_certificates` است.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jewelry()
    {
        return $this->belongsTo('App\Models\JewelryCertificate', 'jewelry_id');
    }

    /**
     * نام گویا برای همان رابطه بالا — استفاده در کد جدید توصیه می‌شود.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jewelryCertificate()
    {
        return $this->belongsTo('App\Models\JewelryCertificate', 'jewelry_id');
    }

    /**
     * کاربری که گزارش را ثبت کرده است.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}