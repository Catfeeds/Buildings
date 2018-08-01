<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_labels', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->char('building_guid')->nullable()->comment('楼盘GUID');
            $table->timestamps();
        });
        DB::statement("alter table `building_labels` comment'楼盘标签表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_labels');
    }
}
