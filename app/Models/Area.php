<?php

namespace App\Models;

class Area extends BaseModel
{
    // 城市
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // 楼盘
    public function building()
    {
        return $this->hasMany(Building::class, 'area_guid', 'guid');
    }

}
