<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addDefaultUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加默认用户';

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
        // 添加默认用户
        self::addDefaultUser();
    }

    // 添加默认用户
    public function addDefaultUser()
    {
        User::create([
            'name' => 'admin@chulouwang.com',
            'password' => bcrypt('chulouwang888')
        ]);

    }
}
