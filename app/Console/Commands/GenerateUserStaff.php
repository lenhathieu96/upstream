<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateUserStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-staff';

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
        // already run in server
        return null;

        $firstNameArr = ['huy', 'khang', 'bao', 'phuc', 'anh', 'khoa', 'phat', 'dat', 'khoi', 'an', 'nam', 'quan', 'hoang', 'hieu', 'tri', 'tai'];
        $lastNameArr = ['nguyen', 'tran', 'phan', 'le', 'bui', 'dang', 'pham', 'hoang', 'huynh', 'truong', 'ngo'];
        
        for ($i=1; $i<=20; $i++) {
            $firstNameKey = array_rand($firstNameArr);
            $firstName = $firstNameArr[$firstNameKey ];

            $lastNameKey = array_rand($lastNameArr);
            $lastName = $lastNameArr[$lastNameKey ];
            $rand = rand(10,99);
            
            $phoneNumber = str_replace('+', '0', fake()->unique()->e164PhoneNumber());

            // Username
            $user = new User();
            $user->name = $firstName . ' ' . $lastName;
            $user->user_type = 'staff';
            $user->username = $firstName . $lastName . $rand;
            $user->email = $firstName . $lastName . $rand . '@gmail.com';
            $user->password = Hash::make('12345678');
            $user->phone_number = $phoneNumber;
            $user->save();

            // Staff
            $staff = new Staff();
            $staff->user_id = $user->id;
            $staff->first_name = $firstName;
            $staff->last_name = $lastName;
            $staff->gender = 'Male';
            $staff->email = $user->email;
            $staff->phone_number = $phoneNumber;
            $staff->status = 'active';
            $staff->save();
        }
    }
}
