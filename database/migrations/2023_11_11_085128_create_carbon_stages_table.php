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
        Schema::create('carbon_stages', function (Blueprint $table) {
            $table->id();
            $table->integer('carbon_emissions_id');
            $table->double('crop_establish',0,2);
            $table->double('water_soil',0,2);
            $table->double('fetilizer',0,2);
            $table->double('equipment',0,2);
            $table->double('harvesting',0,2);
            $table->double('straw_management',0,2);
            $table->double('drying',0,2);
            $table->double('storing',0,2);
            $table->double('milling',0,2);
            $table->double('packaging',0,2);
            $table->double('transports',0,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carbon_stages');
    }
};
