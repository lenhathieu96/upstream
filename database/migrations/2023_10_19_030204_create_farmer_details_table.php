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
        Schema::create('farmer_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('staff_id');
            $table->string('enrollment_date');
            $table->string('enrollment_place');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('identity_proof');
            $table->integer('country');
            $table->integer('province');
            $table->integer('district');
            $table->integer('commune');
            $table->string('village');
            $table->string('lng');
            $table->string('lat');
            $table->string('proof_no');
            $table->string('gender');
            $table->string('dob');
            $table->string('farmer_code');
            $table->longText('farmer_photo');
            $table->longText('id_proof_photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_details');
    }
};
