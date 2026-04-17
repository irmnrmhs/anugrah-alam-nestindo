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
            'nitrit' => 'required|numeric',
            'ph' => 'required|numeric',
            'ozone' => 'required|numeric',
            'organoleptis' => 'required|boolean',
        ]);

        $isValid =
            $validated['nitrit'] >= 0 && $validated['nitrit'] < 3 &&
            $validated['ph'] >= 6.5 && $validated['ph'] < 8.5 &&
            $validated['ozone'] >= 0 && $validated['ozone'] < 0.3 &&
            $validated['organoleptis'] == 1;

        $validated['hasil'] = $isValid ? 1 : 0;

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
            'nitrit' => 'required|numeric',
            'ph' => 'required|numeric',
            'ozone' => 'required|numeric',
            'organoleptis' => 'required|boolean',
        ]);

        $isValid =
            $validated['nitrit'] >= 0 && $validated['nitrit'] < 3 &&
            $validated['ph'] >= 6.5 && $validated['ph'] < 8.5 &&
            $validated['ozone'] >= 0 && $validated['ozone'] < 0.3 &&
            $validated['organoleptis'] == 1;

        $validated['hasil'] = $isValid ? 1 : 0;

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
