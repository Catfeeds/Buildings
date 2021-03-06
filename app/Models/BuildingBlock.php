<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class BuildingBlock extends BaseModel
{
    protected $appends = [
        'info',
        'block_info'
    ];

    // 全局作用域 排序
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function(Builder $builder) {
            $builder->orderBy('weight', 'asc');
        });

    }

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
