<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // live, reported, banned, deleted
        Schema::create('user_status', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('status');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Social Logins
        // Microsoft, GitHub, Facebook, Twitter, Google, LinkedIn
        Schema::create('oauth_type', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('homepage_url');
            $table->string('official_documentation_url');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('surname');
            $table->char('bio')->nullable();
            $table->string('intro')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('email')->unique(); //if logging in with OAuth, set this email address to the OAuth Email Address
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password')->nullable(); //nullable as can login with OAuth email and password
            $table->unsignedSmallInteger('oauth_type_id')->nullable();
            $table->foreign('oauth_type_id')->references('id')->on('oauth_type')->onDelete('cascade');
            $table->string('oauth_email')->nullable(); //to connect users table to OAuth email
            $table->string('username');
            $table->enum('user_level', ['admin', 'standard', 'police'])->default('standard');
            $table->unsignedTinyInteger('police_id')->nullable();
            $table->unsignedSmallInteger('status_id');
            $table->foreign('status_id')->references('id')->on('user_status')->onDelete('cascade');
            $table->rememberToken();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_oauth_type_id_foreign');
            $table->dropColumn('oauth_type_id');
            $table->dropForeign('users_status_id_foreign');
            $table->dropColumn('status_id');
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('user_status');
        Schema::dropIfExists('oauth_type');
    }
}
