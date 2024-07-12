<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\Services\Common\UniqueCodeService;
use App\Models\VehicleType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (['Boat', 'Truck', 'Mini Van'] as $type) {
            VehicleType::create([
                'name' => $type,
                'code' => UniqueCodeService::generate('VT'),
                'slug' => Str::slug($type)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
