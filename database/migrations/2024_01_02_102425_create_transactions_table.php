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
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 45)->unique();
            $table->string('name', 45);
            $table->timestamps();

            $table->index('code');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribution_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('farmer_id');
            $table->string('transaction_type', 45)->nullable();
            $table->double('transaction_amount', 20, 2)->nullable();
            $table->double('balance_amount', 20, 2)->nullable();
            $table->double('initial_balance', 20, 3)->nullable();
            $table->foreign('account_id')
                ->references('id')
                ->on('staff');
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmer_details');
            $table->foreign('distribution_id')->references('id')->on('distributions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('transactions');
    }
};
