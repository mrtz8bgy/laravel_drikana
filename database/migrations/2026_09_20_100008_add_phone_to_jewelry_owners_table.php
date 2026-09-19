<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToJewelryOwnersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('jewelry_owners') && !Schema::hasColumn('jewelry_owners', 'phone')) {
            Schema::table('jewelry_owners', function (Blueprint $table) {
                $table->string('phone', 15)->nullable()->after('national_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('jewelry_owners') && Schema::hasColumn('jewelry_owners', 'phone')) {
            Schema::table('jewelry_owners', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
    }
}
