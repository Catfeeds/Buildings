<?php

namespace App\Console\Commands;

use App\AgencyModels\AgencyCity;
use App\Handler\Common;
use App\Models\Area;
use App\Models\Block;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use Illuminate\Console\Command;

class AgencyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agencyData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入中介城市,区域,商圈,楼盘,楼座基础数据';

    /**
     * AddManager constructor.
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
        self::agencyData();
    }

    // 导入中介城市,区域,商圈,楼盘,楼座基础数据
    public function agencyData()
    {
        $citys = AgencyCity::with('agencyArea.agencyBlock.agencyBuilding.agencyBuildingBlock')->get();

        \DB::beginTransaction();
        try {
            foreach ($citys as $agencyCity) {
                // 加入城市基础数据表
                $city = City::create([
                    'guid' => Common::getUuid(),
                    'name' => $agencyCity->name,
                ]);
                if (empty($city)) throw new \Exception($agencyCity->name.'的城市添加失败');

                foreach ($agencyCity->agencyArea as $agencyArea) {
                    $area = Area::create([
                        'guid' => Common::getUuid(),
                        'city_guid' => $city->guid,
                        'name' => $agencyArea->name
                    ]);
                    if (empty($area)) throw new \Exception($agencyArea->name.'的区域添加失败');

                    foreach ($agencyArea->agencyBlock as $agencyBlock) {
                        $block = Block::create([
                            'guid' => Common::getUuid(),
                            'name' => $agencyBlock->name,
                            'area_guid' => $area->guid
                        ]);
                        if (empty($block)) throw new \Exception($agencyBlock->name.'的商圈添加失败');

                        foreach ($agencyBlock->agencyBuilding as $agencyBuilding) {
                            $building = Building::create([
                                'guid' => Common::getUuid(),
                                'name' => $agencyBuilding->name,
                                'gps' => json_decode($agencyBuilding->gps),
                                'x' => $agencyBuilding->x,
                                'y' => $agencyBuilding->y,
                                'area_guid' => $area->guid,
                                'block_guid' => $block->guid,
                                'address' => $agencyBuilding->address,
                                'developer' => $agencyBuilding->developer,
                                'years' => $agencyBuilding->years,
                                'acreage' => $agencyBuilding->acreage,
                                'building_block_num' => $agencyBuilding->building_block_num,
                                'parking_num' => $agencyBuilding->parking_num,
                                'parking_fee' => $agencyBuilding->parking_fee,
                                'greening_rate' => $agencyBuilding->greening_rate,
                                'album' => json_decode($agencyBuilding->album),
                                'big_album' => json_decode($agencyBuilding->big_album),
                                'describe' => $agencyBuilding->describe
                            ]);
                            if (empty($building)) throw new \Exception($agencyBuilding->name.'的楼盘添加失败');

                            foreach ($agencyBuilding->agencyBuildingBlock as $agencyBuildingBlock) {
                                $buildingBlock = BuildingBlock::create([
                                    'guid' => Common::getUuid(),
                                    'building_guid' => $building->guid,
                                    'name' => $agencyBuildingBlock->name,
                                    'name_unit' => $agencyBuildingBlock->name_unit,
                                    'unit' => $agencyBuildingBlock->unit,
                                    'unit_unit' => $agencyBuildingBlock->unit_unit,
                                    'class' => $agencyBuildingBlock->class,
                                    'structure' => $agencyBuildingBlock->structure,
                                    'total_floor' => $agencyBuildingBlock->total_floor,
                                    'property_company' => $agencyBuildingBlock->property_company,
                                    'property_fee' => $agencyBuildingBlock->property_fee,
                                    'heating' => $agencyBuildingBlock->heating,
                                    'air_conditioner' => $agencyBuildingBlock->air_conditioner,
                                    'elevator_num' => $agencyBuildingBlock->elevator_num,
                                    'passenger_lift' => $agencyBuildingBlock->passenger_lift,
                                    'cargo_lift' => $agencyBuildingBlock->cargo_lift,
                                    'president_lift' => $agencyBuildingBlock->president_lift,
                                ]);
                                if (empty($buildingBlock)) throw new \Exception('id为'.$agencyBuildingBlock->id.'的楼座添加失败');
                            }
                        }
                    }
                }
            }
            \DB::commit();
            $this->info('基础数据导入成功');
        } catch (\Exception $exception) {
            \DB::rollBack();
            $this->error('基础数据导入失败');
        }
    }
}
