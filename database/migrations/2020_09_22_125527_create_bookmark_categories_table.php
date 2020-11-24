<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bookmark_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedbigInteger('sort_order')->nullable();
            $table->index('bookmark_id');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade');
            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('bookmark_categories');
    }
}
