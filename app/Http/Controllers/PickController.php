<?php

namespace App\Http\Controllers;

use App\Exports\PickExport;
use App\Models\Document;
use App\Models\Employee;
use App\Models\History;
use App\Models\Pick;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PickController extends Controller
{
    public string $obj = 'Pencabutan Bulu';
    public function index(): View
    {
        $picks = Pick::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR05PB')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.pick', compact('picks', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'keterangan' => 'nullable',
            'shift' => 'required',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_cabut
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $pick = Pick::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $pick,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $pick = Pick::findOrFail($id);
        return response()->json($pick);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Pick::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_cabut,
            'last' => $last?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'keterangan' => 'nullable',
            'shift' => 'required',
        ]);

        $pick = Pick::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_cabut + $pick->biji_masuk;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $pick->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR05PB')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $pick = Pick::findOrFail($id);
            $pick->delete();

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
                'edges.employee'
            ])
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->where('tujuan', 'PR05PB')
            ->get();

        $historyIds = $histories->pluck('id');

        $picks = Pick::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {

            return Excel::download(
                new PickExport($picks),
                'Cabut Bulu.xlsx'
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR05PB')
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.pick-form', compact('picks', 'document', 'histories'))
                ->setPaper('A4', 'landscape');

        $filename = 'Pencabutan Bulu.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Pick::findOrFail($id)->delete();
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
