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
            'dcertificates_id'  => 1,
            'cars_id'           => 2,
            'drivers_id'        => 3,
            'kondisi'           => 'Bebas dari ... (checkbox)',
            'keterangan'        => 'Dinyatakan aman ...',
        ]);
    }
}
