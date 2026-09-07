<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrikanaSampleProductsSeeder extends Seeder
{
    /**
     * ۶ محصول نمونه طلا و جواهر برای نمایش در صفحه اصلی
     */
    public function run()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('products')) {
            $this->command->warn('❌ جدول products وجود ندارد — ابتدا migration ها را اجرا کنید');
            return;
        }

        $admin = DB::table('users')->where('email','admin@drikana.com')->first();
        $adminId = $admin ? $admin->id : 1;

        $catRings    = DB::table('categories')->where('slug','rings')->first();
        $catNeck     = DB::table('categories')->where('slug','necklaces')->first();
        $catWatch    = DB::table('categories')->where('slug','luxury-watches')->first();
        $catDia      = DB::table('categories')->where('slug','diamonds-gemstones')->first();
        $catBrace    = DB::table('categories')->where('slug','bracelets-earrings')->first();
        $catSet      = DB::table('categories')->where('slug','jewelry-sets')->first();

        $brandRolex  = DB::table('brands')->where('slug','rolex')->first();
        $brandCartier= DB::table('brands')->where('slug','cartier')->first();
        $brandTiff   = DB::table('brands')->where('slug','tiffany-co')->first();
        $brandDrikana= DB::table('brands')->where('slug','drikana-signature')->first();

        $products = [
            [
                'name' => 'انگشتر الماس نامزدی دریکانا سری رؤیا',
                'category_id' => $catRings->id ?? null,
                'brand_id' => $brandDrikana->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/ring.svg',
                'photos' => 'frontend/images/drikana/ring.svg',
                'unit_price' => 285000000,
                'purchase_price' => 260000000,
                'discount' => 5, 'discount_type' => 'percent',
                'description' => '<p>انگشتر نامزدی طلای ۱۸ عیار با الماس ۰٫۷ قیراط برلیان تراش. همراه با شناسنامه معتبر GIA.</p>',
                'tags' => 'انگشتر,الماس,نامزدی,طلا ۱۸',
                'slug' => 'diamond-engagement-ring-roy',
                'featured' => 1, 'todays_deal' => 0, 'published' => 1, 'refundable' => 1, 'variant_product' => 0,
                'current_stock' => 3, 'rating' => 5.0, 'num_of_sale' => 4,
            ],
            [
                'name' => 'گردنبند مروارید طبیعی کواترو',
                'category_id' => $catNeck->id ?? null,
                'brand_id' => $brandTiff->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/necklace.svg',
                'photos' => 'frontend/images/drikana/necklace.svg',
                'unit_price' => 52000000,
                'purchase_price' => 48000000,
                'discount' => 0, 'discount_type' => 'amount',
                'description' => '<p>گردنبند مروارید طبیعی آب شیرین، رشته ۴۵ سانتی، قفل طلای ۱۸ عیار.</p>',
                'tags' => 'گردنبند,مروارید,طلا',
                'slug' => 'pearl-necklace-cuatro',
                'featured' => 1, 'todays_deal' => 1, 'published' => 1, 'refundable' => 1, 'variant_product' => 0,
                'current_stock' => 5, 'rating' => 4.9, 'num_of_sale' => 7,
            ],
            [
                'name' => 'ساعت مردانه Rolex Submariner Date اصل',
                'category_id' => $catWatch->id ?? null,
                'brand_id' => $brandRolex->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/watch.svg',
                'photos' => 'frontend/images/drikana/watch.svg',
                'unit_price' => 4200000000,
                'purchase_price' => 3900000000,
                'discount' => 3, 'discount_type' => 'percent',
                'description' => '<p>ساعت اتوماتیک مردانه رولکس سابمارینر دیت، استیل ضدخش، مقاوم در برابر آب تا عمق ۳۰۰ متر. با جعبه و کارت گارانتی اصلی.</p>',
                'tags' => 'ساعت,رولکس,لوکس,مردانه',
                'slug' => 'rolex-submariner-date',
                'featured' => 1, 'todays_deal' => 0, 'published' => 1, 'refundable' => 0, 'variant_product' => 0,
                'current_stock' => 1, 'rating' => 5.0, 'num_of_sale' => 2,
            ],
            [
                'name' => 'دستبند زمرد نقره با طلای سفید',
                'category_id' => $catBrace->id ?? null,
                'brand_id' => $brandDrikana->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/diamond.svg',
                'photos' => 'frontend/images/drikana/diamond.svg',
                'unit_price' => 145000000,
                'purchase_price' => 130000000,
                'discount' => 0, 'discount_type' => 'amount',
                'description' => '<p>دستبند زنانه با زمرد کلمبیا و طلای سفید ۱۸ عیار، با شناسنامه اتحادیه طلا.</p>',
                'tags' => 'دستبند,زمرد,طلا سفید',
                'slug' => 'emerald-bracelet-silver-gold',
                'featured' => 1, 'todays_deal' => 1, 'published' => 1, 'refundable' => 1, 'variant_product' => 0,
                'current_stock' => 2, 'rating' => 4.8, 'num_of_sale' => 3,
            ],
            [
                'name' => 'نیم‌ست یاقوت سرخ آتوسا',
                'category_id' => $catSet->id ?? null,
                'brand_id' => $brandDrikana->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/crown.svg',
                'photos' => 'frontend/images/drikana/crown.svg',
                'unit_price' => 195000000,
                'purchase_price' => 180000000,
                'discount' => 7, 'discount_type' => 'percent',
                'description' => '<p>نیم‌ست یاقوت سرخ برمه با طلای ۱۸ عیار شامل گردنبند، گوشواره و انگشتر.</p>',
                'tags' => 'نیم ست,یاقوت,زرشکی,عروس',
                'slug' => 'ruby-half-set-atousa',
                'featured' => 1, 'todays_deal' => 0, 'published' => 1, 'refundable' => 1, 'variant_product' => 0,
                'current_stock' => 2, 'rating' => 5.0, 'num_of_sale' => 1,
            ],
            [
                'name' => 'حلقه ازدواج Cartier Love اصل',
                'category_id' => $catRings->id ?? null,
                'brand_id' => $brandCartier->id ?? null,
                'thumbnail_img' => 'frontend/images/drikana/ring.svg',
                'photos' => 'frontend/images/drikana/ring.svg',
                'unit_price' => 98000000,
                'purchase_price' => 90000000,
                'discount' => 0, 'discount_type' => 'amount',
                'description' => '<p>حلقه ازدواج کارتیه لاو طلای ۱۸ عیار، با حک لوگو، اصل با جعبه و برگ ضمانت.</p>',
                'tags' => 'حلقه,ازدواج,کارتیه,طلا ۱۸',
                'slug' => 'cartier-love-wedding-ring',
                'featured' => 1, 'todays_deal' => 1, 'published' => 1, 'refundable' => 0, 'variant_product' => 0,
                'current_stock' => 4, 'rating' => 4.9, 'num_of_sale' => 5,
            ],
        ];

        $now = now();
        foreach ($products as $p) {
            if (DB::table('products')->where('slug',$p['slug'])->exists()) continue;
            DB::table('products')->insert(array_merge([
                'added_by' => 'admin',
                'user_id' => $adminId,
                'unit' => 'عدد',
                'weight' => 0,
                'tax' => 0, 'tax_type' => 'amount',
                'shipping_type' => 'flat_rate', 'shipping_cost' => 0,
                'meta_title' => $p['name'],
                'meta_description' => strip_tags($p['description']),
                'created_at' => $now, 'updated_at' => $now,
            ], $p));
        }

        // Add some products to home_categories
        if (\Illuminate\Support\Facades\Schema::hasTable('home_categories') && DB::table('home_categories')->count() === 0) {
            $i = 0;
            foreach ([$catRings, $catNeck, $catWatch, $catDia] as $c) {
                if ($c) {
                    DB::table('home_categories')->insert(['category_id'=>$c->id,'position'=>$i++,'subsubcategories'=>null,'created_at'=>$now,'updated_at'=>$now]);
                }
            }
        }
    }
}
