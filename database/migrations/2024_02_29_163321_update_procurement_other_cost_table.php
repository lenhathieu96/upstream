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
        Schema::table('procurement_other_cost', function (Blueprint $table) {
            $table->dropColumn('actual_qty');
            $table->dropColumn('actual_sub_total');
            $table->string('item');
            $table->double('quantity', 20);
            $table->double('rate', 20);
            $table->double('sub_total', 20);
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
