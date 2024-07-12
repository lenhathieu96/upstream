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
        Schema::create('vendor_procurements', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_procurement_code')->unique()->index();
            $table->timestamp('transaction_date');
            $table->unsignedBigInteger('season_id');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->string('order_code');
            $table->longText('photos')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_procurement_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_procurement_id');
            $table->foreign('vendor_procurement_id')
                ->references('id')
                ->on('vendor_procurements');
            $table->string('product_id');
            $table->string('product_name');
            $table->unsignedBigInteger('sale_intention_id');
            $table->longText('photos')->nullable();
            $table->float('order_quantity');
            $table->timestamps();
        });

        Schema::create('post_harvest_qc', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('description');
            $table->string('unit')->nullable();
            $table->float('min_standard')->nullable();
            $table->float('max_standard')->nullable();
            $table->boolean('is_published')->default(true);
            $table->tinyInteger('type')->default(0);
            $table->timestamps();
        });

        Schema::create('vendor_procurement_qc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_detail_id');
            $table->foreign('vendor_detail_id')
                ->references('id')
                ->on('vendor_procurement_details');
            $table->unsignedBigInteger('post_harvest_qc_id');
            $table->foreign('post_harvest_qc_id')
                ->references('id')
                ->on('post_harvest_qc');
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_procurements');
    }
};
