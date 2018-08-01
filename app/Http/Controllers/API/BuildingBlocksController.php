<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingBlocksRequest;
use App\Models\BuildingBlock;
use App\Repositories\BuildingBlocksRepository;
use Illuminate\Http\Request;

class BuildingBlocksController extends APIBaseController
{
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
        $res = $repository->getList($request->per_page, $request);
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
}
