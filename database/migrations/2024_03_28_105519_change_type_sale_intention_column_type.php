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
        Schema::table('sale_intentions', function (Blueprint $table) {
            $table->double('min_price', 20, 2)->nullable()->change();
            $table->double('max_price', 20, 2)->nullable()->change();
            $table->double('quantity', 20, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
