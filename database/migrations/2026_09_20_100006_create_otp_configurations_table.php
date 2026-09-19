<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOtpConfigurationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('otp_configurations')) {
            Schema::create('otp_configurations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // These rows are read directly by the OTP views/helpers. Seed them for
        // existing installations as well as fresh databases.
        $types = [
            'nexmo',
            'twillo',
            'ssl_wireless',
            'fast2sms',
            'melipayamak',
            'farazsms',
            'otp_for_order',
            'otp_for_delivery_status',
            'otp_for_paid_status',
        ];

        foreach ($types as $type) {
            if (!DB::table('otp_configurations')->where('type', $type)->exists()) {
                DB::table('otp_configurations')->insert([
                    'type' => $type,
                    'value' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('otp_configurations');
    }
}
