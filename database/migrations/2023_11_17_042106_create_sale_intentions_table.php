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
        Schema::create('sale_intentions', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->integer('farm_land_id');
            $table->integer('cultivation_id');
            $table->integer('season_id');
            $table->string('variety');
            $table->string('sowing_date');
            $table->string('product_id');
            $table->double('min_price',8,2);
            $table->double('max_price',8,2);
            $table->string('date_for_harvest');
            $table->string('aviable_date');
            $table->string('grade');
            $table->string('age_of_crop');
            $table->string('quality_check');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_intentions');
    }
};
