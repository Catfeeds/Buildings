<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Building;
use App\Models\BuildingBlock;
use Illuminate\Database\Eloquent\Model;

class BuildingBlocksRepository extends Model
{
    // 拿到楼盘下的所有楼座
    public function getAllBuildingBlock(
        $request
    )
    {
        return BuildingBlock::where('building_guid', $request->building_guid)->with('building.area')->get();
    }
    
    // 获取所有楼座的列表
    public function getList(
        $per_page,
        $request
    )
    {
        $buildingBlock = BuildingBlock::with('building.area')->orderBy('updated_at', 'desc');
        if (!empty($request->area_guid)) {
            // 传了城区 查城区下的楼盘 再查楼盘下的楼座
            $buildings = Building::where('area_guid', $request->area_guid)->get()->pluck('guid')->toArray();
            $result = $buildingBlock->whereIn('building_guid', $buildings);
        }
        if (!empty($request->building_guid)) {
            $result = $buildingBlock->where('building_guid', $request->building_guid);
        }
        if (empty($request->area_guid) && empty($request->building_guid)) $result = $buildingBlock;
        return $result->paginate($per_page);
    }

    // 修改单个楼座的单元和楼座名称
    public function changeNameUnit(
        $buildingBlock,
        $request
    )
    {
        $buildingBlock->name = $request->name;
        $buildingBlock->name_unit = $request->name_unit;
        $buildingBlock->unit = $request->unit;
        $buildingBlock->unit_unit = $request->unit_unit;
        return $buildingBlock->save();
    }

    // 添加楼座单元和楼座名称
    public function addNameUnit($request)
    {
        $res = BuildingBlock::create([
            'guid' => Common::getUuid(),
            'building_guid' => $request->building_guid,
            'name' => $request->name,
            'name_unit' => $request->name_unit,
            'unit' => $request->unit,
            'unit_unit' => $request->unit_unit
        ]);
        return $res;
    }

    // 补充某个楼座的楼座信息
    public function addBlockInfo($buildingBlock, $request)
    {
        $buildingBlock->class = $request->class;
        $buildingBlock->structure = $request->structure;
        $buildingBlock->total_floor = $request->total_floor;
        $buildingBlock->property_company = $request->property_company;
        $buildingBlock->property_fee = $request->property_fee;

        $buildingBlock->heating = $request->heating;
        $buildingBlock->air_conditioner = $request->air_conditioner;

        $buildingBlock->passenger_lift = $request->passenger_lift;
        $buildingBlock->cargo_lift = $request->cargo_lift;
        $buildingBlock->president_lift = $request->president_lift;

        return $buildingBlock->save();
    }
}