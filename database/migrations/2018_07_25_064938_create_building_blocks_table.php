<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_blocks', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->integer('id')->nullable()->comment('原始id');
            $table->char('building_guid', 32)->comment('所属楼盘guid');
            $table->integer('building_id')->comment('所属楼盘原始id');
            $table->string('name', 32)->nullable()->comment('楼座名称');
            $table->string('name_unit', 32)->nullable()->comment('楼座单位');
            $table->string('unit', 32)->nullable()->comment('单元名称');
            $table->string('unit_unit', 32)->nullable()->comment('单元单位');
            $table->integer('class')->nullable()->comment('等级 1：甲 2：乙 3：丙');
            $table->integer('structure')->nullable()->comment('房屋结构 1：钢筋混凝土结构 2：钢结构 3：砖混结构 4：砖木结构');
            $table->integer('total_floor')->nullable()->comment('楼层总数量');
            $table->string('property_company', 128)->nullable()->comment('物业公司');
            $table->decimal('property_fee')->nullable()->comment('物业费');
            $table->integer('heating')->nullable()->comment('采暖方式 1：空调 2：太阳能');
            $table->integer('air_conditioner')->nullable()->comment('空调类型 1：中央空调 2：非中央空调');
            $table->integer('elevator_num')->nullable()->comment('电梯总数量');
            $table->integer('passenger_lift')->nullable()->comment('客梯数量');
            $table->integer('cargo_lift')->nullable()->comment('货梯数量');
            $table->integer('president_lift')->nullable()->comment('总裁电梯数量');
            $table->integer('weight')->nullable()->comment('排序权重');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `building_blocks` comment'楼座表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_blocks');
    }
}
