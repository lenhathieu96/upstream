<?php

namespace App\Console\Commands;

use App\Models\Cooperative;
use Illuminate\Console\Command;
use App\Http\Controllers\CooperativeController;

class CreateUserForCooperative extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user-for-cooperative';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cooperativeController = new CooperativeController();
        $cooperatives = Cooperative::all();

        foreach ($cooperatives as $cooperative) {
            $cooperativeController->upstreamCreateUserFromCooperative($cooperative);
        }

        $this->info('finish create user for cooperative');
    }
}
