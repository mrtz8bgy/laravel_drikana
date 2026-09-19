<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentToMissingReportsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('missing_reports') && !Schema::hasColumn('missing_reports', 'police_report_path')) {
            Schema::table('missing_reports', function (Blueprint $table) {
                $table->string('police_report_path')->nullable()->after('description');
                $table->string('police_report_name')->nullable()->after('police_report_path');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('missing_reports') && Schema::hasColumn('missing_reports', 'police_report_path')) {
            Schema::table('missing_reports', function (Blueprint $table) {
                $table->dropColumn(['police_report_path', 'police_report_name']);
            });
        }
    }
}
