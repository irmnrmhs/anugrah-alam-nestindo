<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Position;
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

            // Cari berdasarkan nama
            $department = Department::where('nama_dept', $row['departemen'])->first();
            $position = Position::where('posisi', $row['posisi'])->first();

            // Skip kalau tidak ditemukan
            if (!$department) {
                continue;
            }

            if (!$position) {
                continue;
            }

            // Skip kalau NIP sudah ada
            if (Employee::where('nip', $row['nip'])->exists()) {
                continue;
            }

            Employee::create([
                'nip'     => $row['nip'],
                'nama'    => $row['nama'],
                'positions_id' => $position->id,
                'dept_id' => $department->id,
            ]);
        }
    }
}
