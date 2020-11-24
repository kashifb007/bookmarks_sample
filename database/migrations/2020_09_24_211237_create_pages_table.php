<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('page_short_name', 20);
            $table->string('title', 255);
            $table->string('description', 512);
            $table->string('image', 512)->nullable();
            $table->unsignedBigInteger('user_id'); //author
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('main_menu')->default(false);
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
        Schema::dropIfExists('pages');
    }
}
