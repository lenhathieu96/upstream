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
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('vehicle_types');
            $table->string('license_number');
            $table->string('driver_name');
            $table->string('driver_phone_number');
            $table->integer('capacity');
            $table->string('status')->nullable();
            $table->string('driver_photo')->nullable();
            $table->string('driver_id_photo')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('booking_code')->unique()->index();
            $table->dateTime('booking_date');
            $table->string('photo')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_types');
    }
};
