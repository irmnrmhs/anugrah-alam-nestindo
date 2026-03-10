<?php

namespace App\Http\Controllers;

use App\Models\PackageType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PackageTypeController extends Controller
{
    public string $obj = 'Jenis Bahan Kemas';
    public function index(): View
    {
        $types = PackageType::latest()->get();
        return view('production.package-type', compact('types'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required'
        ]);

        PackageType::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function show(int $id): JsonResponse{
        $type = PackageType::findOrFail($id);
        return response()->json($type);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required'
        ]);

        $type = PackageType::findOrFail($id);
        $type->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $type = PackageType::findOrFail($id);
        $type->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        PackageType::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
