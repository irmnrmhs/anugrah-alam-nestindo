<?php

namespace App\Exports;

use App\Models\Wash;
use Maatwebsite\Excel\Concerns\FromCollection;

class WashTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Wash::all();
    }
}
