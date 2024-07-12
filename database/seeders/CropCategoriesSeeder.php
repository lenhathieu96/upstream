<?php

namespace Database\Seeders;

use App\Models\CropCategory;
use App\Models\Season;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CropCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CropCategory::create(['code'=> 'field_crop', 'name'=> 'Field Crop']);
        CropCategory::create(['code'=> 'oil_seed', 'name'=> 'Oil Seed']);
        CropCategory::create(['code'=> 'spices', 'name'=> 'Spices']);
        CropCategory::create(['code'=> 'fruits', 'name'=> 'Fruits']);
        CropCategory::create(['code'=> 'vegetables', 'name'=> 'Vegetables']);
        CropCategory::create(['code'=> 'fiber', 'name'=> 'Fiber']);
    }
}
