<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToReportTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedMediumInteger('sort_order')->nullable();
        });

        Schema::table('report_type', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->nullable();
        });

        Schema::table('link_status', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('report_type', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('link_status', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
