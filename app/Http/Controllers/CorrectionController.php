<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use App\Models\History;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CorrectionController extends Controller
{
    public string $obj = 'Inspeksi dan Koreksi';
    public function index(): View
    {
        $corrections = Correction::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR04IK')->get();
        $employees = Employee::all();

        return view('production.correction', compact('corrections', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_koreksi ||
            $validated['berat_masuk'] > $tracker->sisa_berat_koreksi
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $correction = Correction::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $correction,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $correction = Correction::findOrFail($id);
        return response()->json($correction);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $correction = Correction::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_koreksi + $correction->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_koreksi + $correction->berat_masuk;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $correction->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $correction = Correction::findOrFail($id);
        $correction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Correction::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
