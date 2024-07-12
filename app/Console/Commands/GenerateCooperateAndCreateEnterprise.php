<?php

namespace App\Console\Commands;

use App\Http\Controllers\CooperativeController;
use App\Models\Cooperative;
use Illuminate\Console\Command;

class GenerateCooperateAndCreateEnterprise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-cooperate-and-create-enterprise';

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
        // finish migrate cooperative to enterprise
        return null;

        $cooperatives = Cooperative::get();
        foreach($cooperatives as $cooperative)
        {
            $cooperative->formation_date = now();
            $cooperative->generateCooperativeCode();
            $cooperative->email = 'cooperative_' . str_pad($cooperative->id, 4, '0', STR_PAD_LEFT) . '@gmail.com';
            $cooperative->phone_number = str_replace('+', '0', fake()->unique()->e164PhoneNumber());
            $cooperative->save();
        }

        $cooperatives = Cooperative::get();
        foreach ($cooperatives as $cooperative) {
            $cooperatedController = new CooperativeController();
            $cooperatedController->createEnterprise($cooperative);
        }
    }
}
