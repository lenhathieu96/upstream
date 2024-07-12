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
        Schema::create('finance_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->string('loan_taken_last_year')->nullable();
            $table->string('loan_taken_from')->nullable();
            $table->integer('loan_amount')->nullable();
            $table->string('purpose')->nullable();
            $table->integer('loan_interest')->nullable();
            $table->string('interest_period')->nullable();
            $table->string('security')->nullable();
            $table->integer('loan_repayment_amount')->nullable();
            $table->string('loan_repayment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_infos');
    }
};
