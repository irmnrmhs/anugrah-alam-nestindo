<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Username',
            // 'Email',
            'password',
            'Role',
            'Karyawan',
        ];
    }

    public function array(): array
    {
        return [];
    }
}
