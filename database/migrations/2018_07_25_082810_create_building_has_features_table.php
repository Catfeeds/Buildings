<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingHasFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_has_features', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->char('building_guid',32)->nullable()->comment('楼盘guid');
            $table->char('building_feature_guid',32)->nullable()->comment('楼盘特色guid');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `building_has_features` comment'楼盘特色关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_has_features');
    }
}
