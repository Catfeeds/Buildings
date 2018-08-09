<?php

namespace App\Repositories;

use App\Models\Area;
use App\Models\Block;
use App\Models\BuildingKeyword;
use Illuminate\Database\Eloquent\Model;

class BuildingKeywordsRepository extends Model
{
    // 关键字列表
    public function buildingKeywordsList(
        $request
    )
    {
        $result = BuildingKeyword::make();

        if ($request->city_guid) {
            // 通过城市查出区域
            $areaGuids = Area::where('city_guid', $request->city_guid)->pluck('guid')->toArray();
            $areas = Area::whereIn('guid', $areaGuids)->with('building')->get();
            $buildingGuid = $areas->map(function($area) {
                return [
                    array_column(Area::find($area->guid)->building->flatten()->toArray(), 'guid')
                ];
            });
            $result = $result->whereIn('building_guid', $buildingGuid->flatten()->toArray());
        } elseif ($request->area_guid) {
            $buildingGuid = array_column(Area::find($request->area_guid)->building->flatten()->toArray(), 'guid');
            $result = $result->whereIn('building_guid', $buildingGuid);
        } elseif ($request->block_guid) {
            $buildingGuid = array_column(Block::find($request->block_guid)->building->flatten()->toArray(), 'guid');
            $result = $result->whereIn('building_guid', $buildingGuid);
        } elseif ($request->building_guid) {
            $result = $result->where(['building_guid' => $request->building_guid]);
        }

        return $result->paginate($request->per_page??10);
    }

    // 修改关键字
    public function updateBuildingKeywords(
        $request,
        $buildingKeyword
    )
    {
        $buildingKeyword->keywords = $request->keywords;
        if (!$buildingKeyword->save()) return false;
        return true;
    }
}