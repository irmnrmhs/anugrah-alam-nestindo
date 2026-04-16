<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        Department::create([
            'kd_dept' => 'ADM',
            'nama_dept' => 'Administrasi',
        ]);
        
        // 2
        Department::create([
            'kd_dept' => 'EX',
            'nama_dept' => 'Ekspor',
        ]);
        
        // 3
        Department::create([
            'kd_dept' => 'HR',
            'nama_dept' => 'Human Resource',
        ]);
        
        // 4
        Department::create([
            'kd_dept' => 'AC',
            'nama_dept' => 'Accounting',
        ]);
        
        // 5
        Department::create([
            'kd_dept' => 'GA',
            'nama_dept' => 'General Affairs',
        ]);
        
        // 6
        Department::create([
            'kd_dept' => 'SP',
            'nama_dept' => 'Support',
        ]);

        // 7
        Department::create([
            'kd_dept' => 'RW',
            'nama_dept' => 'Rumah Walet',
        ]);

        // 8
        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Bahan Baku',
            'nama_eng'  => 'Raw Material',
        ]);

        // 9
        Department::create([
            'kd_dept' => 'PC',
            'nama_dept' => 'Pencucian',
            'nama_eng'  => 'Washing',
        ]);

        // 10
        Department::create([
            'kd_dept' => 'IK',
            'nama_dept' => 'Koreksi',
        ]);

        // 11
        Department::create([
            'kd_dept' => 'PB',
            'nama_dept' => 'Pencabutan',
            'nama_eng'  => "Feather's Plucking",
        ]);

        // 12
        Department::create([
            'kd_dept' => 'PRD',
            'nama_dept' => 'Perendaman (CCP 1)',
        ]);

        // 13
        Department::create([
            'kd_dept' => 'CB',
            'nama_dept' => 'Cabut Bilas',
        ]);

        // 14
        Department::create([
            'kd_dept' => 'MC',
            'nama_dept' => 'Cetak',
            'nama_eng' => 'Molding',
        ]);

        // 15
        Department::create([
            'kd_dept' => 'PK',
            'nama_dept' => 'Pengeringan',
        ]);

        // 16
        Department::create([
            'kd_dept' => 'GP',
            'nama_dept' => 'Grading',
        ]);

        // 17
        Department::create([
            'kd_dept' => 'PR',
            'nama_dept' => 'Produksi',
        ]);

        // 18
        Department::create([
            'kd_dept' => 'QC',
            'nama_dept' => 'Kontrol Kualitas',
        ]);
        
        // 19
        Department::create([
            'kd_dept' => 'FS',
            'nama_dept' => 'Keamanan Pangan',
        ]);
        
        // 20
        Department::create([
            'kd_dept' => 'MR',
            'nama_dept' => 'Manajemen Representatif',
        ]);
        
    }
}
