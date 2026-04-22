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
            $table->string('order_number');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('custom_entry')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('discount_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();
            $table->string('payment_method');
            $table->string('order_type');
            $table->string('order_status')->default('pending');
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
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
