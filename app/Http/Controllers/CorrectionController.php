<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Correction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CorrectionController extends Controller
{
    public string $obj = 'Inspeksi dan Koreksi';
    public function index(): View
    {
        $corrections = Correction::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR04IK')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.correction', compact('corrections', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji' => 'required|integer|min:0',
            'keterangan' => 'nullable',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji'] > $tracker->sisa_biji_koreksi
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $correction = Correction::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $correction,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $correction = Correction::findOrFail($id);
        return response()->json($correction);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Correction::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_koreksi,
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
        ]);

        $correction = Correction::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_koreksi + $correction->biji;

        if(
            $validated['biji'] > $biji_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        $correction->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $correction = Correction::findOrFail($id);
            $correction->delete();

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
        $corrections = Correction::with(['employee', 'history'])
            ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR04IK')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.correction-form', compact('corrections', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Inspeksi dan koreksi.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Correction::findOrFail($id)->delete();
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
