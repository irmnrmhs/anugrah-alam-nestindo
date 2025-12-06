<?php

namespace App\Http\Controllers;

use App\Models\Dcertificate;
use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
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

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);
        $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
        $validated['kode'] = $kode;

        $identifier = ProductIdentifier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $identifier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $identifier = ProductIdentifier::findOrFail($id);
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

        $rm = RawMaterial::with('arrival')->find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);
        $supplier = $rm->arrival->dcertificate->supplier->kode;

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);
        $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
        $validated['kode'] = $kode;

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
