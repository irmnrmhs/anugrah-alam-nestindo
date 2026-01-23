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
            'deskripsi' => 'Departemen Administrasi adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'EX',
            'nama_dept' => 'Ekspor',
            'deskripsi' => 'Departemen Ekspor adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'HR',
            'nama_dept' => 'Human Resource',
            'deskripsi' => 'Departemen SDM adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'AC',
            'nama_dept' => 'Accounting',
            'deskripsi' => 'Departemen Akuntansi adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'GA',
            'nama_dept' => 'General Affairs',
            'deskripsi' => 'Departemen General Affairs adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'SP',
            'nama_dept' => 'Support',
            'deskripsi' => 'Support adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Bahan Baku',
            'deskripsi' => 'Departemen Bahan Baku adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Pencucian',
            'deskripsi' => 'Departemen Pencucian adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Pencabutan',
            'deskripsi' => 'Departemen Pencabutan adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'CCP1',
            'deskripsi' => 'Departemen CCP1 adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Cetak',
            'deskripsi' => 'Departemen Cetak adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Pengeringan',
            'deskripsi' => 'Departemen Pengeringan adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'RM',
            'nama_dept' => 'Grading',
            'deskripsi' => 'Departemen Grading adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'PR',
            'nama_dept' => 'Produksi',
            'deskripsi' => 'Departemen Produksi adalah ...'
        ]);

        Department::create([
            'kd_dept' => 'QC',
            'nama_dept' => 'Kontrol Kualitas',
            'deskripsi' => 'Departemen Kontrol Kualitas adalah ...'
        ]);
        
        Department::create([
            'kd_dept' => 'FS',
            'nama_dept' => 'Keamanan Pangan',
            'deskripsi' => 'Departemen Keamanan Pangan adalah ...'
        ]);
        
    }
}
