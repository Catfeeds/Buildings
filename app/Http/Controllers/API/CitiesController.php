<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Traits\QiNiu;
use App\Http\Requests\API\CitiesRequest;
use App\Models\City;
use App\Repositories\CitiesRepository;
use Illuminate\Http\Request;

class CitiesController extends APIBaseController
{
    use QiNiu;

    // 城市列表
    public function index(
        Request $request,
        CitiesRepository $repository
    )
    {
        $res = $repository->cityList($request);
        return $this->sendResponse($res,'城市列表获取成功');
    }

    // 添加城市数据
    public function store(
        CitiesRequest $request,
        CitiesRepository $repository
    )
    {
        $res = $repository->addCity($request);
        return $this->sendResponse($res,'城市数据添加成功');
    }

    // 获取修改城市数据原始数据
    public function edit(
        City $city
    )
    {
        return $this->sendResponse($city,'获取修改城市数据原始数据');
    }

    // 修改城市基本信息
    public function update(
        City $city,
        CitiesRequest $request,
        CitiesRepository $repository
    )
    {
        $res = $repository->updateCity($request, $city);
        if (empty($res)) return $this->sendError('城市信息修改失败');
        return $this->sendResponse($res,'城市信息修改成功');
    }

    // 获取所有城市
    public function getAllCity()
    {
        $citys = City::all()->map(function($city) {
            return [
                'value' => $city->guid,
                'label' => $city->name
            ];
        });

        return $this->sendResponse($citys,'获取所有城市成功');
    }

    // 获取下拉数据
    public function getAllSelect(
        Request $request
    )
    {
        if (!in_array($request->number, [1,2,3]) && $request->number) return $this->sendError('参数错误');

        if (empty($request->city_guid)) {
            $all = City::with('area.block.building.buildingBlock')->get();
        } else {
            $all = City::where('guid', $request->city_guid)->with('area.block.building.buildingBlock')->get();
        }

        $citys = array();
        foreach ($all as $cityKey => $city) {
            $citys[$cityKey]['value'] = $city->guid;
            $citys[$cityKey]['label'] = $city->name;

            if ($request->number == 1) continue;

            $areas = array();
            foreach ($city->area as $areaKey => $area) {
                $areas[$areaKey]['value'] = $area->guid;
                $areas[$areaKey]['label'] = $area->name;

                if ($request->number == 2) continue;

                $blocks = array();
                foreach ($area->block as $blockKey => $block) {
                    $blocks[$blockKey]['value'] = $block->guid;
                    $blocks[$blockKey]['label'] = $block->name;

                    if ($request->number == 3) continue;

                    $buildings = array();
                    foreach ($block->building as $buildingKey => $building) {
                        $buildings[$buildingKey]['value'] = $building->guid;
                        $buildings[$buildingKey]['label'] = $building->name;
                    }
                    $blocks[$blockKey]['children'] = $buildings;
                }
                $areas[$areaKey]['children'] = $blocks;
            }
            $citys[$cityKey]['children'] = $areas;
        }

        return $this->sendResponse($citys,'获取下拉数据成功');
    }

    // 获取楼盘,楼座关联基础数据
    public function getBuildingBlock(
        Request $request
    )
    {
        if (empty($request->city_guid)) {
            $all = City::with('area.block.building.buildingBlock')->get();
        } else {
            $all = City::where('guid', $request->city_guid)->with('area.block.building.buildingBlock')->get();
        }

        $data = array();
        foreach ($all as $cityKey => $city) {
            foreach ($city->area as $areaKey => $area) {
                $buildings = array();
                foreach ($area->building as $buildingKey => $building) {
                    $buildings[$buildingKey]['value'] = $building->guid;
                    $buildings[$buildingKey]['label'] = $building->name;
                    $buildingBlocks = array();
                    foreach ($building->buildingBlock as $buildingBlocKey => $buildingBlock) {
                        $buildingBlocks[$buildingBlocKey]['value'] = $buildingBlock->guid;
                        $buildingBlocks[$buildingBlocKey]['label'] = $buildingBlock->name.$buildingBlock->name_unit;
                    }
                    $buildings[$buildingKey]['children'] = $buildingBlocks;
                }
                $data[] = $buildings;
            }
        }

        $datas = array();
        foreach ($data as $v) {
            foreach ($v as $val) {
                $datas[] = $val;
            }
        }

        return $this->sendResponse($datas,'获取下拉数据成功');
    }

}
