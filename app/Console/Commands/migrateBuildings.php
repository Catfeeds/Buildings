<?php

namespace App\Console\Commands;

use App\AgencyModels\AgencyBuilding;
use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use Illuminate\Console\Command;

class migrateBuildings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateBuildings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移中介楼盘数据';

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
        $building = AgencyBuilding::all();
        foreach ($building as $v) {

            $guid = Area::where('id', $v->area_id)->value('guid');

            $res = Building::create([
                'guid' => Common::getUuid(),
                'id' => $v->id,
                'name' => $v->name,
                'gps' => $v->gps,
                'x' => $v->x,
                'y' => $v->y,
                'area_guid' => $guid,
                'area_id' => $v->area_id,
                'block_guid' => '0d75cca4a78411e8bb3000163e125aba',
                'address' => $v->address,
                'developer' => $v->developer,
                'years' => $v->years,
                'acreage' => $v->acreage,
                'building_block_num' => $v->building_block_num,
                'parking_num' => $v->parking_num,
                'parking_fee' => $v->parking_fee,
                'greening_rate' => $v->greening_rate,
                'album' => $v->album,
                'big_album' => $v->big_album,
                'describe' => $v->describe
            ]);
            if (!$res) \Log::info($v->id.'迁移失败');
        }
    }
}
