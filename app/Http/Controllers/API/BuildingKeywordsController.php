<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingKeywordsRequest;
use App\Models\BuildingKeyword;
use App\Repositories\BuildingKeywordsRepository;
use Illuminate\Http\Request;

class BuildingKeywordsController extends APIBaseController
{
    // 关键字列表
    public function index(
        Request $request,
        BuildingKeywordsRepository $repository
    )
    {
        $res = $repository->buildingKeywordsList($request);
        return $this->sendResponse($res,'获取关键词列表成功');
    }

    // 获取关键字修改之前原始
    public function edit(
        BuildingKeyword $buildingKeyword
    )
    {
        return $this->sendResponse($buildingKeyword,'获取关键字修改之前原始数据成功');
    }

    // 修改关键字
    public function update(
        BuildingKeyword $buildingKeyword,
        BuildingKeywordsRequest $request,
        BuildingKeywordsRepository $repository
    )
    {
        $res = $repository->updateBuildingKeywords($request, $buildingKeyword);
        if (empty($res)) return $this->sendError('关键字修改失败');
        return $this->sendResponse($res,'关键字修改成功');
    }



}
