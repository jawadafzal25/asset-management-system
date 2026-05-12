<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * All system permissions are derived from the database schema.
     *
     * Format:
     *   permission_name  → used internally and in middleware checks
     *   module           → groups permissions on the frontend (tick-box groups)
     *   action           → what the permission does
     *   display_name     → shown on frontend UI
     *   description      → tooltip / helper text on frontend
     *
     * Modules derived from PDF schema:
     *   - User Management   (users table)
     *   - Role Management   (roles table)
     *   - Permission        (permissions table)
     *   - Department        (departments table)
     *   - Employee          (employees table)
     *   - Category          (categories table)
     *   - Asset             (assets table)
     *   - Assignment        (asset_assignments table)
     *   - Maintenance       (maintenance_requests table)
     */
    public function run(): void
    {
        $permissions = [

            // ── User Management ───────────────────────────────────────────────
            [
                'permission_name' => 'user.view',
                'module'          => 'User Management',
                'action'          => 'view',
                'display_name'    => 'View Users',
                'description'     => 'Can view the list of all system users.',
            ],
            [
                'permission_name' => 'user.create',
                'module'          => 'User Management',
                'action'          => 'create',
                'display_name'    => 'Create User',
                'description'     => 'Can create new system users.',
            ],
            [
                'permission_name' => 'user.update',
                'module'          => 'User Management',
                'action'          => 'update',
                'display_name'    => 'Update User',
                'description'     => 'Can edit existing user information.',
            ],
            [
                'permission_name' => 'user.delete',
                'module'          => 'User Management',
                'action'          => 'delete',
                'display_name'    => 'Delete User',
                'description'     => 'Can delete system users.',
            ],

            // ── Role Management ───────────────────────────────────────────────
            [
                'permission_name' => 'role.view',
                'module'          => 'Role Management',
                'action'          => 'view',
                'display_name'    => 'View Roles',
                'description'     => 'Can view all roles.',
            ],
            [
                'permission_name' => 'role.create',
                'module'          => 'Role Management',
                'action'          => 'create',
                'display_name'    => 'Create Role',
                'description'     => 'Can create new roles.',
            ],
            [
                'permission_name' => 'role.update',
                'module'          => 'Role Management',
                'action'          => 'update',
                'display_name'    => 'Update Role',
                'description'     => 'Can edit existing roles and their permission sets.',
            ],
            [
                'permission_name' => 'role.delete',
                'module'          => 'Role Management',
                'action'          => 'delete',
                'display_name'    => 'Delete Role',
                'description'     => 'Can delete roles from the system.',
            ],

            // ── Permission Management ─────────────────────────────────────────
            [
                'permission_name' => 'permission.view',
                'module'          => 'Permission Management',
                'action'          => 'view',
                'display_name'    => 'View Permissions',
                'description'     => 'Can view all system permissions.',
            ],
            [
                'permission_name' => 'permission.assign',
                'module'          => 'Permission Management',
                'action'          => 'assign',
                'display_name'    => 'Assign Permissions to Role',
                'description'     => 'Can assign or remove permissions from a role.',
            ],

            // ── Department Management ─────────────────────────────────────────
            [
                'permission_name' => 'department.view',
                'module'          => 'Department Management',
                'action'          => 'view',
                'display_name'    => 'View Departments',
                'description'     => 'Can view all departments.',
            ],
            [
                'permission_name' => 'department.create',
                'module'          => 'Department Management',
                'action'          => 'create',
                'display_name'    => 'Create Department',
                'description'     => 'Can create new departments.',
            ],
            [
                'permission_name' => 'department.update',
                'module'          => 'Department Management',
                'action'          => 'update',
                'display_name'    => 'Update Department',
                'description'     => 'Can edit department information.',
            ],
            [
                'permission_name' => 'department.delete',
                'module'          => 'Department Management',
                'action'          => 'delete',
                'display_name'    => 'Delete Department',
                'description'     => 'Can delete departments.',
            ],

            // ── Employee Management ───────────────────────────────────────────
            [
                'permission_name' => 'employee.view',
                'module'          => 'Employee Management',
                'action'          => 'view',
                'display_name'    => 'View Employees',
                'description'     => 'Can view all employee records.',
            ],
            [
                'permission_name' => 'employee.create',
                'module'          => 'Employee Management',
                'action'          => 'create',
                'display_name'    => 'Create Employee',
                'description'     => 'Can add new employees to the system.',
            ],
            [
                'permission_name' => 'employee.update',
                'module'          => 'Employee Management',
                'action'          => 'update',
                'display_name'    => 'Update Employee',
                'description'     => 'Can edit employee details.',
            ],
            [
                'permission_name' => 'employee.delete',
                'module'          => 'Employee Management',
                'action'          => 'delete',
                'display_name'    => 'Delete Employee',
                'description'     => 'Can remove employee records.',
            ],

            // ── Category Management ───────────────────────────────────────────
            [
                'permission_name' => 'category.view',
                'module'          => 'Category Management',
                'action'          => 'view',
                'display_name'    => 'View Categories',
                'description'     => 'Can view all asset categories.',
            ],
            [
                'permission_name' => 'category.create',
                'module'          => 'Category Management',
                'action'          => 'create',
                'display_name'    => 'Create Category',
                'description'     => 'Can create new asset categories (e.g. Macs, Electronics).',
            ],
            [
                'permission_name' => 'category.update',
                'module'          => 'Category Management',
                'action'          => 'update',
                'display_name'    => 'Update Category',
                'description'     => 'Can edit asset category details.',
            ],
            [
                'permission_name' => 'category.delete',
                'module'          => 'Category Management',
                'action'          => 'delete',
                'display_name'    => 'Delete Category',
                'description'     => 'Can delete asset categories.',
            ],

            // ── Asset Management ──────────────────────────────────────────────
            [
                'permission_name' => 'asset.view',
                'module'          => 'Asset Management',
                'action'          => 'view',
                'display_name'    => 'View Assets',
                'description'     => 'Can view all company assets.',
            ],
            [
                'permission_name' => 'asset.create',
                'module'          => 'Asset Management',
                'action'          => 'create',
                'display_name'    => 'Create Asset',
                'description'     => 'Can add new company assets to the system.',
            ],
            [
                'permission_name' => 'asset.update',
                'module'          => 'Asset Management',
                'action'          => 'update',
                'display_name'    => 'Update Asset',
                'description'     => 'Can edit asset details and quantities.',
            ],
            [
                'permission_name' => 'asset.delete',
                'module'          => 'Asset Management',
                'action'          => 'delete',
                'display_name'    => 'Delete Asset',
                'description'     => 'Can remove assets from the system.',
            ],

            // ── Asset Assignment ──────────────────────────────────────────────
            [
                'permission_name' => 'assignment.view',
                'module'          => 'Asset Assignment',
                'action'          => 'view',
                'display_name'    => 'View Assignments',
                'description'     => 'Can view all asset assignment records.',
            ],
            [
                'permission_name' => 'assignment.create',
                'module'          => 'Asset Assignment',
                'action'          => 'create',
                'display_name'    => 'Assign Asset',
                'description'     => 'Can assign assets to employees.',
            ],
            [
                'permission_name' => 'assignment.update',
                'module'          => 'Asset Assignment',
                'action'          => 'update',
                'display_name'    => 'Update Assignment',
                'description'     => 'Can modify existing assignment records.',
            ],
            [
                'permission_name' => 'assignment.return',
                'module'          => 'Asset Assignment',
                'action'          => 'return',
                'display_name'    => 'Return Asset',
                'description'     => 'Can process asset returns from employees.',
            ],
            [
                'permission_name' => 'assignment.delete',
                'module'          => 'Asset Assignment',
                'action'          => 'delete',
                'display_name'    => 'Delete Assignment',
                'description'     => 'Can delete assignment records.',
            ],

            // ── Maintenance Requests ──────────────────────────────────────────
            [
                'permission_name' => 'maintenance.view',
                'module'          => 'Maintenance',
                'action'          => 'view',
                'display_name'    => 'View Maintenance Requests',
                'description'     => 'Can view all maintenance request records.',
            ],
            [
                'permission_name' => 'maintenance.create',
                'module'          => 'Maintenance',
                'action'          => 'create',
                'display_name'    => 'Create Maintenance Request',
                'description'     => 'Can report a new maintenance issue.',
            ],
            [
                'permission_name' => 'maintenance.update',
                'module'          => 'Maintenance',
                'action'          => 'update',
                'display_name'    => 'Update Maintenance Status',
                'description'     => 'Can update the status of a maintenance request.',
            ],
            [
                'permission_name' => 'maintenance.delete',
                'module'          => 'Maintenance',
                'action'          => 'delete',
                'display_name'    => 'Delete Maintenance Request',
                'description'     => 'Can delete maintenance request records.',
            ],
        ];

        // Insert all permissions — skip if already exists (safe to re-run)
        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['permission_name' => $permission['permission_name']],
                array_merge($permission, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ ' . count($permissions) . ' permissions seeded successfully.');
        $this->command->table(
            ['Module', 'Permission Name', 'Display Name'],
            collect($permissions)->map(fn($p) => [
                $p['module'],
                $p['permission_name'],
                $p['display_name'],
            ])->toArray()
        );
    }
}
