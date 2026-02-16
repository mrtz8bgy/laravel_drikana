<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('repair_technicians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique()->comment('آیدی کاربر (تکنسین)');
            $table->string('specialty')->nullable()->comment('تخصص');
            $table->integer('experience_years')->nullable()->comment('سال‌های تجربه');
            $table->string('certificate')->nullable()->comment('گواهینامه');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();
            
            // کلید خارجی
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('repair_technicians');
    }
}