<?php

namespace Tests\Feature;

use App\Http\Controllers\AddonController;
use Tests\TestCase;
use ZipArchive;

/**
 * آزمون محافظ‌های نصب افزونه — بازبینی مورد L-03.
 *
 * ── مسئله ────────────────────────────────────────────────────────────
 *
 * نصب افزونه در این سامانه یعنی «باز کردن یک ZIP بارگذاری‌شده، جای‌گزینی
 * فایل‌های پروژه با محتوای آن، و اجرای فایل SQL درون بسته با
 * `DB::unprepared()`». پیش از این اصلاح هیچ بازبینی روی مسیرها نبود:
 *
 *   • **Zip Slip:** عضوی با نام `../../public/x.php` بیرون از پوشه مقصد
 *     نوشته می‌شد.
 *   • **بازنویسی فایل حیاتی:** `update_directory` در `config.json` می‌توانست
 *     `.env`، `composer.json` یا `public/index.php` باشد.
 *   • **مسیر SQL بیرون از بسته:** `$dir` از نام نخستین عضو ZIP می‌آمد و
 *     بدون بازبینی در مسیر فایل SQL استفاده می‌شد.
 *
 * این آزمون هر سه محافظ را با ZIP واقعی می‌سنجد (نه فقط بازبینی متن کد).
 */
class AddonInstallerHardeningTest extends TestCase
{
    /**
     * @var array<int,string>
     */
    protected $tempPaths = [];

    protected function tearDown(): void
    {
        foreach ($this->tempPaths as $path) {
            $this->deleteRecursively($path);
        }

        $this->tempPaths = [];

        parent::tearDown();
    }

    /**
     * سازنده کنترلر — بدون اجرای میان‌افزار.
     *
     * @return AddonController
     */
    protected function controller()
    {
        return new AddonController();
    }

    /**
     * فراخوانی یک متد محافظت‌شده با Reflection.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    protected function callProtected($method, array $arguments)
    {
        $reflection = new \ReflectionMethod($this->controller(), $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($this->controller(), $arguments);
    }

    /**
     * ساخت یک ZIP آزمایشی در پوشه موقت.
     *
     * @param  array $entries map: نام عضو → محتوا
     * @return string مسیر فایل ZIP
     */
    protected function makeZip(array $entries)
    {
        $zipPath = $this->freshTempPath('addon') . '.zip';

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($zipPath, ZipArchive::CREATE) === true);

        foreach ($entries as $name => $content) {
            if ($content === null) {
                $zip->addEmptyDir($name);
            } else {
                $zip->addFromString($name, $content);
            }
        }

        $zip->close();

        return $zipPath;
    }

    /**
     * @param  string $prefix
     * @return string مسیر تازه و بی‌وجود
     */
    protected function freshTempPath($prefix)
    {
        $path = sys_get_temp_dir() . '/' . $prefix . '-' . uniqid();
        $this->tempPaths[] = $path;

        return $path;
    }

    /**
     * @param  string $path
     * @return void
     */
    protected function deleteRecursively($path)
    {
        if (is_dir($path) && !is_link($path)) {
            foreach (scandir($path) as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                $this->deleteRecursively($path . '/' . $entry);
            }
            @rmdir($path);
        } elseif (file_exists($path)) {
            @unlink($path);
        }
    }

    // ==================================================================
    //  Zip Slip
    // ==================================================================

    public function test_zip_slip_entry_is_rejected()
    {
        $zipPath = $this->makeZip([
            'addon/config.json' => '{"name":"ok"}',
            '../escaped.txt'    => 'این فایل نباید بیرون از پوشه مقصد ساخته شود',
        ]);

        $destination = $this->freshTempPath('extract');
        mkdir($destination, 0755, true);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $result = $this->callProtected('extractSafely', [$zip, $destination]);
        $zip->close();

        $this->assertFalse($result, 'بسته دارای عضو «..» باید رد شود.');

        $escaped = dirname($destination) . '/escaped.txt';
        $this->assertFileNotExists($escaped, 'محتوای بسته نباید بیرون از پوشه مقصد نوشته شود.');
    }

    public function test_absolute_path_entry_is_rejected()
    {
        $zipPath = $this->makeZip([
            '/tmp/drikana-evil.txt' => 'مطلق',
        ]);

        $destination = $this->freshTempPath('extract');
        mkdir($destination, 0755, true);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $result = $this->callProtected('extractSafely', [$zip, $destination]);
        $zip->close();

        $this->assertFalse($result, 'عضو با مسیر مطلق باید رد شود.');
    }

    public function test_windows_drive_entry_is_rejected()
    {
        $zipPath = $this->makeZip([
            'C:/windows/evil.txt' => 'درایو',
        ]);

        $destination = $this->freshTempPath('extract');
        mkdir($destination, 0755, true);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $result = $this->callProtected('extractSafely', [$zip, $destination]);
        $zip->close();

        $this->assertFalse($result, 'عضو با مسیر درایو ویندوزی باید رد شود.');
    }

    public function test_normal_zip_is_extracted()
    {
        $zipPath = $this->makeZip([
            'myaddon/config.json'    => '{"name":"نمونه"}',
            'myaddon/sql/update.sql' => 'SELECT 1;',
        ]);

        $destination = $this->freshTempPath('extract');
        mkdir($destination, 0755, true);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $result = $this->callProtected('extractSafely', [$zip, $destination]);
        $zip->close();

        $this->assertTrue($result, 'بسته سالم باید استخراج شود.');
        $this->assertFileExists($destination . '/myaddon/config.json');
        $this->assertFileExists($destination . '/myaddon/sql/update.sql');
    }

    // ==================================================================
    //  مقصد فایل — حفاظت از فایل‌های حیاتی
    // ==================================================================

    public function test_critical_files_cannot_be_overwritten()
    {
        $blocked = [
            '.env',
            '.env.example',
            'composer.json',
            'composer.lock',
            'public/index.php',
            'artisan',
            '.htaccess',
        ];

        foreach ($blocked as $path) {
            $this->assertNull(
                $this->callProtected('safeDestination', [$path]),
                'فایل «' . $path . '» هرگز نباید با محتوای افزونه بازنویسی شود.'
            );
        }
    }

    public function test_protected_directories_are_refused()
    {
        $blocked = [
            'bootstrap/cache/config.php',
            'vendor/autoload.php',
            'storage/logs/laravel.log',
            '.git/config',
            'node_modules/x.js',
        ];

        foreach ($blocked as $path) {
            $this->assertNull(
                $this->callProtected('safeDestination', [$path]),
                'نوشتن در «' . $path . '» نباید مجاز باشد.'
            );
        }
    }

    /**
     * مسیرهای جهت‌دار (`..`) و درایو ویندوزی باید صریحاً رد شوند.
     */
    public function test_traversal_paths_are_refused()
    {
        foreach (['../outside.php', 'app/../../outside.php', '..\\outside.php', 'C:/x.php'] as $path) {
            $this->assertNull(
                $this->callProtected('safeDestination', [$path]),
                'مسیر «' . $path . '» باید رد شود.'
            );
        }
    }

    /**
     * «هیچ نوشتنی بیرون از ریشه پروژه» — ویژگی اصلی امنیتی.
     *
     * برای هر ورودی خصمانه، نتیجه باید یا `null` باشد یا مسیری **داخل**
     * ریشه پروژه. توجه: مسیر مطلق سیستم (`/etc/passwd`) عامدانه رد
     * نمی‌شود، بلکه «نسبی داخل پروژه» تفسیر می‌گردد — همان رفتار
     * `base_path()` در نسخه اصلی، برای سازگاری با افزونه‌های موجود.
     * آنچه اهمیت دارد، خودِ ویژگی ایمنی است: خروج از ریشه ممنوع.
     */
    public function test_no_input_can_escape_the_project_root()
    {
        $hostile = [
            '/etc/passwd',
            '/home/user/outside.php',
            '../outside.php',
            'app/../../outside.php',
            'C:/x.php',
            'bootstrap/cache/config.php',
            'vendor/autoload.php',
        ];

        foreach ($hostile as $path) {
            $resolved = $this->callProtected('safeDestination', [$path]);

            if ($resolved === null) {
                continue;   // رد صریح — بهترین حالت
            }

            $this->assertStringStartsWith(
                base_path(),
                $resolved,
                'مسیر «' . $path . '» به بیرون از ریشه پروژه اشاره کرد: ' . $resolved
            );
        }
    }

    public function test_legitimate_addon_file_is_allowed()
    {
        $resolved = $this->callProtected('safeDestination', ['app/Http/Controllers/AddonProbe.php']);

        $this->assertNotNull($resolved, 'مسیر سالم افزونه باید مجاز باشد.');
        $this->assertStringStartsWith(base_path(), $resolved);
    }

    // ==================================================================
    //  مسیر فایل SQL
    // ==================================================================

    public function test_sql_path_rejects_traversal_segments()
    {
        $this->assertNull(
            $this->callProtected('safeAddonSqlPath', ['aaa', '../../etc', 'update.sql']),
            'پوشه افزونه دارای «..» باید رد شود.'
        );

        $this->assertNull(
            $this->callProtected('safeAddonSqlPath', ['aaa', 'bbb', '../update.sql']),
            'نام فایل دارای «..» باید رد شود.'
        );

        $this->assertNull(
            $this->callProtected('safeAddonSqlPath', ['aa/bb', 'bbb', 'update.sql']),
            'پوشه موقت دارای جداکننده مسیر باید رد شود.'
        );
    }

    public function test_sql_path_only_returns_existing_files()
    {
        $this->assertNull(
            $this->callProtected('safeAddonSqlPath', ['nope', 'nope', 'update.sql']),
            'فایل SQL ناموجود نباید مسیر برگرداند.'
        );
    }
}
