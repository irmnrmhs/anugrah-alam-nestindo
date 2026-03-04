<?php

namespace App\Http\Controllers;

use App\Models\Water;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WaterController extends Controller
{
    public string $obj = 'Air Produksi';
    public function index(): View
    {
        $waters = Water::latest()->get();

        return view('quality-control.water', compact('waters'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nitrit' => 'required|numeric|min:0|max:999.9',
            'ph' => 'required|numeric',
            'ozone' => 'required|numeric|min:0|max:9999.9',
            'organoleptis' => 'required|boolean',
        ]);

        Water::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $water = Water::findOrFail($id);
        return response()->json($water);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nitrit' => 'required|numeric|min:0|max:999.9',
            'ph' => 'required|numeric',
            'ozone' => 'required|numeric|min:0|max:9999.9',
            'organoleptis' => 'required|boolean',
        ]);

        $water = Water::findOrFail($id);

        $water->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $water,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $water = Water::findOrFail($id);
        $water->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Water::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
