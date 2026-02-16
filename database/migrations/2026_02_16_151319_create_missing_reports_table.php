<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissingReportsTable extends Migration
{
    public function up()
    {
        Schema::create('missing_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jewelry_id');  // این به JewelryCertificate اشاره می‌کنه
            $table->enum('type', ['theft', 'lost'])->comment('سرقت یا مفقودی');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'found'])->default('pending');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jewelry_id')->references('id')->on('jewelry_certificates')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('missing_reports');
    }
}