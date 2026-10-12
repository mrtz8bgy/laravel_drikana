<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * سرویس ثبت رخدادهای حساس (Audit Trail).
 *
 * ── اصول ────────────────────────────────────────────────────────────
 * ۱) هرگز اطلاعات حساس (رمز عبور، توکن، کلید API) ثبت نمی‌شود؛ فیلدهای
 *    حساس پیش از ذخیره حذف/ماسک می‌شوند.
 * ۲) جدول فقط افزودنی است و هیچ مسیر کاربری برای حذف ندارد.
 * ۳) شکست در ثبت Audit هرگز نباید عملیات اصلی کسب‌وکار را متوقف کند؛
 *    خطا در Log برنامه ثبت می‌شود و جریان ادامه می‌یابد.
 */
class AuditLogger
{
    /**
     * فیلدهایی که هرگز نباید در Audit ثبت شوند.
     *
     * @var array
     */
    protected static $sensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'confirm_password',
        'remember_token',
        'token',
        'api_token',
        'access_token',
        'refresh_token',
        'secret',
        'api_key',
        'merchant_key',
        'client_secret',
        'verification_code',
        'otp',
        'otp_code',
        'national_id',
        'card_number',
        'cvv',
        'bank_account',
        'iban',
        'private_key',
    ];

    /**
     * ثبت یک رخداد.
     *
     * @param  string            $event       نام رخداد از ثابت‌های AuditLog
     * @param  string|null       $description توضیح خوانا
     * @param  Model|null        $auditable   موجودیت هدف
     * @param  array             $oldValues   مقادیر قبلی
     * @param  array             $newValues   مقادیر جدید
     * @param  string            $severity    سطح اهمیت
     * @param  int|null          $userId      کاربر عامل (پیش‌فرض: کاربر جاری)
     * @return \App\Models\AuditLog|null
     */
    public function log(
        $event,
        $description = null,
        Model $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        $severity = AuditLog::SEVERITY_INFO,
        $userId = null
    ) {
        try {
            $request = request();

            $record = AuditLog::create([
                'user_id'        => $userId !== null ? $userId : (Auth::check() ? Auth::id() : null),
                'event'          => $event,
                'auditable_type' => $auditable ? get_class($auditable) : null,
                'auditable_id'   => $auditable ? $auditable->getKey() : null,
                'description'    => $this->truncate($description, 500),
                'old_values'     => $this->encode($oldValues),
                'new_values'     => $this->encode($newValues),
                'ip_address'     => $request ? $this->truncate($request->ip(), 45) : null,
                'user_agent'     => $request ? $this->truncate((string) $request->userAgent(), 500) : null,
                'url'            => $request ? $this->truncate((string) $request->fullUrl(), 500) : null,
                'method'         => $request ? $this->truncate((string) $request->method(), 10) : null,
                'severity'       => $severity,
                'created_at'     => now(),
            ]);

            return $record;
        } catch (\Exception $e) {
            // ثبت Audit نباید هرگز عملیات اصلی را متوقف کند
            Log::warning('AuditLogger failed to write record', [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * ثبت رخداد امنیتی (severity بالاتر).
     *
     * @param  string $event
     * @param  string $description
     * @return \App\Models\AuditLog|null
     */
    public function security($event, $description, $severity = AuditLog::SEVERITY_WARNING)
    {
        return $this->log($event, $description, null, [], [], $severity);
    }

    /**
     * ثبت تغییر یک مدل با محاسبه تفاوت‌ها (diff).
     *
     * @param  string      $event
     * @param  Model       $model
     * @param  array       $original
     * @param  array       $changes
     * @param  string|null $description
     * @return \App\Models\AuditLog|null
     */
    public function logModelChange($event, Model $model, array $original = [], array $changes = [], $description = null)
    {
        $severity = AuditLog::SEVERITY_NOTICE;

        if (in_array($event, [
            AuditLog::EVENT_ROLE_ASSIGNED,
            AuditLog::EVENT_ROLE_REVOKED,
            AuditLog::EVENT_ROLE_PERMISSIONS,
            AuditLog::EVENT_OWNERSHIP_CHANGED,
            AuditLog::EVENT_TRANSFER_APPROVED,
            AuditLog::EVENT_USER_DELETED,
            AuditLog::EVENT_ROLE_DELETED,
        ], true)) {
            $severity = AuditLog::SEVERITY_CRITICAL;
        }

        return $this->log($event, $description, $model, $original, $changes, $severity);
    }

    /**
     * حذف فیلدهای حساس از آرایه و تبدیل به JSON.
     *
     * @param  array $values
     * @return string|null
     */
    protected function encode(array $values)
    {
        if (empty($values)) {
            return null;
        }

        return json_encode(
            $this->stripSensitive($values),
            JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR
        );
    }

    /**
     * حذف بازگشتی کلیدهای حساس از آرایه.
     *
     * @param  array $values
     * @return array
     */
    protected function stripSensitive(array $values)
    {
        $clean = [];

        foreach ($values as $key => $value) {
            if (is_string($key) && in_array(strtolower($key), static::$sensitiveKeys, true)) {
                $clean[$key] = '[REDACTED]';
                continue;
            }

            if (is_array($value)) {
                // عمق محدود تا از حلقه بی‌پایان جلوگیری شود
                $clean[$key] = $this->stripSensitive($value);
                continue;
            }

            if (is_object($value)) {
                $clean[$key] = '[object:' . get_class($value) . ']';
                continue;
            }

            if (is_string($value) && mb_strlen($value) > 2000) {
                $clean[$key] = mb_substr($value, 0, 2000) . '…[truncated]';
                continue;
            }

            $clean[$key] = $value;
        }

        return $clean;
    }

    /**
     * کوتاه‌سازی رشته برای جا شدن در ستون.
     *
     * @param  string|null $value
     * @param  int         $length
     * @return string|null
     */
    protected function truncate($value, $length)
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        return mb_strlen($value) > $length ? mb_substr($value, 0, $length) : $value;
    }
}
