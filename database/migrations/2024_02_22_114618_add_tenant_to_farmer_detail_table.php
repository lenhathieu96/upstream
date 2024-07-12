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
        Schema::table('farmer_details', function (Blueprint $table) {
            $table->string('tenant')->after('cooperative_id')->nullable();
        });

        Schema::table('cultivations', function (Blueprint $table) {
            $table->string('season_id')->nullable()->change();
            $table->string('crop_id')->nullable()->change();
            $table->string('crop_variety')->nullable()->change();
            $table->string('sowing_date')->nullable()->change();
            $table->string('expect_date')->nullable()->change();
            $table->string('est_yield')->nullable()->change();
        });

        Schema::table('farm_lands', function (Blueprint $table) {
            $table->string('total_land_holding')->nullable()->change();
            $table->string('actual_area')->nullable()->change();
            $table->string('land_ownership')->nullable()->default('Own')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmer_details', function (Blueprint $table) {
            //
        });
    }
};
