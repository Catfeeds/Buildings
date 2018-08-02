<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\City;
use Illuminate\Database\Eloquent\Model;

class CitiesRepository extends Model
{
    // 城市列表
    public function cityList($request)
    {
        return City::paginate($request->per_page??10);
    }
    
    // 添加城市数据
    public function addCity($request)
    {
        return City::create([
            'guid' => Common::getUuid(),
            'name' => $request->name
        ]);
    }


    // 修改城市基本信息
    public function updateCity(
        $request,
        $city
    )
    {
        $city->name = $request->name;
        if (!$city->save()) return false;
        return true;
    }
}