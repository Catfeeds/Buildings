<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BlocksRequest;
use App\Models\Block;
use App\Repositories\BlocksRepository;
use Illuminate\Http\Request;

class BlocksController extends APIBaseController
{
    // 商圈列表
    public function index(
        Request $request,
        BlocksRepository $repository
    )
    {
        $repository->blockList($request);
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

}
