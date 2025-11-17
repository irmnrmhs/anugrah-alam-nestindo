<?php

namespace Database\Seeders;

use App\Models\Feather;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feather::create([
            'kode' => 'BRP',
            'jenis_bulu' => 'Bulu Ringan Plontos',
        ]);

        Feather::create([
            'kode' => 'BS',
            'jenis_bulu' => 'Bulu Sedang',
        ]);

        Feather::create([
            'kode' => 'BB',
            'jenis_bulu' => 'Bulu Berat',
        ]);
    }
}
