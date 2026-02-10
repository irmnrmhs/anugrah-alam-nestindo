<?php

namespace Database\Seeders;

use App\Models\Dcertificate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DcertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dcertificate::create([
            'companies_id'  => 1,
            'wbhouses_id'   => 3,
            'no_skp'        => 'SKP01',
            'tgl_skp'       => '2025-12-05',
        ]);
    }
}
