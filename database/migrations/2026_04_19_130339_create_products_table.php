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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('product_name');
            $table->string('product_description');
            $table->string('product_image');
            $table->decimal('selling_price', 10, 2);
            $table->decimal('supplier_price', 10, 2);
            $table->integer('quantity')->nullable();
            $table->integer('whole_sale_qty');
            $table->decimal('whole_sale_price', 10, 2);
            $table->boolean('is_show')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
