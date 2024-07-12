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
        Schema::create('crop_calendar_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('crop_calendar_id');
            $table->string('activity_title')->nullable();
            $table->unsignedInteger('crop_activity_id');
            $table->unsignedInteger('crop_stage_id');
            $table->unsignedInteger('duration');
            $table->string('duration_uom');
            $table->text('activity_description')->nullable();
            $table->unsignedInteger('repetition');
            $table->unsignedInteger('lead_time');
            $table->tinyInteger('is_base_on_sowing_date')->nullable()->default(0);
            $table->string('status')->nullable()->default('inactive')->comment('active, inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_calendar_details');
    }
};
