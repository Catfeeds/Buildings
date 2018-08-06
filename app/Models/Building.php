<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $casts = [
        'gps' => 'array',
        'album' => 'array',
        'big_album' => 'array',
    ];

    protected $appends = [
        'type_cn',
        'pic_url_cn',
        'pc_pic_url',
        'area_label',
        'city_guid'
    ];

    // 楼座
    public function buildingBlock()
    {
        return $this->hasMany('App\Models\BuildingBlock','building_guid','guid');
    }

    // 所属商圈
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    //区域
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // 楼盘类型信息   type_cn
    public function getTypeCnAttribute()
    {
        switch ($this->type) {
            case 1:
                return '住宅';
            case 2:
                return '写字楼';
            case 3:
                return '商铺';
        }
    }

    // pc端图片url
    public function getPcPicUrlAttribute()
    {
        if (!empty($this->big_album)) {
            return collect($this->big_album)->map(function($img) {
                return [
                    'name' => $img,
                    'url' => config('setting.qiniu_url') . $img
                ];
            });
        } else {
            return collect([
                [
                    'name' => '',
                    'url' => config('setting.pc_building_default_big_img')
                ]
            ]);
        }
    }

    // 图片url
    public function getPicUrlCnAttribute()
    {
        return collect($this->album)->map(function($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img
            ];
        });
    }

    // 区域信息
    public function getAreaLabelAttribute()
    {
        $area = $this->area;
        if (empty($area)) return;
        return $area->name;
    }

    // 城市guid
    public function getCityGuidAttribute()
    {
        if (empty($this->area)) return;
        return $this->area->city->guid;
    }
}
