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
            $table->foreignId('location_id')->constrained('user_locations')->cascadeOnDelete();
            $table->string('delivery_type')->default('normal');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropForeign(['location_id']);
            $table->dropColumn([
                'location_id',
                'delivery_type',
                'pickup_date',
                'pickup_time',
                'delivery_date',
                'delivery_time',
                'notes',
                'status',
                'total_price',
                'created_at',
                'updated_at',
            ]);
        
        });
    }
};
