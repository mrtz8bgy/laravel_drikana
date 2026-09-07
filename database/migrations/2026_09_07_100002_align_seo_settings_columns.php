<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlignSeoSettingsColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seo_settings')) {
            return;
        }

        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'keyword')) {
                $table->text('keyword')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'author')) {
                $table->string('author')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'revisit')) {
                $table->unsignedInteger('revisit')->default(7);
            }
            if (!Schema::hasColumn('seo_settings', 'sitemap_link')) {
                $table->string('sitemap_link')->nullable();
            }
            if (!Schema::hasColumn('seo_settings', 'description')) {
                $table->text('description')->nullable();
            }
        });

        if (\DB::table('seo_settings')->count() === 0) {
            \DB::table('seo_settings')->insert([
                'meta_title' => config('app.name'),
                'meta_description' => config('app.name'),
                'description' => config('app.name'),
                'keyword' => '',
                'author' => config('app.name'),
                'revisit' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // Keep the core SEO table intact when rolling back compatibility columns.
    }
}
