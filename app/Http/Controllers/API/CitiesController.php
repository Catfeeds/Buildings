<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CitiesRequest;
use App\Models\City;
use App\Repositories\CitiesRepository;
use Illuminate\Http\Request;

class CitiesController extends APIBaseController
{
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
}
