<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRepairStatusHistoriesForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('repair_status_histories', function (Blueprint $table) {
            // ابتدا اطمینان از InnoDB بودن جدول
            DB::statement('ALTER TABLE repair_orders ENGINE = InnoDB');
            DB::statement('ALTER TABLE users ENGINE = InnoDB');
            
            // اضافه کردن کلیدهای خارجی
            $table->foreign('repair_order_id')
                  ->references('id')
                  ->on('repair_orders')
                  ->onDelete('cascade');
                  
            $table->foreign('updated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('repair_status_histories', function (Blueprint $table) {
            $table->dropForeign(['repair_order_id']);
            $table->dropForeign(['updated_by']);
        });
    }
}