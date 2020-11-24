<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * logged in
         * logged out
         * added link
         * visited link
         * updated link
         * deleted link
         * shared link
         * reported a bookmark
         * reported a site
         * banned a link
         * banned a site
         */
        Schema::create('user_activity', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('activity');
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('user_activity');
    }
}
