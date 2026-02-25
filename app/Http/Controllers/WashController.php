<?php

namespace App\Http\Controllers;

use App\Exports\WashExport;
use App\Models\Document;
use App\Models\Employee;
use App\Models\History;
use App\Models\Wash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class WashController extends Controller
{
    public string $obj = 'Pencucian';
    public function index(): View
    {
        $washes = Wash::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR03PC')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.wash', compact('washes', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji_in' => 'required|integer|min:0',
            'biji_out' => 'required|integer|min:0',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_in'] > $tracker->sisa_biji_cuci
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_out'] > $validated['biji_in']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Stok keluar melebihi stok masuk',
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

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Wash::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_cuci,
            'last' => $last?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji_in' => 'required|integer|min:0',
            'biji_out' => 'required|integer|min:0',
        ]);

        $wash = Wash::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $masuk_sisa = $tracker->sisa_biji_cuci + $wash->biji_in;

        if(
            $validated['biji_in'] > $masuk_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_out'] > $validated['biji_in']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Stok keluar melebihi stok masuk',
            ], 422);
        }
        
        $wash->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR03PC')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
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

    public function export(Request $request, $rawMaterialId)
    {
        $type = $request->get('type', 'pdf');
        
        $histories = History::with([
                'gcolor.rawMaterial.arrivals.dcertificate.wbhouse',
                'washes.employee'
            ])
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->where('tujuan', 'PR03PC')
            ->get();

        $historyIds = $histories->pluck('id');

        $washes = Wash::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {

            return Excel::download(
                new WashExport($washes),
                'Pencucian.xlsx'
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR03PC')
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.forms.wash-form',
            compact('washes', 'document', 'histories')
        )->setPaper('A4', 'landscape');

        return $pdf->stream('Pencucian.pdf');
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
