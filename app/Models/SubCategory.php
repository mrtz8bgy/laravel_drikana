<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SubCategory
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class SubCategory extends Model
{

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subSubCategories()
    {
        return $this->hasMany(SubSubCategory::class);
    }

    // ── رفع باگ ─────────────────────────────────────────────────────
    // متد نام مستعار «subsubcategories» با subSubCategories() در PHP یکی
    // شمرده می‌شد (نام متدها حساس به حروف نیست) و خطای مرگبار
    // «Cannot redeclare» می‌داد؛ حذف شد. Eloquent با نام کوچک هم به
    // رابطه می‌رسد.

}
