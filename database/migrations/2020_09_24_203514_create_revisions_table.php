<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisionsTable extends Migration
{
    /**
     * Store the changes everyone makes with their own data, user details, bookmark details and category details
     * Historical data
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); //author
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->string('old_text');
            $table->unsignedBigInteger('bookmark_id')->nullable();
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade');
            $table->index('bookmark_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index('category_id');
            $table->unsignedBigInteger('site_id')->nullable();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->index('site_id');
            $table->enum('type_of_change', ['user_info', 'bookmark', 'category', 'site'])->default('bookmark');
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
        Schema::dropIfExists('revisions');
    }
}
