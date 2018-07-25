<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Area;
use Illuminate\Database\Eloquent\Model;

class AreasRepository extends Model
{
    // 区域列表
    public function areaList(
        $request
    )
    {
        return Area::paginate($request->per_page??10);
    }

    // 添加区域
    public function addArea(
        $request
    )
    {
        return Area::create([
            'guid' => Common::getUuid(),
            'city_guid' => $request->city_guid,
            'name' => $request->name
        ]);
    }

    public function updateArea(
        $request,
        $area
    )
    {
        $area->city_guid = $request->city_guid;
        $area->name = $request->name;
        if (!$area->save()) return false;
        return true;
    }
}