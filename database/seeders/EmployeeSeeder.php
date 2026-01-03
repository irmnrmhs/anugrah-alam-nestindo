<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'nip' => '1111',
            'nama' => 'Nama Super Admin',
            'positions_id' => 1,
            'dept_id' => 1
        ]);

        Employee::create([
            'nip' => '2222',
            'nama' => 'Nama Admin Bahan Baku',
            'positions_id' => 1,
            'dept_id' => 2
        ]);

        Employee::create([
            'nip' => '3333',
            'nama' => 'Nama Admin Produksi',
            'positions_id' => 1,
            'dept_id' => 3
        ]);

        Employee::create([
            'nip' => '4444',
            'nama' => 'Nama Admin QC',
            'positions_id' => 1,
            'dept_id' => 4
        ]);

        Employee::create([
            'nip' => '5555',
            'nama' => 'Nama Admin FS',
            'positions_id' => 1,
            'dept_id' => 5
        ]);

        Employee::create([
            'nip' => '6666',
            'nama' => 'Nama Admin Ekspor',
            'positions_id' => 1,
            'dept_id' => 6
        ]);

        Employee::create([
            'nip' => '7777',
            'nama' => 'Nama Admin Human Resource',
            'positions_id' => 1,
            'dept_id' => 7
        ]);

        Employee::create([
            'nip' => '8888',
            'nama' => 'Nama Admin Akuntansi',
            'positions_id' => 1,
            'dept_id' => 8
        ]);

        Employee::create([
            'nip' => '9999',
            'nama' => 'Nama Admin General Affairs',
            'positions_id' => 1,
            'dept_id' => 9
        ]);

        Employee::create([
            'nip' => '11',
            'nama' => 'Ahmad',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '12',
            'nama' => 'Iqbal',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '13',
            'nama' => 'Idun',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '14',
            'nama' => 'Udin',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '15',
            'nama' => 'Bilal',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '16',
            'nama' => 'Muji',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '17',
            'nama' => 'Ifan',
            'positions_id' => 2,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '18',
            'nama' => 'A',
            'positions_id' => 3,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '19',
            'nama' => 'B',
            'positions_id' => 3,
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '20',
            'nama' => 'C',
            'positions_id' => 3,
            'dept_id' => 10
        ]);

        // Employee::create([
        //     'nip' => '1010',
        //     'nama' => 'Nama User',
        //     'dept_id' => 10
        // ]);
    }
}
