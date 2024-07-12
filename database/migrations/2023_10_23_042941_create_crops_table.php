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
        Schema::create('cultivations', function (Blueprint $table) {
            $table->id();
            $table->integer('farm_land_id');
            $table->integer('season_id');
            $table->integer('crop_id');
            $table->string('crop_variety');
            $table->string('sowing_date');
            $table->string('expect_date');
            $table->string('est_yield');
            $table->longText('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivations');
    }
};
