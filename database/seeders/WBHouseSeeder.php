<?php

namespace Database\Seeders;

use App\Models\WBHouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WBHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WBHouse::create([
            'kode'      => '2092',
            'nama'      => 'Cibarengkok 1',
            'alamat'    => '...',
            'area'      => 'Jawa Barat',
            'kapasitas' => 209.00,
        ]);

        WBHouse::create([
            'kode'      => '2106',
            'nama'      => 'Beruta A',
            'alamat'    => '...',
            'area'      => 'Kalimantan',
            'kapasitas' => 700.00,
        ]);
    }
}
