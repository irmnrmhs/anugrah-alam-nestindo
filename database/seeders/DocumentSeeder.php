<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::create([
            'depts_id'  => 7,
            'kode'      => 'SKP058',
            'no'        => 'AAN/FRM/RW/01/04',
            'name'      => 'Surat Keterangan Pengiriman',
            'rev'       => 1,
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 8,
            'kode'      => 'KBB058',
            'no'        => 'AAN/FRM/RM/01/01',
            'name'      => 'Kendaraan Bahan Baku',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 18,
        ]);

        Document::create([
            'depts_id'  => 8,
            'kode'      => 'DBB058',
            'no'        => 'AAN/FRM/RM/01/02',
            'name'      => 'Kedatangan Bahan Baku',
            'rev'       => 1,
            'tgl'       => '2022-04-05',
            'employees_id'  => 18,
        ]);

        Document::create([
            'depts_id'  => 8,
            'kode'      => 'SBB058',
            'no'        => 'AAN/FRM/RM/01/03',
            'name'      => 'Stok Bahan Baku',
            'rev'       => 1,
            'tgl'       => '2022-11-01',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 8,
            'kode'      => 'PR01GB',
            'no'        => 'AAN/FRM/RM/01/05',
            'name'      => 'Grading Bahan Baku',
            'rev'       => 3,
            'tgl'       => '2023-08-10',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 8,
            'kode'      => 'PR02SK',
            'no'        => 'AAN/FRM/RM/01/09',
            'name'      => 'Sesek Kaki',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 20,
        ]);

        Document::create([
            'depts_id'  => 9,
            'kode'      => 'PR03PC',
            'no'        => 'AAN/FRM/PR/01/01',
            'name'      => 'Pencucian',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 21,
        ]);

        Document::create([
            'depts_id'  => 10,
            'kode'      => 'PR04IK',
            'no'        => 'AAN/FRM/PR/01/08',
            'name'      => 'Inspeksi dan Koreksi',
            'rev'       => 0,
            'tgl'       => '2023-01-11',
            'employees_id'  => 1,
        ]);

        Document::create([
            'depts_id'  => 11,
            'kode'      => 'PR05PB',
            'no'        => 'AAN/FRM/PR/01/02',
            'name'      => 'Pencabutan Bulu',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 22,
        ]);

        Document::create([
            'depts_id'  => 11,
            'kode'      => 'PR05PB',
            'no'        => 'AAN/FRM/PR/01/02',
            'name'      => 'Pencabutan Bulu',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 23,
        ]);

        Document::create([
            'depts_id'  => 12,
            'kode'      => 'PR06PR',
            'no'        => 'AAN/FRM/PR/01/03',
            'name'      => 'Perendaman',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 13,
            'kode'      => 'PR07CB',
            'no'        => 'AAN/FRM/PR/01/07',
            'name'      => 'Cabut Bilas',
            'rev'       => 0,
            'tgl'       => '2023-01-11',
            'employees_id'  => 17,
        ]);

        Document::create([
            'depts_id'  => 14,
            'kode'      => 'PR08MC',
            'no'        => 'AAN/FRM/PR/01/04-a',
            'name'      => 'Masuk Cetak',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 14,
            'kode'      => 'PR09KC',
            'no'        => 'AAN/FRM/PR/01/04-b',
            'name'      => 'Keluar Cetak',
            'rev'       => 1,
            'tgl'       => '2023-01-16',
            'employees_id'  => 17,
        ]);

        Document::create([
            'depts_id'  => 15,
            'kode'      => 'PR10PK',
            'no'        => 'AAN/FRM/PR/01/05',
            'name'      => 'Pengeringan',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 17,
        ]);

        Document::create([
            'depts_id'  => 16,
            'kode'      => 'PR11GP',
            'no'        => 'AAN/FRM/PR/01/06a',
            'name'      => 'Grading Produk Jadi',
            'rev'       => 1,
            'tgl'       => '2023-02-01',
            'employees_id'  => 18,
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
            'depts_id'  => 15,
            'kode'      => 'PR13ST',
            'no'        => 'AAN/FRM/PR/04/01',
            'name'      => 'Steaming',
            'rev'       => 5,
            'tgl'       => '2025-05-05',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCBBA',
            'no'        => 'AAN/FRM/QC/01/01',
            'name'      => 'Kadar Air Bahan Baku',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCBBN',
            'no'        => 'AAN/FRM/QC/01/02',
            'name'      => 'Kadar Nitrit Bahan Baku',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCBBL',
            'no'        => 'AAN/FRM/QC/18/01',
            'name'      => 'Kadar Aluminium Bahan Baku',
            'rev'       => 0,
            'tgl'       => '2025-01-02',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCCCPN',
            'no'        => 'AAN/FRM/QC/01/03',
            'name'      => 'Kadar Nitrit Selama Proses',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCCCPL',
            'no'        => 'AAN/FRM/QC/18/01',
            'name'      => 'Kadar Aluminium Selama Proses',
            'rev'       => 01,
            'tgl'       => '2025-08-12',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCPJA',
            'no'        => 'AAN/FRM/QC/01/05',
            'name'      => 'Kadar Air Produk Jadi',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCPJN',
            'no'        => 'AAN/FRM/QC/01/04',
            'name'      => 'Kadar Nitrit Produk Jadi',
            'rev'       => 0,
            'tgl'       => '2021-10-08',
            'employees_id'  => 19,
        ]);

        Document::create([
            'depts_id'  => 18,
            'kode'      => 'QCPJL',
            'no'        => 'AAN/FRM/QC/18/02',
            'name'      => 'Kadar Aluminium Produk Jadi',
            'rev'       => 0,
            'tgl'       => '2025-01-02',
            'employees_id'  => 19,
        ]);
    }
}
