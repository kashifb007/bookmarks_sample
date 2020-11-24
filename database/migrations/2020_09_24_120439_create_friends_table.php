<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // person who made the friend request
            $table->unsignedBigInteger('friend_user_id'); // person you wanted to be friends with
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('friend_user_id');
            //blocked only by the receiver
            $table->enum('status', ['requested', 'accepted', 'cancelled', 'blocked'])->default('requested');
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
        Schema::dropIfExists('friends');
    }
}
