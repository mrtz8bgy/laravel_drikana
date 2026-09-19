<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupPointsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pickup_points')) {
            Schema::create('pickup_points', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('staff_id');
                $table->string('name');
                $table->text('address');
                $table->string('phone', 15);
                $table->tinyInteger('pick_up_status')->nullable();
                $table->tinyInteger('cash_on_pickup_status')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pickup_points');
    }
}
