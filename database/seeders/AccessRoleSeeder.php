<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('access_roles')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Hak akses untuk admin',
                'access' => 'private',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Siswa',
                'description' => 'Ini hak akses untuk seluruh siswa',
                'access' => 'public',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
