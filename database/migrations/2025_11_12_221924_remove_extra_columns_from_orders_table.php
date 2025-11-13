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
        Schema::table('orders', function (Blueprint $table) {
            //
             $table->dropColumn([
                'order_number',
                'total_amount',
                'status',
                'address',
                'phone',
                'notes',
                'created_at',
                'updated_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
             $table->string('order_number')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        
        });
    }
};
