<?php

namespace App\Http\Controllers;

use App\Models\Wash;
use App\Models\History;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WashController extends Controller
{
    public string $obj = 'Pencucian';
    public function index(): View
    {
        $washes = Wash::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR03PC')->get();

        $employees = Employee::all();

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
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_cuci ||
            $validated['berat_masuk'] > $tracker->sisa_berat_cuci
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
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
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99'
        ]);

        $wash = Wash::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_cuci + $wash->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_cuci + $wash->berat_keluar;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
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
        $wash = Wash::findOrFail($id);
        $wash->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Wash::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
