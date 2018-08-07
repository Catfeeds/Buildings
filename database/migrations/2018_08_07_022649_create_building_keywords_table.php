<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_keywords', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->char('building_guid',32)->primary()->comment('楼盘guid');
            $table->text('keywords')->comment('索引字段');
            $table->timestamps();
        });
        \DB::statement('alter table `building_keywords` ADD FULLTEXT(`keywords`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_keywords');
    }
}
