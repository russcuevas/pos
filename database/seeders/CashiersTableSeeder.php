<?php

namespace Database\Seeders;

use App\Models\Cashiers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashiersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cashiers::create([
            'fullname' => 'Cashier Russel',
            'email' => 'russelcuevas00@gmail.com',
            'password' => bcrypt('123456789'),
            'status' => 'active',
        ]);

        Cashiers::create([
            'fullname' => 'Cashier Russel 2',
            'email' => 'russelvincentcuevas26@gmail.com',
            'password' => bcrypt('123456789'),
            'status' => 'inactive',
        ]);
    }
}
