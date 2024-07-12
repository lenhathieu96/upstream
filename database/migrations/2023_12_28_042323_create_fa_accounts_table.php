<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fa_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('acc_no')->nullable()->unique();
            $table->string('acc_type')->nullable();
            $table->tinyInteger('typee')->nullable();
            $table->timestamp('acc_open_date')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('farmer_id')->nullable();
            $table->foreign('farmer_id')->references('id')->on('farmer_details');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->double('cash_balance', 20, 2)->default(0);
            $table->double('credit_balance', 20, 2)->default(0);
            $table->double('balance', 20, 2)->default(0);
            $table->double('dist_balance', 20, 2)->default(0);
            $table->string('loan_acc_no')->nullable();
            $table->double('loan_amount', 20, 2)->default(0);
            $table->double('outstanding_amount', 20, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_accounts');
    }
};
