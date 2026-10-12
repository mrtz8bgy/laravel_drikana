<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Models\BusinessSetting;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    public function featured()
    {
        return new CategoryCollection(Category::where('featured', 1)->get());
    }

    public function home()
    {
        // ── رفع باگ ─────────────────────────────────────────────────
        // اگر تنظیم category_homepage در دیتابیس نباشد، فراخوانی ->value
        // روی null خطای «Trying to get property 'value' of non-object» و
        // پاسخ ۵۰۰ می‌داد. اکنون در نبود تنظیم، همهٔ دسته‌های سطح اول
        // برگردانده می‌شوند.
        $homepageCategories = BusinessSetting::where('type', 'category_homepage')->first();

        if ($homepageCategories == null || empty($homepageCategories->value)) {
            return new CategoryCollection(Category::all());
        }

        $settings = json_decode($homepageCategories->value);

        if ($settings == null || !isset($settings->category)) {
            return new CategoryCollection(Category::all());
        }

        $categories = json_decode($settings->category);

        if (empty($categories)) {
            return new CategoryCollection(Category::all());
        }

        return new CategoryCollection(Category::find($categories));
    }
}
