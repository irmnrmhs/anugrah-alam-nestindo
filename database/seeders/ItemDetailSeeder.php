<?php

namespace Database\Seeders;

use App\Models\ItemDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemDetail::create([
            'grades_id' => 2,
            'items_id' => 1,
            'specification' => 'Z3',
            'price' => 5700,
        ]);
    }
}
