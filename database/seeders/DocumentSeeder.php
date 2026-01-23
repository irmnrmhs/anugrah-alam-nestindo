<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::create([
            'depts_id'  => 1,
            'kode'      => 'SKP058',
            'no'        => 'AAN/FRM/RW/01/04',
            'name'      => 'Surat Keterangan Pengiriman',
            'rev'       => 1,
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 1,
            'kode'      => 'KBB058',
            'no'        => 'AAN/FRM/RM/01/01',
            'name'      => 'Kendaraan Bahan Baku',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 2,
            'kode'      => 'DBB058',
            'no'        => 'AAN/FRM/RM/01/02',
            'name'      => 'Kedatangan Bahan Baku',
            'rev'       => 1,
            'tgl'       => '2022-04-05',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 2,
            'kode'      => 'SBB058',
            'no'        => 'AAN/FRM/RM/01/03',
            'name'      => 'Stok Bahan Baku',
            'rev'       => 1,
            'tgl'       => '2022-11-01',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 3,
            'kode'      => 'PR01GB',
            'no'        => 'AAN/FRM/RM/01/05',
            'name'      => 'Grading Bahan Baku',
            'rev'       => 3,
            'tgl'       => '2023-08-10',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR02SK',
            'no'        => 'AAN/FRM/RW/01/09',
            'name'      => 'Sesek Kaki',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR03PC',
            'no'        => 'AAN/FRM/PR/01/01',
            'name'      => 'Pencucian',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR04IK',
            'no'        => 'AAN/FRM/PR/01/08',
            'name'      => 'Inspeksi dan Koreksi',
            'rev'       => 0,
            'tgl'       => '2023-01-11',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR05PB',
            'no'        => 'AAN/FRM/PR/01/02',
            'name'      => 'Pencabutan Bulu',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR06PR',
            'no'        => 'AAN/FRM/PR/01/03',
            'name'      => 'Perendaman',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR07CB',
            'no'        => 'AAN/FRM/PR/01/07',
            'name'      => 'Cabut Bilas',
            'rev'       => 0,
            'tgl'       => '2023-01-11',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR08MC',
            'no'        => 'AAN/FRM/PR/01/04-a',
            'name'      => 'Masuk Cetak',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR09KC',
            'no'        => 'AAN/FRM/PR/01/04-b',
            'name'      => 'Keluar Cetak',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR10PK',
            'no'        => 'AAN/FRM/PR/01/05',
            'name'      => 'Pengeringan',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR11GP',
            'no'        => 'AAN/FRM/PR/01/06a',
            'name'      => 'Grading Produk Jadi',
            'rev'       => 1,
            'tgl'       => '2023-02-01',
            'employees_id'  => 1,
        ]);

        // Document::create([
            //     'depts_id'  => 4,
            //     'kode'      => 'PR12SP',
            //     'no'        => 'AAN/FRM/RW/01/01',
            //     'name'      => 'Stok Produk Jadi',
            //     'rev'       => 1,
            // ]);
            //     'employees_id'  => 1,

        Document::create([
            'depts_id'  => 4,
            'kode'      => 'PR13ST',
            'no'        => 'AAN/FRM/PR/04/01',
            'name'      => 'Steaming',
            'rev'       => 5,
            'tgl'       => '2025-05-05',
            'employees_id'  => 1,
        ]);
    }
}
