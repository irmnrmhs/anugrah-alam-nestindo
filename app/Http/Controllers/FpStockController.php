<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\Employee;
use App\Models\FpStock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FpStockController extends Controller
{
    public string $obj = 'Stok Produk Jadi';
    public function index(): View
    {
        $stocks = FpStock::with('fproducts', 'employee')->latest()->get();
        $fproducts = FinishedProduct::all();
        $employees = Employee::all();

        return view('production.fp-stock', compact('stocks', 'fproducts', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $fproduct = FinishedProduct::find($validated['fproducts_id']);

        if (
            $validated['biji_keluar'] > $fproduct->biji_sisa ||
            $validated['berat_keluar'] > $fproduct->berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        FpStock::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Stok keluar berhasil ditambahkan'
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $stock = FpStock::with('fproducts', 'employee')->findOrFail($id);
        return response()->json($stock);
    }

    public function materialInfo($id)
    {
        $fproduct = FinishedProduct::findOrFail($id);
        $lastOut = FpStock::where('fproducts_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $fproduct->biji,
            'berat_sisa' => $fproduct->berat,
            'last_date' => $lastOut?->tgl_keluar,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $stock = FpStock::findOrFail($id);
        $fproduct = FinishedProduct::find($validated['fproducts_id']);

        $biji_sisa = $fproduct->biji_sisa + $stock->biji_keluar;
        $berat_sisa = $fproduct->berat_sisa + $stock->berat_keluar;

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
            'message' => 'Stok berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $stock = FpStock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FpStock::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
