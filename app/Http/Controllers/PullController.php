<?php

namespace App\Http\Controllers;

use App\Models\Pull;
use App\Models\History;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PullController extends Controller
{
    public string $obj = 'Cetak Keluar';
    public function index(): View
    {
        $pulls = Pull::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR08MC')->get();
        $employees = Employee::all();

        return view('production.correction', compact('pulls', 'histories', 'employees'));
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
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_keluar ||
            $validated['berat_masuk'] > $tracker->sisa_berat_keluar
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $pull = Pull::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $pull,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $pull = Pull::findOrFail($id);
        return response()->json($pull);
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
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $pull = Pull::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_masuk + $pull->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_masuk + $pull->berat_masuk;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $pull->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $pull = Pull::findOrFail($id);
        $pull->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Pull::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
