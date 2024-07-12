<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable(); 
            $table->text('capacity');
            $table->string('type')->comment('Internal/External/Procurement Center/Distribution Center/Cooperative')->nullable(); 
            $table->string('lat')->nullable(); 
            $table->string('lng')->nullable(); 
            $table->text('address')->nullable();
            $table->string('status')->comment('active, inactive'); 
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff');
            $table->timestamps();
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->string('user_type')->after('user_id')->nullable();
        });

        // change default user_type of staff table is 'Staff'
        DB::table('staff')->update(['user_type' => 'staff']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
