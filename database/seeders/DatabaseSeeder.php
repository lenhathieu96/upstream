<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //\App\Models\User::factory(10)->create();

        // supper admin
        $admin = new User();
        $admin->name = "Supper Admin";
        $admin->user_type = "super_admin";
        $admin->username = "farmangel";
        $admin->email = "admin@farm-angel.com";
        $admin->password = Hash::make('123456789abc');
        $admin->phone_number = "123456789";
        $admin->email_verified_at = "";
        $admin->save();

        // staff
        // $staff = new Staff();
        // $staff->user_id = $admin->id;
        // $staff->first_name = 'Supper';
        // $staff->last_name = 'Admin';
        // $staff->gender = 'Male';
        // $staff->email = 'admin@farm-angel.com';
        // $staff->phone_number = '123456789';
        // $staff->status = 'active';
        // $staff->save();

        // \App\Models\User::factory()->create([
        //     'name' => 'Supper Admin',
        //     'username' => 'farmangel',
        //     'email' => 'farmangel@farm-angel.com',
        //     'password' => Hash::make('123456789abc'),
        //     'phone_number' => '',
        //     'user_type'=> 'super_admin',
        // ]);

        $this->call([
            SeasonsSeeder::class,
            CropCategoriesSeeder::class,
        ]);
    }
}
