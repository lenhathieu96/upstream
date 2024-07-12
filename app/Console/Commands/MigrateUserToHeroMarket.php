<?php

namespace App\Console\Commands;

use App\Models\FarmerDetails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MigrateUserToHeroMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-user-to-hero-market';

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
        
        // finish upload to hero market
        return null; 

        set_time_limit('86400');
        $farmerDetails = FarmerDetails::whereNotNull('phone_number')->get()->groupBy('phone_number');
        $phoneExistUrl = env('HEROMARKET_URL') . '/api/v2/file/is-phone-existed';
        $signupApiUrl = env('HEROMARKET_URL') . '/api/v2/auth/signup';

        try {
            foreach ($farmerDetails as $phoneNumber => $groupFamer) {
                $phoneNumber = '+84' . ((int) $phoneNumber);
                $farmer = $groupFamer->whereNotNull('farmer_photo')->first();
                if (empty($farmer)) {
                    $farmer = $groupFamer->first();
                }

                $response = Http::withOptions(['verify' => false])->post($phoneExistUrl, ['phone_number' => $phoneNumber]);
                $response = json_decode($response->getBody(), true);
                if (isset($response['phone_exist']) && $response['phone_exist'] == 1) {
                    continue;
                }

                $email = 'upstream_' . uniqid() . '@gmail.com';

                $dataRegisterSeller = [
                    'create_seller_from_upstream' => 1,
                    'bussiness_name' => $farmer->full_name,
                    'email' => $email,
                    'password' => '12345678',   
                    'password_confirmation' => '12345678',   
                    'country_code' => '84',
                    'phone' => $phoneNumber,
                    'country' => 238,
                    'city' => 48358,
                    'state' => 4056,
                    'address' => $farmer->short_address,
                    'user_type' => 'farmer',
                    'lat' => $farmer->lat,
                    'lng' => $farmer->lng,
                    'is_enterprise' => 0,
                    'categories_id' => 1,
                    'upstream_farmer_id' => $farmer->id,
                ];

                $upload = $farmer->thumbnail;
                if (!empty($upload)) {
                    $dataRegisterSeller['upload_from_upstream'] = 1;
                    $dataRegisterSeller['file_original_name'] = $upload->file_original_name;
                    $dataRegisterSeller['file_name'] = str_replace('storage/', '', $upload->file_name);
                    $dataRegisterSeller['file_size'] = $upload->file_size;
                    $dataRegisterSeller['extension'] = $upload->extension;
                    $dataRegisterSeller['type'] = $upload->type;
                }

                $response = Http::withOptions(['verify' => false])->post($signupApiUrl, $dataRegisterSeller);
                $response = json_decode($response->getBody(), true);
                $this->info('register user successfully: ' . $farmer->full_name);

                // send image file
                if (!empty($upload)) {
                    send_image_to_hero($upload);
                }
            }
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }

        $this->info('register finished');
    }
}
