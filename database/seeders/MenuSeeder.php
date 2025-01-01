<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['route' => 'dashboard.index', 'label' => 'Dashboard'],
            ['route' => 'list-students.index', 'label' => 'Daftar Siswa'],
            ['route' => 'user-management.index', 'label' => 'Manajemen User'],
            ['route' => 'access-role.index', 'label' => 'Hak Akses'],
            ['route' => 'broadcast.index', 'label' => 'Broadcast'],
            ['route' => 'question.index', 'label' => 'Buat Paket'],
            ['route' => 'medical-field.index', 'label' => 'Bidang'],
            ['route' => 'question-detail.index', 'label' => 'Buat Soal'],
            ['route' => 'admin.question-list.index', 'label' => 'Daftar Soal'],
        ]);
    }
}
