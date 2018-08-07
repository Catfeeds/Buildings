<?php

namespace App\AgencyModels;

use Illuminate\Database\Eloquent\Model;

class AgencyBuilding extends Model
{
    protected $table = 'buildings';

    protected $guarded = [];

    protected $connection = 'agency';

    // 楼座
    public function agencyBuildingBlock()
    {
        return $this->hasMany('App\AgencyModels\AgencyBuildingBlock','building_id','id');
    }

}
