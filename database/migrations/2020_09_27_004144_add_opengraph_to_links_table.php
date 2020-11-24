<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOpengraphToLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->string('og_title', 255)->nullable();
            $table->string('og_description', 512)->nullable();
            $table->string('og_image', 1024)->nullable();
            $table->string('og_url', 512)->nullable();
            $table->string('og_type', 128)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('og_image');
            $table->dropColumn('og_url');
            $table->dropColumn('og_type');
        });
    }
}
