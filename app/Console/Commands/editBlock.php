<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Block;
use App\Models\BlockLocation;
use Illuminate\Console\Command;

class editBlock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'editBlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '商圈表编辑';

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
        $blockLocation = BlockLocation::all();

        foreach ($blockLocation as $v) {
            $block_id = Block::where('guid', $v->block_guid)->value('id');
            $v->update(['block_id' => $block_id]);
        }

        $block = Block::all();
        foreach ($block as $v) {
            $area_id =  Area::where('guid', $v->area_guid)->value('id');
            $v->update(['area_id' => $area_id]);
        }
    }
}
