<?php

namespace App\Exports;

use App\Models\edge;
use Maatwebsite\Excel\Concerns\FromCollection;

class EdgeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return edge::all();
    }
}
