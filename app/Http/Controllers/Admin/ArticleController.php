<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\Content\ArticleService;
use App\Services\Content\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * کنترلر مدیریت مقالات (پنل مدیریت).
 *
 * ── بررسی مجوز ──────────────────────────────────────────────────────
 * مجوزها در سطح روتر (میدل‌ور `permission:`) و همچنین در سطح رکورد
 * (Policy) بررسی می‌شوند — دفاع لایه‌ای.
 */
class ArticleController extends Controller
{
    /**
     * @var \App\Services\Content\ArticleService
     */
    protected $service;

    public function __construct(ArticleService $service)
    {
        $this->middleware('auth');

        $this->middleware('permission:articles.view,articles.view_own')->only(['index', 'show']);
        $this->middleware('permission:articles.create')->only(['create', 'store']);
        $this->middleware('permission:articles.update')->only(['edit', 'update']);
        $this->middleware('permission:articles.delete')->only(['destroy']);
        $this->middleware('permission:articles.publish')->only(['publish', 'unpublish']);
        $this->middleware('permission:articles.submit')->only(['submit']);
        $this->middleware('permission:articles.images.manage')->only(['uploadImage', 'deleteImage']);
        $this->middleware('permission:articles.categories.manage')->only([
            'categories', 'storeCategory', 'updateCategory', 'deleteCategory',
        ]);

        $this->service = $service;
    }

    // =========================================================
    // مقالات
    // =========================================================

    /**
     * فهرست مقالات پنل مدیریت.
     *
     * نویسنده بدون مجوز سراسری فقط مقاله‌های خودش را می‌بیند.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Article::query()->with(['category', 'author']);

        // محدودسازی به مقاله‌های خود نویسنده در صورت نداشتن مجوز سراسری
        if (!Auth::user()->hasPermissionTo('articles.view')) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            if (in_array($status, Article::statuses(), true)) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('category')) {
            $query->where('category_id', (int) $request->get('category'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('excerpt', 'like', '%' . $search . '%');
            });
        }

        $articles = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'draft'          => Article::where('status', Article::STATUS_DRAFT)->count(),
            'pending_review' => Article::where('status', Article::STATUS_PENDING_REVIEW)->count(),
            'published'      => Article::where('status', Article::STATUS_PUBLISHED)->count(),
            'archived'       => Article::where('status', Article::STATUS_ARCHIVED)->count(),
        ];

        $categories = ArticleCategory::query()->ordered()->get();

        return view('admin.articles.index', compact('articles', 'stats', 'categories'));
    }

    /**
     * فرم ایجاد مقاله.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = ArticleCategory::query()->active()->ordered()->get();

        return view('admin.articles.create', compact('categories'));
    }

    /**
     * ذخیره مقاله جدید.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        try {
            $article = $this->service->create($validated, Auth::user());

            // آپلود تصویر شاخص
            if ($request->hasFile('featured_image')) {
                $this->service->uploadFeaturedImage($article, $request->file('featured_image'), Auth::user());
            }

            return redirect()
                ->route('admin.articles.edit', $article->getKey())
                ->with('success', 'مقاله «' . $article->title . '» به‌صورت پیش‌نویس ذخیره شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ذخیره مقاله.');
        }
    }

    /**
     * نمایش جزئیات مقاله در پنل.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $article = Article::query()->with(['category', 'author', 'images'])->findOrFail($id);

        $this->authorize('view', $article);

        return view('admin.articles.show', compact('article'));
    }

    /**
     * فرم ویرایش مقاله.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $article = Article::query()->with('images')->findOrFail($id);

        $this->authorize('update', $article);

        $categories = ArticleCategory::query()->active()->ordered()->get();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * به‌روزرسانی مقاله.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $article = Article::query()->findOrFail($id);

        $this->authorize('update', $article);

        $validated = $request->validate($this->rules($article), $this->messages());

        try {
            $this->service->update($article, $validated, Auth::user());

            if ($request->hasFile('featured_image')) {
                $this->service->uploadFeaturedImage($article, $request->file('featured_image'), Auth::user());
            }

            return redirect()
                ->route('admin.articles.edit', $article->getKey())
                ->with('success', 'مقاله با موفقیت به‌روزرسانی شد.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ویرایش مقاله.');
        }
    }

    /**
     * حذف مقاله.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $article = Article::query()->findOrFail($id);

        $this->authorize('delete', $article);

        try {
            $title = $article->title;
            $this->service->delete($article, Auth::user());

            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'مقاله «' . $title . '» حذف شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در حذف مقاله.');
        }
    }

    // =========================================================
    // گردش‌کار وضعیت
    // =========================================================

    /**
     * ارسال مقاله برای بررسی.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit($id)
    {
        $article = Article::query()->findOrFail($id);

        $this->authorize('submit', $article);

        try {
            $this->service->submitForReview($article, Auth::user());

            return back()->with('success', 'مقاله برای بررسی ارسال شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * انتشار مقاله.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish($id)
    {
        $article = Article::query()->findOrFail($id);

        // بررسی مجوز «انتشار» — جدای از مجوز ویرایش
        $this->authorize('publish', $article);

        try {
            $this->service->publish($article, Auth::user());

            return back()->with('success', 'مقاله منتشر شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * لغو انتشار مقاله.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unpublish($id)
    {
        $article = Article::query()->findOrFail($id);

        $this->authorize('unpublish', $article);

        try {
            $this->service->unpublish($article, Auth::user());

            return back()->with('success', 'انتشار مقاله لغو شد.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // =========================================================
    // تصاویر
    // =========================================================

    /**
     * آپلود تصویر داخل متن مقاله.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, $id)
    {
        $article = Article::query()->findOrFail($id);

        $this->authorize('deleteImage', $article);

        $request->validate([
            'image'   => 'required|file|max:3072',
            'alt'     => 'nullable|string|max:191',
            'caption' => 'nullable|string|max:500',
        ], [
            'image.required' => 'انتخاب تصویر الزامی است.',
            'image.max'      => 'حجم تصویر نباید بیشتر از ۳ مگابایت باشد.',
        ]);

        try {
            $image = $this->service->uploadInlineImage(
                $article,
                $request->file('image'),
                Auth::user(),
                ['alt' => $request->get('alt'), 'caption' => $request->get('caption')]
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'image'   => ['id' => $image->getKey(), 'url' => $image->url, 'path' => $image->path],
                ]);
            }

            return back()->with('success', 'تصویر با موفقیت آپلود شد.');
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * حذف تصویر داخل متن.
     *
     * @param  int $imageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage($imageId)
    {
        $image = \App\Models\ArticleImage::query()->with('article')->findOrFail($imageId);

        if ($image->article) {
            $this->authorize('deleteImage', $image->article);
        }

        try {
            $this->service->deleteInlineImage($image, Auth::user());

            return back()->with('success', 'تصویر حذف شد.');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در حذف تصویر.');
        }
    }

    // =========================================================
    // دسته‌بندی‌ها
    // =========================================================

    /**
     * مدیریت دسته‌بندی مقاله‌ها.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        $categories = ArticleCategory::query()
            ->withCount('articles')
            ->ordered()
            ->paginate(30);

        return view('admin.articles.categories', compact('categories'));
    }

    /**
     * ایجاد دسته‌بندی.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|min:2|max:191',
            'slug'        => 'nullable|string|max:191|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|integer|exists:article_categories,id',
            'sort_order'  => 'nullable|integer|min:0|max:9999',
            'is_active'   => 'nullable|boolean',
        ], [
            'slug.regex' => 'شناسه دسته فقط می‌تواند شامل حروف کوچک لاتین، رقم و خط تیره باشد.',
        ]);

        try {
            ArticleCategory::create([
                'name'        => HtmlSanitizer::cleanText($validated['name']),
                'slug'        => ArticleCategory::makeUniqueSlug(
                    !empty($validated['slug']) ? $validated['slug'] : $validated['name']
                ),
                'description' => HtmlSanitizer::cleanText(isset($validated['description']) ? $validated['description'] : null),
                'parent_id'   => isset($validated['parent_id']) ? $validated['parent_id'] : null,
                'sort_order'  => isset($validated['sort_order']) ? $validated['sort_order'] : 0,
                'is_active'   => !empty($validated['is_active']),
            ]);

            return back()->with('success', 'دسته‌بندی ایجاد شد.');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'خطای غیرمنتظره در ایجاد دسته‌بندی.');
        }
    }

    /**
     * ویرایش دسته‌بندی.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(Request $request, $id)
    {
        $category = ArticleCategory::query()->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|min:2|max:191',
            'slug'        => 'nullable|string|max:191|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0|max:9999',
            'is_active'   => 'nullable|boolean',
        ]);

        try {
            $category->name        = HtmlSanitizer::cleanText($validated['name']);
            $category->description = HtmlSanitizer::cleanText(isset($validated['description']) ? $validated['description'] : null);
            $category->sort_order  = isset($validated['sort_order']) ? $validated['sort_order'] : 0;
            $category->is_active   = !empty($validated['is_active']);

            if (!empty($validated['slug'])) {
                $category->slug = ArticleCategory::makeUniqueSlug($validated['slug'], $category->getKey());
            }

            $category->save();

            return back()->with('success', 'دسته‌بندی به‌روزرسانی شد.');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'خطای غیرمنتظره در ویرایش دسته‌بندی.');
        }
    }

    /**
     * حذف دسته‌بندی.
     *
     * دسته‌ای که مقاله دارد حذف نمی‌شود تا مقاله‌ها بدون دسته نمانند.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCategory($id)
    {
        $category = ArticleCategory::query()->withCount('articles')->findOrFail($id);

        if ($category->articles_count > 0) {
            return back()->with('error',
                'این دسته ' . $category->articles_count . ' مقاله دارد. '
                . 'ابتدا مقاله‌ها را به دسته دیگری منتقل کنید.'
            );
        }

        $category->delete();

        return back()->with('success', 'دسته‌بندی حذف شد.');
    }

    // =========================================================
    // قواعد اعتبارسنجی
    // =========================================================

    /**
     * قواعد اعتبارسنجی مقاله.
     *
     * @param  \App\Models\Article|null $article
     * @return array
     */
    protected function rules($article = null)
    {
        return [
            'title'              => 'required|string|min:3|max:191',
            'slug'               => 'nullable|string|max:191|regex:/^[a-z0-9-]+$/',
            'excerpt'            => 'nullable|string|max:500',
            'content'            => 'nullable|string|max:500000',
            'category_id'        => 'nullable|integer|exists:article_categories,id',
            'author_name'        => 'nullable|string|max:191',
            'keywords'           => 'nullable|string|max:1000',
            'meta_title'         => 'nullable|string|max:191',
            'meta_description'   => 'nullable|string|max:500',
            'canonical_url'      => 'nullable|url|max:500',
            'featured_image'     => 'nullable|file|max:3072',
            'featured_image_alt' => 'nullable|string|max:191',
            'is_featured'        => 'nullable|boolean',
            'sort_order'         => 'nullable|integer|min:0|max:9999',
        ];
    }

    /**
     * پیام‌های فارسی اعتبارسنجی.
     *
     * @return array
     */
    protected function messages()
    {
        return [
            'title.required'        => 'عنوان مقاله الزامی است.',
            'title.min'             => 'عنوان مقاله باید حداقل ۳ کاراکتر باشد.',
            'slug.regex'            => 'شناسه مقاله فقط می‌تواند شامل حروف کوچک لاتین، رقم و خط تیره باشد.',
            'category_id.exists'    => 'دسته‌بندی انتخاب‌شده معتبر نیست.',
            'canonical_url.url'     => 'آدرس کانونیکال باید یک URL معتبر باشد.',
            'featured_image.max'    => 'حجم تصویر شاخص نباید بیشتر از ۳ مگابایت باشد.',
            'featured_image.file'   => 'فایل تصویر شاخص نامعتبر است.',
        ];
    }
}
