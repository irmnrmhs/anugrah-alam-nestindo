<?php

namespace App\Http\Controllers;

use App\Models\Dcertificate;
use App\Models\Grade;
use App\Models\ProductIdentifier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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

        // gunakan transaction + lock untuk mencegah race condition
        try {
            $identifier = DB::transaction(function() use ($validated) {
                // lock raw material row
                $rm = RawMaterial::lockForUpdate()->with('arrival')->find($validated['rms_id']);
                if (! $rm) {
                    throw new \Exception('Raw material tidak ditemukan');
                }

                // available dari raw material dikurangi yang sudah dipakai oleh identifiers existing
                $availableBiji = ProductIdentifier::availableBijiForRm($rm->id);
                $availableBerat = ProductIdentifier::availableBeratForRm($rm->id);

                if ($validated['biji'] > $availableBiji || $validated['berat'] > $availableBerat) {
                    throw new \InvalidArgumentException('Melebihi stok sisa');
                }

                // build kode seperti logic asal
                $grade = Grade::findOrFail($validated['grades_id']);
                $supplier = $rm->arrival->dcertificate->supplier->kode ?? '';

                $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
                $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);
                $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
                $validated['kode'] = $kode;

                return ProductIdentifier::create($validated);
            }, 5); // retry 5x on deadlock

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil ditambahkan',
                'data' => $identifier,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // logging bisa ditambahkan
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Terjadi kesalahan',
            ], 500);
        }
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

        try {
            $identifier = DB::transaction(function() use ($validated, $id) {
                $identifier = ProductIdentifier::findOrFail($id);

                // lock both old and new raw material rows to be safe (if rms_id changed)
                $oldRm = RawMaterial::lockForUpdate()->find($identifier->rms_id);
                $newRm = RawMaterial::lockForUpdate()->find($validated['rms_id']);

                if (! $newRm) {
                    throw new \Exception('Raw material tujuan tidak ditemukan');
                }

                // kalau rms_id berubah, kita harus cek kapasitas pada RM baru,
                // dan saat menghitung used identifiers untuk RM baru, exclude current identifier (karena nanti dipindah/diupdate)
                $availableBijiForNewRm = ProductIdentifier::availableBijiForRm($newRm->id, $identifier->id);
                $availableBeratForNewRm = ProductIdentifier::availableBeratForRm($newRm->id, $identifier->id);

                if ($validated['biji'] > $availableBijiForNewRm || $validated['berat'] > $availableBeratForNewRm) {
                    throw new \InvalidArgumentException('Melebihi stok sisa');
                }

                // build kode baru berdasarkan rms_id/grade yang dipilih
                $grade = Grade::findOrFail($validated['grades_id']);
                $supplier = $newRm->arrival->dcertificate->supplier->kode ?? '';

                $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
                $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $newRm->kode);
                $kode =  $cleanGrade . '-' . $cleanKode . $supplier;
                $validated['kode'] = $kode;

                $identifier->update($validated);

                return $identifier->fresh();
            }, 5);

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbarui',
                'data' => $identifier,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Terjadi kesalahan',
            ], 500);
        }
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