<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBlockLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_locations', function (Blueprint $table) {
            $table->integer('block_id')->nullable()->comment('商圈id')->after('block_guid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_locations', function (Blueprint $table) {
            $table->dropColumn('block_id');
        });
    }
}
