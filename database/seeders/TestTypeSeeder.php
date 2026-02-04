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
            'kode'  => 'QCBBA',
            'nama_uji'  => 'Kadar Air Bahan Baku',
            'satuan'    => '%',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 15.00,
        ]);

        TestType::create([
            'categories_id' => 1,
            'kode' => 'QCBBN',
            'nama_uji'  => 'Kadar Nitrit Bahan Baku',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);
        
        TestType::create([
            'categories_id' => 1,
            'kode' => 'QCBBL',
            'nama_uji'  => 'Kadar Aluminum Bahan Baku',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);

        TestType::create([
            'categories_id' => 1,
            'kode'  => 'QCCCPN',
            'nama_uji'  => 'Kadar Nitrit Selama Proses',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 30.00,
        ]);

        TestType::create([
            'categories_id' => 1,
            'kode' => 'QCCCPL',
            'nama_uji'  => 'Kadar Aluminium Selama Proses',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);

        TestType::create([
            'categories_id' => 2,
            'kode' => 'QCPJA',
            'nama_uji'  => 'Kadar Air Produk Jadi',
            'satuan'    => '%',
            'standar_minimal' => 0.00,
            'standar_maksimal' => 15.00,
        ]);

        TestType::create([
            'categories_id' => 2,
            'kode' => 'QCPJN',
            'nama_uji'  => 'Kadar Nitrit Produk Jadi',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 30.00,
        ]);
        
        TestType::create([
            'categories_id' => 2,
            'kode' => 'QCPJL',
            'nama_uji'  => 'Kadar Aluminium Produk Jadi',
            'satuan'    => 'mg/kg',
            'standar_minimal' => 0.000,
            'standar_maksimal' => 100.00,
        ]);
    }
}
