<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {

        // احذف status لو كان موجود
        if (Schema::hasColumn('orders', 'status')) {
            $table->dropColumn('status');
        }

        // admin_status
        if (!Schema::hasColumn('orders', 'admin_status')) {
            $table->enum('admin_status', ['pending','accepted','rejected'])->default('pending');
        }

        // order_status
        if (!Schema::hasColumn('orders', 'order_status')) {
            $table->enum('order_status', ['processing','completed','delivered'])->nullable();
        }

        // rejection_reason
        if (!Schema::hasColumn('orders', 'rejection_reason')) {
            $table->text('rejection_reason')->nullable();
        }

        // final_price
        if (!Schema::hasColumn('orders', 'final_price')) {
            $table->decimal('final_price', 10, 2)->nullable();
        }
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
