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
        Schema::create('family_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('education')->nullable();
            $table->string('marial_status')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('no_of_family')->nullable();
            $table->longText('total_child_under_18')->nullable();
            $table->longText('total_child_under_18_going_school')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_infos');
    }
};
