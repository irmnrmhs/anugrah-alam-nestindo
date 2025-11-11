<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'kode'      => 'T',
            'nama'      => 'Tian',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 2,
        ]);
        
        Supplier::create([
            'kode'      => 'E',
            'nama'      => 'Edy',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 2,
        ]);
        
        Supplier::create([
            'kode'      => 'K',
            'nama'      => 'Kian Li',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 2,
        ]);
        
        Supplier::create([
            'kode'      => 'D',
            'nama'      => 'Dodi',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 2,
        ]);
        
        Supplier::create([
            'kode'      => 'A',
            'nama'      => 'Ahong',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 2,
        ]);
        
        Supplier::create([
            'kode'      => 'F',
            'nama'      => 'Fujian',
            'alamat'    => '',
            'no_telp'   => '',
            'categories_id' => 1,
        ]);
    }
}
