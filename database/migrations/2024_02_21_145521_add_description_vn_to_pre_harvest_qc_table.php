<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data = [
            'color' => 'Màu sắc',
            'odour' => 'Mùi',
            'mouldy' => 'Mốc',
            'moisture' => 'Độ ẩm',
            'average_length_of_whole_grain' => 'Độ dài hạt lúa',
            'black_damage_grain' => 'Hạt đen',
            'crack_grain' => 'Hạt bể',
            'impurities' => '',
            'empty_shell_grain' => 'Hạt lép',
            'green_grain' => 'Hạt xanh',
            'undeveloped_kernel' => 'Hạt lừng',
            'purity' => 'Độ thuần',
        ];
        foreach ($data as $key => $value) {
            DB::table('pre_harvest_qc')
                ->where('key', $key)
                ->update(['description_vn' => $value]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_harvest_qc', function (Blueprint $table) {
            //
        });
    }
};
