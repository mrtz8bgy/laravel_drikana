<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerWithdrawRequestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('seller_withdraw_requests')) {
            Schema::create('seller_withdraw_requests', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->double('amount', 8, 2)->nullable();
                $table->text('message')->nullable();
                $table->tinyInteger('status')->nullable();
                $table->tinyInteger('viewed')->nullable();
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('seller_withdraw_requests');
    }
}
