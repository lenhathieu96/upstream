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
        Schema::create('crop_harvests', function (Blueprint $table) {
            $table->id();
            $table->string('crop_harvest_code')->unique();
            $table->double('total_amount')->default(0);
            $table->timestamps();
        });

        Schema::create('crop_harvest_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_harvest_id');
            $table->foreign('crop_harvest_id')
                ->references('id')
                ->on('crop_harvests');
            $table->unsignedBigInteger('cultivation_id');
            $table->foreign('cultivation_id')
                ->references('id')
                ->on('cultivations');
            $table->float('approx_harvest_qty');
            $table->double('price_per_unit');
            $table->double('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_harvest_details');

        Schema::dropIfExists('crop_harvests');
    }
};
