<?php

namespace App\Http\Controllers;

use App\Models\Dry;
use App\Models\History;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DryController extends Controller
{
    public string $obj = 'Pengeringan';
    public function index(): View
    {
        $dries = Dry::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR10PK')->get();
        $employees = Employee::with('position')->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.dry', compact('dries', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'waktu_masuk' => 'required|date_format:H:i',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable',
            'shift' => 'required'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_kering ||
            $validated['berat_masuk'] > $tracker->sisa_berat_kering
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

        $validated['waktu_keluar'] = $validated['waktu_keluar'] ?: null;

        $dry = Dry::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $dry,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $dry = Dry::findOrFail($id);
        return response()->json($dry);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'waktu_masuk' => 'required|date_format:H:i',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable',
            'shift' => 'required'
        ]);

        $dry = Dry::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_kering + $dry->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_kering + $dry->berat_masuk;

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

        $validated['waktu_keluar'] = $validated['waktu_keluar'] ?: null;
        
        $dry->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil perbaharui',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $dry = Dry::findOrFail($id);
            $dry->delete();

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
                Dry::findOrFail($id)->delete();
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
