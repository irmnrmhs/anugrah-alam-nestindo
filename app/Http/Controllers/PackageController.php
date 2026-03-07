<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    public string $obj = 'Kemasan';
    public function index(): View
    {
        $packages = Package::with('type')->latest()->get();
        $types = PackageType::all();

        return view('production.package', compact('packages', 'types'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'types_id' => 'required|exists:packages_types,id',
            'bahan' => 'required|string',
            'satuan' => 'required|string',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'toleransi' => 'required|numeric',
        ]);

        Package::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $package = Package::with('type')->findOrFail($id);
        return response()->json($package);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'types_id' => 'required|exists:packages_types,id',
            'bahan' => 'required|string',
            'satuan' => 'required|string',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'toleransi' => 'required|numeric',
        ]);

        $package = Package::findOrFail($id);

        $package->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $package,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Package::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
