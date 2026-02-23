<?php

namespace App\Exports;

use App\Models\Soak;
use Maatwebsite\Excel\Concerns\FromCollection;

class SoakExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Soak::all();
    }
}
