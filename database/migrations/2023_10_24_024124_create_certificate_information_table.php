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
        Schema::create('certificate_information', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('is_certified_farmer')->nullable();
            $table->string('certification_type')->nullable();
            $table->integer('year_of_ics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_information');
    }
};
