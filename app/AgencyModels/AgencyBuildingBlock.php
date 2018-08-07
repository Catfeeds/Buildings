<?php

namespace App\AgencyModels;

use Illuminate\Database\Eloquent\Model;

class AgencyBuildingBlock extends Model
{
    protected $table = 'building_blocks';

    protected $guarded = [];

    protected $connection = 'agency';



}
