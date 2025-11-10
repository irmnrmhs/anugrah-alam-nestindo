<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::create([
            'plat' => 'B 9137 UCW',
            'merk' => 'Toyota Hilux',
        ]);
        Car::create([
            'plat' => 'B 9348 UCZ',
            'merk' => 'Toyota Hilux',
        ]);
        Car::create([
            'plat' => 'B 9444 BCZ',
            'merk' => 'Toyota Hilux',
        ]);
    }
}
