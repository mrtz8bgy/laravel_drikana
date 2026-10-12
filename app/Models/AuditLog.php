<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * مدل Audit Log — ثبت رخدادهای حساس.
 *
 * این جدول «فقط افزودنی» است. هیچ متد یا مسیری برای ویرایش/حذف رکوردها
 * در لایه اپلیکیشن وجود ندارد. برای نگهداری دوره‌ای، یک Artisan Command
 * با دسترسی سرور باید استفاده شود.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $event
 * @property string $severity
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    /** جدول فقط created_at دارد */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'severity',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // سطوح اهمیت
    const SEVERITY_INFO     = 'info';
    const SEVERITY_NOTICE   = 'notice';
    const SEVERITY_WARNING  = 'warning';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * نام رخدادها — به‌صورت ثابت تا از پراکندگی رشته‌ها جلوگیری شود.
     */
    const EVENT_ROLE_CREATED        = 'role.created';
    const EVENT_ROLE_UPDATED        = 'role.updated';
    const EVENT_ROLE_DELETED        = 'role.deleted';
    const EVENT_ROLE_PERMISSIONS    = 'role.permissions.updated';
    const EVENT_ROLE_ASSIGNED       = 'role.assigned';
    const EVENT_ROLE_REVOKED        = 'role.revoked';
    const EVENT_PERMISSION_CREATED  = 'permission.created';
    const EVENT_PERMISSION_DELETED  = 'permission.deleted';
    const EVENT_USER_CREATED        = 'user.created';
    const EVENT_USER_UPDATED        = 'user.updated';
    const EVENT_USER_DELETED        = 'user.deleted';
    const EVENT_LOGIN_SUCCESS       = 'auth.login.success';
    const EVENT_LOGIN_FAILED        = 'auth.login.failed';
    const EVENT_LOGIN_BLOCKED       = 'auth.login.blocked';
    const EVENT_LOGOUT              = 'auth.logout';
    const EVENT_TRANSFER_REQUESTED  = 'ownership.transfer.requested';
    const EVENT_TRANSFER_APPROVED   = 'ownership.transfer.approved';
    const EVENT_TRANSFER_REJECTED   = 'ownership.transfer.rejected';
    const EVENT_TRANSFER_CANCELLED  = 'ownership.transfer.cancelled';
    const EVENT_OWNERSHIP_CHANGED   = 'ownership.changed';
    const EVENT_CERTIFICATE_CREATED = 'jewelry.certificate.created';
    const EVENT_CERTIFICATE_UPDATED = 'jewelry.certificate.updated';
    const EVENT_CERTIFICATE_DELETED = 'jewelry.certificate.deleted';
    const EVENT_REPAIR_STATUS       = 'repair.status.updated';
    const EVENT_ARTICLE_CREATED     = 'article.created';
    const EVENT_ARTICLE_UPDATED     = 'article.updated';
    const EVENT_ARTICLE_PUBLISHED   = 'article.published';
    const EVENT_ARTICLE_DELETED     = 'article.deleted';
    const EVENT_SETTINGS_UPDATED    = 'settings.updated';
    const EVENT_ACCESS_DENIED       = 'security.access.denied';
    const EVENT_PRODUCT_DELETED     = 'product.deleted';

    /**
     * کاربر عامل رخداد.
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * موجودیت هدف (polymorphic).
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * تبدیل مقادیر JSON به آرایه.
     *
     * @return array
     */
    public function getOldValuesArrayAttribute()
    {
        return $this->jsonToArray($this->old_values);
    }

    /**
     * تبدیل مقادیر JSON به آرایه.
     *
     * @return array
     */
    public function getNewValuesArrayAttribute()
    {
        return $this->jsonToArray($this->new_values);
    }

    /**
     * تبدیل ایمن رشته JSON به آرایه.
     *
     * @param  string|null $json
     * @return array
     */
    protected function jsonToArray($json)
    {
        if (empty($json)) {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeOfEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('severity', [self::SEVERITY_WARNING, self::SEVERITY_CRITICAL]);
    }

    public function scopeForAuditable($query, $type, $id)
    {
        return $query->where('auditable_type', $type)->where('auditable_id', $id);
    }

    /**
     * برچسب فارسی رخداد برای نمایش در پنل.
     *
     * @return string
     */
    public function getEventLabelAttribute()
    {
        $labels = [
            self::EVENT_ROLE_CREATED       => 'ایجاد نقش',
            self::EVENT_ROLE_UPDATED       => 'ویرایش نقش',
            self::EVENT_ROLE_DELETED       => 'حذف نقش',
            self::EVENT_ROLE_PERMISSIONS   => 'تغییر مجوزهای نقش',
            self::EVENT_ROLE_ASSIGNED      => 'انتساب نقش به کاربر',
            self::EVENT_ROLE_REVOKED       => 'سلب نقش از کاربر',
            self::EVENT_PERMISSION_CREATED => 'ایجاد مجوز',
            self::EVENT_PERMISSION_DELETED => 'حذف مجوز',
            self::EVENT_USER_CREATED       => 'ایجاد کاربر',
            self::EVENT_USER_UPDATED       => 'ویرایش کاربر',
            self::EVENT_USER_DELETED       => 'حذف کاربر',
            self::EVENT_LOGIN_SUCCESS      => 'ورود موفق',
            self::EVENT_LOGIN_FAILED       => 'تلاش ناموفق ورود',
            self::EVENT_LOGIN_BLOCKED      => 'ورود مسدود شده',
            self::EVENT_LOGOUT             => 'خروج',
            self::EVENT_TRANSFER_REQUESTED => 'ثبت درخواست انتقال مالکیت',
            self::EVENT_TRANSFER_APPROVED  => 'تأیید انتقال مالکیت',
            self::EVENT_TRANSFER_REJECTED  => 'رد انتقال مالکیت',
            self::EVENT_TRANSFER_CANCELLED => 'لغو انتقال مالکیت',
            self::EVENT_OWNERSHIP_CHANGED  => 'تغییر مالکیت',
            self::EVENT_CERTIFICATE_CREATED => 'ثبت شناسنامه',
            self::EVENT_CERTIFICATE_UPDATED => 'ویرایش شناسنامه',
            self::EVENT_CERTIFICATE_DELETED => 'حذف شناسنامه',
            self::EVENT_REPAIR_STATUS      => 'تغییر وضعیت تعمیر',
            self::EVENT_ARTICLE_CREATED    => 'ایجاد مقاله',
            self::EVENT_ARTICLE_UPDATED    => 'ویرایش مقاله',
            self::EVENT_ARTICLE_PUBLISHED  => 'انتشار مقاله',
            self::EVENT_ARTICLE_DELETED    => 'حذف مقاله',
            self::EVENT_SETTINGS_UPDATED   => 'تغییر تنظیمات',
            self::EVENT_ACCESS_DENIED      => 'دسترسی غیرمجاز',
        ];

        return $labels[$this->event] ?? $this->event;
    }

    /**
     * برچسب فارسی سطح اهمیت.
     *
     * @return string
     */
    public function getSeverityLabelAttribute()
    {
        $labels = [
            self::SEVERITY_INFO     => 'اطلاع',
            self::SEVERITY_NOTICE   => 'توجه',
            self::SEVERITY_WARNING  => 'هشدار',
            self::SEVERITY_CRITICAL => 'بحرانی',
        ];

        return $labels[$this->severity] ?? $this->severity;
    }

    /**
     * رنگ Bootstrap متناظر با سطح اهمیت.
     *
     * @return string
     */
    public function getSeverityColorAttribute()
    {
        $colors = [
            self::SEVERITY_INFO     => 'info',
            self::SEVERITY_NOTICE   => 'primary',
            self::SEVERITY_WARNING  => 'warning',
            self::SEVERITY_CRITICAL => 'danger',
        ];

        return $colors[$this->severity] ?? 'secondary';
    }
}
