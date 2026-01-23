<?php

namespace Database\Seeders;

use App\Models\Step;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Step::create([
            'kode' => 'KP',
            'proses' => 'Keterangan Pengiriman',
            'employees_id' => 5,
        ]);

        Step::create([
            'kode' => 'PR01GB',
            'proses' => 'Grading Bahan Baku',
            'employees_id' => 5,
        ]);

        Step::create([
            'kode' => 'PR02SK',
            'proses' => 'Sesek Kaki',
            'employees_id' => 6,
        ]);

        Step::create([
            'kode' => 'PR03PC',
            'proses' => 'Pencucian',
            'employees_id' => 7,
        ]);

        Step::create([
            'kode' => 'PR04IK',
            'proses' => 'Inspeksi dan Koreksi',
            'employees_id' => 8,
        ]);

        Step::create([
            'kode' => 'PR05PB',
            'proses' => 'Pencabutan Bulu',
            'employees_id' => 9,
        ]);

        Step::create([
            'kode' => 'PR06PR',
            'proses' => 'Perendaman',
            'employees_id' => 10,
        ]);

        Step::create([
            'kode' => 'PR07CB',
            'proses' => 'Cabut Bilas',
            'employees_id' => 11,
        ]);

        Step::create([
            'kode' => 'PR08MC',
            'proses' => 'Masuk Cetak',
            'employees_id' => 8,
        ]);

        Step::create([
            'kode' => 'PR09KC',
            'proses' => 'Keluar Cetak',
            'employees_id' => 9,
        ]);

        Step::create([
            'kode' => 'PR10PK',
            'proses' => 'Pengeringan',
            'employees_id' => 10,
        ]);

        Step::create([
            'kode' => 'PR11GP',
            'proses' => 'Grading Produk Jadi',
            'employees_id' => 11,
        ]);

        Step::create([
            'kode' => 'PR12SP',
            'proses' => 'Stok Produk Jadi',
            'employees_id' => 12,
        ]);

        Step::create([
            'kode' => 'PR13ST',
            'proses' => 'Steaming',
            'employees_id' => 13,
        ]);
    }
}
