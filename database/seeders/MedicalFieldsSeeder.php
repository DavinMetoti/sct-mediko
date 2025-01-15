<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicalFields = [
            ['name' => 'Bedah'],
            ['name' => 'Forensik dan Etik'],
            ['name' => 'Gastroenterohepatologi'],
            ['name' => 'Hematologi dan Infeksi'],
            ['name' => 'IKM dan Sistem Kesehatan Nasional'],
            ['name' => 'Integumen'],
            ['name' => 'Kardiologi dan Pulmo'],
            ['name' => 'Muskuloskeletal dan Endokrin'],
            ['name' => 'Nefrologi dan Urologi'],
            ['name' => 'Neurologi dan Psikiatri'],
            ['name' => 'Obstetri dan Ginekologi'],
            ['name' => 'Pediatri'],
            ['name' => 'THT dan Mata'],
        ];

        DB::table('medical_fields')->insert($medicalFields);
    }
}
