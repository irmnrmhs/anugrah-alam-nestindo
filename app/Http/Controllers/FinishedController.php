<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\Steam;
use Illuminate\View\View;

class FinishedController extends Controller
{
    public string $obj = 'Produk Jadi';
    public function index(): View
    {
        $fproducts = FinishedProduct::latest()->get();
        $steams = Steam::all();

        return view('production.finished', compact('fproducts', 'steams'));
    }

    public function info($id)
    {
        $product = FinishedProduct::with(['fpstocks' => function($q){
            $q->latest('tgl_keluar');
        }])->findOrFail($id);

        return response()->json([
            'biji_sisa'  => $product->biji_sisa,
            'berat_sisa' => $product->berat_sisa,
            'last_date'  => optional($product->fpstocks->first())->tgl_keluar,
        ]);
    }
}
