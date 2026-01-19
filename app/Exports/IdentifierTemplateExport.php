<?php

namespace App\Exports;

use App\Models\ProductIdentifier;
use Maatwebsite\Excel\Concerns\FromCollection;

class IdentifierTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductIdentifier::all();
    }
}
