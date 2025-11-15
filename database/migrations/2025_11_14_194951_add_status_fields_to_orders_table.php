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
            $table->enum('admin_status', ['pending', 'accepted', 'rejected'])
            ->default('pending');

        // حالة تنفيذ الطلب من خلال الفريق
        $table->enum('order_status', ['processing', 'completed', 'delivered'])
            ->nullable();

        // السعر النهائي بعد مراجعة الأدمن
        $table->decimal('final_price', 10, 2)->nullable();

        // سبب الرفض
        $table->text('rejection_reason')->nullable();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
              $table->dropColumn([
            'admin_status',
            'order_status',
            'final_price',
            'rejection_reason'
        ]);
        
        });
    }
};
