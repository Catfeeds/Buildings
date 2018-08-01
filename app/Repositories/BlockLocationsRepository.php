<?php

namespace App\Repositories;

use App\Models\BlockLocation;
use Illuminate\Database\Eloquent\Model;

class BlockLocationsRepository extends Model
{
    // 获取所有商圈基础地理位置
    public function blockLocations()
    {
        return BlockLocation::with('block')->get();
    }
}