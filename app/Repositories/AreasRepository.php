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
        if (empty($request->city_guid)) {
            $where = array();
        } else {
            $where = ['city_guid' => $request->city_guid];
        }

        return Area::where($where)->get();
    }

    // 添加区域信息
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

    // 修改区域信息
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

    // 更新排序
    public function updateSort($request)
    {
        $weight = $request->weight;
        \DB::beginTransaction();
        try {
            foreach ($weight as $k => $v) {
                Area::where('guid', $v)->update(['weight' => $k]);
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('更新失败'.$exception->getMessage());
            return false;
        }
    }
}