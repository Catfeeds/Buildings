<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $casts = [
        'gps' => 'array',
        'album' => 'array',
        'company' => 'array',
        'big_album' => 'array',
    ];
}
