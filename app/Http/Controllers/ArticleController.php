<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * کنترلر عمومی مقالات (سمت کاربران سایت).
 *
 * تنها مقاله‌های «منتشرشده» قابل مشاهده هستند. دسترسی به پیش‌نویس از
 * مسیر کاربری مسدود است — جلوگیری از IDOR و افشای محتوای منتشرنشده.
 */
class ArticleController extends Controller
{
    /**
     * فهرست مقالات منتشرشده با جست‌وجو، فیلتر دسته و صفحه‌بندی.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->select([
                'id', 'title', 'slug', 'excerpt', 'featured_image', 'featured_image_alt',
                'category_id', 'author_name', 'user_id', 'views_count', 'published_at',
            ]);

        // فیلتر دسته‌بندی بر اساس slug
        $currentCategory = null;
        if ($request->filled('category')) {
            $categorySlug = (string) $request->get('category');
            $currentCategory = ArticleCategory::query()
                ->where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();

            if ($currentCategory) {
                $query->where('category_id', $currentCategory->getKey());
            }
        }

        // جست‌وجو — با parameter binding امن است
        if ($request->filled('q')) {
            $search = trim((string) $request->get('q'));
            $search = mb_substr($search, 0, 100);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('excerpt', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%');
                });
            }
        }

        // مرتب‌سازی — فهرست سفید (جلوگیری از SQL Injection از طریق orderBy)
        $sort = (string) $request->get('sort', 'latest');
        $sortMap = [
            'latest'  => ['published_at', 'desc'],
            'oldest'  => ['published_at', 'asc'],
            'popular' => ['views_count', 'desc'],
            'title'   => ['title', 'asc'],
        ];
        list($column, $direction) = isset($sortMap[$sort]) ? $sortMap[$sort] : $sortMap['latest'];
        $query->orderBy($column, $direction);

        $articles = $query->paginate(12)->appends($request->query());

        // مقاله‌های ویژه برای نوار کنار
        $featured = Article::query()
            ->published()
            ->featured()
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        $categories = ArticleCategory::query()
            ->active()
            ->withCount(['publishedArticles'])
            ->ordered()
            ->get();

        return view('frontend.articles.index', compact('articles', 'categories', 'featured', 'currentCategory'));
    }

    /**
     * نمایش یک مقاله منتشرشده.
     *
     * ── نکته امنیتی ────────────────────────────────────────────────
     * جست‌وجو با `slug` انجام می‌شود (نه id) و شرط `published` اجباری
     * است. بنابراین حدس‌زدن شناسه عددی، پیش‌نویس را افشا نمی‌کند.
     *
     * @param  string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $article = Article::query()
            ->published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'images'])
            ->firstOrFail();

        // شمارش بازدید — با update اتمیک (بدون شرایط رقابتی)
        DB::table('articles')
            ->where('id', $article->getKey())
            ->update(['views_count' => DB::raw('views_count + 1')]);

        // مقالات مرتبط هم‌دسته
        $related = Article::query()
            ->published()
            ->where('id', '!=', $article->getKey())
            ->when($article->category_id, function ($q) use ($article) {
                $q->where('category_id', $article->category_id);
            })
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        // اگر مقاله مرتبط کافی نبود، جدیدترین‌ها را بیاور
        if ($related->count() < 4 && $article->category_id) {
            $extra = Article::query()
                ->published()
                ->where('id', '!=', $article->getKey())
                ->whereNotIn('id', $related->pluck('id')->all())
                ->orderByDesc('published_at')
                ->limit(4 - $related->count())
                ->get();

            $related = $related->merge($extra);
        }

        $categories = ArticleCategory::query()
            ->active()
            ->withCount(['publishedArticles'])
            ->ordered()
            ->get();

        return view('frontend.articles.show', compact('article', 'related', 'categories'));
    }

    /**
     * فهرست مقالات یک دسته‌بندی.
     *
     * @param  string                   $slug
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function category($slug, Request $request)
    {
        $category = ArticleCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $articles = Article::query()
            ->published()
            ->where('category_id', $category->getKey())
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = ArticleCategory::query()
            ->active()
            ->withCount(['publishedArticles'])
            ->ordered()
            ->get();

        $featured = Article::query()
            ->published()
            ->featured()
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        $currentCategory = $category;

        return view('frontend.articles.index', compact('articles', 'categories', 'featured', 'currentCategory'));
    }

    /**
     * جست‌وجوی مقالات.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }
}
