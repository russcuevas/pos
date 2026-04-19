<?php

namespace Database\Seeders;

use App\Models\Admins;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admins::create([
            'fullname' => 'Admin Russel',
            'email' => 'russelcuevas0@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
    }
}
