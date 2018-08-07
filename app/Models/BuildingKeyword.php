<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingKeyword extends Model
{
    protected $guarded = [];

    // 如果使用的是非递增或者非数字的主键，则必须在模型上设置
    public $incrementing = false;

    // 主键
    protected $primaryKey = 'building_guid';

    // 主键类型
    protected $keyType = 'string';

    protected $with = [
        'building'
    ];

    public function building()
    {
        return $this->belongsTo('App\Models\Building','building_guid','guid');
    }

}
