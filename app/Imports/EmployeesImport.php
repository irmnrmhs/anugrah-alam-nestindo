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
    protected array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Baris Excel (heading = baris 1)
            $rowNumber = $index + 2;

            // Validasi minimal
            if (empty($row['nip']) || empty($row['nama']) || empty($row['departemen'])) {
                $this->errors[] = "Baris {$rowNumber}: NIP / Nama / Departemen kosong";
                continue;
            }

            // Cek department
            $department = Department::where('nama_dept', trim($row['departemen']))->first();
            if (!$department) {
                $this->errors[] = "Baris {$rowNumber}: Departemen '{$row['departemen']}' tidak ditemukan";
                continue;
            }

            // Posisi (boleh kosong)
            $position = null;
            if (!empty($row['posisi'])) {
                $position = Position::where('posisi', trim($row['posisi']))->first();
                if (!$position) {
                    $this->errors[] = "Baris {$rowNumber}: Posisi '{$row['posisi']}' tidak ditemukan";
                    continue;
                }
            }

            // NIP duplikat
            if (Employee::where('nip', $row['nip'])->exists()) {
                $this->errors[] = "Baris {$rowNumber}: NIP '{$row['nip']}' sudah terdaftar";
                continue;
            }

            // Simpan
            Employee::create([
                'nip'          => trim($row['nip']),
                'nama'         => trim($row['nama']),
                'positions_id' => $position?->id,
                'dept_id'      => $department->id,
                'status'       => 1,
            ]);
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

