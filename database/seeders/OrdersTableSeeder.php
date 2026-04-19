<?php

namespace Database\Seeders;

use App\Models\Orders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Orders::create([
            'order_number' => '#1',
            'products_id' => 1,
            'customer_id' => null,
            'customer_name' => 'Customer 1',
            'customer_phone' => '123456789',
            'address' => '123 Main St',
            'quantity' => 1,
            'discount_price' => 0,
            'total_price' => 10,
            'payment_amount' => 10,
            'change_amount' => 0,
            'payment_method' => 'Cash',
            'order_type' => 'Walk in',
            'order_status' => 'Pending',
            'delivery_fee' => 0,
            'remarks' => 'No remarks',
            'cashier_id' => null,
            'admin_id' => 1,
        ]);

        Orders::create([
            'order_number' => '#1',
            'products_id' => 2,
            'customer_id' => null,
            'customer_name' => 'Customer 1',
            'customer_phone' => '123456789',
            'address' => '123 Main St',
            'quantity' => 1,
            'discount_price' => 0,
            'total_price' => 10,
            'payment_amount' => 10,
            'change_amount' => 0,
            'payment_method' => 'Cash',
            'order_type' => 'Walk in',
            'order_status' => 'Pending',
            'delivery_fee' => 0,
            'remarks' => 'No remarks',
            'cashier_id' => 1,
            'admin_id' => null,
        ]);

        Orders::create([
            'order_number' => '#3',
            'customer_id' => 2,
            'products_id' => 1,
            'customer_name' => 'Customer 2',
            'customer_phone' => '123456789',
            'address' => '123 Main St',
            'quantity' => 1,
            'discount_price' => 0,
            'total_price' => 10,
            'payment_amount' => 10,
            'change_amount' => 0,
            'payment_method' => 'Cash',
            'order_type' => 'Online',
            'order_status' => 'Pending',
            'delivery_fee' => 0,
            'remarks' => 'Pickup in ko dyan',
            'cashier_id' => null,
            'admin_id' => null,
        ]);
    }
}
