<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pre_harvest_qc')->insert(array_map(function ($data) {
            return [
                'key' => Str::slug($data['description'], '_'),
                'description' => $data['description'],
                'unit' => $data['unit'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, [
            [
                'description' => 'Color',
                'min_standard' => null,
                'max_standard' => null,
                'unit' => ''
            ], [
                'description' => 'Odour',
                'min_standard' => null,
                'max_standard' => null,
                'unit' => ''
            ], [
                'description' => 'Mouldy',
                'min_standard' => null,
                'max_standard' => null,
                'unit' => ''
            ],[
                'description' => 'Moisture ',
                'min_standard' => null,
                'max_standard' => 27,
                'unit' => '%'
            ], [
                'description' => 'Average length of whole grain',
                'min_standard' => 7,
                'max_standard' => null,
                'unit' => 'mm'
            ], [
                'description' => 'Black, damage grain',
                'min_standard' => null,
                'max_standard' => 10,
                'unit' => '%'
            ], [
                'description' => 'Crack Grain',
                'min_standard' => null,
                'max_standard' => 6,
                'unit' => '%'
            ],[
                'description' => 'Impurities',
                'min_standard' => null,
                'max_standard' => 1,
                'unit' => '%'
            ], [
                'description' => 'Empty shell grain',
                'min_standard' => null,
                'max_standard' => 5,
                'unit' => '%'
            ], [
                'description' => 'Green grain',
                'min_standard' => null,
                'max_standard' => 5,
                'unit' => '%'
            ], [
                'description' => 'Undeveloped kernel',
                'min_standard' => null,
                'max_standard' => null,
                'unit' => '%'
            ], [
                'description' => 'Purity',
                'min_standard' => 75,
                'max_standard' => null,
                'unit' => '%'
            ],
        ]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
