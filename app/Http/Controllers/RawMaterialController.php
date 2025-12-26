<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\RawMaterial;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    public string $obj = 'Bahan Baku';
    public function index(): View
    {
        $raw_materials = RawMaterial::with('arrivals')->latest()->get();
        $arrivals = Arrival::all();

        return view('raw-material.rawMaterial', compact('raw_materials', 'arrivals'));
    }

    public function info($id)
    {
        $raw = RawMaterial::with(['stocks' => function($q){
            $q->latest('tgl_keluar');
        }])->findOrFail($id);

        return response()->json([
            'biji_sisa'  => $raw->biji_sisa,
            'berat_sisa' => $raw->berat_sisa,
            'last_date'  => optional($raw->stocks->first())->tgl_keluar,
        ]);
    }
}
