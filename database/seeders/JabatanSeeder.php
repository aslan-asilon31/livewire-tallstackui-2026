<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------- Daftar Jabatan / Roles Perusahaan -----------------
        $roles = [
            'admin',
            'hr',
            'it',
            'manager',
            'staff',
            'employee',
        ];

        // Buat roles jika belum ada
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // ----------------- Permissions -----------------
        $permissions = [
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'document.view',
            'document.upload',
            'document.delete',
            'position.view',
            'position.edit',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ----------------- Assign permissions ke role -----------------
        Role::where('name', 'admin')->first()->givePermissionTo(Permission::all());
        Role::where('name', 'hr')->first()->givePermissionTo([
            'user.view',
            'user.create',
            'user.edit',
            'document.view',
            'document.upload',
            'position.view',
            'position.edit'
        ]);
        Role::where('name', 'it')->first()->givePermissionTo([
            'user.view',
            'document.view',
            'document.upload'
        ]);
        Role::where('name', 'manager')->first()->givePermissionTo([
            'user.view',
            'document.view',
            'document.upload',
            'position.view'
        ]);
        Role::where('name', 'staff')->first()->givePermissionTo([
            'user.view',
            'document.view'
        ]);
        Role::where('name', 'employee')->first()->givePermissionTo([
            'user.view',
            'document.view'
        ]);

        // ----------------- Assign role ke semua user -----------------
        $users = User::all();

        foreach ($users as $user) {
            // Jika user belum punya role, beri secara acak
            if ($user->roles->isEmpty()) {
                $randomRole = $roles[array_rand($roles)];
                $user->assignRole($randomRole);
            }
        }
    }
}
