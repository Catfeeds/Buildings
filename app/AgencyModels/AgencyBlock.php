<?php

namespace App\AgencyModels;

use Illuminate\Database\Eloquent\Model;

class AgencyBlock extends Model
{
    protected $table = 'blocks';

    protected $guarded = [];

    protected $connection = 'agency';

    // 楼盘
    public function agencyBuilding()
    {
        return $this->hasMany('App\AgencyModels\AgencyBuilding','block_id', 'id');
    }

}
