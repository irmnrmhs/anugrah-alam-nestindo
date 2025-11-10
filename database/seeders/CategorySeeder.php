<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'kode' => 'BB',
            'kategori' => 'Bahan Baku',
            'keterangan' => '',
        ]);

        Category::create([
            'kode' => 'PJ',
            'kategori' => 'Produk Jadi',
            'keterangan' => '',
        ]);
    }
}