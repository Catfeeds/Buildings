<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Block;

class BlocksService
{
    // 获取所有商圈
    public function allBuildingBlock()
    {
        // 获取所有区域
        $areas = Area::get();
        dd($area);
        $data = array();
        foreach ($areas as $area) {
            $block_box = array();
            $blocks = Block::where('area_guid', $area->guid)->get();
            foreach ($blocks as $block) {
                $res['id'] = $block->guid;
                $res['name'] = $block->name;
                $block_box[] = $res;
            }

            $temp['area_name'] = $area->name;
            $temp['block'] = $block_box;
            $data[] = $temp;
        }

        return $data;
    }
}