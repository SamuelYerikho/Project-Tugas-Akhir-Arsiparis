<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Tampilkan form checkbox permission untuk sebuah role, dikelompokkan per modul.
     */
    public function edit(Role $role)
    {
        $permissions = $this->groupPermissions(Permission::orderBy('name')->get());
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Simpan perubahan permission untuk role tersebut.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', "Permission untuk role \"{$role->name}\" berhasil diperbarui.");
    }

    /**
     * Kelompokkan permission ke dalam modul-modul yang mudah dibaca Admin.
     * Permission yang belum masuk mapping akan otomatis jatuh ke grup "Fitur Lainnya".
     */
    private function groupPermissions(Collection $permissions): array
    {
        $moduleMap = [
            'Manajemen Role' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
                'assign role permissions',
            ],
            'Manajemen Pengguna' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
                'reset user password',
            ],
            'Manajemen Arsip' => [
                'view archives',
                'view all archives',
                'upload archives',
                'show archives',
                'edit archives',
                'delete archives',
                'download archives',
            ],
            'Permintaan Akses' => [
                'request archive access',
                'approve archive access',
                'reject archive access',
                'view all access requests',
                'view own access requests',
                'delete archive access request',
            ],
            'Laporan' => [
                'view reports',
                'generate reports',
                'download reports',
            ],
        ];

        $grouped = [];

        foreach ($moduleMap as $label => $names) {
            $matched = $permissions->whereIn('name', $names)->values();

            if ($matched->isNotEmpty()) {
                $grouped[$label] = $matched;
            }
        }

        // Tangkap permission baru yang belum sempat dipetakan, biar tidak hilang dari tampilan
        $mappedNames = collect($moduleMap)->flatten();
        $others = $permissions->reject(fn ($permission) => $mappedNames->contains($permission->name))->values();

        if ($others->isNotEmpty()) {
            $grouped['Fitur Lainnya'] = $others;
        }

        return $grouped;
    }
}