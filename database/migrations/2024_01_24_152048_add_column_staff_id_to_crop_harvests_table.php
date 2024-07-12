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
        Schema::table('crop_harvests', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crop_harvests', function (Blueprint $table) {
            $table->dropColumn('staff_id');
        });
    }
};
