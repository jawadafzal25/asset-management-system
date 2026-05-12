<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Seed all permissions first (your module)
        $this->call(PermissionSeeder::class);

        // Step 2: Your teammate seeds roles using the permissions above
        // $this->call(RoleSeeder::class);

        // Step 3: Main admin seeder
        // $this->call(SuperAdminSeeder::class);
    }
}
