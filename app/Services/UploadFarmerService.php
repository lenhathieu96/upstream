<?php

namespace App\Services;

use App\Models\Cooperative;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UploadFarmerService
{
    // public function getProductByEnterprise(array $attribute)
    // {
    //     $cooperative = Cooperative::find($attribute['id']);
    //     $heromarketUrl = config('upstream.HEROMARKET_URL');
    //     $endpoint = $heromarketUrl . '/api/v2/users/products';

    //     $category =  !empty($attribute['category_id']) ? ['category_id' => $attribute['category_id']] : [];
    //     $response = Http::withOptions([
    //         'verify' => false,
    //     ])->post($endpoint, array_merge(['email' => $cooperative->email], $category));

    //     $response = json_decode($response->getBody(), true);

    //     return $response;
    // }

    public function importFarmer(array $farmerData)
    {
        $farmerDetail = FarmerDetails::create([
            'user_id' => 3,
            'staff_id' => $farmerData['staff_id'],
            'full_name' => trim($farmerData['last_name'] . ' ' . $farmerData['first_name']),
            'phone_number' => $this->getUniquePhoneNumber($farmerData['phone_number']),
            'country' => $farmerData['country'],
            'province' => $farmerData['province'],
            'district' => $farmerData['district'],
            'commune' => $farmerData['commune'],
            'village' => $farmerData['village'],
            'enrollment_date' => now()->toDateString(),
            'gender' => 'Male',
            'farmer_code' => FarmerDetails::getFarmerCode(),
            'cooperative_id' => $farmerData['cooperative_id'],
            'tenant' => $farmerData['tenant'],
        ]);

        if ($farmerDetail) {
            $userId = $this->createUserFromFarmer($farmerDetail);
            $farmerDetail->user_id = $userId;
            $farmerDetail->save();

            $farmLand = FarmLand::create([
                'farmer_id' => $farmerDetail->id,
                'farm_name' => $farmerData['plot_name'],
                'total_land_holding' => $farmerData['cultivated_area'],
                'season_id' => $farmerData['season_id'],
            ]);

            $cultivation = Cultivations::create([
                'farm_land_id' => $farmLand->id,
                'season_id' => $farmerData['season_id'],
                'crop_id' => $farmerData['crop_id'],
                'crop_variety' => $farmerData['variety'],
            ]);

            $farmerDetail->faAccount()->create([
                'typee' => 3,
                'acc_type' => 'FRA'
            ]);

            $email = 'upstream_' . uniqid() . '@gmail.com';
            $signupApiUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/signup';
            
            $response = null;
            // farmer has role seller in heromarket
            $dataRegisterSeller = [
                'create_seller_from_upstream' => 1,
                'bussiness_name' => $farmerDetail->full_name,
                'email' => $email,
                'password' => '12345678',   
                'password_confirmation' => '12345678',   
                'country_code' => '84',
                'phone' => $farmerDetail->phone_number,
                'country' => 238,
                'city' => 48358,
                'state' => 4056,
                'address' => 'Vietnam, Long An city, Long An',
                'user_type' => 'farmer',
                'lat' => null,
                'lng' => null,
                'is_enterprise' => 0,
                'categories_id' => 1,
                'upstream_farmer_id' => $farmerDetail->id,
            ];

            $response = Http::withOptions(['verify' => false])->post($signupApiUrl, $dataRegisterSeller);
            
            // log if cannot register seller
            if ($response?->getStatusCode() != 200) {
                \Log::error('Cannot register seller' .  $response?->body());
            }

            // NOTE: we don't have send image feature here because we only upload farmer by csv

            return null;
        }
    }

    /**
     * use phoneNumber for valid
     * use empty_phone_unique for empty phone
     * use duplicate_rand_phoneNumber for duplicate
     */
    public function getUniquePhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return 'empty_phone_' . uniqid();
        }

        if (str_contains($phoneNumber, 'duplicate')) {
            $phone = $phoneNumber;
        } else {
            $phone = (int) $phoneNumber;
        }

        $farmerByPhone1 = FarmerDetails::where('phone_number', $phone)->first();
        $farmerByPhone2 = FarmerDetails::where('phone_number', '0' . $phone)->first();
        $userByPhone1 = User::where('phone_number', $phone)->first();
        $userByPhone2 = User::where('phone_number', '0' . $phone)->first();

        // check if phone is exist in upstream, if exist then return new dupplicate phone
        if ($farmerByPhone1 || $farmerByPhone2 || $userByPhone1 || $userByPhone2) {
            $newPhoneNumber = 'duplicate_' . rand(100, 99999) . '_' . $phoneNumber;
            return $this->getUniquePhoneNumber($newPhoneNumber);
        }

        if (is_phone_exist_in_hero($phoneNumber, $filter = false)) {
            $newPhoneNumber = 'duplicate_' . rand(100, 99999) . '_' . $phoneNumber;
            return $this->getUniquePhoneNumber($newPhoneNumber);
        }

        return $phoneNumber;
    }

    private function createUserFromFarmer($farmerDetail)
    {
        $username = Str::slug($farmerDetail->full_name, '');
        while(true) {
            if (!$this->isExistUsername($username)) {
                break;
            }
            $username = Str::slug($farmerDetail->full_name, '') . rand(10,9999);
        }

        $user = User::create([
            'name' => $farmerDetail->full_name,
            'user_type' => 'farmer',
            'username' => $username,
            'email' => null,
            'password' => Hash::make('12345678'),
            'phone_number' => $farmerDetail->phone_number,
            'email_verified_at' => null,
            'banned' => 0,
        ]);

        return $user->id;
    }

    private function isExistUsername($username)
    {
        return User::where('username', $username)->exists();
    }

    private function createCultivation($farmLand, $farmerData)
    {
        
    }
}