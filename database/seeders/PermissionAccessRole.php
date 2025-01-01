<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessRoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permission_access_roles')->insert([
            [
                'access_role_id' => 1,
                'route' => 'dashboard.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'list-students.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'user-management.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'access-role.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'broadcast.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'question.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'medical-field.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'question-detail.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'access_role_id' => 1,
                'route' => 'admin.question-list.index',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
