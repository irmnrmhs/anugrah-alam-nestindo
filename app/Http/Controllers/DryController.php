<?php

namespace App\Http\Controllers;

use App\Exports\DryExport;
use App\Models\Document;
use App\Models\Dry;
use App\Models\Employee;
use App\Models\History;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DryController extends Controller
{
    public string $obj = 'Pengeringan';
    public function index(): View
    {
        $dries = Dry::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR10PK')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.dry', compact('dries', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'waktu_in' => 'nullable|date_format:H:i',
            'waktu_out' => 'nullable|date_format:H:i',
            'shift' => 'required',
        ]);

        $tracker = History::find($validated['histories_id']);
        
        if(
            $validated['biji'] > $tracker->sisa_biji_kering
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $validated['waktu_in'] = $validated['waktu_in'] . ':00';
        $validated['waktu_out'] = $validated['waktu_out']
            ? $validated['waktu_out'] . ':00'
            : null;

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

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Dry::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_kering,
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
            'waktu_in' => 'nullable|date_format:H:i',
            'waktu_out' => 'nullable|date_format:H:i',
            'shift' => 'required'
        ]);

        $dry = Dry::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_kering + $dry->biji_masuk;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $validated['waktu_in'] = $validated['waktu_in'] ?: null;
        $validated['waktu_out'] = $validated['waktu_out'] ?: null;
        
        $dry->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil perbaharui',
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR10PK')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
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
            ->where('tujuan', 'PR10PK')
            ->get();

        $historyIds = $histories->pluck('id');

        $dries = Dry::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {
            return Excel::download(
                new DryExport($dries),
                'Pengeringan.xlsx'
            );
        }
        
        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR10PK')
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.dry-form', compact('dries', 'document', 'histories'))
                ->setPaper('A4', 'portrait');

        $filename = 'Pengeringan.pdf';

        return $pdf->stream($filename);
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
