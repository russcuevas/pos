<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = \App\Models\Orders::first();

        if ($order) {
            // 1. Customer Message (customer_id is NOT NULL, allowed is NULL)
            \App\Models\OrdersChats::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'message' => "Hello, I have a question about my order.",
                'allowed' => null,
                'created_at' => now()->subMinutes(10),
            ]);

            // 2. Admin/Cashier Reply (customer_id is NULL, allowed is NOT NULL)
            \App\Models\OrdersChats::create([
                'order_id' => $order->id,
                'customer_id' => null,
                'message' => "Hi! We are currently processing it. It will be ready soon.",
                'allowed' => 1,
                'created_at' => now()->subMinutes(5),
            ]);

            // 3. Another Customer Message
            \App\Models\OrdersChats::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'message' => "Okay, thank you!",
                'allowed' => null,
                'created_at' => now(),
            ]);
        }
    }
}
