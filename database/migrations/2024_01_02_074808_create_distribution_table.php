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
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')
                ->references('id')
                ->on('staff');
            $table->unsignedBigInteger('farmer_id');
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmer_details');
            $table->timestamp('distribution_date')->default(now());
            $table->double('total_amount');
            $table->timestamps();
        });

        Schema::create('distribution_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->string('category_name');
            $table->bigInteger('quantity');
            $table->bigInteger('available_stocks');
            $table->double('price_per_unit');
            $table->double('sub_total');
            $table->string('unit');
            $table->unsignedBigInteger('distribution_id');
            $table->foreign('distribution_id')
                ->references('id')
                ->on('distributions');
            $table->timestamps();
        });

        Schema::create('distribution_balance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->unsignedBigInteger('farmer_id');
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmer_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');

        Schema::dropIfExists('distribution_details');

        Schema::dropIfExists('distribution_balance');
    }
};
