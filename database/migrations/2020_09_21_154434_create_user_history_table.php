<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); //who did this action
            $table->unsignedSmallInteger('activity_id'); //what was the activity: report, ban, login, logout
            $table->unsignedBigInteger('bookmark_id')->nullable(); //the link that was visited
            $table->unsignedBigInteger('site_id')->nullable(); //the site that was reported
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('user_activity')->onDelete('cascade');
            $table->foreign('bookmark_id')->references('id')->on('links')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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
        Schema::dropIfExists('user_history');
    }
}
