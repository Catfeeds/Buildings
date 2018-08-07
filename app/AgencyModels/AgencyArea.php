<?php

namespace App\AgencyModels;

use Illuminate\Database\Eloquent\Model;

class AgencyArea extends Model
{
    protected $table = 'areas';

    protected $guarded = [];

    protected $connection = 'agency';

    // 商圈
    public function agencyBlock()
    {
        return $this->hasMany('App\AgencyModels\AgencyBlock','area_id','id');
    }

}
