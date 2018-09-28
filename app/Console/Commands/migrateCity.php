<?php

namespace App\Console\Commands;

use App\AgencyModels\AgencyCity;
use App\Handler\Common;
use App\Models\City;
use Illuminate\Console\Command;

class migrateCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateCity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移中介项目城市表';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $city = AgencyCity::all();
        foreach ($city as $v) {
            $res = City::create([
                'guid' => Common::getUuid(),
                'id' => $v->id,
                'name' => $v->name
            ]);
            if (!$res) {
                \Log::info($v->id.'迁移失败');
            }
        }
    }
}
