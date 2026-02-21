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
            'grade' => 'BRP-P',
            'feathers_id' => 1,
            'colors_id' => 1,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BRP-PB',
            'feathers_id' => 1,
            'colors_id' => 2,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BRP-PG',
            'feathers_id' => 1,
            'colors_id' => 3,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BS-P',
            'feathers_id' => 2,
            'colors_id' => 1,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BS-PB',
            'feathers_id' => 2,
            'colors_id' => 2,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BS-PG',
            'feathers_id' => 2,
            'colors_id' => 3,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BB-P',
            'feathers_id' => 3,
            'colors_id' => 1,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BB-PB',
            'feathers_id' => 3,
            'colors_id' => 2,
            'status' => 1,
        ]);

        Grade::create([
            'grade' => 'BB-PG',
            'feathers_id' => 3,
            'colors_id' => 3,
            'status' => 1,
        ]);
    }
}
