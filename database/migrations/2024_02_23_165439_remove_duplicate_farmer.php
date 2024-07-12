<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FarmerDetails;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $farmers = FarmerDetails::whereIn('farmer_code', ['A002034266', 'A002034265'])->get();
        $farmers->each(function (FarmerDetails $farmer) {
            $farmer->faAccount()->delete();
            $farmer->user()->delete();
            $farmer->cultivation_crop()->delete();
            $farmer->family_info()->delete();
            $farmer->asset_info()->delete();
            $farmer->bank_info()->delete();
            $farmer->animal_husbandry()->delete();
            $farmer->certificate_info()->delete();
            $farmer->finance_info()->delete();
            $farmer->farm_equipment()->delete();
            $farmer->distributions()->delete();
            $farmer->transactions()->delete();
            $farmer->farm_lands()->delete();
            $farmer->delete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
