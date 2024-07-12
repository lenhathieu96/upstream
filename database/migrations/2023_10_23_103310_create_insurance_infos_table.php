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
        Schema::create('insurance_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('life_insurance')->nullable();
            $table->string('provider_life_insurance')->nullable();
            $table->integer('life_insurance_amount')->nullable();
            $table->string('life_insurance_enrolled_date')->nullable();
            $table->string('life_insurance_end_date')->nullable();
            $table->string('health_insurance')->nullable();
            $table->string('provider_health_insurance')->nullable();
            $table->integer('health_insurance_amount')->nullable();
            $table->string('health_insurance_enrolled_date')->nullable();
            $table->string('health_insurance_end_date')->nullable();
            $table->string('crop_insurance')->nullable();
            $table->string('provider_crop_insurance')->nullable();
            $table->string('crop_insured')->nullable();
            $table->integer('no_of_area_insured')->nullable();
            $table->string('crop_insurance_enrolled_date')->nullable();
            $table->string('crop_insurance_end_date')->nullable();
            $table->string('social_insurance')->nullable();
            $table->string('provider_social_insurance')->nullable();
            $table->string('social_insurance_enrolled_date')->nullable();
            $table->string('social_insurance_end_date')->nullable();
            $table->string('other_insurance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_infos');
    }
};
