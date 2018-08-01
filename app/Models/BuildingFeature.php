<?php

namespace App\Models;

class BuildingFeature extends BaseModel
{
    protected $appends = [
        'pic_cn',
        'pc_pic_cn',
        'feature_cn',
        'pc_pic_url'
    ];

    public function getPicCnAttribute()
    {
        return config('setting.qiniu_url').$this->pic;
    }

    // pc端楼盘特色图片
    public function getPcPicCnAttribute()
    {
        return config('setting.qiniu_url').$this->pc_pic;
    }

    // 特色图片
    public function getFeatureCnAttribute()
    {
        return collect($this->pic)->map(function($img) {
            return [
                'name' => $img,
                'url'  => config('setting.qiniu_url') . $img
            ];
        });
    }

    // pc端楼盘特色图片
    public function getPcPicUrlAttribute()
    {
        return collect($this->pc_pic)->map(function($img) {
            return [
                'name' => $img,
                'url'  => config('setting.qiniu_url') . $img
            ];
        });
    }
}
