<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create([
            'posisi' => '-'
        ]);

        Position::create([
            'posisi' => 'Supir'
        ]);

        Position::create([
            'posisi' => 'Produksi'
        ]);

        Position::create([
            'posisi' => 'Staff'
        ]);
    }
}
