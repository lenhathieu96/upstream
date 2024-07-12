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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('procurement_code')->unique()->index();
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings');
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses');
            $table->timestamp('transaction_date');
            $table->double('total_amount');
            $table->timestamps();
        });

        Schema::create('procurement_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_id');
            $table->foreign('procurement_id')
                ->references('id')
                ->on('procurements');
            $table->unsignedBigInteger('farmer_id');
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmer_details');
            $table->unsignedBigInteger('crop_harvest_detail_id');
            $table->foreign('crop_harvest_detail_id')
                ->references('id')
                ->on('crop_harvest_details');
            $table->float('actual_qty');
            $table->double('actual_sub_total');
            $table->timestamps();
        });

        Schema::create('procurement_other_cost', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_id');
            $table->foreign('procurement_id')
                ->references('id')
                ->on('procurements');
            $table->float('actual_qty');
            $table->double('actual_sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
