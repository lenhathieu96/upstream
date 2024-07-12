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
        Schema::create('product_losses', function (Blueprint $table) {
            $table->id();
            $table->integer('carbon_emissions_id');
            $table->double('yield_before_harvest',0,2);
            $table->double('harvesting_losses',0,2);
            $table->double('drying_losses',0,2);
            $table->double('storing_losses',0,2);
            $table->double('milling_losses',0,2);
            $table->double('food_losses',0,2);
            $table->double('husk',0,2);
            $table->double('bran',0,2);
            $table->double('rice_straw',0,2);
            $table->double('rice_husk',0,2);
            $table->double('rice_bran',0,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_losses');
    }
};
