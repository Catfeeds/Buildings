<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Block;
use App\Models\BlockLocation;
use Illuminate\Database\Eloquent\Model;

class BlocksRepository extends Model
{
    // 商圈列表
    public function blockList(
        $request
    )
    {
        $model = Block::with('area', 'building', 'blockLocation');
        if($request->area_guid) {
            $model = $model->where(['area_guid'=>$request->area_guid]);
        }
        return $model->paginate($request->per_page??10);
    }

    // 添加商圈
    public function addBlock(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $block = Block::create([
                'guid' => Common::getUuid(),
                'area_guid' => $request->area_guid,
                'name' => $request->name,
                'recommend' => $request->recommend,
                'agent_name' => $request->agent_name,
                'agent_pic' => $request->agent_pic
            ]);
            if (empty($block)) throw new \Exception('商圈添加失败');

            $blockLocation = BlockLocation::where('block_guid', $block->guid)->first();

            if (empty($blockLocation)) {
                $addBlockLocation = BlockLocation::create([
                    'guid' => Common::getUuid(),
                    'block_guid' => $block->guid,
                    'x' => $request->x,
                    'y' => $request->y,
                    'scope' => $request->baidu_coord,
                    'building_num' => $block->withCount('building')->first()->building_count,
                ]);
                if (empty($addBlockLocation)) throw new \Exception('商圈地理范围添加失败');
            } else {
                $blockLocation->x = $request->x;
                $blockLocation->y = $request->y;
                $blockLocation->scope = $request->baidu_coord;
                $blockLocation->building_num = $block->withCount('building')->first()->building_count;
                if (!$blockLocation->save()) throw new \Exception('商圈地理位置修改失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 修改商圈
    public function updateBlock(
        $request,
        $block
    )
    {
        \DB::beginTransaction();
        try {
            $block->area_guid = $request->area_guid;
            $block->name = $request->name;
            $block->recommend = $request->recommend;
            $block->agent_name = $request->agent_name;
            $block->agent_pic = $request->agent_pic;
            if (!$block->save()) throw new \Exception('商圈修改失败');

            $blockLocation = BlockLocation::where('block_guid', $block->guid)->first();
            if (empty($blockLocation)) {
                $addBlockLocation = BlockLocation::create([
                    'guid' => Common::getUuid(),
                    'block_guid' => $block->guid,
                    'x' => $request->x,
                    'y' => $request->y,
                    'scope' => $request->baidu_coord,
                    'building_num' => $block->withCount('building')->first()->building_count,
                ]);
                if (empty($addBlockLocation)) throw new \Exception('商圈地理范围添加失败');
            } else {
                $blockLocation->x = $request->x;
                $blockLocation->y = $request->y;
                $blockLocation->scope = $request->baidu_coord;
                $blockLocation->building_num = $block->withCount('building')->first()->building_count;
                if (!$blockLocation->save()) throw new \Exception('商圈地理位置修改失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 商圈添加推荐
    public function addRecommend(
        $guid,
        $request
    )
    {
        return Block::where('guid', $guid)->update(['recommend' => $request->recommend]);
    }
}