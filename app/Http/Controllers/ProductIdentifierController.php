<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductIdentifierController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $identifiers = ProductIdentifier::with('supplier', 'rawMaterial', 'grade')->oldest()->get();
        $suppliers = Supplier::all();
        $rms = RawMaterial::all();
        $grades = Grade::all();

        return view('raw-material.productIdentifier', compact('identifiers', 'suppliers', 'rms', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'suppliers_id' => 'required|exists:suppliers,id',
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            // 'kode' => 'required|string|max:50|unique:product_identifiers,kode',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $supplier = Supplier::find($validated['suppliers_id']);
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $supplier->kode . $cleanGrade . '-' . $cleanKode;

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::with('supplier', 'rawMaterial', 'grade')->findOrFail($id);
        return response()->json($identifier);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'suppliers_id' => 'required|exists:suppliers,id',
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            // 'kode' => 'required|string|max:50|unique:product_identifiers,kode,' . $id,
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        $supplier = Supplier::find($validated['suppliers_id']);
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $supplier->kode . $cleanGrade . '-' . $cleanKode;

        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $identifier,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
        $identifier->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
