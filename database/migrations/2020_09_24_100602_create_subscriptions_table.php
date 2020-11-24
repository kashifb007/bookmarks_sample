<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 300);
            $table->unsignedBigInteger('user_id'); //who did this action
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_ended')->nullable();
            $table->unsignedSmallInteger('tier_id')->default(1);
            $table->foreign('tier_id')->references('id')->on('tiers')->onDelete('cascade');
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
        Schema::dropIfExists('subscriptions');
    }
}
