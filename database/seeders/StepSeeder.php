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
        // Step::create([
        //     'depts_id' => 1,
        //     'kode' => 'KP',
        //     'proses' => 'Keterangan Pengiriman',
        // ]);

        // Step::create([
        //     'depts_id' => 5,
        //     'kode' => 'KBB',
        //     'proses' => 'Kendaraan Bahan Baku',
        // ]);

        Step::create([
            'depts_id' => 5,
            'kode' => 'KBB',
            'proses' => 'Kedatangan Bahan Baku',
        ]);

        Step::create([
            'depts_id' => 5,
            'kode' => 'PR01GB',
            'proses' => 'Grading Bahan Baku',
        ]);

        Step::create([
            'depts_id' => 6,
            'kode' => 'PR02SK',
            'proses' => 'Sesek Kaki',
        ]);

        Step::create([
            'depts_id' => 7,
            'kode' => 'PR03PC',
            'proses' => 'Pencucian',
        ]);

        Step::create([
            'depts_id' => 8,
            'kode' => 'PR04IK',
            'proses' => 'Inspeksi dan Koreksi',
        ]);

        Step::create([
            'depts_id' => 9,
            'kode' => 'PR05PB',
            'proses' => 'Pencabutan Bulu',
        ]);

        Step::create([
            'depts_id' => 10,
            'kode' => 'PR06PR',
            'proses' => 'Perendaman',
        ]);

        Step::create([
            'depts_id' => 11,
            'kode' => 'PR07CB',
            'proses' => 'Cabut Bilas',
        ]);

        Step::create([
            'depts_id' => 8,
            'kode' => 'PR08MC',
            'proses' => 'Masuk Cetak',
        ]);

        Step::create([
            'depts_id' => 9,
            'kode' => 'PR09KC',
            'proses' => 'Keluar Cetak',
        ]);

        Step::create([
            'depts_id' => 10,
            'kode' => 'PR10PK',
            'proses' => 'Pengeringan',
        ]);

        Step::create([
            'depts_id' => 11,
            'kode' => 'PR11GP',
            'proses' => 'Grading Produk Jadi',
        ]);

        Step::create([
            'depts_id' => 12,
            'kode' => 'PR12SP',
            'proses' => 'Stok Produk Jadi',
        ]);

        Step::create([
            'depts_id' => 13,
            'kode' => 'PR13ST',
            'proses' => 'Steaming',
        ]);
    }
}
