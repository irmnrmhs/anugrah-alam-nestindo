<?php

namespace Database\Seeders;

use App\Models\FpGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FpGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FpGrade::create([
            'kode' => 'GR1',
            'grade' => 'Grade 1',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => 'GR2',
            'grade' => 'Grade 2',
            'keterangan' => '-',
            'status' => 1
        ]);
    }
}
