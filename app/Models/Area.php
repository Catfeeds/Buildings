<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Area extends BaseModel
{
    // 全局作用域 排序
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function(Builder $builder) {
            $builder->orderBy('weight', 'asc');
        });

    }

    // 城市
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // 区域管理商圈
    public function block()
    {
        return $this->hasMany('App\Models\Block','area_guid','guid');
    }

    // 楼盘
    public function building()
    {
        return $this->hasMany(Building::class, 'area_guid', 'guid');
    }

}
