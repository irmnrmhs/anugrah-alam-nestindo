<?php

namespace App\Exports;

use App\Models\Correction;
use Maatwebsite\Excel\Concerns\FromCollection;

class CorrectionTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Correction::all();
    }
}
