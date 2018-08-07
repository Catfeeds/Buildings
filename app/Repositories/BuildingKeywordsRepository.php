<?php

namespace App\Repositories;

use App\Models\BuildingKeyword;
use Illuminate\Database\Eloquent\Model;

class BuildingKeywordsRepository extends Model
{
    // 关键字列表
    public function buildingKeywordsList(
        $request
    )
    {
        return BuildingKeyword::paginate($request->per_page??10);
    }

    // 修改关键字
    public function updateBuildingKeywords(
        $request,
        $buildingKeyword
    )
    {
        $buildingKeyword->keywords = $request->keywords;
        if (!$buildingKeyword->save()) return false;
        return true;
    }
}