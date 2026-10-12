<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use App\BusinessSetting;
use Artisan;
use CoreComponentRepository;

class BusinessSettingsController extends Controller
{
    public function activation(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
    	return view('admin.business_settings.activation');
    }

    public function social_login(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.business_settings.social_login');
    }

    public function smtp_settings(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.business_settings.smtp_settings');
    }

    public function google_analytics(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.business_settings.google_analytics');
    }

    public function facebook_chat(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.business_settings.facebook_chat');
    }

    public function payment_method(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        return view('admin.business_settings.payment_method');
    }

    /**
     * Update the API key's for payment methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payment_method_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', $request->payment_method.'_sandbox')->first();
        // dd($business_settings->type);
        if($business_settings != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $business_settings->value = 1;
                $business_settings->save();
            }
            else{
                $business_settings->value = 0;
                $business_settings->save();
            }
        }

        flash("تنظیمات با موفقیت بروزرسانی شد")->success();
        return back();
    }

    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function google_analytics_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_analytics')->first();

        if ($request->has('google_analytics')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        flash("تنظیمات با موفقیت بروزرسانی شد")->success();
        return back();
    }

    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function facebook_chat_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_chat')->first();

        if ($request->has('facebook_chat')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        flash("تنظیمات با موفقیت بروزرسانی شد")->success();
        return back();
    }

    public function facebook_pixel_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_pixel')->first();

        if ($request->has('facebook_pixel')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        flash("تنظیمات با موفقیت بروزرسانی شد")->success();
        return back();
    }

    /**
     * Update the API key's for other methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * به‌روزرسانی مقادیر کلیدهای محیطی از پنل مدیریت.
     *
     * ── سخت‌سازی امنیتی [M-10] ────────────────────────────────────────
     *
     * پیش از این اصلاح، هر کلید و هر مقداری که در بدنه درخواست می‌آمد
     * بی‌هیچ بازبینی در فایل `.env` نوشته می‌شد:
     *
     *   • `$type` بازبینی نمی‌شد → امکان افزودن کلید دلخواه به `.env`
     *     (مثلاً `APP_DEBUG`, `SESSION_DRIVER`, `MAIL_DRIVER`) و در
     *     نتیجه تغییر رفتار کل سامانه.
     *   • `$val` از نظر کاراکترهای کنترلی بازبینی نمی‌شد → تزریق
     *     `\r\n` در مقدار، یعنی افزودن «کلیدهای تازه» در میان یک مقدار.
     *   • نوشتن مستقیم روی فایل (بدون فایل موقت) → اگر فرایند در میانه
     *     کار قطع می‌شد، `.env` نیمه‌نوشته و سامانه بی‌کلید می‌ماند.
     *
     * اکنون سه محافظ اعمال می‌شود:
     *   ۱. «فهرست مجاز کلیدها» — تنها کلیدهایی که در `.env.example`
     *      تعریف شده‌اند پذیرفته می‌شوند (Deny by Default).
     *   ۲. «بازبینی مقدار» — کاراکتر کنترلی، کوتیشن و بک‌اسلش رد می‌شود.
     *   ۳. «نوشتن اتمیک» — ابتدا فایل موقت، سپس جای‌گزینی یک‌باره.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function env_key_update(Request $request)
    {
        $types = $request->input('types', []);

        if (!is_array($types)) {
            flash('درخواست نامعتبر است.')->error();
            return back();
        }

        $allowed = $this->writableEnvKeys();
        $written = 0;
        $rejected = [];

        foreach ($types as $type) {
            $type = is_string($type) ? $type : '';

            // ── محافظ ۱: فهرست مجاز ──────────────────────────────
            if ($type === '' || !in_array($type, $allowed, true)) {
                $rejected[] = $type;
                continue;
            }

            $value = $request->input($type, '');

            // مقدار آرایه‌ای پذیرفته نمی‌شود (منبع حمله تزریق)
            if (!is_string($value)) {
                $rejected[] = $type;
                continue;
            }

            if (!$this->isSafeEnvValue($value)) {
                $rejected[] = $type;
                continue;
            }

            if ($this->overWriteEnvFile($type, $value)) {
                $written++;
            } else {
                $rejected[] = $type;
            }
        }

        if (!empty($rejected)) {
            flash(
                'به‌روزرسانی انجام شد، اما این کلیدها پذیرفته نشدند: '
                . implode(', ', $rejected)
            )->warning();
        }

        if ($written > 0 || empty($rejected)) {
            flash('تنظیمات با موفقیت بروزرسانی شد.')->success();
        }

        return back();
    }

    /**
     * فهرست کلیدهایی که اجازه نوشتن در `.env` را دارند.
     *
     * منبع فهرست، خودِ `.env.example` است: اگر کلیدی در قالب نمونه
     * وجود ندارد، این متد هم آن را نمی‌نویسد. بنابراین افزودن یک کلید
     * تازه به `.env` نیازمند افزودن آن به `.env.example` (و در نتیجه
     * یک تغییر کد قابل بازبینی در Git) است — نه یک درخواست HTTP.
     *
     * کلیدهای حساس عمداً از فهرست بیرون گذاشته شده‌اند تا پنل مدیریت
     * نتواند خودش را قفل کند یا وضعیت امنیتی سامانه را تغییر دهد.
     *
     * @return array
     */
    protected function writableEnvKeys()
    {
        // کلیدهایی که هرگز نباید از رابط کاربری نوشته شوند
        $neverWritable = [
            'APP_KEY',          // تغییر آن تمام داده رمزنگاری‌شده را ناخوانا می‌کند
            'APP_ENV',          // محیط اجرا باید در استقرار تعیین شود
            'APP_DEBUG',        // افشای جزئیات خطای PHP در محیط عملیاتی
            'APP_URL',
            'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE',
            'DB_USERNAME', 'DB_PASSWORD',   // خرابی کامل اتصال دیتابیس
            'SESSION_DRIVER', 'CACHE_DRIVER', 'QUEUE_CONNECTION',
            'BROADCAST_DRIVER', 'SESSION_SECURE_COOKIE', 'SESSION_ENCRYPT',
            'LOG_CHANNEL', 'DEMO_MODE',
        ];

        static $keys = null;

        if ($keys !== null) {
            return $keys;
        }

        $keys = [];
        $example = base_path('.env.example');

        if (is_file($example)) {
            foreach (file($example, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);

                if ($line === '' || $line[0] === '#') {
                    continue;
                }

                $pos = strpos($line, '=');
                if ($pos === false) {
                    continue;
                }

                $name = trim(substr($line, 0, $pos));

                if (preg_match('/^[A-Z][A-Z0-9_]*$/', $name)) {
                    $keys[$name] = true;
                }
            }
        }

        $keys = array_keys($keys);

        // حذف کلیدهای غیرقابل‌نوشتن
        $keys = array_values(array_diff($keys, $neverWritable));

        return $keys;
    }

    /**
     * آیا مقدار برای نوشتن در فایل `.env` ایمن است؟
     *
     * قاعده: هیچ کاراکتر کنترلی (شامل `\r` و `\n`)، هیچ کوتیشنی و
     * هیچ بک‌اسلشی پذیرفته نمی‌شود. دلیل:
     *
     *   • `\r\n` → شکستن خط در فایل پیکربندی و افزودن کلید تازه.
     *   • `"` و `'` → خروج از رشته و ساخت مقدار غیرمنتظره.
     *   • `\`  → در برخی پیاده‌سازی‌های dotenv، تعبیر کاراکتر escape.
     *
     * @param  string $value
     * @return bool
     */
    protected function isSafeEnvValue($value)
    {
        if ($value === '') {
            return true;
        }

        // کاراکتر کنترلی (شامل \r و \n و NUL)
        if (preg_match('/[\x00-\x1F\x7F]/', $value)) {
            return false;
        }

        // کوتیشن‌ها و بک‌اسلش — با strpos بررسی می‌شوند تا از دام
        // escape در رشته‌های PHP و الگوهای باقاعده دور بمانیم.
        if (strpos($value, '"') !== false) {
            return false;
        }

        if (strpos($value, "'") !== false) {
            return false;
        }

        if (strpos($value, '\\') !== false) {
            return false;
        }

        return true;
    }

    /**
     * نوشتن یک کلید در فایل `.env`.
     *
     * برخلاف نسخه پیشین، این متد:
     *   • کلید را با قاعده `^[A-Z][A-Z0-9_]*$` بازبینی می‌کند،
     *   • مقدار را با `isSafeEnvValue()` بازبینی می‌کند،
     *   • خط موجود را «خط‌به‌خط» جای‌گزین می‌کند (نه با
     *     `str_replace` روی کل محتوا که می‌توانست چند جای مختلف را
     *     دست بزند)،
     *   • بازنویسی را «اتمیک» انجام می‌دهد تا فایل نیمه‌نوشته نشود.
     *
     * @param  string $type نام کلید
     * @param  string $val  مقدار
     * @return bool  آیا نوشتن موفق بود؟
     */
    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');

        if (!preg_match('/^[A-Z][A-Z0-9_]*$/', (string) $type)) {
            return false;
        }

        if (!$this->isSafeEnvValue((string) $val)) {
            return false;
        }

        if (!file_exists($path) || !is_writable($path)) {
            return false;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return false;
        }

        $newLine = $type . '="' . trim($val) . '"';

        // ── جای‌گزینی خط موجود ────────────────────────────────────
        $lines = preg_split('/\r\n|\n|\r/', $contents);
        $replaced = false;

        foreach ($lines as $i => $line) {
            if (strpos(ltrim($line), $type . '=') === 0) {
                $lines[$i] = $newLine;
                $replaced = true;
                break;
            }
        }

        if (!$replaced) {
            $lines[] = $newLine;
        }

        $updated = implode("\r\n", $lines);

        return $this->atomicWrite($path, $updated);
    }

    /**
     * نوشتن اتمیک فایل: ابتدا فایل موقت، سپس جای‌گزینی یک‌باره.
     *
     * اگر فرایند در میانه کار قطع شود، فایل اصلی دست‌نخورده می‌ماند.
     *
     * @param  string $path
     * @param  string $contents
     * @return bool
     */
    protected function atomicWrite($path, $contents)
    {
        $tmp = $path . '.tmp-' . bin2hex(random_bytes(6));

        if (file_put_contents($tmp, $contents, LOCK_EX) === false) {
            return false;
        }

        // مجوز فایل اصلی حفظ می‌شود (۰۶۰۰ روی سرور عملیاتی)
        $perms = @fileperms($path);

        if ($perms !== false) {
            @chmod($tmp, $perms & 0777);
        }

        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            return false;
        }

        return true;
    }

    public function seller_verification_form(Request $request)
    {
    	return view('admin.business_settings.seller_verification_form');
    }

    /**
     * Update sell verification form.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seller_verification_form_update(Request $request)
    {
        $form = array();
        $select_types = ['select', 'multi_select', 'radio'];
        $j = 0;
        for ($i=0; $i < count($request->type); $i++) {
            $item['type'] = $request->type[$i];
            $item['label'] = $request->label[$i];
            if(in_array($request->type[$i], $select_types)){
                $item['options'] = json_encode($request['options_'.$request->option[$j]]);
                $j++;
            }
            array_push($form, $item);
        }
        $business_settings = BusinessSetting::where('type', 'verification_form')->first();
        $business_settings->value = json_encode($form);
        if($business_settings->save()){
            flash("فرم تایید با موفقیت بروزرسانی شد")->success();
            return back();
        }
    }

    public function update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if($business_settings!=null){
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
            else{
                $business_settings = new BusinessSetting;
                $business_settings->type = $type;
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
        }
        flash("تنظیمات با موفقیت بروزرسانی شد")->success();
        return back();
    }

    public function updateActivationSettings(Request $request)
    {
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if($business_settings!=null){
            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                Artisan::call('down');
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                Artisan::call('up');
            }
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        else{
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        return '1';
    }

    public function vendor_commission(Request $request)
    {
        $business_settings = BusinessSetting::where('type', 'vendor_commission')->first();
        return view('admin.business_settings.vendor_commission', compact('business_settings'));
    }

    public function vendor_commission_update(Request $request){
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->type = $request->type;
        $business_settings->value = $request->value;
        $business_settings->save();

        flash('کمیسیون فروشنده با موفقیت بروزرسانی شد')->success();
        return back();
    }

    public function shipping_configuration(Request $request){
        return view('admin.shipping_configuration.index');
    }

    public function shipping_configuration_update(Request $request){
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();
        return back();
    }
}


