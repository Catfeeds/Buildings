<?php

namespace App\Models;

class City extends BaseModel
{
    // 区域
    public function area()
    {
        return $this->hasMany('App\Models\Area','city_guid','guid');
    }
}
