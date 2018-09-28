<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('主键GUID');
            $table->integer('id')->nullable()->comment('原始id');
            $table->string('name', 32)->comment('区域名');
            $table->char('city_guid',32)->comment('城市guid');
            $table->integer('city_id')->nullable()->comment('城市id');
            $table->integer('weight')->nullable()->comment('排序权重');
            $table->softDeletes();
            $table->timestamps();
        });
        \DB::statement("alter table `areas` comment'区域信息基础表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
