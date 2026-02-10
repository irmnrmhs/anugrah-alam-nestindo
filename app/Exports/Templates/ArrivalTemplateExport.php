<?php

namespace App\Exports;

use App\Models\Arrival;
use Maatwebsite\Excel\Concerns\FromCollection;

class ArrivalTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Arrival::all();
    }
}
