<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name'     => 'Super Admin',
            'username' => 'superadmin',
            'email'    => 'superadmin@rribatam.go.id',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::factory()->create([
            'name'     => 'Admin Arsip',
            'username' => 'admin',
            'email'    => 'admin@rribatam.go.id',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');

        $staff = User::factory()->create([
            'name'     => 'Staff Arsip',
            'username' => 'staff',
            'email'    => 'staff@rribatam.go.id',
            'password' => bcrypt('password'),
        ]);
        $staff->assignRole('Staff');
    }
}