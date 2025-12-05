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
            'no_skp'        => 'SKP01',
            'tgl_skp'       => '2025-11-07',
            // 'tgl_panen'     => '2025-11-06',
            // 'berat_panen'   => 50.00,
            // 'tgl_kirim'     => '2025-11-07',
            // 'berat_kirim'   => 50.00,
            'companies_id'  => 1,
            'suppliers_id'  => 2,
            'wbhouses_id'   => 1,
        ]);
    }
}
