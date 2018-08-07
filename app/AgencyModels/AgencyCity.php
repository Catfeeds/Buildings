<?php

namespace App\AgencyModels;

use Illuminate\Database\Eloquent\Model;

class AgencyCity extends Model
{
    protected $table = 'cities';

    protected $guarded = [];

    protected $connection = 'agency';

    // 区域
    public function agencyArea()
    {
        return $this->hasMany('App\AgencyModels\AgencyArea','city_id','id');
    }
}
