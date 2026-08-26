<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Super Admin: Mendapatkan seluruh permission yang ada
        $superAdmin = Role::firstOrCreate([
            'name'       => 'Super Admin',
            'guard_name' => 'web',
        ]);
        $superAdmin->syncPermissions(Permission::all());

        // Admin: Permission dikosongkan sementara
        $admin = Role::firstOrCreate([
            'name'       => 'Admin',
            'guard_name' => 'web',
        ]);
        $admin->syncPermissions([]);

        // Staff: Permission dikosongkan sementara
        $staff = Role::firstOrCreate([
            'name'       => 'Staff',
            'guard_name' => 'web',
        ]);
        $staff->syncPermissions([]);
    }
}