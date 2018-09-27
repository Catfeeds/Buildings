<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->integer('id')->nullable()->comment('原始id');
            $table->string('name', 128)->nullable()->comment('楼盘名');
            $table->json('gps')->nullable()->comment('gps定位');
            $table->string('x', 32)->nullable()->comment('经度');
            $table->string('y', 32)->nullable()->comment('纬度');

            $table->char('area_guid',32)->nullable()->comment('关联城区guid');
            $table->char('block_guid',32)->nullable()->comment('商圈guid');
            $table->string('address', 128)->nullable()->comment('具体地址');

            $table->string('developer', 128)->nullable()->comment('开发商');
            $table->integer('years')->nullable()->comment('年代 --年');
            $table->decimal('acreage',20,2)->nullable()->comment('建筑面积 --㎡');
            $table->integer('building_block_num')->nullable()->comment('楼栋数量 --栋');
            $table->integer('parking_num')->nullable()->comment('车位数量 --个');
            $table->decimal('parking_fee')->nullable()->comment('停车费 --元/月');
            $table->integer('greening_rate')->nullable()->comment('绿化率 --%');

            $table->json('album')->nullable()->comment('楼盘相册');
            $table->json('big_album')->nullable()->comment('楼盘大图相册');
            $table->text('describe')->nullable()->comment('楼盘描述');

            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `buildings` comment'楼盘信息基础表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
