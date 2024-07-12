<?php

namespace App\Console\Commands;

use App\Models\FarmerDetails;
use App\Models\Uploads;
use Illuminate\Console\Command;

class UpdateUserTypeUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-type-upload';

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
        // finish update
        return null;

        // process code
        $farmerDetails = FarmerDetails::whereNotNull('farmer_photo')->get();
        foreach ($farmerDetails as $farmerDetail) {
            $upload = Uploads::find($farmerDetail->farmer_photo); // upload.id = $farmerDetail->farmer_photo
            if ($upload) {
                $upload->update(['user_type' => 'farmer', 'user_id' => $farmerDetail->id]);
            }
        }
        $this->info('task finish');
    }
}
