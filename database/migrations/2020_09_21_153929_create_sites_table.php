<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('base_uri', 300); //can also be a subdomain as they could have their own separate logo
            $table->string('title', 1000);
            $table->string('description', 3000)->nullable();
            $table->string('logo', 500)->nullable();
            $table->string('logo_sha1sum', 100)->nullable(); //if sha1sum and bytes have changed, we need to refresh the logo
            $table->unsignedInteger('logo_bytes')->nullable();
            $table->dateTime('last_ping'); //ping every time someone adds a bookmark for this site
            $table->dateTime('last_logo_update'); //the last time the logo was updated
            $table->unsignedSmallInteger('status_code'); //the status code from attempting to fetch a logo, eg 200 OK
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
        Schema::dropIfExists('sites');
    }
}
