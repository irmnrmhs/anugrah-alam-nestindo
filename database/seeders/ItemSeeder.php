<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'grades_id' => 2,
            'item' => "BIRD'S NEST (WHITЕ)",
            'item_cn' => '白燕窝(燕盏)',
            'specification' => 'Z3',
            'price' => 5700,
        ]);
    }
}
