<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingFeaturesRequest;
use App\Models\BuildingFeature;
use App\Repositories\BuildingFeaturesRepository;
use Illuminate\Http\Request;

class BuildingFeaturesController extends APIBaseController
{
    // 楼盘特色列表
    public function index(
        Request $request,
        BuildingFeaturesRepository $repository
    )
    {
        $res = $repository->buildingFeaturesList($request);
        return $this->sendResponse($res,'楼盘特色列表获取成功');
    }

    // 添加楼盘特色
    public function store(
        BuildingFeaturesRequest $request,
        BuildingFeaturesRepository $repository
    )
    {
        $res = $repository->addBuildingFeature($request);
        return $this->sendResponse($res,'楼盘特色添加成功');
    }

    // 楼盘特色修改之前原始数据
    public function edit(
        BuildingFeature $buildingFeature
    )
    {
        return $this->sendResponse($buildingFeature,'楼盘特色修改之前原始数据获取成功');
    }

    // 修改楼盘特色
    public function update(
        BuildingFeature $buildingFeature,
        BuildingFeaturesRequest $request,
        BuildingFeaturesRepository $repository
    )
    {
        $res = $repository->updateBuildingFeature($request, $buildingFeature);
        if (empty($res)) return $this->sendError('楼盘特色修改失败');
        return $this->sendResponse($res,'楼盘特色修改成功');
    }
}
