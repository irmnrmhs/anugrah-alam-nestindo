<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'kode' => 'P',
            'jenis_warna' => 'Putih',
        ]);

        Color::create([
            'kode' => 'PB',
            'jenis_warna' => 'Putih Beras',
        ]);

        Color::create([
            'kode' => 'PG',
            'jenis_warna' => 'Putih Gading',
        ]);
    }
}
