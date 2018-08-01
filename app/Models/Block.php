<?php

namespace App\Models;

class Block extends BaseModel
{
    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_guid','guid');
    }

    public function building()
    {
        return $this->hasMany('App\Models\Building','block_guid', 'guid');
    }

    public function blockLocation()
    {
        return $this->belongsTo('App\Models\BlockLocation', 'guid','block_guid');
    }
}
