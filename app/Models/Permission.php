<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * مدل مجوز (Permission).
 *
 * هر مجوز یک شناسه اتمی به شکل `گروه.عملیات` دارد:
 *   products.view, products.create, ownership.transfers.approve, ...
 *
 * تفکیک عملیات‌ها الزامی است: مشاهده ≠ ایجاد ≠ ویرایش ≠ حذف ≠ تأیید ≠ انتشار.
 *
 * @property int    $id
 * @property string $slug
 * @property string $name
 * @property string $group
 * @property string $description
 */
class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'slug',
        'name',
        'group',
        'description',
    ];

    /** مدت اعتبار کش فهرست مجوزها (ثانیه) */
    const CACHE_TTL = 3600;
    const CACHE_KEY = 'drikana.permissions.all';
    const CACHE_KEY_GROUPS = 'drikana.permissions.grouped';

    /**
     * نقش‌هایی که این مجوز را دارند.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * فهرست کامل مجوزها به شکل [slug => name] با کش.
     *
     * @return array
     */
    public static function allSlugs()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::query()->pluck('slug')->all();
        });
    }

    /**
     * مجوزها گروه‌بندی‌شده برای نمایش در فرم تخصیص نقش.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function grouped()
    {
        return Cache::remember(self::CACHE_KEY_GROUPS, self::CACHE_TTL, function () {
            return self::query()
                ->orderBy('group')
                ->orderBy('slug')
                ->get()
                ->groupBy('group')
                ->map(function ($permissions) {
                    return $permissions->pluck('name', 'slug')->all();
                });
        });
    }

    /**
     * پاک‌سازی کش مجوزها — باید پس از هر تغییر در مجوزها فراخوانی شود.
     */
    public static function flushCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY_GROUPS);
        Role::flushCache();
    }
}
