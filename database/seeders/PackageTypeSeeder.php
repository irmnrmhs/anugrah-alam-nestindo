<?php

namespace Database\Seeders;

use App\Models\PackageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackageType::create([
            'type' => 'Nylon',
        ]);
        
        PackageType::create([
            'type' => 'Plastik POF',
        ]);
        
        PackageType::create([
            'type' => 'Mika',
        ]);
        
        PackageType::create([
            'type' => 'Mikroba',
        ]);
        
        PackageType::create([
            'type' => 'Box',
        ]);
        
        PackageType::create([
            'type' => 'Kotak Donatsu',
        ]);
        
        PackageType::create([
            'type' => 'Multipack',
        ]);
        
        PackageType::create([
            'type' => 'Basic Home',
        ]);
        
        PackageType::create([
            'type' => 'Hardtop',
        ]);
    }
}
