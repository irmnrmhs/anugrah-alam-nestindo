<?php

namespace App\Http\Controllers;

use App\Exports\EdgeExport;
use App\Models\Document;
use App\Models\Edge;
use App\Models\Employee;
use App\Models\History;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EdgeController extends Controller
{
    public string $obj = 'Sesek Kaki';
    public function index(): View
    {
        $edges = Edge::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR02SK')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.edge', compact('edges', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_sesek
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $edge = Edge::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $edge,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $edge = Edge::findOrFail($id);
        return response()->json($edge);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Edge::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_sesek,
            'hcr_sisa' => $tracker->total_hancuran,
            'last' => $last?->tanggal,
        ]);
    }

    // public function hancuranInfo($rawMaterialId)
    // {
    //     $histories = History::whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
    //         $q->where('id', $rawMaterialId);
    //     })
    //     ->where('tujuan', 'PR02SK')
    //     ->get();

    //     $totalHancuran = $histories->sum('total_hancuran');

    //     $last = Edge::whereIn('histories_id', $histories->pluck('id'))
    //         ->latest()
    //         ->first();

    //     return response()->json([
    //         'hcr_sisa' => $totalHancuran,
    //         'last' => $last?->tanggal,
    //     ]);
    // }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
        ]);

        $edge = Edge::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_sesek + $edge->biji;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $edge->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR02SK')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $edge = Edge::findOrFail($id);
            $edge->delete();

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

        // $rawMaterialId = $history->gcolor->rawMaterial->id;
        $histories = History::with([
                'gcolor.rawMaterial.arrivals.dcertificate.wbhouse',
                'edges.employee'
            ])
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->where('tujuan', 'PR02SK')
            ->get();

        $historyIds = $histories->pluck('id');

        $edges = Edge::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {

            return Excel::download(
                new EdgeExport($edges),
                'Sesek Kaki.xlsx'
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR02SK')
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.edge-form', compact('edges', 'document', 'histories'))
                ->setPaper('A4', 'landscape');

        return $pdf->stream('Sesek_Kaki.pdf');
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Edge::findOrFail($id)->delete();
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
