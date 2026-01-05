<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\FpResult;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FpResultController extends Controller
{
    public string $obj = 'Hasil Uji';
    public function index(): View
    {
        $results = FpResult::latest()->get();
        $products = FinishedProduct::all();

        return view('quality-control.fpResult', compact('results', 'products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        if($validated['kadar_air']>15)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar'
            ], 422);
        }elseif($validated['kadar_nitrit'] > 30)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar'
            ]);
        }elseif($validated['kadar_aluminium'] > 100)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar'
            ]);
        }

        $result = FpResult::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = FpResult::findOrFail($id);
        // $result = FpResult::with('rawMaterial')->findOrFail($id);
        return response()->json($result);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        $result = FpResult::findOrFail($id);

        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = FpResult::findOrFail($id);
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
            'items.*.kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'items.*.kadar_nitrit' => 'nullable|numeric|min:0|max:999.9',
            'items.*.kadar_aluminium' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            FpResult::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FpResult::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
