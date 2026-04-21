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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');
            $table->string('discount_code')->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->integer('total_quantity');
            $table->decimal('total_price', 12, 2);
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_address');
            $table->string('note')->nullable();
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod');
            $table->enum('status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
