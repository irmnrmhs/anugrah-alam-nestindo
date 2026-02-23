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
        $edges = Edge::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR02SK')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.edge', compact('edges', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'hancuran' => 'nullable|numeric|min:0|max:99999.99',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_sesek ||
            $validated['hancuran'] > $tracker->sisa_berat_sesek
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
            'berat_sisa' => $tracker->sisa_berat_sesek,
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
            'berat' => 'nullable|numeric|min:0|max:99999.99',
        ]);

        $edge = Edge::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_sesek + $edge->biji;
        $berat_sisa = $tracker->sisa_berat_sesek + $edge->berat;

        if(
            $validated['biji'] > $biji_sisa ||
            $validated['berat'] > $berat_sisa
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

    public function export(Request $request, $id)
    {
        $type = $request->get('type', 'pdf');

        $rm = History::with([
            'gcolor.rawMaterial',
            'edges.employee'
        ])->findOrFail($id);

        // $edges = Edge::with(['employee', 'history'])
        //     ->findOrFail($id);

        $edges = Edge::with(['employee', 'history'])
            ->where('histories_id', $id)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {

            $filename = 'Sesek Kaki - ' .
                str_replace(['/', '\\'], '-', $rm->gcolor->rawMaterial->kode) . '.xlsx';

            return Excel::download(
                new EdgeExport($edges),
                $filename
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR02SK')
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.forms.edge-form',
            compact('rm', 'edges', 'document')
        )->setPaper('A4', 'landscape');

        $filename = 'Sesek_Kaki' .
            str_replace(['/', '\\'], '-', $rm->gcolor->rawMaterial->kode) . '.pdf';

        return $pdf->stream($filename);
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
