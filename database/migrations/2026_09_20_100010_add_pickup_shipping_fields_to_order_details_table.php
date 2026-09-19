<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPickupShippingFieldsToOrderDetailsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_details')) {
            return;
        }

        if (!Schema::hasColumn('order_details', 'shipping_type')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->string('shipping_type', 255)->nullable()->after('delivery_status');
            });
        }

        if (!Schema::hasColumn('order_details', 'pickup_point_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->unsignedInteger('pickup_point_id')->nullable()->after('shipping_type');
                $table->index('pickup_point_id');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('order_details')) {
            return;
        }

        $columns = [];
        if (Schema::hasColumn('order_details', 'pickup_point_id')) {
            $columns[] = 'pickup_point_id';
        }
        if (Schema::hasColumn('order_details', 'shipping_type')) {
            $columns[] = 'shipping_type';
        }

        if (!empty($columns)) {
            Schema::table('order_details', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
