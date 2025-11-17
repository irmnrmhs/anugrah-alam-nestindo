<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    public string $obj = 'Jenis Warna';

    public function index(): View
    {
        $colors = Color::oldest()->get();

        return view('masters.color', compact('colors'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:colors,kode',
            'jenis_warna' => 'required|string|max:100|unique:colors,jenis_warna',
        ]);

        $color = Color::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $color,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $color = Color::findOrFail($id);

        return response()->json($color);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:colors,kode,' . $id,
            'jenis_warna' => 'required|string|max:100|unique:colors,jenis_warna,' . $id,
        ]);

        $color = Color::findOrFail($id);
        $color->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $color,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $color = Color::findOrFail($id);
        $color->delete();
                                                                                                                
        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
