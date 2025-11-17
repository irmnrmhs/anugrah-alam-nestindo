<?php

namespace Database\Seeders;

use App\Models\Shape;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shape::create([
            'kode' => 'MK',
            'jenis_bentuk' => 'Mangkok',
        ]);

        Shape::create([
            'kode' => 'OVL',
            'jenis_bentuk' => 'Oval',
        ]);
    }
}
