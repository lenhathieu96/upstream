<?php

use App\Models\FarmerDetails;
use App\Models\Uploads;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->string('user_type')->after('file_name')->nullable();
        });


        // process code
        $farmerDetails = FarmerDetails::whereNotNull('farmer_photo')->get();
        foreach ($farmerDetails as $farmerDetail) {
            Uploads::where('id', $farmerDetail->farmer_photo)->update(['user_type' => 'farmer', 'user_id' => $farmerDetail->id]);
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
