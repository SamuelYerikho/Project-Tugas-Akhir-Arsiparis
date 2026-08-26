<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Manajemen Arsip (Tanpa search archives karena ikut view archives)
            'view archives',
            'view all archives',
            'show archives',
            'upload archives',
            'edit archives',
            'delete archives',
            'download archives',

            // Manajemen Pengguna
            'view users',
            'create users',
            'edit users',
            'delete users',
            'reset user password',

            // Manajemen Role
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign role permissions',

            // Laporan
            'view reports',
            'generate reports',
            'download reports',

            // Akses Arsip
            'view own access requests', 
            'view all access requests', 
            'request archive access',
            'approve archive access',
            'reject archive access',
            'delete archive access request',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}