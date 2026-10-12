<?php

namespace Tests\Feature;

use App\Slider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * آزمون اسلایدر بنر صفحه اصلی — رفع اشکال «اسلایدر نمایش داده نمی‌شود».
 *
 * ── ریشه اشکال ───────────────────────────────────────────────────────
 *
 * پنل مدیریت از ابتدا امکان بارگذاری، فعال/غیرفعال و حذف اسلاید را داشت
 * (`frontend_settings/sliders` روی جدول `sliders`)، و CSS کامل بنر هم در
 * `public/css/drikana/store.css` وجود داشت — اما **هیچ‌جا از سایت، اسلاید
 * را رندر نمی‌کرد**. در بازطراحی لوکس، بخش بنر با یک «HERO استاتیک»
 * جای‌گزین شده و مارک‌آپ `@foreach (\App\Slider...)` از ویو حذف شده بود.
 * نتیجه: اسلایدهای بارگذاری‌شده در صفحه اصلی دیده نمی‌شدند.
 *
 * این آزمون سه چیز را تضمین می‌کند:
 *   ۱) اسلایدهای منتشرشده با تصویر موجود، در HTML صفحه اصلی می‌آیند؛
 *   ۲) اسلاید غیرفعال یا با فایل تصویر ناموجود، رندر نمی‌شود
 *      (بنر شکسته یا اسلاید ناخواسته نه)؛
 *   ۳) نبود اسلاید، صفحه اصلی را خراب نمی‌کند (بخش به‌کل حذف می‌شود).
 */
class HomeSliderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * فایل‌های تصویر ساخته‌شده در جریان آزمون (برای پاک‌سازی).
     *
     * @var array<int,string>
     */
    protected $createdFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        $this->createdFiles = [];

        parent::tearDown();
    }

    /**
     * ساخت یک تصویر آزمایشی در public/uploads/sliders.
     *
     * محتوای فایل مهم نیست؛ منطق برنامه فقط «وجود فایل» را بررسی می‌کند.
     * اما همین بررسی، مهم است: رکوردی با نام فایل ساختگی نباید بنر
     * شکسته بسازد.
     *
     * @return string مسیر نسبی (همان چیزی که در ستون photo ذخیره می‌شود)
     */
    protected function makeSliderImage()
    {
        $relative = 'uploads/sliders/test-' . uniqid() . '.jpg';
        $absolute = public_path($relative);

        if (!is_dir(dirname($absolute))) {
            mkdir(dirname($absolute), 0755, true);
        }

        file_put_contents($absolute, 'fake-image-bytes');
        $this->createdFiles[] = $absolute;

        return $relative;
    }

    /**
     * ساخت یک اسلایدر.
     *
     * @param  array $attributes
     * @return Slider
     */
    protected function makeSlider(array $attributes = [])
    {
        $slider = new Slider();

        $slider->photo     = isset($attributes['photo']) ? $attributes['photo'] : $this->makeSliderImage();
        $slider->link      = isset($attributes['link']) ? $attributes['link'] : '#';
        $slider->published = isset($attributes['published']) ? $attributes['published'] : 1;
        $slider->save();

        return $slider;
    }

    /**
     * خالی‌کردن جدول اسلایدرها برای سناریوی «بدون اسلاید».
     *
     * @return void
     */
    protected function removeAllSliders()
    {
        Slider::query()->delete();
    }

    // ==================================================================
    //  ۱) نمایش اسلایدهای منتشرشده
    // ==================================================================

    public function test_homepage_renders_published_sliders()
    {
        $this->removeAllSliders();

        $first  = $this->makeSlider(['link' => 'https://example.com/one']);
        $second = $this->makeSlider(['link' => 'https://example.com/two']);

        $response = $this->get('/');

        $response->assertStatus(200);

        $body = $response->getContent();

        $this->assertContains(
            'fullwidth-slider-section',
            $body,
            'بخش بنر اسلایدر باید در صفحه اصلی رندر شود.'
        );

        $this->assertContains(
            'slick-carousel',
            $body,
            'اسلایدر باید با کلاس و data-attribute های مورد انتظار موتور اسلایدر رندر شود.'
        );

        $this->assertContains(
            asset($first->photo),
            $body,
            'تصویر اسلاید اول باید در HTML باشد.'
        );

        $this->assertContains(
            asset($second->photo),
            $body,
            'تصویر اسلاید دوم باید در HTML باشد.'
        );

        // ترتیب: همان ترتیب جدول (id صعودی) — چون `orderBy` صریح شده است
        $this->assertLessThan(
            strpos($body, asset($second->photo)),
            strpos($body, asset($first->photo)),
            'اسلایدها باید به ترتیب id نمایش داده شوند (ترتیب پنل مدیریت).'
        );
    }

    public function test_slider_carousel_is_configured_as_single_slide_banner()
    {
        $this->removeAllSliders();
        $this->makeSlider();

        $body = $this->get('/')->getContent();

        // موتور اسلایدر پروژه (main.js → slickInit) این data-attribute ها را
        // می‌خواند. اگر تعداد اسلاید هر نما ۱ نباشد، بنر مثل کاروسل
        // محصولات چندتایی می‌شود و طراحی بنر می‌شکند.
        foreach (['data-slick-items="1"', 'data-slick-dots="true"', 'data-slick-autoplay="true"'] as $attribute) {
            $this->assertContains(
                $attribute,
                $body,
                'صفت «' . $attribute . '» در تنظیمات اسلایدر بنر لازم است.'
            );
        }
    }

    public function test_slider_overlay_content_uses_existing_design_classes()
    {
        $this->removeAllSliders();
        $this->makeSlider();

        $body = $this->get('/')->getContent();

        // این کلاس‌ها در public/css/drikana/store.css تعریف شده‌اند؛
        // استفاده از همان‌ها یعنی «بدون CSS تازه» و ظاهر سازگار با طراحی.
        foreach (['slide-content-overlay', 'slide-content', 'slide-btn-custom'] as $class) {
            $this->assertContains($class, $body, 'کلاس «' . $class . '» بخش بنر انتظار می‌رفت.');
        }
    }

    // ==================================================================
    //  ۲) چیزهایی که نباید نمایش داده شوند
    // ==================================================================

    public function test_unpublished_slider_is_not_rendered()
    {
        $this->removeAllSliders();

        $visible   = $this->makeSlider(['published' => 1]);
        $invisible = $this->makeSlider(['published' => 0]);

        $body = $this->get('/')->getContent();

        $this->assertContains(
            asset($visible->photo),
            $body,
            'اسلاید منتشرشده باید نمایش داده شود.'
        );

        $this->assertNotContains(
            asset($invisible->photo),
            $body,
            'اسلاید غیرفعال نباید در صفحه اصلی بیاید — این همان کلید پنل مدیریت است.'
        );
    }

    public function test_slider_with_missing_image_file_is_skipped()
    {
        $this->removeAllSliders();

        $good = $this->makeSlider();

        // رکوردی که فایل تصویرش روی دیسک نیست (مثل رکورد نمونه‌ای که
        // تصویرش بعداً پاک شده). نمایش آن، بنر شکسته می‌سازد.
        $broken = $this->makeSlider(['photo' => 'uploads/sliders/this-file-does-not-exist.jpg']);

        $body = $this->get('/')->getContent();

        $this->assertContains(asset($good->photo), $body);

        $this->assertNotContains(
            asset($broken->photo),
            $body,
            'اسلایدی که فایل تصویرش وجود ندارد نباید بنر شکسته بسازد.'
        );
    }

    public function test_homepage_without_published_sliders_hides_the_section()
    {
        $this->removeAllSliders();

        $body = $this->get('/')->getContent();

        $this->assertNotContains(
            'fullwidth-slider-section',
            $body,
            'وقتی اسلایدی نیست، بخش بنر باید به‌کل حذف شود (نه کادر خالی).'
        );

        // صفحه اصلی در هر حالت باید سالم باشد — بدون اسلایدر هم کار می‌کند
        $this->assertContains('d-hero', $body);
    }

    // ==================================================================
    //  ۳) پیوند اسلاید
    // ==================================================================

    public function test_slider_with_placeholder_link_does_not_open_blank_tab()
    {
        $this->removeAllSliders();
        $this->makeSlider(['link' => '#']);

        $section = $this->sliderSection($this->get('/')->getContent());

        $this->assertNotSame(
            '',
            $section,
            'بخش بنر باید رندر شده باشد تا این آزمون معنا داشته باشد.'
        );

        // توجه: در کل صفحه، جاهای دیگری از قالب هم `href="#"` دارند
        // (مثل بازکننده‌های منوی کشویی). پس بررسی فقط داخل بخش بنر
        // انجام می‌شود، وگرنه آزمون مثبت کاذب می‌شود.
        $this->assertNotContains(
            'href="#"',
            $section,
            'پیوند «#» نباید در بنر به لنگر تبدیل شود — کلیک روی آن، تب خالی باز می‌کند.'
        );

        $this->assertContains(
            route('categories.all'),
            $section,
            'در نبود پیوند، دکمه بنر باید به صفحه دسته‌بندی‌ها اشاره کند.'
        );
    }

    public function test_external_slider_link_is_hardened()
    {
        $this->removeAllSliders();
        $this->makeSlider(['link' => 'https://example.com/promo']);

        $body = $this->get('/')->getContent();

        $this->assertContains('https://example.com/promo', $body);
        $this->assertContains(
            'rel="noopener noreferrer"',
            $body,
            'پیوند بیرونی باید rel="noopener noreferrer" داشته باشد تا صفحه مقصد '
            . 'به window.opener دسترسی نداشته باشد.'
        );
    }

    /**
     * بریدن بخش بنر اسلایدر از کل HTML صفحه.
     *
     * @param  string $body
     * @return string
     */
    protected function sliderSection($body)
    {
        $start = strpos($body, 'fullwidth-slider-section');

        if ($start === false) {
            return '';
        }

        $end = strpos($body, '</section>', $start);

        return $end === false ? substr($body, $start) : substr($body, $start, $end - $start);
    }

    // ==================================================================
    //  ۴) نبود جدول (نصب ناقص)
    // ==================================================================

    public function test_published_sliders_query_is_ordered_and_guarded()
    {
        // متد باید بدون خطا کار کند و مجموعه برگرداند
        $reflection = new \ReflectionMethod(\App\Http\Controllers\HomeController::class, 'publishedSliders');
        $reflection->setAccessible(true);

        $controller = new \App\Http\Controllers\HomeController();
        $result = $reflection->invoke($controller);

        $this->assertInstanceOf(
            \Illuminate\Support\Collection::class,
            $result,
            'publishedSliders باید همیشه یک Collection برگرداند (حتی در نصب ناقص).'
        );

        // ترتیب id صعودی
        $ids = $result->pluck('id')->all();
        $sorted = $ids;
        sort($sorted);

        $this->assertSame($sorted, $ids, 'اسلایدها باید به ترتیب id بازگردند.');
    }
}
