<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_features', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->string('name',32)->nullable()->comment('名称');
            $table->tinyInteger('weight')->nullable()->comment('权重');
            $table->string('pic', 128)->nullable()->comment('图片');
            $table->string('pc_pic',128)->nullable()->comment('pc端图片');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `building_features` comment'楼盘特色信息基础表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_features');
    }
}
