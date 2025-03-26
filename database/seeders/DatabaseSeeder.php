<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RolesAndPermissionsSeeder::class);

        // Create the admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin@123'),
        ]);

        // Assign the 'admin' role
        $adminRole = Role::where('name', 'admin')->first();
        if (! $adminRole) {
            $adminRole = Role::create(['name' => 'admin']);
        }
        $adminUser->assignRole($adminRole);

        // Optionally create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(UserSeeder::class);
    }
}
