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
        Schema::create('farm_lands', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('farm_name');
            $table->double('total_land_holding',20,2);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->double('farm_land_ploting',20,2);
            $table->string('actual_area');
            $table->longText('farm_photo')->nullable();
            $table->string('land_ownership');
            $table->string('srp_score')->nullable();
            $table->string('carbon_index')->nullable();
            $table->longText('approach_road')->nullable();
            $table->string('land_topology')->nullable();
            $table->string('land_gradient')->nullable();
            $table->longText('land_document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_lands');
    }
};
