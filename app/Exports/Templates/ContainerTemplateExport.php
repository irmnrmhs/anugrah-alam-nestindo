<?php

namespace App\Exports;

use App\Models\Container;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContainerTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Container::all();
    }
}
