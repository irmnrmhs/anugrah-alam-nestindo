<?php

namespace Database\Seeders;

use App\Models\Arrival;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArrivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Arrival::create([
            'kode'              => '2157-121225',
            'dcertificates_id'  => 1,
            'cars_id'           => 2,
            'employees_id'      => 3,
            'tgl_kedatangan'   => '2025-12-12',
            'kondisi'           => 'Bebas dari ... (checkbox)',
            'keterangan'        => 'Dinyatakan aman ...',
        ]);
    }
}
