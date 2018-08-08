<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingKeyword;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Console\Command;

class AddBuildingKeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addBuildingKeyword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '楼盘关键字';

    /**
     * AddManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 楼盘关键字
        self::addBuildingKeyword();
    }

    /**
     * 说明: 楼盘关键字添加
     *
     * @author 罗振
     */
    public function addBuildingKeyword()
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();

        $buildings = Building::with('block', 'area.city')->get();

        foreach ($buildings as $key => $v) {
            $buildingName = $v->name;   // 楼盘名
            $blockName = empty($v->block)?'':$v->block->name;   // 商圈名
            $areaName = $v->area->name; // 区域名
            $cityName = $v->area->city->name;   // 城市名
            $string = $buildingName.$blockName.$areaName.$cityName;

            // 切词之后的字符串
            $jbArray = Jieba::cutForSearch($string);

            // 字符串长度
            $length = mb_strlen($string, 'utf-8');
            $array = [];
            for ($i=0; $i<$length; $i++) {
                $array[] = mb_substr($string, $i, 1, 'utf-8');
            }

            // 楼盘名
            $array[] = $buildingName;

            $endString = array_unique(array_merge($array, $jbArray));

            $string = implode(' ', $endString);

            $res = BuildingKeyword::create([
                'building_guid' => $v->guid,
                'keywords' => $string
            ]);
            if (empty($res)) \Log::error('guid为:'.$v->guid.'的楼盘关键词添加失败');
        }
    }
}