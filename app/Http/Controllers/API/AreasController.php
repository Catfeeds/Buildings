<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AreasRequest;
use App\Models\Area;
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

}
