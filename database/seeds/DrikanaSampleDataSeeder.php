<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\JewelryOwner;
use App\Models\JewelryCertificate;
use App\Models\MissingReport;

/**
 * Seeder نمونه برای دریکانا — طلا و جواهر
 *
 * توجه: قبل از اجرا، بک‌آپ از دیتابیس بگیرید.
 * اجرا: php artisan db:seed --class=DrikanaSampleDataSeeder
 */
class DrikanaSampleDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 در حال درج داده‌های نمونه دریکانا...');

        // ===== 1) دسته‌بندی‌های طلا و جواهر =====
        $categories = [
            ['name' => 'حلقه ازدواج و انگشتر', 'icon' => 'frontend/images/drikana/ring.svg', 'meta_title' => 'خرید انگشتر طلا و حلقه ازدواج'],
            ['name' => 'گردنبند و آویز',     'icon' => 'frontend/images/drikana/necklace.svg', 'meta_title' => 'گردنبند طلا و جواهر'],
            ['name' => 'ساعت مچی لوکس',     'icon' => 'frontend/images/drikana/watch.svg', 'meta_title' => 'ساعت طلا و لوکس'],
            ['name' => 'الماس و سنگ قیمتی', 'icon' => 'frontend/images/drikana/diamond.svg', 'meta_title' => 'الماس و سنگ قیمتی'],
            ['name' => 'گوشواره و دستبند',  'icon' => 'frontend/images/drikana/necklace.svg', 'meta_title' => 'گوشواره و دستبند'],
            ['name' => 'سکه و شمش طلا',     'icon' => 'frontend/images/drikana/coin.svg', 'meta_title' => 'سکه و شمش طلا'],
            ['name' => 'نیم‌ست و ست کامل',   'icon' => 'frontend/images/drikana/crown.svg', 'meta_title' => 'ست طلا و جواهر'],
            ['name' => 'عتیقه‌جات و کلکسیون', 'icon' => 'frontend/images/drikana/antique.svg', 'meta_title' => 'اشیاء عتیقه'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                array_merge($cat, [
                    'slug' => Str::slug($cat['name']),
                    'top' => 1,
                    'featured' => 1,
                    'commission_rate' => 0,
                    'digital' => 0,
                    'published' => 1,
                    'level' => 0,
                ])
            );
        }
        $this->command->info('✅ دسته‌بندی‌ها ثبت شدند.');

        // ===== 2) برندها =====
        $brands = ['Rolex', 'Cartier', 'Tiffany & Co', 'Bvlgari', 'Drikana Signature', 'Patek Philippe', 'Omega', 'Van Cleef & Arpels'];
        foreach ($brands as $b) {
            Brand::updateOrCreate(['name' => $b], [
                'slug' => Str::slug($b),
                'meta_title' => $b,
                'top' => 1,
                'verified' => 1,
                'published' => 1,
            ]);
        }
        $this->command->info('✅ برندها ثبت شدند.');

        // ===== 3) مالکان نمونه جواهرات =====
        $owners = [
            ['full_name' => 'علی رضایی',    'phone' => '09121234567', 'national_id' => '0012345678', 'address' => 'تهران، پاسداران'],
            ['full_name' => 'مریم احمدی',   'phone' => '09129876543', 'national_id' => '0076543210', 'address' => 'تهران، سعادت‌آباد'],
            ['full_name' => 'حسین مرادی',   'phone' => '09351112233', 'national_id' => '0098765432', 'address' => 'اصفهان، خیابان چهارباغ'],
            ['full_name' => 'زهرا کریمی',   'phone' => '09213334455', 'national_id' => '0056781234', 'address' => 'شیراز، زند'],
        ];
        foreach ($owners as $o) {
            JewelryOwner::updateOrCreate(
                ['national_id' => $o['national_id']],
                array_merge($o, ['user_id' => null, 'created_at' => now(), 'updated_at' => now()])
            );
        }
        $this->command->info('✅ مالکان نمونه ثبت شدند.');

        // ===== 4) شناسنامه‌های نمونه =====
        $certificates = [
            [
                'serial_number' => 'DRK-A-10001', 'product_name' => 'انگشتر الماس نامزدی',
                'metal_type' => 'طلا', 'purity' => 750, 'weight' => 6.4,
                'stone_type' => 'الماس برلیان 1.2 قیراط',
                'issued_at' => '2024-03-15', 'description' => 'انگشتر نامزدی با پایه طلای سفید ۱۸ عیار و الماس برلیان درجه VVS1',
                'owner_national' => '0012345678',
            ],
            [
                'serial_number' => 'DRK-A-10002', 'product_name' => 'گردنبند مروارید',
                'metal_type' => 'طلا', 'purity' => 750, 'weight' => 12.8,
                'stone_type' => 'مروارید طبیعی',
                'issued_at' => '2024-05-20', 'description' => 'گردنبند طلای ۱۸ عیار با مروارید خاوری و قفل فشاری',
                'owner_national' => '0076543210',
            ],
            [
                'serial_number' => 'DRK-A-10003', 'product_name' => 'ساعت رولکس سابمارینر',
                'metal_type' => 'سایر', 'purity' => null, 'weight' => 155,
                'stone_type' => '—',
                'issued_at' => '2023-11-10', 'description' => 'ساعت اتوماتیک مردانه با بدنه استیل و طلای ۱۸ عیار، اصل سوئیس',
                'owner_national' => '0098765432',
            ],
            [
                'serial_number' => 'DRK-A-10004', 'product_name' => 'دستبند زمرد اصل',
                'metal_type' => 'طلا', 'purity' => 750, 'weight' => 18.3,
                'stone_type' => 'زمرد کلمبیا ۴.۵ قیراط',
                'issued_at' => '2024-01-08', 'description' => 'دستبند طلای ۱۸ عیار با زمرد اصل کلمبیا و الماس تراش برلیان',
                'owner_national' => '0056781234',
            ],
            [
                'serial_number' => 'DRK-A-10005', 'product_name' => 'نیم‌ست یاقوت سرخ',
                'metal_type' => 'طلا', 'purity' => 750, 'weight' => 24.6,
                'stone_type' => 'یاقوت برمه ۳ قیراط',
                'issued_at' => '2024-07-22', 'description' => 'نیم‌ست شامل گوشواره و آویز، با یاقوت سرخ اصیل برمه و طلای ۱۸ عیار',
                'owner_national' => '0012345678',
            ],
        ];
        foreach ($certificates as $c) {
            $owner = JewelryOwner::where('national_id', $c['owner_national'])->first();
            unset($c['owner_national']);
            JewelryCertificate::updateOrCreate(
                ['serial_number' => $c['serial_number']],
                array_merge($c, [
                    'owner_id' => $owner ? $owner->id : null,
                    'is_verified' => 1,
                    'created_at' => now(), 'updated_at' => now(),
                ])
            );
        }
        $this->command->info('✅ شناسنامه‌های نمونه ثبت شدند.');

        $this->command->info("\n🎉 داده‌های نمونه با موفقیت درج شدند!");
    }
}
