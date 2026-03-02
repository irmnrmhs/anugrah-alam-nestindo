<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'kode' => 'X',
            'nama' => 'Xiamen Diamond Bird Biotechnology Co., Ltd.',
            'alamat' => 'Unit A-1, 5th Floor, 2#, No.9, Zhongcang East Road, Haicang District, Xiamen',
            'no_telp' => '400-0250592',
            'fax' => '',
            'negara' => 'China',
        ]);
    }
}
