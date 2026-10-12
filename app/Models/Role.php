<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * مدل نقش (Role).
 *
 * توسعه‌ی جدول موجود `roles`. این مدل مرجع اصلی نقش‌ها در سیستم RBAC است.
 *
 * ── سازگاری با گذشته ────────────────────────────────────────────────
 * جدول `roles` ستون متنی `permissions` دارد که در آن آرایه JSON مجوزها
 * ذخیره می‌شد (و در هیچ کدی خوانده نمی‌شد). این ستون حفظ شده تا کد قدیمی
 * نشکند، اما منبع حقیقت اکنون جدول واسط `permission_role` است.
 * متد `syncLegacyPermissionsColumn()` هر دو را هم‌گام نگه می‌دارد.
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int    $level
 * @property bool   $is_system
 * @property bool   $is_active
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'is_system',
        'is_active',
        // ستون قدیمی — برای سازگاری عقب‌رو حفظ شده است
        'permissions',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'level'     => 'integer',
    ];

    /** مدت اعتبار کش مجوزهای هر نقش (ثانیه) */
    const CACHE_TTL = 3600;

    // =========================================================
    // روابط
    // =========================================================

    /**
     * مجوزهای این نقش (منبع حقیقت RBAC).
     */
    public function permissionModels()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    /**
     * کاربرانی که این نقش را دارند.
     */
    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'role_user', 'role_id', 'user_id')
            ->withTimestamps();
    }

    // =========================================================
    // متدهای دامنه
    // =========================================================

    /**
     * دریافت آرایه slug مجوزهای این نقش (با کش).
     *
     * @return array
     */
    public function permissionSlugs()
    {
        $roleId = $this->getKey();

        return Cache::remember(self::cacheKey($roleId), self::CACHE_TTL, function () {
            return $this->permissionModels()->pluck('slug')->all();
        });
    }

    /**
     * بررسی داشتن یک مجوز مشخص.
     *
     * @param  string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        return in_array($permissionSlug, $this->permissionSlugs(), true);
    }

    /**
     * هم‌گام‌سازی مجوزها بر اساس آرایه‌ای از slug.
     *
     * @param  array $slugs
     * @return void
     */
    public function syncPermissions(array $slugs)
    {
        // فقط slugهای معتبری که در جدول permissions وجود دارند پذیرفته می‌شوند.
        // این کار از تزریق مجوز جعلی و خطای کلید خارجی جلوگیری می‌کند.
        $validIds = Permission::query()->whereIn('slug', $slugs)->pluck('id')->all();

        $this->permissionModels()->sync($validIds);

        $this->syncLegacyPermissionsColumn($slugs);

        Cache::forget(self::cacheKey($this->getKey()));
    }

    /**
     * هم‌گام‌سازی ستون قدیمی `permissions` (JSON) با مجوزهای فعلی.
     *
     * هدف: اگر جایی از کد قدیمی یا ابزار خارجی این ستون را بخواند، مقدار
     * نادرست نبیند. این ستون دیگر منبع حقیقت نیست.
     *
     * @param  array|null $slugs
     * @return void
     */
    public function syncLegacyPermissionsColumn(array $slugs = null)
    {
        if ($slugs === null) {
            $slugs = $this->permissionSlugs();
        }

        $this->forceFill([
            'permissions' => json_encode(array_values($slugs), JSON_UNESCAPED_UNICODE),
        ])->save();
    }

    /**
     * نقش پیش‌فرض سیستم (برای ثبت‌نام کاربران جدید).
     *
     * @return \App\Models\Role|null
     */
    public static function defaultCustomerRole()
    {
        return static::query()->where('slug', 'customer')->first();
    }

    /**
     * یافتن نقش بر اساس slug.
     *
     * @param  string $slug
     * @return \App\Models\Role|null
     */
    public static function findBySlug($slug)
    {
        return static::query()->where('slug', $slug)->first();
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('level')->orderBy('name');
    }

    // =========================================================
    // کش
    // =========================================================

    /**
     * کلید کش مجوزهای یک نقش.
     *
     * @param  int $roleId
     * @return string
     */
    public static function cacheKey($roleId)
    {
        return 'drikana.role.' . $roleId . '.permissions';
    }

    /**
     * پاک‌سازی کش تمام نقش‌ها.
     *
     * @return void
     */
    public static function flushCache()
    {
        static::query()->pluck('id')->each(function ($id) {
            Cache::forget(self::cacheKey($id));
        });
        Cache::forget('drikana.roles.all');
    }

    /**
     * پاک‌سازی کش این نقش هنگام ذخیره یا حذف.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($role) {
            Cache::forget(self::cacheKey($role->getKey()));
        });

        static::deleted(function ($role) {
            Cache::forget(self::cacheKey($role->getKey()));
        });
    }
}
