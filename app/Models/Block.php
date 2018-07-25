<?php

namespace App\Models;

class Block extends BaseModel
{
    public function building()
    {
        return $this->hasMany('App\Models\Building','block_guid', 'guid');
    }
}
