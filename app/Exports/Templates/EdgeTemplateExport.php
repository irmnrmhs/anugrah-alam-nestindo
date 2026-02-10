<?php

namespace App\Exports;

use App\Models\Edge;
use Maatwebsite\Excel\Concerns\FromCollection;

class EdgeTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Edge::all();
    }
}
