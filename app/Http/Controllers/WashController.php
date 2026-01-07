<?php

namespace App\Http\Controllers;

use App\Models\Wash;
use App\Models\History;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class WashController extends Controller
{
    public string $obj = 'Pencucian';
    public function index(): View
    {
        $washes = Wash::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR03PC')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.wash', compact('washes', 'histories', 'employees'));
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
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_koreksi ||
            $validated['berat_masuk'] > $tracker->sisa_berat_koreksi
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan lainnya',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Biji atau berat keluar melebihi biji atau berat masuk',
            ], 422);
        }

        $wash = Wash::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $wash,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $wash = Wash::findOrFail($id);
        return response()->json($wash);
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
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99'
        ]);

        $wash = Wash::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_cuci + $wash->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_cuci + $wash->berat_masuk;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan lainnya',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Biji atau berat keluar melebihi biji atau berat masuk',
            ], 422);
        }

        if(
            $validated['biji_keluar'] < $tracker->total_biji_koreksi ||
            $validated['berat_keluar'] < $tracker->total_berat_koreksi
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Biji atau berat keluar tidak boleh lebih kecil dari stok yang sedang diproses pada tahapan lain.',
            ], 422);
        }

        $wash->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $wash,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $wash = Wash::findOrFail($id);
            $wash->delete();

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
                Wash::findOrFail($id)->delete();
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
