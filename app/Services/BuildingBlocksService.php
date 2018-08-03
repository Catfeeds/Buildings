<?php

namespace App\Services;

use App\Models\BuildingBlock;

class BuildingBlocksService
{
    // 通过楼座获取城市
    public function adoptBuildingBlockGetCity($request)
    {
        $temp = BuildingBlock::find($request->building_block_guid);

        // 拼接商圈获取城市数据
        $arr[] = $temp->building->area->city->guid;
        $arr[] = $temp->building->area->guid;
        $arr[] = $temp->building->guid;
        $arr[] = $request->building_block_guid;

        return $arr;
    }


}