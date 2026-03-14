<?php

namespace App\Http\Controllers;

use App\Exports\RinseExport;
use App\Models\Document;
use App\Models\Employee;
use App\Models\History;
use App\Models\Rinse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RinseController extends Controller
{
    public string $obj = 'Cabut Bilas';
    public function index(): View
    {
        $rinses = Rinse::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR07CB')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'Produksi');
            })->get();

        return view('production.rinse', compact('rinses', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'shift' => 'required',
            'cek' => 'required',
            'keterangan' => 'nullable'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_bilas
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $rinse = Rinse::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $rinse,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $rinse = Rinse::findOrFail($id);
        return response()->json($rinse);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Rinse::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_bilas,
            'last' => $last?->tgl_mulai,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'shift' => 'required',
            'cek' => 'required',
            'keterangan' => 'nullable'
        ]);

        $rinse = Rinse::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_rendam + $rinse->biji_masuk;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $rinse->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR07CB')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $rinse = Rinse::findOrFail($id);
            $rinse->delete();

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
            ->where('tujuan', 'PR07CB')
            ->get();

        $historyIds = $histories->pluck('id');

        $rinses = Rinse::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {
            return Excel::download(
                new RinseExport($rinses),
                'Cabut Bilas.xlsx'
            );
        }
        
        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR07CB')
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.rinse-form', compact('rinses', 'document', 'histories'))
                ->setPaper('A4', 'portrait');

        $filename = 'Cabut Bilas.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Rinse::findOrFail($id)->delete();
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
