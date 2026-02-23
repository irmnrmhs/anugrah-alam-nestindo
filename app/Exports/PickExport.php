<?php

namespace App\Exports;

use App\Models\Pick;
use Maatwebsite\Excel\Concerns\FromCollection;

class PickExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pick::all();
    }
}
