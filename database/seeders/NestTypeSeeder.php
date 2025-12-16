<?php

namespace Database\Seeders;

use App\Models\NestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NestType::create([
            'type' => 'Mangkok'
        ]);

        NestType::create([
            'type' => 'Patahan'
        ]);

        NestType::create([
            'type' => 'Kaki'
        ]);

        NestType::create([
            'type' => 'Hancuran'
        ]);

        NestType::create([
            'type' => 'Hancuran Cetak'
        ]);

        NestType::create([
            'type' => 'Xiandun'
        ]);

        NestType::create([
            'type' => 'Suzhan'
        ]);
    }
}
