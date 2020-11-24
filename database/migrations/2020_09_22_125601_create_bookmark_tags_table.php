<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bookmark_id');
            $table->unsignedBigInteger('tag_id');
            $table->index('bookmark_id');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade');
            $table->index('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_tags');
    }
}
