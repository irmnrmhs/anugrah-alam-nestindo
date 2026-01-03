<?php

namespace App\Http\Controllers;

use App\Models\Soak;
use App\Models\History;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SoakController extends Controller
{
    public string $obj = 'Perendaman';
    public function index(): View
    {
        $soaks = Soak::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR06PR')->get();
        $employees = Employee::with('position')->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.soak', compact('soaks', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_rendam ||
            $validated['berat_masuk'] > $tracker->sisa_berat_rendam
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi biji masuk atau berat masuk',
            ], 422);
        }

        $soak = Soak::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $soak,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $soak = Soak::findOrFail($id);
        return response()->json($soak);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $soak = Soak::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_rendam + $soak->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_rendam + $soak->berat_masuk;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi biji masuk atau berat masuk',
            ], 422);
        }

        $soak->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $soak = Soak::findOrFail($id);
            $soak->delete();

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil dihapus.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data',
            ], 500);
        }
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Soak::findOrFail($id)->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data terpilih berhasil dihapus'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
