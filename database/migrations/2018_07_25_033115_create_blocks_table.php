<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->string('name', 32)->nullable()->comment('商圈名');
            $table->char('area_guid',32)->nullable()->comment('区域guid');
            $table->tinyInteger('recommend')->default(2)->comment('是否推荐 1: 是 2: 否');
            $table->string('agent_name',32)->nullable()->comment('经纪人名称');
            $table->string('agent_pic',32)->nullable()->comment('经纪人图片');
            $table->softDeletes();
            $table->timestamps();
        });
        \DB::statement("alter table `blocks` comment'商圈信息基础表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
