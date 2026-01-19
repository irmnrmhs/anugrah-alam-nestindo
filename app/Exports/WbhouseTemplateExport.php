<?php

namespace App\Exports;

use App\Models\Wbhouse;
use Maatwebsite\Excel\Concerns\FromCollection;

class WbhouseTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Wbhouse::all();
    }
}
