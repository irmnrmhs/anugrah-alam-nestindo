<?php

namespace App\Exports;

use App\Models\Rinse;
use Maatwebsite\Excel\Concerns\FromCollection;

class RinseTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rinse::all();
    }
}
