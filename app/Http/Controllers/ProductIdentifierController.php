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
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->latest()->get();
        $rms = RawMaterial::all();
        $grades = Grade::active()->get();

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
        
        $raw = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $raw->kode);

        // $validated['kode'] =  $cleanGrade . '-' . $cleanKode . $supplier->kode;
        $validated['kode'] =  $cleanGrade . '-' . $cleanKode;

        if (
            $validated['biji'] > ($raw->biji - $raw->biji_sisa) ||
            $validated['berat'] > ($raw->berat - $raw->berat_sisa)
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
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
        $raw = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $raw->kode);

        $validated['kode'] = $cleanGrade . '-' . $cleanKode;
        // $validated['kode'] = $cleanGrade . '-' . $cleanKode . $supplier->kode;

        $identifier = ProductIdentifier::findOrFail($id);

        // hitung sisa aktual
        $biji_sisa = $raw->biji_sisa + $grade->biji;
        $berat_sisa = $raw->berat_sisa + $grade->berat;

        if (
            $validated['biji'] > $biji_sisa ||
            $validated['berat'] > $berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

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

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        ProductIdentifier::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
