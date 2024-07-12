<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Staff;
use App\Models\FarmerDetails;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $staffs = Staff::select('id as staff_id')->get()->makeHidden('name')->toArray();
        $farmers = FarmerDetails::select('id as farmer_id')->get()->makeHidden(['avatar_url', 'id_proof_photo_url','full_address'])->toArray();
        $staffNo = 0;
        $farmerNo = 0;

        DB::table('fa_accounts')->insert(array_map(function ($staff) use (&$staffNo) {
            $staffCode = '22' . str_pad(++$staffNo, 10, '0', STR_PAD_LEFT);
            return array_merge($staff, [
                'typee' => 2,
                'acc_type' => 'FOA',
                'acc_no' => $staffCode,
                'loan_acc_no' => $staffCode
            ]);
        }, $staffs));

        DB::table('fa_accounts')->insert(array_map(function ($farmer) use (&$farmerNo) {
            $farmerCode = '11' . str_pad(++$farmerNo, 10, '0', STR_PAD_LEFT);
            return array_merge($farmer, [
                'typee' => 3,
                'acc_type' => 'FRA',
                'acc_no' => $farmerCode,
                'loan_acc_no' => $farmerCode
            ]);
        }, $farmers));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
