<?php

namespace App\Http\Controllers;

use App\Models\Dry;
use App\Models\History;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DryController extends Controller
{
    public string $obj = 'Pengeringan';
    public function index(): View
    {
        $dries = Dry::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR10PK')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.dry', compact('dries', 'histories', 'employees'));
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
            'keterangan' => 'nullable',
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
            'waktu_in' => 'nullable|date_format:H:i',
            'waktu_out' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable',
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

    public function export($id)
    {
        $dries = Dry::with(['employee', 'history'])
            ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR10PK')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.dry-form', compact('dries', 'document'))
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
