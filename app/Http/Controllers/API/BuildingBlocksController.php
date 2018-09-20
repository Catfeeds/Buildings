<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingBlocksRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use App\Repositories\BuildingBlocksRepository;
use App\Services\BuildingBlocksService;
use Illuminate\Http\Request;

class BuildingBlocksController extends APIBaseController
{
    // 拿到楼盘下的所有楼座
    public function index(
        Request $request,
        BuildingBlocksRepository $repository
    )
    {
        $buildingBlocks = $repository->getAllBuildingBlock($request);
        return $this->sendResponse($buildingBlocks, '获取成功');
    }

    // 单条楼座信息
    public function show(BuildingBlock $buildingBlock)
    {
        return $this->sendResponse($buildingBlock, '获取成功');
    }

    // 楼座分页列表
    public function allBlocks(
        Request $request,
        BuildingBlocksRepository $repository
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res, '获取成功');
    }

    // 修改某个楼座的名称
    public function changeNameUnit
    (
        BuildingBlocksRequest $request,
        BuildingBlock $buildingBlock,
        BuildingBlocksRepository $repository
    )
    {
        if (!empty($request->name) && $request->name != $buildingBlock->name && BuildingBlock::where(['building_guid' => $request->building_guid, 'name' => $request->name, 'name_unit' => $request->name_unit, 'unit' => $request->unit, 'unit_unit' => $request->unit_unit])->first()) return $this->sendError('同一楼盘下楼座名称不能重复');

        $res = $repository->changeNameUnit($buildingBlock, $request);
        return $this->sendResponse($res, '修改成功');
    }

    // 添加楼座（名称、楼盘）
    public function addNameUnit(
        BuildingBlocksRequest $request,
        BuildingBlocksRepository $repository
    )
    {
        $temp = BuildingBlock::where([
            'building_guid' => $request->building_guid,
            'name' => $request->name,
            'name_unit' => $request->name_unit,
            'unit' => $request->unit,
            'unit_unit' => $request->unit_unit
        ])->first();
        if ($temp) {
            return $this->sendError('同一楼盘下楼座名称不能重复');
        }

        $res = $repository->addNameUnit($request);
        return $this->sendResponse($res, '添加成功');
    }

    // 补充楼座信息
    public function addBlockInfo
    (
        BuildingBlock $buildingBlock,
        Request $request,
        BuildingBlocksRepository $repository
    )
    {
        $res = $repository->addBlockInfo($buildingBlock, $request);
        return $this->sendResponse($res, '修改成功');
    }

    // 所有的楼座下拉数据
    public function buildingBlocksSelect(
        Request $request
    )
    {
        if (empty($request->city_guid)) {
            $cities = City::all();
        } else {
            $cities = City::where('guid', $request->city_guid)->get();
        }
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_guid', $city->guid)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $buildings = Building::where('area_guid', $area->guid)->get();
                $building_box = array();
                foreach ($buildings as $building) {
                    $buildingBlocks = BuildingBlock::where('building_guid', $building->guid)->get();
                    $buildingBlocks = $buildingBlocks->sortBy('name')->values();
                    $buildingBlockBox = array();
                    foreach ($buildingBlocks as $buildingBlock) {
                        $item = array(
                            'value' => $buildingBlock->guid,
                            'label' => $buildingBlock->block_info,
                        );
                        $buildingBlockBox[] = $item;
                    }
                    $item = array(
                        'value' => $building->guid,
                        'label' => $building->name,
                        'children' => $buildingBlockBox
                    );
                    $building_box[] = $item;
                }
                $area_item = array(
                    'value' => $area->guid,
                    'label' => $area->name,
                    'children' => $building_box
                );
                $area_box[] = $area_item;
            }
            $city_item = array(
                'value' => $city->guid,
                'label' => $city->name,
                'children' => $area_box
            );
            $city_box[] = $city_item; // 所有城市
        }
        return $this->sendResponse($city_box, '获取成功');
    }

    // 通过楼座获取城市
    public function adoptBuildingBlockGetCity(
        Request $request,
        BuildingBlocksService $service
    )
    {
        return $service->adoptBuildingBlockGetCity($request);
    }

    // 更新排序
    public function sort
    (
        BuildingBlocksRequest $request,
        BuildingBlocksRepository $repository
    )
    {
        $res = $repository->updateSort($request);
        if (!$res) return $this->sendError('排序更新失败');
        return $this->sendResponse(true,'排序更新成功');
    }

}
