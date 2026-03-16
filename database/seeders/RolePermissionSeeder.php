<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Client Management
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',

            // Contract Management
            'view_contracts',
            'create_contracts',
            'edit_contracts',
            'delete_contracts',

            // Invoice Management
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'delete_invoices',
            'approve_invoices',

            // Site Management
            'view_sites',
            'create_sites',
            'edit_sites',
            'delete_sites',

            // Employee Management
            'view_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',

            // Attendance Management
            'view_attendances',
            'create_attendances',
            'edit_attendances',
            'delete_attendances',
            'approve_attendances',

            // Task Management
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'delete_tasks',
            'assign_tasks',

            // Payroll Management
            'view_payrolls',
            'create_payrolls',
            'edit_payrolls',
            'delete_payrolls',
            'approve_payrolls',

            // Leave Request Management
            'view_leave_requests',
            'create_leave_requests',
            'edit_leave_requests',
            'delete_leave_requests',
            'approve_leave_requests',

            // Inventory Management
            'view_inventory',
            'create_inventory',
            'edit_inventory',
            'delete_inventory',

            // Transaction Management
            'view_transactions',
            'create_transactions',
            'edit_transactions',
            'delete_transactions',

            // Report Management
            'view_reports',
            'export_reports',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role & Permission Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin HR - HR management related
        $adminHr = Role::firstOrCreate(['name' => 'admin-hr', 'guard_name' => 'web']);
        $adminHr->syncPermissions([
            'view_employees',
            'create_employees',
            'edit_employees',
            'view_attendances',
            'approve_attendances',
            'view_payrolls',
            'create_payrolls',
            'edit_payrolls',
            'approve_payrolls',
            'view_leave_requests',
            'approve_leave_requests',
            'view_transactions',
            'view_invoices',
            'view_reports',
            'export_reports',
        ]);

        // Site Leader - site operations
        $siteLeader = Role::firstOrCreate(['name' => 'site-leader', 'guard_name' => 'web']);
        $siteLeader->syncPermissions([
            'view_sites',
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'assign_tasks',
            'view_attendances',
            'create_attendances',
            'view_inventory',
            'create_inventory',
            'edit_inventory',
            'view_leave_requests',
            'approve_leave_requests',
            'view_reports',
        ]);

        // Gardener - field worker
        $gardener = Role::firstOrCreate(['name' => 'gardener', 'guard_name' => 'web']);
        $gardener->syncPermissions([
            'view_tasks',
            'edit_tasks',
            'view_attendances',
            'create_attendances',
            'view_inventory',
            'view_leave_requests',
            'create_leave_requests',
        ]);

        // Client - limited view access
        $client = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $client->syncPermissions([
            'view_sites',
            'view_tasks',
            'view_invoices',
            'view_reports',
        ]);
    }
}
