<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جداول سیستم مدیریت محتوا (مقالات طلا و جواهرات).
 *
 *   article_categories — دسته‌بندی موضوعی مقالات
 *   articles           — مقالات با گردش‌کار انتشار
 *   article_images     — تصاویر داخل متن هر مقاله (رابطه یک‌به‌چند)
 *
 * نکات امنیتی متناظر (در لایه اپلیکیشن پیاده شده‌اند):
 *   • تصویر اصلی و تصاویر داخل متن با اعتبارسنجی «نوع واقعی فایل» ذخیره می‌شوند
 *   • نام فایل به‌صورت تصادفی و امن تولید می‌شود
 *   • HTML متن مقاله با HTMLPurifier پاک‌سازی می‌شود
 */
class CreateArticlesTables extends Migration
{
    public function up()
    {
        // ---------------------------------------------------------
        // article_categories
        // ---------------------------------------------------------
        if (!Schema::hasTable('article_categories')) {
            Schema::create('article_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 191);
                $table->string('slug', 191)->unique('article_categories_slug_unique');
                $table->string('description', 500)->nullable();
                $table->string('image', 191)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('parent_id', 'article_categories_parent_id_index');
                $table->index('is_active', 'article_categories_is_active_index');
            });
        }

        // ---------------------------------------------------------
        // articles
        // ---------------------------------------------------------
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->increments('id');

                $table->string('title', 191);
                // slug یکتا برای URL خوانا
                $table->string('slug', 191)->unique('articles_slug_unique');

                $table->string('excerpt', 500)->nullable();
                $table->longText('content')->nullable();

                // تصویر شاخص
                $table->string('featured_image', 191)->nullable();
                $table->string('featured_image_alt', 191)->nullable();

                $table->unsignedInteger('category_id')->nullable();

                // نویسنده — name برای درج دستی نام، user_id برای رهگیری
                $table->unsignedInteger('user_id')->nullable();
                $table->string('author_name', 191)->nullable();

                // کلمات کلیدی به شکل JSON array
                $table->text('keywords')->nullable();

                // SEO
                $table->string('meta_title', 191)->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->string('canonical_url', 500)->nullable();

                // draft | pending_review | published | archived
                $table->string('status', 30)->default('draft');
                $table->boolean('is_featured')->default(false);

                $table->unsignedBigInteger('views_count')->default(0);
                $table->unsignedInteger('sort_order')->default(0);

                // رهگیری گردش‌کار
                $table->unsignedInteger('submitted_by')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedInteger('published_by')->nullable();
                $table->timestamp('published_at')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index('category_id', 'articles_category_id_index');
                $table->index('status', 'articles_status_index');
                $table->index('published_at', 'articles_published_at_index');
                $table->index('user_id', 'articles_user_id_index');
                $table->index('is_featured', 'articles_is_featured_index');

                $table->foreign('category_id')
                    ->references('id')->on('article_categories')
                    ->onDelete('set null')->onUpdate('cascade');
            });
        }

        // ---------------------------------------------------------
        // article_images — تصاویر داخل متن
        // ---------------------------------------------------------
        if (!Schema::hasTable('article_images')) {
            Schema::create('article_images', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('article_id');
                $table->string('path', 191);
                $table->string('original_name', 191)->nullable();
                $table->string('alt', 191)->nullable();
                $table->string('caption', 500)->nullable();
                $table->unsignedInteger('file_size')->nullable();
                $table->string('mime_type', 100)->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index('article_id', 'article_images_article_id_index');

                $table->foreign('article_id')
                    ->references('id')->on('articles')
                    ->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('article_images');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
}
