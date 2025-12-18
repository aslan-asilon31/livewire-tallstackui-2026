<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RpSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------- Roles -----------------
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $hrRole = Role::firstOrCreate(['name' => 'hr']);
        $itRole = Role::firstOrCreate(['name' => 'it']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

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

        // ----------------- Assign permissions to roles -----------------
        $adminRole->givePermissionTo(Permission::all());  // admin dapat semua

        $hrRole->givePermissionTo(['user.view', 'user.create', 'user.edit', 'document.view', 'document.upload', 'position.view', 'position.edit']);

        $itRole->givePermissionTo(['user.view', 'document.view', 'document.upload']);

        $employeeRole->givePermissionTo(['user.view', 'document.view']);

        // ----------------- Contoh assign role ke user -----------------
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'is_activated' => 1]
        );
        $adminUser->assignRole($adminRole);

        $hrUser = User::firstOrCreate(
            ['email' => 'hr@example.com'],
            ['name' => 'HR User', 'password' => bcrypt('password'), 'is_activated' => 1]
        );
        $hrUser->assignRole($hrRole);
    }
}
