<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Edge;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\History;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EdgeController extends Controller
{
    public string $obj = 'Sesek Kaki';
    public function index(): View
    {
        $edges = Edge::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR02SK')->get();
        $grades = Grade::where('status', 1)->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.edge', compact('edges', 'histories', 'employees', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|array',
            'berat' => 'required|array',
        ]);

        $total_biji = array_sum($request->biji);
        $total_berat = array_sum($request->berat);

        $tracker = History::find($validated['histories_id']);

        if(
            $total_biji > $tracker->sisa_biji_sesek ||
            $total_berat > $tracker->sisa_berat_sesek
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        Edge::create([
            'histories_id' => $validated['histories_id'],
            'employees_id' => $validated['employees_id'],
            'tanggal' => $validated['tanggal'],
            'biji' => $total_biji,
            'berat' => $total_berat,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $edge = Edge::findOrFail($id);
        return response()->json($edge);
    }

    public function info($id)
    {
        // $tracker = History::findOrFail($id);
        $tracker = History::with('gcolor.rawMaterial')->findOrFail($id);
        $last = Edge::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_sesek,
            'berat_sisa' => $tracker->sisa_berat_sesek,
            'last' => $last?->tanggal,
            'kode_bahan_baku' => $tracker->gcolor->rawMaterial->kode,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
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

    public function export($id)
    {
        $edges = Edge::with(['employee', 'history'])
            ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR02SK')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.edge-form', compact('edges', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Sesek Kaki.pdf';

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
