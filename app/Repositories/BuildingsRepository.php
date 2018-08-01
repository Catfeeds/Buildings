<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\BuildingFeature;
use App\Models\BuildingHasFeature;
use App\Models\BuildingLabel;
use Illuminate\Database\Eloquent\Model;

class BuildingsRepository extends Model
{
    // 楼盘列表
    public function buildingList(
        $request,
        $service
    )
    {
        $result = Building::with('buildingBlock', 'features', 'label', 'area', 'block')->orderBy('updated_at', 'desc');

        if ($request->city_guid && $request->area_guid && $request->building_guid) {
            $result = $result->where(['id' => $request->building_guid]);
        } elseif ($request->city_guid && $request->area_guid && empty($request->building_guid)) {
            $buildingGuid = array_column(Area::find($request->area_guid)->building->flatten()->toArray(), 'guid');
            $result = $result->whereIn('guid', $buildingGuid);
        } elseif ($request->city_guid && empty($request->area_guid) && empty($request->building_guid)) {
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
            $service->features($v);
            $service->label($v);
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

                'type' => $request->type,
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

                'company' => $request->company,
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

            // 添加楼盘特色
            if (!empty($request->building_feature)) {
                foreach($request->building_feature as $buildingFeature) {
                    $addBuildingHasFeature = BuildingHasFeature::create([
                        'guid' => Common::getUuid(),
                        'building_guid' => $building->guid,
                        'building_feature_guid' => $buildingFeature
                    ]);
                    if (empty($addBuildingHasFeature)) throw new \Exception('楼盘特色添加失败');
                }
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
            $building->type = $request->type;
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
            $building->company = $request->company;
            $building->album = $request->album;
            $building->big_album = $request->big_album;
            $building->describe = $request->describe;
            if (!$building->save()) throw new \Exception('楼盘修改失败');

            // 获取要修改的特色
            $buildingFeatures = $request->building_feature;
            if (!empty($buildingFeatures)) {
                // 查询查该楼盘已经有的特色
                $features = BuildingHasFeature::where('building_guid', $building->guid)->pluck('building_feature_guid')->toArray();

                // 修改特色-已有特色,得到要添加的特色
                $addFeature = array_diff($buildingFeatures, $features);
                if (!empty($addFeature)) {
                    $res = BuildingHasFeature::where('building_guid', $building->guid)->whereIn('building_feature_guid', $addFeature)->get();
                    if (!$res->isEmpty()) throw new \Exception('楼盘特色不能重复添加');
                    foreach($addFeature as $v) {
                        $addBuildingHasFeature = BuildingHasFeature::create([
                            'guid' => Common::getUuid(),
                            'building_guid' => $building->guid,
                            'building_feature_guid' => $v
                        ]);
                        if (empty($addBuildingHasFeature)) throw new \Exception('楼盘特色关联表修改失败');
                    }
                }
                // 已有特色-修改特色,得到要删除的特色
                $delFeature = array_diff($features, $buildingFeatures);
                if (!empty($delFeature)) {
                    foreach($delFeature as $v) {
                        $delBuildingHasFeature = BuildingHasFeature::where([
                            'building_guid' => $building->guid,
                            'building_feature_guid' => $v
                        ])->delete();
                        if (empty($delBuildingHasFeature)) throw new \Exception('楼盘特色关联表删除失败');
                    }
                }
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 添加楼盘标签
    public function addBuildingLabel($request)
    {
        return BuildingLabel::create([
            'building_guid' => $request->building_guid
        ]);
    }

    // 删除楼盘标签
    public function delBuildingLabel(
        $guid
    )
    {
        return BuildingLabel::where('building_guid', $guid)->first()->delete();
    }

    // 获取楼盘特色下拉数据
    public function getBuildingFeatureList()
    {
        return BuildingFeature::all();
    }
}