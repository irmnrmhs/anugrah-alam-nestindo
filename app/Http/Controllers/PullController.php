<?php

namespace App\Http\Controllers;

use App\Models\Pull;
use App\Models\History;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PullController extends Controller
{
    public string $obj = 'Cetak Keluar';
    public function index(): View
    {
        $pulls = Pull::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR09KC')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.pull', compact('pulls', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_masuk'] > $tracker->sisa_biji_keluar ||
            $validated['berat_masuk'] > $tracker->sisa_berat_keluar
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Biji atau berat setelah proses melebihi biji atau berat sebelum proses',
            ], 422);
        }

        $pull = Pull::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $pull,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $pull = Pull::findOrFail($id);
        return response()->json($pull);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Pull::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_keluar,
            'berat_sisa' => $tracker->sisa_berat_keluar,
            'last' => $last?->tgl_mulai,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'nullable|date',
            'biji_keluar' => 'nullable|integer|min:0',
            'berat_keluar' => 'nullable|numeric|min:0|max:99999.99',
            'shift' => 'required',
            'keterangan' => 'nullable'
        ]);

        $pull = Pull::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $biji_sisa = $tracker->sisa_biji_keluar + $pull->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_keluar + $pull->berat_masuk;

        if(
            $validated['biji_masuk'] > $biji_sisa ||
            $validated['berat_masuk'] > $berat_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_keluar'] > $validated['biji_masuk'] ||
            $validated['berat_keluar'] > $validated['berat_masuk']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Biji atau berat setelah proses melebihi biji atau berat sebelum proses',
            ], 422);
        }

        $pull->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $pull = Pull::findOrFail($id);
            $pull->delete();

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
        $pulls = Pull::findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR09KC')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.pull-form', compact('pulls', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Keluar Cetak.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Pull::findOrFail($id)->delete();
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
