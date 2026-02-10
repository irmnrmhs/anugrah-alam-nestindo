<?php

namespace App\Exports;

use App\Models\Dcertificate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SkpExport implements FromView, ShouldAutoSize
{
    protected Dcertificate $dcertificate;

    public function __construct(Dcertificate $dcertificate)
    {
        $this->dcertificate = $dcertificate;
    }

    public function view(): View
    {
        return view('exports.excel.skp-excel', [
            'dcertificate' => $this->dcertificate
        ]);
    }
}
