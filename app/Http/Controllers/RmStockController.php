<?php

namespace App\Http\Controllers;

use App\Models\RmStock;
use App\Models\RawMaterial;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RmStockController extends Controller
{
    public string $obj = 'Stok Bahan Baku';
    public function index(): View
    {
        $stocks = RmStock::with('rawMaterial', 'employee')->oldest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::all();

        return view('raw-material.rmStock', compact('stocks', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'biji_sisa' => 'required|integer|min:0',
            'berat_sisa' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $stock = RmStock::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $stock,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $stock = RmStock::with('rawMaterial', 'employee')->findOrFail($id);
        return response()->json($stock);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'biji_sisa' => 'required|integer|min:0',
            'berat_sisa' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $stock = RmStock::findOrFail($id);

        $stock->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $stock,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $stock = RmStock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
