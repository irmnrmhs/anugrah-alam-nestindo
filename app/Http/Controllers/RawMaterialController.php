<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\Dcertificate;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    public string $obj = 'Bahan Baku';
    public function index(): View
    {
        $raw_materials = RawMaterial::with('arrival')->oldest()->get();
        $arrivals = Arrival::all();

        return view('raw-material.rawMaterial', compact('raw_materials', 'arrivals'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // 'kode' => 'required|string|max:50|unique:raw_materials,kode',
            // 'arrivals_id' => 'required|exists:arrivals,id|unique:raw_materials,arrivals_id',
            'biji' => 'required|numeric|min:0|max:99999.99',
            'berat' => 'required|numeric|min:0|max:999.99',
        ]);

        $raw_material = RawMaterial::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $raw_material,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $raw_material = RawMaterial::with('arrival')->findOrFail($id);
        return response()->json($raw_material);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            // 'kode' => 'required|string|max:50|unique:raw_materials,kode,' . $id,
            // 'arrivals_id' => 'required|exists:arrivals,id|unique:raw_materials,arrivals_id,' . $id,
            'biji' => 'required|numeric|min:0|max:99999.99',
            'berat' => 'required|numeric|min:0|max:999.99',
        ]);

        $raw_material = RawMaterial::findOrFail($id);

        $raw_material->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $raw_material,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $raw_material = RawMaterial::findOrFail($id);
        $raw_material->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
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
