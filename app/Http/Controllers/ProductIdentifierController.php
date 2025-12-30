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
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->oldest()->get();
        $rms = RawMaterial::all();
        $grades = Grade::all();

        return view('raw-material.productIdentifier', compact('identifiers', 'rms', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        // $supplier = Supplier::find($validated['suppliers_id']);
        
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        // $validated['kode'] =  $cleanGrade . '-' . $cleanKode . $supplier->kode;
        $validated['kode'] =  $cleanGrade . '-' . $cleanKode;

        if (
            $validated['biji'] < $rm->biji_sisa_identifier ||
            $validated['berat'] < $rm->berat_sisa_identifier
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::with('rawMaterial', 'grade')->findOrFail($id);
        return response()->json($identifier);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'grades_id' => 'required|exists:grades,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99'
        ]);

        // $supplier = Supplier::find($validated['suppliers_id']);
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] = $cleanGrade . '-' . $cleanKode;
        // $validated['kode'] = $cleanGrade . '-' . $cleanKode . $supplier->kode;

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