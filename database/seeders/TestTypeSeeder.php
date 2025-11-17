<?php

namespace Database\Seeders;

use App\Models\TestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TestType::create([
            'categories_id' => 1,
            'nama_uji'  => 'Kadar Air',
            'satuan'    => '%',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 15.00,
        ]);

        TestType::create([
            'categories_id' => 1,
            'nama_uji'  => 'Kadar Nitrit',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);
        
        TestType::create([
            'categories_id' => 1,
            'nama_uji'  => 'Kadar Aluminum',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);

        TestType::create([
            'categories_id' => 2,
            'nama_uji'  => 'Kadar Air',
            'satuan'    => '%',
            'standar_minimal' => 0.00,
            'standar_maksimal' => 15.00,
        ]);

        TestType::create([
            'categories_id' => 2,
            'nama_uji'  => 'CCP 1',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 30.00,
        ]);

        TestType::create([
            'categories_id' => 2,
            'nama_uji'  => 'Kadar Nitrit',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 30.00,
        ]);
        
        TestType::create([
            'categories_id' => 2,
            'nama_uji'  => 'Kadar Aluminum',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);
    }
}
