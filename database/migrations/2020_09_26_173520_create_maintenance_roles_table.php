<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_type')->onDelete('cascade');
            $table->boolean('allowed')->default(false);
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
        Schema::dropIfExists('maintenance_roles');
    }
}
