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
        Schema::create('srps', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->integer('staff_id');
            $table->integer('farm_land_id');
            $table->integer('crop_id');
            $table->integer('season_id');
            $table->integer('score');
            $table->string('sowing_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srps');
    }
};
