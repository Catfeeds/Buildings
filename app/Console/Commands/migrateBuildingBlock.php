<?php

namespace App\Console\Commands;

use App\AgencyModels\AgencyBuildingBlock;
use App\Handler\Common;
use App\Models\Building;
use App\Models\BuildingBlock;
use Illuminate\Console\Command;

class migrateBuildingBlock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateBuildingBlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移中介项目楼座表';

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
        $buildingBlock = AgencyBuildingBlock::all();
        foreach ($buildingBlock as $v) {
            $guid = Building::where('id', $v->building_id)->value('guid');
            $res = BuildingBlock::create([
                'guid' => Common::getUuid(),
                'id' => $v->id,
                'building_guid' => $guid,
                'building_id' => $v->building_id,
                'name' => $v->name,
                'name_unit' => $v->name_unit,
                'unit' => $v->unit,
                'unit_unit' => $v->unit_unit,
                'class' => $v->class,
                'structure' => $v->structure,
                'total_floor' => $v->total_floor,
                'property_company' => $v->property_company,
                'property_fee' => $v->property_fee,
                'heating' => $v->heating,
                'air_conditioner' => $v->air_conditioner,
                'elevator_num' => $v->elevator_num,
                'passenger_lift' => $v->passenger_lift,
                'cargo_lift' => $v->cargo_lift,
                'president_lift' => $v->president_lift
            ]);
            if (!$res) \Log::info($v->id.'迁移失败');
        }
    }
}
