<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BlocksRequest;
use App\Models\Block;
use App\Repositories\BlockLocationsRepository;
use App\Repositories\BlocksRepository;
use App\Services\BlocksService;
use Illuminate\Http\Request;

class BlocksController extends APIBaseController
{
    // 商圈列表
    public function index(
        Request $request,
        BlocksRepository $repository
    )
    {
        $res = $repository->blockList($request);
        return $this->sendResponse($res,'商圈列表获取成功');
    }

    // 添加商圈
    public function store(
        BlocksRequest $request,
        BlocksRepository $repository
    )
    {
        $res = $repository->addBlock($request);
        return $this->sendResponse($res,'添加商圈成功');
    }

    // 商圈修改之前原始数据
    public function edit(
        Block $block
    )
    {
        if (!empty($block->blockLocation)) {
            $block->blockLocationGuid = $block->blockLocation->guid;   // 商圈基础地理位置
        }
        $block->city_guid = $block->area->city->guid;
        return $this->sendResponse($block,'获取商圈修改之前原始数据成功');
    }

    // 修改商圈
    public function update(
        Block $block,
        BlocksRequest $request,
        BlocksRepository $repository
    )
    {
        $res = $repository->updateBlock($request, $block);
        if (empty($res)) return $this->sendError('商圈修改失败');
        return $this->sendResponse($res,'商圈修改成功');
    }

    // 某个区域下的商圈下拉数据
    public function blocksSelect(Request $request)
    {
        $area_guid = $request->area_guid;
        if (empty($area_guid)) return $this->sendError('参数错误');

        $blocks = Block::where('area_guid', $area_guid)->get();
        $blockBox = array();
        foreach ($blocks as $v) {
            $item = array(
                'value' => $v->guid,
                'label' => $v->name
            );
            $blockBox[] = $item;
        }
        return $this->sendResponse($blockBox, '获取成功');
    }

    // 获取所有商圈基础地理位置
    public function blockLocations(
        BlockLocationsRepository $repository
    )
    {
        $res = $repository->blockLocations();
        return $this->sendResponse($res,'获取所有商圈基础地理位置成功');
    }

    // 获取所有商圈
    public function allBuildingBlock(
        BlocksService $blocksService
    )
    {
        $result= $blocksService->allBuildingBlock();
        return $this->sendResponse($result,'所有商圈信息获取成功');
    }
}
