<?php

namespace App\Policies;

use App\Models\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy مقاله (Article).
 *
 * تفکیک کامل عملیات طبق نیازمندی:
 *   مشاهده ≠ ایجاد ≠ ویرایش ≠ حذف ≠ ارسال برای بررسی ≠ انتشار ≠ مدیریت دسته‌بندی
 *
 * قواعد مهم:
 *   • نویسنده فقط مقاله‌های خودش را ویرایش می‌کند
 *   • انتشار مقاله نیازمند مجوز صریح است و با «ویرایش» یکی نیست
 *   • مقاله منتشرشده حذف فیزیکی نمی‌شود؛ آرشیو می‌گردد
 */
class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * آیا کاربر نویسنده این مقاله است؟
     *
     * @return bool
     */
    protected function isAuthor(User $user, Article $article)
    {
        return $article->user_id !== null && (int) $article->user_id === (int) $user->id;
    }

    /**
     * مشاهده فهرست مقاله‌های پنل مدیریت.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyPermissionTo([
            'articles.view',
            'articles.view_own',
        ]);
    }

    /**
     * مشاهده یک مقاله.
     *
     * مقاله منتشرشده برای عموم آزاد است (این بررسی در کنترلر عمومی
     * استفاده نمی‌شود، اما برای پنل مدیریت معنا دارد).
     */
    public function view(User $user, Article $article)
    {
        if ($this->isAuthor($user, $article) && $user->hasPermissionTo('articles.view_own')) {
            return true;
        }

        return $user->hasPermissionTo('articles.view');
    }

    /**
     * ایجاد مقاله.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('articles.create');
    }

    /**
     * ویرایش مقاله.
     *
     * نویسنده تنها مقاله خودش را می‌تواند ویرایش کند. دارنده مجوز
     * سراسری، همه مقاله‌ها را.
     */
    public function update(User $user, Article $article)
    {
        if ($this->isAuthor($user, $article) && $user->hasPermissionTo('articles.update')) {
            return true;
        }

        return $user->hasAnyPermissionTo(['articles.update', 'articles.update_any']);
    }

    /**
     * حذف مقاله.
     *
     * مقاله منتشرشده هرگز حذف فیزیکی نمی‌شود؛ کنترلر آن را آرشیو
     * (status = archived) می‌کند تا لینک‌های عمومی ۴۰۴ نشوند.
     */
    public function delete(User $user, Article $article)
    {
        if ($article->status === Article::STATUS_PUBLISHED) {
            return false;
        }

        return $user->hasPermissionTo('articles.delete');
    }

    /**
     * ارسال مقاله برای بررسی (draft → pending_review).
     */
    public function submit(User $user, Article $article)
    {
        if ($this->isAuthor($user, $article) && $user->hasPermissionTo('articles.update')) {
            return true;
        }

        return $user->hasPermissionTo('articles.submit');
    }

    /**
     * انتشار مقاله (pending_review → published).
     *
     * این مجوز جدای از «ایجاد» و «ویرایش» است — طبق نیازمندی
     * «جداسازی مجوز ... و انتشار».
     */
    public function publish(User $user, Article $article)
    {
        return $user->hasPermissionTo('articles.publish');
    }

    /**
     * بازگرداندن مقاله منتشرشده به پیش‌نویس.
     */
    public function unpublish(User $user, Article $article)
    {
        return $user->hasPermissionTo('articles.publish');
    }

    /**
     * مدیریت دسته‌بندی مقاله‌ها.
     */
    public function manageCategories(User $user)
    {
        return $user->hasPermissionTo('articles.categories.manage');
    }

    /**
     * حذف تصویر داخل متن مقاله.
     */
    public function deleteImage(User $user, Article $article)
    {
        if ($this->isAuthor($user, $article) && $user->hasPermissionTo('articles.update')) {
            return true;
        }

        return $user->hasPermissionTo('articles.update_any');
    }
}
