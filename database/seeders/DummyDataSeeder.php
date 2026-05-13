<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ek Dummy Admin (User) dalte hain (Jiski ID 1 hogi)
        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Ek Dummy Employee dalte hain (Jiski ID 1 hogi)
        DB::table('employees')->insert([
            'name' => 'Ali Raza',
            'email' => 'ali@test.com',
            // Agar employee table mein aur koi lazmi column hai toh yahan add kar lein
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Ek Dummy Asset (Laptop) dalte hain (Jiski ID 1 hogi)
        DB::table('assets')->insert([
            'name' => 'Dell Latitude 5420',
            'quantity' => 5,           // Humne 5 laptops add kiye hain
            'status' => 'Available',   // Status available rakha hai
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}