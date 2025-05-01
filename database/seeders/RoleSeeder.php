<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'master-admin' => [
                'Can Enter',
                'Data Entry',
                'Manage Editors',
                'Manage Admins',
                'Give Permissions',
            ],
            'admin' => [
                'Can Enter',
                'Data Entry',
                'Manage Editors',
                'Give Permissions',
            ],
            'editor' => [
                'Can Enter',
                'Data Entry',
            ],
        ];

        foreach ($roles as $roleName => $permissionNames) {
            $role = Role::findOrCreate($roleName);

            $permissions = Permission::whereIn('name', $permissionNames)->get();

            $role->syncPermissions($permissions);

            $this->command->info("Role \"$roleName\" created with " .
                count($permissions) . " permissions: " .
                implode(', ', $permissionNames));
        }
    }
}
