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
            'kode' => 'X',
            'grade' => 'Xiandun',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => 'S',
            'grade' => 'Suzhan',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => '-',
            'grade' => 'Mangkok',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => '-',
            'grade' => 'Patahan',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => '-',
            'grade' => 'Kaki',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => '-',
            'grade' => 'Hancuran',
            'keterangan' => '-',
            'status' => 1
        ]);

        FpGrade::create([
            'kode' => '-',
            'grade' => 'Hancuran Cetak',
            'keterangan' => '-',
            'status' => 1
        ]);
    }
}
