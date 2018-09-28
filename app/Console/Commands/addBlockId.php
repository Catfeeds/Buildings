<?php

namespace App\Console\Commands;

use App\Models\Block;
use App\Models\Building;
use Illuminate\Console\Command;

class addBlockId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addBlockId';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自增商圈id';

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
        $block = Block::all();
        foreach ($block as $k => $v) {
            $v->update(['id' => $k+1]);
        }
        Building::where([])->update(['block_id' => 1]);
    }
}
