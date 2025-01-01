<?php

namespace Database\Seeders;

use App\Models\PermissionAccessRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('admin123'),
            'id_access_role' => 1,
        ]);

        $this->call(MenuSeeder::class);
        $this->call(MedicalFieldsTableSeeder::class);
        $this->call(AccessRoleSeeder::class);
        $this->call(AccessRoleMenuSeeder::class);
    }
}
