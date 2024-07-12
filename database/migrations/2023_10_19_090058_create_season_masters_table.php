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
        Schema::create('season_masters', function (Blueprint $table) {
            $table->id();
            $table->string('season_code');
            $table->date('from_period');
            $table->date('to_period');
            $table->string('status')->default('active')->comment('active,inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('season_masters');
    }
};
