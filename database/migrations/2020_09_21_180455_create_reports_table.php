<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_type', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); //who did this action
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->unsignedBigInteger('bookmark_id')->nullable(); //the bookmark to complain of
            $table->unsignedBigInteger('target_user_id')->nullable(); //who the user is ACCUSING of an offence
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('bookmark_id');
            $table->index('target_user_id');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade');
            $table->string('notes')->nullable();
            $table->unsignedSmallInteger('report_type_id');
            $table->foreign('report_type_id')->references('id')->on('report_type')->onDelete('cascade');
            $table->unsignedMediumInteger('suspicious_id')->nullable(); // did our bots pick up any abuse or suspicious posting ?
            $table->foreign('suspicious_id')->references('id')->on('suspicious')->onDelete('cascade');
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
        Schema::dropIfExists('report_type');
        Schema::dropIfExists('reports');
    }
}
