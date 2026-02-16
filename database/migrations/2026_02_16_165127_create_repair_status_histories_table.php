<?php
// database/migrations/[timestamp]_create_repair_status_histories_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairStatusHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('repair_status_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('repair_order_id');
            $table->enum('status', [
                'pending', 'in_progress', 'completed', 'delivered'
            ]);
            $table->unsignedBigInteger('updated_by'); // کاربری که وضعیت رو تغییر داده
            $table->text('notes')->nullable(); // توضیحات تغییر
            $table->timestamps();
            
            $table->foreign('repair_order_id')->references('id')->on('repair_orders')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['repair_order_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_status_histories');
    }
}