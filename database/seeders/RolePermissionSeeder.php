<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-orders',
            'view-orders',
            'manage-products',
            'view-products',
            'manage-categories',
            'view-categories',
            'manage-services',
            'view-services',
            'manage-payments',
            'view-payments',
            'verify-payment',
            'view-reports',
            'export-reports',
            'view-transactions',
            'manage-users',
            'view-users',
            'manage-promo-codes',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $petugasRole->syncPermissions([
            'view-orders',
            'manage-orders',
            'view-products',
            'view-categories',
            'view-services',
            'view-payments',
            'manage-payments',
            'verify-payment',
        ]);
    }
}
