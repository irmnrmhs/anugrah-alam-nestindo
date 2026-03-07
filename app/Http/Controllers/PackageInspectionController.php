<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageInspection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PackageInspectionController extends Controller
{
    public string $obj = 'Pemeriksaan Bahan Baku';
    public function index(): View
    {
        $inspections = PackageInspection::with('type')->latest()->get();
        $packages = Package::all();

        return view('production.package-inspections', compact('inspections', 'packages'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'packages_id' => 'required|exists:packages,id',
            'tanggal' => 'required|date',
            'hasil' => 'required|boolean',
        ]);

        PackageInspection::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $inspections = PackageInspection::with('package')->findOrFail($id);
        return response()->json($inspections);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'packages_id' => 'required|exists:packages,id',
            'tanggal' => 'required|date',
            'hasil' => 'required|boolean',
        ]);

        $inspection = PackageInspection::findOrFail($id);

        $inspection->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $inspection,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $inspection = PackageInspection::findOrFail($id);
        $inspection->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        PackageInspection::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
