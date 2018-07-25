<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_locations', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->char('block_guid',32)->nullable()->comment('商圈guid');
            $table->string('x',32)->nullable()->comment('经度');
            $table->string('y',32)->nullable()->comment('纬度');
            $table->text('scope')->nullable()->comment('商圈范围');
            $table->integer('building_num')->nullable()->comment('楼盘套数');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `block_locations` comment'商圈地理范围基础数据表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_locations');
    }
}
