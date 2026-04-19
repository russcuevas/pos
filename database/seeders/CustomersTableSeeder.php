<?php

namespace Database\Seeders;

use App\Models\Customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customers::create([
            'fullname' => 'Customer Russel',
            'email' => 'cuevas0@gmail.com',
            'password' => bcrypt('123456789'),
            'phone_number' => '09123456789',
            'address' => 'Address 1',
            'is_verified' => true,
        ]);

        Customers::create([
            'fullname' => 'Customer Russel Vincent',
            'email' => 'cuevas00@gmail.com',
            'password' => bcrypt('123456789'),
            'phone_number' => '09123456789',
            'address' => 'Address 2',
            'is_verified' => false,
        ]);
    }
}
