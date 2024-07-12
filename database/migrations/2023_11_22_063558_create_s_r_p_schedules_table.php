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
        Schema::create('srp_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('srp_id');
            $table->string('name_action');
            $table->timestamps('date_action');
            $table->integer('is_finished')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_r_p_schedules');
    }
};
