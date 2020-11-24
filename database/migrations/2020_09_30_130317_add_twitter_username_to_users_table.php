<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwitterUsernameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('twitter_username', 50)->nullable();
            $table->string('twitter_profile_image', 500)->nullable();
            $table->string('facebook_username', 50)->nullable();
            $table->string('facebook_profile_image', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('twitter_username');
            $table->dropColumn('twitter_profile_image');
            $table->dropColumn('facebook_username');
            $table->dropColumn('facebook_profile_image');
        });
    }
}
