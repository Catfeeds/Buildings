<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBuildingBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_blocks', function (Blueprint $table) {
            $table->integer('weight')->nullable()->comment('排序权重')->after('president_lift');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_blocks', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
}
