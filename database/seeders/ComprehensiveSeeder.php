<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Users
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'user1@example.com'], [
            'name' => 'Test User 1',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'user2@example.com'], [
            'name' => 'Test User 2',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Seed Categories
        Category::updateOrCreate(['name' => 'Laptops'], ['description' => 'Computing devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Monitors'], ['description' => 'Display devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Keyboards'], ['description' => 'Input devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Mice'], ['description' => 'Pointing devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Printers'], ['description' => 'Printing devices', 'status' => true]);

        // Seed Employees
        Employee::updateOrCreate(['email' => 'john.doe@example.com'], [
            'name' => 'John Doe',
            'father_name' => 'James Doe',
            'contact_info' => '03001234567',
            'address' => '123 Main St',
            'designation' => 'Software Engineer',
            'joining_date' => '2026-01-15',
            'salary' => 50000,
            'status' => 'active',
            'department_id' => 2
        ]);

        Employee::updateOrCreate(['email' => 'jane.smith@example.com'], [
            'name' => 'Jane Smith',
            'father_name' => 'Robert Smith',
            'contact_info' => '03009876543',
            'address' => '456 Oak Ave',
            'designation' => 'HR Manager',
            'joining_date' => '2026-01-20',
            'salary' => 45000,
            'status' => 'active',
            'department_id' => 3
        ]);

        Employee::updateOrCreate(['email' => 'mike.johnson@example.com'], [
            'name' => 'Mike Johnson',
            'father_name' => 'William Johnson',
            'contact_info' => '03005555555',
            'address' => '789 Pine Rd',
            'designation' => 'Finance Manager',
            'joining_date' => '2026-01-10',
            'salary' => 55000,
            'status' => 'active',
            'department_id' => 3
        ]);

        Employee::updateOrCreate(['email' => 'sarah.williams@example.com'], [
            'name' => 'Sarah Williams',
            'father_name' => 'David Williams',
            'contact_info' => '03003334444',
            'address' => '321 Oak Lane',
            'designation' => 'Project Manager',
            'joining_date' => '2026-02-01',
            'salary' => 60000,
            'status' => 'active',
            'department_id' => 2
        ]);

        Employee::updateOrCreate(['email' => 'thomas.brown@example.com'], [
            'name' => 'Thomas Brown',
            'father_name' => 'George Brown',
            'contact_info' => '03002221111',
            'address' => '654 Elm Street',
            'designation' => 'Accountant',
            'joining_date' => '2026-02-10',
            'salary' => 48000,
            'status' => 'active',
            'department_id' => 3
        ]);

        // Seed Assets - Laptops
        Asset::updateOrCreate(['asset_code' => 'LAP001'], [
            'asset_name' => 'Dell Latitude 5440',
            'category_id' => 1,
            'department_id' => 2,
            'brand' => 'Dell',
            'purchase_date' => '2026-01-01',
            'total_quantity' => 10,
            'remaining_quantity' => 10,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'LAP002'], [
            'asset_name' => 'HP EliteBook 850',
            'category_id' => 1,
            'department_id' => 2,
            'brand' => 'HP',
            'purchase_date' => '2026-01-05',
            'total_quantity' => 8,
            'remaining_quantity' => 8,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'LAP003'], [
            'asset_name' => 'Lenovo ThinkPad X1',
            'category_id' => 1,
            'department_id' => 2,
            'brand' => 'Lenovo',
            'purchase_date' => '2026-01-10',
            'total_quantity' => 5,
            'remaining_quantity' => 5,
            'status' => 'available'
        ]);

        // Monitors
        Asset::updateOrCreate(['asset_code' => 'MON001'], [
            'asset_name' => 'LG Monitor 24 inch',
            'category_id' => 2,
            'department_id' => 2,
            'brand' => 'LG',
            'purchase_date' => '2026-01-05',
            'total_quantity' => 15,
            'remaining_quantity' => 15,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'MON002'], [
            'asset_name' => 'Dell UltraSharp 27 inch',
            'category_id' => 2,
            'department_id' => 2,
            'brand' => 'Dell',
            'purchase_date' => '2026-01-12',
            'total_quantity' => 12,
            'remaining_quantity' => 12,
            'status' => 'available'
        ]);

        // Keyboards
        Asset::updateOrCreate(['asset_code' => 'KEY001'], [
            'asset_name' => 'Logitech Mechanical Keyboard',
            'category_id' => 3,
            'department_id' => 2,
            'brand' => 'Logitech',
            'purchase_date' => '2026-01-08',
            'total_quantity' => 20,
            'remaining_quantity' => 20,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'KEY002'], [
            'asset_name' => 'Microsoft Wireless Keyboard',
            'category_id' => 3,
            'department_id' => 2,
            'brand' => 'Microsoft',
            'purchase_date' => '2026-01-15',
            'total_quantity' => 18,
            'remaining_quantity' => 18,
            'status' => 'available'
        ]);

        // Mice
        Asset::updateOrCreate(['asset_code' => 'MOU001'], [
            'asset_name' => 'Logitech MX Master 3',
            'category_id' => 4,
            'department_id' => 2,
            'brand' => 'Logitech',
            'purchase_date' => '2026-01-20',
            'total_quantity' => 25,
            'remaining_quantity' => 25,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'MOU002'], [
            'asset_name' => 'Microsoft Sculpt Comfort Mouse',
            'category_id' => 4,
            'department_id' => 2,
            'brand' => 'Microsoft',
            'purchase_date' => '2026-01-22',
            'total_quantity' => 22,
            'remaining_quantity' => 22,
            'status' => 'available'
        ]);

        // Printers
        Asset::updateOrCreate(['asset_code' => 'PRT001'], [
            'asset_name' => 'HP LaserJet Pro M404n',
            'category_id' => 5,
            'department_id' => 2,
            'brand' => 'HP',
            'purchase_date' => '2026-01-25',
            'total_quantity' => 3,
            'remaining_quantity' => 3,
            'status' => 'available'
        ]);

        Asset::updateOrCreate(['asset_code' => 'PRT002'], [
            'asset_name' => 'Canon imagePRESS C250',
            'category_id' => 5,
            'department_id' => 2,
            'brand' => 'Canon',
            'purchase_date' => '2026-01-28',
            'total_quantity' => 2,
            'remaining_quantity' => 2,
            'status' => 'available'
        ]);

        $this->command->info('✅ Comprehensive test data seeded successfully!');
        $this->command->info('Total Records:');
        $this->command->info('  - Users: ' . DB::table('users')->count());
        $this->command->info('  - Employees: ' . DB::table('employees')->count());
        $this->command->info('  - Categories: ' . DB::table('categories')->count());
        $this->command->info('  - Assets: ' . DB::table('assets')->count());
    }
}
