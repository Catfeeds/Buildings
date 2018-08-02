<?php

namespace App\Models;

class BuildingBlock extends BaseModel
{
    protected $appends = [
        'info',
        'block_info'
    ];

    // 楼盘
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // 获取楼座info
    public function getInfoAttribute()
    {
        $building = $this->building;
        if (empty($building)) return;
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $building->name . $blocksInfo;
    }

    // 获取楼座info
    public function getBlockInfoAttribute()
    {
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $blocksInfo;
    }
}
