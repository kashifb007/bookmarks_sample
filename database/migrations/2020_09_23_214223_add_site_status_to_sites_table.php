<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteStatusToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->unsignedSmallInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('link_status')->onDelete('cascade');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->unsignedSmallInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('link_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}
