<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEcommerceFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country', 50)->nullable();
            }
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 255)->nullable();
            }
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 20)->default('customer');
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->double('balance', 20, 2)->default(0.00);
            }
            if (!Schema::hasColumn('users', 'banned')) {
                $table->tinyInteger('banned')->default(0);
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->integer('referred_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address','city','postal_code','phone','country',
                'provider_id','verification_code','phone_verified_at',
                'avatar','user_type','balance','banned','referred_by'
            ]);
        });
    }
}
