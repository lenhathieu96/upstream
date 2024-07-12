<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Season::create(['code'=> 'winter_spring', 'name'=> 'Winter Spring']);
        Season::create(['code'=> 'summer_autumn', 'name'=> 'Summer Autumn']);
        Season::create(['code'=> 'autumn_winter', 'name'=> 'Autumn Winter']);
        Season::create(['code'=> 'other', 'name'=> 'Other']);
    }
}
