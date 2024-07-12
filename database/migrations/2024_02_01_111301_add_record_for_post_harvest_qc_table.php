<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();
        DB::table('post_harvest_qc')->insert(array_map(function ($data) use ($now) {
            return [
                'key' => Str::slug($data['description'], '_'),
                'description' => $data['description'],
                'unit' => $data['unit'] ?? '',
                'type' => $data['type'] ?? '',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }, [
            [
                'description' => 'Moisture',
                'unit' => '%',
                'type' => 1
            ], [
                'description' => 'Number of Immature Grains',
                'unit' => '',
                'type' => 1
            ], [
                'description' => 'Number of Shrunken, shrivelled grain',
                'unit' => '',
                'type' => 1
            ], [
                'description' => 'Number of Damaged grain',
                'unit' => '',
                'type' => 1
            ], [
                'description' => 'Number of Foreign matters',
                'unit' => '',
                'type' => 1
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
