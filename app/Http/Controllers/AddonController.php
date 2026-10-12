<?php

namespace App\Http\Controllers;

use App\Addon;
use App\Menu;
use Illuminate\Http\Request;
use ZipArchive;
use DB;
use Auth;
use App\BusinessSetting;
use CoreComponentRepository;
use Illuminate\Support\Str;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.addons.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.addons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('addon_zip')) {
            // Create update directory.
            $dir = 'addons';
            if (!is_dir($dir))
                mkdir($dir, 0777, true);

            $path = $request->addon_zip->store('addons');
            $zipped_file_name = $request->addon_zip->getClientOriginalName();
            //dd(base_path('public/'.$path));
            //Unzip uploaded update file and remove zip file.
            $zip = new ZipArchive;
            $res = $zip->open(base_path('public/'.$path));

            $random_dir = Str::random(10);

            $dir = trim($zip->getNameIndex(0), '/');

            if ($res === true) {
                // ── [بازبینی L-03] استخراج ایمن ──────────────────────
                //
                // `$zip->extractTo()` بدون بازبینی نام اعضا، امکان
                // «Zip Slip» می‌دهد: یک عضو با نام `../../public/x.php`
                // بیرون از پوشه مقصد نوشته می‌شود. `extractSafely`
                // هر عضو را پیش از نوشتن بازبینی می‌کند.
                $target = base_path('temp/' . $random_dir . '/addons');

                if (!$this->extractSafely($zip, $target)) {
                    $zip->close();
                    flash('فایل افزونه نامعتبر است (مسیر غیرمجاز در بسته).')->error();
                    return redirect()->route('addons.index');
                }

                $zip->close();
            }
            else {
                flash('فایل افزونه باز نشد.')->error();
                return redirect()->route('addons.index');
            }

            // $unzipped_file_name = substr($zipped_file_name, 0, -4);



            $str = file_get_contents('temp/'.$random_dir.'/addons/'.$dir.'/config.json');

            $json = json_decode($str, true);

            //dd($json);

            if (BusinessSetting::where('type', 'current_version')->first()->value >= $json['minimum_item_version']) {
                if(count(Addon::where('unique_identifier', $json['unique_identifier'])->get()) == 0){
                    $addon = new Addon;
                    $addon->name = $json['name'];
                    $addon->unique_identifier = $json['unique_identifier'];
                    $addon->version = $json['version'];
                    $addon->activated = 1;
                    $addon->image = $json['addon_banner'];
                    $addon->save();

                    // Create new directories.
                    if (!empty($json['directory'])) {
                        //dd($json['directory'][0]['name']);
                        foreach ($json['directory'][0]['name'] as $directory) {
                            if (is_dir(base_path($directory)) == false){
                                mkdir(base_path($directory), 0777, true);

                            }else {
                                echo "error on creating directory";
                            }

                        }
                    }

                    // Create/Replace new files.
                    if (!empty($json['files'])) {
                        foreach ($json['files'] as $file){
                            $source = base_path('temp/'.$random_dir.'/'.$file['root_directory']);
                            $destination = $this->safeDestination($file['update_directory']);

                            if ($destination === null) {
                                // مقصد بیرون از پروژه یا از فایل‌های محافظت‌شده است
                                flash('مسیر غیرمجاز در بسته افزونه: ' . $file['update_directory'])->error();
                                return redirect()->route('addons.index');
                            }

                            if (is_file($source)) {
                                copy($source, $destination);
                            }
                        }
                    }

                    // Run sql modifications
                    $sql_path = $this->safeAddonSqlPath($random_dir, $dir, 'update.sql');

                    if ($sql_path !== null) {
                        DB::unprepared(file_get_contents($sql_path));
                    }

                    flash('Addon nstalled successfully')->success();
                    return redirect()->route('addons.index');
                }
                else {
                    // Create new directories.
                    if (!empty($json['directory'])) {
                        //dd($json['directory'][0]['name']);
                        foreach ($json['directory'][0]['name'] as $directory) {
                            if (is_dir(base_path($directory)) == false){
                                mkdir(base_path($directory), 0777, true);

                            }else {
                                echo "error on creating directory";
                            }

                        }
                    }

                    // Create/Replace new files.
                    if (!empty($json['files'])) {
                        foreach ($json['files'] as $file){
                            $source = base_path('temp/'.$random_dir.'/'.$file['root_directory']);
                            $destination = $this->safeDestination($file['update_directory']);

                            if ($destination === null) {
                                // مقصد بیرون از پروژه یا از فایل‌های محافظت‌شده است
                                flash('مسیر غیرمجاز در بسته افزونه: ' . $file['update_directory'])->error();
                                return redirect()->route('addons.index');
                            }

                            if (is_file($source)) {
                                copy($source, $destination);
                            }
                        }
                    }

                    $addon = Addon::where('unique_identifier', $json['unique_identifier'])->first();

                    for($i = $addon->version+0.1; $i <= $json['version']; $i=$i+0.1) {
                        // Run sql modifications
                        $sql_path = $this->safeAddonSqlPath($random_dir, $dir, $i . '.sql');

                        if ($sql_path !== null) {
                            DB::unprepared(file_get_contents($sql_path));
                        }
                    }

                    $addon->version = $json['version'];
                    $addon->save();

                    flash('This addon is updated successfully')->success();
                    return redirect()->route('addons.index');
                }
            }
            else {
                flash('This version is not capable of installing Addons, Please update.')->error();
                return redirect()->route('addons.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function show(Addon $addon)
    {
        //
    }

    public function list()
    {
        //return view('backend.'.Auth::user()->role.'.addon.list')->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function edit(Addon $addon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function activation(Request $request)
    {
        $addon  = Addon::find($request->id);
        //$menu  = Menu::where('displayed_name', $addon->unique_identifier)->first();
        $addon->activated = $request->status;

        $addon->save();
        //$menu->save();

        // $data = array(
        //     'status' => true,
        //     'notification' => translate('addon_status_updated_successfully')
        // );
        // return $data;

        return 1;
    }

    /**
     * حذف یک افزونه.
     *
     * [رفع باگ — Stage 2]
     * روت `DELETE /admin/addons/{addon}` (Route::resource) به این متد
     * اشاره می‌کرد اما متد وجود نداشت و حذف افزونه از پنل مدیریت با
     * خطای 500 مواجه می‌شد.
     *
     * نکته امنیتی:
     *   شناسه افزونه پیش از حذف اعتبارسنجی می‌شود و حذف در یک تراکنش
     *   دیتابیس انجام می‌گیرد تا اگر حذف رکورد منو شکست خورد، افزونه
     *   نیمه‌حذف باقی نماند.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $addon = Addon::findOrFail($id);

        DB::transaction(function () use ($addon) {
            // رکوردهای منوی وابسته به این افزونه نیز حذف می‌شوند
            Menu::where('parent_id', $addon->id)->orWhere('id', $addon->id)->delete();

            $addon->delete();
        });

        flash(__('افزونه با موفقیت حذف شد'))->success();

        return redirect()->route('addons.index');
    }

    // ══════════════════════════════════════════════════════════════════
    //  محافظ‌های نصب افزونه  [بازبینی L-03 — مرحله ۷]
    // ══════════════════════════════════════════════════════════════════
    //
    //  نصب افزونه، ماهیتاً یعنی «اجرای کد و SQL از یک بسته بارگذاری‌شده».
    //  این قابلیت را حذف نمی‌کنیم (قابلیت سالم پروژه است)، اما سه
    //  محافظ روی آن می‌گذاریم تا بسته «خارج از مسیر پروژه» چیزی
    //  ننویسد و فایل‌های حیاتی را بازنویسی نکند.
    //
    //  چرا این محافظ‌ها لازم‌اند؟ چون executor این افزونه‌ها
    //  `DB::unprepared()` است: بسته افزونه SQL خام اجرا می‌کند. پس
    //  «تعیین اینکه چه فایلی بارگذاری شود» باید سخت‌گیرانه باشد.
    // ══════════════════════════════════════════════════════════════════

    /**
     * فایل‌هایی که هرگز نباید با محتوای یک بسته افزونه بازنویسی شوند.
     *
     * @var array<int,string>
     */
    protected static $protectedFiles = [
        '.env', '.env.example', '.env.testing',
        '.htaccess', 'composer.json', 'composer.lock',
        'public/index.php', 'artisan',
    ];

    /**
     * پوشه‌هایی که محتوای بسته افزونه نباید در آن‌ها نوشته شود.
     *
     * @var array<int,string>
     */
    protected static $protectedDirectories = [
        'bootstrap/cache', 'storage', '.git', 'vendor', 'node_modules',
    ];

    /**
     * استخراج ایمن بسته ZIP (محافظ Zip Slip).
     *
     * هر عضو بسته پیش از نوشتن بازبینی می‌شود:
     *   • نام مطلق (`/etc/passwd`) رد می‌شود
     *   • نام دارای `..` رد می‌شود
     *   • نام دارای درایو ویندوزی (`C:\`) رد می‌شود
     *   • مسیر نهایی باید داخل پوشه مقصد بماند
     *
     * @param  \ZipArchive $zip
     * @param  string      $destination پوشه مقصد (مطلق)
     * @return bool  آیا همه اعضا ایمن بودند؟
     */
    protected function extractSafely(ZipArchive $zip, $destination)
    {
        $destination = rtrim(str_replace('\\', '/', $destination), '/');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if ($name === false) {
                return false;
            }

            $normalized = str_replace('\\', '/', $name);

            // نام مطلق یا دارای جهت‌دهی بالا
            if ($normalized === '' || $normalized[0] === '/'
                || preg_match('#^[A-Za-z]:/#', $normalized)
                || preg_match('#(^|/)\.\.(/|$)#', $normalized)
                || strpos($normalized, "\0") !== false) {
                return false;
            }

            $target = $destination . '/' . $normalized;

            // پوشه پدر ساخته می‌شود، سپس مسیر واقعی بازبینی می‌شود.
            // ترتیب مهم است: `realpath()` روی مسیر ناموجود `false`
            // برمی‌گرداند و اگر اول بازبینی کنیم، بسته سالم هم رد می‌شود.
            $parent = dirname($target);

            if (!is_dir($parent)) {
                mkdir($parent, 0755, true);
            }

            // بازبینی دوم (ضد حمله با symlink): مسیر واقعی پدر باید
            // زیر پوشه مقصد بماند. اگر پدر هنوز ساخته نشده باشد
            // (`false`)، بازبینی رشته‌ای بالا تضمین کافی است.
            $realDestination = realpath($destination);
            $realTargetParent = realpath($parent);

            if ($realDestination === false
                || ($realTargetParent !== false
                    && strpos($realTargetParent, $realDestination) !== 0)) {
                return false;
            }

            if (substr($normalized, -1) === '/') {
                continue;
            }

            $stream = $zip->getStream($name);

            if ($stream === false) {
                return false;
            }

            $out = fopen($target, 'wb');

            if ($out === false) {
                fclose($stream);
                return false;
            }

            stream_copy_to_stream($stream, $out);
            fclose($out);
            fclose($stream);
        }

        return true;
    }

    /**
     * مسیر مقصد ایمن برای یک فایل بسته افزونه.
     *
     * @param  string $relative مسیر اعلام‌شده در config.json
     * @return string|null  مسیر مطلق، یا null اگر مجاز نباشد
     */
    protected function safeDestination($relative)
    {
        if (!is_string($relative) || $relative === '') {
            return null;
        }

        // نکته سازگاری: افزونه‌های نسخه اصلی فروشنده ممکن است مسیر را با
        // «/» ابتدایی بنویسند (`/app/...`). رفتار `base_path()` هم همین
        // بود: آن را نسبی در نظر می‌گرفت. پس «/» ابتدایی حذف می‌شود تا
        // مسیر همیشه «داخل پروژه» تفسیر شود — نه اینکه به مسیر مطلق
        // سیستم تبدیل گردد. سپس «..» و درایو ویندوزی رد می‌شوند.
        $normalized = ltrim(str_replace('\\', '/', $relative), '/');

        if (strpos($normalized, '..') !== false
            || preg_match('#^[A-Za-z]:#', $normalized)
            || strpos($normalized, "\0") !== false) {
            return null;
        }

        $base = rtrim(str_replace('\\', '/', base_path()), '/');
        $target = $base . '/' . $normalized;

        // فایل محافظت‌شده
        if (in_array($normalized, self::$protectedFiles, true)) {
            return null;
        }

        // پوشه محافظت‌شده
        foreach (self::$protectedDirectories as $directory) {
            if (strpos($normalized, $directory . '/') === 0) {
                return null;
            }
        }

        // خروج از ریشه پروژه — با توجه به اینکه «..» و مسیر مطلق و
        // درایو ویندوزی در بالا رد شده‌اند، الحاق رشته‌ای نمی‌تواند از
        // ریشه بیرون بزند. این بازبینی لایه دوم است و symlink‌ها را
        // می‌گیرد.
        //
        // توجه: اگر پوشه پدر هنوز ساخته نشده باشد، `realpath()` مقدار
        // `false` می‌دهد — و این «رد کردن» نیست؛ در جریان نصب افزونه،
        // پوشه‌ها پیش از کپی ساخته می‌شوند و اگر نشده باشند، بازبینی
        // رشته‌ای بالا کافی است.
        $realParent = realpath(dirname($target));

        if ($realParent !== false && strpos($realParent, $base) !== 0) {
            return null;
        }

        return $target;
    }

    /**
     * مسیر ایمن فایل SQL یک افزونه.
     *
     * @param  string $randomDir
     * @param  string $addonDir
     * @param  string $fileName
     * @return string|null
     */
    protected function safeAddonSqlPath($randomDir, $addonDir, $fileName)
    {
        foreach ([$randomDir, $addonDir, $fileName] as $segment) {
            if (!is_string($segment) || $segment === ''
                || strpos($segment, '..') !== false
                || strpos($segment, '/') !== false
                || strpos($segment, '\\') !== false
                || strpos($segment, "\0") !== false) {
                return null;
            }
        }

        $path = base_path('temp/' . $randomDir . '/addons/' . $addonDir . '/sql/' . $fileName);

        return is_file($path) ? $path : null;
    }
}
