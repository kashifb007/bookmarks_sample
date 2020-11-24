<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToBookmarkCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookmark_category', function (Blueprint $table) {
            $table->unique(['bookmark_id', 'category_id'])->change();
        });

        Schema::table('bookmark_tag', function (Blueprint $table) {
            $table->unique(['bookmark_id', 'tag_id'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmark_category', function (Blueprint $table) {
            //
        });
    }
}
