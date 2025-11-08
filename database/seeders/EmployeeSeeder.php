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
            'dept_id' => 1
        ]);

        Employee::create([
            'nip' => '2222',
            'nama' => 'Nama Admin Bahan Baku',
            'dept_id' => 2
        ]);

        Employee::create([
            'nip' => '3333',
            'nama' => 'Nama Admin Produksi',
            'dept_id' => 3
        ]);

        Employee::create([
            'nip' => '4444',
            'nama' => 'Nama Admin QC',
            'dept_id' => 4
        ]);

        Employee::create([
            'nip' => '5555',
            'nama' => 'Nama Admin FS',
            'dept_id' => 5
        ]);

        Employee::create([
            'nip' => '6666',
            'nama' => 'Nama Admin Ekspor',
            'dept_id' => 6
        ]);

        Employee::create([
            'nip' => '7777',
            'nama' => 'Nama Admin Human Resource',
            'dept_id' => 7
        ]);

        Employee::create([
            'nip' => '8888',
            'nama' => 'Nama Admin Akuntansi',
            'dept_id' => 8
        ]);

        Employee::create([
            'nip' => '9999',
            'nama' => 'Nama Admin General Affairs',
            'dept_id' => 9
        ]);

        Employee::create([
            'nip' => '11',
            'nama' => 'Ahmad',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '12',
            'nama' => 'Iqbal',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '13',
            'nama' => 'Idun',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '14',
            'nama' => 'Udin',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '15',
            'nama' => 'Bilal',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '16',
            'nama' => 'Muji',
            'dept_id' => 10
        ]);

        Employee::create([
            'nip' => '17',
            'nama' => 'Ifan',
            'dept_id' => 10
        ]);

        // Employee::create([
        //     'nip' => '1010',
        //     'nama' => 'Nama User',
        //     'dept_id' => 10
        // ]);
    }
}
