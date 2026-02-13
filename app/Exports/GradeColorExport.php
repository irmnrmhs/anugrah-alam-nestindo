<?php

namespace App\Exports;

use App\Models\GradeColor;
use Maatwebsite\Excel\Concerns\FromCollection;

class GradeColorExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GradeColor::all();
    }
}
