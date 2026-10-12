<?php

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Role;
use App\Services\Content\ArticleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * داده نمونه برای بخش مقالات (فقط محیط نمایشی/توسعه).
 *
 * ── چرا جدا از DrikanaSampleDataSeeder؟ ─────────────────────────────
 * سیدرهای محصول و مالکیت، ساختار داده‌ای پیچیده‌ای دارند و زمان
 * اجرایشان طولانی است. جدا کردن مقالات اجازه می‌دهد در محیطی که فقط
 * می‌خواهید «بخش مقالات صفحه اصلی را ببینید»، تنها همین سیدر اجرا شود:
 *
 *     php artisan db:seed --class=DrikanaSampleArticlesSeeder
 *
 * ── امنیت ───────────────────────────────────────────────────────────
 * هیچ رمز عبوری اینجا ساخته نمی‌شود. مقاله‌ها با `ArticleService`
 * ساخته می‌شوند تا همان مسیر پاک‌سازی HTML و مدیریت اسلاگ اجرا شود —
 * یعنی داده نمونه هم از همان کنترل‌های امنیتی رد می‌شود که محتوای
 * واقعی رد می‌شود. اگر سیدر مستقیماً رکورد می‌ساخت، ممکن بود در آینده
 * کد ناامنی را دور بزند.
 *
 * ── تکرارپذیری ─────────────────────────────────────────────────────
 * اگر دسته‌بندی یا مقاله‌ای با همان نام موجود باشد، دوباره ساخته
 * نمی‌شود؛ بنابراین اجرای چندباره سیدر داده تکراری تولید نمی‌کند.
 */
class DrikanaSampleArticlesSeeder extends Seeder
{
    /**
     * دسته‌بندی‌های موضوعی مقالات.
     *
     * @var array<int,array{name:string,slug:string,description:string,sort_order:int}>
     */
    protected $categories = [
        [
            'name'        => 'شناخت طلا و عیار',
            'slug'        => 'gold-knowledge',
            'description' => 'عیارها، رنگ‌ها و روش‌های تشخیص طلای اصل.',
            'sort_order'  => 1,
        ],
        [
            'name'        => 'سنگ‌های قیمتی',
            'slug'        => 'gemstones',
            'description' => 'الماس، یاقوت، زمرد و راهنمای انتخاب سنگ مناسب.',
            'sort_order'  => 2,
        ],
        [
            'name'        => 'نگهداری اشیاء گران‌بها',
            'slug'        => 'care-and-maintenance',
            'description' => 'روش‌های درست تمیزکردن و نگهداری جواهرات و ساعت.',
            'sort_order'  => 3,
        ],
        [
            'name'        => 'شناسنامه و اصالت',
            'slug'        => 'certificate-and-authenticity',
            'description' => 'چرا شناسنامه مهم است و چگونه اصالت قطعه را بررسی کنیم.',
            'sort_order'  => 4,
        ],
    ];

    /**
     * مقاله‌های نمونه — با وضعیت انتشار مشخص.
     *
     * @var array<int,array<string,mixed>>
     */
    protected $articles = [
        [
            'category'     => 'gold-knowledge',
            'title'        => 'عیار طلا چیست و چگونه آن را تشخیص دهیم؟',
            'excerpt'      => 'عیار، نسبت طلای خالص به فلزات دیگر در یک قطعه است. در این راهنما یاد می‌گیرید عیارهای رایج را بشناسید و روش‌های ساده تشخیص طلای اصل را به کار ببرید.',
            'author_name'  => 'کارشناس دریکانا',
            'keywords'     => 'عیار طلا, تشخیص طلای اصل, طلای ۱۸ عیار, مهر و علامت',
            'meta_title'   => 'عیار طلا چیست؟ راهنمای کامل تشخیص طلای اصل',
            'meta_description' => 'راهنمای شناخت عیار طلا، تفاوت عیارهای رایج و روش‌های تشخیص طلای اصل بدون آسیب به قطعه.',
            'content'      => '<p>عیار طلا معیاری است که نشان می‌دهد در یک قطعه، چند بخش از هزار بخش آن طلای خالص است. طلای ۱۸ عیار یعنی ۷۵۰ واحد از هزار واحد طلا و باقی فلزات آلیاژی است.</p><h3>عیارهای رایج در بازار ایران</h3><ul><li><strong>۲۴ عیار</strong>: طلای خالص، نرم و کم‌مقاوم — برای جواهرات روزمره مناسب نیست.</li><li><strong>۱۸ عیار</strong>: رایج‌ترین عیار جواهرات در ایران؛ تعادل خوبی بین درخشش و مقاومت دارد.</li><li><strong>۱۴ عیار</strong>: مقاوم‌تر و ارزان‌تر، اما رنگ آن کم‌رنگ‌تر است.</li></ul><h3>روش‌های تشخیص</h3><p>هر قطعه معتبر باید مهر عیار داشته باشد. مهر، یک نشان کوچک است که معمولاً در قسمت پشت انگشتر یا داخل گردنبند حک می‌شود. اگر مهر وجود ندارد، آزمایش عیار در آزمایشگاه معتبر تنها راه مطمئن است.</p><p>روش‌های خانگی مانند آهنربا یا سرکه هیچ‌کدام قطعی نیستند و ممکن است به قطعه آسیب بزنند. برای اطمینان، قطعه را به آزمایشگاه بسپارید.</p>',
            'status'       => Article::STATUS_PUBLISHED,
            'is_featured'  => true,
            'published_at' => '-6 days',
            'views_count'  => 1840,
        ],
        [
            'category'     => 'care-and-maintenance',
            'title'        => '۷ نکته برای نگهداری جواهرات؛ از تمیزکردن تا انبار کردن',
            'excerpt'      => 'جواهرات گران‌بها با گذشت زمان کدر می‌شوند — اما بیشتر این کدری‌ها نتیجه اشتباه در نگهداری است، نه فرسودگی. این هفت نکته عمر قطعات شما را بیشتر می‌کند.',
            'author_name'  => 'کارشناس دریکانا',
            'keywords'     => 'نگهداری جواهرات, تمیز کردن طلا, انبار کردن جواهر, جلوگیری از کدر شدن',
            'meta_title'   => 'نگهداری جواهرات: ۷ نکته کاربردی',
            'meta_description' => 'هفت نکته عملی برای تمیزکردن، انبار کردن و حفظ درخشش طلا و جواهر و جلوگیری از آسیب به سنگ‌های قیمتی.',
            'content'      => '<p>بیشتر آسیب‌هایی که به جواهرات می‌رسد، نتیجه عادت‌های روزمره است. رعایت چند نکته ساده، سال‌ها به عمر قطعات شما اضافه می‌کند.</p><h3>۱) عطر را آخر بزنید</h3><p>عطر و اسپری مو، لایه‌ای روی سطح سنگ‌ها و طلا می‌سازند که درخشش را می‌گیرد. عطر را زودتر بزنید و پس از خشک‌شدن، جواهر را بیندازید.</p><h3>۲) شنا کردن با جواهر ممنوع</h3><p>کلر استخر به آلیاژهای طلا آسیب می‌زند و می‌تواند سطح آن را کدر کند.</p><h3>۳) جواهرات را جدا نگه دارید</h3><p>الماس سختی بالایی دارد و می‌تواند سنگ‌های نرم‌تر مثل زمرد و مروارید را خراش دهد. هر قطعه را در کیسه پارچه‌ای جدا نگه دارید.</p><h3>۴ و ۵) تمیزکردن درست</h3><p>آب گرم و چند قطره مایع ظرفشویی ملایم و یک مسواک نرم، برای بیشتر جواهرات کافی است. از جوش‌شیرین استفاده نکنید؛ به سنگ‌های متخلخل مانند زمرد و فیروزه آسیب می‌زند.</p><h3>۶ و ۷) بازدید دوره‌ای</h3><p>سالی یک‌بار گیره‌ها و پیچ‌ها را بررسی کنید. سنگ‌هایی که شل شده‌اند باید پیش از افتادن، دوباره جاگذاری شوند.</p>',
            'status'       => Article::STATUS_PUBLISHED,
            'is_featured'  => false,
            'published_at' => '-3 days',
            'views_count'  => 962,
        ],
        [
            'category'     => 'certificate-and-authenticity',
            'title'        => 'شناسنامه جواهر؛ سندی که مالکیت شما را قابل پیگیری می‌کند',
            'excerpt'      => 'شناسنامه فقط یک کاغذ نیست: شماره سری، مشخصات قطعه و سابقه مالکان را ثبت می‌کند و در زمان فروش یا اعلام سرقت، تعیین‌کننده است.',
            'author_name'  => 'کارشناس دریکانا',
            'keywords'     => 'شناسنامه جواهر, اصالت طلا, استعلام شناسنامه, سابقه مالکیت',
            'meta_title'   => 'شناسنامه جواهر چیست و چه اطلاعاتی ثبت می‌کند؟',
            'meta_description' => 'توضیح اینکه شناسنامه جواهر چه اطلاعاتی دارد، چرا در زمان فروش اهمیت دارد و چگونه اصالت آن را استعلام کنیم.',
            'content'      => '<p>شناسنامه، هویت یک قطعه است. هر شناسنامه معتبر باید حداقل این موارد را ثبت کند: شماره سری یکتا، نوع فلز و عیار، وزن، نوع و مشخصات سنگ، و عنوان مالک.</p><h3>چرا مهم است؟</h3><p>در لحظه فروش، خریدار می‌خواهد مطمئن شود قطعه همان چیزی است که ادعا می‌شود. شناسنامه با ثبت مشخصات دقیق، این اطمینان را می‌دهد.</p><h3>سابقه مالکیت</h3><p>سامانه دریکانا برای هر شناسنامه، سابقه‌ای از مالکان ثبت می‌کند. این سابقه نشان می‌دهد قطعه در چه تاریخ‌هایی به چه کسانی منتقل شده است. هنگام اعلام سرقت، این سابقه به شناسایی سریع‌تر قطعه کمک می‌کند.</p><p><strong>توجه:</strong> سوابق نرم‌افزاری «خودِ اثبات مالکیت» نیستند. برای اثبات قانونی مالکیت، اسناد رسمی لازم است و تشخیص نهایی با مراجع قانونی است.</p>',
            'status'       => Article::STATUS_PUBLISHED,
            'is_featured'  => false,
            'published_at' => '-1 day',
            'views_count'  => 415,
        ],
        [
            'category'     => 'gemstones',
            'title'        => 'الماس، یاقوت یا زمرد؟ راهنمای انتخاب سنگ بر پایه کاربرد',
            'excerpt'      => 'پیش از انتخاب سنگ قیمتی، باید بدانید از آن چه استفاده‌ای خواهید کرد. این راهنما انتخاب را بر پایه دوام، نگهداری و بودجه ساده می‌کند.',
            'author_name'  => 'کارشناس دریکانا',
            'keywords'     => 'الماس, یاقوت, زمرد, انتخاب سنگ قیمتی, دوام سنگ',
            'meta_title'   => 'انتخاب سنگ قیمتی؛ الماس، یاقوت یا زمرد؟',
            'meta_description' => 'مقایسه الماس، یاقوت و زمرد بر پایه دوام، نگهداری و کاربرد روزمره برای انتخاب آگاهانه.',
            'content'      => '<p>انتخاب سنگ، پیش از هر چیز به کاربرد آن بستگی دارد. سنگی که برای جواهر روزمره انتخاب می‌کنید با سنگی که در کلکسیون نگه داشته می‌شود، معیارهای متفاوتی دارد.</p>',
            'status'       => Article::STATUS_DRAFT,
            'is_featured'  => false,
            'published_at' => null,
            'views_count'  => 0,
        ],
    ];

    /**
     * اجرای سیدر.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB::table('users')->where('user_type', 'admin')->orderBy('id')->first();

        if (!$admin) {
            $this->command->error('کاربر مدیری برای ثبت نویسنده مقاله یافت نشد. ابتدا سیدر اصلی یا دستور drikana:make-super-admin را اجرا کنید.');

            return;
        }

        $author = \App\User::query()->find($admin->id);

        if (!$author || !$author->hasPermissionTo('articles.create')) {
            // نویسنده باید مجوز ساخت مقاله داشته باشد وگرنه سرویس رد می‌کند
            $this->command->warn('کاربر مدیر مجوز ساخت مقاله ندارد. ابتدا RbacSeeder را اجرا کنید.');

            return;
        }

        $service = app(ArticleService::class);

        // -------------------------------------------------------
        // ۱) دسته‌بندی‌ها
        // -------------------------------------------------------
        $categoryIds = [];
        $createdCategories = 0;

        foreach ($this->categories as $definition) {
            $category = ArticleCategory::query()->where('slug', $definition['slug'])->first();

            if (!$category) {
                $category = new ArticleCategory();
                $category->name        = $definition['name'];
                $category->slug        = $definition['slug'];
                $category->description = $definition['description'];
                $category->sort_order  = $definition['sort_order'];
                $category->is_active   = true;
                $category->save();

                $createdCategories++;
            }

            $categoryIds[$definition['slug']] = $category->getKey();
        }

        // -------------------------------------------------------
        // ۲) مقاله‌ها
        // -------------------------------------------------------
        $createdArticles = 0;

        foreach ($this->articles as $definition) {
            $exists = Article::withTrashed()->where('title', $definition['title'])->exists();

            if ($exists) {
                continue;
            }

            $article = $service->create([
                'title'        => $definition['title'],
                'excerpt'      => $definition['excerpt'],
                'content'      => $definition['content'],
                'category_id'  => isset($categoryIds[$definition['category']]) ? $categoryIds[$definition['category']] : null,
                'author_name'  => $definition['author_name'],
                'keywords'     => $definition['keywords'],
                'meta_title'   => isset($definition['meta_title']) ? $definition['meta_title'] : null,
                'meta_description' => isset($definition['meta_description']) ? $definition['meta_description'] : null,
                'is_featured'  => $definition['is_featured'],
            ], $author);

            // شمار بازدید نمونه (فقط برای واقعی‌بودن ظاهر صفحه)
            if (!empty($definition['views_count'])) {
                DB::table('articles')->where('id', $article->getKey())->update([
                    'views_count' => (int) $definition['views_count'],
                ]);
            }

            // وضعیت نهایی با سرویس (نه با آپدیت مستقیم) تا گردش کار و
            // ثبت Audit رعایت شود
            if ($definition['status'] === Article::STATUS_PUBLISHED) {
                $service->publish($article, $author);
            }

            // تاریخ انتشار دلخواه (برای مرتب‌سازی طبیعی در صفحه اصلی)
            if ($definition['status'] === Article::STATUS_PUBLISHED && !empty($definition['published_at'])) {
                DB::table('articles')->where('id', $article->getKey())->update([
                    'published_at' => now()->modify($definition['published_at']),
                ]);
            }

            $createdArticles++;
        }

        $this->command->info('── بخش مقالات ─────────────────────────────');
        $this->command->line('دسته‌بندی‌ها: ' . $createdCategories . ' مورد ایجاد، ' . (count($this->categories) - $createdCategories) . ' مورد موجود بود.');
        $this->command->line('مقاله‌ها: ' . $createdArticles . ' مورد ایجاد شد.');
        $this->command->line('آدرس بخش عمومی: ' . url('/articles'));
    }
}
