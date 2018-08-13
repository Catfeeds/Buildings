<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingKeyword;
use App\Services\BuildingKeywordsService;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Console\Command;
use Overtrue\LaravelPinyin\Facades\Pinyin;

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

        $service = new BuildingKeywordsService();
        foreach ($buildings as $key => $v) {
            $string = $service->keyword($v);

            $res = BuildingKeyword::create([
                'building_guid' => $v->guid,
                'keywords' => $string
            ]);
            if (empty($res)) \Log::error('guid为:'.$v->guid.'的楼盘关键词添加失败');
        }
    }
}
