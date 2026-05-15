<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Asset;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Categories
        Category::updateOrCreate(['name' => 'Laptops'], ['description' => 'Computing devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Monitors'], ['description' => 'Display devices', 'status' => true]);
        Category::updateOrCreate(['name' => 'Keyboards'], ['description' => 'Input devices', 'status' => true]);

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

        // Seed Assets
        Asset::updateOrCreate(['asset_code' => 'LAP001'], [
            'asset_name' => 'Dell Laptop',
            'category_id' => 1,
            'department_id' => 2,
            'brand' => 'Dell',
            'purchase_date' => '2026-01-01',
            'total_quantity' => 10,
            'remaining_quantity' => 10,
            'status' => 'available'
        ]);

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

        $this->command->info('Test data seeded successfully!');
    }
}
