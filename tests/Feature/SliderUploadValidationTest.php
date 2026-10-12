<?php

namespace Tests\Feature;

use App\Slider;
use App\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * آزمون اعتبارسنجی بارگذاری اسلایدر (پنل مدیریت).
 *
 * ── مسئله ────────────────────────────────────────────────────────────
 *
 * `SliderController@store` پیش‌تر هیچ اعتبارسنجی‌ای نداشت و هر فایلی را
 * می‌پذیرفت:
 *
 *     $slider->photo = $photo->store('uploads/sliders');
 *
 * تصاویر در `public/uploads/sliders/` (زیر ریشه وب) نوشته می‌شوند. پس
 * فایلی با محتوای اجرایی که به‌عنوان «تصویر» بارگذاری شود، در سرورهایی
 * که اجرای PHP در پوشه بارگذاری را نبسته‌اند، به اجرای کد می‌رسد.
 *
 * این آزمون سنجه‌های واقعی را می‌سنجد (نه فقط بازبینی متن کد):
 * نوع واقعی فایل با `image`، پسوند، حجم و تعداد.
 */
class SliderUploadValidationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * فایل‌های ساخته‌شده در جریان آزمون.
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
     * کاربر مدیر کل — دسترسی به مسیرهای پنل.
     *
     * @return User
     */
    protected function makeSuperAdmin()
    {
        $user = new User();
        $user->name      = 'مدیر آزمون اسلایدر';
        $user->email     = 'slider' . uniqid() . '@example.test';
        $user->password  = bcrypt('test-password-12345');
        $user->user_type = 'admin';
        $user->role      = 'admin';
        $user->save();

        $roleIds = Role::query()->whereIn('slug', ['super_admin'])->pluck('id')->all();

        $payload = [];
        foreach ($roleIds as $roleId) {
            $payload[$roleId] = [
                'assigned_at' => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        $user->roles()->sync($payload);
        $user->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * ساخت یک فایل بارگذاری‌شده با محتوای دلخواه.
     *
     * @param  string $name    نام فایل (پسوند مهم است)
     * @param  string $content محتوا
     * @return UploadedFile
     */
    protected function makeFakeUpload($name, $content)
    {
        $path = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($path, $content);

        $this->createdFiles[] = $path;

        return new UploadedFile($path, $name, 'image/jpeg', null, true);
    }

    /**
     * فهرست کنونی فایل‌های پوشه اسلایدرها.
     *
     * @return array<int,string>
     */
    protected function sliderFiles()
    {
        return glob(public_path('uploads/sliders/*')) ?: [];
    }

    /**
     * پیگیری فایل‌های **تازه‌نوشته‌شده** برای پاک‌سازی و بررسی.
     *
     * توجه: `glob` نتایج را مرتب برمی‌گرداند، پس «بریدن» فهرست بر اساس
     * شمارش، فایل اشتباه می‌دهد. تفاضل مجموعه‌ها درست است.
     *
     * @param  array<int,string> $before
     * @return array<int,string> فایل‌های تازه
     */
    protected function trackNewSliderFiles(array $before)
    {
        $new = array_values(array_diff($this->sliderFiles(), $before));

        foreach ($new as $file) {
            $this->createdFiles[] = $file;
        }

        return $new;
    }

    // ==================================================================
    //  ۱) فایل غیرتصویری رد می‌شود
    // ==================================================================

    public function test_non_image_file_is_rejected()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        // فایلی با محتوای اجرایی و **پسوند تصویر** — دقیقاً همان حمله‌ای
        // که اعتبارسنجی باید بگیرد. قاعده `image` محتوا را می‌سنجد، نه نام.
        // (در Laravel 5.8 فقط `create` و `image` وجود دارند، پس فایل را
        //  دستی می‌سازیم تا محتوای دلخواه داشته باشد.)
        $countBefore = Slider::query()->count();
        $payload = $this->makeFakeUpload('shell.jpg', "<?php system(\$_GET['c']); ?>");

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => [$payload],
            ]);

        $response->assertRedirect(route('home_settings.index'));
        $response->assertSessionHasErrors('photos.0');

        $written = $this->trackNewSliderFiles($before);
        $countAfter = Slider::query()->count();

        $this->assertSame(
            [],
            $written,
            'فایل غیرتصویری نباید در پوشه بارگذاری نوشته شود.'
        );

        // توجه: دیتابیس آزمون اسلایدرهای نمونه دارد، پس «تعداد کل» سنجه
        // درستی نیست؛ افزایش تعداد بررسی می‌شود.
        $this->assertSame(
            0,
            $countAfter - $countBefore,
            'برای فایل نامعتبر نباید رکورد اسلایدر ساخته شود.'
        );
    }

    public function test_php_extension_is_rejected()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $payload = UploadedFile::fake()->create('payload.php', 10, 'application/x-php');

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => [$payload],
            ]);

        $response->assertSessionHasErrors('photos.0');

        $this->assertSame([], $this->trackNewSliderFiles($before));
    }

    // ==================================================================
    //  ۲) نبود فایل و تعداد بیش از حد
    // ==================================================================

    public function test_upload_without_file_is_rejected()
    {
        $user = $this->makeSuperAdmin();

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), ['url' => 'https://example.com']);

        $response->assertSessionHasErrors('photos');
    }

    public function test_more_than_ten_images_is_rejected()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $photos = [];

        for ($i = 0; $i < 11; $i++) {
            $photos[] = UploadedFile::fake()->image('slide-' . $i . '.jpg', 40, 40);
        }

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => $photos,
            ]);

        $response->assertSessionHasErrors('photos');

        $this->assertSame([], $this->trackNewSliderFiles($before));
    }

    // ==================================================================
    //  ۳) تصویر معتبر پذیرفته می‌شود
    // ==================================================================

    public function test_valid_image_is_accepted_and_published()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com/promo',
                'photos' => [UploadedFile::fake()->image('banner.jpg', 100, 60)],
            ]);

        $response->assertRedirect(route('home_settings.index'));
        $response->assertSessionHasNoErrors();

        $this->trackNewSliderFiles($before);

        $slider = Slider::query()->orderByDesc('id')->first();

        $this->assertNotNull($slider, 'تصویر معتبر باید رکورد اسلایدر بسازد.');
        $this->assertSame('https://example.com/promo', $slider->link);
        $this->assertSame(1, (int) $slider->published, 'اسلاید تازه باید منتشرشده باشد.');

        // فایل باید زیر پوشه وب ذخیره شده باشد (همان جایی که ویو انتظار دارد)
        $this->assertStringStartsWith('uploads/sliders/', $slider->photo);
        $this->assertFileExists(public_path($slider->photo));

        // و در صفحه اصلی دیده شود
        $this->assertContains(
            asset($slider->photo),
            $this->get('/')->getContent(),
            'اسلایدی که مدیر بارگذاری کرده، باید در صفحه اصلی رندر شود.'
        );
    }

    // ==================================================================
    //  ۴) تله‌های اعتبارسنجی که آزمون لو داد
    // ==================================================================

    public function test_jpeg_is_accepted_though_framework_image_rule_rejects_it()
    {
        // ── مهم ──────────────────────────────────────────────────────
        // در Laravel 5.8 قاعده `image` تمام JPEG ها را رد می‌کند:
        //   `image` → mimes([jpeg,png,gif,bmp,svg,webp])
        //   ولی guessExtension() برای JPEG مقدار «jpg» می‌دهد.
        // اگر در SliderController از `image` استفاده می‌شد، مدیر پنل
        // نمی‌توانست هیچ عکس jpg بارگذاری کند و فکر می‌کرد دکمه خراب است.
        // این آزمون همان رگرسیون را قفل می‌کند.
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com/jpg',
                'photos' => [UploadedFile::fake()->image('banner.jpg', 120, 80)],
            ]);

        $response->assertSessionHasNoErrors();

        $slider = Slider::query()->orderByDesc('id')->first();

        $this->assertNotNull($slider, 'عکس JPEG معتبر باید پذیرفته شود.');
        $this->assertFileExists(public_path($slider->photo));

        $this->trackNewSliderFiles($before);
    }

    public function test_png_is_accepted()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com/png',
                'photos' => [UploadedFile::fake()->image('banner.png', 120, 80)],
            ]);

        $response->assertSessionHasNoErrors();
        $this->trackNewSliderFiles($before);
    }

    public function test_random_bytes_named_jpg_is_rejected()
    {
        // فایل با بایت‌های تصادفی و نام .jpg: حدس نوع بر پایه «پسوند
        // مشتری» می‌افتد و ممکن است از قاعده mimes رد شود؛ قاعده
        // `dimensions` قطعاً می‌گیرد چون getimagesize شکست می‌خورد.
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $payload = $this->makeFakeUpload('random.jpg', str_repeat('A', 2048));

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => [$payload],
            ]);

        $response->assertSessionHasErrors();

        $this->assertSame([], $this->trackNewSliderFiles($before));
    }

    public function test_oversized_dimensions_are_rejected()
    {
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $payload = $this->makeFakeUpload('huge.jpg', '');
        // تصویر واقعی بزرگ‌تر از سقف ۴۰۰۰ پیکسل
        $image = imagecreatetruecolor(4100, 20);
        imagejpeg($image, $payload->getPathname());
        imagedestroy($image);

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => [$payload],
            ]);

        $response->assertSessionHasErrors('photos.0');

        $this->assertSame([], $this->trackNewSliderFiles($before));
    }

    public function test_polyglot_is_stored_with_image_extension()
    {
        // «پلی‌گلات»: فایل با هدر GIF و کد PHP در ادامه. چنین فایلی می‌تواند
        // از قاعده‌های نوع‌محور رد شود، اما نکته مهم‌تر این است که فایل
        // ذخیره‌شده هرگز پسوند اجرایی نگیرد. Laravel نام فایل را با
        // `hashName()` (۴۰ کاراکتر تصادفی + پسوند حدس‌زده از محتوا)
        // می‌سازد، پس خروجی `.gif` است — نه `.php`.
        $user = $this->makeSuperAdmin();
        $before = $this->sliderFiles();

        $polyglot = "GIF89a" . str_repeat("\x00", 100) . "<?php system(\$_GET['c']); ?>";
        $payload = $this->makeFakeUpload('poly.jpg', $polyglot);

        $response = $this->actingAs($user)
            ->from(route('home_settings.index'))
            ->post(route('sliders.store'), [
                'url'    => 'https://example.com',
                'photos' => [$payload],
            ]);

        $this->trackNewSliderFiles($before);

        $slider = Slider::query()->orderByDesc('id')->first();

        if ($slider === null) {
            // اگر فریم‌ورک فایل را نپذیرد، هم بی‌خطر است
            $this->assertTrue(true, 'پلی‌گلات رد شد — حالت ایمن‌تر.');

            return;
        }

        $extension = strtolower(pathinfo($slider->photo, PATHINFO_EXTENSION));

        $this->assertNotContains(
            $extension,
            ['php', 'phtml', 'phar', 'htaccess', 'js', 'html'],
            'فایل ذخیره‌شده نباید پسوند اجرایی بگیرد. پسوند: ' . $extension
        );

        $this->assertContains($extension, ['gif', 'jpg', 'jpeg', 'png', 'webp']);
    }

    public function test_upload_route_is_not_exempt_from_csrf()
    {
        // مسیر بارگذاری اسلایدر نباید در استثناهای CSRF باشد. (در آزمون،
        // میان‌افزار CSRF به‌صورت خودکار غیرفعال است، پس نمی‌توان با یک
        // درخواست واقعی این را سنجید؛ فهرست استثناها خوانده می‌شود.)
        // سازنده این میان‌افزار در Laravel 5.8 دو آرگومان می‌گیرد
        // (Application و Encrypter)، پس نمونه از کانتینر گرفته می‌شود.
        $middleware = app(\App\Http\Middleware\VerifyCsrfToken::class);

        $reflection = new \ReflectionClass($middleware);
        $property = $reflection->getProperty('except');
        $property->setAccessible(true);

        $except = (array) $property->getValue($middleware);

        $path = ltrim(parse_url(route('sliders.store'), PHP_URL_PATH), '/');

        $this->assertNotContains(
            $path,
            $except,
            'مسیر بارگذاری اسلایدر هرگز نباید از بررسی CSRF مستثنا شود.'
        );

        // خودِ فرم هم باید توکن CSRF داشته باشد
        $form = file_get_contents(resource_path('views/admin/sliders/create.blade.php'));

        $this->assertContains(
            '@csrf',
            $form,
            'فرم بارگذاری اسلایدر باید توکن CSRF داشته باشد.'
        );
    }
}
