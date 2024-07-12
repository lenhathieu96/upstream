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
        Schema::create('animal_husbandries', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('farm_animal')->nullable();
            $table->integer('animal_count')->nullable();
            $table->string('fodder')->nullable();
            $table->string('animal_housing')->nullable();
            $table->string('revenue')->nullable();
            $table->string('breed_name')->nullable();
            $table->string('animal_for_growth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_husbandries');
    }
};
