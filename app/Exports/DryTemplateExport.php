<?php

namespace App\Exports;

use App\Models\Dry;
use Maatwebsite\Excel\Concerns\FromCollection;

class DryTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Dry::all();
    }
}
