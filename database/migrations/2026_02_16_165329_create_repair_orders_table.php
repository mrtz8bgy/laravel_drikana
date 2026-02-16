<?php
// database/migrations/[timestamp]_create_repair_orders_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number', 50)->unique(); // شماره سفارش یکتا
            $table->unsignedBigInteger('user_id'); // کاربر ثبت‌کننده
            $table->unsignedBigInteger('jewelry_id'); // قطعه
            $table->enum('repair_type', ['cleaning', 'restoration', 'resizing', 'other'])->comment('نوع تعمیر');
            $table->text('description')->nullable(); // توضیحات مشکل
            $table->date('request_date'); // تاریخ درخواست
            $table->date('scheduled_date')->nullable(); // تاریخ برنامه‌ریزی
            $table->enum('location', ['customer', 'workshop'])->default('workshop'); // مکان تعمیر
            $table->enum('status', [
                'pending',      // ثبت شده
                'in_progress',  // در حال انجام
                'completed',    // تکمیل شده
                'delivered'     // تحویل داده شد
            ])->default('pending');
            $table->unsignedBigInteger('assigned_to')->nullable(); // تکنسین اصلی
            $table->date('completion_date')->nullable(); // تاریخ تکمیل
            $table->date('delivery_date')->nullable(); // تاریخ تحویل
            $table->decimal('estimated_cost', 10, 2)->nullable(); // هزینه تخمینی
            $table->decimal('final_cost', 10, 2)->nullable(); // هزینه نهایی
            $table->string('images')->nullable(); // عکس‌ها (JSON)
            $table->text('notes')->nullable(); // یادداشت‌های داخلی
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jewelry_id')->references('id')->on('jewelry_certificates')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            
            $table->index('order_number');
            $table->index('status');
            $table->index('request_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_orders');
    }
}