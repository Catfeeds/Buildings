<?php

namespace App\Console\Commands;

use App\AgencyModels\AgencyArea;
use App\Handler\Common;
use App\Models\Area;
use App\Models\City;
use Illuminate\Console\Command;

class migrateAreas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateAreas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移中介项目区域表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $area = AgencyArea::all();

        // 武汉市规定
        $guid = City::where('name', '武汉')->value('guid');
        foreach ($area as $v) {
            $res = Area::create([
                'guid' => Common::getUuid(),
                'id' => $v->id,
                'name' => $v->name,
                'city_guid' => $guid,
                'city_id' => $v->city_id
            ]);
            if (!$res) \Log::info($v->id.'迁移失败');
        }
    }
}
