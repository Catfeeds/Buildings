<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use Illuminate\Database\Eloquent\Model;

class BuildingsRepository extends Model
{
    // 楼盘列表
    public function buildingList(
        $request,
        $service
    )
    {
        $result = Building::with('buildingBlock', 'area', 'block')->orderBy('updated_at', 'desc');

        if ($request->building_guid) {
            $result = $result->where(['guid' => $request->building_guid]);
        } elseif ($request->block_guid) {
            $request = $result->where('block_guid', $request->block_guid);
        } elseif ($request->area_guid) {
            $buildingGuid = array_column(Area::find($request->area_guid)->building->flatten()->toArray(), 'guid');
            $result = $result->whereIn('guid', $buildingGuid);
        } elseif ($request->city_guid) {
            // 通过城市查出区域
            $areaGuids = Area::where('city_guid', $request->city_guid)->pluck('guid')->toArray();

            $areas = Area::whereIn('guid', $areaGuids)->with('building')->get();

            $buildingGuid = $areas->map(function($area) {
                return [
                    array_column(Area::find($area->guid)->building->flatten()->toArray(), 'guid')
                ];
            });

            $result = $result->whereIn('guid', $buildingGuid->flatten()->toArray());
        }

        $buildings = $result->paginate($request->per_page??10);

        foreach($buildings as $v) {
            $service->getAddress($v);
        }
        return $buildings;
    }

    // 添加楼盘
    public function addBuilding(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            // 添加楼盘
            $building = Building::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'gps' => $request->gps,
                'x' => $request->gps[0],
                'y' => $request->gps[1],

                'area_guid' => $request->area_guid,
                'block_guid' => $request->block_guid,
                'address' => $request->address,

                'developer' => $request->developer,
                'years' => $request->years,
                'acreage' => $request->acreage,
                'building_block_num' => $request->building_block_num,
                'parking_num' => $request->parking_num,
                'parking_fee' => $request->parking_fee,
                'greening_rate' => $request->greening_rate,

                'album' => $request->album,
                'big_album' => $request->big_album,

                'describe' => $request->describe,
            ]);
            if (empty($building)) throw new \Exception('楼盘添加失败');

            // 添加楼座
            foreach ($request->building_block as $buildingBlock) {
                $addBuildingBlock = BuildingBlock::create([
                    'guid' => Common::getUuid(),
                    'building_guid' => $building->guid,
                    'name' => $buildingBlock['name'],
                    'name_unit' => $buildingBlock['name_unit'],
                    'unit' => $buildingBlock['unit'],
                    'unit_unit' => $buildingBlock['unit_unit'],
                ]);
                if (empty($addBuildingBlock)) throw new \Exception('楼栋添加失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 修改楼盘
    public function updateBuilding(
        $request,
        $building
    )
    {
        \DB::beginTransaction();
        try {
            // 修改楼盘
            $building->name = $request->name;
            $building->gps = $request->gps;
            $building->x = $request->gps[0];
            $building->y = $request->gps[1];
            $building->area_guid = $request->area_guid;
            $building->block_guid = $request->block_guid;
            $building->address = $request->address;
            $building->developer = $request->developer;
            $building->years = $request->years;
            $building->acreage = $request->acreage;
            $building->building_block_num = $request->building_block_num;
            $building->parking_num = $request->parking_num;
            $building->parking_fee = $request->parking_fee;
            $building->greening_rate = $request->greening_rate;
            $building->album = $request->album;
            $building->big_album = $request->big_album;
            $building->describe = $request->describe;
            if (!$building->save()) throw new \Exception('楼盘修改失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

}