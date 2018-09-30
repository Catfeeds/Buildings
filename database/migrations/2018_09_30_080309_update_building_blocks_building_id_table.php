<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuildingBlocksBuildingIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_blocks', function (Blueprint $table) {
            $table->string('building_id', 32)->nullable()->comment('所属楼盘guid')->change();
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
            $table->char('building_id')->comment('建筑面积/平方米')->change();
        });
    }
}
