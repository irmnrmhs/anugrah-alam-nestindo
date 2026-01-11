<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductIdentifier;

class ProductReportController extends Controller
{
    public function scan(string $kode)
    {
        // 1. Cari Identifier berdasarkan barcode
        $identifier = ProductIdentifier::with([
            'grade',
            'rawMaterial',
            'histories.products.grade'
        ])->where('kode', $kode)->first();

        if (!$identifier) {
            // return view('report.report-not-found', compact('kode'));
            return 'tidak ditemukan';
        }

        return view('report.product-report', [
            'identifier' => $identifier
        ]);
    }
}
