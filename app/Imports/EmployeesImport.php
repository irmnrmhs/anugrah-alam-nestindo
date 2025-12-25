<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Cari departemen berdasarkan nama
            $department = Department::where('nama_dept', $row['departemen'])->first();

            // Skip kalau departemen tidak ditemukan
            if (!$department) {
                continue;
            }

            // Skip kalau NIP sudah ada
            if (Employee::where('nip', $row['nip'])->exists()) {
                continue;
            }

            Employee::create([
                'nip'     => $row['nip'],
                'nama'    => $row['nama'],
                'dept_id' => $department->id,
            ]);
        }
    }
}
