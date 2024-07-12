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
        Schema::create('srp_integrated_pest_management', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->integer('cultivation_id');
            $table->integer('staff_id');
            $table->integer('srp_id');
            $table->string('question');
            $table->string('answer');
            $table->integer('score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_r_p_integrated_pest_management');
    }
};
