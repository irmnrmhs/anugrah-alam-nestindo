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
            'area' => 'Jawa Barat',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Banten',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Kalimantan Tengah',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Kalimantan Timur',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Kalimantan Selatan',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Tanjung Priok',
            'keterangan' => '',
        ]);

        Area::create([
            'area' => 'Nusa Tenggara Barat',
            'keterangan' => '',
        ]);
    }
}
