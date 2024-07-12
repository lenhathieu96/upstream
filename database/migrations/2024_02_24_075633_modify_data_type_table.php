<?php

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
        Schema::table('cultivations', function (Blueprint $table) {
            $table->integer('season_id')->nullable()->change();
            $table->integer('crop_id')->nullable()->change();
        });

        Schema::table('farm_lands', function (Blueprint $table) {
            $table->double('total_land_holding', 20, 2)->nullable()->change();
            $table->double('actual_area', 20, 2)->nullable()->change();
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
