<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusInLinkStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_status', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status');
        });

        Schema::table('user_status', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status');
        });

        Schema::table('report_type', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_status', function (Blueprint $table) {
            //
        });
    }
}
