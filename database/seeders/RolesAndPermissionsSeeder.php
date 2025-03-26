<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create Permissions if they don't exist
        $editArticles = Permission::firstOrCreate(['name' => 'edit articles']);
        $deleteArticles = Permission::firstOrCreate(['name' => 'delete articles']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([$editArticles, $deleteArticles]);
        $userRole->givePermissionTo($editArticles);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
