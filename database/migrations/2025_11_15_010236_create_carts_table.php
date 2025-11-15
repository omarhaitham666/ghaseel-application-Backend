<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('admin_status')->nullable();
            $table->string('order_status')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();

            // الفهارس
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
