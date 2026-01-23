<?php

namespace App\Http\Controllers;

use App\Models\RmStock;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class RmStockController extends Controller
{
    public string $obj = 'Stok keluar';
    public function index(): View
    {
        $stocks = RmStock::with('rawMaterial', 'employee')->latest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::where('status', 1)->get();

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
            'keterangan' => 'nullable'
        ]);

        $raw = RawMaterial::find($validated['rms_id']);

        if (
            $validated['biji_keluar'] > $raw->biji_sisa ||
            $validated['berat_keluar'] > $raw->berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        RmStock::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan'
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $stock = RmStock::with('rawMaterial', 'employee')->findOrFail($id);
        return response()->json($stock);
    }

    public function materialInfo($id)
    {
        $raw = RawMaterial::findOrFail($id);
        $lastOut = RmStock::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $raw->biji_sisa,
            'berat_sisa' => $raw->berat_sisa,
            'last_date' => $lastOut?->tgl_keluar,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $stock = RmStock::findOrFail($id);
        $raw = RawMaterial::find($validated['rms_id']);

        $biji_sisa = $raw->biji_sisa + $stock->biji_keluar;
        $berat_sisa = $raw->berat_sisa + $stock->berat_keluar;

        if (
            $validated['biji_keluar'] > $biji_sisa ||
            $validated['berat_keluar'] > $berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $stock->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
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

    public function export($id)
    {
        $stocks = RmStock::with(['rawMaterial', 'employee'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('exports.rm-stock-form', compact('stocks'))
            ->setPaper('A4', 'portrait');

        $filename = 'Form_Stok_Bahan_Baku_' . $stocks->id . '.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        RmStock::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
