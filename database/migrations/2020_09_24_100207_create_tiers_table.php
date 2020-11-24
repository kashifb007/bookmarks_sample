<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 200);
            $table->string('description', 1000);
            $table->float('monthly_amount', 8, 2);
            $table->float('annual_amount', 8, 2);
            $table->float('lifetime_amount', 8, 2);
            $table->unsignedSmallInteger('limit')->nullable()->default(30); // up to how many bookmarks
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
        Schema::dropIfExists('tiers');
    }
}
