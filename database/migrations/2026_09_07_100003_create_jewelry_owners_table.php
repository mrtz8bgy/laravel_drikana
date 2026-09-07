<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJewelryOwnersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('jewelry_owners')) {
            return;
        }

        Schema::create('jewelry_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('full_name');
            $table->string('national_id', 10)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->unique(['user_id', 'national_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jewelry_owners');
    }
}
