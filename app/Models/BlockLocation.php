<?php

namespace App\Models;

class BlockLocation extends BaseModel
{
    // 商圈
    public function block()
    {
        return $this->belongsTo('App\Models\Block','block_guid', 'guid');
    }
}
