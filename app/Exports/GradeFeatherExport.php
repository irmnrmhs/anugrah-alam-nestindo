<?php

namespace App\Exports;

use App\Models\GradeFeather;
use Maatwebsite\Excel\Concerns\FromCollection;

class GradeFeatherExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GradeFeather::all();
    }
}
