<?php

namespace App\Exports;

use App\Models\Wbhouse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WbhouseTemplateExport implements FromArray, WithHeadings
{
    /**
     * Header kolom (baris pertama Excel)
     */
    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'alamat',
            'area',
            'kapasitas'
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
