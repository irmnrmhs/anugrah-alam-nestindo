<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            'kode' => 'JB',
            'area' => 'Jawa Barat',
            'kh' => 0,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'BN',
            'area' => 'Banten',
            'kh' => 0,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'KTG',
            'area' => 'Kalimantan Tengah',
            'kh' => 1,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'KTM',
            'area' => 'Kalimantan Timur',
            'kh' => 1,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'KS',
            'area' => 'Kalimantan Selatan',
            'kh' => 1,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'TP',
            'area' => 'Tanjung Priok',
            'kh' => 1,
            'keterangan' => '',
        ]);

        Area::create([
            'kode' => 'NTB',
            'area' => 'Nusa Tenggara Barat',
            'kh' => 1,
            'keterangan' => '',
        ]);
    }
}
