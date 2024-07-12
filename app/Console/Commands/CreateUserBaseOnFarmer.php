<?php

namespace App\Console\Commands;

use App\Models\FarmerDetails;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserBaseOnFarmer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user-base-on-farmer';

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
        // finish create user from farmer
        return null;

        // update username with rule: has no space
        $this->updateUsername();

        // Create user from farmer
        foreach (FarmerDetails::all() as $farmerDetail) {
            $this->createUserFromFarmer($farmerDetail);
            $this->info('create user: ' . $farmerDetail->full_name);
        }

        $this->info('create users finish');
    }

    private function updateUsername()
    {
        foreach (User::all() as $user) {
            if (str_contains($user->username, ' ')) {
                $username = Str::slug($user->username, '');
                while(true) {
                    if (!$this->isExistUsername($username)) {
                        break;
                    }
                    $username = Str::slug($user->username, '') . rand(10,9999);
                }

                $user->username = $username;
                $user->save();
            }
        }
    }

    private function createUserFromFarmer($farmerDetail)
    {
        // Str:slug function will make value lowercase, remove special character
        $username = Str::slug($farmerDetail->full_name, '');
        while(true) {
            if (!$this->isExistUsername($username)) {
                break;
            }
            $username = Str::slug($farmerDetail->full_name, '') . rand(10, 999);
        }

        $phoneNumber = $farmerDetail->phone_number;
        while(true) {
            if (empty($phoneNumber)) {
                $phoneNumber = 'empty_phone_' . uniqid();
            }

            if (!$this->isExistPhoneNumber($phoneNumber)) {
                break;
            }

            $phoneNumber = 'duplicate_' . rand(10, 9999) . '_' . $farmerDetail->phone_number;
            if (!$this->isExistPhoneNumber($phoneNumber)) {
                break;
            }

            $phoneNumber = 'duplicate_' . uniqid() . '_' . $farmerDetail->phone_number;
        }

        $user = User::create([
            'name' => $farmerDetail->full_name,
            'user_type' => 'farmer',
            'username' => $username,
            'email' => null,
            'password' => Hash::make('12345678'),
            'phone_number' => $phoneNumber,
            'email_verified_at' => null,
            'banned' => 0,
        ]);

        $farmerDetail->user_id = $user->id;
        $farmerDetail->save();
    }

    private function isExistUsername($username)
    {
        return User::where('username', $username)->exists();
    }

    private function isExistPhoneNumber($phoneNumber)
    {
        // check for empty phone
        if (str_contains($phoneNumber, 'empty_phone')) {
            return  User::where('phone_number', $phoneNumber)->exists();
        }

        // already modify phone number
        if (str_contains($phoneNumber, 'duplicate')) {
            return  User::where('phone_number', $phoneNumber)->exists();
        }

        // default flow
        $phone = (int) $phoneNumber;
        $userByPhone1 = User::where('phone_number', $phone)->exists();
        $userByPhone2 = User::where('phone_number', '0' . $phone)->exists();

        return $userByPhone1 || $userByPhone2;
    }
}
