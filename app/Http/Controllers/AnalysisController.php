<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AnalysisController extends Controller
{
    public string $obj = 'Analisis Produk Jadi';
    public function index(): View
    {
        $analys = Analysis::latest()->get();
        return view('quality-control.analysis', compact('analys'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item'     => 'required',
            'standard'   => 'required',
        ]);

        Analysis::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function show(int $id): JsonResponse{
        $analys = Analysis::findOrFail($id);
        return response()->json($analys);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'item'     => 'required',
            'standard'   => 'required',
        ]);

        $analys = Analysis::findOrFail($id);
        $analys->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $analys = Analysis::findOrFail($id);
        $analys->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Analysis::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
