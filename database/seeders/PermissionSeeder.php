<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'Can Enter',
            'Data Entry',
            'Manage Editors',
            'Manage Admins',
            'Give Permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
            $this->command->info("Permission \"$permission\" created");
        }
    }
}
