<?php

namespace App\Http\Controllers;

use App\Exports\EntryExport;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\History;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EntryController extends Controller
{
    public string $obj = 'Cetak Masuk';
    public function index(): View
    {
        $entries = Entry::with(['history.gcolor.rawMaterial', 'employee'])->latest()->get();
        $histories = History::with('gcolor.rawMaterial')->where('tujuan', 'PR08MC')->get();
        $rms = $histories->pluck('gcolor.rawMaterial')->unique('id')->values();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.entry', compact('entries', 'histories', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'shift' => 'required',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_entry
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $entry = Entry::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $entry,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $entry = Entry::findOrFail($id);
        return response()->json($entry);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Entry::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_entry,
            'berat_sisa' => $tracker->sisa_berat_entry,
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
        ]);

        $entry = Entry::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_entry + $entry->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_entry + $entry->berat_masuk;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $entry->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function getGrades($rawMaterialId)
    {
        $histories = History::with('gcolor')
            ->where('tujuan', 'PR08MC')
            ->whereHas('gcolor.rawMaterial', function ($q) use ($rawMaterialId) {
                $q->where('id', $rawMaterialId);
            })
            ->get();

        return response()->json($histories);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $entry = Entry::findOrFail($id);
            $entry->delete();

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
            ->where('tujuan', 'PR08MC')
            ->get();

        $historyIds = $histories->pluck('id');

        $entries = Entry::with([
                'employee',
                'history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse'
            ])
            ->whereIn('histories_id', $historyIds)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {
            return Excel::download(
                new EntryExport($entries),
                'Masuk Cetak.xlsx'
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR08MC')
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.entry-form', compact('entries', 'document', 'histories'))
                ->setPaper('A4', 'landscape');

        $filename = 'Masuk Cetak.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Entry::findOrFail($id)->delete();
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
