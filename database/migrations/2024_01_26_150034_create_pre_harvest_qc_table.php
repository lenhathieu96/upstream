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
        Schema::create('pre_harvest_qc', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('description');
            $table->string('unit')->nullable();
            $table->float('min_standard')->nullable();
            $table->float('max_standard')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_harvest_qc');
    }
};
