<?php

namespace App\Services;

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class BuildingKeywordsService
{
    // 关键字处理
    public function keyword($building)
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();

        $buildingName = $building->name;   // 楼盘名
        $blockName = empty($building->block)?'':$building->block->name;   // 商圈名
        $areaName = $building->area->name; // 区域名
        $cityName = $building->area->city->name;   // 城市名
        $string = $buildingName.$blockName.$areaName.$cityName;

        // 切词之后的字符串
        $jbArray = Jieba::cutForSearch($string);

        // 汉子转拼音
        $pyJbArray = array();
        foreach ($jbArray as $value) {
            $pyJbArray[] = preg_replace('# #', '', Pinyin::sentence($value));
        }

        // 字符串长度
        $length = mb_strlen($string, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++) {
            $array[] = mb_substr($string, $i, 1, 'utf-8');
        }

        // 楼盘名
        $array[] = $buildingName;

        // 汉子转拼音
        $pyArray = array();
        foreach ($array as $val) {
            $pyArray[] = preg_replace('# #', '', Pinyin::sentence($val));
        }

        $endString = array_unique(array_merge($array, $jbArray, $pyArray, $pyJbArray));

        $string = implode(' ', $endString);

        return $string;
    }


}