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
        Schema::create('asset_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('housing_ownership')->nullable();
            $table->string('house_type')->nullable();
            $table->string('consumer_electronic')->nullable();
            $table->string('vehicle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_infos');
    }
};
