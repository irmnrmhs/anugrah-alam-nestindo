<?php

namespace App\Http\Controllers;

use App\Models\FpAlum;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\FinishedProduct;
use Illuminate\Http\JsonResponse;

class FpAlumController extends Controller
{
    public string $obj = 'Hasil Uji Aluminium Produk Jadi';
    public function index(): View
    {
        $results = FpAlum::latest()->get();
        $products = FinishedProduct::all();

        return view('quality-control.fp-alum', compact('results', 'products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        if($validated['kadar_aluminium'] > 100)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }

        $result = FpAlum::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = FpAlum::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        if($validated['kadar_aluminium'] > 100)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }
        
        $result = FpAlum::findOrFail($id);
        
        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = FpAlum::findOrFail($id);
        $result->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.products_id'   => 'required|exists:finished_products,id',
            'items.*.kadar_aluminium' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            FpAlum::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FpAlum::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }    
}
