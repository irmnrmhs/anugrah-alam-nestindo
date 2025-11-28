<?php

namespace App\Http\Controllers;

use App\Models\Feather;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeatherController extends Controller
{
    public string $obj = 'Jenis Bulu';

    public function index(): View
    {
        $feathers = Feather::latest()->get();

        return view('masters.feather', compact('feathers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:feathers,kode',
            'jenis_bulu' => 'required|string|max:100|unique:feathers,jenis_bulu',
        ]);

        $feather = Feather::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $feather,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $feather = Feather::findOrFail($id);

        return response()->json($feather);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:feathers,kode,' . $id,
            'jenis_bulu' => 'required|string|max:100|unique:feathers,jenis_bulu,' . $id,
        ]);

        $feather = Feather::findOrFail($id);
        $feather->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $feather,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $feather = Feather::findOrFail($id);
        $feather->delete();
                                                                                                                
        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
