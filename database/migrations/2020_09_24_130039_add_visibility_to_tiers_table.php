<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibilityToTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiers', function (Blueprint $table) {
            $table->enum('visibility', ['hidden', 'public', 'cancelled'])->default('public');
            $table->unsignedSmallInteger('sort_order');
            $table->char('colour')->default('blue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiers', function (Blueprint $table) {
            $table->dropColumn('visibility');
            $table->dropColumn('sort_order');
            $table->dropColumn('colour');
        });
    }
}
