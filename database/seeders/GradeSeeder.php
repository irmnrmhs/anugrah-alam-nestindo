<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::create([
            // 'categories_id' => 1,
            // 'grade' => 'MK-BRP-P',
            'shapes_id' => 1,
            'feathers_id' => 1,
            'colors_id' => 1,
            'status' => 1,
        ]);
    }
}
