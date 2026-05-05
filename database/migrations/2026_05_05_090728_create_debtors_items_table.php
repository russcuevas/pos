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
        Schema::create('debtors_items', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->nullable();
            $table->foreignId('debtor_id')->constrained('debtors_accounts')->onDelete('cascade');
            $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('custom_entry')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->decimal('custom_cost', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debtors_items');
    }
};
