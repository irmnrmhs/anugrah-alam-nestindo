<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RmResult;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RmResultController extends Controller
{
    public string $obj = 'Hasil Uji';
    public function index(): View
    {
        $results = RmResult::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.rmResult', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'nullable|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $result = RmResult::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = RmResult::with('rawMaterial')->findOrFail($id);
        return response()->json($result);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'nullable|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $result = RmResult::findOrFail($id);

        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = RmResult::findOrFail($id);
        $result->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
