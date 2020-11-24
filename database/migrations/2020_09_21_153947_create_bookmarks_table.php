<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('link_id');
            $table->string('title', 200);
            $table->string('description', 2000)->nullable();
            $table->unsignedbigInteger('sort_order')->nullable();
            $table->enum('bookmark_status', ['live', 'trash', 'deleted'])->default('live');
            $table->timestamps();
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->unique('link_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            //$table->dropForeign('bookmarks_user_id_foreign');
            //$table->dropIndex('bookmarks_user_id_index');
            //$table->dropUnique('bookmarks_user_id_index');
            //$table->dropColumn('user_id');
            //$table->dropForeign('bookmarks_link_id_foreign');
            //$table->dropIndex('bookmarks_link_id_index');
            //$table->dropColumn('link_id');
            });
        Schema::dropIfExists('bookmarks');
    }
}
