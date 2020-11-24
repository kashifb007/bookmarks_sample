<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDescriptionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('description', 2000);
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->string('title', 255);
        });

        Schema::table('links', function (Blueprint $table) {
            $table->string('meta_description', 512);
            $table->string('url');
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->string('title', 512);
            $table->string('description', 512);
            $table->string('base_uri', 512);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
