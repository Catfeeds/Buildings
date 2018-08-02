<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingsRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\City;
use App\Repositories\BuildingsRepository;
use App\Services\BuildingsService;
use Illuminate\Http\Request;

class BuildingsController extends APIBaseController
{
    // 楼盘列表
    public function index(
        Request $request,
        BuildingsRepository $repository,
        BuildingsService $service
    )
    {
        $res = $repository->buildingList($request, $service);
        return $this->sendResponse($res,'楼盘列表获取成功');
    }

    // 添加楼盘
    public function store(
        BuildingsRequest $request,
        BuildingsRepository $repository
    )
    {
        $validate = [];
        foreach ($request->building_block as $v) {
            if (in_array($v['name'].'|'.$v['name_unit'].'|'.$v['unit'].'|'.$v['unit_unit'], $validate)) {
                return $this->sendError('楼座信息不能重复添加');
            }
            $validate[] = $v['name'].'|'.$v['name_unit'].'|'.$v['unit'].'|'.$v['unit_unit'];
        }

        $res = $repository->addBuilding($request);
        if (empty($res)) return $this->sendError('楼盘添加失败');
        return $this->sendResponse($res,'楼盘添加成功');
    }

    // 楼盘修改之前原始数据
    public function edit(
        Building $building
    )
    {
        $building->city_guid = $building->area->city->guid;
        return $this->sendResponse($building,'楼盘修改之前原始数据');
    }

    // 修改楼盘
    public function update(
        Building $building,
        BuildingsRequest $request,
        BuildingsRepository $repository
    )
    {
        $res = $repository->updateBuilding($request, $building);
        if (empty($res)) return $this->sendError('楼盘修改失败');
        return $this->sendResponse($res,'楼盘修改成功');
    }

    // 楼盘搜索下拉
    public function buildingSearchSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_guid', $city->guid)->get();
            $area_box = array();
            foreach ($areas as $area) {
                // 获取楼盘
                $buildings = $area->Building->flatten();
                $building_box = array();
                foreach ($buildings as $building) {
                    $item = array(
                        'value' => $building->guid,
                        'label' => $building->name,
                    );
                    $building_box[] = $item;
                }
                $item = array(
                    'value' => $area->guid,
                    'label' => $area->name,
                    'children' => $building_box
                );
                $area_box[] = $item;
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

    // 所有楼盘下拉数据
    public function buildingSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_guid', $city->guid)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $buildings = Building::where('area_guid', $area->guid)->get();
                $building_box = array();
                foreach ($buildings as $building) {
                    $item = array(
                        'value' => $building->guid,
                        'label' => $building->name,
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
}
