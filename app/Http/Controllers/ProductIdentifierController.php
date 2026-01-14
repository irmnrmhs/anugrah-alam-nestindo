<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Supplier;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\ProductIdentifier;
use Illuminate\Http\JsonResponse;

class ProductIdentifierController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $identifiers = ProductIdentifier::with('rawMaterial', 'grade')->latest()->get();
        
        // $rms = RawMaterial::all();
        $rms = RawMaterial::whereHas('stocks', function ($q) {
            $q->where('biji_keluar', '>', 0)
            ->orWhere('berat_keluar', '>', 0);
        })->get();

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

        $supplier = Supplier::find($validated['suppliers_id']);
        
        $rm = RawMaterial::find($validated['rms_id']);
        $grade = Grade::find($validated['grades_id']);

        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $validated['kode'] =  $cleanGrade . '-' . $cleanKode . $supplier->kode;
        // $validated['kode'] =  $cleanGrade . '-' . $cleanKode;

        if (
            $validated['biji'] > $rm->biji_sisa_identifier ||
            $validated['berat'] > $rm->berat_sisa_identifier
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok tersedia',
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

    public function materialInfo($id)
    {
        $raw = RawMaterial::findOrFail($id);
        $lastOut = ProductIdentifier::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $raw->biji_sisa_identifier,
            'berat_sisa' => $raw->berat_sisa_identifier,
            'last_date' => $lastOut?->tgl_keluar,
        ]);
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

        $availableBiji  = $rm->biji_sisa_identifier + $identifier->biji;
        $availableBerat = $rm->berat_sisa_identifier + $identifier->berat;

        if (
            $validated['biji'] > $availableBiji ||
            $validated['berat'] > $availableBerat
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
}