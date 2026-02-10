<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesTemplateExport implements FromArray, WithHeadings
{
    /**
     * Header kolom (baris pertama Excel)
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Posisi',
            'Departemen',
        ];
    }

    /**
     * Isi data (kosong karena ini template)
     */
    public function array(): array
    {
        return [];
    }
}
