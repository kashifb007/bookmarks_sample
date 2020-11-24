<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // admin, police, standard, developer, copywriter, founding member, read only
        Schema::create('user_type', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique();
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_level');
            $table->unsignedSmallInteger('user_type_id')->default(1);
            $table->foreign('user_type_id')->references('id')->on('user_type')->onDelete('cascade');
        });

        Schema::table('user_status', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_type');
    }
}
