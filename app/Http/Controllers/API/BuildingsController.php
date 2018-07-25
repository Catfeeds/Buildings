<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingsRequest;
use App\Models\Building;
use App\Repositories\BuildingsRepository;
use Illuminate\Http\Request;

class BuildingsController extends APIBaseController
{
    // 楼盘列表
    public function index(
        Request $request,
        BuildingsRepository $repository
    )
    {
        $res = $repository->buildingList($request);
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
}
