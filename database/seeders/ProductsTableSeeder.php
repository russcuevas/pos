<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Products::create([
            'product_code' => 'P001',
            'product_name' => 'Product 1',
            'product_description' => 'Product 1 Description',
            'product_image' => 'product1.jpg',
            'selling_price' => 10,
            'supplier_price' => 5,
            'quantity' => 100,
            'whole_sale_qty' => 10,
            'whole_sale_price' => 50,
            'is_show' => true,
        ]);

        Products::create([
            'product_code' => 'P002',
            'product_name' => 'Product 2',
            'product_description' => 'Product 2 Description',
            'product_image' => 'product2.jpg',
            'selling_price' => 20,
            'supplier_price' => 10,
            'quantity' => 200,
            'whole_sale_qty' => 20,
            'whole_sale_price' => 50,
            'is_show' => false,
        ]);
    }
}
