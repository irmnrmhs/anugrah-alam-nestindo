<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'kd_dept' => 'ADM',
            'nama_dept' => 'Administrasi',
        ]);
        
        Department::create([
            'kd_dept' => 'EX',
            'nama_dept' => 'Ekspor',
        ]);
        
        Department::create([
            'kd_dept' => 'HR',
            'nama_dept' => 'Human Resource',
        ]);
        
        Department::create([
            'kd_dept' => 'AC',
            'nama_dept' => 'Accounting',
        ]);
        
        Department::create([
            'kd_dept' => 'GA',
            'nama_dept' => 'General Affairs',
        ]);
        
        Department::create([
            'kd_dept' => 'SP',
            'nama_dept' => 'Support',
        ]);

        Department::create([
            'kd_dept' => 'RW',
            'nama_dept' => 'Rumah Walet',
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Bahan Baku',
        ]);

        Department::create([
            'kd_dept' => 'PC',
            'nama_dept' => 'Pencucian',
        ]);

        Department::create([
            'kd_dept' => 'PB',
            'nama_dept' => 'Pencabutan',
        ]);

        Department::create([
            'kd_dept' => 'PRD',
            'nama_dept' => 'CCP1',
        ]);

        Department::create([
            'kd_dept' => 'CT',
            'nama_dept' => 'Cetak',
        ]);

        Department::create([
            'kd_dept' => 'PK',
            'nama_dept' => 'Pengeringan',
        ]);

        Department::create([
            'kd_dept' => 'GP',
            'nama_dept' => 'Grading',
        ]);

        Department::create([
            'kd_dept' => 'PR',
            'nama_dept' => 'Produksi',
        ]);

        Department::create([
            'kd_dept' => 'QC',
            'nama_dept' => 'Kontrol Kualitas',
        ]);
        
        Department::create([
            'kd_dept' => 'FS',
            'nama_dept' => 'Keamanan Pangan',
        ]);
        
    }
}
