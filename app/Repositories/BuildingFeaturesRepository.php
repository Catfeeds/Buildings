<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\BuildingFeature;
use Illuminate\Database\Eloquent\Model;

class BuildingFeaturesRepository extends Model
{
    // 楼盘特色列表
    public function buildingFeaturesList(
        $request
    )
    {
        return BuildingFeature::paginate($request->per_page??10);
    }

    // 添加楼盘特色
    public function addBuildingFeature(
        $request
    )
    {
        return BuildingFeature::create([
            'guid' => Common::getUuid(),
            'name' => $request->name,
            'weight' => $request->weight,
            'pic' => $request->pic,
            'pc_pic' => $request->pc_pic
        ]);
    }

    // 修改楼盘特色
    public function updateBuildingFeature(
        $request,
        $buildingFeature
    )
    {
        $buildingFeature->name = $request->name;
        $buildingFeature->weight = $request->weight;
        $buildingFeature->pic = $request->pic;
        $buildingFeature->pc_pic = $request->pc_pic;
        if (!$buildingFeature->save()) return false;
        return true;
    }
}