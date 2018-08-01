<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AreasRequest;
use App\Models\Area;
use App\Models\City;
use App\Repositories\AreasRepository;
use Illuminate\Http\Request;

class AreasController extends APIBaseController
{
    // 区域列表
    public function index(
        Request $request,
        AreasRepository $repository
    )
    {
        $res = $repository->areaList($request);
        return $this->sendResponse($res,'区域列表获取成功');
    }

    // 添加区域
    public function store(
        AreasRequest $request,
        AreasRepository $repository
    )
    {
        $res = $repository->addArea($request);
        return $this->sendResponse($res,'添加区域信息成功');
    }

    // 区域修改前原始数据
    public function edit(
        Area $area
    )
    {
        return $this->sendResponse($area,'获取区域修改前原始数据成功');
    }

    // 修改区域信息
    public function update(
        Area $area,
        AreasRequest $request,
        AreasRepository $repository
    )
    {
        $res = $repository->updateArea($request, $area);
        if (empty($res)) return $this->sendError('修改区域信息失败');
        return $this->sendResponse($res,'修改区域信息成功');
    }

    // 获取所有区域
    public function getAllArea()
    {
        $areas = Area::all()->map(function($area) {
            return [
                'value' => $area->guid,
                'label' => $area->name
            ];
        });

        return $this->sendResponse($areas,'获取所有区域成功');
    }

    // 所有区的下拉数据
    public function areasSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_guid', $city->guid)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $item = array(
                    'value' => $area->guid,
                    'label' => $area->name,
                );
                $area_box[] = $item; // 城市下的区
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
