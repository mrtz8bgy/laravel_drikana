<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DrikanaCoreInstallSeeder extends Seeder
{
    public function run()
    {
        // ══════════════════════════════════════════════
        // 1) Admin user
        // ══════════════════════════════════════════════
        if (DB::table('users')->where('email', 'admin@drikana.com')->doesntExist()) {
            DB::table('users')->insert([
                'name'              => 'مدیریت دریکانا',
                'email'             => 'admin@drikana.com',
                'password'          => Hash::make('123456'),
                'user_type'         => 'admin',
                'role'              => 'admin',
                'email_verified_at' => now(),
                'phone'             => '09120000000',
                'city'              => 'تهران',
                'address'           => 'تهران، بازار بزرگ طلا و جواهر',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
        $adminId = DB::table('users')->where('email', 'admin@drikana.com')->value('id');

        // ══════════════════════════════════════════════
        // 2) Default currency (Iranian Toman)
        // ══════════════════════════════════════════════
        if (DB::table('currencies')->count() === 0) {
            DB::table('currencies')->insert([
                ['name' => 'تومان', 'symbol' => 'تومان', 'code' => 'IRT', 'exchange_rate' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'ریال',  'symbol' => 'ریال',  'code' => 'IRR', 'exchange_rate' => 10, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'US Dollar', 'symbol' => '$', 'code' => 'USD', 'exchange_rate' => 0.00002, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // ══════════════════════════════════════════════
        // 3) Language (Persian + RTL)
        // ══════════════════════════════════════════════
        if (DB::table('languages')->count() === 0) {
            DB::table('languages')->insert([
                ['name' => 'Persian (فارسی)', 'code' => 'fa', 'app_lang_code' => 'fa', 'status' => 1, 'is_rtl' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'English',          'code' => 'en', 'app_lang_code' => 'en', 'status' => 0, 'is_rtl' => 0, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // ══════════════════════════════════════════════
        // 4) General settings
        // ══════════════════════════════════════════════
        if (DB::table('general_settings')->count() === 0) {
            DB::table('general_settings')->insert([
                'site_name'  => 'دریکانا | بازار آنلاین طلا و جواهر',
                'address'    => 'تهران، بازار بزرگ طلا، پلاک ۱',
                'phone'      => '021-88776655',
                'email'      => 'info@drikana.com',
                'facebook'   => '#',
                'instagram'  => '#',
                'twitter'    => '#',
                'youtube'    => '#',
                'logo'       => 'frontend/images/logo/drikana-logo.svg',
                'favicon'    => 'frontend/images/logo/favicon.svg',
                'admin_logo' => 'frontend/images/logo/drikana-logo.svg',
                'meta_title'       => 'دریکانا | مرجع تخصصی خرید، فروش و استعلام طلا، جواهر و ساعت لوکس',
                'meta_description' => 'دریکانا، بازار آنلاین تخصصی طلا، جواهر، سنگ‌های قیمتی، ساعت‌های لوکس و سکه. استعلام اصالت شناسنامه، رهگیری و گزارش سرقت جواهرات.',
                'system_default_currency' => 'IRT',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ══════════════════════════════════════════════
        // 5) Business settings (with vendor system OFF by default — avoids seller errors when no sellers exist)
        // ══════════════════════════════════════════════
        $settings = [
            'vendor_system_activation'          => '0', // ⚠️ غیرفعال تا وقتی فروشنده‌ای اضافه شود (برای جلوگیری از خطا)
            'digital_product'                   => '0',
            'wallet_system'                     => '1',
            'coupon_system'                     => '1',
            'pickup_point'                      => '0',
            'refund_request'                    => '1',
            'otp_system'                        => '0',
            'guest_checkout_activation'         => '1',
            'conversation_system'               => '0',
            'color_option'                      => '1',
            'attribute_option'                  => '1',
            'seller_verification'               => '0',
            'email_verification'                => '0',
            'facebook_pixel'                    => '0',
            'google_analytics'                  => '0',
            'facebook_chat'                     => '0',
            'twitter_pixel'                     => '0',
            'google_recaptcha'                  => '0',
            'mail_verify'                       => '0',
            'sslcommerz_payment'                => '0',
            'stripe_payment'                    => '0',
            'paypal_payment'                    => '0',
            'razorpay_payment'                  => '0',
            'instamojo_payment'                 => '0',
            'cash_payment'                      => '1',
            'bank_payment'                      => '0',
            'wallet_payment'                    => '0',
            'sslcommerz_sandbox'                => '1',
            'paypal_sandbox'                    => '1',
            'stripe_sandbox'                    => '1',
            'razorpay_sandbox'                  => '1',
            'instamojo_sandbox'                 => '1',
            'top_categories'                    => '4',
            'best_selling_products'            => '12',
            'homepage_slider_count'             => '3',
            'menu_categories'                   => '10',
            'page_builder'                      => '1',
            'tax'                               => '0',
            'shipping_cost'                     => '0',
            'shipping_type'                     => 'flat_rate',
            'shipping_delivery_type'            => 'home_delivery',
            'force_ssl'                         => '0',
            'vendor_commission'                 => '10',
            'website_color'                     => '#d4af37',
            'base_currency'                     => 'IRT',
            'current_version'                   => '2.0',
        ];
        foreach ($settings as $k => $v) {
            DB::table('business_settings')->updateOrInsert(['type' => $k], ['value' => $v, 'created_at' => now(), 'updated_at' => now()]);
        }

        // ══════════════════════════════════════════════
        // 6) SEO defaults
        // ══════════════════════════════════════════════
        if (DB::table('seo_settings')->count() === 0) {
            DB::table('seo_settings')->insert([
                'meta_title' => 'دریکانا | بازار تخصصی طلا، جواهر و ساعت لوکس',
                'meta_description' => 'خرید و فروش امن طلا، جواهر، الماس، ساعت‌های لوکس، سکه و نقره با استعلام اصالت آنلاین.',
                'meta_keywords' => 'طلا،جواهر،الماس،ساعت لوکس،سکه،نقره،انگشتر عقد،گردنبند،دریکانا',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ══════════════════════════════════════════════
        // 7) Admin shop (so that sellers query returns a default record if vendor system activated)
        // ══════════════════════════════════════════════
        if (DB::table('shops')->count() === 0) {
            DB::table('shops')->insert([
                'user_id'   => $adminId,
                'name'      => 'گالری رسمی دریکانا',
                'logo'      => 'frontend/images/logo/drikana-logo.svg',
                'slug'      => 'drikana-official',
                'address'   => 'تهران، بازار بزرگ طلا، پلاک ۱',
                'meta_title'=> 'گالری رسمی دریکانا',
                'meta_description' => 'گالری رسمی و شعبه مرکزی دریکانا، ارائه دهنده طلا، جواهر و ساعت‌های لوکس اصل.',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // A seller record for admin (optional — only needed if vendor system turned on)
        if (DB::table('sellers')->count() === 0) {
            DB::table('sellers')->insert([
                'user_id'               => $adminId,
                'verification_status'   => 1,
                'cash_on_delivery_status' => 1,
                'admin_to_pay'          => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ══════════════════════════════════════════════
        // 8) Default home slider (uses logo as placeholder)
        // ══════════════════════════════════════════════
        if (DB::table('sliders')->count() === 0) {
            DB::table('sliders')->insert([
                [
                    'position' => 0,
                    'photo' => 'frontend/images/drikana/hero-bg.svg',
                    'link'  => '#categories',
                    'published' => 1,
                    'heading_text' => 'مجموعه‌ای بی‌نظیر از طلا و جواهرات اصل',
                    'heading_text_color' => '#ffffff',
                    'subheading_text' => 'استعلام اصالت آنلاین، رهگیری هوشمند و خرید امن از بازار تخصصی دریکانا',
                    'subheading_text_color' => '#d4af37',
                    'button_text' => 'مشاهده محصولات',
                    'button_text_color' => '#07091a',
                    'button_background_color' => '#d4af37',
                    'text_position' => 'right',
                    'created_at' => now(), 'updated_at' => now(),
                ],
                [
                    'position' => 1,
                    'photo' => 'frontend/images/drikana/hero-bg.svg',
                    'link'  => route('frontend.jewelry.verify', [], false),
                    'published' => 1,
                    'heading_text' => 'استعلام اصالت شناسنامه طلا',
                    'heading_text_color' => '#ffffff',
                    'subheading_text' => 'با وارد کردن کد شناسنامه، اصالت قطعه طلا یا جواهر را بررسی کنید',
                    'subheading_text_color' => '#d4af37',
                    'button_text' => 'استعلام آنلاین',
                    'button_text_color' => '#07091a',
                    'button_background_color' => '#d4af37',
                    'text_position' => 'right',
                    'created_at' => now(), 'updated_at' => now(),
                ],
                [
                    'position' => 2,
                    'photo' => 'frontend/images/drikana/hero-bg.svg',
                    'link'  => route('frontend.jewelry.report_stolen', [], false),
                    'published' => 1,
                    'heading_text' => 'گزارش سرقت یا مفقودی جواهر',
                    'heading_text_color' => '#ffffff',
                    'subheading_text' => 'با ثبت جواهر مفقودی خود به شبکه سراسری رهگیری دریکانا بپیوندید',
                    'subheading_text_color' => '#d4af37',
                    'button_text' => 'ثبت گزارش',
                    'button_text_color' => '#07091a',
                    'button_background_color' => '#d4af37',
                    'text_position' => 'right',
                    'created_at' => now(), 'updated_at' => now(),
                ],
            ]);
        }

        // ══════════════════════════════════════════════
        // 9) Footer / link defaults
        // ══════════════════════════════════════════════
        if (DB::table('links')->count() === 0) {
            DB::table('links')->insert([
                ['name' => 'درباره دریکانا', 'url' => '/pages/about',     'position' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'قوانین استفاده', 'url' => '/pages/terms',     'position' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'حریم خصوصی',     'url' => '/pages/privacy',   'position' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'تماس با ما',      'url' => '/pages/contact',   'position' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'استعلام اصالت',  'url' => '/jewelry/verify',  'position' => 5, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'ثبت گزارش سرقت', 'url' => '/jewelry/report-stolen', 'position' => 6, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // ══════════════════════════════════════════════
        // 10) Policies
        // ══════════════════════════════════════════════
        if (DB::table('policies')->count() === 0) {
            DB::table('policies')->insert([
                ['name' => 'return_policy',   'content' => 'در صورت مغایرت با شناسنامه، بازگشت وجه تا ۷ روز.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'support_policy',  'content' => 'پشتیبانی ۲۴ ساعته دریکانا.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'privacy_policy',  'content' => 'اطلاعات کاربران شما نزد ما محفوظ است.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'terms_conditions','content' => 'قوانین کلی استفاده از بازار دریکانا.', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'seller_policy',   'content' => 'فروشندگان منتخب پس از احراز هویت.', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
