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
        Schema::create('crop_calendars', function (Blueprint $table) {
            $table->id();
            $table->integer('crop_info_id')->unsigned();
            $table->string('calendar_name');
            $table->integer('country_id');
            $table->integer('province_id');
            $table->integer('district_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_calendars');
    }
};
